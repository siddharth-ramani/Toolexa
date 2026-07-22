(function () {
    var menu = document.getElementById('siteMenu');
    var navToggle = document.querySelector('[data-nav-toggle]');
    var dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]');

    if (navToggle && menu) {
        navToggle.addEventListener('click', function () {
            var isOpen = menu.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            navToggle.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
        });
    }

    function closeDropdown(dropdown) {
        dropdown.classList.remove('open');
        var button = dropdown.querySelector('[data-dropdown-toggle]');
        if (button) {
            button.setAttribute('aria-expanded', 'false');
        }
    }

    function openDropdown(dropdown) {
        dropdown.classList.add('open');
        var button = dropdown.querySelector('[data-dropdown-toggle]');
        if (button) {
            button.setAttribute('aria-expanded', 'true');
        }
    }

    dropdownButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            var current = button.closest('.nav-dropdown');
            var shouldOpen = !current.classList.contains('open');

            document.querySelectorAll('.nav-dropdown.open').forEach(function (dropdown) {
                if (dropdown !== current) {
                    closeDropdown(dropdown);
                }
            });

            if (shouldOpen) {
                openDropdown(current);
            } else {
                closeDropdown(current);
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.nav-dropdown')) {
            document.querySelectorAll('.nav-dropdown.open').forEach(function (dropdown) {
                closeDropdown(dropdown);
            });
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.nav-dropdown.open').forEach(closeDropdown);
            if (menu && menu.classList.contains('open')) {
                menu.classList.remove('open');
                if (navToggle) {
                    navToggle.setAttribute('aria-expanded', 'false');
                    navToggle.setAttribute('aria-label', 'Open navigation');
                }
            }
        }
    });

    function trackEvent(name, params) {
        if (!name || typeof window.gtag !== 'function') {
            return;
        }

        window.gtag('event', name, params || {});
    }

    document.querySelectorAll('[data-ga-event]').forEach(function (element) {
        element.addEventListener('click', function () {
            trackEvent(element.getAttribute('data-ga-event'), {
                event_category: element.getAttribute('data-ga-category') || 'Tools',
                event_label: element.getAttribute('data-ga-label') || element.textContent.trim()
            });
        });
    });

    document.querySelectorAll('[data-tool-actions]').forEach(function (actions) {
        var copyButton = actions.querySelector('[data-copy-url]');
        var shareButton = actions.querySelector('[data-share-url]');
        var status = actions.querySelector('[data-copy-status]');

        function showStatus(message) {
            if (!status) {
                return;
            }

            status.textContent = message;
            window.setTimeout(function () {
                status.textContent = '';
            }, 2200);
        }

        function copyCurrentUrl(successMessage) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(window.location.href).then(function () {
                    showStatus(successMessage);
                }).catch(function () {
                    showStatus('Copy failed');
                });
            }

            var input = document.createElement('input');
            input.value = window.location.href;
            input.setAttribute('readonly', 'readonly');
            input.style.position = 'absolute';
            input.style.left = '-9999px';
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showStatus(successMessage);

            return Promise.resolve();
        }

        if (copyButton) {
            copyButton.addEventListener('click', function () {
                copyCurrentUrl('Link copied');
            });
        }

        if (shareButton) {
            shareButton.addEventListener('click', function () {
                var shareData = {
                    title: document.title,
                    url: window.location.href
                };

                if (navigator.share) {
                    navigator.share(shareData).catch(function () {});
                    return;
                }

                copyCurrentUrl('Share link copied');
            });
        }
    });

    document.querySelectorAll('[data-copy-target]').forEach(function (button) {
        button.addEventListener('click', function () {
            var target = document.getElementById(button.getAttribute('data-copy-target'));
            var status = button.parentElement ? button.parentElement.querySelector('[data-copy-status]') : null;

            function showStatus(message) {
                if (!status) {
                    return;
                }

                status.textContent = message;
                window.setTimeout(function () {
                    status.textContent = '';
                }, 2200);
            }

            if (!target) {
                showStatus('Copy failed');
                return;
            }

            var text = target.value || target.textContent || '';

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function () {
                    showStatus('Result copied');
                }).catch(function () {
                    showStatus('Copy failed');
                });
                return;
            }

            target.focus();
            target.select();
            document.execCommand('copy');
            showStatus('Result copied');
        });
    });

    document.querySelectorAll('[data-copy-text]').forEach(function (button) {
        button.addEventListener('click', function () {
            var text = button.getAttribute('data-copy-text') || '';
            var originalText = button.textContent;

            function markCopied(message) {
                button.textContent = message;
                window.setTimeout(function () {
                    button.textContent = originalText;
                }, 1800);
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function () {
                    markCopied('Copied');
                }).catch(function () {
                    markCopied('Copy failed');
                });
                return;
            }

            var input = document.createElement('input');
            input.value = text;
            input.setAttribute('readonly', 'readonly');
            input.style.position = 'absolute';
            input.style.left = '-9999px';
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            markCopied('Copied');
        });
    });

    document.querySelectorAll('[data-clear-tool]').forEach(function (button) {
        button.addEventListener('click', function () {
            var form = button.closest('form');
            var layout = button.closest('.tool-layout');

            window.setTimeout(function () {
                if (form) {
                    form.querySelectorAll('textarea, input[type="text"], input[type="number"]').forEach(function (field) {
                        field.value = '';
                    });
                }

                if (layout) {
                    layout.querySelectorAll('.text-output, .copy-source').forEach(function (field) {
                        field.value = '';
                    });
                    layout.querySelectorAll('[data-copy-status]').forEach(function (status) {
                        status.textContent = '';
                    });
                }
            }, 0);
        });
    });

    document.querySelectorAll('[data-image-tool]').forEach(function (tool) {
        var mode = tool.getAttribute('data-image-mode');
        var input = tool.querySelector('[data-image-input]');
        var processButton = tool.querySelector('[data-image-process]');
        var clearButton = tool.querySelector('[data-image-clear]');
        var originalPreview = tool.querySelector('[data-original-preview]');
        var outputPreview = tool.querySelector('[data-output-preview]');
        var emptyOriginal = tool.querySelector('[data-empty-original]');
        var emptyOutput = tool.querySelector('[data-empty-output]');
        var originalMeta = tool.querySelector('[data-original-meta]');
        var outputMeta = tool.querySelector('[data-output-meta]');
        var download = tool.querySelector('[data-image-download]');
        var status = tool.querySelector('[data-image-status]');
        var cropCanvas = tool.querySelector('[data-crop-canvas]');
        var resizeWidth = tool.querySelector('[data-resize-width]');
        var resizeHeight = tool.querySelector('[data-resize-height]');
        var maintainRatio = tool.querySelector('[data-maintain-ratio]');
        var qualityRange = tool.querySelector('[data-quality-range]');
        var qualityValue = tool.querySelector('[data-quality-value]');
        var backgroundColor = tool.querySelector('[data-background-color]');
        var aspectRatio = tool.querySelector('[data-aspect-ratio]');
        var sourceImage = null;
        var sourceFile = null;
        var outputUrl = null;
        var cropSelection = null;
        var cropDrag = null;

        function setStatus(message) {
            if (!status) {
                return;
            }

            status.textContent = message;
        }

        function clearStatusLater() {
            window.setTimeout(function () {
                setStatus('');
            }, 2400);
        }

        function bytes(size) {
            if (!size) {
                return '0 KB';
            }

            if (size < 1024 * 1024) {
                return Math.round(size / 1024) + ' KB';
            }

            return (size / (1024 * 1024)).toFixed(2) + ' MB';
        }

        function resetOutput() {
            if (outputUrl) {
                URL.revokeObjectURL(outputUrl);
                outputUrl = null;
            }

            if (outputPreview) {
                outputPreview.hidden = true;
                outputPreview.removeAttribute('src');
            }

            if (emptyOutput) {
                emptyOutput.hidden = false;
            }

            if (outputMeta) {
                outputMeta.textContent = '';
            }

            if (download) {
                download.classList.add('disabled');
                download.removeAttribute('href');
            }
        }

        function drawCropCanvas() {
            if (!cropCanvas || !sourceImage) {
                return;
            }

            var maxWidth = Math.min(720, cropCanvas.parentElement.clientWidth || 720);
            var scale = Math.min(1, maxWidth / sourceImage.naturalWidth);
            cropCanvas.width = Math.max(1, Math.round(sourceImage.naturalWidth * scale));
            cropCanvas.height = Math.max(1, Math.round(sourceImage.naturalHeight * scale));
            var ctx = cropCanvas.getContext('2d');
            ctx.clearRect(0, 0, cropCanvas.width, cropCanvas.height);
            ctx.drawImage(sourceImage, 0, 0, cropCanvas.width, cropCanvas.height);

            if (cropSelection) {
                ctx.fillStyle = 'rgba(15, 23, 42, .38)';
                ctx.fillRect(0, 0, cropCanvas.width, cropCanvas.height);
                ctx.drawImage(
                    sourceImage,
                    cropSelection.x / scale,
                    cropSelection.y / scale,
                    cropSelection.width / scale,
                    cropSelection.height / scale,
                    cropSelection.x,
                    cropSelection.y,
                    cropSelection.width,
                    cropSelection.height
                );
                ctx.strokeStyle = '#14b8a6';
                ctx.lineWidth = 2;
                ctx.strokeRect(cropSelection.x, cropSelection.y, cropSelection.width, cropSelection.height);
            }
        }

        function setOriginal(file, image) {
            sourceFile = file;
            sourceImage = image;
            resetOutput();
            cropSelection = null;

            if (originalPreview) {
                originalPreview.src = image.src;
                originalPreview.hidden = mode === 'image-cropper';
            }

            if (cropCanvas) {
                cropCanvas.hidden = mode !== 'image-cropper';
                drawCropCanvas();
            }

            if (emptyOriginal) {
                emptyOriginal.hidden = true;
            }

            if (originalMeta) {
                originalMeta.textContent = image.naturalWidth + ' x ' + image.naturalHeight + ' px · ' + bytes(file.size);
            }

            if (resizeWidth) {
                resizeWidth.value = image.naturalWidth;
            }

            if (resizeHeight) {
                resizeHeight.value = image.naturalHeight;
            }
        }

        function loadImage(file) {
            if (!file || !file.type.match(/^image\/(jpeg|png|webp)$/)) {
                setStatus('Please choose a JPG, PNG or WebP image.');
                clearStatusLater();
                return;
            }

            if (mode === 'jpg-to-png-converter' && !file.type.match(/^image\/jpeg$/)) {
                setStatus('Please choose a JPG or JPEG image.');
                clearStatusLater();
                return;
            }

            var reader = new FileReader();
            reader.onload = function () {
                var image = new Image();
                image.onload = function () {
                    setOriginal(file, image);
                    setStatus('Image loaded');
                    clearStatusLater();
                };
                image.onerror = function () {
                    setStatus('Could not read this image.');
                };
                image.src = reader.result;
            };
            reader.readAsDataURL(file);
        }

        function makeCanvas(width, height) {
            var canvas = document.createElement('canvas');
            canvas.width = Math.max(1, Math.round(width));
            canvas.height = Math.max(1, Math.round(height));
            return canvas;
        }

        function canvasToBlob(canvas, type, quality, filename) {
            canvas.toBlob(function (blob) {
                if (!blob) {
                    setStatus('Could not process image.');
                    return;
                }

                resetOutput();
                outputUrl = URL.createObjectURL(blob);
                outputPreview.src = outputUrl;
                outputPreview.hidden = false;
                emptyOutput.hidden = true;
                outputMeta.textContent = canvas.width + ' x ' + canvas.height + ' px · ' + bytes(blob.size);
                download.href = outputUrl;
                download.download = filename;
                download.classList.remove('disabled');
                setStatus('Image ready');
                clearStatusLater();
            }, type, quality);
        }

        function drawSourceTo(canvas, fillColor) {
            var ctx = canvas.getContext('2d');

            if (fillColor) {
                ctx.fillStyle = fillColor;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }

            ctx.drawImage(sourceImage, 0, 0, canvas.width, canvas.height);
        }

        function sourceOutput() {
            if (sourceFile.type === 'image/png') {
                return { type: 'image/png', extension: 'png' };
            }

            if (sourceFile.type === 'image/webp') {
                return { type: 'image/webp', extension: 'webp' };
            }

            return { type: 'image/jpeg', extension: 'jpg' };
        }

        function processImage() {
            if (!sourceImage || !sourceFile) {
                setStatus('Choose an image first.');
                clearStatusLater();
                return;
            }

            var canvas;
            var quality = qualityRange ? Number(qualityRange.value) / 100 : .9;

            if (mode === 'image-resizer') {
                canvas = makeCanvas(Number(resizeWidth.value), Number(resizeHeight.value));
                drawSourceTo(canvas);
                var resizeOutput = sourceOutput();
                canvasToBlob(canvas, resizeOutput.type, .92, 'toolexa-resized.' + resizeOutput.extension);
                return;
            }

            if (mode === 'image-compressor') {
                canvas = makeCanvas(sourceImage.naturalWidth, sourceImage.naturalHeight);
                drawSourceTo(canvas);
                var outputType = sourceFile.type === 'image/webp' ? 'image/webp' : (sourceFile.type === 'image/png' ? 'image/png' : 'image/jpeg');
                var extension = outputType === 'image/webp' ? 'webp' : (outputType === 'image/png' ? 'png' : 'jpg');
                canvasToBlob(canvas, outputType, quality, 'toolexa-compressed.' + extension);
                return;
            }

            if (mode === 'jpg-to-png-converter') {
                canvas = makeCanvas(sourceImage.naturalWidth, sourceImage.naturalHeight);
                drawSourceTo(canvas);
                canvasToBlob(canvas, 'image/png', 1, 'toolexa-converted.png');
                return;
            }

            if (mode === 'png-to-jpg-converter') {
                canvas = makeCanvas(sourceImage.naturalWidth, sourceImage.naturalHeight);
                drawSourceTo(canvas, backgroundColor ? backgroundColor.value : '#ffffff');
                canvasToBlob(canvas, 'image/jpeg', .92, 'toolexa-converted.jpg');
                return;
            }

            if (mode === 'image-cropper') {
                if (!cropSelection || cropSelection.width < 2 || cropSelection.height < 2) {
                    setStatus('Drag on the preview to select a crop area.');
                    clearStatusLater();
                    return;
                }

                var scaleX = sourceImage.naturalWidth / cropCanvas.width;
                var scaleY = sourceImage.naturalHeight / cropCanvas.height;
                canvas = makeCanvas(cropSelection.width * scaleX, cropSelection.height * scaleY);
                canvas.getContext('2d').drawImage(
                    sourceImage,
                    cropSelection.x * scaleX,
                    cropSelection.y * scaleY,
                    cropSelection.width * scaleX,
                    cropSelection.height * scaleY,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );
                var cropOutput = sourceOutput();
                canvasToBlob(canvas, cropOutput.type, .92, 'toolexa-cropped.' + cropOutput.extension);
            }
        }

        function updateRatioFromWidth() {
            if (!maintainRatio || !maintainRatio.checked || !sourceImage || !resizeWidth || !resizeHeight) {
                return;
            }

            resizeHeight.value = Math.max(1, Math.round(Number(resizeWidth.value) * sourceImage.naturalHeight / sourceImage.naturalWidth));
        }

        function updateRatioFromHeight() {
            if (!maintainRatio || !maintainRatio.checked || !sourceImage || !resizeWidth || !resizeHeight) {
                return;
            }

            resizeWidth.value = Math.max(1, Math.round(Number(resizeHeight.value) * sourceImage.naturalWidth / sourceImage.naturalHeight));
        }

        function cropPoint(event) {
            var rect = cropCanvas.getBoundingClientRect();
            var pointer = event.touches && event.touches[0] ? event.touches[0] : event;
            return {
                x: Math.max(0, Math.min(cropCanvas.width, (pointer.clientX - rect.left) * cropCanvas.width / rect.width)),
                y: Math.max(0, Math.min(cropCanvas.height, (pointer.clientY - rect.top) * cropCanvas.height / rect.height))
            };
        }

        function updateCropSelection(point) {
            var x = Math.min(cropDrag.x, point.x);
            var y = Math.min(cropDrag.y, point.y);
            var width = Math.abs(point.x - cropDrag.x);
            var height = Math.abs(point.y - cropDrag.y);
            var ratio = aspectRatio && aspectRatio.value !== 'free' ? Number(aspectRatio.value) : null;

            if (ratio && width > 0) {
                height = width / ratio;

                if (point.y < cropDrag.y) {
                    y = cropDrag.y - height;
                }

                if (y + height > cropCanvas.height) {
                    height = cropCanvas.height - y;
                    width = height * ratio;
                }

                if (x + width > cropCanvas.width) {
                    width = cropCanvas.width - x;
                    height = width / ratio;
                }
            }

            cropSelection = { x: x, y: y, width: width, height: height };
            drawCropCanvas();
        }

        if (input) {
            input.addEventListener('change', function () {
                loadImage(input.files[0]);
            });
        }

        if (processButton) {
            processButton.addEventListener('click', processImage);
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                if (input) {
                    input.value = '';
                }

                sourceImage = null;
                sourceFile = null;
                cropSelection = null;
                resetOutput();

                if (originalPreview) {
                    originalPreview.hidden = true;
                    originalPreview.removeAttribute('src');
                }

                if (cropCanvas) {
                    cropCanvas.hidden = true;
                }

                if (emptyOriginal) {
                    emptyOriginal.hidden = false;
                }

                if (originalMeta) {
                    originalMeta.textContent = '';
                }

                setStatus('Image cleared');
                clearStatusLater();
            });
        }

        if (resizeWidth) {
            resizeWidth.addEventListener('input', updateRatioFromWidth);
        }

        if (resizeHeight) {
            resizeHeight.addEventListener('input', updateRatioFromHeight);
        }

        if (qualityRange && qualityValue) {
            qualityRange.addEventListener('input', function () {
                qualityValue.textContent = qualityRange.value;
            });
        }

        if (cropCanvas) {
            cropCanvas.addEventListener('mousedown', function (event) {
                if (!sourceImage) {
                    return;
                }

                cropDrag = cropPoint(event);
                cropSelection = { x: cropDrag.x, y: cropDrag.y, width: 0, height: 0 };
            });

            cropCanvas.addEventListener('mousemove', function (event) {
                if (!cropDrag) {
                    return;
                }

                updateCropSelection(cropPoint(event));
            });

            document.addEventListener('mouseup', function () {
                cropDrag = null;
            });

            cropCanvas.addEventListener('touchstart', function (event) {
                if (!sourceImage) {
                    return;
                }

                event.preventDefault();
                cropDrag = cropPoint(event);
                cropSelection = { x: cropDrag.x, y: cropDrag.y, width: 0, height: 0 };
            }, { passive: false });

            cropCanvas.addEventListener('touchmove', function (event) {
                if (!cropDrag) {
                    return;
                }

                event.preventDefault();
                updateCropSelection(cropPoint(event));
            }, { passive: false });

            cropCanvas.addEventListener('touchend', function () {
                cropDrag = null;
            });

            if (aspectRatio) {
                aspectRatio.addEventListener('change', function () {
                    cropSelection = null;
                    drawCropCanvas();
                });
            }
        }

        tool.querySelectorAll('[data-share-url]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (navigator.share) {
                    navigator.share({ title: document.title, url: window.location.href }).catch(function () {});
                    return;
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(window.location.href).then(function () {
                        setStatus('Share link copied');
                        clearStatusLater();
                    });
                }
            });
        });
    });

    document.querySelectorAll('[data-browser-tool]').forEach(function (tool) {
        var mode = tool.getAttribute('data-browser-mode');
        var result = tool.querySelector('[data-browser-result]');
        var copySource = tool.querySelector('[data-browser-copy-source]');
        var processButton = tool.querySelector('[data-browser-process]');
        var clearButton = tool.querySelector('[data-browser-clear]');
        var copyAllButton = tool.querySelector('[data-browser-copy-all]');
        var downloadButton = tool.querySelector('[data-browser-download]');
        var status = tool.querySelector('[data-copy-status]');
        var lastLines = [];

        function setStatus(message) {
            if (!status) {
                return;
            }

            status.textContent = message;
            window.setTimeout(function () {
                status.textContent = '';
            }, 2200);
        }

        function setCopyText(text) {
            if (copySource) {
                copySource.value = text;
            }
        }

        function renderText(text, meta) {
            result.innerHTML = '';
            var value = document.createElement('strong');
            value.className = 'browser-result-value';
            value.textContent = text;
            result.appendChild(value);

            if (meta) {
                var small = document.createElement('small');
                small.textContent = meta;
                result.appendChild(small);
            }

            setCopyText(text);
        }

        function renderList(lines) {
            result.innerHTML = '';
            var list = document.createElement('div');
            list.className = 'browser-result-list';

            lines.forEach(function (line) {
                var row = document.createElement('div');
                var code = document.createElement('code');
                var button = document.createElement('button');
                code.textContent = line;
                button.className = 'btn btn-secondary btn-sm';
                button.type = 'button';
                button.textContent = 'Copy';
                button.addEventListener('click', function () {
                    copyText(line, 'UUID copied');
                });
                row.appendChild(code);
                row.appendChild(button);
                list.appendChild(row);
            });

            result.appendChild(list);
            lastLines = lines;
            setCopyText(lines.join('\n'));
        }

        function renderMessage(message) {
            result.innerHTML = '';
            var span = document.createElement('span');
            span.textContent = message;
            result.appendChild(span);
            setCopyText(message);
        }

        function copyText(text, message) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function () {
                    setStatus(message);
                }).catch(function () {
                    setStatus('Copy failed');
                });
                return;
            }

            setCopyText(text);
            copySource.focus();
            copySource.select();
            document.execCommand('copy');
            setStatus(message);
        }

        function randomInt(max) {
            if (window.crypto && window.crypto.getRandomValues) {
                var array = new Uint32Array(1);
                window.crypto.getRandomValues(array);
                return array[0] % max;
            }

            return Math.floor(Math.random() * max);
        }

        function uuidV4() {
            if (window.crypto && window.crypto.randomUUID) {
                return window.crypto.randomUUID();
            }

            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (char) {
                var value = randomInt(16);
                var output = char === 'x' ? value : ((value & 0x3) | 0x8);
                return output.toString(16);
            });
        }

        function generateUuids() {
            var count = Math.max(1, Math.min(100, Number(tool.querySelector('[data-uuid-count]').value || 1)));
            var lines = [];

            for (var index = 0; index < count; index++) {
                lines.push(uuidV4());
            }

            renderList(lines);
            setStatus('UUIDs generated');
        }

        function generateNumbers() {
            var min = Math.ceil(Number(tool.querySelector('[data-number-min]').value));
            var max = Math.floor(Number(tool.querySelector('[data-number-max]').value));
            var count = Math.max(1, Math.min(100, Number(tool.querySelector('[data-number-count]').value || 1)));
            var allowDuplicates = tool.querySelector('[data-number-duplicates]').checked;
            var range = max - min + 1;
            var values = [];
            var used = {};

            if (!Number.isFinite(min) || !Number.isFinite(max) || min > max) {
                renderMessage('Enter a valid minimum and maximum range.');
                return;
            }

            if (!allowDuplicates && count > range) {
                renderMessage('The range is too small for unique random numbers.');
                return;
            }

            while (values.length < count) {
                var value = min + randomInt(range);

                if (!allowDuplicates && used[value]) {
                    continue;
                }

                used[value] = true;
                values.push(String(value));
            }

            renderText(values.join(', '), count + ' number' + (count === 1 ? '' : 's') + ' generated');
        }

        function generateString() {
            var length = Math.max(1, Math.min(1000, Number(tool.querySelector('[data-string-length]').value || 1)));
            var groups = [];
            var similar = /[O0Il1]/g;

            if (tool.querySelector('[data-string-upper]').checked) {
                groups.push('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            }

            if (tool.querySelector('[data-string-lower]').checked) {
                groups.push('abcdefghijklmnopqrstuvwxyz');
            }

            if (tool.querySelector('[data-string-numbers]').checked) {
                groups.push('0123456789');
            }

            if (tool.querySelector('[data-string-symbols]').checked) {
                groups.push('!@#$%^&*()-_=+[]{};:,.?/|');
            }

            var chars = groups.join('');

            if (tool.querySelector('[data-string-similar]').checked) {
                chars = chars.replace(similar, '');
            }

            if (!chars) {
                renderMessage('Choose at least one character group.');
                return;
            }

            var output = '';
            for (var index = 0; index < length; index++) {
                output += chars[randomInt(chars.length)];
            }

            renderText(output, length + ' characters');
        }

        function validateUuid() {
            var value = (tool.querySelector('[data-uuid-input]').value || '').trim();
            var match = value.match(/^[0-9a-f]{8}-[0-9a-f]{4}-([1-8])[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i);

            if (!value) {
                renderMessage('Enter a UUID to validate.');
                return;
            }

            if (!match) {
                renderText('Invalid UUID', 'The value does not match the standard UUID format.');
                return;
            }

            renderText('Valid UUID v' + match[1], value);
        }

        function convertBinaryDecimal() {
            var conversion = tool.querySelector('[data-binary-mode]').value;
            var value = (tool.querySelector('[data-binary-input]').value || '').trim();

            if (conversion === 'binary-to-decimal') {
                if (!/^[01]+$/.test(value)) {
                    renderMessage('Enter a valid binary number using only 0 and 1.');
                    return;
                }

                renderText(String(parseInt(value, 2)), 'Decimal output');
                return;
            }

            if (!/^\d+$/.test(value)) {
                renderMessage('Enter a non-negative decimal integer.');
                return;
            }

            renderText(Number(value).toString(2), 'Binary output');
        }

        function process() {
            if (mode === 'uuid-generator') {
                generateUuids();
            } else if (mode === 'random-number-generator') {
                generateNumbers();
            } else if (mode === 'random-string-generator') {
                generateString();
            } else if (mode === 'uuid-validator') {
                validateUuid();
            } else if (mode === 'binary-decimal-converter') {
                convertBinaryDecimal();
            }
        }

        if (processButton) {
            processButton.addEventListener('click', process);
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                tool.querySelectorAll('input[type="text"], input[type="number"]').forEach(function (input) {
                    input.value = '';
                });
                lastLines = [];
                renderMessage('Result will appear here.');
                setCopyText('');
            });
        }

        if (copyAllButton) {
            copyAllButton.addEventListener('click', function () {
                copyText(lastLines.join('\n'), 'All UUIDs copied');
            });
        }

        if (downloadButton) {
            downloadButton.addEventListener('click', function () {
                if (!lastLines.length) {
                    setStatus('Generate UUIDs first');
                    return;
                }

                var blob = new Blob([lastLines.join('\n')], { type: 'text/plain' });
                var url = URL.createObjectURL(blob);
                var link = document.createElement('a');
                link.href = url;
                link.download = 'toolexa-uuids.txt';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            });
        }

        if (mode === 'binary-decimal-converter') {
            tool.querySelectorAll('[data-binary-mode], [data-binary-input]').forEach(function (field) {
                field.addEventListener('input', convertBinaryDecimal);
                field.addEventListener('change', convertBinaryDecimal);
            });
        }
    });

    document.querySelectorAll('[data-developer-tool]').forEach(function (tool) {
        var mode = tool.getAttribute('data-developer-mode');
        var input = tool.querySelector('[data-developer-input]');
        var output = tool.querySelector('[data-developer-output]');
        var status = tool.querySelector('[data-developer-status]');
        var error = tool.querySelector('[data-developer-error]');
        var clearButton = tool.querySelector('[data-developer-clear]');
        var downloadButton = tool.querySelector('[data-developer-download]');
        var shareButton = tool.querySelector('[data-share-url]');
        var markdownPreview = tool.querySelector('[data-markdown-preview]');
        var markdownPreviewBody = tool.querySelector('[data-markdown-preview-body]');

        function setStatus(message) {
            if (status) {
                status.textContent = message;
            }
        }

        function setError(message, line) {
            if (!error) {
                return;
            }

            error.hidden = false;
            error.textContent = line ? 'Line ' + line + ': ' + message : message;
            if (input) {
                input.classList.add('input-invalid');
            }
        }

        function clearError() {
            if (error) {
                error.hidden = true;
                error.textContent = '';
            }
            if (input) {
                input.classList.remove('input-invalid');
            }
        }

        function setOutput(text) {
            output.value = text || '';
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function jsonErrorLine(text, message) {
            var match = String(message).match(/position\s+(\d+)/i);
            if (!match) {
                return null;
            }

            var position = Number(match[1]);
            if (!Number.isFinite(position)) {
                return null;
            }

            return text.slice(0, position).split(/\r\n|\r|\n/).length;
        }

        function parseJson() {
            var text = input.value.trim();

            if (!text) {
                throw { message: 'Enter JSON before processing.', line: null };
            }

            try {
                return JSON.parse(text);
            } catch (exception) {
                throw {
                    message: exception.message || 'Invalid JSON syntax.',
                    line: jsonErrorLine(text, exception.message)
                };
            }
        }

        function validateJson() {
            var parsed = parseJson();
            var summary = Array.isArray(parsed)
                ? 'Valid JSON array with ' + parsed.length + ' item' + (parsed.length === 1 ? '' : 's') + '.'
                : 'Valid JSON ' + (parsed !== null && typeof parsed === 'object' ? 'object' : typeof parsed) + '.';

            setOutput(JSON.stringify(parsed, null, 2));
            setStatus(summary);
        }

        function beautifyJson() {
            var parsed = parseJson();
            setOutput(JSON.stringify(parsed, null, 2));
            setStatus('JSON beautified successfully.');
        }

        function minifyJson() {
            var parsed = parseJson();
            setOutput(JSON.stringify(parsed));
            setStatus('JSON minified successfully.');
        }

        function escapeXml(value) {
            return escapeHtml(value).replace(/&#039;/g, '&apos;');
        }

        function xmlTagName(key) {
            var clean = String(key).trim().replace(/[^A-Za-z0-9_.-]/g, '-');
            if (!clean || !/^[A-Za-z_]/.test(clean)) {
                clean = 'item-' + clean;
            }
            return clean.replace(/-+/g, '-');
        }

        function jsonToXmlNode(value, name, depth) {
            var tag = xmlTagName(name || 'item');
            var indent = '  '.repeat(depth);
            var childIndent = '  '.repeat(depth + 1);

            if (Array.isArray(value)) {
                if (!value.length) {
                    return indent + '<' + tag + '></' + tag + '>';
                }

                return value.map(function (item) {
                    return jsonToXmlNode(item, tag, depth);
                }).join('\n');
            }

            if (value !== null && typeof value === 'object') {
                var keys = Object.keys(value);
                if (!keys.length) {
                    return indent + '<' + tag + '></' + tag + '>';
                }

                return indent + '<' + tag + '>\n'
                    + keys.map(function (key) {
                        return jsonToXmlNode(value[key], key, depth + 1);
                    }).join('\n')
                    + '\n' + indent + '</' + tag + '>';
            }

            return indent + '<' + tag + '>' + escapeXml(value === null ? '' : value) + '</' + tag + '>';
        }

        function convertJsonToXml() {
            var parsed = parseJson();
            var body = '<?xml version="1.0" encoding="UTF-8"?>\n' + jsonToXmlNode(parsed, 'root', 0);
            setOutput(body);
            setStatus('JSON converted to XML successfully.');
        }

        function parseXml() {
            var text = input.value.trim();
            var parser;
            var doc;
            var parserError;

            if (!text) {
                throw { message: 'Enter XML before processing.' };
            }

            parser = new DOMParser();
            doc = parser.parseFromString(text, 'application/xml');
            parserError = doc.querySelector('parsererror');

            if (parserError) {
                throw { message: parserError.textContent.replace(/\s+/g, ' ').trim() || 'Invalid XML syntax.' };
            }

            return doc;
        }

        function xmlNodeToJson(node) {
            var result = {};
            var childElements = Array.prototype.filter.call(node.childNodes, function (child) {
                return child.nodeType === 1;
            });
            var text = Array.prototype.filter.call(node.childNodes, function (child) {
                return child.nodeType === 3 && child.nodeValue.trim() !== '';
            }).map(function (child) {
                return child.nodeValue.trim();
            }).join(' ');

            if (node.attributes && node.attributes.length) {
                result['@attributes'] = {};
                Array.prototype.forEach.call(node.attributes, function (attr) {
                    result['@attributes'][attr.name] = attr.value;
                });
            }

            childElements.forEach(function (child) {
                var value = xmlNodeToJson(child);
                if (Object.prototype.hasOwnProperty.call(result, child.nodeName)) {
                    if (!Array.isArray(result[child.nodeName])) {
                        result[child.nodeName] = [result[child.nodeName]];
                    }
                    result[child.nodeName].push(value);
                } else {
                    result[child.nodeName] = value;
                }
            });

            if (text) {
                if (Object.keys(result).length) {
                    result['#text'] = text;
                } else {
                    return text;
                }
            }

            return result;
        }

        function validateXml() {
            var doc = parseXml();
            setOutput(new XMLSerializer().serializeToString(doc));
            setStatus('XML is valid.');
        }

        function convertXmlToJson() {
            var doc = parseXml();
            var root = doc.documentElement;
            var json = {};
            json[root.nodeName] = xmlNodeToJson(root);
            setOutput(JSON.stringify(json, null, 2));
            setStatus('XML converted to JSON successfully.');
        }

        function formatHtmlNode(node, depth) {
            var indent = '  '.repeat(depth);
            var serializer = new XMLSerializer();
            var children;
            var attrs;
            var open;

            if (node.nodeType === 3) {
                return node.nodeValue.trim() ? indent + node.nodeValue.trim() : '';
            }

            if (node.nodeType === 8) {
                return indent + '<!--' + node.nodeValue.trim() + '-->';
            }

            if (node.nodeType !== 1) {
                return '';
            }

            children = Array.prototype.map.call(node.childNodes, function (child) {
                return formatHtmlNode(child, depth + 1);
            }).filter(Boolean);

            attrs = Array.prototype.map.call(node.attributes, function (attr) {
                return attr.name + '="' + attr.value.replace(/"/g, '&quot;') + '"';
            }).join(' ');
            open = '<' + node.tagName.toLowerCase() + (attrs ? ' ' + attrs : '') + '>';

            if (!children.length) {
                return indent + open + '</' + node.tagName.toLowerCase() + '>';
            }

            if (children.length === 1 && node.childNodes.length === 1 && node.firstChild.nodeType === 3) {
                return indent + open + node.firstChild.nodeValue.trim() + '</' + node.tagName.toLowerCase() + '>';
            }

            return indent + open + '\n' + children.join('\n') + '\n' + indent + '</' + node.tagName.toLowerCase() + '>';
        }

        function beautifyHtml() {
            var text = input.value.trim();
            var doc;
            var nodes;

            if (!text) {
                throw { message: 'Enter HTML before processing.' };
            }

            doc = new DOMParser().parseFromString(text, 'text/html');
            nodes = Array.prototype.map.call(doc.body.childNodes, function (node) {
                return formatHtmlNode(node, 0);
            }).filter(Boolean);
            setOutput(nodes.join('\n'));
            setStatus('HTML beautified successfully.');
        }

        function minifyHtml() {
            var text = input.value.trim();

            if (!text) {
                throw { message: 'Enter HTML before processing.' };
            }

            setOutput(text.replace(/<!--[\s\S]*?-->/g, '').replace(/>\s+</g, '><').replace(/\s{2,}/g, ' ').trim());
            setStatus('HTML minified successfully.');
        }

        function minifyCss() {
            var text = input.value.trim();

            if (!text) {
                throw { message: 'Enter CSS before processing.' };
            }

            setOutput(text
                .replace(/\/\*[\s\S]*?\*\//g, '')
                .replace(/\s+/g, ' ')
                .replace(/\s*([{}:;,>~+])\s*/g, '$1')
                .replace(/;}/g, '}')
                .trim());
            setStatus('CSS minified successfully.');
        }

        function beautifyCss() {
            var text = input.value.trim();
            var formatted;
            var level = 0;

            if (!text) {
                throw { message: 'Enter CSS before processing.' };
            }

            formatted = text
                .replace(/\/\*[\s\S]*?\*\//g, function (comment) {
                    return '\n' + comment.trim() + '\n';
                })
                .replace(/\s*{\s*/g, ' {\n')
                .replace(/;\s*/g, ';\n')
                .replace(/\s*}\s*/g, '\n}\n')
                .replace(/,\s*/g, ',\n')
                .split('\n')
                .map(function (line) {
                    line = line.trim();
                    if (!line) {
                        return '';
                    }
                    if (line.charAt(0) === '}') {
                        level = Math.max(0, level - 1);
                    }
                    var outputLine = '  '.repeat(level) + line;
                    if (line.charAt(line.length - 1) === '{') {
                        level += 1;
                    }
                    return outputLine;
                })
                .filter(Boolean)
                .join('\n');

            setOutput(formatted);
            setStatus('CSS formatted successfully.');
        }

        function htmlInlineMarkdown(node) {
            var text = '';

            Array.prototype.forEach.call(node.childNodes, function (child) {
                var tag;

                if (child.nodeType === 3) {
                    text += child.nodeValue.replace(/\s+/g, ' ');
                    return;
                }

                if (child.nodeType !== 1) {
                    return;
                }

                tag = child.tagName.toLowerCase();

                if (tag === 'strong' || tag === 'b') {
                    text += '**' + htmlInlineMarkdown(child).trim() + '**';
                } else if (tag === 'em' || tag === 'i') {
                    text += '*' + htmlInlineMarkdown(child).trim() + '*';
                } else if (tag === 'code') {
                    text += '`' + child.textContent.trim() + '`';
                } else if (tag === 'a') {
                    text += '[' + htmlInlineMarkdown(child).trim() + '](' + (child.getAttribute('href') || '') + ')';
                } else if (tag === 'img') {
                    text += '![' + (child.getAttribute('alt') || '') + '](' + (child.getAttribute('src') || '') + ')';
                } else if (tag === 'br') {
                    text += '\n';
                } else {
                    text += htmlInlineMarkdown(child);
                }
            });

            return text.replace(/[ \t]+\n/g, '\n').trim();
        }

        function htmlBlockMarkdown(node, depth, index) {
            var tag;
            var prefix;

            if (node.nodeType === 3) {
                return node.nodeValue.trim();
            }

            if (node.nodeType !== 1) {
                return '';
            }

            tag = node.tagName.toLowerCase();

            if (/^h[1-6]$/.test(tag)) {
                return '#'.repeat(Number(tag.charAt(1))) + ' ' + htmlInlineMarkdown(node);
            }

            if (tag === 'p') {
                return htmlInlineMarkdown(node);
            }

            if (tag === 'blockquote') {
                return htmlInlineMarkdown(node).split('\n').map(function (line) {
                    return '> ' + line;
                }).join('\n');
            }

            if (tag === 'pre') {
                return '```\n' + node.textContent.trim() + '\n```';
            }

            if (tag === 'ul' || tag === 'ol') {
                return Array.prototype.map.call(node.children, function (child, childIndex) {
                    prefix = tag === 'ol' ? (childIndex + 1) + '. ' : '- ';
                    return prefix + htmlInlineMarkdown(child);
                }).join('\n');
            }

            if (tag === 'hr') {
                return '---';
            }

            return Array.prototype.map.call(node.childNodes, function (child, childIndex) {
                return htmlBlockMarkdown(child, depth + 1, childIndex);
            }).filter(Boolean).join('\n\n');
        }

        function convertHtmlToMarkdown() {
            var text = input.value.trim();
            var doc;
            var markdown;

            if (!text) {
                throw { message: 'Enter HTML before processing.' };
            }

            doc = new DOMParser().parseFromString(text, 'text/html');
            markdown = Array.prototype.map.call(doc.body.childNodes, function (node, index) {
                return htmlBlockMarkdown(node, 0, index);
            }).filter(Boolean).join('\n\n');

            setOutput(markdown);
            setStatus('HTML converted to Markdown successfully.');
        }

        function markdownInline(text) {
            return escapeHtml(text)
                .replace(/!\[([^\]]*)\]\(([^)]+)\)/g, '<img src="$2" alt="$1">')
                .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2">$1</a>')
                .replace(/`([^`]+)`/g, '<code>$1</code>')
                .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
                .replace(/\*([^*]+)\*/g, '<em>$1</em>');
        }

        function convertMarkdownToHtml() {
            var text = input.value.replace(/\r\n/g, '\n').trim();
            var lines;
            var html = [];
            var index = 0;
            var inCode = false;
            var codeLines = [];

            if (!text) {
                throw { message: 'Enter Markdown before processing.' };
            }

            lines = text.split('\n');

            function closeParagraph(buffer) {
                if (buffer.length) {
                    html.push('<p>' + markdownInline(buffer.join(' ')) + '</p>');
                    buffer.length = 0;
                }
            }

            var paragraph = [];

            while (index < lines.length) {
                var line = lines[index];
                var trimmed = line.trim();
                var listItems = [];
                var ordered = false;

                if (/^```/.test(trimmed)) {
                    if (inCode) {
                        html.push('<pre><code>' + escapeHtml(codeLines.join('\n')) + '</code></pre>');
                        codeLines = [];
                        inCode = false;
                    } else {
                        closeParagraph(paragraph);
                        inCode = true;
                    }
                    index += 1;
                    continue;
                }

                if (inCode) {
                    codeLines.push(line);
                    index += 1;
                    continue;
                }

                if (!trimmed) {
                    closeParagraph(paragraph);
                    index += 1;
                    continue;
                }

                if (/^---+$/.test(trimmed)) {
                    closeParagraph(paragraph);
                    html.push('<hr>');
                    index += 1;
                    continue;
                }

                if (/^#{1,6}\s+/.test(trimmed)) {
                    closeParagraph(paragraph);
                    var level = trimmed.match(/^#+/)[0].length;
                    html.push('<h' + level + '>' + markdownInline(trimmed.replace(/^#{1,6}\s+/, '')) + '</h' + level + '>');
                    index += 1;
                    continue;
                }

                if (/^>\s?/.test(trimmed)) {
                    closeParagraph(paragraph);
                    html.push('<blockquote>' + markdownInline(trimmed.replace(/^>\s?/, '')) + '</blockquote>');
                    index += 1;
                    continue;
                }

                if (/^(-|\*)\s+/.test(trimmed) || /^\d+\.\s+/.test(trimmed)) {
                    closeParagraph(paragraph);
                    ordered = /^\d+\.\s+/.test(trimmed);
                    while (index < lines.length) {
                        trimmed = lines[index].trim();
                        if (ordered && !/^\d+\.\s+/.test(trimmed)) break;
                        if (!ordered && !/^(-|\*)\s+/.test(trimmed)) break;
                        listItems.push('<li>' + markdownInline(trimmed.replace(/^(\d+\.|-|\*)\s+/, '')) + '</li>');
                        index += 1;
                    }
                    html.push((ordered ? '<ol>' : '<ul>') + '\n' + listItems.join('\n') + '\n' + (ordered ? '</ol>' : '</ul>'));
                    continue;
                }

                paragraph.push(trimmed);
                index += 1;
            }

            closeParagraph(paragraph);
            if (inCode) {
                html.push('<pre><code>' + escapeHtml(codeLines.join('\n')) + '</code></pre>');
            }

            setOutput(html.join('\n'));
            if (markdownPreview && markdownPreviewBody) {
                markdownPreview.hidden = false;
                markdownPreviewBody.innerHTML = output.value;
            }
            setStatus('Markdown converted to HTML successfully.');
        }

        function encodeBase64() {
            var text = input.value;

            if (!text) {
                throw { message: 'Enter text before encoding.' };
            }

            setOutput(btoa(unescape(encodeURIComponent(text))));
            setStatus('Text encoded to Base64 successfully.');
        }

        function decodeBase64() {
            var text = input.value.trim();
            var decoded;

            if (!text) {
                throw { message: 'Enter Base64 before decoding.' };
            }

            if (!/^[A-Za-z0-9+/]+={0,2}$/.test(text) || text.length % 4 === 1) {
                throw { message: 'Invalid Base64 input.' };
            }

            try {
                decoded = decodeURIComponent(escape(atob(text)));
            } catch (exception) {
                throw { message: 'Invalid Base64 input.' };
            }

            setOutput(decoded);
            setStatus('Base64 decoded successfully.');
        }

        function stripSqlComments(text) {
            return text.replace(/--.*$/gm, '').replace(/\/\*[\s\S]*?\*\//g, '');
        }

        function beautifySql() {
            var text = input.value.trim();
            var keywords = /\b(SELECT|FROM|WHERE|INNER JOIN|LEFT JOIN|RIGHT JOIN|FULL JOIN|JOIN|ON|GROUP BY|ORDER BY|HAVING|LIMIT|OFFSET|VALUES|SET|AND|OR|INSERT INTO|UPDATE|DELETE FROM)\b/gi;
            var formatted;

            if (!text) {
                throw { message: 'Enter SQL before processing.' };
            }

            formatted = text
                .replace(/\s+/g, ' ')
                .replace(keywords, function (match) {
                    var upper = match.toUpperCase();
                    return '\n' + (upper === 'AND' || upper === 'OR' ? '  ' + upper : upper);
                })
                .replace(/\s*,\s*/g, ',\n  ')
                .replace(/\(\s*/g, '(\n  ')
                .replace(/\s*\)/g, '\n)')
                .replace(/\n{2,}/g, '\n')
                .trim();

            setOutput(formatted);
            setStatus('SQL beautified successfully.');
        }

        function minifySql() {
            var text = input.value.trim();

            if (!text) {
                throw { message: 'Enter SQL before processing.' };
            }

            setOutput(stripSqlComments(text).replace(/\s+/g, ' ').replace(/\s*([(),=<>+\-*\/])\s*/g, '$1').trim());
            setStatus('SQL minified successfully.');
        }

        function run(action) {
            clearError();
            if (markdownPreview && mode !== 'markdown-to-html-converter') {
                markdownPreview.hidden = true;
            }

            try {
                if (mode === 'json-formatter' && action === 'beautify') {
                    beautifyJson();
                } else if (mode === 'json-formatter' && action === 'minify') {
                    minifyJson();
                } else if ((mode === 'json-formatter' || mode === 'json-validator') && action === 'validate') {
                    validateJson();
                } else if (mode === 'json-to-xml-converter') {
                    convertJsonToXml();
                } else if (mode === 'xml-to-json-converter' && action === 'validate') {
                    validateXml();
                } else if (mode === 'xml-to-json-converter') {
                    convertXmlToJson();
                } else if (mode === 'html-formatter' && action === 'minify') {
                    minifyHtml();
                } else if (mode === 'html-formatter') {
                    beautifyHtml();
                } else if (mode === 'css-minifier') {
                    minifyCss();
                } else if (mode === 'css-beautifier') {
                    beautifyCss();
                } else if (mode === 'html-to-markdown-converter') {
                    convertHtmlToMarkdown();
                } else if (mode === 'markdown-to-html-converter') {
                    convertMarkdownToHtml();
                } else if (mode === 'base64-encoder-decoder' && action === 'decode') {
                    decodeBase64();
                } else if (mode === 'base64-encoder-decoder') {
                    encodeBase64();
                } else if (mode === 'sql-formatter' && action === 'minify') {
                    minifySql();
                } else if (mode === 'sql-formatter') {
                    beautifySql();
                }
            } catch (exception) {
                setOutput('');
                setStatus('Please fix the highlighted input.');
                setError(exception.message || 'Unable to process this input.', exception.line);
                if (markdownPreview) {
                    markdownPreview.hidden = true;
                }
            }
        }

        function downloadOutput(extension) {
            var text = output.value;
            var types = {
                json: 'application/json',
                xml: 'application/xml',
                html: 'text/html',
                css: 'text/css',
                md: 'text/markdown',
                txt: 'text/plain',
                sql: 'application/sql'
            };
            var blob;
            var url;
            var link;

            if (!text) {
                setStatus('Generate output before downloading.');
                return;
            }

            blob = new Blob([text], { type: (types[extension] || 'text/plain') + ';charset=utf-8' });
            url = URL.createObjectURL(blob);
            link = document.createElement('a');
            link.href = url;
            link.download = 'toolexa-' + mode + '.' + extension;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        tool.querySelectorAll('[data-developer-action]').forEach(function (button) {
            button.addEventListener('click', function () {
                run(button.getAttribute('data-developer-action'));
            });
        });

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                input.value = '';
                setOutput('');
                clearError();
                if (markdownPreview) {
                    markdownPreview.hidden = true;
                }
                if (markdownPreviewBody) {
                    markdownPreviewBody.innerHTML = '';
                }
                setStatus('Result will appear here.');
            });
        }

        if (downloadButton) {
            downloadButton.addEventListener('click', function () {
                downloadOutput(downloadButton.getAttribute('data-developer-download'));
            });
        }

        if (mode === 'markdown-to-html-converter' && input) {
            input.addEventListener('input', function () {
                if (!input.value.trim()) {
                    setOutput('');
                    clearError();
                    if (markdownPreview) {
                        markdownPreview.hidden = true;
                    }
                    if (markdownPreviewBody) {
                        markdownPreviewBody.innerHTML = '';
                    }
                    setStatus('Result will appear here.');
                    return;
                }

                run('convert');
            });
        }

        if (shareButton) {
            shareButton.addEventListener('click', function () {
                if (navigator.share) {
                    navigator.share({ title: document.title, url: window.location.href }).catch(function () {});
                    return;
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(window.location.href).then(function () {
                        setStatus('Share link copied.');
                    });
                }
            });
        }
    });

    document.querySelectorAll('[data-pdf-tool]').forEach(function (tool) {
        var mode = tool.getAttribute('data-pdf-mode');
        var input = tool.querySelector('[data-pdf-input]');
        var processButton = tool.querySelector('[data-pdf-process]');
        var clearButton = tool.querySelector('[data-pdf-clear]');
        var result = tool.querySelector('[data-pdf-result]');
        var list = tool.querySelector('[data-pdf-list]');
        var copySource = tool.querySelector('[data-pdf-copy-source]');
        var downloadButton = tool.querySelector('[data-pdf-download]');
        var status = tool.querySelector('[data-pdf-status]');
        var files = [];
        var outputBytes = null;

        function setStatus(message) {
            if (!status) return;
            status.textContent = message;
            window.setTimeout(function () { status.textContent = ''; }, 2400);
        }

        function bytes(size) {
            if (!size) return '0 KB';
            if (size < 1024 * 1024) return Math.round(size / 1024) + ' KB';
            return (size / (1024 * 1024)).toFixed(2) + ' MB';
        }

        function pdfLibReady() {
            return window.PDFLib && window.PDFLib.PDFDocument;
        }

        function setCopy(text) {
            copySource.value = text;
        }

        function renderResult(rows) {
            result.innerHTML = '';
            var wrap = document.createElement('div');
            wrap.className = 'browser-result-list';

            rows.forEach(function (row) {
                var item = document.createElement('div');
                var label = document.createElement('code');
                label.textContent = row[0] + ': ' + row[1];
                item.appendChild(label);
                wrap.appendChild(item);
            });

            result.appendChild(wrap);
            setCopy(rows.map(function (row) { return row[0] + ': ' + row[1]; }).join('\n'));
        }

        function renderMessage(message) {
            result.innerHTML = '<span>' + message + '</span>';
            setCopy(message);
        }

        function resetDownload() {
            outputBytes = null;
            downloadButton.classList.add('disabled');
        }

        function renderFiles() {
            list.innerHTML = '';

            files.forEach(function (file, index) {
                var row = document.createElement('div');
                row.className = 'pdf-file-row';
                row.draggable = true;
                row.dataset.index = index;

                var name = document.createElement('span');
                name.textContent = (index + 1) + '. ' + file.name;

                var meta = document.createElement('small');
                meta.textContent = bytes(file.size);

                var remove = document.createElement('button');
                remove.className = 'btn btn-secondary btn-sm';
                remove.type = 'button';
                remove.textContent = 'Remove';
                remove.addEventListener('click', function () {
                    files.splice(index, 1);
                    resetDownload();
                    renderFiles();
                });

                row.appendChild(name);
                row.appendChild(meta);
                row.appendChild(remove);
                list.appendChild(row);
            });

            if (!files.length) {
                list.innerHTML = '';
            }
        }

        function fileBuffer(file) {
            return file.arrayBuffer();
        }

        function pdfVersion(buffer) {
            var header = new TextDecoder('latin1').decode(new Uint8Array(buffer.slice(0, 16)));
            var match = header.match(/%PDF-(\d\.\d)/);
            return match ? match[1] : 'Unavailable';
        }

        async function loadPdf(file, options) {
            return window.PDFLib.PDFDocument.load(await fileBuffer(file), options || {});
        }

        async function countPages() {
            if (!pdfLibReady()) return renderMessage('PDF library is still loading. Try again in a moment.');
            if (!files[0]) return renderMessage('Choose a PDF file first.');
            resetDownload();
            try {
                var buffer = await fileBuffer(files[0]);
                var pdf = await window.PDFLib.PDFDocument.load(buffer);
                renderResult([
                    ['File', files[0].name],
                    ['Total Pages', pdf.getPageCount()],
                    ['File Size', bytes(files[0].size)],
                    ['PDF Version', pdfVersion(buffer)]
                ]);
            } catch (error) {
                renderMessage('Could not read this PDF. It may be encrypted or damaged.');
            }
        }

        async function metadata() {
            if (!pdfLibReady()) return renderMessage('PDF library is still loading. Try again in a moment.');
            if (!files[0]) return renderMessage('Choose a PDF file first.');
            resetDownload();
            try {
                var pdf = await loadPdf(files[0]);
                renderResult([
                    ['Title', pdf.getTitle() || 'Unavailable'],
                    ['Author', pdf.getAuthor() || 'Unavailable'],
                    ['Subject', pdf.getSubject() || 'Unavailable'],
                    ['Creator', pdf.getCreator() || 'Unavailable'],
                    ['Producer', pdf.getProducer() || 'Unavailable'],
                    ['Creation Date', pdf.getCreationDate() ? pdf.getCreationDate().toString() : 'Unavailable'],
                    ['Modification Date', pdf.getModificationDate() ? pdf.getModificationDate().toString() : 'Unavailable'],
                    ['Page Count', pdf.getPageCount()],
                    ['File Size', bytes(files[0].size)]
                ]);
            } catch (error) {
                renderMessage('Could not read metadata. The PDF may be encrypted or damaged.');
            }
        }

        async function passwordCheck() {
            if (!pdfLibReady()) return renderMessage('PDF library is still loading. Try again in a moment.');
            if (!files[0]) return renderMessage('Choose a PDF file first.');
            resetDownload();
            try {
                var pdf = await loadPdf(files[0]);
                renderResult([
                    ['Encryption Status', 'Readable / not password blocked'],
                    ['Page Count', pdf.getPageCount()],
                    ['Message', 'The PDF can be opened by the browser library.']
                ]);
            } catch (error) {
                renderResult([
                    ['Encryption Status', 'Protected or unreadable'],
                    ['Page Count', 'Unavailable'],
                    ['Message', 'The PDF may be password protected, encrypted or damaged.']
                ]);
            }
        }

        function imageData(file) {
            return new Promise(function (resolve, reject) {
                var reader = new FileReader();
                reader.onload = function () {
                    var image = new Image();
                    image.onload = function () { resolve({ image: image, dataUrl: reader.result }); };
                    image.onerror = reject;
                    image.src = reader.result;
                };
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        async function imageToPdf() {
            if (!pdfLibReady()) return renderMessage('PDF library is still loading. Try again in a moment.');
            if (!files.length) return renderMessage('Choose one or more images first.');
            var pdf = await window.PDFLib.PDFDocument.create();

            for (var index = 0; index < files.length; index++) {
                var item = await imageData(files[index]);
                var imageBytes = await fetch(item.dataUrl).then(function (response) { return response.arrayBuffer(); });
                var embedded = files[index].type === 'image/png'
                    ? await pdf.embedPng(imageBytes)
                    : await pdf.embedJpg(files[index].type === 'image/webp' ? await webpToJpegBytes(item.image) : imageBytes);
                var page = pdf.addPage([embedded.width, embedded.height]);
                page.drawImage(embedded, { x: 0, y: 0, width: embedded.width, height: embedded.height });
            }

            outputBytes = await pdf.save();
            downloadButton.classList.remove('disabled');
            renderResult([
                ['Images', files.length],
                ['Output', 'PDF ready for download'],
                ['Estimated Size', bytes(outputBytes.length)]
            ]);
        }

        function webpToJpegBytes(image) {
            var canvas = document.createElement('canvas');
            canvas.width = image.naturalWidth;
            canvas.height = image.naturalHeight;
            canvas.getContext('2d').drawImage(image, 0, 0);
            return fetch(canvas.toDataURL('image/jpeg', .92)).then(function (response) {
                return response.arrayBuffer();
            });
        }

        async function mergePdf() {
            if (!pdfLibReady()) return renderMessage('PDF library is still loading. Try again in a moment.');
            if (files.length < 2) return renderMessage('Choose at least two PDF files to merge.');
            var merged = await window.PDFLib.PDFDocument.create();

            try {
                for (var index = 0; index < files.length; index++) {
                    var source = await loadPdf(files[index]);
                    var copied = await merged.copyPages(source, source.getPageIndices());
                    copied.forEach(function (page) { merged.addPage(page); });
                }

                outputBytes = await merged.save();
                downloadButton.classList.remove('disabled');
                renderResult([
                    ['Files Merged', files.length],
                    ['Total Pages', merged.getPageCount()],
                    ['Output', 'Merged PDF ready for download']
                ]);
            } catch (error) {
                renderMessage('Could not merge one or more PDFs. Remove encrypted or damaged files and try again.');
            }
        }

        function process() {
            if (mode === 'image-to-pdf-converter') imageToPdf();
            if (mode === 'pdf-page-counter') countPages();
            if (mode === 'pdf-metadata-viewer') metadata();
            if (mode === 'pdf-password-checker') passwordCheck();
            if (mode === 'pdf-merger') mergePdf();
        }

        if (input) {
            input.addEventListener('change', function () {
                files = Array.from(input.files || []);
                resetDownload();
                renderFiles();
                renderMessage(files.length ? files.length + ' file(s) selected.' : 'Select a file to preview details.');
            });
        }

        if (processButton) processButton.addEventListener('click', process);

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                files = [];
                input.value = '';
                list.innerHTML = '';
                resetDownload();
                renderMessage('Select a file to preview details.');
            });
        }

        list.addEventListener('dragstart', function (event) {
            var row = event.target.closest('.pdf-file-row');
            if (row) event.dataTransfer.setData('text/plain', row.dataset.index);
        });

        list.addEventListener('dragover', function (event) {
            event.preventDefault();
        });

        list.addEventListener('drop', function (event) {
            event.preventDefault();
            var from = Number(event.dataTransfer.getData('text/plain'));
            var row = event.target.closest('.pdf-file-row');
            if (!row || !Number.isFinite(from)) return;
            var to = Number(row.dataset.index);
            var moved = files.splice(from, 1)[0];
            files.splice(to, 0, moved);
            resetDownload();
            renderFiles();
        });

        if (downloadButton) {
            downloadButton.addEventListener('click', function () {
                if (!outputBytes) return setStatus('Create the PDF first.');
                var blob = new Blob([outputBytes], { type: 'application/pdf' });
                var url = URL.createObjectURL(blob);
                var link = document.createElement('a');
                link.href = url;
                link.download = mode === 'pdf-merger' ? 'toolexa-merged.pdf' : 'toolexa-output.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            });
        }
    });

    document.querySelectorAll('[data-seller-tool]').forEach(function (tool) {
        var mode = tool.getAttribute('data-seller-mode');
        var layoutField = tool.querySelector('[data-seller-layout]');
        var layoutOptions = tool.querySelectorAll('[data-seller-layout-option]');
        var input = tool.querySelector('[data-seller-input]');
        var processButton = tool.querySelector('[data-seller-process]');
        var clearButton = tool.querySelector('[data-seller-clear]');
        var result = tool.querySelector('[data-seller-result]');
        var copySource = tool.querySelector('[data-seller-copy-source]');
        var downloadButton = tool.querySelector('[data-seller-download]');
        var status = tool.querySelector('[data-seller-status]');
        var outputBytes = null;

        var cropPresets = {
            'meesho-label-cropper': {
                standard_a4: { left: .07, top: .07, width: .86, height: .64 },
                invoice_top: { left: .05, top: .04, width: .9, height: .54 }
            },
            'amazon-label-cropper': {
                auto: { left: .07, top: .06, width: .86, height: .6 },
                a4_top: { left: .06, top: .05, width: .88, height: .58 },
                a4_center: { left: .08, top: .2, width: .84, height: .58 }
            },
            'flipkart-label-cropper': {
                standard_a4: { left: .07, top: .08, width: .86, height: .62 },
                top_label: { left: .06, top: .05, width: .88, height: .56 }
            },
            'myntra-label-cropper': {
                standard_a4: { left: .08, top: .08, width: .84, height: .62 },
                shipping_block: { left: .07, top: .12, width: .86, height: .58 }
            },
            'ajio-label-cropper': {
                standard_a4: { left: .08, top: .08, width: .84, height: .62 },
                top_label: { left: .06, top: .06, width: .88, height: .56 }
            }
        };

        function setStatus(message) {
            if (!status) return;
            status.textContent = message;
            window.setTimeout(function () { status.textContent = ''; }, 2400);
        }

        function setCopy(text) {
            copySource.value = text;
        }

        function renderMessage(message) {
            result.innerHTML = '<span>' + message + '</span>';
            setCopy(message);
        }

        function renderRows(rows) {
            result.innerHTML = '';
            var wrap = document.createElement('div');
            wrap.className = 'browser-result-list';

            rows.forEach(function (row) {
                var item = document.createElement('div');
                var code = document.createElement('code');
                code.textContent = row[0] + ': ' + row[1];
                item.appendChild(code);
                wrap.appendChild(item);
            });

            result.appendChild(wrap);
            setCopy(rows.map(function (row) { return row[0] + ': ' + row[1]; }).join('\n'));
        }

        function resetOutput() {
            outputBytes = null;
            downloadButton.classList.add('disabled');
        }

        function presetFor(page) {
            var selected = layoutField.value;
            var presets = cropPresets[mode] || {};

            if (mode === 'amazon-label-cropper' && selected === 'auto') {
                var size = page.getSize();
                return size.height >= size.width
                    ? presets.a4_top
                    : { left: .05, top: .05, width: .9, height: .9 };
            }

            return presets[selected] || presets.standard_a4 || { left: .07, top: .07, width: .86, height: .62 };
        }

        function cropBox(page, preset) {
            var size = page.getSize();
            var left = size.width * preset.left;
            var top = size.height * preset.top;
            var width = size.width * preset.width;
            var height = size.height * preset.height;

            return {
                left: left,
                bottom: Math.max(0, size.height - top - height),
                right: Math.min(size.width, left + width),
                top: Math.min(size.height, size.height - top)
            };
        }

        async function processSellerLabel() {
            if (!window.PDFLib || !window.PDFLib.PDFDocument) {
                renderMessage('PDF library is still loading. Try again in a moment.');
                return;
            }

            if (!input.files || !input.files[0]) {
                renderMessage('Upload a PDF first.');
                return;
            }

            resetOutput();

            try {
                var file = input.files[0];
                var source = await window.PDFLib.PDFDocument.load(await file.arrayBuffer());
                var output = await window.PDFLib.PDFDocument.create();
                var pages = source.getPages();
                var outputWidth = 288;
                var outputHeight = 432;

                for (var index = 0; index < pages.length; index++) {
                    var page = pages[index];
                    var box = cropBox(page, presetFor(page));
                    var embedded = await output.embedPage(page, box);
                    var scale = Math.min(outputWidth / embedded.width, outputHeight / embedded.height);
                    var drawWidth = embedded.width * scale;
                    var drawHeight = embedded.height * scale;
                    var outPage = output.addPage([outputWidth, outputHeight]);

                    outPage.drawPage(embedded, {
                        x: (outputWidth - drawWidth) / 2,
                        y: (outputHeight - drawHeight) / 2,
                        width: drawWidth,
                        height: drawHeight
                    });
                }

                outputBytes = await output.save();
                downloadButton.classList.remove('disabled');
                renderRows([
                    ['File', file.name],
                    ['Pages Processed', pages.length],
                    ['Output Size', '4x6 PDF'],
                    ['Layout', (tool.querySelector('[data-seller-layout-option]:checked') || {}).closest ? tool.querySelector('[data-seller-layout-option]:checked').closest('.seller-layout-card').querySelector('strong').textContent : layoutField.value],
                    ['Status', 'Cropped PDF ready for download']
                ]);
                setStatus('Label PDF ready');
            } catch (error) {
                renderMessage('Could not process this PDF. It may be encrypted, damaged or using an unsupported layout.');
            }
        }

        if (input) {
            input.addEventListener('change', function () {
                resetOutput();
                renderMessage(input.files && input.files[0] ? input.files[0].name + ' selected.' : 'Upload a PDF and click Process Label.');
            });
        }

        layoutOptions.forEach(function (option) {
            option.addEventListener('change', function () {
                layoutField.value = option.value;
                tool.querySelectorAll('.seller-layout-card').forEach(function (card) {
                    card.classList.toggle('is-selected', card.contains(option));
                });
                resetOutput();
                renderMessage(input.files && input.files[0] ? input.files[0].name + ' selected. Click Process Label.' : 'Upload a PDF and click Process Label.');
            });
        });

        if (processButton) {
            processButton.addEventListener('click', processSellerLabel);
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                input.value = '';
                resetOutput();
                renderMessage('Upload a PDF and click Process Label.');
            });
        }

        if (downloadButton) {
            downloadButton.addEventListener('click', function () {
                if (!outputBytes) {
                    setStatus('Process the label first.');
                    return;
                }

                var blob = new Blob([outputBytes], { type: 'application/pdf' });
                var url = URL.createObjectURL(blob);
                var link = document.createElement('a');
                link.href = url;
                link.download = mode.replace(/-/g, '-') + '-output.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            });
        }

        tool.querySelectorAll('[data-share-url]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (navigator.share) {
                    navigator.share({ title: document.title, url: window.location.href }).catch(function () {});
                    return;
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(window.location.href).then(function () {
                        setStatus('Share link copied');
                    });
                }
            });
        });
    });

    document.querySelectorAll('[data-local-tool]').forEach(function (tool) {
        var mode = tool.getAttribute('data-local-mode');
        var output = tool.querySelector('[data-local-output]');
        var preview = tool.querySelector('[data-local-preview]');
        var clearButton = tool.querySelector('[data-local-clear]');
        var downloadButtons = tool.querySelectorAll('[data-local-download]');
        var lastSvg = '';
        var lastPngCanvas = null;
        var faviconAssets = [];
        var pickedPalette = [];

        function setOutput(text) {
            output.value = text || '';
        }

        function downloadText(text, filename, type) {
            var blob = new Blob([text], { type: type + ';charset=utf-8' });
            var url = URL.createObjectURL(blob);
            var link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        function clamp(value, min, max) {
            return Math.max(min, Math.min(max, Number(value) || 0));
        }

        function rgbToHex(r, g, b) {
            return '#' + [r, g, b].map(function (value) {
                return Math.round(clamp(value, 0, 255)).toString(16).padStart(2, '0');
            }).join('');
        }

        function hexToRgb(hex) {
            var clean = String(hex || '').trim().replace(/^#/, '');
            if (clean.length === 3) {
                clean = clean.split('').map(function (char) { return char + char; }).join('');
            }
            if (!/^[0-9a-f]{6}$/i.test(clean)) {
                return null;
            }
            return {
                r: parseInt(clean.slice(0, 2), 16),
                g: parseInt(clean.slice(2, 4), 16),
                b: parseInt(clean.slice(4, 6), 16)
            };
        }

        function rgbToHsl(r, g, b) {
            r /= 255; g /= 255; b /= 255;
            var max = Math.max(r, g, b);
            var min = Math.min(r, g, b);
            var h = 0;
            var s = 0;
            var l = (max + min) / 2;
            var d;

            if (max !== min) {
                d = max - min;
                s = l > .5 ? d / (2 - max - min) : d / (max + min);
                if (max === r) h = (g - b) / d + (g < b ? 6 : 0);
                if (max === g) h = (b - r) / d + 2;
                if (max === b) h = (r - g) / d + 4;
                h /= 6;
            }

            return { h: Math.round(h * 360), s: Math.round(s * 100), l: Math.round(l * 100) };
        }

        function hslToRgb(h, s, l) {
            h = clamp(h, 0, 360) / 360;
            s = clamp(s, 0, 100) / 100;
            l = clamp(l, 0, 100) / 100;
            var r;
            var g;
            var b;

            function hue(p, q, t) {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1 / 6) return p + (q - p) * 6 * t;
                if (t < 1 / 2) return q;
                if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                return p;
            }

            if (s === 0) {
                r = g = b = l;
            } else {
                var q = l < .5 ? l * (1 + s) : l + s - l * s;
                var p = 2 * l - q;
                r = hue(p, q, h + 1 / 3);
                g = hue(p, q, h);
                b = hue(p, q, h - 1 / 3);
            }

            return { r: Math.round(r * 255), g: Math.round(g * 255), b: Math.round(b * 255) };
        }

        function renderColor(rgb) {
            var hex = rgbToHex(rgb.r, rgb.g, rgb.b);
            var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
            var swatch = tool.querySelector('[data-color-preview]');
            tool.querySelector('[data-color-hex]').value = hex;
            tool.querySelector('[data-color-r]').value = rgb.r;
            tool.querySelector('[data-color-g]').value = rgb.g;
            tool.querySelector('[data-color-b]').value = rgb.b;
            tool.querySelector('[data-color-h]').value = hsl.h;
            tool.querySelector('[data-color-s]').value = hsl.s;
            tool.querySelector('[data-color-l]').value = hsl.l;
            if (swatch) swatch.style.background = hex;
            setOutput(['HEX: ' + hex, 'RGB: rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')', 'HSL: hsl(' + hsl.h + ', ' + hsl.s + '%, ' + hsl.l + '%)'].join('\n'));
        }

        function processColor(action) {
            var rgb;
            if (action === 'hex') {
                rgb = hexToRgb(tool.querySelector('[data-color-hex]').value);
                if (!rgb) {
                    setOutput('Enter a valid HEX color.');
                    return;
                }
            } else if (action === 'hsl-rgb') {
                rgb = hslToRgb(tool.querySelector('[data-color-h]').value, tool.querySelector('[data-color-s]').value, tool.querySelector('[data-color-l]').value);
            } else {
                rgb = {
                    r: clamp(tool.querySelector('[data-color-r]').value, 0, 255),
                    g: clamp(tool.querySelector('[data-color-g]').value, 0, 255),
                    b: clamp(tool.querySelector('[data-color-b]').value, 0, 255)
                };
            }
            renderColor(rgb);
        }

        var code39 = {
            '0': 'nnnwwnwnn', '1': 'wnnwnnnnw', '2': 'nnwwnnnnw', '3': 'wnwwnnnnn', '4': 'nnnwwnnnw',
            '5': 'wnnwwnnnn', '6': 'nnwwwnnnn', '7': 'nnnwnnwnw', '8': 'wnnwnnwnn', '9': 'nnwwnnwnn',
            'A': 'wnnnnwnnw', 'B': 'nnwnnwnnw', 'C': 'wnwnnwnnn', 'D': 'nnnnwwnnw', 'E': 'wnnnwwnnn',
            'F': 'nnwnwwnnn', 'G': 'nnnnnwwnw', 'H': 'wnnnnwwnn', 'I': 'nnwnnwwnn', 'J': 'nnnnwwwnn',
            'K': 'wnnnnnnww', 'L': 'nnwnnnnww', 'M': 'wnwnnnnwn', 'N': 'nnnnwnnww', 'O': 'wnnnwnnwn',
            'P': 'nnwnwnnwn', 'Q': 'nnnnnnwww', 'R': 'wnnnnnwwn', 'S': 'nnwnnnwwn', 'T': 'nnnnwnwwn',
            'U': 'wwnnnnnnw', 'V': 'nwwnnnnnw', 'W': 'wwwnnnnnn', 'X': 'nwnnwnnnw', 'Y': 'wwnnwnnnn',
            'Z': 'nwwnwnnnn', '-': 'nwnnnnwnw', '.': 'wwnnnnwnn', ' ': 'nwwnnnwnn', '$': 'nwnwnwnnn',
            '/': 'nwnwnnnwn', '+': 'nwnnnwnwn', '%': 'nnnwnwnwn', '*': 'nwnnwnwnn'
        };

        var eanL = ['0001101','0011001','0010011','0111101','0100011','0110001','0101111','0111011','0110111','0001011'];
        var eanG = ['0100111','0110011','0011011','0100001','0011101','0111001','0000101','0010001','0001001','0010111'];
        var eanR = ['1110010','1100110','1101100','1000010','1011100','1001110','1010000','1000100','1001000','1110100'];
        var eanParity = ['LLLLLL','LLGLGG','LLGGLG','LLGGGL','LGLLGG','LGGLLG','LGGGLL','LGLGLG','LGLGGL','LGGLGL'];

        function checksumUpc(value) {
            var sum = value.split('').reduce(function (total, digit, index) {
                return total + Number(digit) * (index % 2 === 0 ? 3 : 1);
            }, 0);
            return String((10 - sum % 10) % 10);
        }

        function checksumEan(value) {
            var sum = value.split('').reduce(function (total, digit, index) {
                return total + Number(digit) * (index % 2 === 0 ? 1 : 3);
            }, 0);
            return String((10 - sum % 10) % 10);
        }

        function barcodeBits(type, value) {
            var bits = '';
            var index;
            var parity;

            if (type === 'code39' || type === 'code128') {
                value = String(value || '').toUpperCase().replace(/[^0-9A-Z .\-$/+%]/g, '');
                if (!value) value = 'TOOLEXA';
                value = '*' + value + '*';
                value.split('').forEach(function (char) {
                    var pattern = code39[char] || code39['-'];
                    for (var i = 0; i < pattern.length; i++) {
                        bits += (i % 2 === 0 ? '1' : '0').repeat(pattern[i] === 'w' ? 3 : 1);
                    }
                    bits += '0';
                });
                return { bits: bits, label: value.replace(/\*/g, ''), value: value.replace(/\*/g, '') };
            }

            value = String(value || '').replace(/\D/g, '');
            if (type === 'upca') {
                value = value.slice(0, 11).padStart(11, '0');
                value += checksumUpc(value);
                bits = '101';
                for (index = 0; index < 6; index++) bits += eanL[Number(value[index])];
                bits += '01010';
                for (index = 6; index < 12; index++) bits += eanR[Number(value[index])];
                bits += '101';
                return { bits: bits, label: value, value: value };
            }

            value = value.slice(0, 12).padStart(12, '0');
            value += checksumEan(value);
            parity = eanParity[Number(value[0])];
            bits = '101';
            for (index = 1; index <= 6; index++) bits += (parity[index - 1] === 'L' ? eanL : eanG)[Number(value[index])];
            bits += '01010';
            for (index = 7; index <= 12; index++) bits += eanR[Number(value[index])];
            bits += '101';
            return { bits: bits, label: value, value: value };
        }

        function renderBarcode() {
            var type = tool.querySelector('[data-barcode-type]').value;
            var encoded = barcodeBits(type, tool.querySelector('[data-barcode-value]').value);
            var barWidth = type === 'code39' || type === 'code128' ? 2 : 3;
            var height = 100;
            var width = encoded.bits.length * barWidth + 24;
            var rects = '';

            encoded.bits.split('').forEach(function (bit, index) {
                if (bit === '1') {
                    rects += '<rect x="' + (12 + index * barWidth) + '" y="12" width="' + barWidth + '" height="' + height + '" fill="#0f172a"/>';
                }
            });

            lastSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + width + '" height="150" viewBox="0 0 ' + width + ' 150"><rect width="100%" height="100%" fill="#fff"/>' + rects + '<text x="' + (width / 2) + '" y="136" text-anchor="middle" font-family="Arial" font-size="16">' + encoded.label + '</text></svg>';
            tool.querySelector('[data-barcode-preview]').innerHTML = lastSvg;
            setOutput(['Type: ' + type.toUpperCase(), 'Value: ' + encoded.value, 'Format: SVG/PNG ready'].join('\n'));
        }

        function barcodePng() {
            var svg = new Blob([lastSvg], { type: 'image/svg+xml;charset=utf-8' });
            var url = URL.createObjectURL(svg);
            var image = new Image();
            image.onload = function () {
                var canvas = document.createElement('canvas');
                canvas.width = image.width;
                canvas.height = image.height;
                canvas.getContext('2d').drawImage(image, 0, 0);
                lastPngCanvas = canvas;
                URL.revokeObjectURL(url);
                canvas.toBlob(function (blob) {
                    var pngUrl = URL.createObjectURL(blob);
                    var link = document.createElement('a');
                    link.href = pngUrl;
                    link.download = 'toolexa-barcode.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(pngUrl);
                }, 'image/png');
            };
            image.src = url;
        }

        function imageToBase64() {
            var input = tool.querySelector('[data-image-base64-input]');
            var file = input.files && input.files[0];
            var image = tool.querySelector('[data-image-base64-preview]');

            if (!file) {
                setOutput('Upload an image first.');
                return;
            }

            var reader = new FileReader();
            reader.onload = function () {
                image.src = reader.result;
                image.hidden = false;
                setOutput(reader.result);
            };
            reader.readAsDataURL(file);
        }

        function vat(action) {
            var amount = Number(tool.querySelector('[data-vat-amount]').value);
            var rate = Number(tool.querySelector('[data-vat-rate]').value);
            var net;
            var vatAmount;
            var total;

            if (!Number.isFinite(amount) || !Number.isFinite(rate) || amount < 0 || rate < 0) {
                setOutput('Enter a valid amount and VAT rate.');
                return;
            }

            if (action === 'vat-remove') {
                total = amount;
                net = total / (1 + rate / 100);
                vatAmount = total - net;
            } else {
                net = amount;
                vatAmount = net * rate / 100;
                total = net + vatAmount;
            }

            setOutput(['Net Amount: ' + net.toFixed(2), 'VAT Rate: ' + rate.toFixed(2) + '%', 'VAT Amount: ' + vatAmount.toFixed(2), 'Total Amount: ' + total.toFixed(2)].join('\n'));
        }

        function robotsTxt() {
            var agent = (tool.querySelector('[data-robots-agent]').value || '*').trim() || '*';
            var allow = (tool.querySelector('[data-robots-allow]').value || '').split(/\r?\n/).map(function (line) { return line.trim(); }).filter(Boolean);
            var disallow = (tool.querySelector('[data-robots-disallow]').value || '').split(/\r?\n/).map(function (line) { return line.trim(); }).filter(Boolean);
            var sitemap = (tool.querySelector('[data-robots-sitemap]').value || '').trim();
            var lines = ['User-agent: ' + agent];

            allow.forEach(function (rule) { lines.push('Allow: ' + rule); });
            disallow.forEach(function (rule) { lines.push('Disallow: ' + rule); });
            if (sitemap) {
                lines.push('', 'Sitemap: ' + sitemap);
            }

            setOutput(lines.join('\n') + '\n');
        }

        function passwordStrength() {
            var value = tool.querySelector('[data-password-strength-input]').value || '';
            var score = 0;
            var suggestions = [];
            var meter = tool.querySelector('[data-strength-meter] span');
            var charset = 0;
            var label;
            var crackSeconds;

            if (value.length >= 8) score += 20; else suggestions.push('Use at least 8 characters.');
            if (value.length >= 12) score += 20; else suggestions.push('Use 12 or more characters for stronger protection.');
            if (/[a-z]/.test(value)) { score += 10; charset += 26; } else suggestions.push('Add lowercase letters.');
            if (/[A-Z]/.test(value)) { score += 10; charset += 26; } else suggestions.push('Add uppercase letters.');
            if (/\d/.test(value)) { score += 10; charset += 10; } else suggestions.push('Add numbers.');
            if (/[^A-Za-z0-9]/.test(value)) { score += 15; charset += 32; } else suggestions.push('Add symbols.');
            if (!/(.)\1{2,}/.test(value)) score += 10; else suggestions.push('Avoid repeated characters.');
            if (!/(password|qwerty|admin|welcome|letmein|123456)/i.test(value)) score += 5; else suggestions.push('Avoid common password words.');

            score = Math.max(0, Math.min(100, score));
            label = score >= 75 ? 'Strong' : (score >= 45 ? 'Medium' : 'Weak');
            crackSeconds = Math.pow(Math.max(charset, 1), Math.max(value.length, 1)) / 1000000000;

            function time(seconds) {
                if (seconds < 60) return 'Less than a minute';
                if (seconds < 3600) return Math.round(seconds / 60) + ' minutes';
                if (seconds < 86400) return Math.round(seconds / 3600) + ' hours';
                if (seconds < 31536000) return Math.round(seconds / 86400) + ' days';
                return Math.min(999999, Math.round(seconds / 31536000)) + ' years';
            }

            if (meter) {
                meter.style.width = score + '%';
                meter.style.background = score >= 75 ? '#0f766e' : (score >= 45 ? '#f59e0b' : '#e11d48');
            }

            setOutput([
                'Score: ' + score + '/100',
                'Strength: ' + label,
                'Estimated crack time: ' + time(crackSeconds),
                'Length: ' + value.length,
                'Lowercase: ' + (/[a-z]/.test(value) ? 'Yes' : 'No'),
                'Uppercase: ' + (/[A-Z]/.test(value) ? 'Yes' : 'No'),
                'Numbers: ' + (/\d/.test(value) ? 'Yes' : 'No'),
                'Symbols: ' + (/[^A-Za-z0-9]/.test(value) ? 'Yes' : 'No'),
                'Suggestions: ' + (suggestions.length ? suggestions.join(' ') : 'Looks good. Use a unique password and enable MFA.')
            ].join('\n'));
        }

        function parseCsv(text) {
            var rows = [];
            var row = [];
            var cell = '';
            var quote = false;
            var i;
            var char;
            var next;

            for (i = 0; i < text.length; i++) {
                char = text[i];
                next = text[i + 1];
                if (char === '"' && quote && next === '"') {
                    cell += '"';
                    i += 1;
                } else if (char === '"') {
                    quote = !quote;
                } else if (char === ',' && !quote) {
                    row.push(cell);
                    cell = '';
                } else if ((char === '\n' || char === '\r') && !quote) {
                    if (char === '\r' && next === '\n') i += 1;
                    row.push(cell);
                    rows.push(row);
                    row = [];
                    cell = '';
                } else {
                    cell += char;
                }
            }
            row.push(cell);
            rows.push(row);
            return rows.filter(function (item) { return item.some(function (value) { return value.trim() !== ''; }); });
        }

        function csvToJson() {
            var text = tool.querySelector('[data-csv-input]').value.trim();
            var rows;
            var headers;
            var data;

            if (!text) {
                setOutput('Paste CSV text or upload a CSV file first.');
                return;
            }

            rows = parseCsv(text);
            headers = rows.shift() || [];
            data = rows.map(function (row) {
                var item = {};
                headers.forEach(function (header, index) {
                    item[(header || 'column_' + (index + 1)).trim()] = row[index] || '';
                });
                return item;
            });

            setOutput(JSON.stringify(data, null, 2));
        }

        function timestamp(action) {
            var unix = tool.querySelector('[data-timestamp-unix]');
            var human = tool.querySelector('[data-timestamp-human]');
            var date;
            var seconds;

            if (action === 'timestamp-current') {
                date = new Date();
            } else if (action === 'timestamp-human') {
                seconds = Number(unix.value);
                if (!Number.isFinite(seconds)) {
                    setOutput('Enter a valid Unix timestamp.');
                    return;
                }
                date = new Date(String(Math.round(seconds)).length > 10 ? seconds : seconds * 1000);
            } else {
                if (!human.value) {
                    setOutput('Choose a human date first.');
                    return;
                }
                date = new Date(human.value);
            }

            seconds = Math.floor(date.getTime() / 1000);
            unix.value = seconds;
            human.value = new Date(date.getTime() - date.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            setOutput(['Unix Timestamp: ' + seconds, 'Milliseconds: ' + date.getTime(), 'Local Date: ' + date.toString(), 'UTC ISO: ' + date.toISOString()].join('\n'));
        }

        function webpToPng() {
            var input = tool.querySelector('[data-webp-input]');
            var files = Array.prototype.slice.call(input.files || []);
            var wrap = tool.querySelector('[data-webp-preview]');
            var summaries = [];

            if (!files.length) {
                setOutput('Upload one or more WebP images first.');
                return;
            }

            wrap.innerHTML = '';
            files.forEach(function (file) {
                if (file.type !== 'image/webp') {
                    summaries.push(file.name + ': skipped (not WebP)');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function () {
                    var image = new Image();
                    image.onload = function () {
                        var canvas = document.createElement('canvas');
                        var card = document.createElement('div');
                        var img = document.createElement('img');
                        var button = document.createElement('button');
                        canvas.width = image.naturalWidth;
                        canvas.height = image.naturalHeight;
                        canvas.getContext('2d').drawImage(image, 0, 0);
                        img.src = canvas.toDataURL('image/png');
                        img.alt = file.name + ' PNG preview';
                        img.className = 'image-output-preview';
                        button.type = 'button';
                        button.className = 'btn btn-secondary btn-sm';
                        button.textContent = 'Download PNG';
                        button.addEventListener('click', function () {
                            var link = document.createElement('a');
                            link.href = img.src;
                            link.download = file.name.replace(/\.webp$/i, '') + '.png';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        });
                        card.className = 'finance-result-item';
                        card.appendChild(img);
                        card.appendChild(button);
                        wrap.appendChild(card);
                    };
                    image.src = reader.result;
                };
                reader.readAsDataURL(file);
                summaries.push(file.name + ': ready');
            });

            setOutput(summaries.join('\n'));
        }

        function keywordDensity() {
            var text = tool.querySelector('[data-keyword-text]').value || '';
            var stop = 'a,an,and,are,as,at,be,by,for,from,has,he,in,is,it,its,of,on,that,the,to,was,were,will,with,you,your,or,if,not,this,these,those,can,into,than,then,they,their,we,our'.split(',');
            var words = (text.toLowerCase().match(/[a-z0-9]+(?:'[a-z]+)?/g) || []);
            var counts = {};
            var filtered;
            var unique;
            var rows;

            words.forEach(function (word) {
                if (stop.indexOf(word) === -1 && word.length > 1) {
                    counts[word] = (counts[word] || 0) + 1;
                }
            });

            unique = Object.keys(counts).length;
            filtered = Object.keys(counts).sort(function (a, b) { return counts[b] - counts[a]; }).slice(0, 20);
            rows = [
                'Total Words: ' + words.length,
                'Unique Words: ' + unique,
                '',
                'Top Keywords:'
            ];
            filtered.forEach(function (word, index) {
                rows.push((index + 1) + '. ' + word + ' - ' + counts[word] + ' times - ' + (words.length ? (counts[word] / words.length * 100).toFixed(2) : '0.00') + '%');
            });
            setOutput(rows.join('\n'));
        }

        function canvasBlob(canvas) {
            return new Promise(function (resolve) {
                canvas.toBlob(resolve, 'image/png');
            });
        }

        function resizeImageToCanvas(image, size) {
            var canvas = document.createElement('canvas');
            canvas.width = size;
            canvas.height = size;
            canvas.getContext('2d').drawImage(image, 0, 0, size, size);
            return canvas;
        }

        function faviconGenerator() {
            var input = tool.querySelector('[data-favicon-input]');
            var file = input.files && input.files[0];
            var sizes = mode === 'favicon-generator' ? [16, 32, 48, 180, 192, 512] : [16, 32, 48, 64, 180, 192, 512];
            var previewBox = tool.querySelector('[data-favicon-preview]');

            if (!file) {
                setOutput('Upload an image first.');
                return Promise.reject(new Error('No image selected'));
            }

            faviconAssets = [];
            previewBox.innerHTML = '';

            return new Promise(function (resolve, reject) {
                var image = new Image();
                var url = URL.createObjectURL(file);

                image.onload = async function () {
                    try {
                        for (var i = 0; i < sizes.length; i++) {
                            var canvas = resizeImageToCanvas(image, sizes[i]);
                            var blob = await canvasBlob(canvas);
                            var dataUrl = canvas.toDataURL('image/png');
                            var card = document.createElement('div');
                            var img = document.createElement('img');
                            var label = document.createElement('strong');
                            img.src = dataUrl;
                            img.alt = sizes[i] + ' favicon preview';
                            img.className = 'image-output-preview';
                            label.textContent = sizes[i] + 'x' + sizes[i];
                            card.className = 'finance-result-item';
                            card.appendChild(img);
                            card.appendChild(label);
                            previewBox.appendChild(card);
                            faviconAssets.push({ name: 'favicon-' + sizes[i] + 'x' + sizes[i] + '.png', size: sizes[i], blob: blob, dataUrl: dataUrl });
                        }
                        URL.revokeObjectURL(url);
                        setOutput('Generated favicon sizes:\n' + sizes.map(function (size) { return size + 'x' + size; }).join('\n'));
                        resolve(faviconAssets);
                    } catch (error) {
                        URL.revokeObjectURL(url);
                        reject(error);
                    }
                };
                image.onerror = function () {
                    URL.revokeObjectURL(url);
                    setOutput('Could not read this image. Try a PNG, JPG or WebP file.');
                    reject(new Error('Invalid image'));
                };
                image.src = url;
            });
        }

        async function icoBlob() {
            if (!faviconAssets.length) {
                await faviconGenerator();
            }
            var assets = faviconAssets.filter(function (asset) { return [16, 32, 48, 64].indexOf(asset.size) !== -1; });
            var buffers = await Promise.all(assets.map(function (asset) { return asset.blob.arrayBuffer(); }));
            var headerSize = 6 + assets.length * 16;
            var total = headerSize + buffers.reduce(function (sum, buffer) { return sum + buffer.byteLength; }, 0);
            var bytes = new Uint8Array(total);
            var view = new DataView(bytes.buffer);
            var offset = headerSize;
            view.setUint16(0, 0, true);
            view.setUint16(2, 1, true);
            view.setUint16(4, assets.length, true);
            assets.forEach(function (asset, index) {
                var base = 6 + index * 16;
                var buffer = new Uint8Array(buffers[index]);
                view.setUint8(base, asset.size === 256 ? 0 : asset.size);
                view.setUint8(base + 1, asset.size === 256 ? 0 : asset.size);
                view.setUint8(base + 2, 0);
                view.setUint8(base + 3, 0);
                view.setUint16(base + 4, 1, true);
                view.setUint16(base + 6, 32, true);
                view.setUint32(base + 8, buffer.byteLength, true);
                view.setUint32(base + 12, offset, true);
                bytes.set(buffer, offset);
                offset += buffer.byteLength;
            });
            return new Blob([bytes], { type: 'image/x-icon' });
        }

        function crc32(bytes) {
            var table = window.__crcTable || (window.__crcTable = Array.from({ length: 256 }, function (_, n) {
                for (var k = 0; k < 8; k++) n = n & 1 ? 0xedb88320 ^ (n >>> 1) : n >>> 1;
                return n >>> 0;
            }));
            var crc = -1;
            for (var i = 0; i < bytes.length; i++) crc = (crc >>> 8) ^ table[(crc ^ bytes[i]) & 255];
            return (crc ^ -1) >>> 0;
        }

        async function zipBlob() {
            var files = [];
            if (!faviconAssets.length) await faviconGenerator();
            for (var i = 0; i < faviconAssets.length; i++) {
                files.push({ name: faviconAssets[i].name, data: new Uint8Array(await faviconAssets[i].blob.arrayBuffer()) });
            }
            files.push({ name: 'favicon.ico', data: new Uint8Array(await (await icoBlob()).arrayBuffer()) });
            var chunks = [];
            var central = [];
            var offset = 0;

            function u16(value) { return [value & 255, value >>> 8 & 255]; }
            function u32(value) { return [value & 255, value >>> 8 & 255, value >>> 16 & 255, value >>> 24 & 255]; }
            function strBytes(value) { return Array.from(new TextEncoder().encode(value)); }

            files.forEach(function (file) {
                var name = strBytes(file.name);
                var crc = crc32(file.data);
                var local = new Uint8Array([].concat(u32(0x04034b50), u16(20), u16(0), u16(0), u16(0), u16(0), u32(crc), u32(file.data.length), u32(file.data.length), u16(name.length), u16(0), name));
                chunks.push(local, file.data);
                central.push({ file: file, name: name, crc: crc, offset: offset });
                offset += local.length + file.data.length;
            });
            var centralStart = offset;
            central.forEach(function (item) {
                var dir = new Uint8Array([].concat(u32(0x02014b50), u16(20), u16(20), u16(0), u16(0), u16(0), u16(0), u32(item.crc), u32(item.file.data.length), u32(item.file.data.length), u16(item.name.length), u16(0), u16(0), u16(0), u16(0), u32(0), u32(item.offset), item.name));
                chunks.push(dir);
                offset += dir.length;
            });
            chunks.push(new Uint8Array([].concat(u32(0x06054b50), u16(0), u16(0), u16(files.length), u16(files.length), u32(offset - centralStart), u32(centralStart), u16(0))));
            return new Blob(chunks, { type: 'application/zip' });
        }

        function mortgage() {
            var loan = Number(tool.querySelector('[data-mortgage-loan]').value);
            var rate = Number(tool.querySelector('[data-mortgage-rate]').value) / 100 / 12;
            var months = Number(tool.querySelector('[data-mortgage-years]').value) * 12;
            if (!Number.isFinite(loan) || !Number.isFinite(rate) || !Number.isFinite(months) || loan <= 0 || months <= 0) {
                setOutput('Enter a valid loan amount, interest rate and loan term.');
                return;
            }
            var payment = rate === 0 ? loan / months : loan * rate * Math.pow(1 + rate, months) / (Math.pow(1 + rate, months) - 1);
            var balance = loan;
            var totalInterest = 0;
            var yearly = [];
            for (var month = 1; month <= months; month++) {
                var interest = balance * rate;
                var principal = payment - interest;
                balance = Math.max(0, balance - principal);
                totalInterest += interest;
                if (month % 12 === 0 || month === months) yearly.push('Year ' + Math.ceil(month / 12) + ': Balance ' + balance.toFixed(2));
            }
            setOutput(['Monthly Payment: ' + payment.toFixed(2), 'Total Payment: ' + (payment * months).toFixed(2), 'Total Interest: ' + totalInterest.toFixed(2), '', 'Amortization Summary:', yearly.slice(0, 30).join('\n')].join('\n'));
        }

        function slugify() {
            var text = tool.querySelector('[data-slug-text]').value || '';
            var slug = text.normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-').replace(/-+/g, '-');
            setOutput(slug);
        }

        function loadPickerImage() {
            var input = tool.querySelector('[data-picker-input]');
            var file = input.files && input.files[0];
            var canvas = tool.querySelector('[data-picker-canvas]');
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function () {
                var image = new Image();
                image.onload = function () {
                    var max = 680;
                    var scale = Math.min(1, max / image.naturalWidth);
                    canvas.width = Math.round(image.naturalWidth * scale);
                    canvas.height = Math.round(image.naturalHeight * scale);
                    canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
                    canvas.hidden = false;
                    setOutput('Click the image to pick a color.');
                };
                image.src = reader.result;
            };
            reader.readAsDataURL(file);
        }

        function pickColor(event) {
            var canvas = tool.querySelector('[data-picker-canvas]');
            var rect = canvas.getBoundingClientRect();
            var x = Math.floor((event.clientX - rect.left) * canvas.width / rect.width);
            var y = Math.floor((event.clientY - rect.top) * canvas.height / rect.height);
            var data = canvas.getContext('2d').getImageData(x, y, 1, 1).data;
            var rgb = { r: data[0], g: data[1], b: data[2] };
            var hex = rgbToHex(rgb.r, rgb.g, rgb.b);
            var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
            var row = 'HEX: ' + hex + ' | RGB: rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ') | HSL: hsl(' + hsl.h + ', ' + hsl.s + '%, ' + hsl.l + '%)';
            pickedPalette.unshift(row);
            setOutput(pickedPalette.slice(0, 20).join('\n'));
        }

        tool.querySelectorAll('[data-local-action]').forEach(function (button) {
            button.addEventListener('click', function () {
                var action = button.getAttribute('data-local-action');
                if (mode === 'hex-rgb-hsl-color-converter') processColor(action);
                if (mode === 'barcode-generator') renderBarcode();
                if (mode === 'image-to-base64-converter') imageToBase64();
                if (mode === 'vat-calculator') vat(action);
                if (mode === 'robots-txt-generator') robotsTxt();
                if (mode === 'password-strength-checker') passwordStrength();
                if (mode === 'csv-to-json-converter') csvToJson();
                if (mode === 'timestamp-converter') timestamp(action);
                if (mode === 'webp-to-png-converter') webpToPng();
                if (mode === 'keyword-density-checker') keywordDensity();
                if (mode === 'ico-favicon-generator' || mode === 'favicon-generator') faviconGenerator();
                if (mode === 'mortgage-calculator') mortgage();
                if (mode === 'url-slug-generator') slugify();
                if (mode === 'color-picker-from-image') loadPickerImage();
            });
        });

        var csvFile = tool.querySelector('[data-csv-file]');
        if (csvFile) {
            csvFile.addEventListener('change', function () {
                var file = csvFile.files && csvFile.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function () {
                    tool.querySelector('[data-csv-input]').value = reader.result;
                };
                reader.readAsText(file);
            });
        }

        var pickerInput = tool.querySelector('[data-picker-input]');
        if (pickerInput) {
            pickerInput.addEventListener('change', loadPickerImage);
        }

        var pickerCanvas = tool.querySelector('[data-picker-canvas]');
        if (pickerCanvas) {
            pickerCanvas.addEventListener('click', pickColor);
        }

        if (mode === 'hex-rgb-hsl-color-converter') {
            processColor('hex');
        }

        if (mode === 'barcode-generator') {
            renderBarcode();
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                setOutput('');
                if (mode === 'image-to-base64-converter') {
                    var input = tool.querySelector('[data-image-base64-input]');
                    var image = tool.querySelector('[data-image-base64-preview]');
                    input.value = '';
                    image.hidden = true;
                    image.removeAttribute('src');
                }
                if (mode === 'barcode-generator') {
                    tool.querySelector('[data-barcode-preview]').innerHTML = '';
                    lastSvg = '';
                }
                if (mode === 'password-strength-checker') {
                    tool.querySelector('[data-password-strength-input]').value = '';
                    var meter = tool.querySelector('[data-strength-meter] span');
                    if (meter) meter.style.width = '0%';
                }
                if (mode === 'csv-to-json-converter') {
                    tool.querySelector('[data-csv-input]').value = '';
                    tool.querySelector('[data-csv-file]').value = '';
                }
                if (mode === 'webp-to-png-converter') {
                    tool.querySelector('[data-webp-input]').value = '';
                    tool.querySelector('[data-webp-preview]').innerHTML = '';
                }
                if (mode === 'keyword-density-checker') tool.querySelector('[data-keyword-text]').value = '';
                if (mode === 'ico-favicon-generator' || mode === 'favicon-generator') {
                    tool.querySelector('[data-favicon-input]').value = '';
                    tool.querySelector('[data-favicon-preview]').innerHTML = '';
                    faviconAssets = [];
                }
                if (mode === 'mortgage-calculator') {
                    tool.querySelector('[data-mortgage-loan]').value = '';
                    tool.querySelector('[data-mortgage-rate]').value = '';
                    tool.querySelector('[data-mortgage-years]').value = '';
                }
                if (mode === 'url-slug-generator') tool.querySelector('[data-slug-text]').value = '';
                if (mode === 'color-picker-from-image') {
                    tool.querySelector('[data-picker-input]').value = '';
                    tool.querySelector('[data-picker-canvas]').hidden = true;
                    pickedPalette = [];
                }
            });
        }

        downloadButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var type = button.getAttribute('data-local-download');
                if (type === 'svg') {
                    if (!lastSvg) renderBarcode();
                    downloadText(lastSvg, 'toolexa-barcode.svg', 'image/svg+xml');
                } else if (type === 'png') {
                    if (!lastSvg) renderBarcode();
                    barcodePng();
                } else if (type === 'txt') {
                    if (!output.value) {
                        imageToBase64();
                    }
                    if (output.value) {
                        downloadText(output.value, 'toolexa-image-base64.txt', 'text/plain');
                    }
                } else if (type === 'robots') {
                    if (!output.value) robotsTxt();
                    downloadText(output.value, 'robots.txt', 'text/plain');
                } else if (type === 'json') {
                    if (!output.value) csvToJson();
                    downloadText(output.value, 'toolexa-csv.json', 'application/json');
                } else if (type === 'png-batch') {
                    var first = tool.querySelector('[data-webp-preview] img');
                    if (first) {
                        var link = document.createElement('a');
                        link.href = first.src;
                        link.download = 'toolexa-webp-converted.png';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                } else if (type === 'ico') {
                    icoBlob().then(function (blob) {
                        var url = URL.createObjectURL(blob);
                        var link = document.createElement('a');
                        link.href = url;
                        link.download = 'favicon.ico';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        URL.revokeObjectURL(url);
                    }).catch(function () {});
                } else if (type === 'favicon-zip') {
                    zipBlob().then(function (blob) {
                        var url = URL.createObjectURL(blob);
                        var link = document.createElement('a');
                        link.href = url;
                        link.download = 'toolexa-favicons.zip';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        URL.revokeObjectURL(url);
                    }).catch(function () {});
                } else if (type === 'palette') {
                    downloadText(output.value || '', 'toolexa-palette.txt', 'text/plain');
                }
            });
        });

        tool.querySelectorAll('[data-local-print]').forEach(function (button) {
            button.addEventListener('click', function () {
                var html;
                if (mode === 'barcode-generator') {
                    if (!lastSvg) renderBarcode();
                    html = lastSvg;
                } else {
                    if (!output.value && mode === 'mortgage-calculator') mortgage();
                    html = '<pre style="font-family:Arial,sans-serif;white-space:pre-wrap;line-height:1.6;font-size:16px">' + (output.value || '').replace(/[&<>"']/g, function (char) {
                        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char];
                    }) + '</pre>';
                }
                var win = window.open('', '_blank');
                if (!win) return;
                win.document.write('<!doctype html><title>Print Result</title><body style="display:grid;place-items:center;min-height:100vh;padding:32px">' + html + '<script>window.print();<\/script></body>');
                win.document.close();
            });
        });

        tool.querySelectorAll('[data-share-url]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (navigator.share) {
                    navigator.share({ title: document.title, url: window.location.href }).catch(function () {});
                    return;
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(window.location.href);
                }
            });
        });
    });

    document.querySelectorAll('[data-advanced-tool]').forEach(function (tool) {
        var mode = tool.dataset.mode;
        var output = tool.querySelector('[data-output]');
        var clear = tool.querySelector('[data-clear]');
        var outputBytes = null;

        function downloadBlob(blob, name) {
            var url = URL.createObjectURL(blob), link = document.createElement('a');
            link.href = url; link.download = name; document.body.appendChild(link); link.click(); link.remove();
            window.setTimeout(function () { URL.revokeObjectURL(url); }, 500);
        }

        async function hash() {
            var value = tool.querySelector('[data-hash-input]').value;
            if (!value) { output.value = ''; return; }
            if (!window.crypto || !window.crypto.subtle) { output.value = 'SHA-256 requires a secure modern browser context.'; return; }
            var digest = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(value));
            output.value = Array.from(new Uint8Array(digest)).map(function (byte) { return byte.toString(16).padStart(2, '0'); }).join('');
        }

        function parseUrl() {
            var panel = tool.querySelector('[data-url-result]');
            panel.innerHTML = '';
            try {
                var url = new URL(tool.querySelector('[data-url-input]').value.trim());
                var params = Array.from(url.searchParams.entries());
                var rows = [['Protocol', url.protocol.replace(':', '')], ['Host', url.hostname], ['Port', url.port || (url.protocol === 'https:' ? '443 (default)' : url.protocol === 'http:' ? '80 (default)' : 'Not specified')], ['Path', url.pathname || '/'], ['Fragment', url.hash ? url.hash.slice(1) : 'None']];
                var list = document.createElement('div'); list.className = 'browser-result-list';
                rows.concat([['Query Parameters', params.length ? params.map(function (p) { return p[0] + ' = ' + p[1]; }).join(', ') : 'None']]).forEach(function (row) {
                    var item = document.createElement('div'), code = document.createElement('code'); code.textContent = row[0] + ': ' + row[1]; item.appendChild(code); list.appendChild(item);
                });
                panel.appendChild(list);
                output.value = rows.map(function (r) { return r[0] + ': ' + r[1]; }).concat(['Query Parameters:', params.length ? params.map(function (p) { return '  ' + p[0] + ': ' + p[1]; }).join('\n') : '  None']).join('\n');
            } catch (error) { panel.textContent = 'Enter a valid absolute URL including its protocol.'; output.value = ''; }
        }

        var palette = [];
        function randomColor() { return '#' + Array.from(crypto.getRandomValues(new Uint8Array(3))).map(function (x) { return x.toString(16).padStart(2, '0'); }).join('').toUpperCase(); }
        function rgb(hex) { return [parseInt(hex.slice(1,3),16), parseInt(hex.slice(3,5),16), parseInt(hex.slice(5,7),16)]; }
        function renderPalette(generate) {
            var wrap = tool.querySelector('[data-palette]'); if (!wrap) return;
            if (!palette.length) palette = Array.from({length:5}, function () { return {hex:randomColor(), locked:false}; });
            if (generate) palette.forEach(function (color) { if (!color.locked) color.hex = randomColor(); });
            wrap.innerHTML = '';
            palette.forEach(function (color, index) {
                var values = rgb(color.hex), swatch = document.createElement('div'); swatch.className = 'palette-swatch'; swatch.style.background = color.hex;
                var hex = document.createElement('strong'); hex.textContent = color.hex; var rgbText = document.createElement('small'); rgbText.textContent = 'rgb(' + values.join(', ') + ')';
                var copy = document.createElement('button'); copy.className = 'btn btn-secondary btn-sm'; copy.type = 'button'; copy.textContent = 'Copy'; copy.onclick = function () { navigator.clipboard.writeText(color.hex); };
                var lock = document.createElement('button'); lock.className = 'btn btn-secondary btn-sm'; lock.type = 'button'; lock.textContent = color.locked ? 'Unlock' : 'Lock'; lock.setAttribute('aria-pressed', color.locked); lock.onclick = function () { palette[index].locked = !palette[index].locked; renderPalette(false); };
                swatch.append(hex, rgbText, copy, lock); wrap.appendChild(swatch);
            });
            output.value = palette.map(function (c) { return c.hex + ' | rgb(' + rgb(c.hex).join(', ') + ')'; }).join('\n');
        }

        function selectedPages(value, total) {
            var pages = [];
            value.split(',').forEach(function (part) {
                var bits = part.trim().split('-'), start = Number(bits[0]), end = bits.length > 1 ? Number(bits[1]) : start;
                if (!Number.isInteger(start) || !Number.isInteger(end) || start < 1 || end < start || end > total) throw new Error('Invalid range');
                for (var page = start; page <= end; page++) if (pages.indexOf(page - 1) < 0) pages.push(page - 1);
            });
            if (!pages.length) throw new Error('No pages'); return pages;
        }
        async function inspectPdf() {
            var file = tool.querySelector('[data-pdf-input]').files[0], label = tool.querySelector('[data-page-count]'); outputBytes = null; tool.querySelector('[data-download]').classList.add('disabled');
            if (!file) { label.textContent = 'Select a PDF to preview its page count.'; return; }
            try { var pdf = await PDFLib.PDFDocument.load(await file.arrayBuffer()); label.textContent = 'Detected ' + pdf.getPageCount() + ' page(s) in ' + file.name + '.'; }
            catch (e) { label.textContent = 'Could not read this PDF. It may be protected or damaged.'; }
        }
        async function splitPdf() {
            var panel = tool.querySelector('[data-pdf-result]'), file = tool.querySelector('[data-pdf-input]').files[0];
            if (!file || !window.PDFLib) { panel.textContent = !file ? 'Choose a PDF first.' : 'PDF library is still loading. Try again.'; return; }
            try { var source = await PDFLib.PDFDocument.load(await file.arrayBuffer()), indices = selectedPages(tool.querySelector('[data-ranges]').value, source.getPageCount()), result = await PDFLib.PDFDocument.create(), pages = await result.copyPages(source, indices); pages.forEach(function (p) { result.addPage(p); }); outputBytes = await result.save(); panel.textContent = 'Extracted ' + indices.length + ' of ' + source.getPageCount() + ' pages. Your new PDF is ready.'; tool.querySelector('[data-download]').classList.remove('disabled'); }
            catch (e) { panel.textContent = 'Check the page range and ensure every page is within the PDF page count.'; }
        }

        function markedChars(text, other) {
            var fragment = document.createDocumentFragment();
            Array.from(text).forEach(function (char, i) { var span = document.createElement('span'); span.textContent = char; if (char !== Array.from(other)[i]) span.className = 'diff-char'; fragment.appendChild(span); }); return fragment;
        }
        function compare() {
            var a = tool.querySelector('[data-original]').value, b = tool.querySelector('[data-changed]').value, left = a.split('\n'), right = b.split('\n'), diff = tool.querySelector('[data-diff]'), max = Math.max(left.length, right.length), added=0, removed=0, changed=0, plain=[]; diff.innerHTML='';
            for (var i=0;i<max;i++) { var old=left[i], newer=right[i]; if (old===newer) addLine(' ', old || '', 'diff-same'); else if (old===undefined) { added++; addLine('+',newer,'diff-add'); } else if (newer===undefined) { removed++; addLine('-',old,'diff-remove'); } else { changed++; addLine('-',old,'diff-remove',newer); addLine('+',newer,'diff-add',old); } }
            function addLine(sign,textValue,klass,other) { var row=document.createElement('div'), mark=document.createElement('b'), body=document.createElement('span'); row.className='diff-line '+klass; mark.textContent=sign; body.appendChild(other===undefined?document.createTextNode(textValue):markedChars(textValue,other)); row.append(mark,body); diff.appendChild(row); plain.push(sign+' '+textValue); }
            tool.querySelector('[data-compare-summary]').textContent='Original: '+left.length+' lines / '+a.length+' characters · Changed: '+right.length+' lines / '+b.length+' characters · Added: '+added+' · Removed: '+removed+' · Modified: '+changed; output.value=plain.join('\n');
        }

        function htmlAttribute(value) {
            return String(value).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }
        function openGraph() {
            var rows = [['og:title', tool.querySelector('[data-og-title]').value], ['og:description', tool.querySelector('[data-og-description]').value], ['og:image', tool.querySelector('[data-og-image]').value], ['og:url', tool.querySelector('[data-og-url]').value], ['og:type', tool.querySelector('[data-og-type]').value]];
            output.value = rows.map(function (row) { return '<meta property="' + row[0] + '" content="' + htmlAttribute(row[1]) + '">'; }).join('\n');
        }

        function convertEntity() {
            var input = tool.querySelector('[data-entity-input]').value, conversion = tool.querySelector('[data-entity-mode]').value;
            if (conversion === 'encode') { output.value = input.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); return; }
            var area = document.createElement('textarea'); area.innerHTML = input; output.value = area.value;
        }

        var fallbackZones = ['UTC','Asia/Kolkata','Asia/Dubai','Asia/Singapore','Asia/Tokyo','Australia/Sydney','Europe/London','Europe/Paris','Europe/Berlin','Africa/Johannesburg','America/New_York','America/Chicago','America/Denver','America/Los_Angeles','America/Toronto','America/Sao_Paulo','Pacific/Auckland'];
        function allZones() { return typeof Intl.supportedValuesOf === 'function' ? Intl.supportedValuesOf('timeZone') : fallbackZones; }
        function validZone(zone) { try { new Intl.DateTimeFormat('en-US',{timeZone:zone}).format(); return true; } catch(e) { return false; } }
        function zoneParts(date, zone) {
            var result = {}, parts = new Intl.DateTimeFormat('en-CA',{timeZone:zone,year:'numeric',month:'2-digit',day:'2-digit',hour:'2-digit',minute:'2-digit',second:'2-digit',hourCycle:'h23'}).formatToParts(date);
            parts.forEach(function(p){ if(p.type!=='literal') result[p.type]=Number(p.value); }); return result;
        }
        function localInZone(value, zone) {
            var match = value.match(/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/); if(!match) throw new Error('date');
            var wanted = Date.UTC(+match[1],+match[2]-1,+match[3],+match[4],+match[5],0), guess = wanted;
            for(var i=0;i<3;i++){ var p=zoneParts(new Date(guess),zone), shown=Date.UTC(p.year,p.month-1,p.day,p.hour,p.minute,p.second); guess += wanted-shown; }
            return new Date(guess);
        }
        function zoneLabel(date, zone) { return new Intl.DateTimeFormat('en-IN',{timeZone:zone,dateStyle:'full',timeStyle:'long'}).format(date); }
        function updateZoneClocks() {
            if(mode!=='time-zone-converter') return; var source=tool.querySelector('[data-source-zone]').value, target=tool.querySelector('[data-target-zone]').value, now=new Date();
            tool.querySelector('[data-source-current]').textContent=validZone(source)?zoneLabel(now,source):'Choose a valid zone'; tool.querySelector('[data-target-current]').textContent=validZone(target)?zoneLabel(now,target):'Choose a valid zone';
        }
        function convertTimeZone() {
            var source=tool.querySelector('[data-source-zone]').value.trim(), target=tool.querySelector('[data-target-zone]').value.trim(), value=tool.querySelector('[data-zone-datetime]').value;
            if(!validZone(source)||!validZone(target)||!value){ output.value='Choose valid source and destination time zones, then enter a date and time.'; return; }
            try { var instant=localInZone(value,source); output.value=['Source: '+zoneLabel(instant,source)+' ('+source+')','Destination: '+zoneLabel(instant,target)+' ('+target+')','UTC: '+instant.toISOString()].join('\n'); } catch(e){ output.value='Could not convert this local time. Check the selected values.'; }
        }

        function uuidV4() {
            if (crypto.randomUUID) return crypto.randomUUID();
            var bytes=crypto.getRandomValues(new Uint8Array(16)); bytes[6]=(bytes[6]&15)|64; bytes[8]=(bytes[8]&63)|128;
            var hex=Array.from(bytes).map(function(b){return b.toString(16).padStart(2,'0');}).join(''); return hex.slice(0,8)+'-'+hex.slice(8,12)+'-'+hex.slice(12,16)+'-'+hex.slice(16,20)+'-'+hex.slice(20);
        }
        function generateUuids() {
            var quantity=Number(tool.querySelector('[data-uuid-quantity]').value); if(!Number.isInteger(quantity)||quantity<1||quantity>1000){output.value='';tool.querySelector('[data-uuid-summary]').textContent='Enter a whole number from 1 to 1,000.';return;}
            output.value=Array.from({length:quantity},uuidV4).join('\n'); tool.querySelector('[data-uuid-summary]').textContent='Generated '+quantity+' UUID v4 value'+(quantity===1?'':'s')+'.';
        }

        function xmlEscape(value) { return String(value).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&apos;'); }
        function addSitemapRow(values) {
            var wrap=tool.querySelector('[data-sitemap-rows]'),row=document.createElement('div');row.className='sitemap-row';
            row.innerHTML='<div><label>URL</label><input class="form-control" type="url" placeholder="https://example.com/page" data-sm-url></div><div><label>Change frequency</label><select class="form-control" data-sm-frequency><option value="">Not specified</option><option>always</option><option>hourly</option><option>daily</option><option>weekly</option><option>monthly</option><option>yearly</option><option>never</option></select></div><div><label>Priority</label><input class="form-control" type="number" min="0" max="1" step="0.1" value="0.5" data-sm-priority></div><div><label>Last modified</label><input class="form-control" type="date" data-sm-date></div><button class="btn btn-secondary btn-sm" type="button">Remove</button>';
            if(values){row.querySelector('[data-sm-url]').value=values.url||'';row.querySelector('[data-sm-frequency]').value=values.frequency||'';row.querySelector('[data-sm-priority]').value=values.priority||'0.5';row.querySelector('[data-sm-date]').value=values.date||'';}
            row.querySelector('button').addEventListener('click',function(){row.remove();});wrap.appendChild(row);
        }
        function generateSitemap() {
            var entries=[],invalid=false;tool.querySelectorAll('.sitemap-row').forEach(function(row){var url=row.querySelector('[data-sm-url]').value.trim(),priority=row.querySelector('[data-sm-priority]').value,frequency=row.querySelector('[data-sm-frequency]').value,date=row.querySelector('[data-sm-date]').value;if(!url)return;try{var parsed=new URL(url);if(!/^https?:$/.test(parsed.protocol))throw new Error();}catch(e){invalid=true;return;}if(Number(priority)<0||Number(priority)>1){invalid=true;return;}entries.push({url:url,priority:priority,frequency:frequency,date:date});});
            if(invalid||!entries.length){output.value=invalid?'Correct invalid URLs or priority values before generating.':'Add at least one complete URL.';return;}
            var lines=['<?xml version="1.0" encoding="UTF-8"?>','<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'];entries.forEach(function(e){lines.push('  <url>','    <loc>'+xmlEscape(e.url)+'</loc>');if(e.date)lines.push('    <lastmod>'+e.date+'</lastmod>');if(e.frequency)lines.push('    <changefreq>'+e.frequency+'</changefreq>');if(e.priority!=='')lines.push('    <priority>'+Number(e.priority).toFixed(1)+'</priority>');lines.push('  </url>');});lines.push('</urlset>');output.value=lines.join('\n');
        }
        function minifyJson() { var status=tool.querySelector('[data-json-status]'),input=tool.querySelector('[data-json-min-input]').value;try{output.value=JSON.stringify(JSON.parse(input));status.textContent='Valid JSON minified from '+input.length+' to '+output.value.length+' characters.';}catch(e){output.value='';status.textContent='Invalid JSON: '+e.message;} }
        function gradientCss() { var type=tool.querySelector('[data-gradient-type]').value,angle=tool.querySelector('[data-gradient-angle]').value,one=tool.querySelector('[data-gradient-one]').value,two=tool.querySelector('[data-gradient-two]').value,gradient=type==='radial'?'radial-gradient(circle, '+one+', '+two+')':'linear-gradient('+angle+'deg, '+one+', '+two+')';tool.querySelector('[data-gradient-preview]').style.background=gradient;tool.querySelector('[data-angle-label]').textContent=angle+'°';tool.querySelector('[data-gradient-angle]').disabled=type==='radial';output.value='background: '+gradient+';'; }

        function tracePng() {
            var input=tool.querySelector('[data-png-input]'),file=input.files&&input.files[0],status=tool.querySelector('[data-svg-status]'),preview=tool.querySelector('[data-svg-preview]');if(!file){status.textContent='Upload a PNG first.';return;}if(file.type!=='image/png'){status.textContent='Only PNG files are supported.';return;}
            var url=URL.createObjectURL(file),image=new Image();image.onload=function(){var max=160,scale=Math.min(1,max/image.naturalWidth,max/image.naturalHeight),width=Math.max(1,Math.round(image.naturalWidth*scale)),height=Math.max(1,Math.round(image.naturalHeight*scale)),canvas=document.createElement('canvas');canvas.width=width;canvas.height=height;var ctx=canvas.getContext('2d');ctx.drawImage(image,0,0,width,height);URL.revokeObjectURL(url);var data=ctx.getImageData(0,0,width,height).data,step=Number(tool.querySelector('[data-color-step]').value),rects=[];
                function colorAt(x,y){var i=(y*width+x)*4,a=data[i+3];if(a<16)return null;function q(v){return step===1?v:Math.min(255,Math.round(v/step)*step);}return [q(data[i]),q(data[i+1]),q(data[i+2]),a];}
                for(var y=0;y<height;y++){var x=0;while(x<width){var color=colorAt(x,y);if(!color){x++;continue;}var end=x+1;while(end<width){var next=colorAt(end,y);if(!next||next.join(',')!==color.join(','))break;end++;}var fill='rgb('+color.slice(0,3).join(',')+')',opacity=color[3]===255?'':' fill-opacity="'+(color[3]/255).toFixed(3)+'"';rects.push('<rect x="'+x+'" y="'+y+'" width="'+(end-x)+'" height="1" fill="'+fill+'"'+opacity+'/>');x=end;}}
                var svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '+width+' '+height+'" width="'+image.naturalWidth+'" height="'+image.naturalHeight+'" shape-rendering="crispEdges">\n'+rects.map(function(r){return '  '+r;}).join('\n')+'\n</svg>';output.value=svg;preview.innerHTML=svg;status.textContent='Created '+rects.length+' colored SVG runs at '+width+'×'+height+' trace resolution. Best results come from simple flat graphics.';
            };image.onerror=function(){URL.revokeObjectURL(url);status.textContent='Could not read this PNG.';};image.src=url;
        }
        function sortText() { var input=tool.querySelector('[data-sort-input]').value,lines=input.split('\n'),original=lines.length,ignore=tool.querySelector('[data-sort-ignore]').checked,remove=tool.querySelector('[data-sort-empty]').checked,modeValue=tool.querySelector('[data-sort-mode]').value;if(remove)lines=lines.filter(function(line){return line.trim()!=='';});function compare(a,b){var left=ignore?a.toLocaleLowerCase():a,right=ignore?b.toLocaleLowerCase():b;return left.localeCompare(right,undefined,{numeric:true});}lines.sort(function(a,b){if(modeValue==='length')return a.length-b.length||compare(a,b);var result=compare(a,b);return modeValue==='za'?-result:result;});output.value=lines.join('\n');tool.querySelector('[data-sort-summary]').textContent='Sorted '+original+' original line'+(original===1?'':'s')+' into '+lines.length+' output line'+(lines.length===1?'':'s')+'.'; }

        function transformImages() {
            var files=Array.from(tool.querySelector('[data-rotate-input]').files||[]),operation=tool.querySelector('[data-image-operation]').value,wrap=tool.querySelector('[data-transform-previews]'),status=tool.querySelector('[data-image-status]');wrap.innerHTML='';if(!files.length){status.textContent='Choose one or more images first.';return;}status.textContent='Processing '+files.length+' image(s)…';var done=0;
            files.forEach(function(file,index){var url=URL.createObjectURL(file),image=new Image();image.onload=function(){var rotate=['90','180','270'].indexOf(operation)>=0?Number(operation):0,swap=rotate===90||rotate===270,canvas=document.createElement('canvas');canvas.width=swap?image.naturalHeight:image.naturalWidth;canvas.height=swap?image.naturalWidth:image.naturalHeight;var ctx=canvas.getContext('2d');ctx.translate(canvas.width/2,canvas.height/2);if(rotate)ctx.rotate(rotate*Math.PI/180);if(operation==='flip-h')ctx.scale(-1,1);if(operation==='flip-v')ctx.scale(1,-1);ctx.drawImage(image,-image.naturalWidth/2,-image.naturalHeight/2);URL.revokeObjectURL(url);var type=file.type==='image/png'?'image/png':'image/jpeg',ext=type==='image/png'?'.png':'.jpg',card=document.createElement('div'),preview=document.createElement('img'),label=document.createElement('strong'),button=document.createElement('button');card.className='finance-result-item';preview.className='image-output-preview';preview.alt='Transformed preview of '+file.name;preview.src=canvas.toDataURL(type,.92);label.textContent=file.name;button.type='button';button.className='btn btn-secondary btn-sm';button.textContent='Download Image';button.onclick=function(){var link=document.createElement('a');link.href=preview.src;link.download=file.name.replace(/\.[^.]+$/,'')+'-'+operation+ext;document.body.appendChild(link);link.click();link.remove();};card.append(preview,label,button);wrap.appendChild(card);done++;status.textContent='Processed '+done+' of '+files.length+' image(s).';};image.onerror=function(){URL.revokeObjectURL(url);done++;status.textContent='Processed '+done+' of '+files.length+' image(s); one file could not be read.';};image.src=url;});
        }
        var regexExamples={email:{pattern:'\\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,}\\b',flags:'gi',text:'Contact hello@toolexa.in or support@example.com.'},url:{pattern:'https?:\\/\\/[^\\s]+',flags:'gi',text:'Visit https://toolexa.in/tools or http://example.com.'},number:{pattern:'-?\\d+(?:\\.\\d+)?',flags:'g',text:'Values: 42, -7 and 3.14.'},space:{pattern:'\\s{2,}',flags:'g',text:'Find   repeated    spaces.'}};
        function testRegex() { var pattern=tool.querySelector('[data-regex-pattern]').value,flags=tool.querySelector('[data-regex-flags]').value,textValue=tool.querySelector('[data-regex-text]').value,status=tool.querySelector('[data-regex-status]'),highlight=tool.querySelector('[data-regex-highlight]');highlight.innerHTML='';try{var effectiveFlags=flags.indexOf('g')>=0?flags:flags+'g',regex=new RegExp(pattern,effectiveFlags),matches=[],last=0,match;while((match=regex.exec(textValue))!==null){matches.push({index:match.index,text:match[0],groups:match.slice(1)});if(match[0]==='')regex.lastIndex++;if(matches.length>=1000)break;}matches.forEach(function(item){highlight.appendChild(document.createTextNode(textValue.slice(last,item.index)));var mark=document.createElement('mark');mark.textContent=item.text||'∅';highlight.appendChild(mark);last=item.index+item.text.length;});highlight.appendChild(document.createTextNode(textValue.slice(last)));status.textContent=matches.length+' match'+(matches.length===1?'':'es')+' found.';output.value=matches.length?matches.map(function(item,i){return (i+1)+'. "'+item.text+'" at index '+item.index+(item.groups.length?' | Groups: '+item.groups.map(function(g){return g===undefined?'undefined':g;}).join(', '):'');}).join('\n'):'No matches found.';}catch(e){status.textContent='Invalid regular expression: '+e.message;highlight.textContent=textValue;output.value='';} }
        function inspectUuid() { var value=tool.querySelector('[data-inspect-uuid]').value.trim().toLowerCase(),panel=tool.querySelector('[data-uuid-inspection]'),match=value.match(/^([0-9a-f]{8})-([0-9a-f]{4})-([0-9a-f]{4})-([0-9a-f]{4})-([0-9a-f]{12})$/);if(!match){panel.textContent='Invalid UUID. Enter the canonical 8-4-4-4-12 hexadecimal format.';output.value='Valid: No';return;}var version=parseInt(match[3][0],16),variantByte=parseInt(match[4].slice(0,2),16),variant=(variantByte&128)===0?'NCS backward compatibility':(variantByte&192)===128?'RFC 4122 / RFC 9562':(variantByte&224)===192?'Microsoft backward compatibility':'Future reserved',explanation=version===1?'Version 1 uses a time-based layout.':version===4?'Version 4 uses random or pseudorandom data.':version===7?'Version 7 begins with a Unix epoch millisecond timestamp.':'Version '+version+' determines how the remaining fields are interpreted.',rows=[['Valid','Yes'],['Normalized UUID',value],['Version',version],['Variant',variant],['time_low',match[1]],['time_mid',match[2]],['time_high_and_version',match[3]],['clock_seq',match[4]],['node',match[5]],['Explanation',explanation]];if(version===1){try{var ticks=BigInt('0x'+match[3].slice(1)+match[2]+match[1]),unix100ns=ticks-122192928000000000n,ms=Number(unix100ns/10000n);rows.push(['Version 1 Timestamp',new Date(ms).toISOString()]);}catch(e){}}panel.innerHTML='';var list=document.createElement('div');list.className='browser-result-list';rows.forEach(function(row){var item=document.createElement('div'),code=document.createElement('code');code.textContent=row[0]+': '+row[1];item.appendChild(code);list.appendChild(item);});panel.appendChild(list);output.value=rows.map(function(row){return row[0]+': '+row[1];}).join('\n'); }
        function duration(seconds) { if(!seconds)return '0 seconds';var minutes=Math.floor(seconds/60),remaining=Math.ceil(seconds%60);return minutes?(minutes+' min '+remaining+' sec'):(remaining+' sec'); }
        function calculateReading() { var textValue=tool.querySelector('[data-reading-text]').value.trim(),words=textValue?textValue.split(/\s+/u).filter(Boolean).length:0,characters=tool.querySelector('[data-reading-text]').value.length,speed=Number(tool.querySelector('[data-reading-speed]').value),reading=words/speed*60,speaking=words/130*60,rows=[['Words',words],['Characters',characters],['Reading Time',duration(reading)],['Speaking Time',duration(speaking)],['Reading Speed',speed+' WPM']],wrap=tool.querySelector('[data-reading-results]');wrap.innerHTML='';rows.forEach(function(row){var card=document.createElement('div'),label=document.createElement('span'),strong=document.createElement('strong');card.className='reading-metric';label.textContent=row[0];strong.textContent=row[1];card.append(label,strong);wrap.appendChild(card);});output.value=rows.map(function(row){return row[0]+': '+row[1];}).join('\n'); }
        function screenDetails() { var orientation=screen.orientation&&screen.orientation.type?screen.orientation.type:(window.innerWidth>=window.innerHeight?'landscape':'portrait'),rows=[['Screen Resolution',screen.width+' × '+screen.height+' CSS px'],['Available Screen',screen.availWidth+' × '+screen.availHeight+' CSS px'],['Viewport Size',window.innerWidth+' × '+window.innerHeight+' CSS px'],['Browser Window Size',window.outerWidth+' × '+window.outerHeight+' CSS px'],['Device Pixel Ratio',window.devicePixelRatio||1],['Color Depth',screen.colorDepth+' bits'],['Pixel Depth',screen.pixelDepth+' bits'],['Orientation',orientation],['User Agent',navigator.userAgent]],wrap=tool.querySelector('[data-screen-results]');wrap.innerHTML='';rows.forEach(function(row){var card=document.createElement('div'),label=document.createElement('span'),strong=document.createElement('strong');card.className='reading-metric';label.textContent=row[0];strong.textContent=row[1];card.append(label,strong);wrap.appendChild(card);});output.value=rows.map(function(row){return row[0]+': '+row[1];}).join('\n'); }

        tool.querySelectorAll('[data-action]').forEach(function (button) { button.addEventListener('click', function () { var action=button.dataset.action; if(action==='hash')hash(); if(action==='url')parseUrl(); if(action==='palette')renderPalette(true); if(action==='split')splitPdf(); if(action==='compare')compare(); if(action==='og')openGraph(); if(action==='entity')convertEntity(); if(action==='timezone')convertTimeZone(); if(action==='uuid')generateUuids(); if(action==='sitemap')generateSitemap(); if(action==='json-minify')minifyJson(); if(action==='png-svg')tracePng(); if(action==='sort')sortText(); if(action==='transform-images')transformImages(); if(action==='regex')testRegex(); if(action==='inspect-uuid')inspectUuid(); if(action==='reading')calculateReading(); if(action==='screen')screenDetails(); }); });
        var hashInput=tool.querySelector('[data-hash-input]'); if(hashInput) hashInput.addEventListener('input', hash);
        var pdfInput=tool.querySelector('[data-pdf-input]'); if(pdfInput) pdfInput.addEventListener('change', inspectPdf);
        var exportButton=tool.querySelector('[data-export]'); if(exportButton) exportButton.addEventListener('click', function(){ downloadBlob(new Blob([JSON.stringify(palette.map(function(c){return {hex:c.hex,rgb:'rgb('+rgb(c.hex).join(', ')+')'};}),null,2)],{type:'application/json'}),'toolexa-color-palette.json'); });
        var download=tool.querySelector('[data-download]'); if(download) download.addEventListener('click',function(){if(outputBytes)downloadBlob(new Blob([outputBytes],{type:'application/pdf'}),'toolexa-split.pdf');});
        var entityInput=tool.querySelector('[data-entity-input]'), entityMode=tool.querySelector('[data-entity-mode]'); if(entityInput)entityInput.addEventListener('input',convertEntity);if(entityMode)entityMode.addEventListener('change',convertEntity);
        if(mode==='open-graph-meta-tag-generator')tool.querySelectorAll('input, textarea, select').forEach(function(field){field.addEventListener('input',openGraph);field.addEventListener('change',openGraph);});
        if(mode==='time-zone-converter'){
            var zoneList=tool.querySelector('#timeZoneList');allZones().forEach(function(zone){var option=document.createElement('option');option.value=zone;zoneList.appendChild(option);});
            var guessed=Intl.DateTimeFormat().resolvedOptions().timeZone||'UTC';tool.querySelector('[data-source-zone]').value=guessed;tool.querySelector('[data-target-zone]').value=guessed==='UTC'?'Asia/Kolkata':'UTC';var now=new Date(),local=new Date(now.getTime()-now.getTimezoneOffset()*60000);tool.querySelector('[data-zone-datetime]').value=local.toISOString().slice(0,16);tool.querySelectorAll('[data-source-zone],[data-target-zone]').forEach(function(field){field.addEventListener('input',updateZoneClocks);});updateZoneClocks();window.setInterval(updateZoneClocks,30000);
        }
        var uuidDownload=tool.querySelector('[data-uuid-download]');if(uuidDownload)uuidDownload.addEventListener('click',function(){if(output.value)downloadBlob(new Blob([output.value+'\n'],{type:'text/plain;charset=utf-8'}),'toolexa-uuid-v4-batch.txt');});
        var sitemapAdd=tool.querySelector('[data-sitemap-add]');if(sitemapAdd){sitemapAdd.addEventListener('click',function(){addSitemapRow();});addSitemapRow({priority:'1.0',frequency:'weekly'});}
        var sitemapDownload=tool.querySelector('[data-sitemap-download]');if(sitemapDownload)sitemapDownload.addEventListener('click',function(){if(!output.value||output.value.indexOf('<?xml')!==0)generateSitemap();if(output.value.indexOf('<?xml')===0)downloadBlob(new Blob([output.value+'\n'],{type:'application/xml'}),'sitemap.xml');});
        var jsonDownload=tool.querySelector('[data-json-download]');if(jsonDownload)jsonDownload.addEventListener('click',function(){if(output.value)downloadBlob(new Blob([output.value+'\n'],{type:'application/json'}),'toolexa-minified.json');});
        if(mode==='css-gradient-generator'){tool.querySelectorAll('[data-gradient-type],[data-gradient-angle],[data-gradient-one],[data-gradient-two]').forEach(function(field){field.addEventListener('input',gradientCss);field.addEventListener('change',gradientCss);});gradientCss();}
        var gradientDownload=tool.querySelector('[data-gradient-download]');if(gradientDownload)gradientDownload.addEventListener('click',function(){downloadBlob(new Blob(['.gradient {\n  '+output.value+'\n}\n'],{type:'text/css'}),'toolexa-gradient.css');});
        var svgDownload=tool.querySelector('[data-svg-download]');if(svgDownload)svgDownload.addEventListener('click',function(){if(output.value.indexOf('<svg')===0)downloadBlob(new Blob([output.value],{type:'image/svg+xml'}),'toolexa-traced.svg');});
        var sortDownload=tool.querySelector('[data-sort-download]');if(sortDownload)sortDownload.addEventListener('click',function(){if(output.value)downloadBlob(new Blob([output.value+'\n'],{type:'text/plain;charset=utf-8'}),'toolexa-sorted-text.txt');});
        var regexExample=tool.querySelector('[data-regex-example]');if(regexExample)regexExample.addEventListener('change',function(){var example=regexExamples[regexExample.value];if(!example)return;tool.querySelector('[data-regex-pattern]').value=example.pattern;tool.querySelector('[data-regex-flags]').value=example.flags;tool.querySelector('[data-regex-text]').value=example.text;testRegex();});
        var readingText=tool.querySelector('[data-reading-text]'),readingSpeed=tool.querySelector('[data-reading-speed]');if(readingText){readingText.addEventListener('input',calculateReading);readingSpeed.addEventListener('change',calculateReading);calculateReading();}
        if(mode==='screen-resolution-checker'){screenDetails();window.addEventListener('resize',screenDetails);window.addEventListener('orientationchange',screenDetails);}
        if(clear) clear.addEventListener('click',function(){ tool.querySelectorAll('input, textarea').forEach(function(el){if(el.type!=='button')el.value='';}); if(output)output.value=''; var diff=tool.querySelector('[data-diff]');if(diff)diff.innerHTML=''; var panel=tool.querySelector('[data-pdf-result]');if(panel)panel.textContent='The extraction summary will appear here.'; outputBytes=null;if(download)download.classList.add('disabled'); });
        if(mode==='color-palette-generator')renderPalette(false);
    });

    document.querySelectorAll('[data-smart-search]').forEach(function (search) {
        var input = search.querySelector('[data-search-input]');
        var results = search.querySelector('[data-search-results]');
        var empty = search.querySelector('[data-search-empty]');
        var discovery = search.querySelector('[data-search-discovery]');
        var loader = search.querySelector('[data-search-loader]');
        var status = search.querySelector('[data-search-status]');
        var filters = Array.from(search.querySelectorAll('[data-search-filter]'));
        var recentSection = search.querySelector('[data-recent-searches]');
        var recentList = search.querySelector('[data-recent-list]');
        var stateNode = search.querySelector('[data-search-state]');
        var initialState = stateNode ? JSON.parse(stateNode.textContent) : {query: '', filter: 'all', results: null};
        var activeFilter = initialState.filter || 'all';
        var activeIndex = -1;
        var debounceTimer = null;
        var requestController = null;
        var responseCache = new Map();
        var recentKey = 'toolexa_recent_searches';

        function escapeRegExp(value) {
            return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function addHighlightedText(element, text, query) {
            var terms = query.trim().split(/\s+/).filter(function (term) { return term.length > 0; });
            if (!terms.length) {
                element.textContent = text;
                return;
            }
            var expression = new RegExp('(' + terms.map(escapeRegExp).join('|') + ')', 'gi');
            text.split(expression).forEach(function (part) {
                if (terms.some(function (term) { return part.toLowerCase() === term.toLowerCase(); })) {
                    var mark = document.createElement('mark');
                    mark.textContent = part;
                    element.appendChild(mark);
                } else {
                    element.appendChild(document.createTextNode(part));
                }
            });
        }

        function resultCard(item, query) {
            var link = document.createElement('a');
            var icon = document.createElement('span');
            var body = document.createElement('span');
            var title = document.createElement('strong');
            var category = document.createElement('span');
            var description = document.createElement('small');
            var action = document.createElement('span');
            link.className = 'smart-search-card';
            link.href = item.url;
            link.setAttribute('data-search-option', '');
            link.setAttribute('role', 'option');
            link.tabIndex = -1;
            icon.className = 'tool-icon';
            icon.setAttribute('aria-hidden', 'true');
            icon.textContent = item.icon;
            body.className = 'smart-search-card-body';
            addHighlightedText(title, item.title, query);
            addHighlightedText(category, item.category, query);
            addHighlightedText(description, item.description, query);
            action.className = 'btn btn-primary btn-sm';
            action.textContent = 'Open';
            body.append(title, category, description);
            link.append(icon, body, action);
            link.addEventListener('click', function () { saveRecent(query); });
            return link;
        }

        function render(payload) {
            var headings = {tools: 'Tools', articles: 'Articles', categories: 'Categories'};
            results.innerHTML = '';
            activeIndex = -1;
            Object.keys(headings).forEach(function (groupName) {
                var items = payload.groups[groupName] || [];
                if (!items.length) return;
                var section = document.createElement('section');
                var heading = document.createElement('h2');
                var list = document.createElement('div');
                section.className = 'smart-search-group';
                section.setAttribute('data-result-group', groupName);
                heading.textContent = headings[groupName];
                items.forEach(function (item) { list.appendChild(resultCard(item, payload.query)); });
                section.append(heading, list);
                results.appendChild(section);
            });
            results.hidden = payload.total === 0;
            empty.hidden = payload.total !== 0;
            discovery.hidden = true;
            input.setAttribute('aria-expanded', payload.total ? 'true' : 'false');
            status.textContent = payload.total === 1 ? '1 result found' : payload.total + ' results found';
        }

        function updateAddress(query) {
            var url = new URL(window.location.href);
            if (query) url.searchParams.set('q', query); else url.searchParams.delete('q');
            if (activeFilter !== 'all') url.searchParams.set('filter', activeFilter); else url.searchParams.delete('filter');
            window.history.replaceState({}, '', url.pathname + url.search);
            var robots = document.querySelector('meta[name="robots"]');
            if (robots) robots.content = query ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        }

        function performSearch() {
            var query = input.value.trim();
            updateAddress(query);
            if (!query.length) {
                if (requestController) requestController.abort();
                results.hidden = true;
                empty.hidden = true;
                discovery.hidden = false;
                input.setAttribute('aria-expanded', 'false');
                status.textContent = 'Enter a character to search';
                loader.classList.remove('active');
                return;
            }
            var key = activeFilter + ':' + query.toLowerCase();
            if (responseCache.has(key)) {
                render(responseCache.get(key));
                return;
            }
            if (requestController) requestController.abort();
            requestController = new AbortController();
            loader.classList.add('active');
            fetch(search.getAttribute('data-endpoint') + '?q=' + encodeURIComponent(query) + '&filter=' + encodeURIComponent(activeFilter), {
                headers: {'Accept': 'application/json'},
                signal: requestController.signal
            }).then(function (response) {
                if (!response.ok) throw new Error('Search request failed');
                return response.json();
            }).then(function (payload) {
                responseCache.set(key, payload);
                render(payload);
            }).catch(function (error) {
                if (error.name !== 'AbortError') status.textContent = 'Search is temporarily unavailable. Please try again.';
            }).finally(function () {
                loader.classList.remove('active');
            });
        }

        function scheduleSearch() {
            window.clearTimeout(debounceTimer);
            debounceTimer = window.setTimeout(performSearch, 250);
        }

        function getRecent() {
            if (window.ToolexaStorage) return window.ToolexaStorage.getRecentSearches();
            try {
                var stored = JSON.parse(window.localStorage.getItem(recentKey) || '[]');
                return Array.isArray(stored) ? stored.slice(0, 20) : [];
            } catch (error) {
                return [];
            }
        }

        function saveRecent(query) {
            query = query.trim();
            if (!query) return;
            if (window.ToolexaStorage) {
                window.ToolexaStorage.addRecentSearch(query);
                renderRecent();
                return;
            }
            var recent = getRecent().filter(function (item) { return item.toLowerCase() !== query.toLowerCase(); });
            recent.unshift(query);
            try { window.localStorage.setItem(recentKey, JSON.stringify(recent.slice(0, 20))); } catch (error) {}
            renderRecent();
        }

        function useSearch(value) {
            input.value = value;
            input.focus();
            performSearch();
        }

        function renderRecent() {
            var recent = getRecent();
            recentList.innerHTML = '';
            recentSection.hidden = recent.length === 0;
            recent.forEach(function (item) {
                var button = document.createElement('button');
                button.type = 'button';
                button.textContent = item;
                button.addEventListener('click', function () { useSearch(item); });
                recentList.appendChild(button);
            });
        }

        function selectableResults() {
            return Array.from(results.querySelectorAll('[data-search-option]'));
        }

        function selectResult(index) {
            var options = selectableResults();
            if (!options.length) return;
            options.forEach(function (option) { option.removeAttribute('aria-selected'); });
            activeIndex = (index + options.length) % options.length;
            options[activeIndex].setAttribute('aria-selected', 'true');
            options[activeIndex].focus({preventScroll: true});
            options[activeIndex].scrollIntoView({block: 'nearest'});
        }

        input.addEventListener('input', scheduleSearch);
        input.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') { event.preventDefault(); selectResult(activeIndex + 1); }
            if (event.key === 'ArrowUp') { event.preventDefault(); selectResult(activeIndex - 1); }
            if (event.key === 'Enter') {
                var options = selectableResults();
                if (activeIndex >= 0 && options[activeIndex]) { event.preventDefault(); saveRecent(input.value); window.location.href = options[activeIndex].href; }
            }
            if (event.key === 'Escape') { results.hidden = true; input.setAttribute('aria-expanded', 'false'); activeIndex = -1; }
        });
        results.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') { event.preventDefault(); selectResult(activeIndex + 1); }
            if (event.key === 'ArrowUp') { event.preventDefault(); selectResult(activeIndex - 1); }
            if (event.key === 'Escape') { event.preventDefault(); input.focus(); results.hidden = true; input.setAttribute('aria-expanded', 'false'); }
        });
        filters.forEach(function (button) {
            button.addEventListener('click', function () {
                activeFilter = button.getAttribute('data-search-filter');
                filters.forEach(function (filter) {
                    var active = filter === button;
                    filter.classList.toggle('active', active);
                    filter.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                performSearch();
            });
        });
        search.querySelectorAll('[data-search-chip]').forEach(function (button) {
            button.addEventListener('click', function () { useSearch(button.getAttribute('data-search-chip')); });
        });
        var clearRecent = search.querySelector('[data-clear-recent]');
        if (clearRecent) clearRecent.addEventListener('click', function () {
            if (window.ToolexaStorage) { var state = window.ToolexaStorage.read(); state.recent.searches = []; window.ToolexaStorage.write(state); }
            try { window.localStorage.removeItem(recentKey); } catch (error) {}
            renderRecent();
        });
        renderRecent();
        if (initialState.query && initialState.results) { render(initialState.results); saveRecent(initialState.query); }
    });

    var counters = document.querySelectorAll('[data-counter]');
    if (counters.length) {
        var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        function completeCounter(counter) {
            counter.textContent = Number(counter.getAttribute('data-counter')).toLocaleString() + (counter.getAttribute('data-counter-suffix') || '');
        }
        function animateCounter(counter) {
            if (counter.getAttribute('data-counted')) return;
            counter.setAttribute('data-counted', 'true');
            var target = Number(counter.getAttribute('data-counter'));
            var suffix = counter.getAttribute('data-counter-suffix') || '';
            if (reduceMotion) { completeCounter(counter); return; }
            var started = performance.now();
            function frame(now) {
                var progress = Math.min(1, (now - started) / 700);
                counter.textContent = Math.round(target * (1 - Math.pow(1 - progress, 3))).toLocaleString() + suffix;
                if (progress < 1) window.requestAnimationFrame(frame);
            }
            window.requestAnimationFrame(frame);
        }
        if ('IntersectionObserver' in window) {
            var counterObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) { if (entry.isIntersecting) { animateCounter(entry.target); observer.unobserve(entry.target); } });
            }, {threshold: .3});
            counters.forEach(function (counter) { counterObserver.observe(counter); });
        } else {
            counters.forEach(completeCounter);
        }
    }

    document.querySelectorAll('[data-newsletter-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            var status = form.querySelector('[data-newsletter-status]');
            if (status) status.textContent = 'Thanks for your interest. Newsletter delivery will be available soon.';
        });
    });

    document.querySelectorAll('img:not([loading])').forEach(function (image) {
        image.setAttribute('loading', 'lazy');
        image.setAttribute('decoding', 'async');
    });
}());
