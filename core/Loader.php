<?php

namespace Tree\Core;

class Loader {

  private static $_storage = [];

  public static function load($file) {
		if (!isset(self::$_storage[$file])) {
			if (!is_file($file)) {
				return false;
			}
			require($file);
			self::$_storage[$file] = true;
		}
		return true;
  }

	public static function loadModels() {
		spl_autoload_register(function($class) {
			require(\Tree\Info\Config::$application['baseFolder'] . '/models/' . substr($class, strrpos($class, '\\') + 1) . '.php');
		});
	}
	
}