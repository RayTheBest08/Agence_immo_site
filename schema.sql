CREATE DATABASE IF NOT EXISTS agence_immo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agence_immo;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  telephone VARCHAR(50),
  role ENUM('user','owner','admin') DEFAULT 'user',
  created_at DATETIME,
  updated_at DATETIME
) ENGINE=InnoDB;

CREATE TABLE categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE types_transaction (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE annonces (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  category_id INT NOT NULL,
  type_transaction_id INT NOT NULL,
  titre VARCHAR(255) NOT NULL,
  description TEXT,
  prix DECIMAL(12,2),
  ville VARCHAR(100),
  surface VARCHAR(50),
  pieces VARCHAR(50),
  kilometrage VARCHAR(50),
  statut_disponibilite VARCHAR(50) DEFAULT 'disponible',
  created_at DATETIME,
  updated_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (type_transaction_id) REFERENCES types_transaction(id)
) ENGINE=InnoDB;

CREATE TABLE annonce_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  annonce_id INT NOT NULL,
  filename VARCHAR(255) NOT NULL,
  ordre INT DEFAULT 1,
  FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
) ENGINE=InnoDB;
