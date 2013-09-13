<?php

class BackupShell extends AppShell 
{
	public $uses = array('Listing');

    public function db_backup1() {
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump1.sql");
    }

    public function db_backup2() {
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump2.sql");
    }

    /*
	Deletes all files in /tmp/logs
	Should be run a few times a week
    */
    public function logs_cleanup() {
    	$dir = TMP . '/logs';
    	foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') 
	        	continue;

	        $old = $dir.'/'.$item;
	        unlink($old);
	    }
    }
}

?>