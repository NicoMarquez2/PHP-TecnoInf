import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D06 - Aumentar cantidad de un ítem en el carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  // --- PASO 1: Tomar un plato ---
  const firstCard = page.locator('.card').first();
  await expect(firstCard).toBeVisible();

  const nombreMenu = await firstCard.locator('.card-title').innerText();
  console.log("🟦 Aumentando cantidad en carrito para:", nombreMenu);

  // --- PASO 2: Agregarlo al carrito ---
  const btnAgregar = firstCard.getByRole('button', { name: /agregar al carrito/i });
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnAgregar.click()
  ]);

  // --- PASO 3: Ir al carrito ---
  await page.goto('http://localhost/cliente/carrito.php');

  // Buscar la fila del ítem en la tabla
  const fila = page.locator(`tr:has(td:has-text("${nombreMenu}"))`);
  await expect(fila).toBeVisible();

  // Cantidad inicial (columna 3 → índice 2)
  const cantidadInicial = parseInt(
    await fila.locator('td').nth(2).innerText()
  );

  // --- PASO 4: Presionar botón ➕ ---
  const btnMas = fila.getByRole('button', { name: '➕' });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnMas.click()
  ]);

  // --- PASO 5: Leer cantidad de nuevo ---
  const filaDespues = page.locator(`tr:has(td:has-text("${nombreMenu}"))`);
  const cantidadFinal = parseInt(
    await filaDespues.locator('td').nth(2).innerText()
  );

  // Validar que aumentó en 1
  expect(cantidadFinal).toBe(cantidadInicial + 1);
});
