<?php
	
exec("php vendor/bin/phinx status -c tools/phinx.php", $outputs);

foreach($outputs as $output) {
	echo $output . "\n";
} 