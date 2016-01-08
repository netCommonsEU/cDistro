<?php
// plug/controllers/lxc.php

function index() {

	global $urlpath, $staticPath;

	$page = "";
	$buttons = "";

	$page .= hlc(t("lxc_common_title"));
	$page .= hl(t("lxc_common_subtitle"),4);

	$page .= par(t("lxc_index_description_1").t("lxc_index_description_2"));
	$page .= par(t("lxc_index_description_3").t("lxc_index_description_4"));
	$page .= par(t("lxc_index_description_5"));

	$buttons .= addButton(array('label'=>t("settings_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath));

	$page .= par(" ");
	$page .= $buttons;

	return(array('type'=>'render','page'=>$page));
}


