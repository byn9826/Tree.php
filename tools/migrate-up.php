<?php
	
exec("php vendor/bin/phinx migrate -c tools/phinx.php", $outputs);

foreach($outputs as $output) {
	echo $output . "\n";
} 