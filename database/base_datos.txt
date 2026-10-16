CREATE DATABASE IF NOT EXISTS triangulo_rectangulo_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE triangulo_rectangulo_db;

CREATE TABLE IF NOT EXISTS usuario (
  id_usuario INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL DEFAULT '',
  correo VARCHAR(255) NOT NULL UNIQUE,
  clave VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS triangulo (
  id_triangulo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  detalle VARCHAR(500) NOT NULL DEFAULT '',
  cateto_a DOUBLE NOT NULL,
  cateto_b DOUBLE NOT NULL,
  hipotenusa DOUBLE NOT NULL,
  area DOUBLE NOT NULL,
  perimetro DOUBLE NOT NULL,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Acceso inicial local: admin@triangulo.com / 123
INSERT INTO usuario (nombre, correo, clave)
SELECT 'Administrador', 'admin@triangulo.com', '123'
WHERE NOT EXISTS (SELECT 1 FROM usuario WHERE correo = 'admin@triangulo.com');
