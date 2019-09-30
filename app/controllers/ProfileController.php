<?php

namespace app\controllers;

use app\core\Controller;

use app\db_workers\Users;
use app\db_workers\Likes;
use app\db_workers\Gallery;
use app\db_workers\Comments;
use app\db_workers\Followers;

use app\additional\Database;
use app\additional\Masks;
use app\additional\MailManage;


class ProfileController extends Controller {
		// =====================================================================================================
	public function settingsAction() {
		if (!USER_LOGGED)
			header('Location: /');

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION["user_id"])) {
      if (!isset($_POST['first_name'])
      		|| !isset($_POST['last_name'])
      		|| !isset($_POST['login'])
      		|| !isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
      		|| !isset($_POST['password']) || !isset($_POST['passRepeat'])
      		|| !isset($_POST['gender'])
      		|| !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $fmsg = "Please fill all fields.";
      } else if ($_POST['password'] != $_POST['passRepeat']) {
        $fmsg = "Passwords do not match";
      } else if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,25}$/", $_POST['password'])) {
        $fmsg  = "Password must have one lower case letter, ";
        $fmsg .= "one upper case letter, one digit, 8-25 length, and no spaces!";
      } else if (!preg_match("/^[A-Za-zА-Яа-яЁё_-]{3,30}$/", $_POST['login'])) {
    		$fmsg = "Login can contain letters, '-' , '_' - symbols and 3-30 symbols length!";
    	} else {
      	$user_data = Users::getMutableUserInfoById($_SESSION["user_id"]);

      	$user_data["first_name"] = htmlspecialchars(preg_replace('/\s+/', ' ', $_POST['first_name']));
      	$user_data["last_name"] = htmlspecialchars(preg_replace('/\s+/', ' ', $_POST['last_name']));
      	$user_data["gender"] = htmlspecialchars(preg_replace('/\s+/', ' ', $_POST['gender']));
      	$user_data["login"] = htmlspecialchars(preg_replace('/\s+/', ' ', $_POST['login']));
      	$user_data["email"] = htmlspecialchars(preg_replace('/\s+/', ' ', $_POST['email']));
      	// Update emails.
      	if (isset($_POST['recieveEmails']))
          $user_data["recieve_mails"] = 1;
        else
          $user_data["recieve_mails"] = 0;
        // Update biography.
        if (isset($_POST['bio']) && $_POST['bio'] != "")
          $user_data["bio"] = htmlspecialchars(preg_replace('/\s+/', ' ', $_POST['bio']));
        else
          $user_data["bio"] = "";

        if ($_POST['password'] != "111111111111Aa")
          $user_data["password"] = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Update avatar.
        if ($_FILES['avatar']['size'] != 0 && $_FILES['avatar']['error'] == 0) {
          if ($_FILES['avatar']['size'] > 800000) {
            $fmsg = "File size is too large, file size must not exceed 800kb";
          } else if ($_FILES['avatar']['type'] != "image/jpeg" 
              && $_FILES['avatar']['type'] != "image/png") {
            $fmsg = "Invalid file format";
          } else {

          	$avatarName = uniqid().".".pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
          	$path = "images/";
          	while (file_exists($path.$avatarName))
            	$avatarName = uniqid().".".pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            	
            move_uploaded_file($_FILES['avatar']['tmp_name'], $path.$avatarName);

            $avatar_src = $user_data["avatar_src"];
            if ($user_data["avatar_src"] != NULL && file_exists($user_data["avatar_src"]))
              unlink($avatar_src);
            // Set new avatar;
            $user_data["avatar_src"] = $path.$avatarName;
          }
        }

        if (!isset($fmsg) && Users::updateUserInfo($user_data))
        	$smsg = "User info succefully updated.";
      }
    }

    $result = Users::getMutableUserInfoById($_SESSION["user_id"]);
    if ($result) {
      $result['fmsg'] = (isset($fmsg) ? $fmsg : '');
      $result['smsg'] = (isset($smsg) ? $smsg : '');
    }
    $this->view->reader("Settings", $result);
	}

	// =====================================================================================================
	public function galleryAction() {
		if (isset($_GET['user'])) {
			if (is_numeric($_GET['user']) && Users::userIdExist($_GET['user'])) {
				$user_info = Users::getUserInfo($_GET['user']);
				if (isset($_SESSION["user_id"]) && Users::userIdExist($_SESSION["user_id"]))
					$user_follow = Followers::isUserFollow($_SESSION["user_id"], $_GET['user']);

				if (isset($_GET["page"]) 
						&& is_numeric($_GET["page"]) 
						&& $_GET["page"] > 0)
					$curr_page = $_GET["page"] - 1;
				else 
					$curr_page = 0;

				$total_images = Gallery::getSizeOfUserGallery($_GET['user']);
				if ($curr_page * 15 > $total_images)
					header('Location: /404');

				$user_images = Gallery::getUserImages($_GET['user'], $curr_page);
				$next_page = ($total_images > ($curr_page + 1) * 15) ? $curr_page + 2 : NULL;
				$prew_page = ($curr_page * 15 > 0) ? $curr_page : NULL;

			} else {
				header('Location: /404');
			}
		} else if (count($_GET) == 1 && isset($_SESSION["user_id"])) {
			if (is_numeric($_SESSION["user_id"]) && Users::userIdExist($_SESSION["user_id"])) {
				$user_info = Users::getUserInfo($_SESSION["user_id"]);

				if (isset($_GET["page"]) 
						&& is_numeric($_GET["page"]) 
						&& $_GET["page"] > 0)
					$curr_page = $_GET["page"] - 1;
				else 
					$curr_page = 0;

				$total_images = Gallery::getSizeOfUserGallery($_SESSION["user_id"]);
				if ($curr_page * 15 > $total_images)
					header('Location: /404');

				$user_images = Gallery::getUserImages($_SESSION["user_id"], $curr_page);
				$next_page = ($total_images > ($curr_page + 1) * 15) ? $curr_page + 2 : NULL;
				$prew_page = ($curr_page * 15 > 0) ? $curr_page : NULL;
			} else {
				header('Location: /404');
			}
		} else {
			header('Location: /404');
		}

		$result = $user_info;
		$result["images"] = $user_images;
		$result["followed"] = isset($user_follow) ? $user_follow : "";
		$result["next_page"] = isset($next_page) ? $next_page : NULL;
		$result["prew_page"] = isset($prew_page) ? $prew_page : NULL;
		$this->view->reader("Gallery", $result);


	}

	// =====================================================================================================
	public function pictureAction() {
		if (isset($_GET['user'])
				&& is_numeric($_GET['user']) 
				&& isset($_GET['image']) 
				&& is_numeric($_GET['image']) 
				&& Gallery::imagePathExist($_GET['user'], $_GET['image'])) {

			$user_like_this_image = false;
			// Check if user like this image
			if (isset($_SESSION["user_id"])
          && is_numeric($_SESSION["user_id"]) 
					&& Users::userIdExist($_SESSION["user_id"])) {
				$user_like_this_image = Likes::IsUserLikedImage($_SESSION["user_id"], $_GET['image']);
				$user_follow = Followers::isUserFollow($_SESSION["user_id"], $_GET['user']);

				// Post comment input if filled
				if (isset($_POST['message'])
	          && $_POST['message'] != ""
	          && isset($_SESSION["user_id"])
	          && is_numeric($_SESSION["user_id"])) {
					// Preventing message resubmission
					$messageIdent = md5($_GET['image'] . $_POST['message'] . $_SESSION["user_id"]);
					$sessionMessageIdent = isset($_SESSION['messageIdent'])?$_SESSION['messageIdent']:'';
					if($messageIdent != $sessionMessageIdent) {
						Comments::addNewComment($_GET['image'], htmlspecialchars($_POST['message']), $_SESSION["user_id"]);
						$author_email = Users::getUserInfoByUserId($_GET['user'])["email"];
						if ($author_email != NULL)
							MailManage::messageNotification($author_email);
						 $_SESSION['messageIdent'] = $messageIdent;
					}
				}
			}
			// All info about image
			$image = Gallery::getSingleImageById($_GET['image']);
			$comments = Comments::getAllComments($_GET['image']);
			$total_likes = Likes::getTotalLikes($_GET['image']);
		} else {
			header('Location: /404');
		}

		$result = $image;
		if ($comments != NULL)
			$result["comments"] = $comments;
		if ($total_likes != NULL)
			$result["total_likes"] = $total_likes;
		
		$result["user_like_this_image"] = $user_like_this_image;
		$result["followed"] = isset($user_follow) ? $user_follow : "";
		$this->view->reader("Image", $result);
	}

	public function makePictureAction() {
		if (!USER_LOGGED)
			header('Location: /404');
		
		$this->view->reader("Make Photo New");
	}


	// =====================================================================================================
	public function followersAction() {
		if (isset($_GET['user'])
				&& is_numeric($_GET['user']) 
				&& Users::userIdExist($_GET['user'])) {
			
			if (isset($_SESSION["user_id"]) && Users::userIdExist($_SESSION["user_id"])) {
				$followers = Followers::getAllFollowers($_GET['user'], $_SESSION["user_id"]);
			} else {
				$followers = Followers::getAllFollowers($_GET['user']);
			}
		} else {
			header('Location: /404');	
		}
		$result["followers"] = $followers;
		$this->view->reader("Followers", $result);
	}

	// =====================================================================================================
	public function followingAction() {
		if (isset($_GET['user'])
				&& is_numeric($_GET['user']) 
				&& Users::userIdExist($_GET['user'])) {

			if (isset($_SESSION["user_id"]) && Users::userIdExist($_SESSION["user_id"])) {
				$followers = Followers::getAllFollowing($_GET['user'], $_SESSION["user_id"]);
			} else {
				$followers = Followers::getAllFollowing($_GET['user']);
			}
		} else {
			header('Location: /404');		
		}

		$result["following"] = $followers;
		$this->view->reader("Following", $result);
	}

	// =====================================================================================================
	public function likesAction() {
		if (isset($_GET['user'])
				&& is_numeric($_GET['user']) 
				&& isset($_GET['image']) 
				&& is_numeric($_GET['image']) 
				&& Gallery::imagePathExist($_GET['user'], $_GET['image'])) {
			

			$likers = Null;
			// $likers = Likes::getAllLikers($_GET['user']);
		} else {
			header('Location: /404');		
		}

		$result["likers"] = $likers;
		$this->view->reader("Following", $result);
	}

}

?>