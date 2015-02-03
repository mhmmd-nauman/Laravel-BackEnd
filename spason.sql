-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.30-MariaDB-log - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2014-06-10 16:07:28
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for spason
DROP DATABASE IF EXISTS `spason`;
CREATE DATABASE IF NOT EXISTS `spason` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `spason`;


-- Dumping structure for table spason.addresses
DROP TABLE IF EXISTS `addresses`;
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
  KEY `addresses_country_id_foreign` (`country_id`),
  KEY `addresses_state_id_foreign` (`state_id`),
  KEY `addresses_city_id_foreign` (`city_id`),
  KEY `addresses_neighbourhood_id_foreign` (`neighbourhood_id`),
  KEY `addresses_route_id_foreign` (`route_id`),
  KEY `addresses_street_id_foreign` (`street_id`),
  KEY `addresses_postal_code_id_foreign` (`postal_code_id`),
  CONSTRAINT `addresses_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `addresses_neighbourhood_id_foreign` FOREIGN KEY (`neighbourhood_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `addresses_postal_code_id_foreign` FOREIGN KEY (`postal_code_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `addresses_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `addresses_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `addresses_street_id_foreign` FOREIGN KEY (`street_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.addresses: ~3 rows (approximately)
DELETE FROM `addresses`;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` (`id`, `country_id`, `state_id`, `city_id`, `neighbourhood_id`, `route_id`, `street_id`, `postal_code_id`, `lat`, `lng`, `address`, `created_at`, `updated_at`) VALUES
	(2, 1, 2, 3, 11, 12, 13, NULL, 48.46452900, 35.04702000, 'Dnepropetrovsk', '2014-05-21 14:01:39', '2014-06-09 19:16:55'),
	(3, 1, 4, 5, 7, 8, 9, NULL, 50.45020900, 30.52253690, 'Kyiv', '2014-05-21 14:02:20', '2014-05-30 06:49:55'),
	(4, 1, 6, NULL, NULL, 10, NULL, NULL, 48.37552610, 31.15961310, 'Ukraine', '2014-05-21 15:47:21', '2014-06-09 19:16:35');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;


-- Dumping structure for table spason.groups
DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.groups: ~2 rows (approximately)
DELETE FROM `groups`;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
	(1, 'Users', '{"users":1}', '2014-05-16 16:20:17', '2014-05-16 16:20:17'),
	(2, 'Admins', '{"admin":1,"users":1}', '2014-05-16 16:20:17', '2014-05-16 16:20:17');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;


-- Dumping structure for table spason.hotels
DROP TABLE IF EXISTS `hotels`;
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
  PRIMARY KEY (`id`),
  KEY `hotels_address_id_foreign` (`address_id`),
  CONSTRAINT `hotels_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.hotels: ~3 rows (approximately)
DELETE FROM `hotels`;
/*!40000 ALTER TABLE `hotels` DISABLE KEYS */;
INSERT INTO `hotels` (`id`, `name`, `description`, `user_id`, `address_id`, `phone`, `reception_times`, `status`, `created_at`, `updated_at`, `offsite_booking_url`, `offsite_booking`, `default_photo_id`, `slug`) VALUES
	(2, 'test hotel 1', '<h1>Recens erant caelumque coercuit turba</h1><p>Fratrum totidemque mentisque. Vultus iunctarum inter. Levitate fabricator dispositam quanto viseret semina. Partim corpora magni caelum mundo faecis quae sorbentur cepit. Glomeravit verba effigiem permisit seductaque ignea. Ora animalibus terrarum sata caeca omni. Sic ora septemque omni fuerat qui habitandae.</p><h2>Dicere diu tum litem secuit</h2><h3>Caelumque pro minantia duae proximus</h3><h4>Legebantur ventos omnia sublime haec</h4><p>Flamma amphitrite. Rerum umentia erat:! Tellure tractu quicquam nullaque nondum non aethera utque. Frigida moles sidera proximus aequalis fecit haec. Amphitrite moderantum freta obsistitur orba. Melioris cepit ne titan glomeravit recepta manebat.</p><ul><li>Descenderat obsistitur media egens nitidis ipsa orbe terris adsiduis utque nix cesserunt terrae galeae aliud densior gentes pulsant arce quod</li><li>Quod recens sublime contraria ubi pontus concordi radiis possedit tenent norant</li><li>Terrae habentem quoque amphitrite radiis carmen litora habentem triones agitabilis iuga declivia umentia</li><li>Titan omni secant nec dedit nabataeaque quarum media colebat sorbentur figuras humanas induit volucres tonitrua poena silvas spectent ponderibus sorbentur pro origine satus fontes</li><li>Caligine adsiduis solum boreas peragebant nubes praebebat pondus igni est surgere undae unus motura di super et sublime toto posset: nec pulsant matutinis effervescere</li></ul><p>Certis natus carentem terrarum ventos cum mollia. Orba sorbentur dedit porrexerat. Proximus contraria haec amphitrite sectamque. Mea mixta? Flamma scythiam rerum sectamque flexi adhuc praeter caelo. Astra tonitrua solum quoque locoque inmensa ultima.</p><ol><li>Ora invasit habendum sine pendebat vindice effervescere recepta terrenae certis subdita volucres boreas mortales indigestaque zonae phoebe peregrinum tractu habendum est vultus terram</li><li>Egens quarum ulla dissociata pondere septemque nullus vix tumescere quoque contraria</li><li>Aethera caelumque dextra zephyro circumfuso possedit tanta nubibus aurea sidera ne hominum mortales minantia sanctius quin</li><li>Quam distinxit iudicis locis silvas hunc et media pontus fixo diu</li></ol><p>Cuncta pinus campoque quicquam? Dispositam obsistitur declivia haec caelum sive peragebant. Utramque emicuit adspirate quam. Pronaque humanas terris nullaque tollere vos sidera levius! Caelum chaos: finxit tellure qui iuga fuerant mortales?</p>', 1, 2, '0988921927', '10:00 - 22:00', 1, '2014-05-21 14:01:39', '2014-06-10 08:40:50', '', 0, 10, 'test-hotel-1'),
	(3, 'Ystad Saltsjöbad', '<h1>Emicuit corpora ita flamina galeae</h1><p>Sed liquidas. Terra matutinis tepescunt altae tempora pugnabant alta circumdare frigida! Partim ne limitibus orbis flexi effigiem. Quem obsistitur ambitae ignotas sorbentur eodem ut pinus secrevit. Tractu tonitrua quod primaque obliquis regio habentia. Rectumque ardentior eurus illi circumfluus.</p><h2>Rudis peregrinum ponderibus animalia habentia</h2><h3>Habitandae totidem pinus habentia suis</h3><h4>Fecit extendi lumina faecis hanc</h4><p>Animalia amphitrite quae tollere congeriem nec levius. Bene cornua fontes peregrinum. Sive iudicis iudicis flexi iunctarum diffundi. Mundum gentes. Derecti terris non ponderibus circumfuso chaos: sunt fuerant quem. Magni ambitae nullo sorbentur abscidit.</p><ul><li>Et formas nuper recepta nubes austro coercuit exemit capacius quanto passim melioris natura ripis membra effervescere tellus habitabilis di fecit horrifer dissaepserat horrifer</li><li>Temperiemque caesa pulsant solidumque inminet undae proxima chaos: carentem tanta distinxit nitidis zephyro qui ulla postquam aequalis</li><li>Sic undis circumdare ligavit: obsistitur obstabatque deducite locis tonitrua caelumque regat effervescere aliis usu foret carentem mare convexi</li><li>Onerosior dispositam subdita glomeravit fidem rectumque evolvit di crescendo silvas aethera</li><li>Litem eodem tuti aethera semina dixere est orba tractu alto his flamina praeter hominum mortales</li><li>Nubes quem caeca mentisque obliquis utque sive eodem illic occiduo sibi mare fecit</li></ul><p>Grandia vos radiis caelumque mollia aer radiis. Praeter orbis totidem speciem umentia cornua dispositam porrexerat extendi. Rapidisque caelumque quia. Siccis undis. Postquam tractu abscidit sublime altae porrexerat? Duae pluvialibus mundi madescit elementaque. Nisi lapidosos ulla septemque. Natus scythiam manebat pondere quod mutatas tegi.</p><ol><li>Ipsa campos pro militis habentia rapidisque ad super egens nix plagae effigiem locum ne declivia</li><li>Rapidisque ita orbe fabricator natura verba regna orba ultima rudis austro bracchia habitandae rectumque piscibus</li><li>Qui perveniunt lucis sibi circumfuso tanto et piscibus iudicis sine nubibus chaos: dissociata egens pondere aeris posset: aquae carentem</li><li>Sibi nabataeaque ambitae regio duas librata viseret regna nam acervo quae effigiem inter orbis campos ponderibus recessit fert porrexerat caligine moles hominum</li></ol><p>Terram mundum ignea aequalis terras tuba liberioris margine. Motura os iussit concordi utque deus. Mundum perveniunt. Cum natura fluminaque. Surgere manebat campoque dixere tegi alto. Iunctarum emicuit. Tumescere nix corpora animal terrenae caelum flamina dextra sua. Nubibus vindice cetera est fecit pluvialibus illas.</p>', 1, 3, '0380480000000', '10:00 - 23:00', 1, '2014-05-21 14:02:20', '2014-06-10 08:40:50', '', 0, 9, 'ystad-saltsj'),
	(4, 'test', '<h1>Iudicis corpore ulla conversa piscibus</h1><p>Vesper praebebat hanc piscibus triones mundo. Plagae unda viseret quam fuit sinistra dissaepserat! Orba caeca di legebantur sive. Rectumque mentisque titan. Coercuit cura ulla scythiam hunc tuti fronde dispositam. Sorbentur norant ita possedit boreas figuras sed quam? Locum calidis sponte sive terras mundi subdita invasit.</p><h2>Ultima pace secuit tanto aer</h2><h3>Cornua quinta inter longo terrarum</h3><h4>Congestaque freta orbe quarum cura</h4><p>Illic regio dissociata illas locoque radiis. Vis duas super quia erat tumescere. Liquidum aliis diu imagine madescit gravitate terrae. Duae dispositam ambitae! Nec humanas ab matutinis. Moderantum natus vos orbe! Aestu semina aethere zonae. Locis legebantur ut litora pondere deducite media figuras passim.</p><ul><li>Tempora di illi quisque quarum locis coeperunt erectos lege sorbentur metusque quae membra meis</li><li>Mixta iussit otia dispositam congestaque diverso nullus colebat adhuc totidem</li><li>Omnia habendum lege quam ne siccis matutinis triones aquae caeca moderantum origine perpetuum nullo lapidosos aquae flamma glomeravit</li><li>Satus permisit concordi innabilis orbem concordi verba non radiis manebat quam natura caelo recepta aetas</li><li>Tonitrua frigida erat: liberioris erant et phoebe praebebat habendum calidis mare diverso glomeravit quod</li><li>Circumfuso regna elementaque valles dixere pro coeperunt mortales origo quin undae campoque</li></ul><p>Zonae animal nisi sublime silvas terra discordia fontes. Porrexerat exemit occiduo permisit quinta. Caeca ardentior litora congestaque cinxit coeperunt? Coegit formas descenderat iapeto liquidas altae. Stagna plagae contraria modo oppida natus piscibus!</p><ol><li>Fixo origine homini illas bracchia montibus sibi tractu fecit tuti ardentior</li><li>Solidumque abscidit aestu tempora cepit titan terris et circumdare zephyro legebantur omnia mixta suis metusque undis invasit terrae</li><li>Fecit satus formaeque ora lumina sive dedit terra nisi sibi tanto meis quanto abscidit pro librata inminet semine</li><li>Fontes congeriem nulli porrexerat summaque pressa circumfluus suis obliquis ligavit: tenent tenent ventis secant</li><li>Habentia gentes pulsant haec mixta modo subsidere mea terra sua pluvialibus animalia pugnabant nec pace hominum tanta congeriem aurea inminet habentem hanc liquidas</li></ol><p>Numero cum quia vindice unus. Omnia evolvit ventos lanient totidem ventos carentem feras. Erat nondum. Animus gentes duris. Vesper sibi tanto conversa summaque silvas. Montes praecipites scythiam divino emicuit mentisque iapeto terrarum pro ventos.</p>', 1, 4, '1231231', '12312', 1, '2014-05-21 15:47:21', '2014-06-10 08:40:50', 'http://google.com', 1, 5, 'test');
/*!40000 ALTER TABLE `hotels` ENABLE KEYS */;


-- Dumping structure for table spason.locations
DROP TABLE IF EXISTS `locations`;
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
  KEY `locations_location_id_foreign` (`location_id`),
  CONSTRAINT `locations_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.locations: ~9 rows (approximately)
DELETE FROM `locations`;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` (`id`, `location_id`, `type`, `lat`, `lng`, `name`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'country', 48.37943300, 31.16558000, 'Ukraine', '2014-05-21 10:08:05', '2014-05-21 10:08:05'),
	(2, 1, 'state', 48.46471700, 35.04618300, 'Dnipropetrovsk Oblast', '2014-05-21 10:08:05', '2014-05-21 10:08:05'),
	(3, 2, 'city', 48.46471700, 35.04618300, 'Dnepropetrovsk', '2014-05-21 10:08:05', '2014-05-21 10:08:05'),
	(4, 1, 'state', 50.45010000, 30.52340000, 'Kyiv City', '2014-05-21 14:02:20', '2014-05-21 14:02:20'),
	(5, 4, 'city', 50.45010000, 30.52340000, 'Kiev', '2014-05-21 14:02:20', '2014-05-21 14:02:20'),
	(6, 1, 'state', 48.50793300, 32.26231700, 'Kirovohrads\'ka Oblast', '2014-05-29 10:55:38', '2014-05-29 10:55:38'),
	(7, 5, 'neighbourhood', 50.45000000, 30.52333330, 'Shevchenkivs\'kyi District', '2014-05-30 06:49:55', '2014-05-30 06:49:55'),
	(8, 7, 'route', 50.44791400, 30.52219210, 'Khreschatyk Street', '2014-05-30 06:49:55', '2014-05-30 06:49:55'),
	(9, 8, 'street', 50.45020900, 30.52253690, '20-22', '2014-05-30 06:49:55', '2014-05-30 06:49:55'),
	(10, 6, 'route', 48.25857000, 30.90351100, 'T1208', '2014-06-09 19:16:35', '2014-06-09 19:16:35'),
	(11, 3, 'neighbourhood', 48.40465890, 35.01330500, 'Babushkins\'kyi District', '2014-06-09 19:16:55', '2014-06-09 19:16:55'),
	(12, 11, 'route', 48.46450080, 35.04548100, 'Karla Marksa Avenue', '2014-06-09 19:16:55', '2014-06-09 19:16:55'),
	(13, 12, 'street', 48.46452900, 35.04702000, '50', '2014-06-09 19:16:55', '2014-06-09 19:16:55');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;


-- Dumping structure for table spason.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.migrations: ~27 rows (approximately)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`migration`, `batch`) VALUES
	('2012_12_06_225921_migration_cartalyst_sentry_install_users', 1),
	('2012_12_06_225929_migration_cartalyst_sentry_install_groups', 1),
	('2012_12_06_225945_migration_cartalyst_sentry_install_users_groups_pivot', 1),
	('2012_12_06_225988_migration_cartalyst_sentry_install_throttle', 1),
	('2014_05_18_132412_create_locations_table', 2),
	('2014_05_18_132720_create_addresses_table', 2),
	('2014_05_18_132721_create_packages_table', 2),
	('2014_05_18_132722_create_hotels_table', 2),
	('2014_05_18_132724_create_room_prices_table', 2),
	('2014_05_18_132725_create_package_rooms_table', 2),
	('2014_05_18_151933_create_spa_table', 2),
	('2014_05_18_152033_create_tags_table', 2),
	('2014_05_18_152110_create_tag_table', 2),
	('2014_05_18_153919_create_services_table', 2),
	('2014_05_18_165246_create_foregin_keys_add', 2),
	('2014_05_22_032359_add_packages_column', 3),
	('2014_05_22_032521_delete_hotels_column', 4),
	('2014_05_22_120234_create_table_photos', 5),
	('2014_05_26_114911_update_table_spa', 7),
	('2014_05_25_094351_create_treatments_table', 6),
	('2014_05_28_065056_create_table_package_treatments', 8),
	('2014_05_18_132723_create_rooms_table', 9),
	('2014_05_29_102200_update_hotels_table', 10),
	('2014_05_29_112409_update_hotels_table_add_default_photo', 11),
	('2014_06_02_115704_update_packages_table_add_default_photo', 12),
	('2014_06_04_101706_create_table_settings', 13),
	('2014_06_09_182855_create_packages_and_hotels_add_slug', 13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;


-- Dumping structure for table spason.packages
DROP TABLE IF EXISTS `packages`;
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
  PRIMARY KEY (`id`),
  KEY `packages_default_room_id_foreign` (`default_room_id`),
  KEY `packages_hotel_id_foreign` (`hotel_id`),
  CONSTRAINT `packages_default_room_id_foreign` FOREIGN KEY (`default_room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `packages_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.packages: ~3 rows (approximately)
DELETE FROM `packages`;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` (`id`, `name`, `short_description`, `description`, `overnights`, `discount`, `default_room_id`, `last_minute`, `campaign`, `recommended`, `status`, `start_date`, `end_date`, `days_in_advance`, `days_available`, `package_includes`, `created_at`, `updated_at`, `hotel_id`, `default_photo_id`, `slug`) VALUES
	(1, 'package for "test hotel" 1', 'short description', 'loooooooooooooooooooooooooooooong description', 1, 1, NULL, 1, 1, 1, 1, '2014-05-22 00:00:00', '0000-00-00 00:00:00', 3, NULL, '', '2014-05-22 03:46:33', '2014-06-10 08:40:50', 2, 4, 'package-for-test-hotel-1'),
	(2, 'bla', 'asfd', 'asdfasdfasdf', 0, 0, NULL, 0, 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, '', '2014-05-22 03:48:31', '2014-06-10 08:40:50', 4, 6, 'bla'),
	(3, 'Some package on hotel 1', 'it\'s a short description', '<h1>Emicuit corpora ita flamina galeae</h1><p>Sed liquidas. Terra matutinis tepescunt altae tempora pugnabant alta circumdare frigida! Partim ne limitibus orbis flexi effigiem. Quem obsistitur ambitae ignotas sorbentur eodem ut pinus secrevit. Tractu tonitrua quod primaque obliquis regio habentia. Rectumque ardentior eurus illi circumfluus.</p><h2>Rudis peregrinum ponderibus animalia habentia</h2><h3>Habitandae totidem pinus habentia suis</h3><h4>Fecit extendi lumina faecis hanc</h4><p>Animalia amphitrite quae tollere congeriem nec levius. Bene cornua fontes peregrinum. Sive iudicis iudicis flexi iunctarum diffundi. Mundum gentes. Derecti terris non ponderibus circumfuso chaos: sunt fuerant quem. Magni ambitae nullo sorbentur abscidit.</p><ul><li>Et formas nuper recepta nubes austro coercuit exemit capacius quanto passim melioris natura ripis membra effervescere tellus habitabilis di fecit horrifer dissaepserat horrifer</li><li>Temperiemque caesa pulsant solidumque inminet undae proxima chaos: carentem tanta distinxit nitidis zephyro qui ulla postquam aequalis</li><li>Sic undis circumdare ligavit: obsistitur obstabatque deducite locis tonitrua caelumque regat effervescere aliis usu foret carentem mare convexi</li><li>Onerosior dispositam subdita glomeravit fidem rectumque evolvit di crescendo silvas aethera</li><li>Litem eodem tuti aethera semina dixere est orba tractu alto his flamina praeter hominum mortales</li><li>Nubes quem caeca mentisque obliquis utque sive eodem illic occiduo sibi mare fecit</li></ul><p>Grandia vos radiis caelumque mollia aer radiis. Praeter orbis totidem speciem umentia cornua dispositam porrexerat extendi. Rapidisque caelumque quia. Siccis undis. Postquam tractu abscidit sublime altae porrexerat? Duae pluvialibus mundi madescit elementaque. Nisi lapidosos ulla septemque. Natus scythiam manebat pondere quod mutatas tegi.</p><ol><li>Ipsa campos pro militis habentia rapidisque ad super egens nix plagae effigiem locum ne declivia</li><li>Rapidisque ita orbe fabricator natura verba regna orba ultima rudis austro bracchia habitandae rectumque piscibus</li><li>Qui perveniunt lucis sibi circumfuso tanto et piscibus iudicis sine nubibus chaos: dissociata egens pondere aeris posset: aquae carentem</li><li>Sibi nabataeaque ambitae regio duas librata viseret regna nam acervo quae effigiem inter orbis campos ponderibus recessit fert porrexerat caligine moles hominum</li></ol><p>Terram mundum ignea aequalis terras tuba liberioris margine. Motura os iussit concordi utque deus. Mundum perveniunt. Cum natura fluminaque. Surgere manebat campoque dixere tegi alto. Iunctarum emicuit. Tumescere nix corpora animal terrenae caelum flamina dextra sua. Nubibus vindice cetera est fecit pluvialibus illas.</p>', 1, 10, NULL, 1, 1, 0, 1, '2014-05-22 00:00:00', '0000-00-00 00:00:00', 2, NULL, '', '2014-06-02 12:17:40', '2014-06-10 08:40:50', 2, 7, 'some-package-on-hotel-1'),
	(4, 'package package', 'Opifex inclusum coeperunt retinebat summaque natus quisquis lucis? Sinistra sine subsidere locis habitabilis. Umentia tuba prima pronaque. Effervescere agitabilis iunctarum. Solidumque cinxit peregrinum nubes! Temperiemque duris nubibus mundum uno ultima nuper. Erectos lanient pro locoque mare liberioris tollere onerosior campos. Tuti ubi ignea.', '<h1>Adspirate mundo diverso contraria satus</h1><p>Illi undas fronde circumdare persidaque qui nullaque. Amphitrite gravitate piscibus. Humanas lucis derecti traxit occiduo circumfuso lapidosos bene? Ulla tenent cepit iners di origine radiis. Tuti piscibus boreas sui adsiduis formas temperiemque alta finxit.</p><h2>Longo terras mixtam fecit terris</h2><h3>Quicquam coegit emicuit fidem aetas</h3><h4>Terra persidaque meis retinebat nubes</h4><p>Onus membra coegit vindice. Dissaepserat astra caelo fulgura locum onus circumfuso. Temperiemque aurea inmensa membra habitabilis margine. Sic sectamque amphitrite di. Gentes inminet. Chaos: perpetuum capacius quisquis quisque circumfuso. Tuba quem spectent terrenae habitandae crescendo premuntur inclusum coercuit!</p><ul><li>Orbe limitibus effigiem terras phoebe sua caesa sine metusque duae piscibus quisquis tanta caelum praeter numero premuntur temperiemque inclusum chaos: moderantum</li><li>Locum silvas tempora quin vix aethera permisit margine cornua tellure adsiduis</li><li>Forma ab aethera capacius nam fixo frigida nulli altae lapidosos duas possedit quanto circumfluus aera diu erant deorum extendi acervo horrifer septemque humanas fossae</li><li>Campoque cura lucis addidit alta feras quia igni regat spectent ille caeca carentem tonitrua</li><li>Declivia fronde lucis ripis sidera sublime induit speciem sinistra nullo ardentior ignotas</li></ul><p>Illi finxit derecti di cum mentes orbem lanient! Aquae qui porrexerat onus animalia ambitae. Induit deorum. Pluvialibus homini umentia est matutinis descenderat proximus certis persidaque? Erectos nuper figuras subdita innabilis traxit galeae.</p><ol><li>Proximus discordia dextra membra toto subsidere melioris sata congestaque tepescunt terris habentia dicere speciem diffundi nunc subdita pace vultus levius</li><li>Neu recens nunc austro deerat alta regna melioris extendi nisi tenent conversa primaque sic videre ensis invasit ripis nuper rerum traxit moles</li><li>Triones secuit fabricator semina titan praecipites distinxit undas sponte primaque eodem</li></ol><p>Pace tuti recens cornua locavit. Colebat triones locoque fulminibus fossae locavit sive pondus summaque. Quanto ante recepta est di campos proxima. Sidera quarum orba perveniunt librata pugnabant. Caecoque mixta tempora adhuc caeca.</p>', 1, 323, NULL, 1, 1, 1, 1, '2014-05-22 00:00:00', '2014-06-30 00:00:00', 1, NULL, '', '2014-06-09 20:28:20', '2014-06-10 08:40:50', 3, 11, 'package-package');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;


-- Dumping structure for table spason.package_rooms
DROP TABLE IF EXISTS `package_rooms`;
CREATE TABLE IF NOT EXISTS `package_rooms` (
  `package_id` int(10) unsigned DEFAULT NULL,
  `room_id` int(10) unsigned DEFAULT NULL,
  KEY `package_rooms_package_id_foreign` (`package_id`),
  KEY `package_rooms_room_id_foreign` (`room_id`),
  CONSTRAINT `package_rooms_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `package_rooms_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.package_rooms: ~2 rows (approximately)
DELETE FROM `package_rooms`;
/*!40000 ALTER TABLE `package_rooms` DISABLE KEYS */;
INSERT INTO `package_rooms` (`package_id`, `room_id`) VALUES
	(1, 5),
	(3, 6),
	(4, 7);
/*!40000 ALTER TABLE `package_rooms` ENABLE KEYS */;


-- Dumping structure for table spason.package_treatments
DROP TABLE IF EXISTS `package_treatments`;
CREATE TABLE IF NOT EXISTS `package_treatments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `treatment_id` int(10) unsigned NOT NULL,
  `package_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.package_treatments: ~3 rows (approximately)
DELETE FROM `package_treatments`;
/*!40000 ALTER TABLE `package_treatments` DISABLE KEYS */;
INSERT INTO `package_treatments` (`id`, `treatment_id`, `package_id`, `status`, `created_at`, `updated_at`) VALUES
	(4, 3, 1, 1, '2014-05-29 06:48:45', '2014-05-29 06:48:45'),
	(5, 4, 1, 1, '2014-05-29 07:14:35', '2014-05-29 07:14:35'),
	(7, 6, 3, 1, '2014-06-02 13:40:04', '2014-06-02 13:40:04'),
	(8, 2, 4, 1, '2014-06-09 21:26:26', '2014-06-09 21:26:26'),
	(9, 1, 4, 1, '2014-06-09 21:26:26', '2014-06-09 21:26:26');
/*!40000 ALTER TABLE `package_treatments` ENABLE KEYS */;


-- Dumping structure for table spason.photos
DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `content_type` enum('hotels','packages','rooms','services') COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.photos: ~8 rows (approximately)
DELETE FROM `photos`;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
INSERT INTO `photos` (`id`, `content_id`, `content_type`, `file`, `status`, `created_at`, `updated_at`) VALUES
	(2, 4, 'hotels', 'uploads/hotels/REDyY3cVA4QE6ePDTAqN.jpg', 1, '2014-05-22 13:45:12', '2014-05-22 14:52:18'),
	(4, 1, 'packages', 'uploads/packages/V2yU9wfWus5rAkeTjS1K.jpg', 1, '2014-05-22 14:58:14', '2014-05-22 14:58:14'),
	(5, 4, 'hotels', 'uploads/hotels/KyXlg9E7pFRcoKRVVX6a.jpg', 1, '2014-05-29 11:14:49', '2014-05-29 11:14:49'),
	(6, 2, 'packages', 'uploads/packages/wGPYAi23zOz56owfDqVy.jpg', 1, '2014-06-02 12:10:12', '2014-06-02 12:10:12'),
	(7, 3, 'packages', 'uploads/packages/pAHY8zCKqkeV2e38f7Gk.jpg', 1, '2014-06-02 12:20:20', '2014-06-02 12:20:20'),
	(8, 3, 'packages', 'uploads/packages/os9944HVoL6Wb8k5ELup.jpg', 1, '2014-06-02 12:20:47', '2014-06-02 12:20:47'),
	(9, 3, 'hotels', 'uploads/hotels/8mmnkqdxOVatF2ezoICn.jpg', 1, '2014-06-05 04:17:12', '2014-06-05 04:17:12'),
	(10, 2, 'hotels', 'uploads/hotels/civyqEIA1pDroZjm7p4b.jpg', 1, '2014-06-05 04:17:46', '2014-06-05 04:17:46'),
	(11, 4, 'packages', 'uploads/packages/FPxCqFQOvNdMnYGcQzzL.png', 1, '2014-06-09 21:27:08', '2014-06-09 21:27:08');
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;


-- Dumping structure for table spason.rooms
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `max_residents` int(10) unsigned DEFAULT NULL,
  `hotel_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.rooms: ~2 rows (approximately)
DELETE FROM `rooms`;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` (`id`, `name`, `description`, `max_residents`, `hotel_id`, `status`, `created_at`, `updated_at`) VALUES
	(5, 'single room', 'test single room in test hotel 2', 1, 2, 1, '2014-05-29 09:29:00', '2014-06-03 14:30:08'),
	(6, 'room room', 'text', 3, 2, 1, '2014-05-29 09:46:09', '2014-06-03 14:30:17'),
	(7, 'standart room', 'standart songle room', 1, 3, 1, '2014-06-09 20:30:02', '2014-06-09 20:30:12');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;


-- Dumping structure for table spason.room_prices
DROP TABLE IF EXISTS `room_prices`;
CREATE TABLE IF NOT EXISTS `room_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned NOT NULL,
  `price` double(10,2) NOT NULL,
  `weekday` enum('mon','tue','wed','thu','fri','sat','sun') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_prices_room_id_foreign` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.room_prices: ~14 rows (approximately)
DELETE FROM `room_prices`;
/*!40000 ALTER TABLE `room_prices` DISABLE KEYS */;
INSERT INTO `room_prices` (`id`, `room_id`, `price`, `weekday`) VALUES
	(1, 5, 201.00, 'mon'),
	(2, 5, 251.00, 'tue'),
	(3, 5, 201.00, 'wed'),
	(4, 5, 231.00, 'thu'),
	(5, 5, 151.00, 'fri'),
	(6, 5, 221.00, 'sat'),
	(7, 5, 201.00, 'sun'),
	(8, 6, 1.00, 'mon'),
	(9, 6, 2.00, 'tue'),
	(10, 6, 3.00, 'wed'),
	(11, 6, 0.00, 'thu'),
	(12, 6, 4.00, 'fri'),
	(13, 6, 5.00, 'sat'),
	(14, 6, 0.00, 'sun'),
	(15, 7, 100.00, 'mon'),
	(16, 7, 100.00, 'tue'),
	(17, 7, 100.00, 'wed'),
	(18, 7, 120.00, 'thu'),
	(19, 7, 130.00, 'fri'),
	(20, 7, 120.00, 'sat'),
	(21, 7, 0.00, 'sun');
/*!40000 ALTER TABLE `room_prices` ENABLE KEYS */;


-- Dumping structure for table spason.services
DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `content_type` enum('hotel','spa') COLLATE utf8_unicode_ci NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.services: ~0 rows (approximately)
DELETE FROM `services`;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;


-- Dumping structure for table spason.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `settings_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.settings: ~0 rows (approximately)
DELETE FROM `settings`;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`name`, `value`) VALUES
	('google_analytics', '<!-- Google Tag Manager -->\r\n<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-M72G95"\r\nheight="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>\r\n<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':\r\nnew Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],\r\nj=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=\r\n\'//www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);\r\n})(window,document,\'script\',\'dataLayer\',\'GTM-M72G95\');</script>\r\n<!-- End Google Tag Manager -->'),
	('order_email', 'rchy@isd.dp.ua');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;


-- Dumping structure for table spason.spas
DROP TABLE IF EXISTS `spas`;
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
  KEY `spa_hotel_id_foreign` (`hotel_id`),
  CONSTRAINT `spa_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.spas: ~3 rows (approximately)
DELETE FROM `spas`;
/*!40000 ALTER TABLE `spas` DISABLE KEYS */;
INSERT INTO `spas` (`id`, `name`, `description`, `hotel_id`, `package_id`, `status`, `created_at`, `updated_at`) VALUES
	(2, 'Midnight spa', 'massage for your <h1>back</h1>', 3, 0, 1, '2014-05-27 14:34:11', '2014-05-30 05:57:47'),
	(3, 'super spaads', 'asdfasdfasdf', 2, 0, 1, '2014-05-28 11:42:27', '2014-05-28 12:49:11'),
	(4, 'king spa', 'just for kings', 4, 0, 1, '2014-06-05 04:23:06', '2014-06-05 04:23:11');
/*!40000 ALTER TABLE `spas` ENABLE KEYS */;


-- Dumping structure for table spason.tag
DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `tag_id_foreign` FOREIGN KEY (`id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.tag: ~0 rows (approximately)
DELETE FROM `tag`;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;


-- Dumping structure for table spason.tags
DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `content_type` enum('package','hotel') COLLATE utf8_unicode_ci NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.tags: ~0 rows (approximately)
DELETE FROM `tags`;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;


-- Dumping structure for table spason.throttle
DROP TABLE IF EXISTS `throttle`;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.throttle: ~2 rows (approximately)
DELETE FROM `throttle`;
/*!40000 ALTER TABLE `throttle` DISABLE KEYS */;
INSERT INTO `throttle` (`id`, `user_id`, `ip_address`, `attempts`, `suspended`, `banned`, `last_attempt_at`, `suspended_at`, `banned_at`) VALUES
	(3, 2, '127.0.0.1', 2, 0, 0, '2014-05-18 10:32:18', NULL, NULL),
	(4, 1, NULL, 0, 0, 0, NULL, NULL, NULL);
/*!40000 ALTER TABLE `throttle` ENABLE KEYS */;


-- Dumping structure for table spason.treatments
DROP TABLE IF EXISTS `treatments`;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.treatments: ~6 rows (approximately)
DELETE FROM `treatments`;
/*!40000 ALTER TABLE `treatments` DISABLE KEYS */;
INSERT INTO `treatments` (`id`, `name`, `description`, `persons`, `price`, `duration`, `default_photo`, `spa_id`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Romantic massage', 'Super mega great massage', 1, 400.00, 30, NULL, 2, 1, '2014-05-28 06:22:30', '2014-05-28 06:22:30'),
	(2, 'Face cleaning', 'Effective face cleaning', 1, 100.00, 10, NULL, 2, 1, '2014-05-28 06:30:10', '2014-05-28 06:30:10'),
	(3, 'body massage', 'full body massage', 1, 700.00, 40, NULL, 3, 1, '2014-05-28 12:43:54', '2014-05-28 12:43:54'),
	(4, 'fitness', 'fitness fitness', 2, 301.00, 90, NULL, 3, 1, '2014-05-28 12:45:05', '2014-05-29 14:13:38'),
	(6, 'fish spa', 'super fish massage for your foots', 1, 123.00, 15, NULL, 3, 1, '2014-06-02 12:52:55', '2014-06-02 12:52:55'),
	(7, 'healing', 'healig treatment description', 2, 450.00, 10, NULL, 4, 1, '2014-06-05 04:24:26', '2014-06-05 04:24:26');
/*!40000 ALTER TABLE `treatments` ENABLE KEYS */;


-- Dumping structure for table spason.users
DROP TABLE IF EXISTS `users`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.users: ~2 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `activated`, `activation_code`, `activated_at`, `last_login`, `persist_code`, `reset_password_code`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
	(1, 'admin@admin.com', '$2y$10$MthYkOnDSTFpDf0uiVAPxe0XjCslx2xZjpd0tqgeYmVPIzTGm2AmK', NULL, 1, NULL, NULL, '2014-06-10 07:41:41', '$2y$10$zkdimHZkV1Xx3MejKDe4zevhVyUZxKo.EyaG2YFC7Ir5DXKV26ZXy', NULL, NULL, NULL, '2014-05-16 16:20:17', '2014-06-10 07:41:41'),
	(2, 'user@user.com', '$2y$10$g3bw1/1BPIl7cjywZaA01uCV.eHP.T0jJBzRHqTlA7jJEObrwVwUa', NULL, 1, NULL, NULL, '2014-05-18 11:28:54', '$2y$10$H28ODMFTLob38qjrcQxBausUi5D6pK54sBeLfZ3qYbDEQDBAoQ9oq', NULL, NULL, NULL, '2014-05-16 16:20:17', '2014-05-18 11:28:54');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table spason.users_groups
DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE IF NOT EXISTS `users_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table spason.users_groups: ~3 rows (approximately)
DELETE FROM `users_groups`;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` (`user_id`, `group_id`) VALUES
	(1, 1),
	(1, 2),
	(2, 1);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
