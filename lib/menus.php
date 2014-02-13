<?php
// lib/menus.php
$menu = array();

function addMenu($name,$link,$suboption='config',$side='right'){
	global $menu;

	if (!isset($menu[$side])){
		$menu[$side] = array();
	}
	if (!isset($menu[$side][$suboption])){
		$menu[$side][$suboption] = array();
	}

	$menu[$side][$suboption][$name]=$link;
}

function addMenuFiles($path){
	global $menu;

	$matches = glob($path."*.menu.php");
	if ( is_array ( $matches ) ) {
   		foreach ( $matches as $filename) {
      		require $filename;
   		}
	}
}

addMenuFiles($documentPath.$plugs_menus);