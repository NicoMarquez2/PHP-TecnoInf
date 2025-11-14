import { test, expect } from '@playwright/test';

test('TC-C04 - Registro con contraseña menor a 6 caracteres (comportamiento actual)', async ({ page }) => {
  await page.goto('http://localhost/register.php');
  await page.waitForSelector('form');

  const email = `shortpass${Date.now()}@mail.com`;

  await page.fill('#username', 'Usuario ShortPass');
  await page.fill('#email', email);
  await page.fill('#password', '123'); // < 6

  await Promise.all([
    page.waitForLoadState('domcontentloaded'),
    page.getByRole('button', { name: 'Completar registro' }).click(),
  ]);

  await expect(
    page.getByText('La contraseña debe tener al menos 6 caracteres', { exact: false })
  ).toBeVisible();
});
