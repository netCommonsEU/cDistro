<?php
//view.php

function hl($text=null,$kind=1){
	if (!is_null($text)){
		if ((!is_numeric($kind)) || (($kind < 0) && ($kind > 4)) ){
			$kind = 4;
		}
		return ("<h".$kind.">".$text."</h".$kind.">\n<br/>");
	}
}

function package_not_install($pkg,$des){
	global $staticFile;

	$page = "";

	$page .= hl('Not Install!');
	$page .= hl($pkg, 2);
	$page .= "<p>".$des."</p>";
	$page .= addButton(array('label'=>'Install','class'=>'btn btn-warning','href'=> $staticFile.'/default/install/'.$pkg));

	return($page);
}