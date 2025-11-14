import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D01 - Agregar menú a favoritos', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  const btnAgregarFav = page.getByRole('link', { name: 'Agregar a favoritos' }).first();
  await btnAgregarFav.click();

  await page.goto('http://localhost/favoritos.php');
  await expect(page.getByRole('button', { name: 'Eliminar' })).toHaveCountGreaterThan(0);
});
