<?php

namespace app\core;

use app\controllers;

/**
 * Роутер отвечает за переадресацию и обработку введенных адресов в строку браузера
 */
class Router {

  protected $routes = [];
  protected $params = [];

  function __construct() {
    $arr = require ROOT_PATH.'/app/core/routes.php';
    foreach($arr as $key => $val) {
      $this->add($key, $val);
    }
  }


  public function add($route, $params) {
    $route = '#^'.$route.'$#';
    $this->routes[$route] = $params;
  }

  public function match() {
    $url = trim($_SERVER['REQUEST_URI'], '/'); //Удаляет пробелы из начала и конца строки
    $url = explode('?', $url)[0];

    foreach ($this->routes as $route => $params) {
      // print_r($matches);
      if (preg_match($route, $url, $matches)) {
        // 'controller', 'action'
        $this->params = $params;
        // print_r($matches);
        return true;
      }
    }
    return false;
  }

  public function run() {

    if ($this->match()) {
      // namespace defined in ****Controller + Controller name;
      $path = "app\\controllers\\".ucfirst($this->params['controller'])."Controller";
      if (class_exists($path)) {
        $action = $this->params['action'].'Action';

        if (method_exists($path, $action)) {
          $controller = new $path($this->params);
          $controller->$action();
        } else {
          View::errorCode(404);
          // echo "action не найден : ".$action;
        }
      } else {
        View::errorCode(404);
        // echo "Контроллер не найден : ".$path;
      }
    } else {
      View::errorCode(404);
      // echo "Маршрут не найдет";
    }
  }

}

?>