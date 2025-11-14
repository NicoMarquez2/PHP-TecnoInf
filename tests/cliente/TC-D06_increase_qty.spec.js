import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D06 - Aumentar cantidad de un ítem en el carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  const btnAgregarCarrito = page.getByRole('link', { name: 'Agregar al carrito' }).first();
  await btnAgregarCarrito.click();

  await page.goto('http://localhost/carrito.php');

  const btnMas = page.getByRole('link', { name: '+' }).first();
  await btnMas.click();
  await page.waitForLoadState('domcontentloaded');

  await expect(page.getByRole('link', { name: '+' })).toBeVisible();
});
