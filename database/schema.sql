-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 11, 2026 at 09:44 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `theranova`
--

-- --------------------------------------------------------

--
-- Table structure for table `apartinatori`
--

CREATE TABLE `apartinatori` (
  `id` int UNSIGNED NOT NULL,
  `pacient_id` int UNSIGNED DEFAULT NULL,
  `nume` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenume` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefon` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grad_rudenie` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_activitati`
--

CREATE TABLE `calendar_activitati` (
  `id` int UNSIGNED NOT NULL,
  `calendar_id` int UNSIGNED DEFAULT NULL,
  `fisa_caz_id` int UNSIGNED DEFAULT NULL,
  `descriere` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_inceput` datetime DEFAULT NULL,
  `data_sfarsit` datetime DEFAULT NULL,
  `cazare` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observatii` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mementouri_zile` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mementouri_emailuri` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_calendare`
--

CREATE TABLE `calendar_calendare` (
  `id` int UNSIGNED NOT NULL,
  `nume` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `culoare` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cerinte`
--

CREATE TABLE `cerinte` (
  `id` int UNSIGNED NOT NULL,
  `fisa_caz_id` int UNSIGNED DEFAULT NULL,
  `decizie_cas` tinyint UNSIGNED DEFAULT NULL,
  `buget_disponibil` int UNSIGNED DEFAULT NULL,
  `sursa_buget` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cerinte_particulare_1` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cerinte_particulare_2` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cerinte_particulare_3` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cerinte_particulare_4` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alte_cerinte_1` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alte_cerinte_2` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alte_cerinte_3` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observatii` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comenzi`
--

CREATE TABLE `comenzi` (
  `id` int UNSIGNED NOT NULL,
  `fisa_caz_id` int UNSIGNED DEFAULT NULL,
  `data` date DEFAULT NULL,
  `sosita` tinyint DEFAULT NULL COMMENT '0-nu | 1-da',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comenzi_componente`
--

CREATE TABLE `comenzi_componente` (
  `id` int UNSIGNED NOT NULL,
  `fisa_caz_id` int UNSIGNED DEFAULT NULL,
  `comanda_id` int UNSIGNED DEFAULT NULL,
  `producator` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_produs` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bucati` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `date_medicale`
--

CREATE TABLE `date_medicale` (
  `id` int UNSIGNED NOT NULL,
  `fisa_caz_id` int UNSIGNED DEFAULT NULL,
  `greutate` tinyint UNSIGNED DEFAULT NULL,
  `parte_amputata` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amputatie` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nivel_de_activitate` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cauza_amputatiei` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_mai_purtat_proteza` tinyint UNSIGNED DEFAULT NULL COMMENT '0-nu | 1-da',
  `tip_proteza` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `circumferinta_bont` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `circumferinta_bont_la_nivel_perineu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marime_picior` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marime_picior_valoare` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alte_afectiuni` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observatii` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fise_caz`
--

CREATE TABLE `fise_caz` (
  `id` int UNSIGNED NOT NULL,
  `data` date DEFAULT NULL,
  `tip_lucrare_solicitata` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_vanzari` int UNSIGNED DEFAULT NULL,
  `user_comercial` int UNSIGNED DEFAULT NULL,
  `user_tehnic` int UNSIGNED DEFAULT NULL,
  `pacient_id` int UNSIGNED NOT NULL,
  `observatii` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oferta` date DEFAULT NULL,
  `fisa_comanda_data` date DEFAULT NULL,
  `fisa_comanda_sosita` tinyint(1) DEFAULT NULL COMMENT '0-nu | 1-da',
  `compresie_manson` date DEFAULT NULL,
  `comanda` date DEFAULT NULL,
  `protezare` date DEFAULT NULL,
  `programare_atelier` datetime DEFAULT NULL,
  `fisa_masuri_descriere` text COLLATE utf8mb4_unicode_ci,
  `stare` tinyint(1) DEFAULT NULL COMMENT '1-deschis | 2-inchis | 3-anulat',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fisiere`
--

CREATE TABLE `fisiere` (
  `id` int UNSIGNED NOT NULL,
  `referinta` tinyint UNSIGNED DEFAULT NULL COMMENT '1-oferte | 2-fisaComanda | 3-fisaMasuri | 4-comenzi',
  `referinta_id` int UNSIGNED DEFAULT NULL,
  `cale` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nume` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incasari`
--

CREATE TABLE `incasari` (
  `id` int UNSIGNED NOT NULL,
  `oferta_id` int UNSIGNED DEFAULT NULL,
  `suma` int UNSIGNED DEFAULT NULL,
  `data` date DEFAULT NULL,
  `nr_data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observatii` text COLLATE utf8mb4_unicode_ci,
  `tip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'incasare',
  `data_inregistrare` date DEFAULT NULL,
  `data_validare` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `informatii_generale`
--

CREATE TABLE `informatii_generale` (
  `id` bigint UNSIGNED NOT NULL,
  `variabila` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valoare` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mesaje_trimise_email`
--

CREATE TABLE `mesaje_trimise_email` (
  `id` int UNSIGNED NOT NULL,
  `referinta` tinyint UNSIGNED DEFAULT NULL COMMENT '1-fisaCaz | 2-comanda | 3-calendarActivitate | 4-incasare',
  `referinta_id` int UNSIGNED DEFAULT NULL,
  `referinta2` tinyint UNSIGNED DEFAULT NULL COMMENT '1-user',
  `referinta2_id` int UNSIGNED DEFAULT NULL,
  `tip` tinyint UNSIGNED DEFAULT NULL COMMENT '1-fisaCaz | 2-oferta | 3-comanda | 4-comandaSosita | 5-reminderAK | 6-reminderBK | 7-comandaVersiuneNoua | 8-mementoActivitateCalendar',
  `mesaj` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oferte`
--

CREATE TABLE `oferte` (
  `id` int UNSIGNED NOT NULL,
  `fisa_caz_id` int UNSIGNED DEFAULT NULL,
  `obiect_contract` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pret` int UNSIGNED DEFAULT NULL,
  `observatii` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceptata` tinyint UNSIGNED DEFAULT NULL COMMENT '0-nu / 1-da / 2-in asteptare',
  `contract_nr` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_data` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pacienti`
--

CREATE TABLE `pacienti` (
  `id` int UNSIGNED NOT NULL,
  `user_responsabil` int UNSIGNED DEFAULT NULL,
  `nume` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenume` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefon` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnp` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serie_numar_buletin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_eliberare_buletin` date DEFAULT NULL,
  `sex` tinyint(1) DEFAULT NULL COMMENT '1-masculin | 2-feminin',
  `cum_a_aflat_de_theranova` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresa` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localitate` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judet` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_postal` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observatii` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries`
--

CREATE TABLE `telescope_entries` (
  `sequence` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries_tags`
--

CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_monitoring`
--

CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `role` tinyint(1) DEFAULT NULL COMMENT '1-vanzari | 2-comercial | 3-tehnic',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activ` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `nume` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variabile`
--

CREATE TABLE `variabile` (
  `id` tinyint UNSIGNED NOT NULL,
  `nume` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valoare` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apartinatori`
--
ALTER TABLE `apartinatori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_activitati`
--
ALTER TABLE `calendar_activitati`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_calendare`
--
ALTER TABLE `calendar_calendare`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cerinte`
--
ALTER TABLE `cerinte`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comenzi`
--
ALTER TABLE `comenzi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comenzi_componente`
--
ALTER TABLE `comenzi_componente`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `date_medicale`
--
ALTER TABLE `date_medicale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fise_caz`
--
ALTER TABLE `fise_caz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fisiere`
--
ALTER TABLE `fisiere`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incasari`
--
ALTER TABLE `incasari`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `informatii_generale`
--
ALTER TABLE `informatii_generale`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `informatii_generale_variabila_unique` (`variabila`);

--
-- Indexes for table `mesaje_trimise_email`
--
ALTER TABLE `mesaje_trimise_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oferte`
--
ALTER TABLE `oferte`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pacienti`
--
ALTER TABLE `pacienti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`);

--
-- Indexes for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variabile`
--
ALTER TABLE `variabile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apartinatori`
--
ALTER TABLE `apartinatori`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar_activitati`
--
ALTER TABLE `calendar_activitati`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar_calendare`
--
ALTER TABLE `calendar_calendare`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cerinte`
--
ALTER TABLE `cerinte`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comenzi`
--
ALTER TABLE `comenzi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comenzi_componente`
--
ALTER TABLE `comenzi_componente`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `date_medicale`
--
ALTER TABLE `date_medicale`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fise_caz`
--
ALTER TABLE `fise_caz`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fisiere`
--
ALTER TABLE `fisiere`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incasari`
--
ALTER TABLE `incasari`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `informatii_generale`
--
ALTER TABLE `informatii_generale`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mesaje_trimise_email`
--
ALTER TABLE `mesaje_trimise_email`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oferte`
--
ALTER TABLE `oferte`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pacienti`
--
ALTER TABLE `pacienti`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  MODIFY `sequence` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_roles`
--
ALTER TABLE `users_roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variabile`
--
ALTER TABLE `variabile`
  MODIFY `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
