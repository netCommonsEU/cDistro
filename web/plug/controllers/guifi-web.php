<?php
//plug/controllers/guifi-web.php

$GUIFI_CONF_DIR = "/etc";
$GUIFI_CONF_FILE = "guifi.conf";

if (file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) && !filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE))
	$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

function index(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_index_subtitle"),4);

	$page .= par(t("guifi-web_index_description"));

	if (!file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) || !filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_not_initialized")."</div>\n";
		$page .= par(t("guifi-web_index_not_initialized"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_initialize"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function initialize(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_initialize_subtitle"),4);

	$page .= par(t("guifi-web_initialize_description"));

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('USERNAME',t("guifi-web_initialize_form_username"),'',array('type'=>'text','required'=>true),'',t("guifi-web_initialize_form_username_tooltip"));
	$form .= addInput('PASSWORD',t("guifi-web_initialize_form_password"),'',array('type'=>'password', 'required'=>true),'',t("guifi-web_initialize_form_password_tooltip"));
 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_check'),'class'=>'btn btn-primary'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function initialize_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_initialize_subtitle"),4);

	/*if (!file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE))
		touch($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE);
	if (fileperms($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) != "16877" )
		chmod($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, 0755);
	*/
	/*if (!file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE))
		touch($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE);
	if (fileperms($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) != "16877" )
		chmod($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, 0755);
	*/

		$buttons .= addSubmit(array('label'=>t('tahoe-lafs_button_create_introducer'),'class'=>'btn btn-success'));

	/*if (! file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) || ( fileperms($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) != "16877" ))
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_config_failed")."</div>\n";
	elseif (! file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) || ( fileperms($GUIFI_CONF_DIR) != "16877" ))

	*/

	$page .= par("aa");
	$page .= par($_POST['USERNAME']);
	$page .= par(md5($_POST['PASSWORD']));
	$page .= par("bb");

	$content = file_get_contents("http://test.guifi.net/api?command=guifi.auth.login&username=".$_POST['USERNAME']."&password=".$_POST['PASSWORD']);
		$page .= par($content);

//	$result_dir = (shell_exec($cmd));

/*
	$page .= par(t("guifi-web_initialize_result"));
		if (!file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) || !file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) )

	if (!file_exists($GUIFI_CONF_DIR))
		mkdir($GUIFI_CONF_DIR, 0755, true);

	$cmd = "apt-get update";
	$page .= ptxt(shell_exec($cmd));
pri


	$page .= par(t("guifi-web_initialize_details"));

	if (!file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) || !file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) )
	$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_not_initialised")."</div>\n";


		$buttons .= addButton(array('label'=>t("tahoe-lafs_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
*/
	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function ffileperms(){
print_r(fileperms("/etc"));
}

function login_post(){
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_login_subtitle"),4);




	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function config_dir_exists() {
	global $GUIFI, $GUIFI_CONF_DIR;
	return file_exists($GUIFI_CONF_DIR);
}