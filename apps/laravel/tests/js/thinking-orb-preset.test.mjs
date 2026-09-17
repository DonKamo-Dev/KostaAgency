import test from 'node:test';
import assert from 'node:assert/strict';
import { presetSizeFor } from '../../resources/js/thinking-orb-preset.js';

test('maps every small display size to the 20 preset', () => {
    for (const size of [14, 16, 18, 20, 24, 40, 47]) {
        assert.equal(presetSizeFor(size), 20);
    }
});

test('maps large display sizes to the 64 preset', () => {
    for (const size of [48, 56, 64, 80]) {
        assert.equal(presetSizeFor(size), 64);
    }
});
