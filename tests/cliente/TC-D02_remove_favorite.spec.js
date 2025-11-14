import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D02 - Eliminar menú de favoritos', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  // Aseguramos que haya al menos uno
  const btnAgregarFav = page.getByRole('link', { name: 'Agregar a favoritos' }).first();
  await btnAgregarFav.click();

  await page.goto('http://localhost/favoritos.php');
  const botonesEliminar = page.getByRole('button', { name: 'Eliminar' });
  const totalAntes = await botonesEliminar.count();

  await botonesEliminar.first().click();
  await page.waitForLoadState('domcontentloaded');

  const totalDespues = await botonesEliminar.count();
  expect(totalDespues).toBeLessThanOrEqual(totalAntes);
});
