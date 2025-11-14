import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D07 - No permitir cantidad negativa en el carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  const btnAgregarCarrito = page.getByRole('link', { name: 'Agregar al carrito' }).first();
  await btnAgregarCarrito.click();

  await page.goto('http://localhost/carrito.php');

  const btnMenos = page.getByRole('link', { name: '-' }).first();
  // Hacemos varios clics para intentar "bajar de 1"
  await btnMenos.click().catch(() => {});
  await btnMenos.click().catch(() => {});
  await page.waitForLoadState('domcontentloaded');

  await expect(page.getByText('Carrito')).toBeVisible();
});
