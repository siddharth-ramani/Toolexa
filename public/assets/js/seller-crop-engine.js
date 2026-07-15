/*
 * Toolexa Seller Label Crop Engine
 * Interactive, config-driven PDF label cropper for marketplace seller tools.
 * Rendering: pdf.js (preview). Export: pdf-lib (vector PDF crop), canvas (PNG crop).
 * Everything runs locally in the browser. No file is ever uploaded or stored.
 *
 * Adding a new marketplace only requires a new entry in MARKETPLACE_PRESETS
 * below plus a matching seller tool route/slug — the engine itself never changes.
 */
(function () {
    'use strict';

    if (typeof document === 'undefined') {
        return;
    }

    var MAX_PAGES = 300;
    var SOFT_PAGE_WARNING = 50;
    var RENDER_SCALE = 3;
    var MIN_ZOOM = 0.25;
    var MAX_ZOOM = 4;
    var HISTORY_LIMIT = 40;
    var MIN_SELECTION = 8;

    var PDFJS_VERSION = '3.11.174';
    var PDFJS_SCRIPT = 'https://unpkg.com/pdfjs-dist@' + PDFJS_VERSION + '/build/pdf.min.js';
    var PDFJS_WORKER = 'https://unpkg.com/pdfjs-dist@' + PDFJS_VERSION + '/build/pdf.worker.min.js';

    var CROP_TYPE_LABELS = {
        shipping_label: 'Shipping Label',
        invoice: 'Invoice',
        barcode_area: 'Barcode Area',
        packing_slip: 'Packing Slip'
    };

    /* ------------------------------------------------------------------
     * Marketplace preset configuration.
     * Every layout maps to a set of crop-type regions expressed as
     * fractions (0-1) of the page's own width/height, so the same
     * region works regardless of the PDF's actual point size.
     * A `null` region means that crop type is not meaningful for that
     * layout (e.g. a thermal label has no separate invoice section) --
     * the UI falls back to leaving the current selection untouched.
     * ------------------------------------------------------------------ */
    var MARKETPLACE_PRESETS = {
        'meesho-label-cropper': {
            layouts: {
                a4_single: {
                    shipping_label: { left: .05, top: .04, width: .90, height: .45 },
                    barcode_area: { left: .30, top: .07, width: .40, height: .16 },
                    invoice: { left: .05, top: .51, width: .90, height: .45 },
                    packing_slip: { left: .05, top: .51, width: .90, height: .45 }
                },
                a4_double: {
                    shipping_label: { left: .05, top: .03, width: .90, height: .22 },
                    barcode_area: { left: .30, top: .05, width: .40, height: .09 },
                    invoice: { left: .05, top: .28, width: .90, height: .20 },
                    packing_slip: { left: .05, top: .53, width: .90, height: .22 }
                },
                thermal_4x6: {
                    shipping_label: { left: 0, top: 0, width: 1, height: 1 },
                    barcode_area: { left: .12, top: .38, width: .76, height: .22 },
                    invoice: null,
                    packing_slip: null
                },
                custom: { shipping_label: null, barcode_area: null, invoice: null, packing_slip: null }
            }
        },
        'amazon-label-cropper': {
            layouts: {
                a4: {
                    shipping_label: { left: .06, top: .05, width: .88, height: .42 },
                    barcode_area: { left: .32, top: .08, width: .36, height: .14 },
                    invoice: { left: .05, top: .50, width: .90, height: .46 },
                    packing_slip: { left: .05, top: .50, width: .90, height: .46 }
                },
                thermal: {
                    shipping_label: { left: 0, top: 0, width: 1, height: 1 },
                    barcode_area: { left: .12, top: .35, width: .76, height: .24 },
                    invoice: null,
                    packing_slip: null
                },
                custom: { shipping_label: null, barcode_area: null, invoice: null, packing_slip: null }
            }
        },
        'flipkart-label-cropper': {
            layouts: {
                a4: {
                    shipping_label: { left: .06, top: .06, width: .88, height: .44 },
                    barcode_area: { left: .32, top: .09, width: .36, height: .15 },
                    invoice: { left: .05, top: .52, width: .90, height: .44 },
                    packing_slip: { left: .05, top: .52, width: .90, height: .44 }
                },
                thermal: {
                    shipping_label: { left: 0, top: 0, width: 1, height: 1 },
                    barcode_area: { left: .12, top: .36, width: .76, height: .24 },
                    invoice: null,
                    packing_slip: null
                },
                custom: { shipping_label: null, barcode_area: null, invoice: null, packing_slip: null }
            }
        },
        'myntra-label-cropper': {
            layouts: {
                a4: {
                    shipping_label: { left: .07, top: .08, width: .86, height: .46 },
                    barcode_area: { left: .32, top: .12, width: .36, height: .16 },
                    invoice: { left: .05, top: .55, width: .90, height: .42 },
                    packing_slip: { left: .05, top: .55, width: .90, height: .42 }
                },
                thermal: {
                    shipping_label: { left: 0, top: 0, width: 1, height: 1 },
                    barcode_area: { left: .12, top: .37, width: .76, height: .23 },
                    invoice: null,
                    packing_slip: null
                },
                custom: { shipping_label: null, barcode_area: null, invoice: null, packing_slip: null }
            }
        },
        'ajio-label-cropper': {
            layouts: {
                a4: {
                    shipping_label: { left: .07, top: .07, width: .86, height: .45 },
                    barcode_area: { left: .32, top: .10, width: .36, height: .15 },
                    invoice: { left: .05, top: .54, width: .90, height: .43 },
                    packing_slip: { left: .05, top: .54, width: .90, height: .43 }
                },
                thermal: {
                    shipping_label: { left: 0, top: 0, width: 1, height: 1 },
                    barcode_area: { left: .12, top: .36, width: .76, height: .24 },
                    invoice: null,
                    packing_slip: null
                },
                custom: { shipping_label: null, barcode_area: null, invoice: null, packing_slip: null }
            }
        }
    };

    function clamp(value, min, max) {
        return Math.max(min, Math.min(max, value));
    }

    function bytesLabel(size) {
        if (!size) return '0 KB';
        if (size < 1024 * 1024) return Math.round(size / 1024) + ' KB';
        return (size / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function describePageSize(width, height) {
        var wIn = (width / 72).toFixed(2);
        var hIn = (height / 72).toFixed(2);
        var label = Math.round(width) + ' x ' + Math.round(height) + ' pt (' + wIn + '" x ' + hIn + '")';

        if (Math.abs(width - 595) < 4 && Math.abs(height - 842) < 4) label += ' — A4';
        else if (Math.abs(width - 612) < 4 && Math.abs(height - 792) < 4) label += ' — US Letter';
        else if (Math.abs(width - 288) < 5 && Math.abs(height - 432) < 5) label += ' — 4x6in Thermal';
        else if (Math.abs(width - 432) < 5 && Math.abs(height - 288) < 5) label += ' — 6x4in Thermal';

        return label;
    }

    var pdfJsReadyPromise = null;

    function loadPdfJsScript() {
        if (window.pdfjsLib) {
            return Promise.resolve(window.pdfjsLib);
        }

        return new Promise(function (resolve, reject) {
            var script = document.createElement('script');
            script.src = PDFJS_SCRIPT;
            script.onload = function () {
                if (!window.pdfjsLib) {
                    reject(new Error('pdf.js failed to initialise.'));
                    return;
                }
                resolve(window.pdfjsLib);
            };
            script.onerror = function () { reject(new Error('Could not load the PDF rendering library.')); };
            document.head.appendChild(script);
        });
    }

    // Workers created from a cross-origin CDN URL are blocked or silently
    // hang in some browsers. Fetching the worker script and running it from
    // a same-origin blob: URL sidesteps that entirely. If the fetch itself
    // fails (offline, blocked host), we still fall back to the direct CDN
    // URL so pdf.js can try its own internal fallback.
    function ensureWorker(pdfjsLib) {
        if (pdfjsLib.GlobalWorkerOptions.workerSrc) {
            return Promise.resolve(pdfjsLib);
        }

        return fetch(PDFJS_WORKER)
            .then(function (response) {
                if (!response.ok) throw new Error('worker fetch failed');
                return response.blob();
            })
            .then(function (blob) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = URL.createObjectURL(blob);
                return pdfjsLib;
            })
            .catch(function () {
                pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_WORKER;
                return pdfjsLib;
            });
    }

    function ensurePdfJs() {
        if (!pdfJsReadyPromise) {
            pdfJsReadyPromise = loadPdfJsScript().then(ensureWorker).catch(function (error) {
                pdfJsReadyPromise = null;
                throw error;
            });
        }
        return pdfJsReadyPromise;
    }

    function withTimeout(promise, ms, message) {
        return new Promise(function (resolve, reject) {
            var settled = false;
            var timer = setTimeout(function () {
                if (settled) return;
                settled = true;
                reject({ friendly: message });
            }, ms);

            promise.then(function (value) {
                if (settled) return;
                settled = true;
                clearTimeout(timer);
                resolve(value);
            }, function (error) {
                if (settled) return;
                settled = true;
                clearTimeout(timer);
                reject(error);
            });
        });
    }

    /* ------------------------------------------------------------------
     * CropEngine: one instance per tool element. Owns PDF loading/
     * rendering, zoom, the crop selection state and undo/redo history.
     * Pure state + rendering -- no DOM event wiring lives here so it can
     * be reused by any marketplace tool without modification.
     * ------------------------------------------------------------------ */
    function createCropEngine(canvas) {
        var ctx = canvas.getContext('2d');
        var pdfDoc = null;
        var pdfFile = null;
        var naturalWidth = 0;
        var naturalHeight = 0;
        var pagePointSize = { width: 0, height: 0 };
        var currentPageNumber = 1;
        var zoom = 1;
        var selection = null;
        var history = [];
        var historyIndex = -1;

        function snapshot() {
            return selection ? { x: selection.x, y: selection.y, width: selection.width, height: selection.height } : null;
        }

        function pushHistory() {
            history = history.slice(0, historyIndex + 1);
            history.push(snapshot());
            if (history.length > HISTORY_LIMIT) history.shift();
            historyIndex = history.length - 1;
        }

        function toFraction(rect) {
            return {
                left: rect.x / naturalWidth,
                top: rect.y / naturalHeight,
                width: rect.width / naturalWidth,
                height: rect.height / naturalHeight
            };
        }

        function fromFraction(fraction) {
            return {
                x: clamp(fraction.left * naturalWidth, 0, naturalWidth),
                y: clamp(fraction.top * naturalHeight, 0, naturalHeight),
                width: clamp(fraction.width * naturalWidth, MIN_SELECTION, naturalWidth),
                height: clamp(fraction.height * naturalHeight, MIN_SELECTION, naturalHeight)
            };
        }

        function defaultSelection() {
            return {
                x: naturalWidth * 0.1,
                y: naturalHeight * 0.1,
                width: naturalWidth * 0.8,
                height: naturalHeight * 0.8
            };
        }

        async function loadFile(file) {
            if (!/\.pdf$/i.test(file.name) && file.type !== 'application/pdf') {
                throw { friendly: 'Please choose a PDF file.' };
            }

            var pdfjsLib;
            try {
                pdfjsLib = await withTimeout(ensurePdfJs(), 15000, 'The PDF rendering library did not load in time. Check your connection and try again.');
            } catch (error) {
                console.error('Toolexa seller crop: pdf.js failed to load', error);
                throw (error && error.friendly) ? error : { friendly: 'Could not load the PDF rendering library. Check your connection and try again.' };
            }

            var arrayBuffer = await file.arrayBuffer();
            var doc;

            try {
                doc = await withTimeout(pdfjsLib.getDocument({ data: arrayBuffer.slice(0) }).promise, 20000, 'This PDF took too long to open. It may be too large or complex for browser rendering.');
            } catch (error) {
                console.error('Toolexa seller crop: pdf.js failed to open document', error);
                if (error && error.friendly) {
                    throw error;
                }
                if (error && error.name === 'PasswordException') {
                    throw { friendly: 'This PDF is password protected. Remove the password and try again.' };
                }
                if (error && error.name === 'InvalidPDFException') {
                    throw { friendly: 'This file does not look like a valid PDF.' };
                }
                throw { friendly: 'This PDF could not be opened. It may be corrupted or use an unsupported format.' };
            }

            if (doc.numPages > MAX_PAGES) {
                throw { friendly: 'This PDF has ' + doc.numPages + ' pages, which is more than Toolexa can process in the browser (' + MAX_PAGES + ' max). Split it into smaller files first.' };
            }

            pdfDoc = doc;
            pdfFile = file;
            selection = null;
            history = [];
            historyIndex = -1;

            await renderPage(1);

            return {
                pageCount: doc.numPages,
                pageSize: describePageSize(pagePointSize.width, pagePointSize.height),
                fileSize: bytesLabel(file.size),
                fileName: file.name,
                largeFile: doc.numPages > SOFT_PAGE_WARNING
            };
        }

        async function renderPage(pageNumber) {
            var page = await pdfDoc.getPage(pageNumber);
            var baseViewport = page.getViewport({ scale: 1 });
            pagePointSize = { width: baseViewport.width, height: baseViewport.height };

            var viewport = page.getViewport({ scale: RENDER_SCALE });
            canvas.width = Math.ceil(viewport.width);
            canvas.height = Math.ceil(viewport.height);
            naturalWidth = canvas.width;
            naturalHeight = canvas.height;

            await page.render({ canvasContext: ctx, viewport: viewport }).promise;
            currentPageNumber = pageNumber;
            applyZoom(zoom || 1);
        }

        async function goToPage(pageNumber) {
            if (!pdfDoc || pageNumber < 1 || pageNumber > pdfDoc.numPages) return;
            var fraction = selection ? toFraction(selection) : null;
            await renderPage(pageNumber);
            selection = fraction ? fromFraction(fraction) : null;
        }

        function applyZoom(nextZoom) {
            zoom = clamp(nextZoom, MIN_ZOOM, MAX_ZOOM);
            canvas.style.width = Math.round(naturalWidth * zoom) + 'px';
            canvas.style.height = Math.round(naturalHeight * zoom) + 'px';
            return zoom;
        }

        function setLayout(marketplace, layoutKey) {
            var preset = MARKETPLACE_PRESETS[marketplace];
            var region = preset && preset.layouts[layoutKey] ? preset.layouts[layoutKey].shipping_label : null;
            selection = region ? fromFraction(region) : defaultSelection();
            pushHistory();
            return selection;
        }

        function applyPreset(marketplace, layoutKey, cropType) {
            var preset = MARKETPLACE_PRESETS[marketplace];
            var layout = preset ? preset.layouts[layoutKey] : null;
            var region = layout ? layout[cropType] : null;

            if (!region) {
                return { applied: false };
            }

            selection = fromFraction(region);
            pushHistory();
            return { applied: true };
        }

        function setSelectionPixels(rect, commit) {
            selection = {
                x: clamp(rect.x, 0, naturalWidth),
                y: clamp(rect.y, 0, naturalHeight),
                width: clamp(rect.width, MIN_SELECTION, naturalWidth),
                height: clamp(rect.height, MIN_SELECTION, naturalHeight)
            };
            if (commit) pushHistory();
        }

        function resetSelection() {
            selection = defaultSelection();
            pushHistory();
            return selection;
        }

        function undo() {
            if (historyIndex <= 0) return false;
            historyIndex -= 1;
            selection = history[historyIndex] ? Object.assign({}, history[historyIndex]) : null;
            return true;
        }

        function redo() {
            if (historyIndex >= history.length - 1) return false;
            historyIndex += 1;
            selection = history[historyIndex] ? Object.assign({}, history[historyIndex]) : null;
            return true;
        }

        async function exportPdf() {
            if (!selection || !pdfFile || !window.PDFLib) {
                throw { friendly: 'Draw or choose a crop area first.' };
            }

            var fraction = toFraction(selection);
            var arrayBuffer = await pdfFile.arrayBuffer();
            var srcDoc = await window.PDFLib.PDFDocument.load(arrayBuffer);
            var outDoc = await window.PDFLib.PDFDocument.create();
            var pages = srcDoc.getPages();

            for (var i = 0; i < pages.length; i++) {
                var page = pages[i];
                var pageWidth = page.getWidth();
                var pageHeight = page.getHeight();
                var left = fraction.left * pageWidth;
                var top = fraction.top * pageHeight;
                var width = fraction.width * pageWidth;
                var height = fraction.height * pageHeight;

                var box = {
                    left: left,
                    bottom: Math.max(0, pageHeight - top - height),
                    right: Math.min(pageWidth, left + width),
                    top: Math.min(pageHeight, pageHeight - top)
                };

                var embedded = await outDoc.embedPage(page, box);
                var outPage = outDoc.addPage([embedded.width, embedded.height]);
                outPage.drawPage(embedded, { x: 0, y: 0, width: embedded.width, height: embedded.height });
            }

            return outDoc.save();
        }

        function exportPng() {
            if (!selection) {
                return Promise.reject({ friendly: 'Draw or choose a crop area first.' });
            }

            var out = document.createElement('canvas');
            out.width = Math.max(1, Math.round(selection.width));
            out.height = Math.max(1, Math.round(selection.height));
            out.getContext('2d').drawImage(
                canvas,
                selection.x, selection.y, selection.width, selection.height,
                0, 0, out.width, out.height
            );

            return new Promise(function (resolve, reject) {
                out.toBlob(function (blob) {
                    if (!blob) { reject({ friendly: 'Could not export this crop as an image.' }); return; }
                    resolve(blob);
                }, 'image/png');
            });
        }

        return {
            loadFile: loadFile,
            goToPage: goToPage,
            applyZoom: applyZoom,
            setLayout: setLayout,
            applyPreset: applyPreset,
            setSelectionPixels: setSelectionPixels,
            resetSelection: resetSelection,
            undo: undo,
            redo: redo,
            exportPdf: exportPdf,
            exportPng: exportPng,
            hasDocument: function () { return !!pdfDoc; },
            getSelection: function () { return selection ? Object.assign({}, selection) : null; },
            getZoom: function () { return zoom; },
            getNaturalSize: function () { return { width: naturalWidth, height: naturalHeight }; },
            getPageCount: function () { return pdfDoc ? pdfDoc.numPages : 0; },
            getCurrentPage: function () { return currentPageNumber; }
        };
    }

    /* ------------------------------------------------------------------
     * DOM wiring -- one block per [data-seller-crop-tool] element.
     * ------------------------------------------------------------------ */
    function initTool(tool) {
        var mode = tool.getAttribute('data-seller-mode');
        var preset = MARKETPLACE_PRESETS[mode];
        if (!preset) return;

        var fileInput = tool.querySelector('[data-crop-file-input]');
        var dropzone = tool.querySelector('[data-crop-dropzone]');
        var fileInfo = tool.querySelector('[data-crop-file-info]');
        var pageSizeEl = tool.querySelector('[data-crop-page-size]');
        var pageCountEl = tool.querySelector('[data-crop-page-count]');
        var fileSizeEl = tool.querySelector('[data-crop-file-size]');

        var layoutOptions = tool.querySelectorAll('[data-crop-layout-option]');
        var presetButtons = tool.querySelectorAll('[data-crop-preset]');

        var stage = tool.querySelector('[data-crop-stage]');
        var wrap = tool.querySelector('[data-crop-canvas-wrap]');
        var canvas = tool.querySelector('[data-crop-pdf-canvas]');
        var selectionEl = tool.querySelector('[data-crop-selection]');
        var handles = tool.querySelectorAll('[data-crop-handle]');
        var emptyState = tool.querySelector('[data-crop-empty]');

        var zoomRange = tool.querySelector('[data-crop-zoom-range]');
        var zoomValueEl = tool.querySelector('[data-crop-zoom-value]');
        var fitWidthBtn = tool.querySelector('[data-crop-fit-width]');
        var fitHeightBtn = tool.querySelector('[data-crop-fit-height]');
        var panToggle = tool.querySelector('[data-crop-pan-toggle]');
        var resetBtn = tool.querySelector('[data-crop-reset]');
        var undoBtn = tool.querySelector('[data-crop-undo]');
        var redoBtn = tool.querySelector('[data-crop-redo]');
        var aspectLock = tool.querySelector('[data-crop-aspect-lock]');

        var prevPageBtn = tool.querySelector('[data-crop-prev-page]');
        var nextPageBtn = tool.querySelector('[data-crop-next-page]');
        var pageIndicator = tool.querySelector('[data-crop-page-indicator]');

        var exportFormatInputs = tool.querySelectorAll('[data-crop-export-format]');
        var exportBtn = tool.querySelector('[data-crop-export]');
        var downloadBtn = tool.querySelector('[data-crop-download]');
        var clearBtn = tool.querySelector('[data-crop-clear]');
        var statusEl = tool.querySelector('[data-crop-status]');
        var errorEl = tool.querySelector('[data-crop-error]');
        var advancedToggle = tool.querySelector('[data-crop-advanced-toggle]');
        var advancedSections = tool.querySelectorAll('[data-crop-advanced]');

        if (!canvas || !stage || !wrap) return;

        var engine = createCropEngine(canvas);
        var currentLayout = layoutOptions.length ? layoutOptions[0].value : null;
        var panMode = false;
        var dragState = null;
        var outputBlob = null;
        var outputKind = null;
        var advancedOpen = false;

        function setStatus(message) {
            if (statusEl) statusEl.textContent = message || '';
        }

        function setError(message) {
            if (!errorEl) return;
            errorEl.textContent = message || '';
            errorEl.hidden = !message;
        }

        function resetDownload() {
            outputBlob = null;
            outputKind = null;
            if (downloadBtn) downloadBtn.classList.add('disabled');
        }

        function displayScale() {
            var rect = canvas.getBoundingClientRect();
            return rect.width ? rect.width / canvas.width : engine.getZoom();
        }

        function syncSelectionUI() {
            var selection = engine.getSelection();
            if (!selection || !selectionEl) {
                if (selectionEl) selectionEl.hidden = true;
                return;
            }

            var scale = displayScale();
            selectionEl.hidden = false;
            selectionEl.style.left = (selection.x * scale) + 'px';
            selectionEl.style.top = (selection.y * scale) + 'px';
            selectionEl.style.width = (selection.width * scale) + 'px';
            selectionEl.style.height = (selection.height * scale) + 'px';
        }

        function syncZoomUI() {
            var zoom = engine.getZoom();
            wrap.style.width = canvas.style.width;
            wrap.style.height = canvas.style.height;
            if (zoomRange) zoomRange.value = zoom;
            if (zoomValueEl) zoomValueEl.textContent = Math.round(zoom * 100) + '%';
            syncSelectionUI();
        }

        function setZoom(nextZoom) {
            engine.applyZoom(nextZoom);
            syncZoomUI();
        }

        function syncPageUI() {
            if (pageIndicator) pageIndicator.textContent = 'Page ' + engine.getCurrentPage() + ' of ' + engine.getPageCount();
            if (prevPageBtn) prevPageBtn.disabled = engine.getCurrentPage() <= 1;
            if (nextPageBtn) nextPageBtn.disabled = engine.getCurrentPage() >= engine.getPageCount();
        }

        function refreshHistoryButtons() {
            // Undo/redo availability is derived implicitly; buttons stay
            // enabled and simply no-op at the boundaries.
        }

        async function handleFile(file) {
            if (!file) return;
            setError('');
            resetDownload();

            if (emptyState) {
                emptyState.hidden = false;
                emptyState.querySelector('span').textContent = 'Rendering PDF preview…';
            }
            canvas.hidden = true;
            if (selectionEl) selectionEl.hidden = true;
            setStatus('Rendering PDF preview…');

            try {
                var info = await engine.loadFile(file);
                currentLayout = layoutOptions.length ? layoutOptions[0].value : null;
                layoutOptions.forEach(function (option) {
                    option.checked = option.value === currentLayout;
                    option.closest('.seller-layout-card').classList.toggle('is-selected', option.checked);
                });

                if (currentLayout) engine.setLayout(mode, currentLayout);

                if (fileInfo) fileInfo.hidden = false;
                if (emptyState) emptyState.hidden = true;
                canvas.hidden = false;
                if (selectionEl) selectionEl.hidden = false;
                if (pageSizeEl) pageSizeEl.textContent = info.pageSize;
                if (pageCountEl) pageCountEl.textContent = String(info.pageCount);
                if (fileSizeEl) fileSizeEl.textContent = info.fileSize;

                syncZoomUI();
                syncPageUI();
                setStatus(info.largeFile ? 'Large file: ' + info.pageCount + ' pages. Rendering and export may take a moment.' : 'PDF loaded. Adjust the crop box, then export.');
            } catch (error) {
                console.error('Toolexa seller crop: could not load file', error);
                setError((error && error.friendly) || 'Could not process this PDF.');
                setStatus('');
                canvas.hidden = true;
                if (selectionEl) selectionEl.hidden = true;
                if (emptyState) {
                    emptyState.hidden = false;
                    emptyState.querySelector('span').textContent = 'Upload a PDF to see the live preview and crop box.';
                }
            }
        }

        function pointerToCanvasPoint(evt) {
            var rect = canvas.getBoundingClientRect();
            var p = evt.touches && evt.touches[0] ? evt.touches[0] : (evt.changedTouches && evt.changedTouches[0] ? evt.changedTouches[0] : evt);
            var scaleX = rect.width ? canvas.width / rect.width : 1;
            var scaleY = rect.height ? canvas.height / rect.height : 1;
            return {
                x: clamp((p.clientX - rect.left) * scaleX, 0, canvas.width),
                y: clamp((p.clientY - rect.top) * scaleY, 0, canvas.height)
            };
        }

        function currentAspectRatio(base) {
            if (!aspectLock || !aspectLock.checked || !base || !base.height) return null;
            return base.width / base.height;
        }

        function resizeFromHandle(handle, start, point, aspectRatio) {
            var x = start.x, y = start.y, w = start.width, h = start.height;
            var right = start.x + start.width;
            var bottom = start.y + start.height;

            if (handle.indexOf('w') !== -1) { x = Math.min(point.x, right - MIN_SELECTION); w = right - x; }
            if (handle.indexOf('e') !== -1) { w = Math.max(MIN_SELECTION, point.x - start.x); }
            if (handle.indexOf('n') !== -1) { y = Math.min(point.y, bottom - MIN_SELECTION); h = bottom - y; }
            if (handle.indexOf('s') !== -1) { h = Math.max(MIN_SELECTION, point.y - start.y); }

            if (aspectRatio) {
                if (handle === 'n' || handle === 's') {
                    w = h * aspectRatio;
                } else {
                    h = w / aspectRatio;
                    if (handle.indexOf('n') !== -1) y = bottom - h;
                }
            }

            x = clamp(x, 0, canvas.width - MIN_SELECTION);
            y = clamp(y, 0, canvas.height - MIN_SELECTION);
            w = clamp(w, MIN_SELECTION, canvas.width - x);
            h = clamp(h, MIN_SELECTION, canvas.height - y);

            return { x: x, y: y, width: w, height: h };
        }

        function drawNewSelection(anchor, point, aspectRatio) {
            var x = Math.min(anchor.x, point.x);
            var y = Math.min(anchor.y, point.y);
            var w = Math.abs(point.x - anchor.x);
            var h = Math.abs(point.y - anchor.y);

            if (aspectRatio) {
                h = w / aspectRatio;
                if (point.y < anchor.y) y = anchor.y - h;
            }

            x = clamp(x, 0, canvas.width);
            y = clamp(y, 0, canvas.height);
            w = clamp(w, 0, canvas.width - x);
            h = clamp(h, 0, canvas.height - y);

            return { x: x, y: y, width: w, height: h };
        }

        function startDrag(evt, kind, handle) {
            if (!engine.hasDocument()) return;
            evt.preventDefault();

            var point = pointerToCanvasPoint(evt);
            var current = engine.getSelection();

            dragState = {
                kind: kind,
                handle: handle,
                anchor: point,
                startSelection: current ? { x: current.x, y: current.y, width: current.width, height: current.height } : null,
                aspectRatio: currentAspectRatio(current),
                startScroll: { left: stage.scrollLeft, top: stage.scrollTop },
                startClient: { x: evt.touches ? evt.touches[0].clientX : evt.clientX, y: evt.touches ? evt.touches[0].clientY : evt.clientY }
            };

            if (kind === 'draw') {
                engine.setSelectionPixels({ x: point.x, y: point.y, width: MIN_SELECTION, height: MIN_SELECTION }, false);
                syncSelectionUI();
            }
        }

        function onDragMove(evt) {
            if (!dragState) return;
            evt.preventDefault();

            if (dragState.kind === 'pan') {
                var clientX = evt.touches ? evt.touches[0].clientX : evt.clientX;
                var clientY = evt.touches ? evt.touches[0].clientY : evt.clientY;
                stage.scrollLeft = dragState.startScroll.left - (clientX - dragState.startClient.x);
                stage.scrollTop = dragState.startScroll.top - (clientY - dragState.startClient.y);
                return;
            }

            var point = pointerToCanvasPoint(evt);
            var next;

            if (dragState.kind === 'move' && dragState.startSelection) {
                var deltaX = point.x - dragState.anchor.x;
                var deltaY = point.y - dragState.anchor.y;
                next = {
                    x: clamp(dragState.startSelection.x + deltaX, 0, canvas.width - dragState.startSelection.width),
                    y: clamp(dragState.startSelection.y + deltaY, 0, canvas.height - dragState.startSelection.height),
                    width: dragState.startSelection.width,
                    height: dragState.startSelection.height
                };
            } else if (dragState.kind === 'resize' && dragState.startSelection) {
                next = resizeFromHandle(dragState.handle, dragState.startSelection, point, dragState.aspectRatio);
            } else if (dragState.kind === 'draw') {
                next = drawNewSelection(dragState.anchor, point, dragState.aspectRatio);
            }

            if (next) {
                engine.setSelectionPixels(next, false);
                syncSelectionUI();
            }
        }

        function onDragEnd() {
            if (!dragState) return;
            var wasDraw = dragState.kind !== 'pan';
            dragState = null;
            if (wasDraw) {
                var selection = engine.getSelection();
                if (selection) engine.setSelectionPixels(selection, true);
            }
        }

        if (dropzone) {
            ['dragenter', 'dragover'].forEach(function (type) {
                dropzone.addEventListener(type, function (evt) {
                    evt.preventDefault();
                    dropzone.classList.add('is-dragover');
                });
            });
            ['dragleave', 'drop'].forEach(function (type) {
                dropzone.addEventListener(type, function (evt) {
                    evt.preventDefault();
                    dropzone.classList.remove('is-dragover');
                });
            });
            dropzone.addEventListener('drop', function (evt) {
                var file = evt.dataTransfer && evt.dataTransfer.files ? evt.dataTransfer.files[0] : null;
                if (file) handleFile(file);
            });
            dropzone.addEventListener('click', function () {
                if (fileInput) fileInput.click();
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (fileInput.files && fileInput.files[0]) handleFile(fileInput.files[0]);
            });
        }

        layoutOptions.forEach(function (option) {
            option.addEventListener('change', function () {
                currentLayout = option.value;
                tool.querySelectorAll('.seller-layout-card').forEach(function (card) {
                    card.classList.toggle('is-selected', card.contains(option));
                });
                if (engine.hasDocument()) {
                    engine.setLayout(mode, currentLayout);
                    syncSelectionUI();
                    setStatus('Layout switched to ' + option.closest('.seller-layout-card').querySelector('strong').textContent + '. Adjust the crop box if needed.');
                }
            });
        });

        presetButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                if (!engine.hasDocument()) {
                    setStatus('Upload a PDF first.');
                    return;
                }
                var cropType = button.getAttribute('data-crop-preset');
                var result = engine.applyPreset(mode, currentLayout, cropType);
                syncSelectionUI();
                setStatus(result.applied
                    ? CROP_TYPE_LABELS[cropType] + ' preset applied. Drag the handles to fine-tune it.'
                    : 'Not available for this layout — adjust the crop box manually.');
            });
        });

        handles.forEach(function (handle) {
            var direction = handle.getAttribute('data-crop-handle');
            handle.addEventListener('mousedown', function (evt) { evt.stopPropagation(); startDrag(evt, 'resize', direction); });
            handle.addEventListener('touchstart', function (evt) { evt.stopPropagation(); startDrag(evt, 'resize', direction); }, { passive: false });
        });

        if (selectionEl) {
            selectionEl.addEventListener('mousedown', function (evt) {
                if (evt.target !== selectionEl) return;
                startDrag(evt, panMode ? 'pan' : 'move', null);
            });
            selectionEl.addEventListener('touchstart', function (evt) {
                if (evt.target !== selectionEl) return;
                startDrag(evt, panMode ? 'pan' : 'move', null);
            }, { passive: false });
        }

        wrap.addEventListener('mousedown', function (evt) {
            if (evt.target !== canvas) return;
            startDrag(evt, panMode ? 'pan' : 'draw', null);
        });
        wrap.addEventListener('touchstart', function (evt) {
            if (evt.target !== canvas) return;
            startDrag(evt, panMode ? 'pan' : 'draw', null);
        }, { passive: false });

        document.addEventListener('mousemove', onDragMove);
        document.addEventListener('touchmove', onDragMove, { passive: false });
        document.addEventListener('mouseup', onDragEnd);
        document.addEventListener('touchend', onDragEnd);

        stage.addEventListener('wheel', function (evt) {
            if (!engine.hasDocument()) return;
            evt.preventDefault();

            var rect = stage.getBoundingClientRect();
            var offsetX = evt.clientX - rect.left + stage.scrollLeft;
            var offsetY = evt.clientY - rect.top + stage.scrollTop;
            var previousZoom = engine.getZoom();
            var factor = evt.deltaY < 0 ? 1.12 : 0.89;

            setZoom(previousZoom * factor);

            var ratio = engine.getZoom() / previousZoom;
            stage.scrollLeft = offsetX * ratio - (evt.clientX - rect.left);
            stage.scrollTop = offsetY * ratio - (evt.clientY - rect.top);
        }, { passive: false });

        if (zoomRange) {
            zoomRange.addEventListener('input', function () { setZoom(Number(zoomRange.value)); });
        }

        if (fitWidthBtn) {
            fitWidthBtn.addEventListener('click', function () {
                var size = engine.getNaturalSize();
                if (!size.width) return;
                setZoom((stage.clientWidth - 24) / size.width);
            });
        }

        if (fitHeightBtn) {
            fitHeightBtn.addEventListener('click', function () {
                var size = engine.getNaturalSize();
                if (!size.height) return;
                setZoom((stage.clientHeight - 24) / size.height);
            });
        }

        if (panToggle) {
            panToggle.addEventListener('click', function () {
                panMode = !panMode;
                panToggle.classList.toggle('is-active', panMode);
                wrap.classList.toggle('is-pan-mode', panMode);
                setStatus(panMode ? 'Pan mode: drag the page to scroll around.' : 'Crop mode: drag to move or resize the crop box.');
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                if (!engine.hasDocument()) return;
                engine.resetSelection();
                syncSelectionUI();
                setStatus('Crop box reset.');
            });
        }

        if (undoBtn) {
            undoBtn.addEventListener('click', function () {
                if (engine.undo()) { syncSelectionUI(); setStatus('Undo applied.'); }
            });
        }

        if (redoBtn) {
            redoBtn.addEventListener('click', function () {
                if (engine.redo()) { syncSelectionUI(); setStatus('Redo applied.'); }
            });
        }

        if (prevPageBtn) {
            prevPageBtn.addEventListener('click', async function () {
                await engine.goToPage(engine.getCurrentPage() - 1);
                syncZoomUI();
                syncPageUI();
            });
        }

        if (nextPageBtn) {
            nextPageBtn.addEventListener('click', async function () {
                await engine.goToPage(engine.getCurrentPage() + 1);
                syncZoomUI();
                syncPageUI();
            });
        }

        function triggerDownload() {
            if (!outputBlob) { setStatus('Download the crop first.'); return; }
            var url = URL.createObjectURL(outputBlob);
            var link = document.createElement('a');
            link.href = url;
            link.download = mode.replace('-label-cropper', '') + '-cropped.' + outputKind;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        if (exportBtn) {
            exportBtn.addEventListener('click', async function () {
                if (!engine.hasDocument()) {
                    setStatus('Upload a PDF first.');
                    return;
                }

                var format = 'pdf';
                exportFormatInputs.forEach(function (input) { if (input.checked) format = input.value; });

                setError('');
                setStatus('Preparing your download…');
                resetDownload();

                try {
                    if (format === 'png') {
                        outputBlob = await engine.exportPng();
                        outputKind = 'png';
                        setStatus('PNG downloaded (current page only, high resolution).');
                    } else {
                        var bytes = await engine.exportPdf();
                        outputBlob = new Blob([bytes], { type: 'application/pdf' });
                        outputKind = 'pdf';
                        setStatus('PDF downloaded — crop applied to all ' + engine.getPageCount() + ' page(s).');
                    }
                    if (downloadBtn) downloadBtn.classList.remove('disabled');
                    triggerDownload();
                } catch (error) {
                    console.error('Toolexa seller crop: export failed', error);
                    setError((error && error.friendly) || 'Could not export this crop.');
                    setStatus('');
                }
            });
        }

        if (downloadBtn) {
            downloadBtn.addEventListener('click', triggerDownload);
        }

        if (advancedToggle) {
            advancedToggle.addEventListener('click', function () {
                advancedOpen = !advancedOpen;
                advancedSections.forEach(function (section) { section.hidden = !advancedOpen; });
                advancedToggle.textContent = advancedOpen ? 'Hide advanced controls' : 'Show advanced controls';
                advancedToggle.setAttribute('aria-expanded', String(advancedOpen));
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (fileInput) fileInput.value = '';
                resetDownload();
                setError('');
                setStatus('');
                canvas.hidden = true;
                if (selectionEl) selectionEl.hidden = true;
                if (fileInfo) fileInfo.hidden = true;
                if (emptyState) emptyState.hidden = false;
            });
        }

        tool.querySelectorAll('[data-share-url]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (navigator.share) {
                    navigator.share({ title: document.title, url: window.location.href }).catch(function () {});
                    return;
                }
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(window.location.href).then(function () { setStatus('Share link copied'); });
                }
            });
        });

        refreshHistoryButtons();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-seller-crop-tool]').forEach(initTool);
    });
})();
