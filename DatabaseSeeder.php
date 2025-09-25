<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

try {
  $pdo = Database::getConnection();

  $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `password` varchar(255) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `users_unique_email` (`mail`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

  $pdo->exec("
        CREATE TABLE IF NOT EXISTS `customers` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `cpf` varchar(11) NOT NULL,
            `rg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `birth_date` date NOT NULL,
            `phone` varchar(11) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `customers_unique` (`cpf`),
            UNIQUE KEY `customers_unique_1` (`rg`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

  $pdo->exec("
        CREATE TABLE IF NOT EXISTS `addresses` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `customer_id` int unsigned NOT NULL,
            `zip` varchar(20) NOT NULL,
            `city` varchar(100) NOT NULL,
            `state` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `country` varchar(100) NOT NULL,
            `street` varchar(255) NOT NULL,
            `neighborhood` varchar(100) NOT NULL,
            `number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `addresses_fk_customer` (`customer_id`),
            CONSTRAINT `addresses_fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ");

  $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE mail = ?");
  $stmt->execute(['admin@admin.com']);
  if ($stmt->fetchColumn() == 0) {
    $pdo->exec("
            INSERT INTO users (name, mail, password, created_at, updated_at) VALUES
            ('admin', 'admin@admin.com', '\$2a\$12\$PzIpoK9m9ixoUxUd2/fQpuerGN1le9P9DJcr.YZpiGUaRU50WNcsu', NOW(), NOW())
        ");
  }

  echo "Banco e tabelas criados com sucesso.\n";
} catch (Exception $e) {
  echo "Erro: " . $e->getMessage() . "\n";
  exit(1);
}
