import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-C01 - Login cliente válido', async ({ page }) => {
  await loginAsClient(page);
  await expect(page).not.toHaveURL(/login\.php$/);
});
