-- phpMyAdmin SQL Dump (ajustado)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
... /* mesmas diretivas de charset do seu dump */

-- 1) Criar e selecionar o banco
CREATE DATABASE IF NOT EXISTS bd_nutri_plus
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE nutri_plus;

-- 2) TABELAS (users antes, para permitir FK)
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,             -- tornou NOT NULL
  `nome` varchar(160) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `horario` varchar(160) NOT NULL,
  `tipo` enum('Doa','Recebe','Doa/Recebe') NOT NULL,
  `alimentos` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `idx_items_user_id` (`user_id`)     -- índice para FK/consultas
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3) ÍNDICES/CONSTRAINTS
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD CONSTRAINT `fk_items_user`
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- 4) AUTO_INCREMENT
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- 5) DADOS - primeiro users, depois items (respeita a FK)
INSERT INTO `users` (`id`, `nome`, `email`, `senha_hash`, `role`, `created_at`) VALUES
(1, 'Administrador', 'admin@mail.com', '$2y$10$Hf7fIKPPqQt3b.6VQVj2X.PieQOGn/jB93mtR2eA5kJIz4/IIpkTC', 'admin', '2025-09-16 23:34:01'),
(5, 'Ian Cunha', 'ian@mail.com', '$2y$10$26GBNRUNJfarK03aC6elwOkV6.oxHBkguE/D9incXijnUl1JUhA..', 'user', '2025-09-17 00:57:10'),
(7, 'teste@mail.com', 'teste@mail.com', '$2y$10$qdch.MVSmwr049BnVq2KMeOCmP3dbcCNw.uFzhQJAxbUdQbDInVwO', 'user', '2025-09-20 02:35:26');

INSERT INTO `items` (`id`, `user_id`, `nome`, `endereco`, `horario`, `tipo`, `alimentos`, `created_at`) VALUES
(4, 1, 'Associação Solidária da Vila', 'Rua das Flores, 123 – Bairro Centro, Uberlândia/MG', 'Seg–Sex 8h–17h', 'Doa', 'Arroz, feijão, macarrão, óleo, leite em pó, enlatados', '2025-09-16 23:39:20'),
(5, 1, 'Igreja Comunidade da Paz', 'Av. Rondon Pacheco, 2500 – Bairro Santa Mônica, Uberlândia/MG', 'Ter–Dom 9h–18h', 'Recebe', 'Cestas básicas, biscoitos, achocolatado, açúcar, café', '2025-09-16 23:41:25');

-- 6) Ajuste de AUTO_INCREMENT após inserts (opcional)
ALTER TABLE `items` AUTO_INCREMENT=12;
ALTER TABLE `users` AUTO_INCREMENT=8;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
... /* mesmas diretivas de restauração */
