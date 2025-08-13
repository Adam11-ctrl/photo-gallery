-- Buat database (jalankan sekali, atau buat manual)
-- CREATE DATABASE photo_gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE photo_gallery;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS photos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  category_id INT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NULL,
  filename VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_photos_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_photos_cat FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Buat user admin default (ganti password_hash sesuai output PHP password_hash)
-- Contoh: admin:admin123 (hash hasil dari PHP 8+)
-- UPDATE di bawah setelah Anda punya hash.
-- INSERT INTO users (name,email,password_hash,role,created_at) VALUES ('Admin','admin@example.com','<REPLACE_HASH>','admin', NOW());
