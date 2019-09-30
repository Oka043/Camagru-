<?php

namespace app\controllers;

use app\core\Controller;
use app\db_workers\Gallery;
use app\db_workers\Users;
/**
 * 
 */
class MainController extends Controller {

	public function indexAction() {
		
		if (isset($_GET["page"]) 
				&& is_numeric($_GET["page"]) 
				&& $_GET["page"] > 0)
			$curr_page = $_GET["page"] - 1;
		else 
			$curr_page = 0;

		$total_images = Gallery::getSizeOfGallery();
		if ($curr_page * 15 > $total_images)
			header('Location: /404');

		$all_images = Gallery::getImages($curr_page);
		
		$result["images"] = $all_images;
		$result["next_page"] = ($total_images > ($curr_page + 1) * 15) ? $curr_page + 2 : NULL;
		$result["prew_page"] = ($curr_page * 15 > 0) ? $curr_page : NULL;

		
		
		$this->view->reader("Главная Страница", $result);
	}
}

?>