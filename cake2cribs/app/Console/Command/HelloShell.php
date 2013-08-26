<?php

class HelloShell extends AppShell 
{
	public $uses = array('Listing');

    public function main() {
        $this->out('Hello world.');
    }

    public function db_backup() {
        shell_exec("mysqldump cake2cribs -u root -proot > ~/dumps/dump.sql");
    }
}

?>