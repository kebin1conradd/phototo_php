<?php
ini_set("display_errors", On);
error_reporting(E_ALL & ~E_NOTICE  & ~E_STRICT & ~E_DEPRECATED);
require_once '/home/phototo/app/framework/Phototo.class.php';
require_once '/home/phototo/app/library/Log.class.php';


try{
	$phototo = new phototo();
	$phototo->displayRender();
}catch(Exception $e){
	$log = new Log();
	$log->write('FrameWork','URI::'.$_SERVER["REQUEST_URI"].'\r\n'.$e);
}
