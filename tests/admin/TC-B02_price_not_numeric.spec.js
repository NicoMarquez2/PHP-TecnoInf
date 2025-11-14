import { test, expect } from '@playwright/test';
import { loginAsAdmin } from '../helpers/auth';
import path from 'path';

test('TC-B02 - Ingresar precio no numérico al crear menú', async ({ page }) => {
  await loginAsAdmin(page);

  await page.goto('http://localhost/admin/agregar_menu.php');
  await page.waitForSelector('form');

  const nombre = `Menu Precio Invalido ${Date.now()}`;

  await page.fill('#nombre', nombre);
  await page.fill('#descripcion', 'Debe fallar por precio no numérico');

  await page.locator('#precio').evaluate(el => el.value = "abc");

  const testImagePath = path.join(__dirname, '../assets/pizza.jpg');
  await page.setInputFiles('#foto', testImagePath);

  await page.getByRole('button', { name: /guardar menú/i }).click();

  await page.waitForTimeout(500);

  await page.goto('http://localhost/index.php');
  await expect(
    page.getByText(nombre, { exact: false })
  ).toHaveCount(0);
});
