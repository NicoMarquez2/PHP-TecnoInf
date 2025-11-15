import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D02 - Eliminar menú de favoritos', async ({ page }) => {
  await loginAsClient(page);

  // --- PASO 1: IR A INDEX Y ASEGURAR QUE HAYA AL MENOS UN FAVORITO ---
  await page.goto('http://localhost/index.php');

  // Buscar cualquier plato que NO esté aún en favoritos
  const btnAgregar = page.getByRole('button', { name: /agregar a favoritos/i }).first();

  if (await btnAgregar.count() > 0) {
    await Promise.all([
      page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
      btnAgregar.click()
    ]);
  }

  // --- PASO 2: IR A FAVORITOS Y TOMAR EL NOMBRE DE UNO ---
  await page.goto('http://localhost/cliente/favoritos.php');

  const favoritoCards = page.locator('.card');
  const totalFav = await favoritoCards.count();

  expect(totalFav).toBeGreaterThan(0);

  const card = favoritoCards.nth(0);
  const nombreFavorito = await card.locator('.card-title').innerText();

  console.log('🟦 Eliminando favorito:', nombreFavorito);

  // --- PASO 3: ELIMINAR ESE FAVORITO ---
  const btnEliminar = card.getByRole('button', { name: /quitar de favoritos/i });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnEliminar.click()
  ]);

  // --- PASO 4: VALIDAR QUE YA NO APARECE ---
  await page.goto('http://localhost/cliente/favoritos.php');

  // No debe existir ninguna tarjeta con ese nombre
  await expect(
    page.locator('.card-title', { hasText: nombreFavorito })
  ).toHaveCount(0);
});
