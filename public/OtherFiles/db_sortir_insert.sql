
INSERT INTO `city` (`id`, `name`, `postal_code`) VALUES(7, 'Nantes', '44000');
INSERT INTO `city` (`id`, `name`, `postal_code`) VALUES(8, 'Saint-Herblain', '44800');
INSERT INTO `city` (`id`, `name`, `postal_code`) VALUES(9, 'Noirmoutier-en-l\'Île', '85330');
INSERT INTO `city` (`id`, `name`, `postal_code`) VALUES(10, 'Rezé', '44400');

INSERT INTO `event` (`id`, `creator_id`, `site_id`, `place_id`, `label`, `date_start`, `duration`, `date_inscription_end`, `max_members`, `description`, `cancel_txt`, `state`) VALUES(11, 12, 40, 10, 'Boire une bière', '2020-02-23 19:00:00', 60, '2020-02-22 14:00:00', 20, 'boire une bière ou plus', NULL, 'Ouverte');
INSERT INTO `event` (`id`, `creator_id`, `site_id`, `place_id`, `label`, `date_start`, `duration`, `date_inscription_end`, `max_members`, `description`, `cancel_txt`, `state`) VALUES(12, 16, 40, 11, 'Soirée ciné', '2020-02-28 20:00:00', 120, '2020-02-27 10:00:00', 10, 'soirée ciné pour les fan de Star Wars', NULL, 'EnCreation');
INSERT INTO `event` (`id`, `creator_id`, `site_id`, `place_id`, `label`, `date_start`, `duration`, `date_inscription_end`, `max_members`, `description`, `cancel_txt`, `state`) VALUES(13, 17, 40, 12, 'Théatre d\'impro', '2020-02-07 20:30:00', 90, '2020-01-16 15:00:00', 15, 'Venez me voir en impro !!! :)', NULL, 'Passee');
INSERT INTO `event` (`id`, `creator_id`, `site_id`, `place_id`, `label`, `date_start`, `duration`, `date_inscription_end`, `max_members`, `description`, `cancel_txt`, `state`) VALUES(14, 14, 40, 13, 'Expo Musée des Arts', '2020-03-29 10:00:00', 60, '2020-03-27 10:00:00', 10, 'Nouvelle expo au Musée des Arts', NULL, 'Ouverte');
INSERT INTO `event` (`id`, `creator_id`, `site_id`, `place_id`, `label`, `date_start`, `duration`, `date_inscription_end`, `max_members`, `description`, `cancel_txt`, `state`) VALUES(15, 18, 40, 14, 'Sortie à la mer', '2020-06-27 17:00:00', 240, '2020-06-20 18:00:00', 15, 'petite sortie à la plage si il fait beau', 'j\'ai poney ce jour-là, on remet ça à plus tard !', 'Annulee');
INSERT INTO `event` (`id`, `creator_id`, `site_id`, `place_id`, `label`, `date_start`, `duration`, `date_inscription_end`, `max_members`, `description`, `cancel_txt`, `state`) VALUES(16, 19, 40, 15, 'Prendre un verre', '2020-01-15 18:00:00', 150, '2020-01-08 10:00:00', 25, 'prendre un verre pour fêter la fin des cours d\'UML', NULL, 'Archivee');
INSERT INTO `event` (`id`, `creator_id`, `site_id`, `place_id`, `label`, `date_start`, `duration`, `date_inscription_end`, `max_members`, `description`, `cancel_txt`, `state`) VALUES(17, 12, 40, 16, 'Chez Anne-Laure', '2020-02-23 14:35:00', 30, '2020-02-22 14:35:00', 154, 'la teuf!', NULL, 'EnCreation');

INSERT INTO `file` (`id`, `user_id`, `path`, `name`, `mime_type`, `size`, `public_path`, `site_id`) VALUES(20, 12, '', '1200px-Mona_Lisa,_by_Leonardo_da_Vinci,_from_C2RMF_retouched.jpg', 'image/jpeg', '853892', '/uploads/1200px-Mona_Lisa,_by_Leonardo_da_Vinci,_from_C2RMF_retouched.jpg', NULL);
INSERT INTO `file` (`id`, `user_id`, `path`, `name`, `mime_type`, `size`, `public_path`, `site_id`) VALUES(21, 18, '', 'pngwave.png', 'image/png', '40426', '/uploads/pngwave.png', NULL);
INSERT INTO `file` (`id`, `user_id`, `path`, `name`, `mime_type`, `size`, `public_path`, `site_id`) VALUES(22, 16, '', 'yoda.jpg', 'image/jpeg', '151619', '/uploads/yoda.jpg', NULL);
INSERT INTO `file` (`id`, `user_id`, `path`, `name`, `mime_type`, `size`, `public_path`, `site_id`) VALUES(24, 19, '', 'la-reine-des-neiges0.jpg', 'image/jpeg', '21713', '/uploads/la-reine-des-neiges0.jpg', NULL);
INSERT INTO `file` (`id`, `user_id`, `path`, `name`, `mime_type`, `size`, `public_path`, `site_id`) VALUES(25, 14, '', 'femme.png', 'image/png', '31421', '/uploads/femme.png', NULL);

INSERT INTO `inscription` (`id`, `user_id`, `event_id`) VALUES(6, 19, 11);
INSERT INTO `inscription` (`id`, `user_id`, `event_id`) VALUES(7, 12, 11);
INSERT INTO `inscription` (`id`, `user_id`, `event_id`) VALUES(8, 18, 11);

INSERT INTO `place` (`id`, `city_id`, `label`, `address`, `latitude`, `longitude`) VALUES(10, 7, 'Délirium Café', '19 Allée Baco', 47.2133278, -1.5503361);
INSERT INTO `place` (`id`, `city_id`, `label`, `address`, `latitude`, `longitude`) VALUES(11, 8, 'Pathé Atlantis', '8 Allée la Pérouse', 47.2223701, -1.6287139);
INSERT INTO `place` (`id`, `city_id`, `label`, `address`, `latitude`, `longitude`) VALUES(12, 7, 'Ligue Improvisation Nantaise ', '1 Rue Jules Brechoir', 47.2253153, -1.5225116);
INSERT INTO `place` (`id`, `city_id`, `label`, `address`, `latitude`, `longitude`) VALUES(13, 7, 'Musée d\'Arts de Nantes', '10 Rue Georges Clemenceau', 47.2195345, -1.5474903);
INSERT INTO `place` (`id`, `city_id`, `label`, `address`, `latitude`, `longitude`) VALUES(14, 9, 'Plage des Dame à Noirmoutier-en-l\'Île', '6 Allée du Tambourin', 47.0092657, -2.2201687);
INSERT INTO `place` (`id`, `city_id`, `label`, `address`, `latitude`, `longitude`) VALUES(15, 7, 'Atomic\'s Café', '6 Cours Olivier de Clisson', 47.2130433, -1.5547859);
INSERT INTO `place` (`id`, `city_id`, `label`, `address`, `latitude`, `longitude`) VALUES(16, 10, 'Chez Anne-Laure', '2 Rue du Pinier', 47.1820637, -1.5479646);

INSERT INTO `site` (`id`, `label`) VALUES(40, 'ENI NANTES');
INSERT INTO `site` (`id`, `label`) VALUES(41, 'ENI QUIMPER');
INSERT INTO `site` (`id`, `label`) VALUES(42, 'ENI RENNES');
INSERT INTO `site` (`id`, `label`) VALUES(44, 'ENI NIORT');

INSERT INTO `status` (`id`, `label`) VALUES(1, 'En création');
INSERT INTO `status` (`id`, `label`) VALUES(2, 'Ouverte');
INSERT INTO `status` (`id`, `label`) VALUES(3, 'Clôturée');
INSERT INTO `status` (`id`, `label`) VALUES(4, 'Activité en cours');
INSERT INTO `status` (`id`, `label`) VALUES(5, 'Passée');
INSERT INTO `status` (`id`, `label`) VALUES(6, 'Annulée');

INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(12, 40, 'blaz2019', 'LAZ', 'Baptiste', 2019, NULL, 'baptiste.laz2019@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$cXpmZ0lTSXBQcDViM0lZRg$IzKo0T65mSaJS0r1yOxy0TOTEohTarbvolrYpLKqqkA', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', 1, 0, NULL, NULL);
INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(13, 40, 'Ls', 'CORNU', 'Lydia', 2019, NULL, 'lydia.cornu2019@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$MUI2OHJpL3pab1RLaFJCUQ$0sz6VwzUZtFKsGAeDCM/7r6Om8/6GKTUfWJ34HcI+0U', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', 1, 0, NULL, NULL);
INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(14, 40, 'alecomte2019', 'LECOMTE', 'Anne-Laure', 2019, NULL, 'anne-laure.lecomte2019@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$cmdvb0p3akQ2MUl0dlFDTA$ad1KdBiLD4BZPWuQoC0mF9MTai7VcVamfRbcD4Jhl4Q', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', 1, 0, NULL, NULL);
INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(16, 40, 'panciaux2019', 'ANCIAUX', 'Priscilla', 2019, NULL, 'priscilla.anciaux2019@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$by45RjRWQU45UVZSWmFEWA$FeJkjBBIvxFCfNRWvrqCLKCl8MQYrGRl+d0LDLyWmbQ', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 1, 0, NULL, NULL);
INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(17, 40, 'apacaud2019', 'PACAUD', 'Alan', 2019, NULL, 'alan.pacaud2019@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$QUd2Ukwxb05PdmZkMjlaMQ$tibfUzQsl8ZltQ0DcfbkcTsVDYkZiHVFAAT+7afzsdE', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 1, NULL, NULL, NULL);
INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(18, 40, 'mbaudin2019', 'BAUDIN', 'Marine', 2019, NULL, 'marine.baudin2019@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$YUdEMFRCR3IyYURWODNvcg$IVeLg16aqKkYW+4vxUVZpOtLD5DJpiswZMjTv9EKhkA', 'a:1:{i:0;s:16:\"ROLE_DEACTIVATED\";}', 0, 0, NULL, NULL);
INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(19, 40, 'polliveaud2019', 'OLLIVEAUD', 'Pauline', 2019, NULL, 'pauline.olliveaud2019@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$UC9iVm8yUGhzSUR1bXlFbA$EzsnperXcRYnWl8G0ToCz1KRa+vGdR4Gb2sMNJmrZvU', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 1, 0, NULL, NULL);
INSERT INTO `user` (`id`, `site_id`, `username`, `name`, `firstname`, `inscription_year`, `phone`, `email`, `password`, `roles`, `active`, `show_phone`, `erased`, `password_token`) VALUES(27, 42, 'mtruc2020', 'TRUC', 'Machin', 2020, NULL, 'machin.truc2020@campus-eni.fr', '$argon2i$v=19$m=65536,t=4,p=1$Z1puemQvejdyWWEuQXBRWg$09txVzm6IN4WEjkQWBhTFDEZNdTv0qltdWbtzJ3A8yU', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 1, NULL, NULL, NULL);

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FK_3BAE0AA761220EA6` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA76BF700BD` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7DA6A219` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);

--
-- Contraintes pour la table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `FK_8C9F3610A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8C9F3610F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `FK_5E90F6D671F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_5E90F6D6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `place`
--
ALTER TABLE `place`
  ADD CONSTRAINT `FK_741D53CD8BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);
COMMIT;
