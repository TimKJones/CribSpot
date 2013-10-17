<?php

class BackupShell extends AppShell 
{
	public $uses = array('Listing');

    /*
    Generates a database dump, and then uploads to the s3 backup bucket
    Saves to file called dump[$number].sql
    */
    public function db_backup()
    {
        $number = $this->args[0];

        /* Generate local dump */
        $this->_generateLocalDump($number); 

        /* Upload file to s3 */
        $db_dumps_path = Configure::read('PATH_TO_DB_DUMPS');
        $backup_bucket_path = Configure::read("S3_DAILY_BACKUP_PATH");
        $this->_uploadFileToS3($db_dumps_path."dump".strval($number).".sql", $backup_bucket_path);
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

    /*
    Uploads the file at $localFilePath to the s3 bucket path specified by $s3_path
    */
    private function _uploadFileToS3($localFilePath, $s3_path)
    {
        $s3cmd = Configure::read("PATH_TO_S3CMD");
        shell_exec($s3cmd." put ".$localFilePath." ".$s3_path);
    }

    /*
    Dumpds entire database to a file in /webroot/dumps/dump[$dumpNumber].sql
    */
    private function _generateLocalDump($dumpNumber)
    {
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump".$dumpNumber.".sql");
    }
}

?>