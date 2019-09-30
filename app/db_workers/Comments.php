<?php 

namespace app\db_workers;

use app\additional\Database;

class Comments {
  protected static $pdo_db;

  const ADD_NEW_COMMENT     = "INSERT INTO comments (comment, image_id, user_id) VALUES (:comment, :image_id, :user_id)";
  const DELETE_ALL_COMMENTS = "DELETE FROM comments WHERE image_id=:image_id";


  const GET_ALL_COMMENTS_FOR_IMAGE = "SELECT 
          c.comment, 
          c.date,
          (TIMESTAMPDIFF(MINUTE, c.date, CURRENT_TIMESTAMP)) as diff_m,
          (TIMESTAMPDIFF(HOUR, c.date, CURRENT_TIMESTAMP)) as diff_h,
          (TIMESTAMPDIFF(DAY, c.date, CURRENT_TIMESTAMP)) as diff_d,
          u.login, 
          u.user_id, 
          u.avatar_src,
          u.gender
        FROM comments c 
        INNER JOIN users u 
          ON c.user_id = u.user_id
        WHERE c.image_id=:image_id
        ";
 
  public static function  initPDO() {
    $settings = require ROOT_PATH."/config/db_config.php";
    self::$pdo_db = new Database($settings);
  }

  public static function  addNewComment($image_id, $message, $user_id) {
    try {
      $data = array (
        "comment" => $message,
        "image_id" => $image_id, 
        "user_id" => $user_id);

      $result = self::$pdo_db->query(self::ADD_NEW_COMMENT, $data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  getAllComments($image_id) {
    try {
      $data = array (
        "image_id" => $image_id);
      $result = self::$pdo_db->query(self::GET_ALL_COMMENTS_FOR_IMAGE, $data);

      if (!empty($result))
        return $result;
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function  deleteAllComments($image_id) {
    try {
      $data = array (
        "image_id" => $image_id);
      $result = self::$pdo_db->query(self::DELETE_ALL_COMMENTS, $data);

      if (!empty($result))
        return $result;
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }
}

Comments::initPDO();

?>