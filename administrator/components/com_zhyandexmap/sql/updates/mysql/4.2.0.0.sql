ALTER TABLE `#__zhyandexmaps_markers` ADD `access` int(11) NOT NULL DEFAULT '1';

ALTER TABLE `#__zhyandexmaps_maps` ADD INDEX `idx_catid` (`catid`);

ALTER TABLE `#__zhyandexmaps_markers` ADD INDEX `idx_catid` (`catid`);
ALTER TABLE `#__zhyandexmaps_markers` ADD INDEX `idx_mapid` (`mapid`);
ALTER TABLE `#__zhyandexmaps_markers` ADD INDEX `idx_markergroup` (`markergroup`);
ALTER TABLE `#__zhyandexmaps_markers` ADD INDEX `idx_createdbyuser` (`createdbyuser`);
ALTER TABLE `#__zhyandexmaps_markers` ADD INDEX `idx_access` (`access`);

ALTER TABLE `#__zhyandexmaps_routers` ADD INDEX `idx_catid` (`catid`);
ALTER TABLE `#__zhyandexmaps_routers` ADD INDEX `idx_mapid` (`mapid`);

ALTER TABLE `#__zhyandexmaps_paths` ADD INDEX `idx_catid` (`catid`);
ALTER TABLE `#__zhyandexmaps_paths` ADD INDEX `idx_mapid` (`mapid`);

ALTER TABLE `#__zhyandexmaps_markergroups` ADD INDEX `idx_catid` (`catid`);

ALTER TABLE `#__zhyandexmaps_maptypes` ADD INDEX `idx_catid` (`catid`);
