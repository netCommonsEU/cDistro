<?php
// controllers/owp.php
$title="OpenVZ Web Panel";
$webpage="https://code.google.com/p/ovz-web-panel/";
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
	global $title, $webpage, $urlpath;

	$page=hlc(t($title));

	$page .= hl(t("<a href='" . $webpage. "'>OpenVZ Web Panel</a> is a GUI web-based frontend for controlling of the hardware and virtual servers with the OpenVZ virtualization technology."),4);

	$page .= '<p>';
	if (!_installed_OWP()) {
		$page .= "<div class='alert alert-error'>".t("$title is not installed")."\n";
		$page .= "<br/>";
		$page .= t("La instal·lació de OpenVZ Web Panel ha de descarregar el programari, instal·lar-lo i tornar a iniciar l'ordinador. Durant l'instal·lació podeu anar a fer un café.");
		$page .= addButton(array('label'=>t("Install $title"),'class'=>'btn', 'href'=>"$urlpath/getprogram", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";

	} else {
		$page .= "<div class='alert alert-success'>".t("$title is installed")."\n";
		$page .= addButton(array('label'=>t("Uninstall $title"),'class'=>'btn', 'href'=>"$urlpath/removeprogram", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";
		
	}
	$page .= '</p>';

	if (_installed_OWP()){
		$page .= '<p>';
		if (!_run_OWP()) {
			$page .= "<div class='alert alert-error'>".t("$title is not running")."\n";
			$page .= addButton(array('label'=>t("Start $title"),'class'=>'btn', 'href'=>"$urlpath/runprogram", 'divOptions'=>array('class'=>'pull-right')));
			$page .="</div>";

		} else {
			$page .= "<div class='alert alert-success'>".t("$title is running")."\n";
			$page .= addButton(array('label'=>t("Stop $title"),'class'=>'btn', 'href'=>"$urlpath/stopprogram", 'divOptions'=>array('class'=>'pull-right')));
			$page .="</div>";
		
		}
		$page .= '</p>';
	}

	if (_run_OWP()) {
		$host = explode(':', $_SERVER['HTTP_HOST'])[0];
		$page .= "<p>";
		$page .= t("Go to ")."<a href='http://".$host.":3000'>".t("Web Panel")."</a>";
		$page .= "</p>";
	}

	return(array('type' => 'render','page' => $page));
}
