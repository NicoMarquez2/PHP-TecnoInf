import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

test('TC-D14 - Datos del carrito persisten al navegar', async ({ page }) => {
  await loginAsClient(page);

  await page.goto('http://localhost/index.php');
  await page.waitForSelector('.card');

  const firstCard = page.locator('.card').first();

  const nombreMenu = await firstCard.locator('.card-title').innerText();

  const btnAgregar = firstCard.getByRole('button', { name: /agregar al carrito/i });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnAgregar.click()
  ]);

  await page.goto('http://localhost/index.php');
  await page.goto('http://localhost/cliente/carrito.php');

  await expect(
    page.getByRole('heading', { name: /mi carrito/i })
  ).toBeVisible();

  await expect(
    page.locator('table tbody tr').filter({ hasText: nombreMenu })
  ).toBeVisible();
});
