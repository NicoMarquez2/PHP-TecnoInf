import { test, expect } from '@playwright/test';

test('TC-C03 - Registro con correo existente', async ({ page }) => {
  await page.goto('http://localhost/register.php');
  await page.waitForSelector('form');

  await page.fill('#username', 'Cliente Existente');
  await page.fill('#email', 'alonso@gmail.com');
  await page.fill('#password', 'alonso');

  await Promise.all([
    page.waitForLoadState('domcontentloaded'),
    page.getByRole('button', { name: 'Completar registro' }).click(),
  ]);

  await expect(
    page.getByText('Este correo ya está registrado', { exact: false })
  ).toBeVisible();
});
