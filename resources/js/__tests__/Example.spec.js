import { describe, expect, it } from '@jest/globals';

describe('Example utility', () => {
    it('adds numbers correctly', () => {
        const add = (a, b) => a + b;
        expect(add(2, 3)).toBe(5);
    });
});

