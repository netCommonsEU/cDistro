<?php
//tahoe-lafs.php

$RESOURCES_PATH=$_SERVER['DOCUMENT_ROOT'].'/plug/resources/tahoe-lafs';
$TAHOELAFS_CONF="tahoe-lafs.conf";
$TAHOE_VARS=load_conffile($RESOURCES_PATH.'/'.$TAHOELAFS_CONF);

function index(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc(t("Tahoe-LAFS"));
	$page .= hl(t("A cloud storage system that distributes your data across multiple servers"),4);
	$page .= par(t("Tahoe-LAFS is a free and open cloud storage system.").' '.t("It distributes your data across multiple servers.") .' '. t("Even if some of the servers fail or are taken over by an attacker, the entire filesystem continues to function correctly, preserving your privacy and security."));
	$page .= txt(t("Tahoe-LAFS status:"));

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS is not installed on this machine")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>nodes</strong> distributed by the network.") . t("Click on the button to install Tahoe-LAFS and start creating a storage grid or to join an existing one."));
		$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/install'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

	if( ! ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') || tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node') ) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is installed on this machine but has not been configured yet")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Click on the buttons to start creating a storage grid or to join an existing one."));
		$buttons .= addButton(array('label'=>t("Create an introducer and start a storage grid"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/createIntroducer'));
		$buttons .= addButton(array('label'=>t("Create a storage node to join a storage grid"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/createNode'));

		$buttons .= addButton(array('label'=>t("Uninstall Tahoe-LAFS"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/tahoe-lafs/uninstall','divOptions'=>array('class'=>'btn-group')));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') ) {
		if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
			$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer is running")."</div>\n";
		else
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is stopped")."</div>\n";

		$buttons .= addButton(array('label'=>t("Manage introducer"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/introducer','divOptions'=>array('class'=>'btn-group')));
	}
	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node') ) {
		if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
			$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS storage node is running")."</div>\n";
		else
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS storage node is stopped")."</div>\n";

		$buttons .= addButton(array('label'=>t("Manage storage node"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/node','divOptions'=>array('class'=>'btn-group')));
	}
	else
			$buttons .= addButton(array('label'=>t("Create a storage node"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/createNode'));


	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function install(){
	global $RESOURCES_PATH;
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Installation"),4);

	if ( isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= txt(t("Installation process result:"));
 		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS is already installed")."</div>\n";
		$page .= txt(t("Tahoe-LAFS installation information:"));
		$page .= ptxt(packageInstallationInfo("tahoe-lafs"));
 		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

 		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

 	$pkgInstall = ptxt(installPackage($TAHOE_VARS['PACKAGE_NAME']));

	if ( isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= txt(t("Installation process result:"));
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS has been successfully installed")."</div>\n";
		$page .= txt(t("Installation process details:"));
		$page .= $pkgInstall;

		$postInstall = array();
		$postInstallAll = "";

		$page .= txt(t("Post-installation process details:"));
		foreach (execute_program( 'addgroup --system tahoe' )['output'] as $key => $value) { $postInstall[] = $value; }
		foreach (execute_program( 'adduser --system --ingroup tahoe --home '.$TAHOE_VARS['DAEMON_HOMEDIR'].' --shell '.$TAHOE_VARS['DAEMON_SHELL'].' '.$TAHOE_VARS['DAEMON_USERNAME'] )['output'] as $key => $value) { $postInstall[] = $value; }
		execute_program_shell( 'touch ' .$TAHOE_VARS['DAEMON_HOMEDIR'] );
		foreach (execute_program( 'chown -vR '.$TAHOE_VARS['DAEMON_USERNAME'].':'.$TAHOE_VARS['DAEMON_GROUP'].' '.$TAHOE_VARS['DAEMON_HOMEDIR'])['output'] as $key => $value) { $postInstall[] = $value;}
		$postInstall[] = execute_program( 'cp -fv '.$RESOURCES_PATH.'/'.$TAHOE_VARS['TAHOE_INITD_FILE'].' '.$TAHOE_VARS['TAHOE_ETC_INITD_FILE'])['output'][0];
		$postInstall[] = execute_program( 'cp -fv '.$RESOURCES_PATH.'/'.$TAHOE_VARS['TAHOE_DEFAULT_FILE'].' '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'])['output'][0];
		foreach (execute_program( 'chmod -v +x '.$TAHOE_VARS['TAHOE_ETC_INITD_FILE'] )['output'] as $key => $value) { $postInstall[] = $value; }
		$postInstall[] = execute_program( 'update-rc.d '.$TAHOE_VARS['TAHOE_ETC_INITD_FILENAME'].' defaults' )['output'][0];

		foreach ($postInstall as $k => $v) { $postInstallAll .= $v.'<br/>'; }
		$page .= ptxt($postInstallAll);
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
		}

	$page .= txt(t("Installation process result:"));
	$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS installation failed")."</div>\n";
	$page .= txt(t("Installation process details:"));
	$page .= $pkgInstall;
	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
	$buttons .= addButton(array('label'=>t("Retry installation"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/install'));

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function uninstall(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Uninstallation"),4);

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= txt(t("Uninstallation process result:"));
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

 			$page .= $buttons;
			return(array('type' => 'render','page' => $page));
	}

 	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') || tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node')) {
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') ){
		$page .= txt(t("Uninstallation process result:"));
		$page .= "<div class='alert alert-warning text-center'>".t("A Tahoe-LAFS introducer is currently configured.") .' '. t("Stop it and remove it before uninstalling Tahoe-LAFS")."</div>\n";
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS introducer"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/introducer'));
		}
		if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node') ){
		$page .= "<div class='alert alert-warning text-center'>".t("A Tahoe-LAFS node is currently configured.") .' '. t("Stop it and remove it before uninstalling Tahoe-LAFS")."</div>\n";
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS node"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/node'));
		}

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

	$pkgUninstall = ptxt(uninstallPackage("tahoe-lafs"));

	if ( isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= txt(t("Uninstallation process result:"));
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS uninstallation failed")."</div>\n";
		$page .= txt(t("Uninstallation process result:"));
		$page .= $pkgUninstall;
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Retry uninstallation"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/uninstall'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

	$page .= txt(t("Uninstallation process result:"));
	$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS has been successfully uninstalled")."</div>\n";
	$page .= txt(t("Uninstallation process details:"));
	$page .= $pkgUninstall;

	$page .= txt(t("Post-uninstallation process details:"));

	$postUninstall = array();
	$postUninstallAll = "";

	foreach (execute_program( 'update-rc.d tahoe-lafs remove' )['output'] as $key => $value) { $postUninstall[] = $value.'<br/>'; }
	$postUninstall[] = execute_program_shell( 'rm -fv /etc/init.d/tahoe-lafs' )['output'];
	$postUninstall[] = execute_program_shell( 'rm -fv /etc/default/tahoe-lafs' )['output'];
	foreach (execute_program( 'deluser --system --remove-home tahoe' )['output'] as $key => $value) { $postUninstall[] = $value.'<br/>'; }
	foreach (execute_program( 'delgroup --system tahoe' )['output'] as $key => $value) { $postUninstall[] = $value.'<br/>'; }
	$postUninstall[] = execute_program_shell( 'rm -rvf '.$TAHOE_VARS['DAEMON_HOMEDIR'])['output'] ;

	foreach ($postUninstall as $v) { $postUninstallAll .= $v; }
	$page .= ptxt($postUninstallAll);

	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function introducer(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Introducer"),4);

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/install'));

			$page .= $buttons;
 			return(array('type' => 'render','page' => $page));
	}

 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is currently not created")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Click on the button to set up an introducer on this machine.") .' '. t("After that, storage nodes will be able to join the introducer to deploy the storage grid."));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Create an introducer"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/createIntroducer'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
	}

	$page .= ajaxStr('introducerStt',t("Checking Tahoe-LAFS introducer status..."));
	$page .= "\n<script>\n";
	$page .= "loadIntroducerStatus = function() { $('#introducerStt').load('".$staticFile."/tahoe-lafs/introducerStatus') ; } ;";
	$page .= "setInterval( loadIntroducerStatus ,5000) ; loadIntroducerStatus();\n";
	$page .= "</script>\n";

	$page .= $buttons;
 	return(array('type' => 'render','page' => $page));

}

function introducerStatus(){
	global $TAHOE_VARS;

	$r = _introducerStatus($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']);
	return (array('type'=>'ajax', 'page' => $r));
}

function _introducerStatus($homedir,$pidfile) {
	global $TAHOE_VARS;
	global $staticFile;

	$page = '';
	$buttons = '';

	$page .= txt(t("Tahoe-LAFS introducer status:"));

 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is currently not created")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Click on the button to set up an introducer on this machine.") .' '. t("After that, storage nodes will be able to join the introducer to deploy the storage grid."));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Create an introducer"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/createIntroducer'));

		$page .= $buttons;
		return($page);
	}

	if ( introducerStarted($homedir, $pidfile) ) {
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer is running")."</div>\n";

		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Stop Tahoe-LAFS introducer"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/tahoe-lafs/stopIntroducer'));
		if (file_exists($TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer/introducer.public'))
			$buttons .= addButton(array('label'=>t("Make this introducer private"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/unpublishIntroducer'));
		else
			$buttons .= addButton(array('label'=>t("Make this introducer public"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/publishIntroducer'));
	}
	else {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer is stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS introducer"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/startIntroducer'));
		if (file_exists($TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer/introducer.public'))
			$buttons .= addButton(array('label'=>t("Make this introducer private"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/unpublishIntroducer'));
		else
			$buttons .= addButton(array('label'=>t("Make this introducer public"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/publishIntroducer'));
		$buttons .= addButton(array('label'=>t("Delete Tahoe-LAFS introducer"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/tahoe-lafs/deleteIntroducer'));
	}

	$page .= txt(t("Grid name:"));
	$page .= ptxt(file_get_contents($TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer/grid.name'));
	$page .= txt(t("Introducer FURL:"));
	$page .= ptxt(execute_program("sed 's/,127\.0\.0\.1:.*\//\//' ".$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['INTRODUCER_FURLFILE']. " | sed 's/,192\.168\..*\..*:.*\//\//' ")['output'][0]);
	$page .= txt(t("Tahoe-LAFS introducer web page:"));
	$webPage = 'http://'.substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':')+1). file_get_contents($TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['WEBPORT_FILENAME']); ;
	$page .= ptxt('<a href="'.$webPage.'" target=_blank>'.$webPage.'</a>');
	$page .= txt(t("Service announcement:"));
	if (file_exists($TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['INTRODUCER_PUBLIC_FILE']))
		$page .= ptxt(t("The storage grid is public and is being announced via Avahi"));
	else
		$page .= ptxt(t("The storage grid is private"));

	$page .= $buttons;

	return($page);
}

function createIntroducer(){
	global $TAHOE_VARS;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Introducer creation"),4);

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/install'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Use this page to set up an introducer on this machine and start a storage grid.") .' '. t("After that, storage nodes will be able to join the introducer to deploy the storage grid."));
		$page .= createForm(array('class'=>'form-horizontal'));
		$page .= addInput('GRID_NAME',t("Grid name"),'Example-Grid-'.sprintf('%03d',mt_rand(0,999)),'','',t("A short name to identify the storage grid."));
		$page .= addInput('INTRODUCER_NAME',t('Introducer name'),'MyIntroducer','','',t("A short nickname to identify the introducer in the storage grid."));
		$page .= addInput('INTRODUCER_WEBPORT',t('Web port'),8228,array('type'=>'number','min'=>'1024','max'=>'65535'),'',t("The port where the introducer's web management interface will run on."));
		$page .= addInput('INTRODUCER_DIR',t('Folder'),$TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer','','readonly',t("The introducer will be installed in this folder."));
		$page .= addInput('INTRODUCER_PUBLIC',t('Public'), true, array('type'=>'checkbox'),'checked',t("Announce the introducer service through Avahi and allow storage nodes to join the grid."),'no');
 		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
 		$buttons .= addSubmit(array('label'=>t('Create introducer'),'class'=>'btn btn-success'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
	}

	$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is already created")."</div>\n";
	$page .= txt(t("Tahoe-LAFS introducer status:"));

	if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']))
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer is running")."</div>\n";
	else
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer is stopped")."</div>\n";

		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS introducer"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/introducer'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
}

function createIntroducer_post(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Introducer creation"),4);

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/install'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

 	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is already created")."</div>\n";
		$page .= txt(t("Tahoe-LAFS introducer status:"));

		if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']))
			$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer is running")."</div>\n";
		else
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer is stopped")."</div>\n";

		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS introducer"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/introducer'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
 	}

	if (empty($_POST)) {
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer creation failed")."</div>\n";
			$page .= "<div class='alert alert-warning text-center'>".t("Incorrect request parameters received")."</div>\n";
			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
			$buttons .= addButton(array('label'=>t("Retry introducer creation"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/createIntroducer'));

			$page .= $buttons;
 			return(array('type' => 'render','page' => $page));
 	}

	$validPost = true;

	if ( empty($_POST['GRID_NAME']) || preg_match("/[^-\w]/i", $_POST['GRID_NAME']) || strlen($_POST['GRID_NAME'] > 80) ) {
		$validPost = false;
		$page .= "<div class='alert alert-error text-center'>".t("Invalid storage grid name").': ' . htmlspecialchars(substr($_POST['GRID_NAME'],0,70)) . "</div>\n";
	}
	if ( empty($_POST['INTRODUCER_NAME']) || preg_match("/[^-\w]/i", $_POST['INTRODUCER_NAME']) || strlen($_POST['INTRODUCER_NAME'] > 80) ) {
		$validPost = false;
		$page .= "<div class='alert alert-error text-center'>".t("Invalid introducer name").': ' . htmlspecialchars(substr($_POST['INTRODUCER_NAME'],0,70)) . "</div>\n";
	}
	if ( empty($_POST['INTRODUCER_WEBPORT']) || ( $_POST['INTRODUCER_WEBPORT'] > 65535 || $_POST['INTRODUCER_WEBPORT'] < 1024 ) )  {
		$validPost = false;
		$page .= "<div class='alert alert-error text-center'>".t("Invalid introducer web port number").': ' . htmlspecialchars(substr($_POST['INTRODUCER_WEBPORT'],0,10)) . "</div>\n";
	}
	if(!$validPost) {
			$page .= "<div class='alert alert-warning text-center'>".t("A maximum of 80 alphanumeric characters, dashes and underscores are allowed in the names")."</div>\n";
			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
			$buttons .= addButton(array('label'=>t("Retry introducer creation"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/createIntroducer'));
			$page .= $buttons;
 			return(array('type' => 'render','page' => $page));
	}

	$postCreate = array();
	foreach (execute_program( $TAHOE_VARS['TAHOE_COMMAND'].' create-introducer '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'])['output'] as $k => $v) { $postCreate[] = $v; }
	execute_program_shell( 'sed -i "s/^nickname.*$/nickname = '.$_POST['INTRODUCER_NAME'].'/" '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE'] );
	execute_program_shell( 'echo '.$_POST['INTRODUCER_WEBPORT'].' >> '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['WEBPORT_FILENAME']);
	if ($_POST['INTRODUCER_PUBLIC']){
		execute_program_shell( 'touch '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['INTRODUCER_PUBLIC_FILE']);
		execute_program_shell( 'sed -i "s/^web\.port.*$/web\.port = tcp:'.intval($_POST['INTRODUCER_WEBPORT']).':interface=0.0.0.0/" '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE']);
	}
	else
		execute_program_shell( 'sed -i "s/^web\.port.*$/web\.port = tcp:'.intval($_POST['INTRODUCER_WEBPORT']).':interface=127.0.0.1/" '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE']);
	execute_program_shell( 'echo '.$_POST['GRID_NAME'].' >> '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['INTRODUCER_GRIDNAME_FILE']);

	if( execute_shell( "grep -q '^AUTOSTART' ".$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/^AUTOSTART=\"[^\"]*/& introducer /" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
	}
	else
		execute_program_shell( 'echo "AUTOSTART=\"introducer\"" >> '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );

	foreach (execute_program( 'chown -vR tahoe:tahoe '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'] )['output'] as $key => $value) { $postCreate[] = $value; }

	$postCreateAll = "";
		foreach ($postCreate as $k => $v) { $postCreateAll .= $v.'<br/>'; }

	$page .= txt(t("Introducer creation process result:"));
	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['INTRODUCER_DIRNAME']))
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer successfully created")."</div>\n";
	else
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer creation failed")."</div>\n";

	$page .= ptxt($postCreateAll);

	$postStart = array();
	foreach (execute_program_shell( $TAHOE_VARS['TAHOE_ETC_INITD_FILE'].' start '.$TAHOE_VARS['INTRODUCER_DIRNAME'])['output'] as $k => $v) { $postStart[] = $v; }

	//This pause is needed in order to let the server start before showing the success/error text
	sleep(2);
	foreach (execute_program_shell( 'ps aux | grep tahoe | grep introducer | grep python | grep -v grep') as $k => $v) { $postStart[] = $v; }

	execute_program_shell($TAHOE_VARS['AVAHI_SERVICE_COMMAND'].' enable '.$TAHOE_VARS['AVAHI_SERVICE_TAHOE'].' >/dev/null 2>&1');
	execute_program_shell($TAHOE_VARS['AVAHI_SERVICE_COMMAND'].' start '.$TAHOE_VARS['AVAHI_SERVICE_TAHOE'].' >/dev/null 2>&1');

	$postStartAll = "";
		foreach ($postStart as $k => $v) { $postStartAll .= $v.'<br/>'; }


	$page .= txt(t("Starting Tahoe-LAFS introducer:"));
	if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer successfully started")."</div>\n";
	else {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer start failed")."</div>\n";
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS introducer"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/startIntroducer'));
		}
	$page .= ptxt($postStartAll);

	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
	$buttons .= addButton(array('label'=>t("Manage introducer"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/introducer','divOptions'=>array('class'=>'btn-group')));

	$page .= $buttons;
 	return(array('type' => 'render','page' => $page));
}

function node(){

	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Storage node"),4);

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/install'));

			$page .= $buttons;
 			return(array('type' => 'render','page' => $page));
	}

 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['NODE_DIRNAME'])) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS storage node is currently not created")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Click on the button to set up a storage node on this machine and join a storage grid."));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Create a storage node"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/createNode'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
	}

	$page .= ajaxStr('nodeStt',t("Checking Tahoe-LAFS storage node status..."));
	$page .= "\n<script>\n";
	$page .= "loadNodeStatus = function() { $('#nodeStt').load('".$staticFile."/tahoe-lafs/nodeStatus') ; } ;";
	$page .= "setInterval( loadNodeStatus ,5000) ; loadNodeStatus();\n";
	$page .= "</script>\n";

	$page .= $buttons;
 	return(array('type' => 'render','page' => $page));

}

function nodeStatus(){
	global $TAHOE_VARS;

	$r = _nodeStatus($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']);
	return (array('type'=>'ajax', 'page' => $r));
}

function _nodeStatus($homedir,$pidfile) {
	global $TAHOE_VARS;
	global $staticFile;

	$page = '';
	$buttons = '';

	$page .= txt(t("Tahoe-LAFS storage node status:"));

 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['NODE_DIRNAME'])) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS storage node is currently not created")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Click on the button to set up a storage node on this machine and join a storage grid."));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Create a storage node"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/createNode'));

		$page .= $buttons;
		return($page);
	}

	if ( nodeStarted($homedir, $pidfile) ) {
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS storage node is running")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Stop Tahoe-LAFS storage node"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/tahoe-lafs/stopNode'));
	}
	else {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS storage node is stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS storage node"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/startNode'));
		$buttons .= addButton(array('label'=>t("Delete Tahoe-LAFS storage node"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/tahoe-lafs/deleteNode'));
	}

	$page .= txt(t("Introducer FURL:"));
	$page .= ptxt(execute_program_shell('grep "^introducer\.furl" '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['NODE_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE']." | sed 's/introducer\.furl = //'")['output']);
	$page .= txt(t("Tahoe-LAFS storage node web page (only accessible from localhost):"));
	$webPage = 'http://'.substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':')+1).'3456';
	$page .= ptxt('<a href="'.$webPage.'" >'.$webPage.'</a>');

	$page .= $buttons;

	return($page);
}

function createNode_get(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Storage node creation"),4);

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/install'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['NODE_DIRNAME'])) {
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Use this page to set up a storage node and join a storage grid."));
		$page .= createForm(array('class'=>'form-horizontal'));
		$page .= addInput('NODE_NICKNAME',t('Storage node name'),'MyStorageNode','',t("A short nickname to identify the storage node in the grid."));

		if(isset($_GET['furl']) && (! is_null($_GET['furl'])))
			$page .= addInput('NODE_INTRODUCER_FURL',t('Introducer FURL'),$_GET['furl'],array('class'=>'input-xxlarge'),'readonly',t("The introducer's FURL of the storage grid you want to join.")." ".t("This value has been obtained from the information published by the introducer via Avahi.")."<br/>".t("If you want to modify this field, please go back to the main Tahoe-LAFS page and manually create a storage node."));
		else
			if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['INTRODUCER_DIRNAME']))
				$page .= addInput('NODE_INTRODUCER_FURL',t('Introducer FURL'),file_get_contents($TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['INTRODUCER_FURLFILE']),array('class'=>'input-xxlarge'),'',t("The introducer's FURL of the storage grid you want to join.")." ".t("The default value has been obtained from the introducer running on this host.")."<br/>".t("If you want to join another introducer, please modify this field accordingly."));
			else
				$page .= addInput('NODE_INTRODUCER_FURL',t('Introducer FURL'),'',array('class'=>'input-xxlarge'),'',t("The introducer's FURL of the storage grid you want to join."));

		$page .= addInput('NODE_DIR',t('Folder'),$TAHOE_VARS['DAEMON_HOMEDIR'].'/node','','readonly',t("The installation path for the storage node."));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addSubmit(array('label'=>t('Create storage node'),'class'=>'btn btn-success'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
	}

	if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE'])) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS storage node is already created")."</div>\n";
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS storage node is running")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS storage node"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/node'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
	}

	else {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS storage node is already created")."</div>\n";
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS storage node is stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS storage node"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/node'));

		$page .= $buttons;
 		return(array('type' => 'render','page' => $page));
	}
}

function createNode_post(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Storage node creation"),4);

	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/install'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

 	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node')) {
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node is already created")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
			$buttons .= addButton(array('label'=>t("Manage storage node"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/node'));

			$page .= $buttons;
 			return(array('type' => 'render','page' => $page));
 	}

 	$validPost = true;

	if ( empty($_POST['NODE_NICKNAME']) || preg_match("/[^-\w]/i", $_POST['NODE_NICKNAME']) || strlen($_POST['NODE_NICKNAME'] > 80) ) {
		$validPost = false;
		$page .= "<div class='alert alert-error text-center'>".t("Invalid storage node name").': ' . htmlspecialchars(substr($_POST['NODE_NICKNAME'],0,70)) . "</div>\n";
	}
	  if ( empty($_POST['NODE_INTRODUCER_FURL']) || !preg_match("^((pb:\/\/)([a-zA-Z0-9]{16,256})(@)([a-zA-Z0-9@:%_\+,.~#\?&\/=]{5,256})(:)([0-9]+)(\/)([a-zA-Z0-9\._-]{1,81}))", $_POST['NODE_INTRODUCER_FURL']) ) {
		$validPost = false;
		$page .= "<div class='alert alert-error text-center'>".t("Invalid introducer FURL").': ' . htmlspecialchars(substr($_POST['NODE_INTRODUCER_FURL'],0,100)) . "</div>\n";
	}

	if(!$validPost) {
			$page .= "<div class='alert alert-warning text-center'>".t("A maximum of 80 alphanumeric characters, dashes and underscores are allowed in the names")."</div>\n";
			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
			$buttons .= addButton(array('label'=>t("Retry storage node creation"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/tahoe-lafs/createNode'));
			$page .= $buttons;
 			return(array('type' => 'render','page' => $page));
	}

	$postCreate = array();
	foreach (execute_program( $TAHOE_VARS['TAHOE_COMMAND'].' create-node '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['NODE_DIRNAME'])['output'] as $k => $v) { $postCreate[] = $v; }
	execute_program_shell( 'sed -i "s/^nickname.*$/nickname = '.$_POST['NODE_NICKNAME'].'/ "'.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['NODE_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE'] );
	execute_program_shell( 'sed -i "s%^introducer\.furl.*$%introducer\.furl = '.$_POST['NODE_INTRODUCER_FURL'].'%" '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['NODE_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE'] );

	if( execute_shell( "grep -q '^AUTOSTART' ".$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/^AUTOSTART=\"[^\"]*/& node /" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );
	}
	else
		execute_program_shell( 'echo "AUTOSTART=\"node\"" >> '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE'] );

	foreach (execute_program( 'chown -vR tahoe:tahoe '.$TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['NODE_DIRNAME'])['output'] as $key => $value) { $postCreate[] = $value; }
	$postCreateAll = "";
		foreach ($postCreate as $k => $v) { $postCreateAll .= $v.'<br/>'; }

	$page .= txt(t("Node creation process result:"));
	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['NODE_DIRNAME']))
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node successfully created")."</div>\n";
	else
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node creation failed")."</div>\n";

	$page .= ptxt($postCreateAll);

	$postStart = array();
	foreach (execute_program( '/etc/init.d/tahoe-lafs start node')['output'] as $k => $v) { $postStart[] = $v; }
	//This pause is needed in order to let the server start before showing the success/error text
	sleep(2);
	foreach (execute_program( 'ps aux | grep tahoe | grep node | grep -v grep')['output'] as $k => $v) { $postStart[] = $v; }

	$postStartAll = "";
		foreach ($postStart as $k => $v) { $postStartAll .= $v.'<br/>'; }

	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
	$buttons .= addButton(array('label'=>t("Manage storage node"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/node'));

	$page .= txt(t("Starting Tahoe-LAFS node:"));
	if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node successfully started")."</div>\n";
	else {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node start failed")."</div>\n";
		$buttons .= addButton(array('label'=>t("Start storage node"),'class'=>'btn btn-success', 'href'=>$staticFile.'/tahoe-lafs/startNode'));
		}
	$page .= ptxt($postStartAll);

	$page .= $buttons;

 	return(array('type' => 'render','page' => $page));
}


function deleteIntroducer(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Introducer deletion"),4);


	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['INTRODUCER_DIRNAME'])) {
 		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is not created")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
 	}

	if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is not stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Manage introducer"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/introducer',));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
 	}

	$postDelete = array();
	$postDeleteAll = "";

	execute_program_shell($TAHOE_VARS['AVAHI_SERVICE_COMMAND'].' stop '.$TAHOE_VARS['AVAHI_SERVICE_TAHOE'].' >/dev/null 2>&1');
	execute_program_shell($TAHOE_VARS['AVAHI_SERVICE_COMMAND'].' disable '.$TAHOE_VARS['AVAHI_SERVICE_TAHOE'].' >/dev/null 2>&1');

	foreach (execute_program( 'rm -vrf '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/*')['output'] as $k => $v) { $postDelete[] = $v; }

	if( execute_shell( "grep -q '^AUTOSTART' /etc/default/tahoe-lafs" )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/introducer/ /" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
	}

	foreach ($postDelete as $k => $v) { $postDeleteAll .= $v.'<br/>'; }
	rmdir($TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME']);

	$page .= txt(t("Introducer deletion process result:"));

	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['INTRODUCER_DIRNAME']))
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer deletion failed")."</div>\n";
	else
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer successfully deleted")."</div>\n";
	$page .= ptxt($postDeleteAll);

	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function deleteNode(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Storage node deletion"),4);


	if ( ! isTahoeInstalled($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['NODE_DIRNAME'])) {
 		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS storage node is not created")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
 	}

	if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS storage node is not stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Manage storage node"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/tahoe-lafs/node'));

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
 	}

	$postDelete = array();
	$postDeleteAll = "";
	foreach (execute_program( 'rm -vrf '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['NODE_DIRNAME'].'/*')['output'] as $k => $v) { $postDelete[] = $v; }

	if( execute_shell( "grep -q '^AUTOSTART' /etc/default/tahoe-lafs" )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/node/ /" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/\" /\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
		execute_program_shell( 'sed -i "s/ \"/\"/" '.$TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']);
	}

	foreach ($postDelete as $k => $v) { $postDeleteAll .= $v.'<br/>'; }
	rmdir($TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['NODE_DIRNAME']);
	$page .= txt(t("Storage deletion process result:"));

	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['NODE_DIRNAME']))
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS storage deletion failed")."</div>\n";
	else
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS storage node successfully deleted")."</div>\n";
	$page .= ptxt($postDeleteAll);

	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>$staticFile.'/tahoe-lafs'));

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function detached_exec($cmd) {

	$pid = pcntl_fork();

   switch($pid) {
		case -1 :
			return false;
		case 0 :
          posix_setsid();
          exec($cmd);
          break;
      default:
          return $pid;
    }
}

function startIntroducer(){
	global $staticFile;
	global $TAHOE_VARS;

   $pid = detached_exec($TAHOE_VARS['TAHOE_ETC_INITD_FILE'].' start introducer >/dev/null 2>&1');

	setFlash(t('Starting Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/introducer'));
}

function stopIntroducer(){
	global $staticFile;
	global $TAHOE_VARS;

   $pid = detached_exec($TAHOE_VARS['TAHOE_ETC_INITD_FILE'].' stop introducer >/dev/null 2>&1');

	setFlash(t('Stopping Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/introducer'));
}

function restartIntroducer(){
	global $staticFile;
	global $TAHOE_VARS;

   $pid = detached_exec($TAHOE_VARS['TAHOE_ETC_INITD_FILE'].' restart introducer >/dev/null 2>&1');

	setFlash(t('Restarting Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/introducer'));
}

function restartAndPublishIntroducer(){
	global $staticFile;
	global $TAHOE_VARS;

   $pid = detached_exec($TAHOE_VARS['TAHOE_ETC_INITD_FILE'].' restart introducer >/dev/null 2>&1');
	sleep (1);

	setFlash(t('Restarting Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/startAvahi'));
}

function startNode(){
	global $staticFile;
	global $TAHOE_VARS;

   $pid = detached_exec($TAHOE_VARS['TAHOE_ETC_INITD_FILE'].' start node >/dev/null 2>&1');

	setFlash(t('Starting Tahoe-LAFS storage node...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/node'));
}

function stopNode(){
	global $staticFile;
	global $TAHOE_VARS;

   $pid = detached_exec($TAHOE_VARS['TAHOE_ETC_INITD_FILE'].' stop node >/dev/null 2>&1');

	setFlash(t('Stopping Tahoe-LAFS storage node...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/node'));
}

function startAvahi(){
	global $staticFile;
	global $TAHOE_VARS;

	sleep (1);
   $pid = detached_exec($TAHOE_VARS['AVAHI_SERVICE_COMMAND'].' start '.$TAHOE_VARS['AVAHI_SERVICE_TAHOE'].' >/dev/null 2>&1');
   $pid = detached_exec($TAHOE_VARS['AVAHI_SERVICE_COMMAND'].' start '.$TAHOE_VARS['AVAHI_SERVICE_TAHOE'].' >/dev/null 2>&1');

	setFlash(t('Publishing Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/introducer'));
}

function stopAvahi(){
	global $staticFile;
	global $TAHOE_VARS;

   $pid = detached_exec($TAHOE_VARS['AVAHI_SERVICE_COMMAND'].' stop '.$TAHOE_VARS['AVAHI_SERVICE_TAHOE'].' >/dev/null 2>&1');

	setFlash(t('Publishing Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/restartIntroducer'));
}

function publishIntroducer(){
	global $staticFile;
	global $TAHOE_VARS;

	$page = "";
	$buttons = "";

	execute_program_shell( 'touch '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer/introducer.public');
	execute_program_shell( 'chown '.$TAHOE_VARS['DAEMON_USERNAME'].':'.$TAHOE_VARS['DAEMON_GROUP'].' '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer/introducer.public');
	execute_program_shell( "sed -i 's/^web\.port.*/web\.port = tcp:".intval(file_get_contents($TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['WEBPORT_FILENAME'])).":interface=0\.0\.0\.0/' ".$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE'] );

	setFlash(t('Publishing Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/restartAndPublishIntroducer'));
}

function unpublishIntroducer(){
	global $staticFile;
	global $TAHOE_VARS;

	execute_program_shell( 'rm -vf '.$TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer/introducer.public');
	execute_program_shell( "sed -i 's/^web\.port.*/web\.port = tcp:".intval(file_get_contents($TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['WEBPORT_FILENAME'])).":interface=127\.0\.0\.1/' ".$TAHOE_VARS['DAEMON_HOMEDIR'].'/'.$TAHOE_VARS['INTRODUCER_DIRNAME'].'/'.$TAHOE_VARS['TAHOE_CONFIG_FILE'] );

	setFlash(t('Unpublishing Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/stopAvahi'));
}

function tahoeCreated($dir,$name) {
	return (is_dir($dir.'/'.$name));
}

function introducerStarted($dir,$pidfile){
	if (is_file("$dir/introducer/$pidfile"))
		return 1;
	else
		return 0;
}

function nodeStarted($dir,$pidfile){
	if (is_file("$dir/node/$pidfile"))
		return 1;
	else
		return 0;
}

function isTahoeInstalled($pkg){
	global $TAHOE_VARS;

	if ( isPackageInstall($pkg) && is_dir($TAHOE_VARS['DAEMON_HOMEDIR']) && is_file($TAHOE_VARS['TAHOE_ETC_INITD_FILE']) && is_file($TAHOE_VARS['TAHOE_ETC_DEFAULT_FILE']))
		return 1;
	else
		return 0;
}


?>