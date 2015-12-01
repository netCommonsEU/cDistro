<?php
//view.php

function hl($text=null,$kind=1){
	if (!is_null($text)){
		if ((!is_numeric($kind)) || (($kind < 0) && ($kind > 4)) ){
			$kind = 4;
		}
		return ("<h".$kind.">".$text."</h".$kind."><br/>");
	}
}

function hlc($text=null,$kind=1){
	if (!is_null($text)){
		if ((!is_numeric($kind)) || (($kind < 0) && ($kind > 4)) ){
			$kind = 4;
		}
		return ("<h".$kind.">".$text."</h".$kind.">");
	}
}

function par($text=null){
	if (!is_null($text)){
		return ("<div>".$text."</div><br/>");
	}
}

function spar($text=null){
	if (!is_null($text)){
		return ('<div><span style="font-size: smaller;">'.$text.'</span></div><br/>');
	}
}

function ptxt($text=null, $id=null){
	if (!is_null($text)){
		if (!is_null($id)){
			return ("<div id=".'"'.$id.'"'."><pre>".$text."</pre></div>");
		}
		else{
			return ("<div><pre>".$text."</pre></div>");
		}
	}
}


function txt($text=null, $id=null){
	if (!is_null($text)){
		if (!is_null($id)){
			return ("<div id=".'"'.$id.'"'.">".$text."</div>");
		}
		else{
			return ("<div>".$text."</div>");
		}
	}
}

function stxt($text=null){
	if (!is_null($text)){
		return ('<div><span style="font-size: smaller;">'.$text.'</span></div>');
	}
}

function package_not_install($pkg,$des){
	global $staticFile;

	$page = "";

	$page .= hlc(t("lib-view_common_package_manager_title"));
	$page .= hl(t("lib-view_common_package_manager_subtitle"),4);

	$page .= txt(t("lib-view_package_not_installed_to_install"));
	$page .= ptxt("<strong>".$pkg."</strong> - ".$des);

	$page .= par(t("lib-view_package_not_installed_text"));

	$page .= addButton(array('label'=>t("lib-view_button_back"),'class'=>'btn btn-default','href'=> $staticFile));
	$page .= addButton(array('label'=>t("lib-view_button_install_pre").$pkg.t("lib-view_button_install_post"),'class'=>'btn btn-success','href'=> $staticFile.'/default/install/'.$pkg));

	return($page);
}

function addTableHeader($values, $options=array()){

	$default = array('class'=>'table');
	$op = array_merge($default,$options);

	$page = "<table";
	foreach ($op as $key => $value) {
		$page .= " ".$key."='".$value."'";
	}
	$page .= ">\n<thead>\n<tr>\n";

	foreach($values as $v){
    	$page .= "<th>".$v."</th>\n";
  	}
    $page .= "</tr>\n</thead>\n<tbody>";

    return($page);
}

function addTableRow($values, $tr_options=array(), $td_options=array()){
	$page = "<tr";
	foreach($tr_options as $k=>$v){
		$page .= " $k='$v'";
	}
	$page .=">";
	foreach($values as $k=>$v){
	$page .= "<td";
		if(isset($td_options[$k]) && is_array($td_options[$k])) {
			foreach($td_options[$k] as $td_k=>$td_v){
				$page .= " $td_k='$td_v'";
			}
		}
		$page .= ">".$v."</td>\n";
  	}
  	$page .= "</tr>";
  	return ($page);
}

function addTableFooter(){
	$page = "</tbody>\n</table>\n";
	return($page);
}

function ajaxStr($div,$label){
	global $staticPath;

	$page = "<div id='".$div."'><img src='".$staticPath."images/ajax_loader.gif' width='40px' height='40px' /> ";
	$page .= $label."</div>";

	return($page);
}
