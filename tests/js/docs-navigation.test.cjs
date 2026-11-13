const assert = require('node:assert/strict');
const {readFileSync} = require('node:fs');
const {join} = require('node:path');
const test = require('node:test');
const vm = require('node:vm');
const source = readFileSync(join(__dirname, '../../web/js/docs-navigation.js'), 'utf8');

function setup(wide) {
    const events = {};
    const panel = {open: true};
    const media = {matches: wide, addEventListener: (event, fn) => { events.media = fn; }};
    const links = ['intro', 'endpoints', 'create'].map((id) => ({
        hash: '#' + id,
        active: false,
        attributes: {},
        classList: {contains() { return links.find((link) => link.hash === '#' + id).active; }},
        setAttribute(name, value) { this.attributes[name] = value; },
        removeAttribute(name) { delete this.attributes[name]; },
    }));
    const heading = {
        attributes: {},
        setAttribute(name, value) { this.attributes[name] = value; },
        focus(options) { this.focused = true; this.preventScroll = options.preventScroll; },
    };
    const nav = {
        querySelector: () => panel,
        querySelectorAll: () => links,
        contains: (link) => links.includes(link),
        addEventListener: (event, fn) => { events.click = fn; },
    };
    const calls = [];
    const window = {matchMedia: () => media, addEventListener: (event, fn) => { events[event] = fn; }};
    const jquery = (target) => ({
        on: (event, fn) => { assert.equal(target, window); events.activate = fn; },
        trigger: () => { assert.equal(target, window); events.activate(); },
        scrollspy: (options) => { assert.equal(target, 'body'); calls.push(options); },
    });
    vm.runInNewContext(source, {
        jQuery: jquery,
        window,
        document: {querySelector: () => nav, getElementById: () => heading},
    });
    return {events, panel, media, links, heading, calls};
}

test('desktop index opens and delegates scroll tracking to installed Bootstrap', () => {
    const state = setup(true);
    assert.equal(state.panel.open, true);
    assert.equal(state.calls[0].target, '#docs-nav');
    assert.equal(state.links[0].attributes['aria-current'], 'location');
});

test('only the most specific active section is announced', () => {
    const state = setup(true);
    state.links[1].active = state.links[2].active = true;
    state.events.activate();
    assert.equal(state.links[0].attributes['aria-current'], undefined);
    assert.equal(state.links[1].attributes['aria-current'], undefined);
    assert.equal(state.links[2].attributes['aria-current'], 'location');
});

test('mobile index closes after a normal anchor activation and focuses the heading', () => {
    const state = setup(false);
    assert.equal(state.panel.open, false);
    state.panel.open = true;
    state.events.click({target: {closest: () => state.links[2]}, button: 0});
    assert.equal(state.panel.open, false);
    assert.equal(state.heading.focused, true);
    assert.equal(state.heading.preventScroll, true);
    assert.equal(state.heading.attributes.tabindex, '-1');
});

test('modified clicks preserve native new-tab behavior', () => {
    const state = setup(false);
    state.panel.open = true;
    state.events.click({target: {closest: () => state.links[2]}, button: 0, metaKey: true});
    assert.equal(state.panel.open, true);
    assert.equal(state.heading.focused, undefined);
});

test('responsive changes restore an open desktop index', () => {
    const state = setup(false);
    state.media.matches = true;
    state.events.media();
    assert.equal(state.panel.open, true);
    assert.equal(state.calls[state.calls.length - 1], 'refresh');
});
