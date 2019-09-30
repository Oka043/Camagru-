<?php 

namespace app\db_workers;

use app\additional\Database;

class Likes {
  protected static $pdo_db;


  const ADD_LIKE_TO_IMAGE             = "INSERT INTO likes (image_id, user_id) VALUES (:image_id, :user_id)";
  const DELETE_LIKE_FROM_IMAGE        = "DELETE FROM likes WHERE image_id=:image_id and user_id = :user_id";
  const DELETE_ALL_LIKES_FROM_IMAGE   = "DELETE FROM likes WHERE image_id=:image_id";
  const IS_USER_LIKED_THIS_IMAGE      = "SELECT * FROM likes WHERE user_id=:user_id and image_id=:image_id";
  const TOTAL_LIKES_BY_IMAGE_ID       = "SELECT COUNT(*) as likes FROM likes WHERE image_id=:image_id";

  public static function initPDO() {
    $settings = require ROOT_PATH."/config/db_config.php";
    self::$pdo_db = new Database($settings);
  }

  public static function addNewLike($user_id, $image_id) {
    try {
      $data = array (
        "user_id" => $user_id,
        "image_id" => $image_id);

      $result = self::$pdo_db->query(self::ADD_LIKE_TO_IMAGE, $data);
  
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function IsUserLikedImage($user_id, $image_id) {
    try {
      $data = array (
        "user_id" => $user_id,
        "image_id" => $image_id);

      $result = self::$pdo_db->query(self::IS_USER_LIKED_THIS_IMAGE, $data);
      
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function getTotalLikes($image_id) {
    try {
      $data = array(
        "image_id" => $image_id);

      $result = self::$pdo_db->query(self::TOTAL_LIKES_BY_IMAGE_ID, $data);

      if (!empty($result))
        return $result[0]['likes'];
      return 0;
    } catch (PDOException $e) {
      return 0;
    }
  }

  public static function deleteOneLike($user_id, $image_id) {
    try {
      $data = array (
        "user_id" => $user_id,
        "image_id" => $image_id);

      $result = self::$pdo_db->query(self::DELETE_LIKE_FROM_IMAGE, $data);
  
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function deleteAllLikes($image_id) {
    try {
      $data = array (
        "image_id" => $image_id);

      $result = self::$pdo_db->query(self::DELETE_ALL_LIKES_FROM_IMAGE, $data);
  
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

}

Likes::initPDO();

?>