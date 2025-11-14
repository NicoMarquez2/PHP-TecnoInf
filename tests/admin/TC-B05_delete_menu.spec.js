import { test, expect } from '@playwright/test';
import { loginAsAdmin } from '../helpers/auth';
import path from 'path';

test('TC-B05 - Eliminar menú', async ({ page }) => {
  await loginAsAdmin(page);

  // crear menú temporal en agregar_menu.php
  await page.goto('http://localhost/admin/agregar_menu.php');
  await page.waitForSelector('form');

  const nombre = `Menu Eliminar ${Date.now()}`;

  await page.fill('#nombre', nombre);
  await page.fill('#descripcion', 'Menú temporal para prueba de eliminación');
  await page.fill('#precio', '450');

  const testImagePath = path.join(__dirname, '../assets/pizza.jpg');
  await page.setInputFiles('#foto', testImagePath);

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.getByRole('button', { name: /guardar menú/i }).click()
  ]);

  // ir a eliminar_menu.php y buscar el menú recién creado
  await page.goto('http://localhost/admin/eliminar_menu.php');
  await page.waitForSelector('.card');

  const card = page.locator('.card-title', { hasText: nombre }).first();
  const parentCard = card.locator('xpath=ancestor::div[contains(@class,"card")]');

  const linkBorrar = parentCard.getByRole('link', { name: 'Borrar' });

  page.once('dialog', dialog => dialog.accept());

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    linkBorrar.click()
  ]);

  await expect(
    page.getByText('Menú eliminado correctamente.')
  ).toBeVisible();

  // confirmar que ya no aparece en el listado
  await expect(
    page.locator('.card-title', { hasText: nombre })
  ).toHaveCount(0);
});
