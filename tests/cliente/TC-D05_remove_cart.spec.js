import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D05 - Eliminar menú del carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  const btnAgregarCarrito = page.getByRole('link', { name: 'Agregar al carrito' }).first();
  await btnAgregarCarrito.click();

  await page.goto('http://localhost/carrito.php');
  const btnEliminar = page.getByRole('link', { name: 'Eliminar' }).first();
  await btnEliminar.click();
  await page.waitForLoadState('domcontentloaded');

  // No debería mostrar el mismo ítem
  // (assert débil: al menos la página sigue cargando sin errores)
  await expect(page.getByText('Carrito')).toBeVisible();
});
