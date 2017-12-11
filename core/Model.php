<?php

namespace Tree\Core;

class Model {
	
	public function __construct() {}
	
	public static function fetch() {
		return new ModelFetcher(static::$table_name, static::class);
	}
	
}