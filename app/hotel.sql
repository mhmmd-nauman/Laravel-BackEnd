-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2015 at 12:24 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10) unsigned DEFAULT NULL,
  `state_id` int(10) unsigned DEFAULT NULL,
  `city_id` int(10) unsigned DEFAULT NULL,
  `neighbourhood_id` int(10) unsigned DEFAULT NULL,
  `route_id` int(10) unsigned DEFAULT NULL,
  `street_id` int(10) unsigned DEFAULT NULL,
  `postal_code_id` int(10) unsigned DEFAULT NULL,
  `lat` double(12,8) NOT NULL,
  `lng` double(12,8) NOT NULL,
  `address` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `addresses_city_id_foreign` (`city_id`),
  KEY `addresses_country_id_foreign` (`country_id`),
  KEY `addresses_neighbourhood_id_foreign` (`neighbourhood_id`),
  KEY `addresses_postal_code_id_foreign` (`postal_code_id`),
  KEY `addresses_route_id_foreign` (`route_id`),
  KEY `addresses_state_id_foreign` (`state_id`),
  KEY `addresses_street_id_foreign` (`street_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `country_id`, `state_id`, `city_id`, `neighbourhood_id`, `route_id`, `street_id`, `postal_code_id`, `lat`, `lng`, `address`, `created_at`, `updated_at`) VALUES
(2, 1, 2, 3, 11, 12, 13, NULL, 48.46452900, 35.04702000, 'Dnepropetrovsk', '2014-05-21 21:01:39', '2014-06-10 02:16:55'),
(3, 1, 4, 5, 7, 8, 9, NULL, 50.45020900, 30.52253690, 'Kyiv', '2014-05-21 21:02:20', '2014-05-30 13:49:55'),
(4, 1, 6, NULL, NULL, 10, NULL, NULL, 48.37552610, 31.15961310, 'Ukraine', '2014-05-21 22:47:21', '2014-06-10 02:16:35'),
(5, 14, 15, 16, NULL, 17, 18, 19, 48.82342680, 2.30729870, '10 rue Gambetta, Paris, France', '2015-03-01 03:17:26', '2015-03-01 03:17:26'),
(6, 20, 21, 22, NULL, NULL, NULL, NULL, 31.55460610, 74.35715810, 'Lahore, Punjab, Pakistan', '2015-03-01 03:19:56', '2015-03-01 03:19:56'),
(7, 20, 21, 22, NULL, NULL, NULL, NULL, 31.55460610, 74.35715810, 'Lahore, Punjab, Pakistan', '2015-03-01 03:26:10', '2015-03-01 03:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `amusements`
--

CREATE TABLE IF NOT EXISTS `amusements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `default_photo_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `amusements`
--

INSERT INTO `amusements` (`id`, `name`, `description`, `status`, `default_photo_id`, `hotel_id`, `created_at`, `updated_at`) VALUES
(1, 'new amusement ', 'description', 1, 0, 5, '2015-03-01 03:54:21', '2015-03-01 03:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `price_type` enum('person','booking') COLLATE utf8_unicode_ci NOT NULL,
  `expire` datetime NOT NULL,
  `discount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `discounts_code_unique` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `name`, `code`, `count`, `price_type`, `expire`, `discount`, `created_at`, `updated_at`, `status`) VALUES
(1, 'New Discount', '101', 10, 'person', '0000-00-00 00:00:00', 10, '2015-03-01 06:50:48', '2015-03-01 06:50:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Users', '{"users":1}', '2014-05-16 23:20:17', '2014-05-16 23:20:17'),
(2, 'Admins', '{"admin":1,"users":1}', '2014-05-16 23:20:17', '2014-05-16 23:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE IF NOT EXISTS `hotels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `address_id` int(10) unsigned DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reception_times` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `offsite_booking_url` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  `offsite_booking` tinyint(4) NOT NULL,
  `default_photo_id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `summary` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `hotels_address_id_foreign` (`address_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `description`, `user_id`, `address_id`, `phone`, `reception_times`, `status`, `created_at`, `updated_at`, `offsite_booking_url`, `offsite_booking`, `default_photo_id`, `slug`, `summary`) VALUES
(2, 'test hotel 1', '<h1>Recens erant caelumque coercuit turba</h1><p>Fratrum totidemque mentisque. Vultus iunctarum inter. Levitate fabricator dispositam quanto viseret semina. Partim corpora magni caelum mundo faecis quae sorbentur cepit. Glomeravit verba effigiem permisit seductaque ignea. Ora animalibus terrarum sata caeca omni. Sic ora septemque omni fuerat qui habitandae.</p><h2>Dicere diu tum litem secuit</h2><h3>Caelumque pro minantia duae proximus</h3><h4>Legebantur ventos omnia sublime haec</h4><p>Flamma amphitrite. Rerum umentia erat:! Tellure tractu quicquam nullaque nondum non aethera utque. Frigida moles sidera proximus aequalis fecit haec. Amphitrite moderantum freta obsistitur orba. Melioris cepit ne titan glomeravit recepta manebat.</p><ul><li>Descenderat obsistitur media egens nitidis ipsa orbe terris adsiduis utque nix cesserunt terrae galeae aliud densior gentes pulsant arce quod</li><li>Quod recens sublime contraria ubi pontus concordi radiis possedit tenent norant</li><li>Terrae habentem quoque amphitrite radiis carmen litora habentem triones agitabilis iuga declivia umentia</li><li>Titan omni secant nec dedit nabataeaque quarum media colebat sorbentur figuras humanas induit volucres tonitrua poena silvas spectent ponderibus sorbentur pro origine satus fontes</li><li>Caligine adsiduis solum boreas peragebant nubes praebebat pondus igni est surgere undae unus motura di super et sublime toto posset: nec pulsant matutinis effervescere</li></ul><p>Certis natus carentem terrarum ventos cum mollia. Orba sorbentur dedit porrexerat. Proximus contraria haec amphitrite sectamque. Mea mixta? Flamma scythiam rerum sectamque flexi adhuc praeter caelo. Astra tonitrua solum quoque locoque inmensa ultima.</p><ol><li>Ora invasit habendum sine pendebat vindice effervescere recepta terrenae certis subdita volucres boreas mortales indigestaque zonae phoebe peregrinum tractu habendum est vultus terram</li><li>Egens quarum ulla dissociata pondere septemque nullus vix tumescere quoque contraria</li><li>Aethera caelumque dextra zephyro circumfuso possedit tanta nubibus aurea sidera ne hominum mortales minantia sanctius quin</li><li>Quam distinxit iudicis locis silvas hunc et media pontus fixo diu</li></ol><p>Cuncta pinus campoque quicquam? Dispositam obsistitur declivia haec caelum sive peragebant. Utramque emicuit adspirate quam. Pronaque humanas terris nullaque tollere vos sidera levius! Caelum chaos: finxit tellure qui iuga fuerant mortales?</p>', 1, 2, '0988921927', '10:00 - 22:00', 1, '2014-05-21 21:01:39', '2014-06-10 15:40:50', '', 0, 10, 'test-hotel-1', NULL),
(3, 'Ystad Saltsjöbad', '<h1>Emicuit corpora ita flamina galeae</h1><p>Sed liquidas. Terra matutinis tepescunt altae tempora pugnabant alta circumdare frigida! Partim ne limitibus orbis flexi effigiem. Quem obsistitur ambitae ignotas sorbentur eodem ut pinus secrevit. Tractu tonitrua quod primaque obliquis regio habentia. Rectumque ardentior eurus illi circumfluus.</p><h2>Rudis peregrinum ponderibus animalia habentia</h2><h3>Habitandae totidem pinus habentia suis</h3><h4>Fecit extendi lumina faecis hanc</h4><p>Animalia amphitrite quae tollere congeriem nec levius. Bene cornua fontes peregrinum. Sive iudicis iudicis flexi iunctarum diffundi. Mundum gentes. Derecti terris non ponderibus circumfuso chaos: sunt fuerant quem. Magni ambitae nullo sorbentur abscidit.</p><ul><li>Et formas nuper recepta nubes austro coercuit exemit capacius quanto passim melioris natura ripis membra effervescere tellus habitabilis di fecit horrifer dissaepserat horrifer</li><li>Temperiemque caesa pulsant solidumque inminet undae proxima chaos: carentem tanta distinxit nitidis zephyro qui ulla postquam aequalis</li><li>Sic undis circumdare ligavit: obsistitur obstabatque deducite locis tonitrua caelumque regat effervescere aliis usu foret carentem mare convexi</li><li>Onerosior dispositam subdita glomeravit fidem rectumque evolvit di crescendo silvas aethera</li><li>Litem eodem tuti aethera semina dixere est orba tractu alto his flamina praeter hominum mortales</li><li>Nubes quem caeca mentisque obliquis utque sive eodem illic occiduo sibi mare fecit</li></ul><p>Grandia vos radiis caelumque mollia aer radiis. Praeter orbis totidem speciem umentia cornua dispositam porrexerat extendi. Rapidisque caelumque quia. Siccis undis. Postquam tractu abscidit sublime altae porrexerat? Duae pluvialibus mundi madescit elementaque. Nisi lapidosos ulla septemque. Natus scythiam manebat pondere quod mutatas tegi.</p><ol><li>Ipsa campos pro militis habentia rapidisque ad super egens nix plagae effigiem locum ne declivia</li><li>Rapidisque ita orbe fabricator natura verba regna orba ultima rudis austro bracchia habitandae rectumque piscibus</li><li>Qui perveniunt lucis sibi circumfuso tanto et piscibus iudicis sine nubibus chaos: dissociata egens pondere aeris posset: aquae carentem</li><li>Sibi nabataeaque ambitae regio duas librata viseret regna nam acervo quae effigiem inter orbis campos ponderibus recessit fert porrexerat caligine moles hominum</li></ol><p>Terram mundum ignea aequalis terras tuba liberioris margine. Motura os iussit concordi utque deus. Mundum perveniunt. Cum natura fluminaque. Surgere manebat campoque dixere tegi alto. Iunctarum emicuit. Tumescere nix corpora animal terrenae caelum flamina dextra sua. Nubibus vindice cetera est fecit pluvialibus illas.</p>', 1, 3, '0380480000000', '10:00 - 23:00', 1, '2014-05-21 21:02:20', '2015-01-25 03:26:50', '', 0, 12, 'ystad-saltsj', NULL),
(4, 'test', '<h1>Iudicis corpore ulla conversa piscibus</h1><p>Vesper praebebat hanc piscibus triones mundo. Plagae unda viseret quam fuit sinistra dissaepserat! Orba caeca di legebantur sive. Rectumque mentisque titan. Coercuit cura ulla scythiam hunc tuti fronde dispositam. Sorbentur norant ita possedit boreas figuras sed quam? Locum calidis sponte sive terras mundi subdita invasit.</p><h2>Ultima pace secuit tanto aer</h2><h3>Cornua quinta inter longo terrarum</h3><h4>Congestaque freta orbe quarum cura</h4><p>Illic regio dissociata illas locoque radiis. Vis duas super quia erat tumescere. Liquidum aliis diu imagine madescit gravitate terrae. Duae dispositam ambitae! Nec humanas ab matutinis. Moderantum natus vos orbe! Aestu semina aethere zonae. Locis legebantur ut litora pondere deducite media figuras passim.</p><ul><li>Tempora di illi quisque quarum locis coeperunt erectos lege sorbentur metusque quae membra meis</li><li>Mixta iussit otia dispositam congestaque diverso nullus colebat adhuc totidem</li><li>Omnia habendum lege quam ne siccis matutinis triones aquae caeca moderantum origine perpetuum nullo lapidosos aquae flamma glomeravit</li><li>Satus permisit concordi innabilis orbem concordi verba non radiis manebat quam natura caelo recepta aetas</li><li>Tonitrua frigida erat: liberioris erant et phoebe praebebat habendum calidis mare diverso glomeravit quod</li><li>Circumfuso regna elementaque valles dixere pro coeperunt mortales origo quin undae campoque</li></ul><p>Zonae animal nisi sublime silvas terra discordia fontes. Porrexerat exemit occiduo permisit quinta. Caeca ardentior litora congestaque cinxit coeperunt? Coegit formas descenderat iapeto liquidas altae. Stagna plagae contraria modo oppida natus piscibus!</p><ol><li>Fixo origine homini illas bracchia montibus sibi tractu fecit tuti ardentior</li><li>Solidumque abscidit aestu tempora cepit titan terris et circumdare zephyro legebantur omnia mixta suis metusque undis invasit terrae</li><li>Fecit satus formaeque ora lumina sive dedit terra nisi sibi tanto meis quanto abscidit pro librata inminet semine</li><li>Fontes congeriem nulli porrexerat summaque pressa circumfluus suis obliquis ligavit: tenent tenent ventis secant</li><li>Habentia gentes pulsant haec mixta modo subsidere mea terra sua pluvialibus animalia pugnabant nec pace hominum tanta congeriem aurea inminet habentem hanc liquidas</li></ol><p>Numero cum quia vindice unus. Omnia evolvit ventos lanient totidem ventos carentem feras. Erat nondum. Animus gentes duris. Vesper sibi tanto conversa summaque silvas. Montes praecipites scythiam divino emicuit mentisque iapeto terrarum pro ventos.</p>', 1, 4, '1231231', '12312', 1, '2014-05-21 22:47:21', '2014-06-10 15:40:50', 'http://google.com', 1, 5, 'test', NULL),
(5, 'new hotel', 'new hotel description', 1, 7, '', '', 1, '2015-03-01 03:26:10', '2015-03-01 03:26:10', '', 1, 0, 'new-hotel', 'new hotel summary');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_additions`
--

CREATE TABLE IF NOT EXISTS `hotel_additions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `default_photo_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `price_unit` enum('room','person') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_awards`
--

CREATE TABLE IF NOT EXISTS `hotel_awards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `default_photo_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `hotel_awards`
--

INSERT INTO `hotel_awards` (`id`, `name`, `description`, `status`, `default_photo_id`, `hotel_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 'new award', 'description ', 1, 0, 5, 'test.com', '2015-03-01 03:55:19', '2015-03-01 03:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_highlights`
--

CREATE TABLE IF NOT EXISTS `hotel_highlights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `default_photo_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quote_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quote_author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_quote_photo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `hotel_highlights`
--

INSERT INTO `hotel_highlights` (`id`, `name`, `description`, `status`, `default_photo_id`, `hotel_id`, `created_at`, `updated_at`, `quote_text`, `quote_author`, `default_quote_photo_id`) VALUES
(1, 'new highlight', 'description', 1, 0, 5, '2015-03-01 03:54:50', '2015-03-01 03:54:50', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned DEFAULT NULL,
  `type` enum('country','state','city','neighbourhood','route','street','postal_code') COLLATE utf8_unicode_ci NOT NULL,
  `lat` double(12,8) NOT NULL,
  `lng` double(12,8) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `locations_location_id_foreign` (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_id`, `type`, `lat`, `lng`, `name`, `created_at`, `updated_at`) VALUES
(1, NULL, 'country', 48.37943300, 31.16558000, 'Ukraine', '2014-05-21 17:08:05', '2014-05-21 17:08:05'),
(2, 1, 'state', 48.46471700, 35.04618300, 'Dnipropetrovsk Oblast', '2014-05-21 17:08:05', '2014-05-21 17:08:05'),
(3, 2, 'city', 48.46471700, 35.04618300, 'Dnepropetrovsk', '2014-05-21 17:08:05', '2014-05-21 17:08:05'),
(4, 1, 'state', 50.45010000, 30.52340000, 'Kyiv City', '2014-05-21 21:02:20', '2014-05-21 21:02:20'),
(5, 4, 'city', 50.45010000, 30.52340000, 'Kiev', '2014-05-21 21:02:20', '2014-05-21 21:02:20'),
(6, 1, 'state', 48.50793300, 32.26231700, 'Kirovohrads''ka Oblast', '2014-05-29 17:55:38', '2014-05-29 17:55:38'),
(7, 5, 'neighbourhood', 50.45000000, 30.52333330, 'Shevchenkivs''kyi District', '2014-05-30 13:49:55', '2014-05-30 13:49:55'),
(8, 7, 'route', 50.44791400, 30.52219210, 'Khreschatyk Street', '2014-05-30 13:49:55', '2014-05-30 13:49:55'),
(9, 8, 'street', 50.45020900, 30.52253690, '20-22', '2014-05-30 13:49:55', '2014-05-30 13:49:55'),
(10, 6, 'route', 48.25857000, 30.90351100, 'T1208', '2014-06-10 02:16:35', '2014-06-10 02:16:35'),
(11, 3, 'neighbourhood', 48.40465890, 35.01330500, 'Babushkins''kyi District', '2014-06-10 02:16:55', '2014-06-10 02:16:55'),
(12, 11, 'route', 48.46450080, 35.04548100, 'Karla Marksa Avenue', '2014-06-10 02:16:55', '2014-06-10 02:16:55'),
(13, 12, 'street', 48.46452900, 35.04702000, '50', '2014-06-10 02:16:55', '2014-06-10 02:16:55'),
(14, NULL, 'country', 46.22763800, 2.21374900, 'Frankreich', '2015-03-01 03:17:23', '2015-03-01 03:17:23'),
(15, 14, 'state', 48.84991980, 2.63704110, 'Île-De-France', '2015-03-01 03:17:23', '2015-03-01 03:17:23'),
(16, 15, 'city', 48.85661400, 2.35222190, 'Paris', '2015-03-01 03:17:24', '2015-03-01 03:17:24'),
(17, 16, 'route', 45.04431000, -0.12884770, 'Rue Gambetta', '2015-03-01 03:17:25', '2015-03-01 03:17:25'),
(18, 17, 'street', 48.82342680, 2.30729870, '10', '2015-03-01 03:17:25', '2015-03-01 03:17:25'),
(19, 17, 'postal_code', 48.81447510, 2.29333370, '92240', '2015-03-01 03:17:26', '2015-03-01 03:17:26'),
(20, NULL, 'country', 30.37532100, 69.34511600, 'Pakistan', '2015-03-01 03:19:55', '2015-03-01 03:19:55'),
(21, 20, 'state', 31.14713050, 75.34121790, 'Punjab', '2015-03-01 03:19:55', '2015-03-01 03:19:55'),
(22, 21, 'city', 31.55460610, 74.35715810, 'Lahore', '2015-03-01 03:19:56', '2015-03-01 03:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `location_groups`
--

CREATE TABLE IF NOT EXISTS `location_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('city','area') COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `location_groups`
--

INSERT INTO `location_groups` (`id`, `name`, `description`, `slug`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test1', 'test location', 'test1', 'city', 1, '2015-03-01 06:51:28', '2015-03-01 06:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `location_group_items`
--

CREATE TABLE IF NOT EXISTS `location_group_items` (
  `item_id` int(10) unsigned NOT NULL,
  `location_group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `location_group_items`
--

INSERT INTO `location_group_items` (`item_id`, `location_group_id`, `created_at`, `updated_at`) VALUES
(5, 1, '2015-03-01 06:51:28', '2015-03-01 06:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_05_18_132412_create_locations_table', 1),
('2014_05_18_132720_create_addresses_table', 1),
('2014_05_18_132721_create_packages_table', 1),
('2014_05_18_132722_create_hotels_table', 1),
('2014_05_18_132723_create_rooms_table', 1),
('2014_05_18_132724_create_room_prices_table', 1),
('2014_05_18_132725_create_package_rooms_table', 1),
('2014_05_18_151933_create_spa_table', 1),
('2014_05_18_152033_create_tags_table', 1),
('2014_05_18_152110_create_tag_table', 1),
('2014_05_18_153919_create_services_table', 1),
('2014_05_18_165246_create_foregin_keys_add', 1),
('2014_05_22_032359_add_packages_column', 1),
('2014_05_22_032521_delete_hotels_column', 1),
('2014_05_22_120234_create_table_photos', 1),
('2014_05_25_094351_create_treatments_table', 1),
('2014_05_26_114911_update_table_spa', 1),
('2014_05_28_065056_create_table_package_treatments', 1),
('2014_05_29_102200_update_hotels_table', 1),
('2014_05_29_112409_update_hotels_table_add_default_photo', 1),
('2014_06_02_115704_update_packages_table_add_default_photo', 1),
('2014_06_04_101706_create_table_settings', 1),
('2014_06_09_182855_create_packages_and_hotels_add_slug', 1),
('2014_06_10_074517_delete_rooms_price_fk_and_alter_settings', 1),
('2014_06_19_081246_create_restaurants_table', 1),
('2014_06_19_114013_update_photos_table_add_new_types', 1),
('2014_06_19_143710_create_table_amusements', 1),
('2014_06_20_060724_create_table_hotel_awards', 1),
('2014_06_20_060823_create_table_hotel_additions', 1),
('2014_06_20_061402_update_table_hotels_add_highlights', 1),
('2014_06_22_215053_update_table_photos_add_awards_type', 1),
('2014_06_24_142835_add_rooms_default_photo', 1),
('2014_06_25_072406_add_package_available_days_column', 1),
('2014_06_25_141701_add_price_per_person_field_to_packages', 1),
('2014_06_30_192941_add_highlights_quote_text_and_photo', 1),
('2012_12_06_225921_migration_cartalyst_sentry_install_users', 2),
('2012_12_06_225929_migration_cartalyst_sentry_install_groups', 2),
('2012_12_06_225945_migration_cartalyst_sentry_install_users_groups_pivot', 2),
('2012_12_06_225988_migration_cartalyst_sentry_install_throttle', 2),
('2014_07_03_073911_add_phone_filed_to_users', 3);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `overnights` int(10) unsigned DEFAULT NULL,
  `discount` int(10) unsigned DEFAULT NULL,
  `default_room_id` int(10) unsigned DEFAULT NULL,
  `last_minute` int(10) unsigned DEFAULT NULL,
  `campaign` int(10) unsigned DEFAULT NULL,
  `recommended` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `days_in_advance` int(10) unsigned DEFAULT NULL,
  `days_available` int(10) unsigned DEFAULT NULL,
  `package_includes` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hotel_id` int(10) unsigned NOT NULL,
  `default_photo_id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `available_days` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price_per_person` int(11) NOT NULL,
  `overnights_max` int(10) unsigned NOT NULL DEFAULT '0',
  `overnights_min` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `packages_default_room_id_foreign` (`default_room_id`),
  KEY `packages_hotel_id_foreign` (`hotel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `short_description`, `description`, `overnights`, `discount`, `default_room_id`, `last_minute`, `campaign`, `recommended`, `status`, `start_date`, `end_date`, `days_in_advance`, `days_available`, `package_includes`, `created_at`, `updated_at`, `hotel_id`, `default_photo_id`, `slug`, `available_days`, `price_per_person`, `overnights_max`, `overnights_min`) VALUES
(1, 'new package', 'new package description', 'full description', NULL, 10, NULL, 1, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, '', '2015-03-01 03:47:12', '2015-03-01 03:47:12', 5, 0, 'new-package', '1', 100, 200, 100);

-- --------------------------------------------------------

--
-- Table structure for table `package_rooms`
--

CREATE TABLE IF NOT EXISTS `package_rooms` (
  `package_id` int(10) unsigned DEFAULT NULL,
  `room_id` int(10) unsigned DEFAULT NULL,
  KEY `package_rooms_package_id_foreign` (`package_id`),
  KEY `package_rooms_room_id_foreign` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_treatments`
--

CREATE TABLE IF NOT EXISTS `package_treatments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `treatment_id` int(10) unsigned NOT NULL,
  `package_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `content_type` enum('hotels','packages','rooms','services') COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `content_id`, `content_type`, `file`, `status`, `created_at`, `updated_at`) VALUES
(2, 4, 'hotels', 'uploads/hotels/REDyY3cVA4QE6ePDTAqN.jpg', 1, '2014-05-22 20:45:12', '2014-05-22 21:52:18'),
(4, 1, 'packages', 'uploads/packages/V2yU9wfWus5rAkeTjS1K.jpg', 1, '2014-05-22 21:58:14', '2014-05-22 21:58:14'),
(5, 4, 'hotels', 'uploads/hotels/KyXlg9E7pFRcoKRVVX6a.jpg', 1, '2014-05-29 18:14:49', '2014-05-29 18:14:49'),
(7, 3, 'packages', 'uploads/packages/pAHY8zCKqkeV2e38f7Gk.jpg', 1, '2014-06-02 19:20:20', '2014-06-02 19:20:20'),
(8, 3, 'packages', 'uploads/packages/os9944HVoL6Wb8k5ELup.jpg', 1, '2014-06-02 19:20:47', '2014-06-02 19:20:47'),
(9, 3, 'hotels', 'uploads/hotels/8mmnkqdxOVatF2ezoICn.jpg', 1, '2014-06-05 11:17:12', '2014-06-05 11:17:12'),
(10, 2, 'hotels', 'uploads/hotels/civyqEIA1pDroZjm7p4b.jpg', 1, '2014-06-05 11:17:46', '2014-06-05 11:17:46'),
(11, 4, 'packages', 'uploads/packages/FPxCqFQOvNdMnYGcQzzL.png', 1, '2014-06-10 04:27:08', '2014-06-10 04:27:08'),
(12, 3, 'hotels', 'uploads/hotels/enor7942opjEGiDq9Ak9.jpg', 1, '2015-01-25 03:26:40', '2015-01-25 03:26:40'),
(13, 1, '', 'uploads/restaurants/vymjJJUysg9ftvsygxT9.jpg', 1, '2015-03-01 03:53:14', '2015-03-01 03:53:14'),
(14, 1, '', 'uploads/restaurants/5HjIojm4zopi31umLgSt.jpg', 1, '2015-03-01 03:53:31', '2015-03-01 03:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE IF NOT EXISTS `restaurants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `default_photo_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `description`, `status`, `default_photo_id`, `hotel_id`, `created_at`, `updated_at`) VALUES
(1, 'new restaurant', 'description', 1, 0, 5, '2015-03-01 03:52:25', '2015-03-01 03:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `max_residents` int(10) unsigned DEFAULT NULL,
  `hotel_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `default_photo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_hotel_id_foreign` (`hotel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `description`, `max_residents`, `hotel_id`, `status`, `created_at`, `updated_at`, `default_photo_id`) VALUES
(1, 'room name', 'room description', 2, 5, 1, '2015-03-01 03:51:41', '2015-03-01 03:51:41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_prices`
--

CREATE TABLE IF NOT EXISTS `room_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned NOT NULL,
  `price` double(10,2) NOT NULL,
  `weekday` enum('mon','tue','wed','thu','fri','sat','sun') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_prices_room_id_foreign` (`room_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `room_prices`
--

INSERT INTO `room_prices` (`id`, `room_id`, `price`, `weekday`) VALUES
(1, 1, 1.00, 'mon'),
(2, 1, 2.00, 'tue'),
(3, 1, 3.00, 'wed'),
(4, 1, 0.00, 'thu'),
(5, 1, 0.00, 'fri'),
(6, 1, 0.00, 'sat'),
(7, 1, 0.00, 'sun');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `content_type` enum('hotel','spa') COLLATE utf8_unicode_ci NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `settings_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('google_analytics', '<!-- Google Tag Manager -->\r\n<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-M72G95"\r\nheight="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>\r\n<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({''gtm.start'':\r\nnew Date().getTime(),event:''gtm.js''});var f=d.getElementsByTagName(s)[0],\r\nj=d.createElement(s),dl=l!=''dataLayer''?''&l=''+l:'''';j.async=true;j.src=\r\n''//www.googletagmanager.com/gtm.js?id=''+i+dl;f.parentNode.insertBefore(j,f);\r\n})(window,document,''script'',''dataLayer'',''GTM-M72G95'');</script>\r\n<!-- End Google Tag Manager -->'),
('order_email', 'rchy@isd.dp.ua');

-- --------------------------------------------------------

--
-- Table structure for table `spas`
--

CREATE TABLE IF NOT EXISTS `spas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `hotel_id` int(10) unsigned NOT NULL,
  `package_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `spas_hotel_id_foreign` (`hotel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `spas`
--

INSERT INTO `spas` (`id`, `name`, `description`, `hotel_id`, `package_id`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Midnight spa', 'massage for your <h1>back</h1>', 3, 0, 1, '2014-05-27 21:34:11', '2014-05-30 12:57:47'),
(3, 'super spaads', 'asdfasdfasdf', 2, 0, 1, '2014-05-28 18:42:27', '2014-05-28 19:49:11'),
(4, 'king spa', 'just for kings', 4, 0, 1, '2014-06-05 11:23:06', '2014-06-05 11:23:11'),
(5, 'new spa hotel', 'description', 5, 0, 1, '2015-03-01 03:49:23', '2015-03-01 03:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `content_type` enum('package','hotel') COLLATE utf8_unicode_ci NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_tag_id_foreign` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `throttle`
--

INSERT INTO `throttle` (`id`, `user_id`, `ip_address`, `attempts`, `suspended`, `banned`, `last_attempt_at`, `suspended_at`, `banned_at`) VALUES
(3, 2, '127.0.0.1', 2, 0, 0, '2014-05-18 17:32:18', NULL, NULL),
(4, 1, NULL, 0, 0, 0, NULL, NULL, NULL),
(5, 3, NULL, 0, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE IF NOT EXISTS `treatments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `persons` int(10) unsigned NOT NULL,
  `price` float(8,2) NOT NULL,
  `duration` int(10) unsigned NOT NULL,
  `default_photo` int(10) unsigned DEFAULT NULL,
  `spa_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `name`, `description`, `persons`, `price`, `duration`, `default_photo`, `spa_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Romantic massage', 'Super mega great massage', 1, 400.00, 30, NULL, 2, 1, '2014-05-28 13:22:30', '2014-05-28 13:22:30'),
(2, 'Face cleaning', 'Effective face cleaning', 1, 100.00, 10, NULL, 2, 1, '2014-05-28 13:30:10', '2014-05-28 13:30:10'),
(3, 'body massage', 'full body massage', 1, 700.00, 40, NULL, 3, 1, '2014-05-28 19:43:54', '2014-05-28 19:43:54'),
(4, 'fitness', 'fitness fitness', 2, 301.00, 90, NULL, 3, 1, '2014-05-28 19:45:05', '2014-05-29 21:13:38'),
(6, 'fish spa', 'super fish massage for your foots', 1, 123.00, 15, NULL, 3, 1, '2014-06-02 19:52:55', '2014-06-02 19:52:55'),
(7, 'healing', 'healig treatment description', 2, 450.00, 10, NULL, 4, 1, '2014-06-05 11:24:26', '2014-06-05 11:24:26'),
(8, 'new treatment', 'description', 10, 100.00, 10, NULL, 5, 1, '2015-03-01 03:50:20', '2015-03-01 03:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_activation_code_index` (`activation_code`),
  KEY `users_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `activated`, `activation_code`, `activated_at`, `last_login`, `persist_code`, `reset_password_code`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(1, 'Caroline@spason.se', '$2y$10$GGpY6tzLEEPL9GQRzBVWBePGIuqKBX5VYz/d8MxoeuhHNNIiA49WO', NULL, 1, NULL, NULL, '2015-03-01 06:50:06', '$2y$10$SeUbesGiMq5yXPLIBtEJXOZSULoUELxfZEZ83COoDpMjKcpA8/ttq', NULL, NULL, NULL, '2015-01-25 03:02:58', '2015-03-01 06:50:06'),
(2, 'Magnus@spason.se', '$2y$10$wk/78wn/lSePCJRFVY8xB.4a2iFrMG2HFhMf5URHCYlggqZlK/00a', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2015-01-25 03:02:58', '2015-01-25 03:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`user_id`, `group_id`) VALUES
(1, 1),
(1, 2),
(2, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `addresses_neighbourhood_id_foreign` FOREIGN KEY (`neighbourhood_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `addresses_postal_code_id_foreign` FOREIGN KEY (`postal_code_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `addresses_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `addresses_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `addresses_street_id_foreign` FOREIGN KEY (`street_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_default_room_id_foreign` FOREIGN KEY (`default_room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `packages_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `package_rooms`
--
ALTER TABLE `package_rooms`
  ADD CONSTRAINT `package_rooms_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `package_rooms_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `spas`
--
ALTER TABLE `spas`
  ADD CONSTRAINT `spas_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `tag_id_foreign` FOREIGN KEY (`id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
