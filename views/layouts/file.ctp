<?php

header('Content-type: ' . $wall['Wall']['mimetype']);

if(!isset($inpage)) {
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename="'.$wall['Wall']['filename'].'"');
    header('Content-length: ' . $wall['Wall']['filesize']);
    header('Content-Transfer-Encoding: binary');
}

echo $content_for_layout;
// die();

?>
