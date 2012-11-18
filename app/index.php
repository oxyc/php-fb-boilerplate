<?php

require 'vendor/autoload.php';
$config = require 'config.php';

$twigView = new \Slim\Extras\Views\Twig();
$twigView->setTemplatesDirectory($config['twig']['templates']);
$twigView->twigOptions = array(
  'cache' => DEBUG ? FALSE : $config['twig']['cache'],
);

$slim = new \Slim\Slim(array(
  'mode' => DEBUG ? 'development' : 'production',
  'view' => $twigView,
));
$slim->config($config);

$app = new App($slim);
$app->authFacebook(new Facebook(array(
  'appId' => $app->config('fb.app_id'),
  'secret' => $app->config('fb.secret'),
  'cookie' => TRUE,
)));
$app->run();
