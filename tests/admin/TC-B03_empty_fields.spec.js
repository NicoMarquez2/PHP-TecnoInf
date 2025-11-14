import { test, expect } from '@playwright/test';
import { loginAsAdmin } from '../helpers/auth';

test('TC-B03 - Campos vacíos en alta de menú', async ({ page }) => {
  await loginAsAdmin(page);
  await page.goto('http://localhost/admin/agregar_menu.php');
  await page.waitForSelector('form');

  const nombre = `Menu Vacio ${Date.now()}`;
  await page.getByRole('button', { name: /guardar menú/i }).click();

  await page.waitForTimeout(400);
  await expect(page).toHaveURL(/agregar_menu\.php/);

  // Confirmar que el menú NO fue creado
  await page.goto('http://localhost/index.php');

  await expect(
    page.getByText(nombre, { exact: false })
  ).toHaveCount(0);
});

