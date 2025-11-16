import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';
import { query } from '../helpers/db';

test('TC-D10 - Realizar una compra completa y verificar en BD', async ({ page }) => {
  const userId = 3;

  await loginAsClient(page);
  await page.goto('http://localhost/index.php');
  await page.waitForSelector('.card');

  const firstCard = page.locator('.card').first();
  const nombreMenu = await firstCard.locator('.card-title').innerText();

  const cardText = await firstCard.innerText();
  const match = cardText.match(/\$([\d\.,]+)/);
  const precioMenu = parseFloat(match[1].replace(',', '.'));

  const btnAgregar = firstCard.getByRole('button', { name: /agregar al carrito/i });
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnAgregar.click()
  ]);

  await page.goto('http://localhost/cliente/carrito.php');

  await expect(
    page.locator('table tbody tr').filter({ hasText: nombreMenu })
  ).toBeVisible();

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.getByRole('button', { name: /confirmar compra/i }).click()
  ]);

  const ordenes = await query(
    'SELECT id, total FROM ordenes WHERE cliente_id = ? ORDER BY id DESC LIMIT 1',
    [userId]
  );

  expect(ordenes.length).toBe(1);

  const ordenId = ordenes[0].id;
  const totalBD = parseFloat(ordenes[0].total);

  expect(totalBD).toBeCloseTo(precioMenu, 2);

  const detalles = await query(
    'SELECT cantidad, precio_unitario FROM orden_detalle WHERE orden_id = ?',
    [ordenId]
  );

  expect(detalles.length).toBe(1);

  const cantidad = detalles[0].cantidad;
  const precioUnitario = parseFloat(detalles[0].precio_unitario);

  expect(cantidad).toBe(1);
  expect(precioUnitario).toBeCloseTo(precioMenu, 2);

  await page.goto('http://localhost/cliente/carrito.php');

  await expect(
    page.getByText('Tu carrito está vacío')
  ).toBeVisible();
});
