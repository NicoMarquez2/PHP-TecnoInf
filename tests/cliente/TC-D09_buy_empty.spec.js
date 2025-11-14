import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D09 - Realizar compra con carrito vacío', async ({ page }) => {
  await loginAsClient(page);
  await page.goto('http://localhost/carrito.php');

  await page.getByRole('link', { name: 'Comprar' }).click();
  await page.waitForLoadState('domcontentloaded');

  await expect(
    page.getByText('El carrito está vacío', { exact: false })
  ).toBeVisible();
});
