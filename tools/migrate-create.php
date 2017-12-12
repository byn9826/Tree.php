<?php
	
exec("php vendor/bin/phinx create " . $argv[1] . " -c tools/phinx.php", $outputs);

foreach($outputs as $output) {
	echo $output . "\n";
} 