-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2023. Nov 11. 08:35
-- Kiszolgáló verziója: 10.5.19-MariaDB-cll-lve
-- PHP verzió: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `toxyhu_api`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `lockers`
--

CREATE TABLE `lockers` (
  `id` int(255) NOT NULL,
  `status` varchar(10) NOT NULL,
  `NeptunCode` varchar(10) NOT NULL,
  `UniPassCode` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- A tábla adatainak kiíratása `lockers`
--

INSERT INTO `lockers` (`id`, `status`, `NeptunCode`, `UniPassCode`) VALUES
(1, 'off', '', ''),
(2, 'off', '', ''),
(3, 'off', '', '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `settingsName` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- A tábla adatainak kiíratása `settings`
--

INSERT INTO `settings` (`id`, `settingsName`, `value`) VALUES
(1, 'NumberOfLockers', '3'),
(2, 'ApiKey', '575e662522972a8df6d87978df3ca137'),
(3, 'PhysicalLockers', '3');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `VName` varchar(32) NOT NULL,
  `KName` varchar(32) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(120) NOT NULL,
  `NeptunCode` varchar(6) NOT NULL,
  `UniPassCode` varchar(24) NOT NULL,
  `CreatedAT` timestamp NOT NULL DEFAULT current_timestamp(),
  `Rank` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `VName`, `KName`, `Email`, `Password`, `NeptunCode`, `UniPassCode`, `CreatedAT`, `Rank`) VALUES
(69, 'Teszt', 'Elek', 'teszt@gmail.com', '$2y$10$9gXcFZVYo6Fx0OxbJ3jiuuoDW4X9E8G6Ju8stE1o4I7jEZjP3ofyK', 'ASDASD', 'DEACTIVATED', '2023-09-23 21:40:02', 'Student');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `lockers`
--
ALTER TABLE `lockers`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `lockers`
--
ALTER TABLE `lockers`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3001;

--
-- AUTO_INCREMENT a táblához `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
