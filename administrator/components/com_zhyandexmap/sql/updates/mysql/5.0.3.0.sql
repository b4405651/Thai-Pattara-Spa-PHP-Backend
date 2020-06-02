ALTER TABLE `#__zhyandexmaps_maps` ADD `fullscreencontrol` tinyint(1) NOT NULL default '1';
ALTER TABLE `#__zhyandexmaps_maps` ADD `fullscreenpos` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__zhyandexmaps_maps` ADD `fullscreenposofsx` int(5) NOT NULL default '0';
ALTER TABLE `#__zhyandexmaps_maps` ADD `fullscreenposofsy` int(5) NOT NULL default '0';

ALTER TABLE `#__zhyandexmaps_maps` ADD `rulercontrol` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__zhyandexmaps_maps` ADD `rulerpos` tinyint(1) NOT NULL default '0';
ALTER TABLE `#__zhyandexmaps_maps` ADD `rulerposofsx` int(5) NOT NULL default '0';
ALTER TABLE `#__zhyandexmaps_maps` ADD `rulerposofsy` int(5) NOT NULL default '0';

ALTER TABLE `#__zhyandexmaps_maps` ADD `markerlistposofsx` int(5) NOT NULL default '0';
ALTER TABLE `#__zhyandexmaps_maps` ADD `markerlistposofsy` int(5) NOT NULL default '0';