<?php
header("Content-Type: application/force-download");
header('Content-Disposition: attachment; filename="'.$_GET['filename'].'"');
header("Content-Length: ".filesize($_GET['fullpath']));
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
readfile($_GET['fullpath']);
exit(0);