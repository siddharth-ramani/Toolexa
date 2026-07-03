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

    document.querySelectorAll('img:not([loading])').forEach(function (image) {
        image.setAttribute('loading', 'lazy');
        image.setAttribute('decoding', 'async');
    });
}());
