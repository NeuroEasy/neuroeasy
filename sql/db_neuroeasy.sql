-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 20-Fev-2016 às 04:47
-- Versão do servidor: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_neuroeasy`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `lca_controllers`
--

CREATE TABLE IF NOT EXISTS `lca_controllers` (
`id` int(11) NOT NULL,
  `descricao` varchar(80) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `lca_controllers`
--

INSERT INTO `lca_controllers` (`id`, `descricao`, `grupo_id`) VALUES
(81, 'usuarios', 2),
(80, 'downloads', 2),
(79, 'treinamentos', 2),
(78, 'pages', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `lca_controller_actions`
--

CREATE TABLE IF NOT EXISTS `lca_controller_actions` (
`id` int(11) NOT NULL,
  `descricao` varchar(80) DEFAULT NULL,
  `controller_id` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `lca_controller_actions`
--

INSERT INTO `lca_controller_actions` (`id`, `descricao`, `controller_id`) VALUES
(42, 'editar_perfil', 81),
(41, 'editar_conta', 81),
(40, 'codigo_fonte_feedforward', 80),
(39, 'pesos_bias', 80),
(38, 'download', 79),
(37, 'relatorio', 79),
(36, 'visualizar', 79),
(35, 'pesos', 79),
(34, 'excluir', 79),
(33, 'salvar', 79),
(32, 'executar', 79),
(31, 'mostrar_dados_importacao', 79),
(30, 'listar', 79),
(29, 'novo', 79),
(28, 'doc', 78);

-- --------------------------------------------------------

--
-- Estrutura da tabela `lca_controller_grupos`
--

CREATE TABLE IF NOT EXISTS `lca_controller_grupos` (
`id` int(11) NOT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `observacao` text
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `lca_controller_grupos`
--

INSERT INTO `lca_controller_grupos` (`id`, `descricao`, `slug`, `observacao`) VALUES
(1, 'Landing Pages', 'landing-pages', 'São as páginas geralmente visualizados para o público'),
(2, 'Sistema', 'sistema', 'São as páginas administrativas da aplicação');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lca_permissoes_rels`
--

CREATE TABLE IF NOT EXISTS `lca_permissoes_rels` (
`id` int(11) NOT NULL,
  `action_id` int(11) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `lca_permissoes_rels`
--

INSERT INTO `lca_permissoes_rels` (`id`, `action_id`, `grupo_id`) VALUES
(42, 42, 1),
(41, 41, 1),
(40, 40, 1),
(39, 39, 1),
(38, 38, 1),
(37, 37, 1),
(36, 36, 1),
(35, 35, 1),
(34, 34, 1),
(33, 33, 1),
(32, 32, 1),
(31, 31, 1),
(30, 30, 1),
(29, 29, 1),
(28, 28, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `lca_users`
--

CREATE TABLE IF NOT EXISTS `lca_users` (
`id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` char(64) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `grupo_id` int(11) DEFAULT NULL,
  `profile_id` int(11) NOT NULL,
  `confirmar_conta_email` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `lca_user_grupos`
--

CREATE TABLE IF NOT EXISTS `lca_user_grupos` (
`id` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `contexto` text
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `lca_user_grupos`
--

INSERT INTO `lca_user_grupos` (`id`, `descricao`, `contexto`) VALUES
(1, 'Administrador', 'Privilégio que poderá executar todo tipo de operação disponível no módulo administrativo. Não haverá limitação para esse tipo de usuário'),
(12, 'Membro', 'Privilégio de acessar o básico de um membro');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lca_user_perfis`
--

CREATE TABLE IF NOT EXISTS `lca_user_perfis` (
`id` int(11) NOT NULL,
  `primeiro_nome` varchar(100) DEFAULT NULL,
  `ultimo_nome` varchar(100) DEFAULT NULL,
  `email_contato` varchar(100) DEFAULT NULL,
  `telefone_contato` varchar(50) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `nn_activation_functions`
--

CREATE TABLE IF NOT EXISTS `nn_activation_functions` (
`id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `nn_activation_functions`
--

INSERT INTO `nn_activation_functions` (`id`, `name`, `slug`, `active`, `created`, `modified`, `deleted`) VALUES
(1, 'Sigmóide', 'sigmoide', 1, '2015-11-12 00:00:00', '2015-11-12 00:00:00', NULL),
(2, 'Tangente Hiperbólica', 'tangente-hiperbolica', 1, '2015-11-12 00:00:00', '2015-11-12 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `nn_learning_rules`
--

CREATE TABLE IF NOT EXISTS `nn_learning_rules` (
`id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `component` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `nn_learning_rules`
--

INSERT INTO `nn_learning_rules` (`id`, `name`, `slug`, `active`, `component`, `created`, `modified`, `deleted`) VALUES
(1, 'Backpropagation', 'backpropagation', 1, 'BackPropagationComponent', '2015-11-12 00:00:00', '2015-11-12 00:00:00', NULL),
(2, 'Backpropagation com momentum', 'backpropagation-com-momentum', 1, 'BackPropagationComponent', '2015-11-12 00:00:00', '2015-11-12 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `nn_types`
--

CREATE TABLE IF NOT EXISTS `nn_types` (
`id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `nn_types`
--

INSERT INTO `nn_types` (`id`, `name`, `slug`, `active`, `created`, `modified`, `deleted`) VALUES
(1, 'Perceptron Múltiplas Camadas (1 camada oculta)', 'perceptron-multiplas-camadas', 1, '2015-11-12 00:00:00', '2015-11-12 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `trainings`
--

CREATE TABLE IF NOT EXISTS `trainings` (
`id` int(11) NOT NULL,
  `token` char(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(200) NOT NULL,
  `description` text,
  `user_id` int(11) DEFAULT NULL,
  `nn_type_id` int(11) DEFAULT NULL,
  `nn_learning_rule_id` int(11) DEFAULT NULL,
  `nn_activation_function_id` int(11) DEFAULT NULL,
  `topology` varchar(200) DEFAULT NULL,
  `learning_rate` decimal(2,2) DEFAULT NULL,
  `momentum` decimal(2,2) DEFAULT NULL,
  `max_epochs` int(11) DEFAULT NULL,
  `max_error` float DEFAULT NULL,
  `tmse_result` float DEFAULT NULL COMMENT 'Total Mean Square Error',
  `created` datetime NOT NULL,
  `deleted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `training_results`
--

CREATE TABLE IF NOT EXISTS `training_results` (
`id` int(11) NOT NULL,
  `training_id` int(11) DEFAULT NULL,
  `name_output` varchar(200) DEFAULT NULL,
  `rquad` decimal(3,3) DEFAULT NULL COMMENT 'R ao quadrado dos dados de treinamento',
  `rquad_validation` decimal(3,3) DEFAULT NULL COMMENT 'R ao quadrado dos dados de validação',
  `rquada` decimal(3,3) DEFAULT NULL COMMENT 'R ao quadrado ajustado dos dados de treinamento',
  `rquada_validation` decimal(3,3) DEFAULT NULL COMMENT 'R ao quadrado ajustado dos dados de validação',
  `created` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lca_controllers`
--
ALTER TABLE `lca_controllers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lca_controller_actions`
--
ALTER TABLE `lca_controller_actions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lca_controller_grupos`
--
ALTER TABLE `lca_controller_grupos`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lca_permissoes_rels`
--
ALTER TABLE `lca_permissoes_rels`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lca_users`
--
ALTER TABLE `lca_users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lca_user_grupos`
--
ALTER TABLE `lca_user_grupos`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lca_user_perfis`
--
ALTER TABLE `lca_user_perfis`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nn_activation_functions`
--
ALTER TABLE `nn_activation_functions`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `nn_learning_rules`
--
ALTER TABLE `nn_learning_rules`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nn_types`
--
ALTER TABLE `nn_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `training_results`
--
ALTER TABLE `training_results`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lca_controllers`
--
ALTER TABLE `lca_controllers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT for table `lca_controller_actions`
--
ALTER TABLE `lca_controller_actions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `lca_controller_grupos`
--
ALTER TABLE `lca_controller_grupos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lca_permissoes_rels`
--
ALTER TABLE `lca_permissoes_rels`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `lca_users`
--
ALTER TABLE `lca_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `lca_user_grupos`
--
ALTER TABLE `lca_user_grupos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `lca_user_perfis`
--
ALTER TABLE `lca_user_perfis`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `nn_activation_functions`
--
ALTER TABLE `nn_activation_functions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `nn_learning_rules`
--
ALTER TABLE `nn_learning_rules`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `nn_types`
--
ALTER TABLE `nn_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_results`
--
ALTER TABLE `training_results`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
