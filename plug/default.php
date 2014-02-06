<?php
// Default file..

function index(){

	$page = "";

	$page .= "<h1>Configure Guifi!</h1>";
	$page .= 'Configure your system!';

	return(array('type'=>'render','page'=>$page));
} 

?>