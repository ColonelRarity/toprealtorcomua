DROP TABLE IF EXISTS `modx_a_mailer`;
CREATE TABLE `modx_a_mailer` (
  `tpl_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mailed` tinyint(4) NOT NULL DEFAULT '0',
  UNIQUE KEY `tpl_id_user_id` (`tpl_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `modx_a_mailer_templates`;
CREATE TABLE `modx_a_mailer_templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `template_subject` varchar(512) NOT NULL,
  `template_post` text NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `modx_a_mailer_users`;
CREATE TABLE `modx_a_mailer_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) NOT NULL,
  `user_email` varchar(64) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `modx_site_modules` (`id`, `name`, `description`, `editor_type`, `disabled`, `category`, `wrap`, `locked`, `icon`, `enable_resource`, `resourcefile`, `createdon`, `editedon`, `guid`, `enable_sharedparams`, `properties`, `modulecode`) VALUES
(null, 'Рассылки', '', 0,  0,  777,  1,  0,  '', 0,  '', 0,  0,  'b1174e2fa5e64516c27d922bafb60adf', 0,  '', '/*\n    cron файл лежит рядом в папке модуля\n*/\nrequire MODX_BASE_PATH . \"assets/modules/mailer/index.php\"; '),

INSERT INTO `modx_site_plugins` (`id`, `name`, `description`, `editor_type`, `category`, `cache_type`, `plugincode`, `locked`, `properties`, `disabled`, `moduleguid`) VALUES
(null,  'Mailer', '', 0,  0,  0,  '/*\r\n    Плагин подписки/отписки на рассылку\r\n*/\r\nswitch ($modx->event->name) {\r\n    case \'OnWebPageInit\':\r\n    \r\n        if (isset($_GET[\'unsubscribe\']) && $_SESSION[\'hash\'][\'unsubscribe\'] != md5(serialize($_GET[\'unsubscribe\']))) {\r\n            $modx->db->query(\'delete from `modx_a_mailer_users` where user_email = \"\'.$modx->db->escape($_GET[\'unsubscribe\']).\'\"\');\r\n            $_SESSION[\'hash\'][\'unsubscribe\'] = md5(serialize($_GET[\'unsubscribe\']));\r\n            $js = \'alert(\"Вы успешно отписались от рассылки!\");\';\r\n        }\r\n        if (count($_POST[\'subscribe\']) > 0 && $_SESSION[\'hash\'][\'subscribe\'] != md5(serialize($_POST[\'subscribe\'])) && filter_var($_POST[\'subscribe\'][\'email\'], FILTER_VALIDATE_EMAIL)) {\r\n            $modx->db->query(\'insert into `modx_a_mailer_users` set user_email = \"\'.$modx->db->escape($_POST[\'subscribe\'][\'email\']).\'\" on duplicate key update user_email = user_email\');\r\n            $_SESSION[\'hash\'][\'subscribe\'] = md5(serialize($_POST[\'subscribe\']));\r\n            $js = \'alert(\"Вы были успешно подписаны на рассылку!\");\';\r\n        } elseif (count($_POST[\'subscribe\']) > 0 && !filter_var($_POST[\'subscribe\'][\'email\'], FILTER_VALIDATE_EMAIL))\r\n            $js = \'alert(\"Вы уже подаисаны на рассылку.\")\';\r\n        if (isset($js)) $modx->regClientScript($js);\r\n    break;\r\n}',  0,  '', 0,  ' ');

INSERT INTO `modx_site_plugin_events` (`pluginid`, `evtid`, `priority`) VALUES
((SELECT LAST_INSERT_ID()),  90, 0);

INSERT INTO `modx_system_settings` (`setting_name`, `setting_value`) VALUES
('smtp_limit',  '10'),
('smtp_login',  'art.digital@artjoker.ua'),
('smtp_name', 'Тестовый SMTP аккаунт Artjoker'),
('smtp_pass', 'y5krl9m5'),
('smtp_port', '25'),
('smtp_server', 'mail.artjoker.ua'),
('smtp_ssl',  '0'),
('smtp_tls',  '0');