import { test, expect } from '@playwright/test';
import { loginAsAdmin } from '../helpers/auth';

test('TC-B04 - Modificar nombre de menú existente (Milanesa)', async ({ page }) => {
  await loginAsAdmin(page);

  // Ir a la lista de menús para elegir cuál editar
  await page.goto('http://localhost/admin/modificar_menu.php');
  await page.waitForSelector('.card');

  // Seleccionar la tarjeta del menú "Milanesa"
  const card = page.locator('.card-title', { hasText: 'Milanesa' }).first();
  const parentCard = card.locator('xpath=ancestor::div[contains(@class,"card")]');

  // Click en “Editar”
  const linkModificar = parentCard.getByRole('link', { name: 'Editar' });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    linkModificar.click()
  ]);

  // Ya estamos en modificar_menu.php?id=XX
  await page.waitForSelector('form');

  // Generar nombre nuevo único
  const nuevoNombre = `Milanesa Editada ${Date.now()}`;

  await page.fill('#nombre', nuevoNombre);

  // Click en guardar cambios
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.getByRole('button', { name: /guardar cambios/i }).click()
  ]);

  // Redirige a /menu.php
  await expect(page).toHaveURL(/menu\.php/);

  // Validar que el nuevo nombre aparece en menu.php
  await expect(
    page.locator('h1, h2, h3, h4, h5, .card-title', { hasText: nuevoNombre })
  ).toBeVisible();
});
