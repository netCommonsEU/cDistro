<?php

function notFunctionExist()
{
    global $Parameters;

    $c = array_shift($Parameters);
    $a = array_shift($Parameters);

    ErrorPage(1, 'function_not_found', t('errors_function_pre')."($c::$a)".t('errors_function_post'));
}

function notFileExist($name = "(unknown)")
{
    ErrorPage(2, 'file_not_found', t('errors_file_pre').$name.t('errors_file_post'));
}

function notReadFile($name = "(unknown)")
{
    ErrorPage(3, 'file_not_readable', t('errors_read_pre').$name.t('errors_read_post'));
}

function notWriteFile($name = "(unknown)")
{
    ErrorPage(4, 'file_not_writeable', t('errors_write_pre').$name.t('errors_write_post'));
}

function callbackReturnUnknow($cmd = "(unknown)")
{
    ErrorPage(5, 'unknown_callback_return', t('errors_callback_pre').$cmd.t('errors_callback_post'));
}

function errorExecuteExternalProgram($cmd = "(unknown)", $output = "(empty)")
{
    ErrorPage(5, 'errorExecuteExternalProgram', t('errors_command_pre').$cmd.t('errors_command_post').$output);
}


function ErrorPage($knum, $kstr, $explain)
{
    global $css, $js, $appName, $staticPath, $appURL, $menu;

    require "templates/header.php";
    require "templates/menu.php";
    require "templates/begincontent.php";
    require "templates/flash.php"; ?>

    <div>
		<h1><?php echo t("errors_error")?></h1>
		<h2><?php echo t("errors_code").": ".$knum." (".$kstr.")" ?></h2>
		<p><?php echo ptxt(print_r($explain, true)); ?></p>
    <?php
    echo addButton(array('label'=>t("errors_button_back"),'class'=>'btn btn-default', 'href'=>'javascript:history.back()'));
    echo addButton(array('label'=>t("errors_button_home"),'class'=>'btn btn-primary', 'href'=>$staticPath)); ?>

	</div>

    <?php
    require "templates/endcontent.php";
    require "templates/footer.php";
    require "templates/endpage.php";



    exit();
}
?>
