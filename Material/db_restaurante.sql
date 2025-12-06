-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07-Jun-2025 às 12:36
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_restaurante`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_cardapio`
--

CREATE TABLE `tb_cardapio` (
  `id_item` int(10) UNSIGNED NOT NULL,
  `nome_item` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(6,2) NOT NULL DEFAULT 0.00,
  `categoria` varchar(50) NOT NULL,
  `imagem` text DEFAULT NULL,
  `visivel` enum('0','1') DEFAULT '1',
  `prato_dia` enum('0','1') DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Informações dos Items do cárdapio do restaurante';

--
-- Extraindo dados da tabela `tb_cardapio`
--

INSERT INTO `tb_cardapio` (`id_item`, `nome_item`, `descricao`, `preco`, `categoria`, `imagem`, `visivel`, `prato_dia`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Bitoque', 'pata, carne, peixe', 2500.00, 'Típocos', '250607071352_menu_lobster-roll.jpg', '1', '0', '2025-06-07 06:13:52', '2025-06-07 06:14:15', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_mesa`
--

CREATE TABLE `tb_mesa` (
  `id_mesa` int(10) UNSIGNED NOT NULL,
  `numero_mesa` tinyint(3) UNSIGNED DEFAULT NULL,
  `capacidade` tinyint(3) UNSIGNED DEFAULT NULL,
  `status` enum('disponivel','reservado') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Para guardar as informações das mesas do restaurante';

--
-- Extraindo dados da tabela `tb_mesa`
--

INSERT INTO `tb_mesa` (`id_mesa`, `numero_mesa`, `capacidade`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 6, 'disponivel', '2025-06-07 06:09:59', '2025-06-07 06:09:59', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_mesa_has_tb_reserva`
--

CREATE TABLE `tb_mesa_has_tb_reserva` (
  `tb_mesa_id_mesa` int(10) UNSIGNED NOT NULL,
  `tb_reserva_id_reversa` int(10) UNSIGNED NOT NULL,
  `data` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_mesa_has_tb_reserva`
--

INSERT INTO `tb_mesa_has_tb_reserva` (`tb_mesa_id_mesa`, `tb_reserva_id_reversa`, `data`, `hora_inicio`, `hora_fim`) VALUES
(1, 1, '2025-06-10', '12:00:00', '13:30:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pedido`
--

CREATE TABLE `tb_pedido` (
  `id_pedido` int(10) UNSIGNED NOT NULL,
  `data_pedido` datetime DEFAULT NULL,
  `codigo_pedido` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `morada` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `modalidade` enum('no local','em casa') DEFAULT NULL,
  `numero_mesa` tinyint(4) DEFAULT NULL,
  `tb_usuario_id_usuario` int(10) UNSIGNED NOT NULL,
  `comprovativo` varchar(500) DEFAULT NULL,
  `data_limite` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_pedido`
--

INSERT INTO `tb_pedido` (`id_pedido`, `data_pedido`, `codigo_pedido`, `status`, `morada`, `email`, `telefone`, `modalidade`, `numero_mesa`, `tb_usuario_id_usuario`, `comprovativo`, `data_limite`, `created_at`, `updated_at`) VALUES
(1, '2025-06-07 10:04:18', 'OB876511', 'AGUARDANDO PAGAMENTO', 'Cuanza-Norte, Cambambe, Zona 4', 'jorgemanuel@gmail.com', '952358855', 'em casa', NULL, 2, NULL, '2025-06-07 13:04:18', '2025-06-07 11:04:18', '2025-06-07 10:04:18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pedido_has_tb_cardapio`
--

CREATE TABLE `tb_pedido_has_tb_cardapio` (
  `tb_pedido_id_pedido` int(10) UNSIGNED NOT NULL,
  `tb_cardapio_id_item` int(10) UNSIGNED NOT NULL,
  `designacao_item` varchar(45) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `preco` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_pedido_has_tb_cardapio`
--

INSERT INTO `tb_pedido_has_tb_cardapio` (`tb_pedido_id_pedido`, `tb_cardapio_id_item`, `designacao_item`, `quantidade`, `preco`) VALUES
(1, 1, 'Bitoque', 1, 2500.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pessoa`
--

CREATE TABLE `tb_pessoa` (
  `id_pessoa` int(11) NOT NULL,
  `nome_completo` varchar(45) NOT NULL,
  `data_nasc` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telefone` varchar(45) DEFAULT NULL,
  `endereco` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_pessoa`
--

INSERT INTO `tb_pessoa` (`id_pessoa`, `nome_completo`, `data_nasc`, `email`, `telefone`, `endereco`) VALUES
(1, 'Jobil Manuel', NULL, 'admin@admin.com', '921403954', 'Zona 8, Madeira'),
(2, 'Jorge Manuel', NULL, 'jorgemanuel@gmail.com', '952358855', 'Cuanza-Norte, Cambambe, Zona 4'),
(3, 'Jobil Soft Developer', NULL, 'jobilsoft@gmail.com', '958747858', 'Camama, Luanda');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_reserva`
--

CREATE TABLE `tb_reserva` (
  `id_reversa` int(10) UNSIGNED NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `presenca` varchar(250) DEFAULT NULL,
  `n_pessoa` int(11) DEFAULT NULL,
  `mensagem` varchar(150) DEFAULT NULL,
  `tipo_refeicao` varchar(50) DEFAULT NULL,
  `comprovativo` varchar(500) DEFAULT NULL,
  `data_limite_pagamento` datetime NOT NULL,
  `tb_pessoa_id_pessoa` int(11) NOT NULL,
  `tb_usuario_id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela para armazenar as reservas de mesas. ';

--
-- Extraindo dados da tabela `tb_reserva`
--

INSERT INTO `tb_reserva` (`id_reversa`, `status`, `presenca`, `n_pessoa`, `mensagem`, `tipo_refeicao`, `comprovativo`, `data_limite_pagamento`, `tb_pessoa_id_pessoa`, `tb_usuario_id_usuario`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EXPIRADA', 'nao', 4, 'Vou jantar com minha familia', 'Almoço', NULL, '2025-06-07 09:37:54', 2, 2, '2025-06-07 07:37:54', '2025-06-07 09:39:40', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tipo_usuario`
--

CREATE TABLE `tb_tipo_usuario` (
  `id_tipouser` int(11) NOT NULL,
  `nivel_acesso` enum('cliente','admin','recepcionista','atendente') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_tipo_usuario`
--

INSERT INTO `tb_tipo_usuario` (`id_tipouser`, `nivel_acesso`) VALUES
(1, 'admin'),
(2, 'cliente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nome_usuario` varchar(45) DEFAULT NULL,
  `senha` varchar(250) DEFAULT NULL,
  `purl` varchar(50) DEFAULT NULL,
  `ativo` tinyint(1) UNSIGNED ZEROFILL DEFAULT NULL,
  `tb_pessoa_id_pessoa` int(11) DEFAULT NULL,
  `tb_tipo_usuario_id_tipouser` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='informações dos usuários do sistema (cliente, adminstratores)';

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`id_usuario`, `nome_usuario`, `senha`, `purl`, `ativo`, `tb_pessoa_id_pessoa`, `tb_tipo_usuario_id_tipouser`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Jobil Admin', '$2y$10$gtzkAkdf1sNXMPihrVi7E.fruvlu9rEllLpmmjztAcGXbjb9i.Zyy', NULL, 1, 1, 1, '2025-06-07 05:36:09', '2025-06-07 05:43:28', NULL),
(2, 'JOrge Black', '$2y$10$gtzkAkdf1sNXMPihrVi7E.fruvlu9rEllLpmmjztAcGXbjb9i.Zyy', NULL, 1, 2, 2, '2025-06-07 05:46:10', '2025-06-07 06:34:19', NULL),
(3, 'Soft Job', '$2y$10$LD7GLFyRyVsnkOTEBw76xuVYl.8jprlfUW56/BUBjLNxU8P3tu9oC', NULL, 1, 3, 1, '2025-06-07 09:03:16', '2025-06-07 09:03:16', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_cardapio`
--
ALTER TABLE `tb_cardapio`
  ADD PRIMARY KEY (`id_item`);

--
-- Índices para tabela `tb_mesa`
--
ALTER TABLE `tb_mesa`
  ADD PRIMARY KEY (`id_mesa`);

--
-- Índices para tabela `tb_mesa_has_tb_reserva`
--
ALTER TABLE `tb_mesa_has_tb_reserva`
  ADD PRIMARY KEY (`tb_mesa_id_mesa`,`tb_reserva_id_reversa`),
  ADD KEY `fk_tb_mesa_has_tb_reserva_tb_reserva1_idx` (`tb_reserva_id_reversa`),
  ADD KEY `fk_tb_mesa_has_tb_reserva_tb_mesa1_idx` (`tb_mesa_id_mesa`);

--
-- Índices para tabela `tb_pedido`
--
ALTER TABLE `tb_pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_tb_pedido_tb_usuario1_idx` (`tb_usuario_id_usuario`);

--
-- Índices para tabela `tb_pedido_has_tb_cardapio`
--
ALTER TABLE `tb_pedido_has_tb_cardapio`
  ADD PRIMARY KEY (`tb_pedido_id_pedido`,`tb_cardapio_id_item`),
  ADD KEY `fk_tb_pedido_has_tb_cardapio_tb_cardapio1_idx` (`tb_cardapio_id_item`),
  ADD KEY `fk_tb_pedido_has_tb_cardapio_tb_pedido1_idx` (`tb_pedido_id_pedido`);

--
-- Índices para tabela `tb_pessoa`
--
ALTER TABLE `tb_pessoa`
  ADD PRIMARY KEY (`id_pessoa`);

--
-- Índices para tabela `tb_reserva`
--
ALTER TABLE `tb_reserva`
  ADD PRIMARY KEY (`id_reversa`),
  ADD KEY `fk_tb_reserva_tb_pessoa1_idx` (`tb_pessoa_id_pessoa`),
  ADD KEY `fk_tb_reserva_tb_usuario1_idx` (`tb_usuario_id_usuario`);

--
-- Índices para tabela `tb_tipo_usuario`
--
ALTER TABLE `tb_tipo_usuario`
  ADD PRIMARY KEY (`id_tipouser`);

--
-- Índices para tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id_usuario`) USING BTREE,
  ADD KEY `fk_tb_usuario_tb_pessoa_idx` (`tb_pessoa_id_pessoa`),
  ADD KEY `fk_tb_usuario_tb_tipo_usuario1_idx` (`tb_tipo_usuario_id_tipouser`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_cardapio`
--
ALTER TABLE `tb_cardapio`
  MODIFY `id_item` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_mesa`
--
ALTER TABLE `tb_mesa`
  MODIFY `id_mesa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_pedido`
--
ALTER TABLE `tb_pedido`
  MODIFY `id_pedido` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_pessoa`
--
ALTER TABLE `tb_pessoa`
  MODIFY `id_pessoa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_reserva`
--
ALTER TABLE `tb_reserva`
  MODIFY `id_reversa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_tipo_usuario`
--
ALTER TABLE `tb_tipo_usuario`
  MODIFY `id_tipouser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_mesa_has_tb_reserva`
--
ALTER TABLE `tb_mesa_has_tb_reserva`
  ADD CONSTRAINT `fk_tb_mesa_has_tb_reserva_tb_mesa1` FOREIGN KEY (`tb_mesa_id_mesa`) REFERENCES `tb_mesa` (`id_mesa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_mesa_has_tb_reserva_tb_reserva1` FOREIGN KEY (`tb_reserva_id_reversa`) REFERENCES `tb_reserva` (`id_reversa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_pedido`
--
ALTER TABLE `tb_pedido`
  ADD CONSTRAINT `fk_tb_pedido_tb_usuario1` FOREIGN KEY (`tb_usuario_id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_pedido_has_tb_cardapio`
--
ALTER TABLE `tb_pedido_has_tb_cardapio`
  ADD CONSTRAINT `fk_tb_pedido_has_tb_cardapio_tb_cardapio1` FOREIGN KEY (`tb_cardapio_id_item`) REFERENCES `tb_cardapio` (`id_item`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_pedido_has_tb_cardapio_tb_pedido1` FOREIGN KEY (`tb_pedido_id_pedido`) REFERENCES `tb_pedido` (`id_pedido`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_reserva`
--
ALTER TABLE `tb_reserva`
  ADD CONSTRAINT `fk_tb_reserva_tb_pessoa1` FOREIGN KEY (`tb_pessoa_id_pessoa`) REFERENCES `tb_pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_reserva_tb_usuario1` FOREIGN KEY (`tb_usuario_id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `fk_tb_usuario_tb_pessoa` FOREIGN KEY (`tb_pessoa_id_pessoa`) REFERENCES `tb_pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_usuario_tb_tipo_usuario1` FOREIGN KEY (`tb_tipo_usuario_id_tipouser`) REFERENCES `tb_tipo_usuario` (`id_tipouser`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
