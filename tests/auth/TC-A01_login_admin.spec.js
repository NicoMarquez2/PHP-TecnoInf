import { test, expect } from '@playwright/test';
import { loginAsAdmin } from '../helpers/auth';

test('TC-A01 - Login administrador válido', async ({ page }) => {
  await loginAsAdmin(page);
  await expect(page).not.toHaveURL(/login\.php$/);
});
