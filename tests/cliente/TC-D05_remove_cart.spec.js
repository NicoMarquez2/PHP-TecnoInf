import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D05 - Eliminar menú del carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  // --- PASO 1: Tomar un plato ---
  const firstCard = page.locator('.card').first();
  await expect(firstCard).toBeVisible();

  const nombreMenu = await firstCard.locator('.card-title').innerText();
  console.log("🟦 Eliminando del carrito:", nombreMenu);

  // --- PASO 2: Agregarlo al carrito ---
  const btnAgregar = firstCard.getByRole('button', { name: /agregar al carrito/i });
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnAgregar.click()
  ]);

  // --- PASO 3: Ir al carrito ---
  await page.goto('http://localhost/cliente/carrito.php');

  // Confirmar que aparece antes de eliminar
  await expect(page.locator('td', { hasText: nombreMenu })).toBeVisible();

  // --- PASO 4: Eliminar ---
  const fila = page.locator(`tr:has(td:has-text("${nombreMenu}"))`);
  const btnEliminar = fila.getByRole('button', { name: '❌' });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnEliminar.click()
  ]);

  // --- PASO 5: Validar que desapareció ---
  await expect(page.locator('td', { hasText: nombreMenu })).toHaveCount(0);
});
