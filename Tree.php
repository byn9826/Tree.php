<?php

class Tree {

  private static $_fileLoader = [];
	
  public static function load($file) {
		if (!in_array($file, self::$_fileLoader)) {
			if (!is_file($file)) {
				return false;
			}
			require($file);
			array_push(self::$_fileLoader, $file);
		}
    return true;
  }

}