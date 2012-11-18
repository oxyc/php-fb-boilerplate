<?php

define('DEBUG', TRUE);

return array(
  'debug' => DEBUG,
  'twig' => array(
    'templates' => 'templates', 
    'cache' => __DIR__ . '/cache',
  ),
  'site' => array(
    'title' => 'title',
    'description' => 'description',
    'ga' => 'googleanalaytics',
  ),
  'fb' => array(
    'app_id' => 'app_id',
    'secret' => 'secret',
    'page_id' => 'page_id',
    'admins' => 'admins',
    'app_url' => 'http://www.facebook.com/',
    'page_url' => 'http://www.facebook.com/',
    'session' => NULL,
  ),
);
