<?php 

namespace app\core;

use app\core\View;

abstract class Controller {
	
	public $route;
	public $view;

	/*
		$route = [
			  'controller' => 'main',
				'action' => 'index'
		]
	*/
	function __construct($route) {
		// route - отвечает за то, какой из контроллеров использовать(находяться в папке controllers).
		// View - отвечает за отображение страницы.
		$this->route = $route;
		$this->view = new View($route);
	}
}


?>