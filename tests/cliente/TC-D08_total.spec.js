import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

function extraerNumero(texto) {
  const match = texto.match(/(\d+[\.,]?\d*)/);
  return match ? parseFloat(match[1].replace(',', '.')) : NaN;
}

test('TC-D08 - Precio total de compra correcto (2 unidades del mismo ítem)', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  // Tomamos el primer card
  const card = page.locator('.card').first();
  const precioTexto = await card.getByText('$', { exact: false }).first().innerText();
  const precioUnitario = extraerNumero(precioTexto);

  const btnAgregarCarrito = card.getByRole('link', { name: 'Agregar al carrito' });
  await btnAgregarCarrito.click();
  await btnAgregarCarrito.click();

  await page.goto('http://localhost/carrito.php');

  const totalTexto = await page.getByText('Total', { exact: false }).first().innerText();
  const total = extraerNumero(totalTexto);

  expect(total).not.toBeNaN();
  expect(total).toBeCloseTo(precioUnitario * 2, 0);
});
