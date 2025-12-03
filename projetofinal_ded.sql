-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/12/2025 às 22:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projetofinal_ded`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `jogadores`
--

CREATE TABLE `jogadores` (
  `id` int(11) NOT NULL,
  `jogador` varchar(100) NOT NULL,
  `personagem` varchar(100) NOT NULL,
  `numero` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `jogadores`
--

INSERT INTO `jogadores` (`id`, `jogador`, `personagem`, `numero`) VALUES
(1, 'Tallison', 'Apollo', '83 988885555'),
(2, 'Geovanna', 'Venus', '83 977774444');

-- --------------------------------------------------------

--
-- Estrutura para tabela `personagens`
--

CREATE TABLE `personagens` (
  `id` int(50) NOT NULL,
  `personagem` varchar(100) NOT NULL,
  `jogador` varchar(100) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `classe` varchar(50) NOT NULL,
  `subclasse` varchar(50) DEFAULT NULL,
  `forca` int(20) NOT NULL,
  `destreza` int(20) NOT NULL,
  `constituicao` int(20) NOT NULL,
  `inteligencia` int(20) NOT NULL,
  `sabedoria` int(20) NOT NULL,
  `carisma` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `personagens`
--

INSERT INTO `personagens` (`id`, `personagem`, `jogador`, `especie`, `classe`, `subclasse`, `forca`, `destreza`, `constituicao`, `inteligencia`, `sabedoria`, `carisma`) VALUES
(1, 'Apollo', 'Tallison', 'Elfo', 'Druida', 'Círculo da Terra', 8, 13, 14, 10, 15, 12),
(2, 'Venus', 'Geovanna', 'Humano', 'Guerreiro', 'Campeão', 15, 12, 14, 10, 13, 8);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `jogadores`
--
ALTER TABLE `jogadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jogador` (`jogador`);

--
-- Índices de tabela `personagens`
--
ALTER TABLE `personagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogador_nome` (`jogador`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `jogadores`
--
ALTER TABLE `jogadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `personagens`
--
ALTER TABLE `personagens`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `personagens`
--
ALTER TABLE `personagens`
  ADD CONSTRAINT `jogador_nome` FOREIGN KEY (`jogador`) REFERENCES `jogadores` (`jogador`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
