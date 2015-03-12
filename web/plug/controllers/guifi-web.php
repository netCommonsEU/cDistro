<?php
//plug/controllers/guifi-web.php

$GUIFI_CONF_DIR = "/etc";
$GUIFI_CONF_FILE = "guifi.conf";

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
	$form .= addInput('USERNAME',t("guifi-web_initialize_form_username"),'',array('type'=>'text','required'=>true,'pattern'=>'[A-Za-z0-9_-\s\.]+'),'',t("guifi-web_initialize_form_username_tooltip"));
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

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_post_empty")."</div>\n";
		$page .= par(t("guifi-web_initialize_post_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	else if (empty($_POST['USERNAME'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_post_emptyusername")."</div>\n";
		$page .= par(t("guifi-web_initialize_post_emptyusername"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	else if (empty($_POST['PASSWORD'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_post_emptypassword")."</div>\n";
		$page .= par(t("guifi-web_initialize_post_emptypassword"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	else {
		$url = "http://test.guifi.net/api?command=guifi.auth.login&username=".$_POST['USERNAME']."&password=".$_POST['PASSWORD'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$json = curl_exec($ch);
		curl_close($ch);

		if (empty($json)) {
			$page .= txt(t("guifi-web_initialize_curl_error"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_empty")."</div>\n";
			$page .= par(t("guifi-web_initialize_curl_empty"));
			$page .= txt(t("guifi-web_initialize_curl_url"));
			$page .= "<div class='alert alert-info text-center'>"."http://guifi.net/api?command=guifi.auth.login&username=".$_POST['USERNAME']."&password=********</div>\n";
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
		}

		else {
			$output = json_decode ($json);

			if ($output->{'code'}->{'code'} == 200 ) {
				$page .= txt(t("guifi-web_initialize_curl_authresult"));
				$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_initialize_curl_ok")."</div>\n";
				$page .= txt(t("guifi-web_initialize_curl_details"));
				$page .= ptxt($json);

				if (!file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE))
					touch($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE);
				if (fileperms($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) != "16877" )
					chmod($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, 0644);

				write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, array("USERNAME"=>'"'.$_POST['USERNAME'].'"', "TOKEN"=>'"'.$output->{'responses'}->{'authToken'}.'"'));

				if ( !file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
					$page .= txt(t("guifi-web_initialize_result"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_file_error")."</div>\n";
					$page .= par(t("guifi-web_initialize_file_error"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}

				else if ( filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) == 0 ) {
					$page .= txt(t("guifi-web_initialize_result"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_file_empty")."</div>\n";
					$page .= par(t("guifi-web_initialize_file_empty"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}

				else {
					$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

					if ( $GUIFI['USERNAME'] != $_POST['USERNAME'] || $GUIFI['TOKEN'] != $output->{'responses'}->{'authToken'} ) {
						$page .= txt(t("guifi-web_initialize_result"));
						$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_file_different")."</div>\n";
						$page .= par(t("guifi-web_initialize_file_different"));
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
					}

					else {
						$page .= txt(t("guifi-web_initialize_result"));
						$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_initialize_success")."</div>\n";
						$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
					}
				}
			}

			else if ($output->{'code'}->{'code'} == 201 ) {

				if ($output->{'errors'}[0]->{'code'} == 401 ) {
					$page .= txt(t("guifi-web_initialize_curl_authresult"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_wrong_command")."</div>\n";
					$page .= txt(t("guifi-web_initialize_curl_details"));
					$page .= ptxt($json);
					$page .= par(t("guifi-web_initialize_curl_wrong_command"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}

				else if ($output->{'errors'}[0]->{'code'} == 403 ) {
					$page .= txt(t("guifi-web_initialize_curl_authresult"));
					$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_initialize_curl_wrong_login")."</div>\n";
					$page .= txt(t("guifi-web_initialize_curl_details"));
					$page .= ptxt($json);
					$page .= par(t("guifi-web_initialize_curl_wrong_login"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}

				else {
					$page .= txt(t("guifi-web_initialize_curl_authresult"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_error")."</div>\n";
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
					$page .= txt(t("guifi-web_initialize_curl_details"));
					$page .= ptxt($json);
					$page .= par(t("guifi-web_initialize_curl_error"));
				}
			}

			else {
				$page .= txt(t("guifi-web_initialize_curl_authresult"));
				$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_error")."</div>\n";
				$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				$page .= txt(t("guifi-web_initialize_curl_details"));
				$page .= ptxt($json);
				$page .= par(t("guifi-web_initialize_curl_error"));
			}

		}
	}

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