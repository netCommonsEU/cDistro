<?php
//tahoe-lafs.php

$RESOURCES_PATH=$_SERVER['DOCUMENT_ROOT'].'/plug/resources/tahoe-lafs';
$TAHOELAFS_CONF="tahoe-lafs.conf";
$TAHOE_VARS=load_conffile($RESOURCES_PATH.'/'.$TAHOELAFS_CONF);

function index(){
	global $TAHOE_VARS;
		
	$page = "";
	$buttons = "";
	
	$page .= hlc(t("Tahoe-LAFS"));
	$page .= hl(t("A cloud storage system that distributes your data across multiple servers."),4);
	$page .= par(t("Tahoe-LAFS is a free and open cloud storage system. It distributes your data across multiple servers.") .' '. t("Even if some of the servers fail or are taken over by an attacker, the entire filesystem continues to function correctly, preserving your privacy and security."));
	$page .= txt(t("Tahoe-LAFS status:"));
		
	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS is not installed on this machine")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>nodes</strong> distributed by the network.") . t("Click on the button to install Tahoe-LAFS and start creating a storage grid or to join an existing one."));
		$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'tahoe-lafs/install','divOptions'=>array('class'=>'btn-group')));
	
		$page .= $buttons;	
		return(array('type' => 'render','page' => $page));
	}
	
	if( ! ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') || tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node') ) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is installed on this machine but has not been configured yet")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>nodes</strong> distributed by the network.") .' '. t("Click on the button to install Tahoe-LAFS and start creating a storage grid or to join an existing one."));
		$buttons .= addButton(array('label'=>t("Create an introducer and start a storage grid"),'class'=>'btn btn-success', 'href'=>'tahoe-lafs/createIntroducer','divOptions'=>array('class'=>'btn-group')));
		$buttons .= addButton(array('label'=>t("Create a storage node and join a storage grid"),'class'=>'btn btn-success', 'href'=>'tahoe-lafs/createIntroducer','divOptions'=>array('class'=>'btn-group')));

		$buttons .= addButton(array('label'=>t("Uninstall Tahoe-LAFS"),'class'=>'btn btn-danger', 'href'=>'tahoe-lafs/uninstall','divOptions'=>array('class'=>'btn-group')));
		
		$page .= $buttons;	
		return(array('type' => 'render','page' => $page));	
	}

	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') ) {
		if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
			$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer running")."</div>\n";
		else
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer stopped")."</div>\n";
		
		$buttons .= addButton(array('label'=>t("Manage introducer"),'class'=>'btn btn-primary', 'href'=>'tahoe-lafs/introducer','divOptions'=>array('class'=>'btn-group')));
	}
	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node') ) {
		if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
			$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node running")."</div>\n";
		else
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS node stopped")."</div>\n";
		
		$buttons .= addButton(array('label'=>t("Manage node"),'class'=>'btn btn-primary', 'href'=>'tahoe-lafs/node','divOptions'=>array('class'=>'btn-group')));	
	}
	else
			$buttons .= addButton(array('label'=>t("Create a storage node"),'class'=>'btn btn-success', 'href'=>'tahoe-lafs/createNode'));
		
		
	$page .= $buttons;	
	return(array('type' => 'render','page' => $page));
}

function install(){
	global $RESOURCES_PATH;
	global $TAHOE_VARS;

	$page = "";
	$buttons = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Installation"),4);

	if ( isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= txt(t("Installation process result:"));
 		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS is already installed")."</div>\n";
		$page .= txt(t("Tahoe-LAFS installation information:"));
		$page .= ptxt(packageInstallationInfo("tahoe-lafs"));
 		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 		
 		$page .= $buttons;	
		return(array('type' => 'render','page' => $page));
	}
		
 	$pkgInstall = ptxt(installPackage("tahoe-lafs"));
	
	if ( isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= txt(t("Installation process result:"));		
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS has been successfully installed")."</div>\n";
		$page .= txt(t("Installation process details:"));
		$page .= $pkgInstall;
			
		$postInstall = array();
		$postInstallAll = "";
			
		$page .= txt(t("Post-installation process details:"));
		foreach (execute_program( 'addgroup --system tahoe' )['output'] as $key => $value) { $postInstall[] = $value; }
		foreach (execute_program( 'adduser --system --ingroup tahoe --home /var/lib/tahoe-lafs --shell /sbin/nologin tahoe' )['output'] as $key => $value) { $postInstall[] = $value; }
		foreach (execute_program( 'chown -vR tahoe:tahoe /var/lib/tahoe-lafs' )['output'] as $key => $value) { $postInstall[] = $value;}
		$postInstall[] = execute_program( 'cp -fv '.$RESOURCES_PATH.'/tahoe-lafs.init.d /etc/init.d/tahoe-lafs' )['output'][0];
		$postInstall[] = execute_program( 'cp -fv '.$RESOURCES_PATH.'/tahoe-lafs.etc.default /etc/default/tahoe-lafs' )['output'][0];
		foreach (execute_program( 'chmod -v +x /etc/init.d/tahoe-lafs' )['output'] as $key => $value) { $postInstall[] = $value; }
		$postInstall[] = execute_program( 'update-rc.d tahoe-lafs defaults' )['output'][0];

		foreach ($postInstall as $k => $v) { $postInstallAll .= $v.'<br/>'; }
		$page .= ptxt($postInstallAll);
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		
		$page .= $buttons;	
		return(array('type' => 'render','page' => $page));
		}
	
	$page .= txt(t("Installation process result:"));
	$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS installation failed")."</div>\n";
	$page .= txt(t("Installation process details:"));
	$page .= $pkgInstall;
	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'install'));
	$buttons .= addButton(array('label'=>t("Retry installation"),'class'=>'btn btn-warning', 'href'=>'install'));
	
	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function uninstall(){
	global $TAHOE_VARS;

	$page = "";
	$buttons = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Uninstallation"),4);

	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= txt(t("Uninstallation process result:"));
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			
 			$page .= $buttons;	
			return(array('type' => 'render','page' => $page));
	}
 	
 	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') || tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node')) {
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));		
		if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer') ){
		$page .= txt(t("Uninstallation process result:"));	
		$page .= "<div class='alert alert-warning text-center'>".t("A Tahoe-LAFS introducer is currently configured.") .' '. t("Stop it and remove it before uninstalling Tahoe-LAFS.")."</div>\n";
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS introducer"),'class'=>'btn btn-primary', 'href'=>'introducer'));
		}
		if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node') ){
		$page .= "<div class='alert alert-warning text-center'>".t("A Tahoe-LAFS node is currently configured.") .' '. t("Stop it and remove it before uninstalling Tahoe-LAFS.")."</div>\n";
		$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS node"),'class'=>'btn btn-primary', 'href'=>'node'));
		}
		
		
		
		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}
	
	$pkgUninstall = ptxt(uninstallPackage("tahoe-lafs"));
	
	if ( isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
		$page .= txt(t("Unistallation process result:"));
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS uninstallation failed")."</div>\n";
		$page .= txt(t("Uninstallation process result:"));
		$page .= $pkgUninstall;
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'install'));
		$buttons .= addButton(array('label'=>t("Retry uninstallation"),'class'=>'btn btn-warning', 'href'=>'uninstall'));
		
		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}
	
	$page .= txt(t("Unistallation process result:"));
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
	$postUninstall[] = execute_program_shell( 'rm -rvf /var/lib/tahoe-lafs' )['output'] ;

	foreach ($postUninstall as $v) { $postUninstallAll .= $v; }
	$page .= ptxt($postUninstallAll);
	
	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
	
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

	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'../tahoe-lafs'));
 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
	
 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is currently not created")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Click on the button to set up an introducer on this machine.") .' '. t("After that, storage nodes will be able to join your introducer to deploy the storage grid."));
		$buttons .= addButton(array('label'=>t("Create an introducer"),'class'=>'btn btn-success', 'href'=>'createIntroducer'));		
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		
		$page .= $buttons; 			
 		return(array('type' => 'render','page' => $page));
	}

	$page .= txt(t("Tahoe-LAFS introducer status:"));
	$page .= ajaxStr('introducerStt',t("Checking Tahoe-LAFS introducer status..."));
	$page .= "\n<script>\n";
	$page .= "loadIntroducerStatus = function() { $('#introducerStt').load('".$staticFile."/tahoe-lafs/introducerStatusPage') ; } ;";
	$page .= "setInterval( loadIntroducerStatus ,5000) ; loadIntroducerStatus();\n";
	$page .= "</script>\n";
	$buttons .= ajaxStr('introducerSttB',t("Checking Tahoe-LAFS introducer status..."));
	$buttons .= "\n<script>\n";
	$buttons .= "loadIntroducerStatusB = function() { $('#introducerSttB').load('".$staticFile."/tahoe-lafs/introducerStatusButtons') ; } ;";
	$buttons .= "setInterval( loadIntroducerStatusB ,5000) ; loadIntroducerStatusB();\n";
	$buttons .= "</script>\n";
	$page .= txt(t("Grid name:"));
	$page .= ptxt(file_get_contents($TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer/grid.name'));
	$page .= txt(t("Introducer FURL:"));	
	$page .= ptxt(execute_program("sed 's/,127\.0\.0\.1:.*\//\//' /var/lib/tahoe-lafs/introducer/introducer.furl | sed 's/,192\.168\..*\..*:.*\//\//' ")['output'][0]);
	$page .= txt(t("Introducer web page:"));		
	$webPage = 'http://'.substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':')+1). file_get_contents('/var/lib/tahoe-lafs/introducer/web.port'); ;
	$page .= ptxt('<a href="'.$webPage.'" >'.$webPage.'</a>');
	
	
	
	$page .= $buttons;	
	
 	return(array('type' => 'render','page' => $page));
 	
}
function introducerStatusPage(){
	global $TAHOE_VARS;
	
	$r = _introducerStatus($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']);
	return (array('type'=>'ajax', 'page' => $r['page']));
}

function introducerStatusButtons(){
	global $TAHOE_VARS;
	
	$r = _introducerStatus($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']);
	return (array('type'=>'ajax', 'page' => $r['buttons']));
}


function _introducerStatus($homedir,$pidfile) {
	global $TAHOE_VARS;

	$page = '';
	$buttons = '';

	if ( introducerStarted($homedir, $pidfile) ) {
		$page = "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer is running")."</div>\n";
		
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Stop Tahoe-LAFS introducer"),'class'=>'btn btn-danger', 'href'=>'stopIntroducer'));
	} 	
	else {
		$page = "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer is stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS introducer"),'class'=>'btn btn-success', 'href'=>'startIntroducer'));
		$buttons .= addButton(array('label'=>t("Delete Tahoe-LAFS introducer"),'class'=>'btn btn-danger', 'href'=>'deleteIntroducer'));
	}
	
	return(array('page'=>$page,'buttons'=>$buttons));
}

function node(){
	global $TAHOE_VARS;
	
	$page = "";
	$buttons = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Node"),4);

	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'../tahoe-lafs'));
 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
	
 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node')) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS node is currently not created")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Click on the button to set up a storage node on this machine and join a storage grid."));
		$buttons .= addButton(array('label'=>t("Create a node"),'class'=>'btn btn-success', 'href'=>'createNode'));		
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		
		$page .= $buttons; 			
 		return(array('type' => 'render','page' => $page));
	}
			
	if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE'])) {
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node is running")."</div>\n";

		$buttons .= addButton(array('label'=>t("Stop Tahoe-LAFS node"),'class'=>'btn btn-danger', 'href'=>'stopNode'));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
				
		$page .= $buttons; 			
 		return(array('type' => 'render','page' => $page));
	} 	
	
	else {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node is stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS node"),'class'=>'btn btn-success', 'href'=>'startNode'));
		$buttons .= addButton(array('label'=>t("Delete Tahoe-LAFS node"),'class'=>'btn btn-danger', 'href'=>'deleteNode'));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		
		$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
}

function createIntroducer(){
	global $TAHOE_VARS;
	
	$page = "";
	$buttons = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Introducer creation"),4);

	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'install'));
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
	
	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network.") .' '. t("Use this page to set up an introducer on this machine and start a storage grid.") .' '. t("After that, storage nodes will be able to join your introducer to deploy the storage grid."));
		$page .= createForm(array('class'=>'form-horizontal'));
		$page .= addInput('GRID_NAME',t("Storage grid name"),'Example-Grid-'.mt_rand(10,99),'','',t("A short name to identify the storage grid."));		
		$page .= addInput('INTRODUCER_NAME',t('Introducer name'),'MyIntroducer','','',t("A short nickname to identify the introducer in the storage grid."));
		$page .= addInput('INTRODUCER_WEBPORT',t('Web port'),8228,array('type'=>'number','min'=>'1024','max'=>'65535'),'',t("The port where the introducer's web management interface will run on."));		
		$page .= addInput('INTRODUCER_DIR',t('Folder'),$TAHOE_VARS['DAEMON_HOMEDIR'].'/introducer','','readonly',t("The instroducer will be installed in this folder."));
		$page .= addInput('INTRODUCER_PUBLIC',t('Public'), true, array('type'=>'checkbox'),'checked',t("Announce the introducer service through Avahi and allow storage nodes to join the grid."),'no');
 		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 		$buttons .= addSubmit(array('label'=>t('Create introducer'),'class'=>'btn btn-success'));
		
		$page .= $buttons; 			
 		return(array('type' => 'render','page' => $page));
	}
			
	if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE'])) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is already created")."</div>\n";
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer is running")."</div>\n";
		$buttons .= addButton(array('label'=>t("Stop Tahoe-LAFS introducer"),'class'=>'btn btn-danger', 'href'=>'stopIntroducer'));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		$page .= ptxt(execute_program("sed 's/,127\.0\.0\.1:.*\//\//' /var/lib/tahoe-lafs/introducer/introducer.furl")['output'][0]);
		
		$page .= $buttons; 			
 		return(array('type' => 'render','page' => $page));
	} 	
	
	else {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer is already created")."</div>\n";		
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer is stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS introducer"),'class'=>'btn btn-success', 'href'=>'startIntroducer'));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
				
		$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
}

function createIntroducer_post(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	$buttons = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Introducer creation"),4);

	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'../tahoe-lafs'));

 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
 	
 	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS is already created")."</div>\n";
			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
			$buttons .= addButton(array('label'=>t("Manage Tahoe-LAFS introducer"),'class'=>'btn btn-primary', 'href'=>'introducer'));
 			 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
 	}

	if (empty($_POST)) {
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer creation failed."). ' '. t("Incorrect request parameters received.")."</div>\n";
			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
						 			
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
		$page .= "<div class='alert alert-error text-center'>".t("Invalid introducer web port number name").': ' . htmlspecialchars(substr($_POST['INTRODUCER_WEBPORT'],0,10)) . "</div>\n";
	}
	if(!$validPost) {
			$page .= "<div class='alert alert-warning text-center'>".t("A maximum of 80 alphanumeric characters, dashes and underscores are allowed in the names.")."</div>\n";	
			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
			$buttons .= addButton(array('label'=>t("Back to introducer creation"),'class'=>'btn btn-warning', 'href'=>'createIntroducer'));
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
	
	$postCreate = array();
	foreach (execute_program( '/usr/bin/tahoe create-introducer /var/lib/tahoe-lafs/introducer')['output'] as $k => $v) { $postCreate[] = $v; }
	execute_program_shell( 'sed -i "s/^nickname.*$/nickname = '.$_POST['INTRODUCER_NAME'].'/" /var/lib/tahoe-lafs/introducer/tahoe.cfg' );
	execute_program_shell( 'echo '.$_POST['INTRODUCER_WEBPORT'].' >> /var/lib/tahoe-lafs/introducer/web.port' );
	if ($_POST['INTRODUCER_PUBLIC']){
		execute_program_shell( 'touch /var/lib/tahoe-lafs/introducer/introducer.public' );
		execute_program_shell( 'sed -i "s/^web\.port.*$/web\.port = tcp:'.intval($_POST['INTRODUCER_WEBPORT']).':interface=0.0.0.0/" /var/lib/tahoe-lafs/introducer/tahoe.cfg' );	
	}
	else
		execute_program_shell( 'sed -i "s/^web\.port.*$/web\.port = tcp:'.intval($_POST['INTRODUCER_WEBPORT']).':interface=127.0.0.1/" /var/lib/tahoe-lafs/introducer/tahoe.cfg' );
	execute_program_shell( 'echo '.$_POST['GRID_NAME'].' >> /var/lib/tahoe-lafs/introducer/grid.name' );	
	
	
	
	if( execute_shell( "grep -q '^AUTOSTART' /etc/default/tahoe-lafs" )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/^AUTOSTART=\"[^\"]*/& introducer /" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
	}
	else
		execute_program_shell( 'echo "AUTOSTART=introducer" >> /etc/default/tahoe-lafs' );			
	
			
	foreach (execute_program( 'chown -vR tahoe:tahoe /var/lib/tahoe-lafs/introducer' )['output'] as $key => $value) { $postCreate[] = $value; }
	$postCreateAll = "";
		foreach ($postCreate as $k => $v) { $postCreateAll .= $v.'<br/>'; }
		
	$page .= txt(t("Introducer creation process result:"));	
	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer'))
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer successfully created")."</div>\n";
	else
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer creation failed")."</div>\n";
	
	$page .= ptxt($postCreateAll);

	$postStart = array();
	foreach (execute_program( '/etc/init.d/tahoe-lafs start introducer')['output'] as $k => $v) { $postStart[] = $v; }
	//This pause is needed in order to let the server start before showing the success/error text
	sleep(2);
	foreach (execute_program( 'ps aux | grep tahoe | grep introducer | grep -v grep')['output'] as $k => $v) { $postStart[] = $v; }	
	
	$postStartAll = "";
		foreach ($postStart as $k => $v) { $postStartAll .= $v.'<br/>'; }

		
	$page .= txt(t("Starting Tahoe-LAFS introducer:"));
	if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer successfully started")."</div>\n";
	else {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer start failed")."</div>\n";
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS introducer"),'class'=>'btn btn-success', 'href'=>'startIntroducer'));
		}
	$page .= ptxt($postStartAll);
	 
	$page .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 	return(array('type' => 'render','page' => $page));
}

function createNode(){
	global $TAHOE_VARS;
	
	$page = "";
	$buttons = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Node creation"),4);

	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'install'));
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
	
	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node')) {
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>storage nodes</strong> distributed by the network. Use this page to set up a storage node and join a storage grid."));
		$page .= createForm(array('class'=>'form-horizontal'));
		$page .= addInput('NODE_NICKNAME',t('Nickname'),'MyGrid-MyStorageNode','',t("A short nickname to identify the storage node in the grid."));
		$page .= addInput('NODE_INTRODUCER_FURL',t('Introducer FURL'),'pb://abcdefghijklmnopqrstuvwxyz1234567890@example.com:12345/introducer','',t("The introducer's FURL of the storage grid you want to join."));
		$page .= addInput('NODE_DIR',t('Folder'),$TAHOE_VARS['DAEMON_HOMEDIR'].'/node','','readonly',t("The installation path for the node."));
		//$page .= addInput('NODE_PUBLIC',t('Public'),true,'',t("Check this box to make this introducer public and announce to the network via Avahi."));
		$buttons .= addSubmit(array('label'=>t('Create node'),'class'=>'btn btn-success'));
		
		$page .= $buttons; 			
 		return(array('type' => 'render','page' => $page));
	}
			
	if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE'])) {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS node is already created")."</div>\n";
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node is running")."</div>\n";
		$buttons .= addButton(array('label'=>t("Stop Tahoe-LAFS introducer"),'class'=>'btn btn-danger', 'href'=>'stopNode'));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
		//$page .= ptxt(execute_program("sed 's/,127\.0\.0\.1:.*\//\//' /var/lib/tahoe-lafs/introducer/introducer.furl")['output'][0]);
		
		$page .= $buttons; 			
 		return(array('type' => 'render','page' => $page));
	} 	
	
	else {
		$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS node is already created")."</div>\n";		
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node is stopped")."</div>\n";
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS node"),'class'=>'btn btn-success', 'href'=>'startNode'));
		$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
				
		$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
}

function createNode_post(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Node creation"),4);

	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'../tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
 	
 	if ( tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node')) {
			$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node is already not created")."</div>\n";
			$buttons .= addButton(array('label'=>t("Manage node"),'class'=>'btn btn-primary', 'href'=>'node')); 			
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
 	}
	
	$postCreate = array();
	foreach (execute_program( '/usr/bin/tahoe create-node /var/lib/tahoe-lafs/node')['output'] as $k => $v) { $postCreate[] = $v; }
	execute_program_shell( 'sed -i "s/^nickname.*$/nickname = '.$_POST['NODE_NICKNAME'].'/" /var/lib/tahoe-lafs/node/tahoe.cfg' );
	execute_program_shell( 'sed -i "s%^introducer\.furl.*$%introducer\.furl = '.$_POST['NODE_INTRODUCER_FURL'].'%" /var/lib/tahoe-lafs/node/tahoe.cfg' );
	                                		

	if( execute_shell( "grep -q '^AUTOSTART' /etc/default/tahoe-lafs" )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/^AUTOSTART=\"[^\"]*/& node /" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
	}
	else
		execute_program_shell( 'echo "AUTOSTART=node" >> /etc/default/tahoe-lafs' );			
	
			
	foreach (execute_program( 'chown -vR tahoe:tahoe /var/lib/tahoe-lafs/node' )['output'] as $key => $value) { $postCreate[] = $value; }
	$postCreateAll = "";
		foreach ($postCreate as $k => $v) { $postCreateAll .= $v.'<br/>'; }
		
	$page .= txt(t("Node creation process result:"));	
	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node'))
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

		
	$page .= txt(t("Starting Tahoe-LAFS node:"));
	if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) )
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node successfully started")."</div>\n";
	else {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node start failed")."</div>\n";
		$buttons .= addButton(array('label'=>t("Start Tahoe-LAFS node"),'class'=>'btn btn-success', 'href'=>'startNode'));
		}
	$page .= ptxt($postStartAll);
	 
	$page .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 	return(array('type' => 'render','page' => $page));
}


function deleteIntroducer(){
	global $TAHOE_VARS;
	global $staticFile;

	$page = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Introducer deletion"),4);


	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'../tahoe-lafs'));
 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
 	
 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer')) {
 		setFlash(t("Tahoe-LAFS introducer is not created"));
		return(array('type'=>'redirect','url'=> $staticFile.'/tahoe-lafs'));
 	}
	
	if ( introducerStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) ) {
 		setFlash(t("Tahoe-LAFS introducer is not stopped"));
		return(array('type'=>'redirect','url'=> $staticFile.'/tahoe-lafs'));
 	}
 	
	$postDelete = array();
	$postDeleteAll = "";
	foreach (execute_program( 'rm -vrf /var/lib/tahoe-lafs/introducer/*')['output'] as $k => $v) { $postDelete[] = $v; }
		
	if( execute_shell( "grep -q '^AUTOSTART' /etc/default/tahoe-lafs" )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/introducer/ /" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
	}			
				
	foreach ($postDelete as $k => $v) { $postDeleteAll .= $v.'<br/>'; }
	rmdir($TAHOE_VARS['DAEMON_HOMEDIR']."/introducer");
			
	$page .= txt(t("Introducer deletion process result:"));	
	
	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'introducer'))
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS introducer deletion failed")."</div>\n";
	else
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer successfully deleted")."</div>\n";
	$page .= ptxt($postDeleteAll);

 	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));

	$page .= $buttons; 			 			
	return(array('type' => 'render','page' => $page));
}

function deleteNode(){
	global $TAHOE_VARS;
	global $staticFile;
	
	$page = "";
	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("Node deletion"),4);


	if ( ! isPackageInstall($TAHOE_VARS['PACKAGE_NAME']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS is currently uninstalled")."</div>\n";
 			$buttons .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'../tahoe-lafs'));
 			$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));
 			
			$page .= $buttons; 			
 			return(array('type' => 'render','page' => $page));
	}
 	
 	if ( ! tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node')) {
 		setFlash(t("Tahoe-LAFS node is not created"));
		return(array('type'=>'redirect','url'=> $staticFile.'/tahoe-lafs'));
 	}
	
	if ( nodeStarted($TAHOE_VARS['DAEMON_HOMEDIR'],$TAHOE_VARS['TAHOE_PID_FILE']) ) {
 		setFlash(t("Tahoe-LAFS node is not stopped"));
		return(array('type'=>'redirect','url'=> $staticFile.'/tahoe-lafs'));
 	}
 	
	$postDelete = array();
	$postDeleteAll = "";
	foreach (execute_program( 'rm -vrf /var/lib/tahoe-lafs/node/*')['output'] as $k => $v) { $postDelete[] = $v; }
		
	if( execute_shell( "grep -q '^AUTOSTART' /etc/default/tahoe-lafs" )['return'] == 0 ) {
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/node/ /" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/\" /\"/" /etc/default/tahoe-lafs' );
		execute_program_shell( 'sed -i "s/ \"/\"/" /etc/default/tahoe-lafs' );
	}			
				
	foreach ($postDelete as $k => $v) { $postDeleteAll .= $v.'<br/>'; }
	rmdir($TAHOE_VARS['DAEMON_HOMEDIR']."/node");
			
	$page .= txt(t("Node deletion process result:"));	
	
	if (tahoeCreated($TAHOE_VARS['DAEMON_HOMEDIR'],'node'))
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS node deletion failed")."</div>\n";
	else
		$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node successfully deleted")."</div>\n";
	$page .= ptxt($postDeleteAll);

 	$buttons .= addButton(array('label'=>t("Back to Tahoe-LAFS"),'class'=>'btn btn-default', 'href'=>'../tahoe-lafs'));

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

   $pid = detached_exec('/etc/init.d/tahoe-lafs start introducer >/dev/null 2>&1');

	setFlash(t('Starting Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/introducer'));
}

function stopIntroducer(){
	global $staticFile;

   $pid = detached_exec('/etc/init.d/tahoe-lafs stop introducer >/dev/null 2>&1');

	setFlash(t('Stopping Tahoe-LAFS introducer...'));
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/introducer'));	
}

function stopNode(){
	global $staticFile; 

	$r = execute_program('/etc/init.d/tahoe-lafs stop node');
	if ($r['return'] == 0) {
		setFlash(t('Tahoe-LAFS node stopped').'!');
	}

	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'tahoe-lafs/node'));	
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

?>