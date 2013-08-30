<?php

class DBBackupShell extends AppShell 
{
	public $uses = array('Listing');

    public function db_backup1() {
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump1.sql");
    }

    public function db_backup2() {
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump2.sql");
    }
}

?>