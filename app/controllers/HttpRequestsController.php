<?php

namespace app\controllers;

use app\core\Controller;

use app\db_workers\Users;
use app\db_workers\Likes;
use app\db_workers\Gallery;
use app\db_workers\Comments;
use app\db_workers\Followers;

use app\additional\Database;
use app\additional\Stikers;
use app\additional\MailManage;


class HttpRequestsController extends Controller {
	// ================================================================================================
	public function followUnfollowAction() {
		if (USER_LOGGED && $_SERVER['REQUEST_METHOD'] == "POST") {
			if (isset($_POST["follow_user_id"]) 
					&& is_numeric($_POST["follow_user_id"])
					&& Users::userIdExist($_POST["follow_user_id"])
					&& $_SESSION["user_id"] != $_POST["follow_user_id"]) {
				
				if (Followers::isUserFollow($_SESSION["user_id"], $_POST["follow_user_id"])) {
					if (Followers::unfollow($_SESSION["user_id"], $_POST["follow_user_id"])) {
						$result = array ("result" => true, "content" => "Follow");
					} else {
						$result = array ("result" => true, "content" => "Unfollow");
					}
				} else {
					if (Followers::follow($_SESSION["user_id"], $_POST["follow_user_id"])) {
						$result = array ("result" => true, "content" => "Unfollow");
					} else {
						$result = array ("result" => true, "content" => "Follow");
					}
				}
			} else {
				$result = array ("result" => false, "content" => "");
			}
			echo json_encode($result);
		} else {
			header('Location: /404');
		}
	}

	// ================================================================================================
	public function likeUnlikeAction() {
		if (USER_LOGGED && $_SERVER['REQUEST_METHOD'] == "POST") {
			if (isset($_POST["user"])
					&& is_numeric($_POST["user"])
					&& isset($_POST["image_id"])
					&& is_numeric($_POST["image_id"])) {
				// Validate user and image id, if path is true this means that user and image also exist.
				if (Gallery::imagePathExist($_POST["user"], $_POST["image_id"])) {
					if (Likes::IsUserLikedImage($_SESSION["user_id"], $_POST["image_id"])) {
						Likes::deleteOneLike($_SESSION["user_id"], $_POST["image_id"]);
						$result = array ("result" => true, "content" => "-");
					} else {
						Likes::addNewLike($_SESSION["user_id"], $_POST["image_id"]);
						MailManage::likeNotification(Users::getUserInfoByUserId($_POST["user"])["email"]);
						$result = array ("result" => true, "content" => "+");
					}
				} else {
					$result = array ("result" => false, "content" => "");
				}
			} else {
				$result = array ("result" => false, "content" => "");
			}
			echo json_encode($result);
		}
		 else {
			header('Location: /404');
		}
	}

	// ================================================================================================
	public function getAllStikersAction() {

		if (USER_LOGGED && $_SERVER['REQUEST_METHOD'] == "POST") {
			$directory = "./public/stikers";
			$directory_seperator = "/";
			$allImages = new Stikers($directory, $directory_seperator);
			echo json_encode($allImages->getAllStikers());
		} else {
			header('Location: /404');	
		}
	} 

	// ================================================================================================
	public function mergeAndSaveAction() {
		if (USER_LOGGED 
			&& $_SERVER['REQUEST_METHOD'] == "POST"
			&& isset($_POST["description"])
			&& isset($_POST["picture"])
			&& isset($_POST["pos_x"])
			&& isset($_POST["pos_y"]) 
			&& isset($_POST["key"]) 
			&& isset($_POST["id"])
		) {
			$base64_string = $_POST["picture"];
			$size_in_bytes = (int) (strlen(rtrim($base64_string, '=')) * 3 / 4);
    		$size_in_mb    = $size_in_bytes / 1024 / 1024;

      if ($size_in_mb > 10) {
      	$fmsg = "The image may not be greater than 5 megabytes";
      } else if (!is_numeric($_POST["pos_x"])
      		|| !is_numeric($_POST["pos_y"])
      		|| !is_numeric($_POST["key"])
      		|| !is_numeric($_POST["id"])
      	) {
      	$fmsg = "Something went wrong try later.";
    	} else {
				$allImages = new Stikers("./public/stikers", "/");
				$user_picture_data = explode(',', $base64_string);
		    $decoded_picture = base64_decode($user_picture_data[1]);
		    $user_picture = imagecreatefromstring($decoded_picture);

		    imagealphablending($user_picture, true);
	      imagesavealpha($user_picture, true);

		    $stiker_picture = imagecreatefrompng(ROOT_PATH.$allImages->getAllStikers()[$_POST["key"]][$_POST["id"]]);
		    // Resize stiker
		    $stiker_width = imagesx($stiker_picture);
		    $stiker_height = imagesy($stiker_picture);
		    $ratio = 250 / $stiker_width;
	      $new_height = $stiker_height * $ratio;
		    $resized_stiker = imagecreatetruecolor(250, 250);  
		    $transparent = imagecolorallocatealpha($resized_stiker, 0, 0, 0, 127);
				imagefill($resized_stiker, 0, 0, $transparent);
	      imagecopyresampled($resized_stiker, 
	      		$stiker_picture, 
	      		0, 0, 
	      		0, 0, 
	      		250, $new_height, 
	      		$stiker_width, $stiker_height
	      	);


	      // Concat user image and resized stiker.
		    imagecopy($user_picture, 
		    		$resized_stiker, 
		    		$_POST["pos_x"], $_POST["pos_y"], 
		    		0, 0, 
		    		250, 250);


		    $path = "images/";
		    $imageName = uniqid().".jpeg";
		    while (file_exists($path.$imageName))
		    	$imageName = uniqid().".jpeg";

		    imagejpeg($user_picture, $path.$imageName);
		    imagedestroy($user_picture);
		    if (Gallery::addNewImage($_SESSION["user_id"], $path.$imageName, htmlspecialchars(preg_replace('/\s+/', ' ', $_POST["description"]))))
		    	$smsg = "Image Saved succefully";
		    else 
		    	$fmsg = "Something went wrong try later";
		  }

    	$result['fmsg'] = (isset($fmsg) ? $fmsg : '');
    	$result['smsg'] = (isset($smsg) ? $smsg : '');
		  echo json_encode($result);
		} else {
			header('Location: /404');
		}
	}



	public function deletePictureAction() {
		if (USER_LOGGED 
			&& $_SERVER['REQUEST_METHOD'] == "POST"
			&& isset($_POST["user_id"]) && is_numeric($_POST["user_id"])
			&& isset($_POST["image_id"]) && is_numeric($_POST["image_id"])
			&& $_POST["user_id"] == $_SESSION["user_id"]
			&& Gallery::imagePathExist($_POST["user_id"], $_POST["image_id"])
		) {
			Likes::deleteAllLikes($_POST["image_id"]);
			Comments::deleteAllComments($_POST["image_id"]);

			$picture = Gallery::getSingleImageById($_POST["image_id"]);
			if (isset($picture["image_src"]) && file_exists($picture["image_src"]))
        unlink($picture["image_src"]);

      if (Gallery::deleteImage($_POST["image_id"]))
      	echo "success";
      else 
      	echo "fail";
		} else {
			header('Location: /404');	
		}
	}


}

?>