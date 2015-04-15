<?php
//plug/controllers/guifi-web.php

$GUIFI_CONF_DIR = "/etc";
$GUIFI_CONF_FILE = "guifi.conf";
$GUIFI_WEB="https://guifi.net";

function index(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_index_subtitle"),4);

	$page .= par(t("guifi-web_index_description"));

	$page .= txt(t("guifi-web_index_status"));
	if (!file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) || !filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
		$page .= par(t("guifi-web_index_not_registered"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials'));
	}

	else {
		$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		if ( !isset($GUIFI['USERNAME']) || !isset($GUIFI['TOKEN']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";

			$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials'));
		}

		else {

			if ( !isset($GUIFI['NODEID']) ) {
				$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
				$page .= par(t("guifi-web_index_not_registered"));
				$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/register'));
			}

			else if ( ! isset($GUIFI['DEVICEID'] )) {
				$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
				$page .= par(t("guifi-web_index_not_registered"));
				$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/register'));
			}

			else {
				$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_index_nodeid")."</div>\n";

				$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/register'));
			}
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));


}

function credentials(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_credentials_subtitle"),4);

	$page .= par(t("guifi-web_credentials_description"));

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('USERNAME',t("guifi-web_credentials_form_username"),'',array('type'=>'text','required'=>true,'pattern'=>'[A-Za-z0-9_-\s\.]+'),'',t("guifi-web_credentials_form_username_tooltip"));
	$form .= addInput('PASSWORD',t("guifi-web_credentials_form_password"),'',array('type'=>'password', 'required'=>true),'',t("guifi-web_credentials_form_password_tooltip"));

	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_check'),'class'=>'btn btn-primary'));

	$page .= $form;

	$page .= txt(t("guifi-web_credentials_security"));
	$page .= "<div class='alert alert-info text-center'>".t("guifi-web_alert_credentials_security_username")."</div>\n";
	$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_credentials_security_password")."</div>\n";
	$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_credentials_security_ssl")."</div>\n";

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function credentials_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE, $GUIFI_WEB;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_credentials_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_post_empty")."</div>\n";
		$page .= par(t("guifi-web_credentials_post_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
	}

	else if (empty($_POST['USERNAME'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_post_emptyusername")."</div>\n";
		$page .= par(t("guifi-web_credentials_post_emptyusername"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
	}

	else if (empty($_POST['PASSWORD'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_post_emptypassword")."</div>\n";
		$page .= par(t("guifi-web_credentials_post_emptypassword"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
	}

	else {

		$gapi = new guifiAPI( $_POST['USERNAME'], $_POST['PASSWORD'] );

		//$page .= ptxt(print_r($gapi, true));

		//Server could not be reached
		if ( !isset($gapi->responseCode) && $gapi->getErrors() == NULL ) {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_curl_empty")."</div>\n";
			$page .= par(t("guifi-web_credentials_curl_empty"));
			$page .= txt(t("guifi-web_credentials_curl_url"));
			$page .= "<div class='alert alert-info text-center'>".$GUIFI_WEB."/api?command=guifi.auth.login&username=".$_POST['USERNAME']."&password=********</div>\n";
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
		}

		//Server was reached
		elseif ( isset($gapi->responseCode) || isset($gapi->getErrors()[0])) {

			//In case of error
			if ( $gapi->getErrors()) {

				switch($gapi->getErrors()[0]->code) {
					case 401:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_curl_wrong_command")."</div>\n";
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->getErrors(), true));
						$page .= par(t("guifi-web_credentials_curl_wrong_command"));
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
						break;

					case 403:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_credentials_curl_wrong_login")."</div>\n";
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->getErrors(), true));
						$page .= par(t("guifi-web_credentials_curl_wrong_login"));
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
						break;

					default:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_curl_error")."</div>\n";
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->getErrors(), true));
						$page .= par(t("guifi-web_credentials_curl_error"));
						break;
				}
			}

			//No error (apparently)
			else if ( isset($gapi->responseCode) ) {

				switch ($gapi->responseCode->code) {
					//Success
					case 200:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_credentials_curl_ok")."</div>\n";
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->responses,true));

						if (!file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE))
							touch($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE);
						if (fileperms($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) != "16877" )
							chmod($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, 0644);

						write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, add_quotes(array("USERNAME"=>$_POST['USERNAME'], "TOKEN"=>$gapi->responses->authToken)));

						//Check if config file has been saved
						if ( !file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
							$page .= txt(t("guifi-web_credentials_saving"));
							$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_file_error")."</div>\n";
							$page .= par(t("guifi-web_credentials_file_error"));
							$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
						}

						/* This does not work as the config. file is written asynchronously
						else if ( filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) == 0 ) {
							$page .= txt(t("guifi-web_credentials_saving"));
							$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_file_empty")."</div>\n";
							$page .= par(t("guifi-web_credentials_file_empty"));
							$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
						}
						*/

						//Good. Check that the config file contents are correct
						else {
							$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

							//Ooops, something was not properly saved
							if ( $GUIFI['USERNAME'] != $_POST['USERNAME'] || $GUIFI['TOKEN'] != $gapi->responses->authToken ) {
								$page .= txt(t("guifi-web_credentials_saving"));
								$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_file_different")."</div>\n";
								$page .= par(t("guifi-web_credentials_file_different"));
								$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
							}

							//Good, data was saved correctly
							else {
								$page .= txt(t("guifi-web_credentials_saving"));
								$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_credentials_file_correct")."</div>\n";
								$page .= par(t("guifi-web_credentials_register"));
								$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
								$buttons .= addButton(array('label'=>t("guifi-web_button_register_continue"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/register'));
							}
						}
						break;

					default:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						break;
				}
			}
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function refresh_credentials(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_refresh_credentials_subtitle"),4);

	$page .= par(t("guifi-web_refresh_credentials_description"));

	if (!file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) || !filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
		$page .= par(t("guifi-web_index_not_registered"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials'));
	}

	else {
		$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		if ( $GUIFI['USERNAME']==null ) {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
			$page .= par(t("guifi-web_index_not_registered"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials'));
		}

		else {

			$form = createForm(array('class'=>'form-horizontal'));
			$form .= addInput('USERNAME',t("guifi-web_refresh_credentials_form_username"),$GUIFI['USERNAME'],array('type'=>'text','required'=>true,'pattern'=>'[A-Za-z0-9_-\s\.]+','readonly'=>true),'',t("guifi-web_refresh_credentials_form_username_tooltip"));
			$form .= addInput('PASSWORD',t("guifi-web_refresh_credentials_form_password"),'',array('type'=>'password', 'required'=>true),'',t("guifi-web_refresh_credentials_form_password_tooltip"));

			$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_refresh'),'class'=>'btn btn-primary'));

			$page .= $form;

			$page .= txt(t("guifi-web_refresh_credentials_security"));
			$page .= "<div class='alert alert-info text-center'>".t("guifi-web_alert_refresh_credentials_security_username")."</div>\n";
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_refresh_credentials_security_password")."</div>\n";
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_refresh_credentials_security_ssl")."</div>\n";

			$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= $fbuttons;


		}
	}
	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function refresh_credentials_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE, $GUIFI_WEB;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_credentials_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_post_empty")."</div>\n";
		$page .= par(t("guifi-web_credentials_post_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
	}

	else if (empty($_POST['USERNAME'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_post_emptyusername")."</div>\n";
		$page .= par(t("guifi-web_credentials_post_emptyusername"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
	}

	else if (empty($_POST['PASSWORD'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_post_emptypassword")."</div>\n";
		$page .= par(t("guifi-web_credentials_post_emptypassword"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
	}

	else {

		$gapi = new guifiAPI( $_POST['USERNAME'], $_POST['PASSWORD'] );

		//$page .= ptxt(print_r($gapi, true));

		//Server could not be reached
		if ( !isset($gapi->responseCode) && $gapi->getErrors() == NULL ) {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_curl_empty")."</div>\n";
			$page .= par(t("guifi-web_credentials_curl_empty"));
			$page .= txt(t("guifi-web_credentials_curl_url"));
			$page .= "<div class='alert alert-info text-center'>".$GUIFI_WEB."/api?command=guifi.auth.login&username=".$_POST['USERNAME']."&password=********</div>\n";
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
		}

		//Server was reached
		elseif ( isset($gapi->responseCode) || isset($gapi->getErrors()[0])) {

			//In case of error
			if ( $gapi->getErrors()) {

				switch($gapi->getErrors()[0]->code) {
					case 401:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_curl_wrong_command")."</div>\n";
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->getErrors(), true));
						$page .= par(t("guifi-web_credentials_curl_wrong_command"));
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
						break;

					case 403:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_credentials_curl_wrong_login")."</div>\n";
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->getErrors(), true));
						$page .= par(t("guifi-web_credentials_curl_wrong_login"));
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
						break;

					default:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_curl_error")."</div>\n";
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->getErrors(), true));
						$page .= par(t("guifi-web_credentials_curl_error"));
						break;
				}
			}

			//No error (apparently)
			else if ( isset($gapi->responseCode) ) {

				switch ($gapi->responseCode->code) {
					//Success
					case 200:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_credentials_curl_ok")."</div>\n";
						$page .= txt(t("guifi-web_credentials_curl_details"));
						$page .= ptxt(print_r($gapi->responses,true));

						if (!file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE))
							touch($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE);
						if (fileperms($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) != "16877" )
							chmod($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, 0644);

						write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, add_quotes(array("USERNAME"=>$_POST['USERNAME'], "TOKEN"=>$gapi->responses->authToken)));

						//Check if config file has been saved
						if ( !file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
							$page .= txt(t("guifi-web_credentials_saving"));
							$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_file_error")."</div>\n";
							$page .= par(t("guifi-web_credentials_file_error"));
							$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
						}

						/* This does not work as the config. file is written asynchronously
						else if ( filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) == 0 ) {
							$page .= txt(t("guifi-web_credentials_saving"));
							$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_file_empty")."</div>\n";
							$page .= par(t("guifi-web_credentials_file_empty"));
							$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
						}
						*/

						//Good. Check that the config file contents are correct
						else {
							$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

							//Ooops, something was not properly saved
							if ( $GUIFI['USERNAME'] != $_POST['USERNAME'] || $GUIFI['TOKEN'] != $gapi->responses->authToken ) {
								$page .= txt(t("guifi-web_credentials_saving"));
								$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_credentials_file_different")."</div>\n";
								$page .= par(t("guifi-web_credentials_file_different"));
								$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
							}

							//Good, data was saved correctly
							else {
								$page .= txt(t("guifi-web_credentials_saving"));
								$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_credentials_file_correct")."</div>\n";
								$page .= par(t("guifi-web_refresh_credentials_success"));
								$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
								//$buttons .= addButton(array('label'=>t("guifi-web_button_register_continue"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/register'));
							}
						}
						break;

					default:
						$page .= txt(t("guifi-web_credentials_curl_authresult"));
						break;
				}
			}
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function register(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_register_subtitle"),4);

	$page .= par(t("guifi-web_register_description"));

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('NODE_ID',t("guifi-web_register_form_nodeid"),'',array('type'=>'number','required'=>true,'min'=>1),'',t("guifi-web_register_form_nodeid_tooltip"));

 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_nodeid'),'class'=>'btn btn-primary'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function register_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$GUIFI_WEB;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_register_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_register_post_empty")."</div>\n";
		$page .= par(t("guifi-web_register_post_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
	}

	else if (empty($_POST['NODE_ID'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_register_post_emptynode")."</div>\n";
		$page .= par(t("guifi-web_register_post_emptynode"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
	}

	else {

		$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		$url = $GUIFI_WEB."/guifi/cnml/".$_POST['NODE_ID']."/node";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$xml = curl_exec($ch);
		$output = new SimpleXMLElement($xml);
		curl_close($ch);

		if (!isset($output->node)) {
			$page .= txt(t("guifi-web_register_curl_noderesult"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_register_wrong_nodeid_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_register_wrong_nodeid_post")."</div>\n";
			$page .= par(t("guifi-web_register_wrong_node"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_register_post_found_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_register_post_found_post")."</div>\n";
			$page .= txt(t("guifi-web_alert_register_post_nodename"));
			$page .= ptxt($output->node['title']);
			$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web/register'));

			write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, add_quotes(array_merge($GUIFI, array("NODEID"=>$_POST['NODE_ID'], "NODENAME"=>$output->node['title']))));

			if (preg_replace('/\s+/', '', $output->node)) {
				$page .= txt(t("guifi-web_alert_register_post_nodedescription"));
				$page .= ptxt($output->node);
			}

			$page .= txt(t("guifi-web_alert_register_post_nodedevices"));
			if ( $output->node['devices'][0] ) {
				$page .= addTableHeader(array(t("guifi-web_register_post_table_id"),t("guifi-web_register_post_table_name"),t("guifi-web_register_post_table_action")));

				$nodeButtons = "";
				$cloudies = 0;
				for ($i = 0; $i < $output->node['devices'][0]; $i++) {
					if ( $output->node->device[$i]['type'] != 'cloudy')
						$page .= addTableRow(
							array(
								$output->node->device[$i]['id'],
								$output->node->device[$i]['title'],
								addButton(array('label'=>t("guifi-web_button_view"),'class'=>'btn btn-default', 'target'=>'_blank', 'href'=>t("guifi-web_register_view_pre").$output->node->device[$i]['id']))
							)
						);
				}
				for ($i = 0; $i < $output->node['devices'][0]; $i++) {
					if ( $output->node->device[$i]['type'] == 'cloudy'){
						$page .= addTableRow(
							array(
								$output->node->device[$i]['id'],
								$output->node->device[$i]['title'],
								addButton(array('label'=>t("guifi-web_button_view"),'class'=>'btn btn-default', 'target'=>'_blank', 'href'=>t("guifi-web_register_view_pre").$output->node->device[$i]['id']))
								.addButton(array('label'=>t("guifi-web_button_register_this"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/guifi-web/register'))
							)
						);
					$cloudies++;
					}
				}
				$page .= addTableFooter();

				if ($cloudies) {
					$page .= "<div class='alert alert-info text-center'>".t("guifi-web_alert_register_post_cloudies")."</div>\n";
					$page .= par(t("guifi-web_register_post_cloudies_pre").' '.$_POST['NODE_ID'].t("guifi-web_register_post_cloudies_post"));
				}

				else {
					$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_register_post_no_cloudies")."</div>\n";
					$page .= par(t("guifi-web_register_post_no_cloudies_pre").' '.$_POST['NODE_ID'].t("guifi-web_register_post_no_cloudies_post"));
				}

				$buttons .= addButton(array('label'=>t("guifi-web_button_register_new"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/new_cloudy'));
			}

			else {
				$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_register_post_no_devices")."</div>\n";
				$page .= par(t("guifi-web_register_post_no_devices"));
				$buttons .= addButton(array('label'=>t("guifi-web_button_register_new"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/new_cloudy'));
			}
			//$page .= ptxt(print_r(htmlspecialchars($xml), true));
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function new_cloudy(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_new_cloudy_subtitle"),4);

	$page .= par(t("guifi-web_new_cloudy_description"));

	$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('NODEID',t("guifi-web_new_cloudy_form_nodeid"),$GUIFI['NODEID'],array('type'=>'number','required'=>true,'min'=>1),'readonly',t("guifi-web_new_cloudy_form_nodeid_tooltip"));
	$form .= addInput('DEVICENAME',t("guifi-web_new_cloudy_form_nick"),$GUIFI['NODENAME'].'-'.'Cloudy',array('type'=>'text','required'=>true,'pattern'=>'[A-Za-z0-9_-]+'),'',t("guifi-web_new_cloudy_form_nick_tooltip"));
	$form .= addInput('EMAIL',t("guifi-web_new_cloudy_form_mail"),'',array('type'=>'email','required'=>true,'placeholder'=>t("guifi-web_new_cloudy_form_mail_placeholder")),'',t("guifi-web_new_cloudy_form_mail_tooltip"));
	$form .= addInput('MAC',t("guifi-web_new_cloudy_form_mac"),strtoupper(getCommunityDevMAC()['output'][0]),array('type'=>'text','required'=>true,'pattern'=>'^([0-9A-F]{2}[:]){5}([0-9A-F]{2})$'),'',t("guifi-web_new_cloudy_form_mac_tooltip"));
	$form .= addInput('DETAILS',t("guifi-web_new_cloudy_form_comment"),'',array('type'=>'textarea','maxlength'=>150,'placeholder'=>t("guifi-web_new_cloudy_form_comment_placeholder")),'',t("guifi-web_new_cloudy_form_comment_tooltip"));

 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_add'),'class'=>'btn btn-success'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}




function new_cloudy_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$fbuttons = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_new_cloudy_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_empty")."</div>\n";
		$page .= par(t("guifi-web_register_add_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['NODEID'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_emptynode")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_emptynode"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['DEVICENAME'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_nick")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_nick"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['EMAIL'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_mail")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_mail"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['MAC'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_mac")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_mac"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	/* //DETAILS are not required
	else if (empty($_POST['DETAILS'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_comment")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_comment"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}
	*/

	else {

		$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		$gapi = new guifiAPI( $GUIFI['USERNAME'], '', $GUIFI['TOKEN'] );

		$node_id = $_POST['NODEID'];
		$type = 'cloudy';
		$mac = $_POST['MAC'];

		$device = array();
		$device['nick'] = $_POST['DEVICENAME'];
		$device['notification'] = $_POST['EMAIL'];
		$device['comment'] = $_POST['DETAILS'];

		$device['status'] = 'Testing';

		$added = $gapi->addDevice($node_id, $type, $mac, $device );

		$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));






		if ( $added && $added->device_id ) {
			write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, add_quotes(array_merge($GUIFI, array("DEVICEID"=>$added->device_id))));

			$page .= txt(t("guifi-web_new_cloudy_result"));
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_new_cloudy_post_success")."</div>\n";
			$page .= txt(t("guifi-web_new_cloudy_deviceid"));
			$page .= ptxt($added->device_id);

			$page .= txt(t("guifi-web_new_cloudy_saving"));
			if ( file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
				$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

				if ( $GUIFI['DEVICEID'] == $added->device_id ) {
					$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_new_cloudy_post_file_correct")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_success"));
				}

				else {
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_file_error")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_file_error"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
				}
			}

			else {
				$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_file_error")."</div>\n";
				$page .= par(t("guifi-web_new_cloudy_file_error"));
				$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
			}
		}

		else {
			$page .= txt(t("guifi-web_new_cloudy_result"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_fail")."</div>\n";
			$page .= txt(t("guifi-web_new_cloudy_details"));
			$page .= ptxt(print_r($gapi->getErrors(),true));

			switch($gapi->getErrors()[0]->code) {
				case 403:
					$page .= txt(t("guifi-web_new_cloudy_post_error"));
					$page .= "<div class='alert alert-error text-center'>".$_POST['DEVICENAME'].': '.t("guifi-web_alert_new_cloudy_post_already_in_use")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_post_already_in_use"));

					$fbuttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/new_cloudy'));
					break;

				case 502:
					$page .= txt(t("guifi-web_new_cloudy_post_error"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_expired")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_credentials_expired"));
					$fbuttons = addButton(array('label'=>t('guifi-web_button_submit_refresh'),'class'=>'btn btn-primary', 'href'=>$staticFile.'/guifi-web/refresh_credentials'));
					break;

				default:
					$page .= par(t("guifi-web_new_cloudy_fail"));

			}
		}


	}

	$buttons .= $fbuttons;
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

function check_credentials() {

}
