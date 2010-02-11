<?php
if ($_SERVER['SERVER_PORT'] != 443) {
header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
}

// set variables throughout system
define('PRODUCT_IMAGES', APP . WEBROOT_DIR . DS . 'img' . DS . 'products' . DS);
define('URL_IMAGES', DS . 'img' . DS . 'products' . DS);
?>