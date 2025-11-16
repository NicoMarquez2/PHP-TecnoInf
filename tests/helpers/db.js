import mysql from 'mysql2/promise';
import fs from 'fs';

export async function query(sql, params = []) {
  const connection = await mysql.createConnection({
    host: "taller-php-paplaboratorio1.g.aivencloud.com",
    port: 10513,
    user: "avnadmin",
    password: "AVNS_LzQfO5qeO4ng60vElzt",
    database: "defaultdb",
    charset: "utf8mb4",
    ssl: {
      rejectUnauthorized: true,
      ca: fs.readFileSync("ca.pem")
    }
  });

  const [rows] = await connection.execute(sql, params);
  await connection.end();
  return rows;
}
