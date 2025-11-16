import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';
import { query } from '../helpers/db';

test('TC-D09c - No debe crearse una orden si el carrito está vacío', async ({ page }) => {
  await loginAsClient(page);

  // --- Cantidad antes ---
  const antes = await query('SELECT COUNT(*) AS total FROM ordenes');
  const totalAntes = antes[0].total;

  // --- Forzar POST confirmar ---
  await page.goto('http://localhost/cliente/carrito.php');

  await page.evaluate(() => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/cliente/carrito.php';

    const input = document.createElement('input');
    input.name = 'accion';
    input.value = 'confirmar';
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
  });

  await page.waitForLoadState('domcontentloaded');

  // --- Cantidad después ---
  const despues = await query('SELECT COUNT(*) AS total FROM ordenes');
  const totalDespues = despues[0].total;

  // --- Validación ---
  expect(totalDespues).toBe(totalAntes);

  // UI debería seguir mostrando carrito vacío
  await expect(page.getByText('Tu carrito está vacío')).toBeVisible();
});
