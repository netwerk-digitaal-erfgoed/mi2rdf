<?php

include('includes/config.php');

if (isset($_GET["guid"])) {
        $guid=preg_replace('/[^A-F0-9\-]/i','',$_GET["guid"]);

        echo '<h6>Conversie MDWS naar JSON</h6>';
        echo '<hr>';
        $file1=$guid.".json.err";
        if(file_exists(UPLOAD_DIR.$file1)) {
                $jsonerr=file_get_contents(UPLOAD_DIR.$file1);
        } else {
                $jsonerr="ERROR: $file1 doesn't exist";
        }
        echo '<xmp>'.$jsonerr.'</xmp>';
        echo '<hr>';


        echo '<h6>Conversie JSON naar Turtle</h6>';
        echo '<hr>';
        $file2=$guid.".ttl.err";
        if(file_exists(UPLOAD_DIR.$file2)) {
                $ttlerr=file_get_contents(UPLOAD_DIR.$file2);
        } else {
                $ttlerr="ERROR: $file2 doesn't exist";
        }
        echo '<xmp>'.$ttlerr.'</xmp>';
        echo '<hr>';

} else {
        echo "ERROR: invalid guid";
}
	