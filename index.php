<?php
/*Script by Prathap Puppala(www.prathappuppala.com)*/
date_default_timezone_set("Asia/Kolkata");
require('CreateBackup.php');
require_once "vendor/autoload.php";
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;

//Instantiating DropBox
$app = new \Kunnu\Dropbox\DropboxApp("App Key", "App Secret", 'Access Key');
$dropbox = new Dropbox($app);

//Enter folder paths which need to take backup
$folderstobackup=array('/var/www/html/');
$backupfolderindropbox="PrathapPuppala_Site/";
$bakfilename="backup-".date("Y-m-d H:i:s").".zip";
//Instantiating CreateBackup to take backup
$CreateBackup_Obj=new CreateBackup();

foreach($folderstobackup as $folder){

//Taking Folder back up in backup directory
$filename=($CreateBackup_Obj->zipData($folder,$folder.$bakfilename));
if($filename==false){echo "Sorry.. ".$folder." backup is failed<br>";}
else{$filename=$bakfilename;echo $folder." backup is created and moved to ".$folder." with the name <b>backup.zip</b><br>";}

// prepare file for upload 
$dropboxFile = new DropboxFile($folder.$filename);
try{
    $file=$dropbox->upload($dropboxFile, backupfolderindropbox.$filename, ['autorename' => true]);
    $file->getName();
    echo $folder." backup is uploaded to dropbox.<br>";
}catch(Exception $e){
    echo $folder." backup upload failed.<br>";
}

unlink($folder.$bakfilename);
echo $folder." Backup file removed<br>";

}

?>
