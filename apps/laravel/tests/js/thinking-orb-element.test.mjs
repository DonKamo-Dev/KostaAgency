import test, { beforeEach } from 'node:test';
import assert from 'node:assert/strict';

class FakeClassList {
    constructor() {
        this.values = new Set();
    }

    add(...values) {
        values.forEach((value) => this.values.add(value));
    }

    remove(...values) {
        values.forEach((value) => this.values.delete(value));
    }

    contains(value) {
        return this.values.has(value);
    }
}

class FakeElement {
    constructor(tagName = 'element') {
        this.tagName = tagName;
        this.isConnected = false;
        this.attributes = new Map();
        this.children = [];
        this.style = {};
        this.classList = new FakeClassList();
        this.className = '';
        this.textContent = '';
        this._innerHTML = '';
    }

    set innerHTML(value) {
        this._innerHTML = value;
        this.children = [];
    }

    get innerHTML() {
        return this._innerHTML;
    }

    setAttribute(name, value) {
        this.attributes.set(name, String(value));
    }

    getAttribute(name) {
        return this.attributes.get(name) ?? null;
    }

    hasAttribute(name) {
        return this.attributes.has(name);
    }

    appendChild(child) {
        this.children.push(child);
        return child;
    }
}

class FakeCanvas extends FakeElement {
    constructor() {
        super('canvas');
        this.context = {
            arcs: [],
            clearCount: 0,
            setTransform() {},
            clearRect: () => {
                this.context.clearCount += 1;
            },
            beginPath() {},
            arc: (...args) => {
                this.context.arcs.push(args);
            },
            fill() {},
            moveTo() {},
            lineTo() {},
            stroke() {},
        };
    }

    getContext(kind) {
        return kind === '2d' ? this.context : null;
    }
}

const customElementRegistry = new Map();
const animationFrames = new Map();
const observers = new Set();
let nextAnimationFrameId = 1;
let reducedMotion = false;

globalThis.HTMLElement = FakeElement;
globalThis.customElements = {
    define(name, constructor) {
        customElementRegistry.set(name, constructor);
    },
    get(name) {
        return customElementRegistry.get(name);
    },
};
globalThis.document = {
    body: new FakeElement('body'),
    documentElement: new FakeElement('html'),
    visibilityState: 'visible',
    createElement(tagName) {
        return tagName === 'canvas' ? new FakeCanvas() : new FakeElement(tagName);
    },
    addEventListener() {},
    getElementById() {
        return null;
    },
};
globalThis.window = {
    devicePixelRatio: 1,
    fetch: async () => ({ ok: true }),
    location: { origin: 'https://kamo.test' },
    matchMedia() {
        return { matches: reducedMotion };
    },
};
globalThis.requestAnimationFrame = (callback) => {
    const id = nextAnimationFrameId++;
    animationFrames.set(id, callback);
    return id;
};
globalThis.cancelAnimationFrame = (id) => {
    animationFrames.delete(id);
};
globalThis.IntersectionObserver = class {
    constructor(callback) {
        this.callback = callback;
        this.connected = true;
        observers.add(this);
    }

    observe() {}

    disconnect() {
        this.connected = false;
        observers.delete(this);
    }
};

const { ThinkingOrbElement } = await import('../../resources/js/thinking-orb.js');
const { MODE_DRAWS } = await import('thinking-orbs/engine');

beforeEach(() => {
    animationFrames.clear();
    observers.clear();
    nextAnimationFrameId = 1;
    reducedMotion = false;
});

function canvasFor(element) {
    return element.children[0].children[0];
}

function setInitialAttributes(element, attributes) {
    for (const [name, value] of Object.entries(attributes)) {
        element.setAttribute(name, value);
    }
}

function connect(element) {
    element.isConnected = true;
    element.connectedCallback();
}

function disconnect(element) {
    element.isConnected = false;
    element.disconnectedCallback();
}

test('uses a supported renderer preset without changing canvas display size or accessible name', () => {
    const element = new ThinkingOrbElement();
    setInitialAttributes(element, { size: 14, label: 'Procesando datos' });

    assert.doesNotThrow(() => connect(element));

    const canvas = canvasFor(element);
    assert.equal(canvas.width, 14);
    assert.equal(canvas.height, 14);
    assert.equal(canvas.style.width, '14px');
    assert.equal(canvas.style.height, '14px');
    assert.equal(canvas.getAttribute('role'), 'img');
    assert.equal(canvas.getAttribute('aria-label'), 'Procesando datos');
    disconnect(element);
});

test('reduced motion draws exactly the stable t=0.6 frame and starts no animation', () => {
    reducedMotion = true;
    const element = new ThinkingOrbElement();
    setInitialAttributes(element, { size: 20, state: 'working' });
    const originalDraw = MODE_DRAWS.orbits;
    const drawTimes = [];
    MODE_DRAWS.orbits = (context, size, time) => {
        drawTimes.push({ context, size, time });
    };

    try {
        connect(element);
    } finally {
        MODE_DRAWS.orbits = originalDraw;
    }

    const context = canvasFor(element).context;
    assert.equal(context.clearCount, 1);
    assert.deepEqual(drawTimes, [{ context, size: 20, time: 0.6 }]);
    assert.equal(animationFrames.size, 0);
    assert.equal(observers.size, 0);
});

test('reduced-motion attribute changes and reconnects never leave animation frames behind', () => {
    reducedMotion = true;
    const element = new ThinkingOrbElement();
    setInitialAttributes(element, { size: 20, state: 'working' });
    connect(element);

    element.setAttribute('state', 'searching');
    element.attributeChangedCallback('state', 'working', 'searching');
    assert.equal(animationFrames.size, 0);

    disconnect(element);
    connect(element);
    assert.equal(animationFrames.size, 0);
    assert.equal(observers.size, 0);
});

test('animated attribute changes, disconnects, and reconnects keep at most one active frame', () => {
    const element = new ThinkingOrbElement();
    setInitialAttributes(element, { size: 20, state: 'working' });
    connect(element);
    assert.equal(animationFrames.size, 1);
    assert.equal(observers.size, 1);

    element.setAttribute('state', 'searching');
    element.attributeChangedCallback('state', 'working', 'searching');
    assert.equal(animationFrames.size, 1);
    assert.equal(observers.size, 1);

    disconnect(element);
    assert.equal(animationFrames.size, 0);
    assert.equal(observers.size, 0);

    connect(element);
    assert.equal(animationFrames.size, 1);
    assert.equal(observers.size, 1);
    disconnect(element);
});

test('attribute changes while detached create no observer or animation frame and reconnect normally', () => {
    const element = new ThinkingOrbElement();
    setInitialAttributes(element, { size: 20, state: 'working' });
    connect(element);
    disconnect(element);

    element.setAttribute('state', 'searching');
    element.attributeChangedCallback('state', 'working', 'searching');
    assert.equal(animationFrames.size, 0);
    assert.equal(observers.size, 0);

    connect(element);
    assert.equal(animationFrames.size, 1);
    assert.equal(observers.size, 1);
    disconnect(element);
});
