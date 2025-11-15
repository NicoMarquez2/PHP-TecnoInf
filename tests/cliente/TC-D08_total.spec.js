import { test, expect } from '@playwright/test';
import { loginAsClient } from '../helpers/auth';

function extraerNumero(texto) {
  const match = texto.match(/(\d+[\.,]?\d*)/);
  return match ? parseFloat(match[1].replace(',', '.')) : NaN;
}

test('TC-D08 - Total correcto al aumentar cantidad de un menú', async ({ page }) => {
  await loginAsClient(page);

  // Ir directo a la sección del menú
  await page.goto('http://localhost/index.php#menu');
  await page.waitForSelector('.card');

  // --- TOMAR PRIMER MENÚ ---
  const firstCard = page.locator('.card').first();

  // Nombre del plato
  const nombreMenu = await firstCard.getByRole('heading').innerText();

  // Precio desde el index
  const cardText = await firstCard.innerText();
  const match = cardText.match(/\$[\d\.,]+/);
  if (!match) throw new Error("No se encontró precio en el primer menú.");

  const precioUnitario = extraerNumero(match[0]);

  // Agregar al carrito
  const formAgregar = firstCard.locator('form').filter({ 
    has: page.locator('input[name="accion"][value="agregar"]') 
  });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    formAgregar.locator('button[type="submit"]').click()
  ]);

  // --- IR AL CARRITO ---
  await page.goto('http://localhost/cliente/carrito.php');

  // Buscar fila del plato
  const fila = page.locator('table tbody tr').filter({ hasText: nombreMenu });
  await expect(fila).toBeVisible();

  // Cantidad inicial
  const cantidadInicial = parseInt(await fila.locator('td').nth(2).innerText(), 10);

  // Click en ➕
  const btnMas = fila.getByRole('button', { name: '➕' });

  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    btnMas.click()
  ]);

  // Cantidad final
  const cantidadFinal = parseInt(await fila.locator('td').nth(2).innerText(), 10);

  // Calcular total esperado
  const totalEsperado = cantidadFinal * precioUnitario;

  // Extraer total mostrado en carrito
  const totalTexto = await page.locator('tr.fw-bold td').nth(1).innerText();
  const totalMostrado = extraerNumero(totalTexto);

  // Validación
  expect(totalMostrado).not.toBeNaN();
  expect(totalMostrado).toBeCloseTo(totalEsperado, 0);
});
