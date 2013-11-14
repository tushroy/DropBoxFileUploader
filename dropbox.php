<?php
session_start();
require 'DropboxUploader.php';
$dirtocopy = './files/';
$dropboxdir = '/Public/CDN/';
$uploader = new DropboxUploader('yourdropboxmail@gmail.com', 'yourpassword');// dropbox credentials


if(!isset($_SESSION["state"])){
 $_SESSION["state"]=0;
 
}
$processed=$_SESSION["state"];
$totalfiles=0;
$copylist;
$destlist;

function PrepareList($dirtocopy,$dropboxdir){
 if ($handle = opendir($dirtocopy)){
 	while (false !== ($entry = readdir($handle))) {
 		 if ($entry != "." && $entry != "..") {
 		 	if(is_dir($dirtocopy.$entry)){
 		 	PrepareList($dirtocopy.$entry.'/',$dropboxdir.$entry.'/');
 		 	}
 		 	else{
 		 	global $totalfiles,$copylist,$destlist;
 		 	$copylist[$totalfiles]=$dirtocopy.$entry;
 		 	$destlist[$totalfiles]=$dropboxdir;
 		 	$totalfiles++; 		 	
 		 	}
 		 }
 	}
 }
}
function processfiles($processed,$totalfiles,$uploader){ 
	$counter=0;
	global $copylist,$destlist;
	if($processed<$totalfiles){
		while($counter<10 && $processed<$totalfiles)
		{
		$uploader->upload($copylist[$processed], $destlist[$processed]);
		echo $processed.' '.$copylist[$processed].' '.$destlist[$processed].'<br>';
		$counter++;
		$processed++;
		}
		$_SESSION["state"]=$processed;
	}
	else{
	echo 'Done <br>';
	session_destroy();
	}
}

PrepareList($dirtocopy,$dropboxdir);
echo count($copylist).'<br>';
processfiles($processed,$totalfiles,$uploader);
echo "Executed!";
?>