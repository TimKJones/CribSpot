<?php

class BackupShell extends AppShell 
{
	public $uses = array('Listing');

    public function db_backup1() {
        $localFilePath = "/home/tim/CribSpot/cake2cribs/app/webroot/dumps/dump1.sql";
        $s3_path = "daily_backups/dump1.sql";
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMIn > ~/CribSpot/cake2cribs/app/webroot/dumps/dump1.sql");

        /* Upload file to s3 */
        shell_exec("~/Downloads/s3cmd-1.5.0-alpha1/s3cmd sync dump1.sql s3://cribspot-backup/daily_backups");

        /* delete local file */
        unlink($localFilePath);
    }

    public function db_backup2() {      
        $localFilePath = "/home/tim/CribSpot/cake2cribs/app/webroot/dumps/dump1.sql";
        $s3_path = "daily_backups/dump2.sql";
        shell_exec("mysqldump cake2cribs -u root -plancPA*travMInj > ~/CribSpot/cake2cribs/app/webroot/dumps/dump2.sql");

        /* Upload file to s3 */
        shell_exec("~/Downloads/s3cmd-1.5.0-alpha1/s3cmd sync dump2.sql s3://cribspot-backup/daily_backups");

        /* delete local file */
        unlink($localFilePath);
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

    private function _uploadFileToS3($localFilePath, $s3_path)
    {
        App::import('Vendor', 'AmazonS3/S3');
        $accessKey = Configure::read('S3_ACCESS_KEY');
        $secretKey = Configure::read('S3_SECRET_KEY');
        $backupBucket = Configure::read('S3_BACKUP_BUCKET');
        $s3 = new S3($accessKey, $secretKey);
        if (!$s3->putObjectFile($localFilePath, $backupBucket, $s3_path, S3::ACL_PRIVATE)) {
            CakeLog::write('FAILED_BACKUP', $localFilePath . '; ' . $backupBucket . '; ' . $s3_path);
        }
    }
}

?>