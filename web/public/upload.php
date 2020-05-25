<?php
 
include('includes/config.php');
include('includes/database.php');
include('includes/queue.php');

set_time_limit(0);

if (!is_dir(UPLOAD_DIR)) {
	mkdir(UPLOAD_DIR);
}
				
if ($_FILES["file"]["size"]>0) {
	
	$uploadedfile=basename($_FILES['file']['name']);
	$ext = strtolower(substr($uploadedfile,-4));
				
	if (($ext == ".txt") || ($ext == ".zip")) {
		if ($uploadedfile!="") {
			if ($ext == ".zip") {
				$tmpdir=UPLOAD_DIR.uniqid()."/";
				$list=unzip($_FILES['file']['tmp_name'],$tmpdir);
				for($i = 0; $i < sizeof($list); $i++) {
					$ext = strtolower(substr($list[$i],-4));
					if ($ext==".txt" || $ext==".xml") {
						$guid=GUID();
						rename($tmpdir.$list[$i],UPLOAD_DIR.$guid.$ext);
						fInsertDataset($guid,$list[$i]);
						fAddToQueue($guid,$list[$i]);
						error_log("INFO: file $list[$i] from uploaded $uploadedfile as ".UPLOAD_DIR.$guid.$ext);
					}
				}
				deleteDirectory($tmpdir);
			} else if ($ext==".txt" || $ext==".xml") {
				$guid=GUID();
				if (move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_DIR.$guid.$ext)) {
					error_log("INFO: file uploaded ".UPLOAD_DIR.$guid.$ext);
					fInsertDataset($guid,$uploadedfile);
					fAddToQueue($guid,$uploadedfile);
				} else {
					error_log("WARN: Possible file upload attack $uploadedfile!");
				}
			}
		} else {
			error_log("ERROR: Geen bestand ge-upload");
		}

	} else {
		error_log("ERROR: onjuist bestandstype");
	}
} else {
	error_log("ERROR: bestandsupload mislukt");
}


function GUID() {
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
	
function unzip($src_file, $dest_dir) {
	error_log("INFO: unzip -j -o $src_file \"*.*\" -d $dest_dir");
	exec("unzip -j -o $src_file \"*.*\" -d $dest_dir");

	$list=array();
	$handler = opendir($dest_dir);
	while( $file = readdir( $handler ) ) {
		if (substr($file,0,1)!="." && $file!=".." && substr($file,-4)==".txt") {
			array_push($list,$file);
		}
	}
	return $list;
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}