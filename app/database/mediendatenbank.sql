-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 27. Sep 2024 um 15:22
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `mediendatenbank`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `Benutzer_ID` int(11) NOT NULL,
  `Benutzername` varchar(20) NOT NULL,
  `EMail` varchar(30) NOT NULL,
  `Nachname` varchar(30) NOT NULL,
  `Vorname` varchar(30) NOT NULL,
  `Rolle` varchar(10) NOT NULL,
  `Registrierungsdatum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`Benutzer_ID`, `Benutzername`, `EMail`, `Nachname`, `Vorname`, `Rolle`, `Registrierungsdatum`) VALUES
(1, 'Nutzer1', 'Nutzer1@gmail.com', 'test', 'Lukas', 'admin', '2024-09-26'),
(2, 'Nutzer2', 'Nutzer2@gmail.com', 'test', 'Hans', 'false', '2024-09-27'),
(3, 'Nutzer3', 'Nutzer3@gmail.com', 'test3', 'Hans', 'false', '2024-09-27'),
(4, 'Nutzer4', 'Nutzer4@gmail.com', 'test4', 'Gnther', 'false', '2024-09-27');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ebooks`
--

CREATE TABLE `ebooks` (
  `ebook_ID` char(36) NOT NULL,
  `Titel` varchar(30) NOT NULL,
  `Dateipfad` varchar(80) DEFAULT NULL,
  `Typ` varchar(10) NOT NULL,
  `Dateigröße` varchar(20) NOT NULL,
  `Hochlade_datum` date NOT NULL,
  `Autor` varchar(30) DEFAULT NULL,
  `Seitenzahl` int(11) DEFAULT NULL,
  `Benutzer_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `ebooks`
--

INSERT INTO `ebooks` (`ebook_ID`, `Titel`, `Dateipfad`, `Typ`, `Dateigröße`, `Hochlade_datum`, `Autor`, `Seitenzahl`, `Benutzer_ID`) VALUES
('028bc472-41c9-4749-9e51-776d9ecf501b', '3.pdf', '/Mediendatenbank/public/uploads/ebook/3.pdf', 'ebook', '13622', '2024-09-27', '', 0, 1),
('28435320-71b1-4c05-8cec-0fa5164d5bd0', '8.pdf', '/Mediendatenbank/public/uploads/ebook/8.pdf', 'ebook', '13698', '2024-09-27', '', 0, 1),
('61b985bf-881e-45d5-8efe-f39fd07e4641', '9.pdf', '/Mediendatenbank/public/uploads/ebook/9.pdf', 'ebook', '13667', '2024-09-27', '', 0, 1),
('639beccf-c7c7-4982-bc1f-c6007e8ae165', '4.pdf', '/Mediendatenbank/public/uploads/ebook/4.pdf', 'ebook', '13571', '2024-09-27', '', 0, 1),
('6464d984-8ce5-4ce6-9749-a28b93ab6ec3', '7.pdf', '/Mediendatenbank/public/uploads/ebook/7.pdf', 'ebook', '13487', '2024-09-27', '', 0, 1),
('9d16ccf5-9a83-4813-b015-41a8486eec13', '10.pdf', '/Mediendatenbank/public/uploads/ebook/10.pdf', 'ebook', '14187', '2024-09-27', '', 0, 1),
('cae1784c-bf53-45d3-9a20-29b2c5e85d82', '1.pdf', '/Mediendatenbank/public/uploads/ebook/1.pdf', 'ebook', '14787', '2024-09-27', '', 0, 1),
('cb7d1f75-9441-4e78-bde5-9a47cb4bb89e', '2.pdf', '/Mediendatenbank/public/uploads/ebook/2.pdf', 'ebook', '13650', '2024-09-27', '', 0, 1),
('cca64225-2de3-4b9d-b695-d6eb3492f523', '6.pdf', '/Mediendatenbank/public/uploads/ebook/6.pdf', 'ebook', '13638', '2024-09-27', '', 0, 1),
('d514a9ae-ecae-4482-9f78-50d99b0e9529', '5.pdf', '/Mediendatenbank/public/uploads/ebook/5.pdf', 'ebook', '13607', '2024-09-27', '', 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fotos`
--

CREATE TABLE `fotos` (
  `Foto_ID` char(36) NOT NULL,
  `Titel` varchar(30) NOT NULL,
  `Dateipfad` varchar(80) DEFAULT NULL,
  `Typ` varchar(10) NOT NULL,
  `Dateigröße` varchar(20) NOT NULL,
  `Hochlade_datum` date NOT NULL,
  `Auflösung` varchar(10) DEFAULT NULL,
  `Benutzer_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `fotos`
--

INSERT INTO `fotos` (`Foto_ID`, `Titel`, `Dateipfad`, `Typ`, `Dateigröße`, `Hochlade_datum`, `Auflösung`, `Benutzer_ID`) VALUES
('0508c149-4374-47b8-b3ef-e25af9c9e365', 'Forest9.jpeg', '/Mediendatenbank/public/uploads/photo/Forest9.jpeg', 'photo', '16119', '2024-09-27', '259x194', 1),
('21db4cab-144a-4721-ab4b-7c38d6a479b9', 'Forest8.jpeg', '/Mediendatenbank/public/uploads/photo/Forest8.jpeg', 'photo', '8001', '2024-09-27', '275x183', 1),
('2a9ea406-18df-4c27-8eff-7b9b746058e9', 'Forest7.jpeg', '/Mediendatenbank/public/uploads/photo/Forest7.jpeg', 'photo', '10751', '2024-09-27', '300x168', 1),
('498cd023-7823-4bcd-b7ab-bf5de62ba82c', 'forest4.jpeg', '/Mediendatenbank/public/uploads/photo/forest4.jpeg', 'photo', '13834', '2024-09-27', '275x183', 1),
('75d3105b-6fa2-428c-abec-5d6ac84b3245', 'Forest3.jpeg', '/Mediendatenbank/public/uploads/photo/Forest3.jpeg', 'photo', '11256', '2024-09-27', '295x171', 1),
('7a792ad0-5e7b-47f7-9238-dcd3b2eb3285', 'Forest 5.jpeg', '/Mediendatenbank/public/uploads/photo/Forest 5.jpeg', 'photo', '9140', '2024-09-27', '300x168', 1),
('d13d663c-81af-4af3-ab21-24a5bc7b148c', 'Forest1.jpeg', '/Mediendatenbank/public/uploads/photo/Forest1.jpeg', 'photo', '7192', '2024-09-27', '275x183', 1),
('d8e47f5a-6dbe-48a9-b543-f0b3bc9fbb88', 'Forest2.jpeg', '/Mediendatenbank/public/uploads/photo/Forest2.jpeg', 'photo', '16432', '2024-09-27', '182x276', 1),
('f08064f7-ca29-4018-bad7-7e2b6ff49c22', 'Forest10.jpeg', '/Mediendatenbank/public/uploads/photo/Forest10.jpeg', 'photo', '12217', '2024-09-27', '300x168', 1),
('fad314a8-61fe-4861-a95e-cdae5642f7aa', 'Forest6.jpeg', '/Mediendatenbank/public/uploads/photo/Forest6.jpeg', 'photo', '18556', '2024-09-27', '275x183', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hörbücher`
--

CREATE TABLE `hörbücher` (
  `Hörbuch_ID` char(36) NOT NULL,
  `Titel` varchar(30) NOT NULL,
  `Dateipfad` varchar(80) DEFAULT NULL,
  `Typ` varchar(10) NOT NULL,
  `Dateigröße` varchar(20) NOT NULL,
  `Hochlade_datum` date NOT NULL,
  `Sprecher` varchar(30) DEFAULT NULL,
  `Dauer` time DEFAULT NULL,
  `Benutzer_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `hörbücher`
--

INSERT INTO `hörbücher` (`Hörbuch_ID`, `Titel`, `Dateipfad`, `Typ`, `Dateigröße`, `Hochlade_datum`, `Sprecher`, `Dauer`, `Benutzer_ID`) VALUES
('02f8622a-4e8b-48ec-aa5f-18b9555a0ce2', 'Audiobook4.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook4.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('27b8d86d-e213-4906-a187-c57f07ec9af7', 'Audiobook10.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook10.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('5999af73-50df-4430-bf86-525073a13b1d', 'Audiobook1.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook1.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('5a9f7159-6a0b-40b6-9852-c0478c054a1a', 'Audiobook5.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook5.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('8e1c276e-8c20-40a9-8135-550e21d3db16', 'Audiobook6.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook6.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('91e15e99-8fa0-4d89-8b6f-faffbc8ce32b', 'Audiobook7.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook7.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('9547e1a2-b75b-4696-9125-af3cbf9a6ee8', 'Audiobook9.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook9.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('d017d117-a051-45a7-81d9-a371a8de763b', 'Audiobook8.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook8.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('ef8d49db-11ad-4744-baac-ca9e6097a55f', 'Audiobook3.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook3.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1),
('fb39c323-07bc-4ec7-a9e4-924e7a34e146', 'Audiobook2.mp3', '/Mediendatenbank/public/uploads/audiobook/Audiobook2.mp3', 'audiobook', '0', '2024-09-27', '', '00:00:00', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schlagworte`
--

CREATE TABLE `schlagworte` (
  `Schlagwort_ID` int(11) NOT NULL,
  `Schlagwort_Name` varchar(20) NOT NULL,
  `Benutzer_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schlagwortmedien`
--

CREATE TABLE `schlagwortmedien` (
  `Schlagwort_ID` int(11) NOT NULL,
  `ebook_ID` char(36) DEFAULT NULL,
  `Hörbuch_ID` char(36) DEFAULT NULL,
  `Video_ID` char(36) DEFAULT NULL,
  `Foto_ID` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `videos`
--

CREATE TABLE `videos` (
  `Video_ID` char(36) NOT NULL,
  `Titel` varchar(30) NOT NULL,
  `Dateipfad` varchar(80) DEFAULT NULL,
  `Typ` varchar(10) NOT NULL,
  `Dateigröße` varchar(20) NOT NULL,
  `Hochlade_datum` date NOT NULL,
  `Auflösung` varchar(10) DEFAULT NULL,
  `Dauer` time DEFAULT NULL,
  `Benutzer_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `videos`
--

INSERT INTO `videos` (`Video_ID`, `Titel`, `Dateipfad`, `Typ`, `Dateigröße`, `Hochlade_datum`, `Auflösung`, `Dauer`, `Benutzer_ID`) VALUES
('249b43da-e22c-4cde-8463-4e4a4aa2909f', '2024-09-22 09-02-19.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-19.mkv', 'video', '1978864', '2024-09-27', '', '00:00:00', 1),
('359dc781-e000-4e0d-9756-e531fb60fab8', '2024-09-22 09-02-30.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-30.mkv', 'video', '1644352', '2024-09-27', '', '00:00:00', 1),
('4f2cb6d1-605c-423e-b108-ec76aa5300b7', '2024-09-22 09-02-05.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-05.mkv', 'video', '1470440', '2024-09-27', '', '00:00:00', 1),
('60068f52-bb67-4312-8e3b-dc6c35bf28e6', '2024-09-22 09-02-50.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-50.mkv', 'video', '1361383', '2024-09-27', '', '00:00:00', 1),
('65c216b2-dd60-43dd-81af-45e3e9a7f974', '2024-09-22 09-02-09.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-09.mkv', 'video', '1926386', '2024-09-27', '', '00:00:00', 1),
('742048c0-5334-4571-9678-a8fd0f7ce612', '2024-09-22 09-02-34.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-34.mkv', 'video', '1081547', '2024-09-27', '', '00:00:00', 1),
('a8884b81-c49e-4725-914c-df54a063f2b1', '2024-09-22 09-02-38.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-38.mkv', 'video', '1688875', '2024-09-27', '', '00:00:00', 1),
('b02f32e4-a598-4897-bb71-13c34eb7861f', '2024-09-22 09-02-25.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-25.mkv', 'video', '2276300', '2024-09-27', '', '00:00:00', 1),
('d5c12f83-ec37-46a3-9004-defb75acb87b', '2024-09-22 09-02-13.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-13.mkv', 'video', '2155224', '2024-09-27', '', '00:00:00', 1),
('dd4b759c-8964-4fff-98c5-ee606579e8f8', '2024-09-22 09-02-54.mkv', '/Mediendatenbank/public/uploads/video/2024-09-22 09-02-54.mkv', 'video', '1562620', '2024-09-27', '', '00:00:00', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`Benutzer_ID`);

--
-- Indizes für die Tabelle `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`ebook_ID`),
  ADD KEY `Benutzer_ID` (`Benutzer_ID`);

--
-- Indizes für die Tabelle `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`Foto_ID`),
  ADD KEY `Benutzer_ID` (`Benutzer_ID`);

--
-- Indizes für die Tabelle `hörbücher`
--
ALTER TABLE `hörbücher`
  ADD PRIMARY KEY (`Hörbuch_ID`),
  ADD KEY `Benutzer_ID` (`Benutzer_ID`);

--
-- Indizes für die Tabelle `schlagworte`
--
ALTER TABLE `schlagworte`
  ADD PRIMARY KEY (`Schlagwort_ID`),
  ADD KEY `Benutzer_ID` (`Benutzer_ID`);

--
-- Indizes für die Tabelle `schlagwortmedien`
--
ALTER TABLE `schlagwortmedien`
  ADD KEY `Schlagwort_ID` (`Schlagwort_ID`),
  ADD KEY `ebook_ID` (`ebook_ID`),
  ADD KEY `Hörbuch_ID` (`Hörbuch_ID`),
  ADD KEY `Video_ID` (`Video_ID`),
  ADD KEY `Foto_ID` (`Foto_ID`);

--
-- Indizes für die Tabelle `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`Video_ID`),
  ADD KEY `Benutzer_ID` (`Benutzer_ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `Benutzer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `schlagworte`
--
ALTER TABLE `schlagworte`
  MODIFY `Schlagwort_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `ebooks`
--
ALTER TABLE `ebooks`
  ADD CONSTRAINT `ebooks_ibfk_1` FOREIGN KEY (`Benutzer_ID`) REFERENCES `benutzer` (`Benutzer_ID`);

--
-- Constraints der Tabelle `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`Benutzer_ID`) REFERENCES `benutzer` (`Benutzer_ID`);

--
-- Constraints der Tabelle `hörbücher`
--
ALTER TABLE `hörbücher`
  ADD CONSTRAINT `hörbücher_ibfk_1` FOREIGN KEY (`Benutzer_ID`) REFERENCES `benutzer` (`Benutzer_ID`);

--
-- Constraints der Tabelle `schlagworte`
--
ALTER TABLE `schlagworte`
  ADD CONSTRAINT `schlagworte_ibfk_1` FOREIGN KEY (`Benutzer_ID`) REFERENCES `benutzer` (`Benutzer_ID`);

--
-- Constraints der Tabelle `schlagwortmedien`
--
ALTER TABLE `schlagwortmedien`
  ADD CONSTRAINT `schlagwortmedien_ibfk_1` FOREIGN KEY (`Schlagwort_ID`) REFERENCES `schlagworte` (`Schlagwort_ID`),
  ADD CONSTRAINT `schlagwortmedien_ibfk_2` FOREIGN KEY (`ebook_ID`) REFERENCES `ebooks` (`ebook_ID`),
  ADD CONSTRAINT `schlagwortmedien_ibfk_3` FOREIGN KEY (`Hörbuch_ID`) REFERENCES `hörbücher` (`Hörbuch_ID`),
  ADD CONSTRAINT `schlagwortmedien_ibfk_4` FOREIGN KEY (`Video_ID`) REFERENCES `videos` (`Video_ID`),
  ADD CONSTRAINT `schlagwortmedien_ibfk_5` FOREIGN KEY (`Foto_ID`) REFERENCES `fotos` (`Foto_ID`);

--
-- Constraints der Tabelle `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`Benutzer_ID`) REFERENCES `benutzer` (`Benutzer_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
