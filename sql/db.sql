CREATE TABLE IF NOT EXISTS `mc_counter` (
  `id_counter` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `number_counter` int(11) DEFAULT 0,
  `order_counter` smallint(3) unsigned NOT NULL DEFAULT 0,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_counter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_counter_content` (
  `id_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_counter` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `title_counter` varchar(130) NOT NULL,
  `desc_counter` text DEFAULT NULL,
  `url_counter` varchar(200) DEFAULT NULL,
  `blank_counter` smallint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_content`),
  KEY `id_adv` (`id_counter`,`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_counter_content`
  ADD CONSTRAINT `mc_counter_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_counter_content_ibfk_1` FOREIGN KEY (`id_counter`) REFERENCES `mc_counter` (`id_counter`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'counter';