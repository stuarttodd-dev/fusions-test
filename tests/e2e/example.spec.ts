import { test, expect } from '@playwright/test';

test.describe('Smoke test', () => {
    test('Playwright is wired up', async () => {
        expect(true).toBeTruthy();
    });
});

