import { test, expect } from '@playwright/test';
import { loginAsClient, loginAsAdmin } from '../helpers/auth';
import path from 'path';

test('TC-D01 - Agregar menú a favoritos', async ({ page }) => {
  // --- LOGIN COMO CLIENTE ---
  await loginAsClient(page);
  await page.goto('http://localhost/index.php');

  // --- BUSCAR UN MENÚ QUE NO ESTÉ EN FAVORITOS ---
  const cards = page.locator('.card');
  const count = await cards.count();

  let selectedCard = null;
  let selectedName = null;

  for (let i = 0; i < count; i++) {
    const card = cards.nth(i);
    const btnAgregar = card.getByRole('button', { name: /agregar a favoritos/i });

    if (await btnAgregar.count() > 0) {
      selectedCard = card;
      selectedName = await card.locator('.card-title').innerText();
      break;
    }
  }

  // --- SI NO HAY MENÚS DISPONIBLES PARA AGREGAR, CREAMOS UNO ---
  if (!selectedCard) {
    console.log('⚠ No hay menús disponibles para agregar. Creando uno temporal...');

    // Crear menú temporal como admin
    await loginAsAdmin(page);
    await page.goto('http://localhost/admin/agregar_menu.php');

    const nombreTmp = 'MenuTempFav_' + Date.now();
    const testImagePath = path.join(__dirname, '../assets/pizza.jpg');

    await page.fill('#nombre', nombreTmp);
    await page.fill('#descripcion', 'Temporal para favoritos');
    await page.fill('#precio', '300');
    await page.setInputFiles('#foto', testImagePath);

    await Promise.all([
      page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
      page.getByRole('button', { name: /guardar/i }).click()
    ]);

    // Volver como cliente
    await loginAsClient(page);
    await page.goto('http://localhost/index.php');

    // Buscar el menú recién creado
    selectedCard = page.locator('.card').filter({ hasText: nombreTmp }).first();
    selectedName = nombreTmp;
  }

  // --- AHORA AGREGAMOS A FAVORITOS ---
  const btnAgregarFav = selectedCard.getByRole('button', { name: /agregar a favoritos/i });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnAgregarFav.click()
  ]);

  // --- IR A FAVORITOS ---
  await page.goto('http://localhost/cliente/favoritos.php');

  // --- VALIDAR QUE EL MENÚ APARECE ---
  await expect(
    page.locator('.card-title', { hasText: selectedName })
  ).toBeVisible();
});
