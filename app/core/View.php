<?php 

namespace app\core;


// Класс для отображения нашей страницы
// Хранит в себе:
// 	1) layout - путь к файлу layout
// 	2) path - путь к файлу с содержимым тега body
// 	3) route - котроллер отображения данного содержимго и action
class View {
	
	// путь
	public $route;

	// Вид - все что находиться в body.
	public $path;
	// Шаблон - наш html код(все что входит в head и html, но не body).
	public $layout = 'default';

	function __construct($route) {
		$this->route = $route;
		// строка, по фатку путь к нашей странице на хосте
		$this->path = $route['controller'].'/'.$route['action'];


		// echo "<br>".$this->path."<br>";
	}


	public function reader($title, $vars = []) {
		// Извлекаем массив в переменные.
		if (isset($vars))
			extract($vars);

		$path = ROOT_PATH.'/app/views/'.$this->path.'.php';
		if (file_exists($path)) {
			ob_start();
			require $path;
			$content = ob_get_clean(); //Получить содержимое текущего буфера и удалить его
			require ROOT_PATH.'/app/layouts/default.php';
		} else {
			echo "Файл не найден : ".$path;
		}
	}

	public static function errorCode($type) {
		http_response_code($type);

		$path = "app/views/errors/".$type.".php";
		if (file_exists($path)) {
			ob_start();
			require $path;
			$content = ob_get_clean();
			$title = $type;
			require ROOT_PATH.'/app/layouts/default.php';
		}
		exit();
	}


	public function redirection($url) {
		header('location: '.$url);
		exit;
	}


}


?>