<?php

function notFunctionExist(){

	global $Parameters;

	$c = array_shift($Parameters);
	$a = array_shift($Parameters);

	ErrorPage(1,'notFunctionExist',"($c::$a): Function doesn't exist.");
}

function notFileExist($name){

	ErrorPage(2,'notFileExist',"'$name': This file doesn't exist.");

}

function notReadFile($name){

	ErrorPage(3,'notReadFile',"'$name': Can't read this file.");

}

function notWriteFile($name){

	ErrorPage(4,'notWriteFile',"'$name': Can't write this file.");

}

function callbackReturnUnknow($cmd){

	ErrorPage(5,'callbackReturnUnknow',"$cmd: Command callback return unknow.");

}

function errorExecuteExternalProgram($cmd,$output=""){

	ErrorPage(5,'errorExecuteExternalProgram',"$cmd: Error execute program.\n$output");

}


function ErrorPage($knum, $kstr, $explain){
	global $css, $js, $appName, $staticPath, $appURL, $menu;

	require "templates/header.php";
	require "templates/menu.php";
	require "templates/begincontent.php";
	require "templates/flash.php";
?>

	<div>
		<h1>Error</h1>
		<h2><?php echo $kstr."(".$knum.")" ?></h2>
		<p><?php echo $explain;?></p>
	</div>
<?php
	require "templates/endcontent.php";
	require "templates/footer.php";
	require "templates/endpage.php";

	exit();
}
?>