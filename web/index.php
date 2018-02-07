<?php

// Config files
require "config/global.php";
require "core.php";
require "lib/apt.php";
require "lib/docker.php";
require "lib/session.php";
require "lib/lang.php";
require "lib/form.php";
require "lib/view.php";
require "lib/errors.php";
require "lib/utilio.php";
require "lib/auth.php";
require "lib/guifi_api.php";
require "lib/package.php";
//require "lib/docker-compose.php";

if (isset($user)) {
    require "lib/menus.php";
    require "lib/avahi.php";
}

$css = array('bootstrap.min','bootstrap-responsive.min', 'jquery.dataTables','main');
$js = array('jquery-1.11.0.min','jquery.dataTables.min','bootstrap.min');
$js_end = array('main');
$dir_configs="/etc/cloudy";

if (isset($user) && !$post_login) {
    // Default
    $controller = "default";
    $action="index";
    $method=strtolower($_SERVER['REQUEST_METHOD']);

    if (isset($Parameters) && is_array($Parameters) && isset($Parameters[0]) && file_exists($documentPath.$plugs_controllers.$Parameters[0].".php")) {
        $controller = $Parameters[0];
        array_shift($Parameters);
    }
    // Load Controller

    if (isset($Parameters) && isset($Parameters[0])) {
        $action=$Parameters[0];
        array_shift($Parameters);
    }


    require $documentPath.$plugs_controllers.$controller.".php";

    if (!is_array($Parameters)) {
        $Parameters=array();
    }

    // Add method type to action
    if (function_exists($action."_".$method)) {
        $action=$action."_".$method;
    }

    if (!function_exists($action)) {
        array_unshift($Parameters, $action);
        array_unshift($Parameters, $controller);
        $controller = "default";
        $action="notFunctionExist";
    }
    $cb = call_user_func_array($action, $Parameters);

    list($wi_ip, $wi_port) = explode(":", $_SERVER['HTTP_HOST']);

    if (isset($conf['PORT_SSL']) && $wi_port != $conf['PORT_SSL'] && isset ($conf['ALLOWHTTP']) && $conf['ALLOWHTTP'] === "1" ){
        $https_url = 'https://'.$wi_ip.':'.$conf['PORT_SSL'];
        $https_alert = "<div class='alert alert-warning text-center'>".t("default_http_https_pre") . "</br>". "<a href=$https_url>$https_url</a>" . t("default_http_https_post") . "</div>\n";
        if (isset ($cb['page']) ){
            $cb['page'] = $https_alert . $cb['page'];
        }
    }
}

switch ($cb['type']) {

case 'render':
    require "templates/header.php";
    require "templates/menu.php";
    require "templates/begincontent.php";
    require "templates/flash.php";

    echo $cb['page'];

    require "templates/endcontent.php";
    require "templates/footer.php";
    require "templates/endpage.php";
    break;
case 'redirect':
    //Header to redirect!
    header('Location: '.$cb['url'], true, 301);
    break;

case 'ajax':
    echo $cb['page'];
    break;

default:
    callbackReturnUnknow($cb['type']);
    break;

}

ob_flush();
