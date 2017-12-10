<?php

namespace App\Model;
	
class Testing extends \Tree\Core\Model {
	
	public function init() {
		$this->useTable('test');
	}
	
}