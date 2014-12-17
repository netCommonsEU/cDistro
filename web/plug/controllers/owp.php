<?php
// controllers/owp.php
$webpage="https://github.com/sibprogrammer/owp";
$urlpath='/owp';
$dirpath="/opt/ovz-web-panel";
$owpinit="/etc/init.d/owp";
$githubgeturl='https://raw.githubusercontent.com/agustim/owp-install/master/getgithub';



function _installed_OWP(){
	global $dirpath;
	return(is_dir($dirpath));
}

function _run_OWP(){
	$cmd = 'netstat -an|grep LISTEN|grep -q ":3000"';
	$ret = execute_shell($cmd);
	return($ret['return'] ==  0 );	
}

function getprogram(){
	global $staticFile, $githubgeturl, $urlpath;

	$cmd = "cd /tmp && curl ".$githubgeturl."| sh -";
	$ret = shell_exec($cmd);

	//setFlash(t('OpenVZWebPanel_was_installed'),"success");
	setFlash($cmd,"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));	
}

function removeprogram(){
	global $urlpath, $staticFile;

	setFlash(t('This feature is not yet implemented.'),"error");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function runprogram(){
	global $urlpath, $staticFile, $owpinit;

	$cmd = $owpinit." start";
	execute_program_detached($cmd);

	setFlash(t('owp_start'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function stopprogram(){
	global $urlpath, $staticFile, $owpinit;

	$cmd = $owpinit." stop";
	execute_program_detached($cmd);

	setFlash(t('owp_stop'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}


function index()
{
	global $urlpath;
	
	$page = "";
	$buttons = "";
	
	$page .= hlc(t("openvzwp_title"));
	$page .= hl(t("openvzwp_subtitle"),4);
   $page .= par(t("openvzwp_description"));

	if (!_installed_OWP()) {
		$page .= "<div class='alert alert-error text-center'>".t("openvzwp_not_installed")."</div>\n";
   	$page .= par(t("openvzwp_installation_explanation"));
		$buttons .= addButton(array('label'=>t("openvzwp_button_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/getprogram"));
	}
	else {
		if (!_run_OWP()) {
			$page .= "<div class='alert alert-warning text-center'>".t("openvzwp_not_running")."</div>\n";
			$buttons .= addButton(array('label'=>t("openvzwp_button_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/runprogram"));
			$buttons .= addButton(array('label'=>t("openvzwp_button_uninstall"),'class'=>'btn btn-danger', 'href'=>"$urlpath/removeprogram"));
		}
		else {
			$page .= "<div class='alert alert-success text-center'>".t("openvzwp_running")."</div>\n";
			$host = explode(':', $_SERVER['HTTP_HOST'])[0];
			$buttons .= addButton(array('label'=>t("openvzwp_button_go_to"),'class'=>'btn btn-primary', 'href'=>"http://".$host.":3000"));
			$buttons .= addButton(array('label'=>t("openvzwp_button_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stopprogram"));
		}
	}
 	
 	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}
