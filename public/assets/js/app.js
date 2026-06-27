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

    document.querySelectorAll('img:not([loading])').forEach(function (image) {
        image.setAttribute('loading', 'lazy');
        image.setAttribute('decoding', 'async');
    });
}());
