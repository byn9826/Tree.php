<?php
	
exec("php vendor/bin/phinx rollback -c tools/phinx.php", $outputs);

foreach($outputs as $output) {
	echo $output . "\n";
} 