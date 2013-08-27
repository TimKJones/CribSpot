<?php

class HelloShell extends AppShell 
{
	public $uses = array('Listing');

    public function main() {
        $this->out('Hello world.');
    }

    public function db_backup1() {
        shell_exec("mysqldump cribspot -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump1.sql");
    }

    public function db_backup2() {
        shell_exec("mysqldump cribspot -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump2.sql");
    }
}

?>