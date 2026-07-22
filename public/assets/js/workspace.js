(function () {
    'use strict';

    var root = document.querySelector('[data-tool-workspace]');
    if (!root) return;

    var STORAGE_KEY = 'toolexa_workspaces_v1';
    var MAX_PANELS = 4;
    var catalogNode = root.querySelector('[data-workspace-catalog]');
    var canvas = root.querySelector('[data-workspace-canvas]');
    var status = root.querySelector('[data-workspace-status]');
    var limit = root.querySelector('[data-workspace-limit]');
    var switches = root.querySelector('[data-workspace-switches]');
    var catalog = [];
    try { catalog = JSON.parse(catalogNode.textContent); } catch (error) {}
    var tools = {};
    catalog.forEach(function (tool) { tools[tool.slug] = tool; });

    function defaults() { return {version: 1, active: {name: '', panels: []}, saved: []}; }
    function read() {
        try {
            var value = JSON.parse(localStorage.getItem(STORAGE_KEY));
            if (!value || !value.active || !Array.isArray(value.active.panels)) return defaults();
            value.active.panels = value.active.panels.filter(function (panel) { return tools[panel.slug]; }).slice(0, MAX_PANELS);
            value.saved = Array.isArray(value.saved) ? value.saved.filter(function (workspace) { return workspace && Array.isArray(workspace.panels); }).slice(0, 30) : [];
            return value;
        } catch (error) { return defaults(); }
    }
    var state = read();
    function persist() { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); }
    function announce(message) { status.textContent = message; }
    function panelId() { return 'panel-' + Date.now().toString(36) + Math.random().toString(36).slice(2, 7); }
    function layout(index) {
        var width = Math.max(360, (canvas.clientWidth - 44) / 2);
        return {x: 12 + (index % 2) * (width + 16), y: 12 + Math.floor(index / 2) * 430, width: width, height: 410};
    }
    function add(slug) {
        var tool = tools[slug];
        if (!tool) return announce('That tool is unavailable.');
        if (state.active.panels.some(function (panel) { return panel.slug === slug; })) return announce(tool.name + ' is already open.');
        if (state.active.panels.length >= MAX_PANELS) return announce('Close a panel before adding another tool.');
        state.active.panels.push(Object.assign({id: panelId(), slug: slug, minimized: false, maximized: false}, layout(state.active.panels.length)));
        state.active.name = '';
        persist(); render(); announce(tool.name + ' added to the workspace.');
    }
    function updatePanel(id, changes) {
        var panel = state.active.panels.find(function (item) { return item.id === id; });
        if (panel) Object.assign(panel, changes);
        persist();
    }
    function button(label, title, handler) {
        var element = document.createElement('button');
        element.type = 'button'; element.className = 'workspace-panel-action'; element.textContent = label;
        element.title = title; element.setAttribute('aria-label', title); element.addEventListener('click', handler);
        return element;
    }
    function makePanel(panel) {
        var tool = tools[panel.slug];
        var element = document.createElement('article');
        var header = document.createElement('header');
        var title = document.createElement('strong');
        var controls = document.createElement('span');
        var iframe = document.createElement('iframe');
        element.className = 'workspace-panel' + (panel.minimized ? ' is-minimized' : '') + (panel.maximized ? ' is-maximized' : '');
        element.dataset.panelId = panel.id;
        element.style.left = panel.x + 'px'; element.style.top = panel.y + 'px'; element.style.width = panel.width + 'px'; element.style.height = panel.height + 'px';
        header.className = 'workspace-panel-header'; title.textContent = tool.icon + '  ' + tool.name;
        controls.className = 'workspace-panel-controls';
        controls.append(
            button('−', panel.minimized ? 'Restore ' + tool.name : 'Minimize ' + tool.name, function () { updatePanel(panel.id, {minimized: !panel.minimized, maximized: false}); render(); }),
            button('□', panel.maximized ? 'Restore ' + tool.name : 'Maximize ' + tool.name, function () { updatePanel(panel.id, {maximized: !panel.maximized, minimized: false}); render(); }),
            button('×', 'Close ' + tool.name, function () { state.active.panels = state.active.panels.filter(function (item) { return item.id !== panel.id; }); state.active.name = ''; persist(); render(); announce(tool.name + ' closed.'); })
        );
        header.append(title, controls);
        iframe.src = tool.url; iframe.title = tool.name; iframe.loading = 'lazy';
        element.append(header, iframe);
        enableDrag(element, header, panel);
        if ('ResizeObserver' in window) {
            var observer = new ResizeObserver(function () {
                if (!element.classList.contains('is-maximized') && window.innerWidth > 900) updatePanel(panel.id, {width: element.offsetWidth, height: element.offsetHeight});
            });
            observer.observe(element);
        }
        return element;
    }
    function enableDrag(element, handle, panel) {
        handle.addEventListener('pointerdown', function (event) {
            if (event.target.closest('button') || window.innerWidth <= 900 || panel.maximized) return;
            event.preventDefault(); handle.setPointerCapture(event.pointerId);
            var startX = event.clientX; var startY = event.clientY; var left = element.offsetLeft; var top = element.offsetTop;
            function move(moveEvent) {
                var x = Math.max(0, Math.min(canvas.clientWidth - element.offsetWidth, left + moveEvent.clientX - startX));
                var y = Math.max(0, top + moveEvent.clientY - startY);
                element.style.left = x + 'px'; element.style.top = y + 'px';
            }
            function end() {
                handle.removeEventListener('pointermove', move); handle.removeEventListener('pointerup', end);
                updatePanel(panel.id, {x: element.offsetLeft, y: element.offsetTop});
            }
            handle.addEventListener('pointermove', move); handle.addEventListener('pointerup', end);
        });
    }
    function renderSwitches() {
        switches.innerHTML = '';
        if (!state.saved.length) { switches.innerHTML = '<span class="workspace-switch-empty">No saved workspaces yet.</span>'; return; }
        state.saved.forEach(function (saved, index) {
            var group = document.createElement('span'); group.className = 'workspace-switch';
            var open = button(saved.name + ' · ' + saved.panels.length, 'Open ' + saved.name, function () {
                state.active = {name: saved.name, panels: saved.panels.filter(function (panel) { return tools[panel.slug]; }).slice(0, MAX_PANELS).map(function (panel) { return Object.assign({}, panel, {id: panelId()}); })};
                persist(); render(); announce(saved.name + ' workspace opened.');
            });
            var remove = button('×', 'Delete ' + saved.name, function () { state.saved.splice(index, 1); persist(); renderSwitches(); announce(saved.name + ' deleted.'); });
            group.append(open, remove); switches.appendChild(group);
        });
    }
    function render() {
        canvas.innerHTML = '';
        if (!state.active.panels.length) canvas.appendChild(root.querySelector('[data-workspace-empty-template]').content.cloneNode(true));
        else state.active.panels.forEach(function (panel) { canvas.appendChild(makePanel(panel)); });
        limit.textContent = state.active.panels.length + ' of ' + MAX_PANELS + ' tools';
        renderSwitches();
    }
    root.querySelector('[data-workspace-add-form]').addEventListener('submit', function (event) { event.preventDefault(); var select = root.querySelector('[data-workspace-tool-select]'); add(select.value); select.value = ''; });
    root.querySelector('[data-workspace-save-form]').addEventListener('submit', function (event) {
        event.preventDefault(); var input = root.querySelector('#workspace-name'); var name = input.value.trim();
        if (!name || !state.active.panels.length) return announce('Add a tool before saving this workspace.');
        var saved = {name: name, panels: state.active.panels.map(function (panel) { return Object.assign({}, panel); }), updatedAt: new Date().toISOString()};
        var existing = state.saved.findIndex(function (item) { return item.name.toLowerCase() === name.toLowerCase(); });
        if (existing >= 0) state.saved[existing] = saved; else state.saved.unshift(saved);
        state.saved = state.saved.slice(0, 30); state.active.name = name; persist(); input.value = ''; renderSwitches(); announce(name + ' workspace saved locally.');
    });
    var requested = root.dataset.addTool;
    if (requested) { add(requested); window.history.replaceState({}, '', window.location.pathname); } else render();
})();
