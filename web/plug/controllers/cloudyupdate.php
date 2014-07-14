<?php
// Update files
$list_packages = array('cDistro'=>array('user'=>'Clommunity', 'repo'=>'cDistro','type'=>'manual','script'=>'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/cDistro.chroot'),
					   'avahi-ps'=>array('user'=>'Clommunity', 'repo'=>'avahi-ps','type'=>'manual','script'=>'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/avahi-ps.chroot')
					   );
$dir_configs="/etc/cloudy";

function index_get()
{

	global $list_packages,$staticFile;

	$page = "";

	$page .= hl(t("Cloudy Update System"));
	$page .= hl(t("Non Debian packages"),3);
	$page .= ajaxStr('tPackages',t("Loading packages information.") );
	$page .= "<script>\n";
	$page .= "$('#tPackages').load('".$staticFile."/cloudyupdate/getUpdateTable');\n";
	$page .= "</script>\n";
	$page .= hl(t("Debian packages"),3);
	$page .= addButton(array('label'=>t('Update Debian packages'),'href'=>$staticFile.'/cloudyupdate/debupdate'));


	return(array('type' => 'render','page' => $page));
}

function getUpdateTable(){

	global $list_packages,$staticFile;

	$table = "";

	$table = addTableHeader(array(t('Package'), t('Version') , t('Your version'),  t('Actions')));
	foreach($list_packages as $pname => $package){
		$buttons = "";
		$your_version = getYourVersion($package['user'],$package['repo']);
		$act_version = getGitMaster($package['user'],$package['repo']);
		if ($your_version != $act_version) {
			$buttons = addButton(array('label'=>t('Update'),'href'=>$staticFile.'/cloudyupdate/update/'.$pname));
		}
		$table .= addTableRow(array($pname, $act_version, $your_version, $buttons));
	}
	$table .= addTableFooter();

		return(array('type'=>'ajax','page'=>$table));
}

function getYourVersion($user, $repo){
	global $dir_configs;

	if (!is_dir($dir_configs)) {
    	mkdir($dir_configs);         
	}
	$configfile = $dir_configs."/".$user."-".$repo.".sha";
	if (!file_exists($configfile))
		return (t('unknown'));
	else 
		return (str_replace("\n", "",str_replace("\r", "",file_get_contents($configfile))));

}

function getGitMaster($user, $repo){
	$github = "https://api.github.com/repos/" . $user . "/" . $repo . "/git/refs/heads/master";

	$sha = execute_program("curl -s $github | grep 'sha'|awk -F':' '{print $2}'|awk -F'\"' '{print $2}'");

	return($sha['output'][0]);
}

function debupdate() {
	global $staticFile;

	$page = "";

	$page .= hl(t("Cloudy Update System"));
	$cmd = "apt-get update 2>&1";
	$page .= ptxt(shell_exec($cmd));
	$page .= addButton(array('label'=>t('Back'),'href'=>$staticFile.'/cloudyupdate'));


	return(array('type' => 'render','page' => $page));

}

function update(){
	global $staticFile, $Parameters;

	$page = "";

	$page .= hl(t("Cloudy Update System"));

	global $list_packages;
	if (isset($Parameters[0]) && $Parameters[0] != "" ){
		$packet = $Parameters[0];
		if (isset($list_packages[$packet])) {
			$info_packet = $list_packages[$packet];
			$cmd = "mkdir -p /tmp/dir_tmp; cd /tmp/dir_tmp; curl -s " . $info_packet['script'] . "| sh - ; cd /tmp; rm -rf /tmp/dir_tmp";
			$ret = execute_program($cmd);
			$page .= ptxt(implode("\n",$ret['output']));
		}
		else {
			$page .= ptxt(t("Error, this package does not exist."));
		}
	}
	else {
		$page .= ptxt(t("Need parameters."));
	}
	$page .= addButton(array('label'=>t('Back'),'href'=>$staticFile.'/cloudyupdate'));


	return(array('type' => 'render','page' => $page));

}
