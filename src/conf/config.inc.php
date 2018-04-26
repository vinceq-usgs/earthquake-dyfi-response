<?php

date_default_timezone_set('UTC');

$CONFIG_DEFAULTS = array(
  'ARCGIS_CLIENT_ID'     => null,
  'ARCGIS_CLIENT_SECRET' => null,
  'BACKEND_SERVERS' => 'backend',
  'BACKUP_DIR' => '/backup',
  'SERVER_SHORTNAME' => $_ENV['HOSTNAME'],
  'WRITE_DIR' => '/data'
);

$CONFIG = array_merge($CONFIG_DEFAULTS, $_ENV);
