-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 13 Haz 2023, 20:19:41
-- Sunucu sürümü: 5.7.42
-- PHP Sürümü: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `turkticaret_ecommerce`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `sepet_tutar` double DEFAULT NULL,
  `yuzde` int(11) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `min_adet` int(11) DEFAULT NULL,
  `max_adet` int(11) DEFAULT NULL,
  `durum` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `campaigns`
--

INSERT INTO `campaigns` (`id`, `title`, `description`, `sepet_tutar`, `yuzde`, `author`, `category_id`, `min_adet`, `max_adet`, `durum`) VALUES
(1, 'sepet_yuzde_indirim', '100 TL ve üzeri alışverişlerde sipariş toplamına %5 indirim', 100, 5, NULL, NULL, NULL, NULL, 1),
(2, 'yazar_kategori_indirim', 'Sabahattin Ali\'nin Roman kitaplarında 2 üründen 1 tanesi bedava.', NULL, NULL, 'Sabahattin Ali', 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `title`) VALUES
(1, 'Roman'),
(2, 'Kişisel Gelişim'),
(3, 'Bilim'),
(4, 'Çocuk ve Gençlik'),
(5, 'Öykü'),
(6, 'Felsefe'),
(7, 'Din Tasavvuf');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `product_id_list` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `list_price` double NOT NULL,
  `campaign_price` double NOT NULL,
  `cargo_price` double NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `list_price` double NOT NULL,
  `stock_quantity` int(255) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `title`, `category_id`, `author`, `list_price`, `stock_quantity`, `created_at`) VALUES
(11, 'Kuyucaklı Yusuf', 1, 'Sabahattin Ali', 10.4, 2, NULL),
(10, 'Benim Zürafam Uçabilir', 4, 'Mert Arık', 27.3, 12, NULL),
(9, 'Aşk 5 Vakittir', 4, 'Mehmet Yıldız', 42, 9, NULL),
(8, 'Allah De Ötesini Bırak', 4, 'Uğur Koşar', 39.6, 18, NULL),
(7, 'Kara Delikler', 3, 'Stephen Hawking', 39, 2, NULL),
(6, 'Sen Yola Çık Yol Sana Görünür', 2, 'Hakan Mengüç', 28.5, 7, NULL),
(5, 'Şeker Portakalı', 1, 'Jose Mauro De Vasconcelos', 33, 1, NULL),
(4, 'Fareler ve İnsanlar', 1, 'John Steinback', 35.75, 8, NULL),
(3, 'Kürk Mantolu Madonna', 1, 'Sabahattin Ali', 9.1, 4, NULL),
(2, 'Tutunamayanlar', 1, 'Oğuz Atay', 90.3, 20, NULL),
(1, 'İnce Memed', 1, 'Yaşar Kemal', 48.75, 10, NULL),
(12, 'Kamyon - Seçme Öyküler', 5, 'Sabahattin Ali', 9.75, 9, NULL),
(13, 'Kendime Düşünceler', 6, 'Marcus Aurelius', 14.4, 1, NULL),
(14, 'Denemeler - Hasan Ali Yücel Klasikleri', 6, 'Michel de Montaigne', 24, 4, NULL),
(15, 'Animal Farm', 1, 'George Orwell', 17.5, 1, NULL),
(16, 'Dokuzuncu Hariciye Koğuşu', 1, 'Peyami Safa', 18.5, 0, NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
