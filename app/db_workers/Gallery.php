<?php
// require_once "DBInstance.php";

namespace app\db_workers;

use app\additional\Database;

class Gallery {
  protected static $pdo_db;


  const ADD_NEW_IMAGE             = "INSERT INTO `gallery` (`user_id`, `image_src`, `desc`) VALUES  (:user_id, :image_src, :description)";
  const IMAGE_PATH_EXIST          = "SELECT * FROM gallery WHERE user_id=:user_id and image_id=:image_id";
  const GET_IMAGE_SRC             = "SELECT image_src FROM gallery WHERE image_id=:image_id";

  const COUNT_ALL_IMAGES          = "SELECT COUNT(*) AS total FROM gallery";
  const COUNT_ALL_USER_IMAGES     = "SELECT COUNT(*) AS total FROM gallery WHERE user_id=:user_id";

  const DELETE_IMAGE_FROM_DB      = "DELETE FROM gallery WHERE image_id=:image_id";
  // =====================================================================================================
  const GET_IMAGES_BY_USER_ID = "SELECT 
                gallery.*,
                (SELECT count(*) from comments where comments.image_id=gallery.image_id) comments,
                (SELECT count(*) from likes where likes.image_id=gallery.image_id) likes
              FROM gallery 
              WHERE gallery.user_id=:user_id
              ORDER BY date DESC
              LIMIT :start, 15";
  // =====================================================================================================
  const GET_AVILABLE_IMAGES   = "SELECT 
                gallery.*,
                (SELECT count(*) from comments where comments.image_id=gallery.image_id) comments,
                (SELECT count(*) from likes where likes.image_id=gallery.image_id) likes
              FROM gallery 
              ORDER BY date DESC
              LIMIT :start, 15";
  // =====================================================================================================
  const GET_IMAGE_BY_ID           = "SELECT 
                gallery.*,
                (TIMESTAMPDIFF(MINUTE, gallery.date, CURRENT_TIMESTAMP)) as diff_m,
                (TIMESTAMPDIFF(HOUR, gallery.date, CURRENT_TIMESTAMP)) as diff_h,
                (TIMESTAMPDIFF(DAY, gallery.date, CURRENT_TIMESTAMP)) as diff_d,
                u.login,
                u.gender,
                u.avatar_src as avatar_src
              FROM gallery 
              INNER JOIN users u 
                ON u.user_id = gallery.user_id
              WHERE gallery.image_id=:image_id";


  public static function initPDO() {
    $settings = require ROOT_PATH."/config/db_config.php";
    self::$pdo_db = new Database($settings);
  }

  public static function imagePathExist($author_id, $image_id) {
    try {
      $data = array (
        "user_id" => $author_id, 
        "image_id" => $image_id);

      $result = self::$pdo_db->query(self::IMAGE_PATH_EXIST, $data);
      
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function addNewImage($author_id, $fullPath, $description) {
    try {
      $data = array (
        "user_id" => $author_id, 
        "image_src" => $fullPath, 
        "description" => $description);

      $result = self::$pdo_db->query(self::ADD_NEW_IMAGE, $data);
      
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function deleteImage($image_id) {
    try {
      $data = array (
        "image_id" => $image_id);

      $fileSrc = self::$pdo_db->query(self::GET_IMAGE_SRC, $data)[0];
      if ($fileSrc != NULL && isset($fileSrc["image_src"]) && file_exists($fileSrc["image_src"]))
        unlink($fileSrc["image_src"]);
      $result = self::$pdo_db->query(self::DELETE_IMAGE_FROM_DB, $data);

      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function getUserImages($user_id, $page=0) {
    try {
      $data = array (
        "user_id" => $user_id,
        "start" => $page * 15);

      $result = self::$pdo_db->query(self::GET_IMAGES_BY_USER_ID, $data);

      if (!empty($result))
        return $result;
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function getImages($page) {
    try {
      $data = array (
        "start" => $page * 15);

      $result = self::$pdo_db->query(self::GET_AVILABLE_IMAGES, $data);
      
      if (!empty($result))
        return $result;
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function getSizeOfGallery() {
    try {
      $result = self::$pdo_db->query(self::COUNT_ALL_IMAGES);
      
      if (!empty($result))
        return $result[0]['total'];
      return 0;
    } catch (PDOException $e) {
      return 0;
    }
  }

  public static function getSizeOfUserGallery($user_id) {
    try {
      $data = array(
        "user_id" => $user_id);

      $result = self::$pdo_db->query(self::COUNT_ALL_USER_IMAGES, $data);
      
      if (!empty($result))
        return $result[0]['total'];
      return 0;
    } catch (PDOException $e) {
      return 0;
    }
  }


  public static function getSingleImageById($image_id) {
    try {
      $data = array (
        "image_id" => $image_id);

      $result = self::$pdo_db->query(self::GET_IMAGE_BY_ID, $data);  

      if (!empty($result))
        return $result[0];
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }
}

Gallery::initPDO();

?>