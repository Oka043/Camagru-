<?php

namespace app\additional;

class Stikers {
  private $allMasks = array();


  public function __construct($directory, $directory_seperator) {
    $this->allMasks = $this->getAllImgs($this->getAllDirs($directory, $directory_seperator));
  }


  private function getAllDirs($directory, $directory_seperator) {
    
    $dirs = array_map(function ($item) use ($directory_seperator) {
      return str_replace("./", "/", $item) . $directory_seperator;
    }, array_filter(glob($directory . '*'), 'is_dir'));

    foreach ($dirs AS $dir) {
      $dirs = array_merge($dirs, $this->getAllDirs(".".$dir, $directory_seperator));
    }
    return $dirs;
  }


  private function getAllImgs($directory) {
    $resizedFilePath = array();
    $i = 0;

    foreach ($directory AS $dir) {
      $images_in_path = array();
      
      foreach (glob(".".$dir . '*.png') as $filename) {
        array_push($images_in_path, str_replace("./", "/", $filename));
      }
      // Если папка пустая - не добавляем.
      if (!empty($images_in_path)) {
        $resizedFilePath[$i++] = $images_in_path;
      }
    }
    return $resizedFilePath;
  }


  public function getAllStikers() {
    $out = array_values($this->allMasks);
    return $out;
  }
}

?>