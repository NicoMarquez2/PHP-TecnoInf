import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D04 - Agregar menú al carrito', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  // --- PASO 1: TOMAR PRIMER MENÚ DISPONIBLE ---
  const firstCard = page.locator('.card').first();
  await expect(firstCard).toBeVisible();

  // Obtener el nombre del menú
  const nombreMenu = await firstCard.locator('.card-title').innerText();
  console.log("🟦 Agregando al carrito:", nombreMenu);

  // --- PASO 2: HACER CLICK EN “Agregar al carrito” ---
  const btnAgregar = firstCard.getByRole('button', { name: /agregar al carrito/i });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnAgregar.click()
  ]);

  // --- PASO 3: IR AL CARRITO ---
  await page.goto('http://localhost/cliente/carrito.php');

  // --- PASO 4: VALIDAR QUE EL MENÚ ESTÁ EN LA TABLA ---
  await expect(
    page.locator('table td', { hasText: nombreMenu })
  ).toBeVisible();
});
