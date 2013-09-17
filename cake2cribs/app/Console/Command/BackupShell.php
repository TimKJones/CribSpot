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

    /*
    Stores a copy of the current database as a snapshot to save for later analysis
    */
    public function save_db_snapshot()
    {
        $month = date('n');
        $day = date('j') - 1;
        $year = date('Y');
        $filename = $month.'_'.$day.'_'.$year.'_snapshot.sql';
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/".$filename);
    }
}

?>