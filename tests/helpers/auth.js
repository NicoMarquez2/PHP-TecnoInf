import { expect } from '@playwright/test';

const BASE_URL = 'http://localhost';

export async function loginAsAdmin(page) {
  await page.goto(BASE_URL + '/login.php');
  await page.waitForSelector('form');
  await page.fill('#mail', 'nicolasmarquez005@gmail.com');
  await page.fill('#password', 'admin');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.getByRole('button', { name: 'Login' }).click(),
  ]);
}

export async function loginAsClient(page) {
  await page.goto(BASE_URL + '/login.php');
  await page.waitForSelector('form');
  await page.fill('#mail', 'alonso@gmail.com');
  await page.fill('#password', 'alonso');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.getByRole('button', { name: 'Login' }).click(),
  ]);
}

export async function registerUser(page, { nombre, email, password }) {
  await page.goto(BASE_URL + '/register.php');
  await page.waitForSelector('form');
  await page.fill('#nombre', nombre);
  await page.fill('#mail', email);
  await page.fill('#password', password);
  await Promise.all([
    page.waitForLoadState('domcontentloaded'),
    page.getByRole('button', { name: 'Registrarse' }).click(),
  ]);
}

export async function registerRandomUser(page) {
  const ts = Date.now();
  const email = `tester${ts}@mail.com`;
  await registerUser(page, {
    nombre: `Tester ${ts}`,
    email,
    password: '123456'
  });
  return email;
}
