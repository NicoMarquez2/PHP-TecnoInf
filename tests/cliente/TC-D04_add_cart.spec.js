import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D04 - Agregar menú al carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  const btnAgregarCarrito = page.getByRole('link', { name: 'Agregar al carrito' }).first();
  await btnAgregarCarrito.click();

  await page.goto('http://localhost/carrito.php');
  await expect(page.getByText('Carrito')).toBeVisible();
});
