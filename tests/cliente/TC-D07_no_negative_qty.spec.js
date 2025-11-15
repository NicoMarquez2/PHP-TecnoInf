import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D07 - No permitir cantidad negativa en el carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  // --- Paso 1: tomar el primer menú ---
  const firstCard = page.locator('.card').first();
  await expect(firstCard).toBeVisible();

  const nombreMenu = await firstCard.locator('.card-title').innerText();

  // --- Paso 2: agregar al carrito ---
  const btnAgregar = firstCard.getByRole('button', { name: /agregar al carrito/i });
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnAgregar.click()
  ]);

  // --- Paso 3: ir al carrito ---
  await page.goto('http://localhost/cliente/carrito.php');

  // Fila correspondiente al menú
  const fila = page.locator(`tr:has(td:has-text("${nombreMenu}"))`);
  await expect(fila).toBeVisible();

  // --- Paso 4: obtener cantidad inicial ---
  const cantidadInicial = parseInt(await fila.locator('td').nth(2).innerText());

  // Botón ➖
  const btnMenos = fila.getByRole('button', { name: '➖' });

  // --- Paso 5: presionar varias veces para intentar ir por debajo ---
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnMenos.click()
  ]);

  // Si cantidad era 1, luego de quitar debe desaparecer la fila
  const filaDespues = page.locator(`tr:has(td:has-text("${nombreMenu}"))`);

  if (cantidadInicial === 1) {
    // ✔️ No debe existir
    await expect(filaDespues).toHaveCount(0);
  } else {
    // ✔️ Si era mayor, debe reducir 1
    const cantidadFinal = parseInt(await filaDespues.locator('td').nth(2).innerText());
    expect(cantidadFinal).toBe(cantidadInicial - 1);
  }

  // ✔️ Verificamos que la página no se rompió
await expect(page.getByRole('heading', { name: /mi carrito/i })).toBeVisible();
});
