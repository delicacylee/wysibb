<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
$fileupload = $_FILES["img"]['tmp_name'];
$newfile = $_FILES["img"]["name"];
$isIframe =($_POST["iframe"]) ? true: false;
$idarea = $_POST["idarea"];
$result = false;
$error = '';
$url = '';
if ((($_FILES["img"]["type"] == "image/gif") || ($_FILES["img"]["type"] == "image/jpeg") || ($_FILES["img"]["type"] == "image/jpg")) && ($_FILES["img"]["size"] < 20000000)) 
{ 
    if ($_FILES["img"]["error"] > 0) 
    { 
        $error = $_FILES["img"]["error"];
    } 
    else 
    { 
        if (file_exists(dirname(__DIR__) . "/upload/" . $fileupload)) 
        { 
            $error = $fileupload . " already exists."; 
        } 
        else 
        { 
            move_uploaded_file($_FILES["img"]["tmp_name"], dirname(__DIR__)."/upload/" . $newfile);
            $result = true;
            $url = "wysibb/upload/" . $newfile;
        } 
    } 
} else { 
    $error = "Invalid file"; 
}

if ($isIframe && $result) {
	#use for iframe upload
	echo '<html><body>OK<script>window.parent.$("#editor").insertImage("'.$url.'","'.$url.'").closeModal().updateUI();</script></body></html>';
}else{
	// use for drag&drop
	header("Content-type: text/javascript");
	if (!$result) {
		echo '{"status":0,"msg":'.$error.'}';
	} else {
		#OK
		echo '{"status":1,"msg":"OK","image_link":"'.$url.'","thumb_link":"'.$url.'"}';
	}
}