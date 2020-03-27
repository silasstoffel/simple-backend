CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `can_delete` smallint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user` ADD UNIQUE KEY `name_unique` (`email`);

-- Default insert:
INSERT INTO `user` (
  `name`,
  `email`,
  `password_hash`,
  `mobile_phone`,
  `created_at`,
  `updated_at`,
  `active`,
  `can_delete`
) VALUES (
  'Mestre TagTec',
  'mestre@tagtec.com.br',
  '$2y$10$uhLbfnHJzz9mz5blzIZ3dOfTKIRewFXiO3YuNZ.4V6omuKFvTHo8i',
  '27996354103',
  '2020-03-26 22:19:52',
  '2020-03-26 22:19:52',
   1,
   0
);
