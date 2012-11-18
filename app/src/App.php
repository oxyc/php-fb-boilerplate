<?php

class App {
  public $slim;
  public $routes;
  public $pageConfig;

  private $pageScopes = array('site', 'fb');

  public function __construct(\Slim\Slim $slim) {
    $this->slim = $slim;
    $this->init();
  }

  public function init() {
    $this->setRoutes();
    $this->setDefaultPageConfig();
  }

  public function setRoutes() {
    $self = $this;

    $this->slim->get('/', function() use ($self) {
      return $self->render('index.html', array(
        'site.title' => 'WELCOME!'
      ));
    });

    $this->slim->get('/page/:page', function($page) use ($self) {
      return $self->render("pages/$page.html");
    });
  }

  public function authFacebook(Facebook $fb) {
    $this->fb = $fb;
    $req = $this->fb->getSignedRequest();
    $this->config('fb.session', array(
      'page_id' => $req['page']['id'],
      'page_admin' => $req['page']['admin'],
      'is_liked' => DEBUG ? TRUE : $req['page']['liked'],
      'country' => $req['user']['country'],
      'locale' => $req['user']['locale'],
    ));
  }

  public function config($property, $value = NULL) {
    if (strpos($property, '.')) {
      list($scope, $property) = explode('.', $args[0]);

      $current = call_user_func(array(&$this->slim, 'config'), $scope);

      if (isset($value)) {
        $value = array_merge($current, array($property => $value));
        return call_user_func(array(&$this->slim, 'config'), $value);
      } else {
        return $current[$property];
      }
    }
    return call_user_func_array(array(&$this->slim, 'config'), func_get_args());
  }

  public function setDefaultPageConfig() {
    foreach ($this->pageScopes as $scope) {
      $this->pageConfig[$scope] = $this->config($scope);
    }
  }

  public function render($template, $args = array()) {
    $vars = $this->pageConfig;
    foreach ($args as $arg => $value) {
      if (strpos($arg, '.')) {
        list($scope, $property) = explode('.', $arg);
        $vars[$scope][$property] = $value;
      } else {
        $vars[$args] = $value;
      }
    }
    call_user_func(array(&$this->slim, 'render'), $template, $vars);
  }

  public function run() {
    $this->slim->run();
  }
}
