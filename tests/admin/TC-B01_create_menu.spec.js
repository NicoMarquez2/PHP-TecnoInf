import { test, expect } from '@playwright/test';
import { loginAsAdmin } from '../helpers/auth';
import path from 'path';

test('TC-B01 - Agregar nuevo menú válido', async ({ page }) => {
  await loginAsAdmin(page);
  await page.goto('http://localhost/admin/agregar_menu.php');
  await page.waitForSelector('form');

  const nombre = `Menu Test ${Date.now()}`;

  await page.fill('#nombre', nombre);
  await page.fill('#descripcion', 'Menú de prueba creado automáticamente');
  await page.fill('#precio', '500');

  const testImagePath = path.join(__dirname, '../assets/pizza.jpg');
  await page.setInputFiles('#foto', testImagePath);

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.getByRole('button', { name: /guardar menú/i }).click(),
  ]);

  await expect(page).toHaveURL(/menu\.php$/);

  // Validar que el menú recién creado aparece en menu.php
  await expect(
    page.getByRole('heading', { name: nombre })
  ).toBeVisible();
});
