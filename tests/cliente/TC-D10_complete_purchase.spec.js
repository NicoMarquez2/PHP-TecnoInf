import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D10 - Realizar una compra completa', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  const btnAgregarCarrito = page.getByRole('link', { name: 'Agregar al carrito' }).first();
  await btnAgregarCarrito.click();

  await page.goto('http://localhost/carrito.php');
  await page.getByRole('link', { name: 'Comprar' }).click();

  await page.waitForLoadState('domcontentloaded');

  await expect(
    page.getByText('Compra realizada', { exact: false })
  ).toBeVisible();
});
