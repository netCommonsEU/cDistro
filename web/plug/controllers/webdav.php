<?php
// webdav

$webdav_pkg = "libapache2-mod-encoding";
$webdav_desc = t("Install WEBDAV Server");
$folders = "/etc/apache2/conf.d/";
$file_module = '../mods-enable/dav_fs.load';
$module = 'dav_fs';
$htpasswd = $folders.".htpasswd";
$extension = ".webdav.conf";
$title = t("WEBDAV Server (Apache2)");
$webuser = "www-data";
$webgroup = "www-data";
$chroot = "/";
$mpmod = 0770;
$min_alias_len = 4;
$max_alias_len = 10;
$min_password = 6;
$max_password = 10;

function index_get()
{

	global $staticFile,$staticPath,$title;
	global $webdav_pkg,$webdav_desc;


	if (!isPackageInstall($webdav_pkg)){
		$page = package_not_install($webdav_pkg,$webdav_desc);
	} else {
		$page = hlc($title);
		$page .= "<div id='pathmount'>";
		$page .= hlc(t("Mount"),2);
		$page .= ajaxStr('tMount',t("Loading Paths.") );
		$page .= "<script>\n";
		$page .= "$('#tMount').load('".$staticFile."/webdav/listMounts');\n";
		$page .= "</script>\n";
		$page .= "</div>";

		$page .= "<div id='userlist'>";
		$page .= hlc(t("Users"),2);
		$page .= ajaxStr('tUsers',t("Loading Users.") );
		$page .= "<script>\n";
		$page .= "$('#tUsers').load('".$staticFile."/webdav/listUsers');\n";
		$page .= "</script>\n";
		$page .= "</div>";

		if (isPackageInstall($webdav_pkg)){
			$page .= addButton(array('label'=>t('Uninstall package'),'class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$webdav_pkg));
		}
	}

	return(array('type'=>'render','page'=>$page));
}

/* Users functions */

function addUser_form($values=null){
	global $staticFile,$staticPath,$title;

	$datos = array(
				array('name'=>'user','type'=>'text','desc'=>t("Username")),
				array('name'=>'password','type'=>'password','desc'=>t("Password")),
				array('name'=>'confirm_password','type'=>'password','desc'=>t("Confirm Password")),
			);

	$page = hlc($title);
	$page .= hl(t("Add User"),2);
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($datos as $dato){
		$options = array('type'=>$dato['type']);
		$n = $dato['name'];
		if (isset($values[$n])) { $options['value'] = $values[$n];}
		$page .= addInput($n,$dato['desc'],null,$options);
	}

	$page .= addSubmit(array('label'=>t('Executar')));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$staticFile.'/webdav'));

	return($page);

}
function addUser_get(){

	$page = addUser_form();
	return(array('type'=>'render','page'=>$page));

}

function addUser_post(){
	global $staticFile,$staticPath,$htpasswd, $min_password, $max_password;

	// Comporvar dades

	$user = $_POST['user'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	if ((strlen($password) <  $min_password) || (strlen($password) > $max_password)){
		setFlash(t("Password must be between $min_password and $max_password characters."),"error");
		return(array('type'=>'render','page'=>addUser_form(array('user'=>$user))));
	}

	if ($password != $confirm_password){
		setFlash(t("Sorry, passwords do not match."),"error");
		return(array('type'=>'render','page'=>addUser_form(array('user'=>$user))));
	}

	$options = "";
	if (!file_exists($htpasswd)) {
		$options = "c";
	}

	if ( execute_shell('/usr/bin/htpasswd -b'.$options.' '.$htpasswd.' '.$user.' '.$password)['return'] == 0 ){
		setFlash(t("Save user."),"success");
	} else {
		setFlash('/usr/bin/htpasswd -b '.$htpasswd.' '.$user.' '.$password,"error");
	}
	return(array('type'=>'redirect','url'=>$staticFile.'/webdav'));

}

function changeUserPassword_form($values=null){
	global $staticFile,$staticPath,$title,$Parameters;

	$datos = array(
				array('name'=>'password','type'=>'password','desc'=>t("Password")),
				array('name'=>'confirm_password','type'=>'password','desc'=>t("Confirm Password")),
			);

	$page = hlc($title);
	$page .= hl(t("Change password"),2);
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($datos as $dato){
		$options = array('type'=>$dato['type']);
		$n = $dato['name'];
		if (isset($values[$n])) { $options['value'] = $values[$n];}
		$page .= addInput($n,$dato['desc'],null,$options);
	}

	$page .= addSubmit(array('label'=>t('Executar')));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$staticFile.'/webdav'));

	return($page);

}
function changeUserPassword_get(){

	$page = changeUserPassword_form();
	return(array('type'=>'render','page'=>$page));

}

function changeUserPassword_post(){
	global $staticFile,$staticPath,$htpasswd,$Parameters;

	// Comporvar dades

	if (!isset($Parameters[0]) ) {
		setFlash(t("User not defined."),"error");
		return(array('type'=>'redirect','url'=>'/webdav'));
	}
	$user = $Parameters[0];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	if ($password != $confirm_password){
		setFlash(t("Sorry, passwords do not match."),"error");
		return(array('type'=>'render','page'=>changeUserPassword_from()));
	}

	if ( execute_shell('/usr/bin/htpasswd -D '.$htpasswd.' '.$user)['return'] == 0 ){
		if ( execute_shell('/usr/bin/htpasswd -b '.$htpasswd.' '.$user.' '.$password)['return'] == 0 ){
			setFlash(t("<i>$user</i>'s password has been Changed."),"success");
		}
	} else {
			setFlash(t("Can not change password from user ('".$user."')"),"error");
	}
	return(array('type'=>'redirect','url'=>$staticFile.'/webdav'));

}

function removeUser(){
	global $staticFile,$staticPath,$htpasswd,$Parameters,$title;

	if (!isset($Parameters[0]) && $Parameters[0] != ""){
		return(array('type'=>'redirect','url'=>$staticFile.'/webdav'));
	}

	$name = $Parameters[0];


	$page = hlc($title);
	$page .= hl(t("Remove User"),2);
	$page .= par(t("Are you sure to delete <i>$name</i> user ?"));



	$page .= addButton(array('label'=>t("Yes, I'm sure"),'href'=>$staticFile.'/webdav/removeUserConfirmed/'.$name));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$staticFile.'/webdav'));
	return(array('type'=>'render','page'=>$page));

}

function removeUserConfirmed(){
	global $staticFile,$staticPath,$htpasswd,$Parameters;


	if (isset($Parameters[0]) && $Parameters[0] != ""){
		if ( execute_shell('/usr/bin/htpasswd -D '.$htpasswd.' '.$Parameters[0])['return'] == 0 ){
			setFlash(t("Remove user."),"success");
		} else {
			setFlash(t("Can not remove user ('".$Parameters[0]."')"),"error");
		}
	}

	return(array('type'=>'redirect','url'=>$staticFile.'/webdav'));
}


function listUsers(){
	global $htpasswd;
	global $staticFile;

	$page = "";


	if (file_exists($htpasswd) && filesize($htpasswd) != 0) {

		$users = array();
		foreach (execute_program( 'cat '.$htpasswd.' | cut -d ":" -f 1' )['output'] as $v) { $users[] = $v; }

		$page .= addTableHeader(array(t("User"),t("Action")));
		foreach($users as $user){
			if ($user != "") {
				$action = "<a class='btn' href='".$staticFile."/webdav/changeUserPassword/".$user."'>".t("Change Password")."</a>";
				$action .= "<a class='btn' href='".$staticFile."/webdav/removeUser/".$user."'>".t("Remove")."</a>";
				$page .= addTableRow(array($user,$action));
			}
		}
		$page .= addTableFooter();

	}

	$page .= "<div><a class='btn' href='".$staticFile."/webdav/addUser'>".t("Add User")."</a></div>";

	return(array('type'=>'ajax','page'=>$page));
}

/* Mount functions */

function listMounts(){
	global $htpasswd;
	global $staticFile;
	global $folders;
	global $extension;

	$page = "";


	if (!is_dir($folders)) {
		// Create directori...
		$page = "Directory doesn't exist.";
		return(array('type'=>'ajax','page'=>$page));
	}

	$mounts = array();

	foreach (glob($folders."*".$extension) as $filename) {
    	$mounts[] = basename($filename);
	}

	$page .= addTableHeader(array(t("Mount"),t("Action")));
	foreach($mounts as $mount){
		if ($mount != "") {
			$action = "<a class='btn' href='".$staticFile."/webdav/viewMount/".$mount."'>".t("View")."</a>";
			$action .= "<a class='btn' href='".$staticFile."/webdav/removeMount/".$mount."'>".t("Remove")."</a>";
			$page .= addTableRow(array($mount,$action));
		}
	}
	$page .= addTableFooter();

	$page .= "<div><a class='btn' href='".$staticFile."/webdav/addMount'>".t("Add Mountpoint")."</a></div>";

	return(array('type'=>'ajax','page'=>$page));
}

function viewMount(){
	global $Parameters,$staticFile,$staticPath,$title,$htpasswd, $folders, $extension;

	if (!isset($Parameters[0])){
		return(array('type'=>'redirect','url'=>$staticFile.'/webdav'));
	}
	$name = $Parameters[0];
	$page = hlc($title);
	$page .= hlc($name,2);
	$page .= "<pre>";
	$page .= htmlspecialchars(execute_program_shell( 'cat '.$folders.$name )['output']);
	$page .= "</pre>";

	$page .= addButton(array('label'=>t('Back'),'href'=>$staticFile.'/webdav'));
	return(array('type'=>'render','page'=>$page));

}

function removeMount(){
	global $staticFile,$staticPath,$htpasswd,$Parameters,$title;

	if (!isset($Parameters[0]) && $Parameters[0] != ""){
		return(array('type'=>'redirect','url'=>$staticFile.'/webdav'));
	}

	$name = $Parameters[0];


	$page = hlc($title);
	$page .= hl(t("Remove Mount"),2);
	$page .= par(t("Are you sure to delete <i>$name</i> mountpoint ?"));



	$page .= addButton(array('label'=>t("Yes, I'm sure"),'href'=>$staticFile.'/webdav/removeMountConfirmed/'.$name));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$staticFile.'/webdav'));
	return(array('type'=>'render','page'=>$page));

}

function removeMountConfirmed(){
	global $staticFile,$staticPath,$Parameters, $folders, $extension;


	if (isset($Parameters[0]) && $Parameters[0] != ""){
		$name = $Parameters[0];
		if ( execute_shell("rm -f ".$folders.$name)['return'] == 0 ){
			setFlash(t("Removed mountpoint file."),"success");
		} else {
			setFlash(t("Can not remove file ('".$name."')"),"error");
		}
		_restartApache();
	}

	return(array('type'=>'redirect','url'=>$staticFile.'/webdav'));
}

function addMount_form($values=null){
	global $staticFile,$staticPath,$title;

	$datos = array(
				array('name'=>'alias','type'=>'text','desc'=>t("Alias Mount")),
				array('name'=>'mountpoint','type'=>'text','desc'=>t("Mount Point")),
				//array('name'=>'uri','type'=>'text','desc'=>t("URI")),
				//array('name'=>'type','type'=>'text','desc'=>t("Type")),
			);

	$page = hlc($title);
	$page .= hl(t("Add Mount"),2);
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($datos as $dato){
		$options = array('type'=>$dato['type']);
		$n = $dato['name'];
		if (isset($values[$n])) { $options['value'] = $values[$n];}
		$page .= addInput($n,$dato['desc'],null,$options);
	}

	$page .= addSubmit(array('label'=>t('Executar')));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$staticFile.'/webdav'));

	return ($page);
}
function addMount_get(){

	$page = addMount_form();
	return(array('type'=>'render','page'=>$page));

}
function addMount_post(){
	global $staticFile,$staticPath,$extension,$folders,$min_alias_len,$max_alias_len,$webuser,$webgroup,$mpmod,$chroot;

	// Comporvar dades

	$name = strip_tags($_POST['alias']);
	$mountpoint = $chroot.strip_tags($_POST['mountpoint']);
	$uri = strip_tags($_POST['mountpoint']);

	if ((strlen($name) < $min_alias_len ) || (strlen($name) > $max_alias_len )){
		setFlash(t("alias field must be $min_alias_len - $max_alias_len characters."),"error");
		return(array('type'=>'render','page'=>addMount_form(array('alias'=>$name,'mountpoint'=>$mountpoint,'uri'=>$uri))));
	}
	$flash = "";
	$flash_type = "success";

	if (_makeWebdabFile($name,$mountpoint)){
		$flash .= par(t("Created configfile."));
	} else {
		$flash .= par(t("Error when create configfile."));
		$flash_type = "error";
	}

	if (_checkDAVApacheModule()) {
		$flash .= par(t("DAV Module is active."));
	} else {
		$flash .= par(t("Error when active DAV Module."));
		$flash_type = "error";
	}

	if (_restartApache()) {
		$flash .= par(t("Reloaded apache2."));
	} else {
		$flash .= par(t("Error when reload apache2."));
		$flash_type = "error";
	}

	if (!is_dir($mountpoint)){
		if(mkdir($mountpoint)) {
			$flash .= par(t("Created Mountpoint."));
		} else {
			$flash .= par(t("Error when create mountpoint."));
			$flash_type = "error";
		}
	}

	if (chown($mountpoint,$webuser)) {
		$flash .= par(t("Changed user."));
	} else {
		$flash .= par(t("Error when change user."));
		$flash_type = "error";
	}

	if (chgrp($mountpoint,$webgroup)) {
		$flash .= par(t("Changed group."));
	} else {
		$flash .= par(t("Error when change group."));
		$flash_type = "error";
	}

	if (chmod($mountpoint,$mpmod)) {
		$flash .= par(t("Changed file mode."));
	} else {
		$flash .= par(t("Error when change file mode."));
		$flash_type = "error";
	}

	setFlash($flash,$flash_type);
	return(array('type'=>'redirect','url'=>'/webdav'));

}

function _makeWebdabFile($name,$mp){
	global $htpasswd, $folders, $extension, $chroot;

	$file = "Alias /$name $mp\n";
	$file .= "<Location /$name>\n";
	$file .= "    DAV On\n";
	$file .= "    AuthType Basic\n";
	$file .= "    AuthName WebDAV\n";
	$file .= "    AuthUserFile $htpasswd\n";
	$file .= "    Require valid-user\n";
	$file .= "    Options Indexes MultiViews\n";
	$file .= "</Location>\n";


	return(file_put_contents($folders.$name.$extension, $file));

}

function _checkDAVApacheModule(){
	global $folders, $file_module, $module;

	if (!file_exists($folders.$file_module)){
		return( execute_shell("/usr/sbin/a2enmod ".$module)['return'] == 0);
	}

	return(true);
}
function _restartApache(){

	return ( execute_shell("/usr/sbin/service apache2 reload")['return'] == 0 );

}
