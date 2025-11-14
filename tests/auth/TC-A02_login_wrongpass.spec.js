import { test, expect } from '@playwright/test';

test('TC-A02 - Login con contraseña incorrecta', async ({ page }) => {
  await page.goto('http://localhost/login.php');
  await page.waitForSelector('form');

  await page.fill('#mail', 'nicolasmarquez005@gmail.com');
  await page.fill('#password', 'incorrecta');

  await Promise.all([
    page.waitForLoadState('domcontentloaded'),
    page.getByRole('button', { name: 'Login' }).click(),
  ]);

  await expect(
    page.getByText('Usuario o contraseña incorrectos', { exact: false })
  ).toBeVisible();

  await expect(page).toHaveURL(/login\.php$/);
});
