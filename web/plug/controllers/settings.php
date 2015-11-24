<?php
// settings.php

$urlpath="settings";

$PKG_HOSTNAME = "hostname";
$HREGEX = "^([a-zA-Z0-9]|[a-zA-Z0-9]+[a-zA-Z0-9\-]*[a-zA-Z0-9])[a-zA-Z0-9]*";
$HPREGEX = "/".$HREGEX."/";


function index() {

	global $urlpath, $staticPath;

	$page = "";
	$buttons = "";

	$page .= hlc(t("settings_common_title"));
	$page .= hl(t("settings_common_subtitle"),4);

	$page .= par(t("settings_index_description"));


	$page .= hlc(t("settings_hostname_title"),2);
	$page .= txt(t("settings_hostname_current"));
	$page .= ptxt(gethostname());

	$buttons .= addButton(array('label'=>t("settings_button_hostname"),'class'=>'btn btn-primary', 'href'=>$staticPath.$urlpath.'/hostname'));
	$page .= $buttons;

	return(array('type'=>'render','page'=>$page));
}


function hostname() {

	global $staticPath, $PKG_HOSTNAME, $HREGEX, $HPREGEX;

	$page = "";
	$buttons = "";

	$page .= hlc(t("settings_hostname_title"),1);
	$page .= hl(t("settings_hostname_subtitle"),4);

	$page .= par(t("settings_hostname_description"));

	if (isset ($_POST['HOSTNAME'])) {
		if ( preg_match ($HPREGEX, $_POST['HOSTNAME'] ))
			return setHostname($_POST['HOSTNAME']);
		else {
			$page .= txt(t("settings_hostname_invalid"));
			if (strlen($_POST['HOSTNAME']))
				$page .= "<div class='alert alert-error text-center'>".'"'.$_POST['HOSTNAME'].'"'."</div>\n";
			else
				$page .= "<div class='alert alert-error text-center'>".t("settings_hostname_empty")."</div>\n";
		}
	}

	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('HOSTNAME',t('settings_hostname_fname'),gethostname(),array('type'=>'text', 'required'=>false, "placeholder"=>t('settings_hostname_fplaceholder'), "pattern"=>$HREGEX),'',t("settings_hostname_ftooltip"));
	$buttons .= addSubmit(array('label'=>t('settings_button_shostname'),'class'=>'btn btn-success'));

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));

}

function setHostname($newhostname){

	global $PKG_HOSTNAME, $staticPath, $urlpath, $HPREGEX;

	if (isPackageInstall($PKG_HOSTNAME) && preg_match ($HPREGEX, $newhostname)) {

		$cmd = "hostname"." ".$newhostname;
		shell_exec($cmd);

		if ($newhostname == gethostname()) {
			setFlash(t('settings_flash_hostname'),"success");
			return(array('type'=>'redirect','url'=>$staticPath.$urlpath));
		}
	}

	setFlash(t('settings_flash_hostnamefail'),"error");
	return(array('type'=>'redirect','url'=>$staticPath.$urlpath));
}


?>