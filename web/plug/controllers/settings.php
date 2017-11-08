<?php
// settings.php

$urlpath="settings";

$PKG_HOSTNAME = "hostname";
$HREGEX = "^([a-zA-Z0-9]|[a-zA-Z0-9]+[a-zA-Z0-9\-]*[a-zA-Z0-9])[a-zA-Z0-9]*";
$HPREGEX = "/".$HREGEX."/";

$SOURCES_MAIN = "/etc/apt/sources.list";
$SOURCESD_PATH = "/etc/apt/sources.list.d";


function index()
{
    global $urlpath, $staticPath;

    $page = "";
    $buttons = "";

    $page .= hlc(t("settings_common_title"));
    $page .= hl(t("settings_common_subtitle"), 4);

    $page .= par(t("settings_index_description"));

    $page .= hlc(t("settings_hostname_title"), 2);
    $page .= txt(t("settings_hostname_current"));
    $page .= ptxt(gethostname());
    $buttons = addButton(array('label'=>t("settings_button_hostname"),'class'=>'btn btn-primary', 'href'=>$staticPath.$urlpath.'/hostname'));
    $page .= $buttons;


    $page .= hlc(t("settings_network_title"), 2);
    $page .= txt(t("settings_network_primaryint"));
    $page .= ptxt(getnetworkprimaryhr());
    if (getnetworkprimary()) {
        $buttons = addButton(array('label'=>t("settings_button_network_modify"),'class'=>'btn btn-primary', 'href'=>$staticPath.$urlpath.'/network'));
    } else {
        $buttons = addButton(array('label'=>t("settings_button_network_configure"),'class'=>'btn btn-success', 'href'=>$staticPath.$urlpath.'/network'));
    }
    $page .= $buttons;


    list($page_t, $buttons_t) = indexSources();
    $page .= $page_t;
    $page .= $buttons_t;

    $buttons = addButton(array('label'=>t("settings_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath));

    $page .= par(" ");
    $page .= $buttons;

    return(array('type'=>'render','page'=>$page));
}


function hostname()
{
    global $staticPath, $PKG_HOSTNAME, $HREGEX, $HPREGEX;

    $page = "";
    $buttons = "";

    $page .= hlc(t("settings_hostname_title"), 1);
    $page .= hl(t("settings_hostname_subtitle"), 4);

    $page .= par(t("settings_hostname_description"));

    if (isset($_POST['HOSTNAME'])) {
        if (preg_match($HPREGEX, $_POST['HOSTNAME'])) {
            return setHostname($_POST['HOSTNAME']);
        } else {
            $page .= txt(t("settings_hostname_invalid"));
            if (strlen($_POST['HOSTNAME'])) {
                $page .= "<div class='alert alert-error text-center'>".'"'.$_POST['HOSTNAME'].'"'."</div>\n";
            } else {
                $page .= "<div class='alert alert-error text-center'>".t("settings_hostname_empty")."</div>\n";
            }
        }
    }

    $page .= createForm(array('class'=>'form-horizontal'));
    $page .= addInput('HOSTNAME', t('settings_hostname_fname'), gethostname(), array('type'=>'text', 'required'=>false, "placeholder"=>t('settings_hostname_fplaceholder'), "pattern"=>$HREGEX), '', t("settings_hostname_ftooltip"));
    $buttons .= addSubmit(array('label'=>t('settings_button_shostname'),'class'=>'btn btn-success'));

    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function setHostname($newhostname)
{
    global $PKG_HOSTNAME, $staticPath, $urlpath, $HPREGEX;

    if (isPackageInstall($PKG_HOSTNAME) && preg_match($HPREGEX, $newhostname)) {
        $cmd = "hostname"." ".$newhostname;
        shell_exec($cmd);

        if ($newhostname == gethostname()) {
            setFlash(t('settings_flash_hostname'), "success");
            return(array('type'=>'redirect','url'=>$staticPath.$urlpath));
        }
    }

    setFlash(t('settings_flash_hostnamefail'), "error");
    return(array('type'=>'redirect','url'=>$staticPath.$urlpath));
}


function set_primary_interface($interface)
{
    global $CLOUDY_CONF_FILE, $CLOUDY_CONF_DIR, $staticPath, $urlpath;

    $dataSave['PRIMARYINTERFACE'] = '"'.$interface.'"';

    write_merge_conffile($CLOUDY_CONF_DIR.$CLOUDY_CONF_FILE, $dataSave);

    if (getnetworkprimary() == $interface) {
        setFlash(t('settings_flash_network_primaryint_success'), "success");
        return(array('type'=>'redirect','url'=>$staticPath.$urlpath));
    } else {
        setFlash(t('settings_flash_network_primaryint_fail'), "error");
        return(array('type'=>'redirect','url'=>$staticPath.$urlpath));
    }
}


function network()
{
    global $staticPath, $urlpath, $PKG_HOSTNAME, $HREGEX, $HPREGEX;

    $page = "";
    $buttons = "";

    $page .= hlc(t("settings_network_title"), 1);
    $page .= hl(t("settings_network_subtitle"), 4);

    $page .= par(t("settings_network_description"));

    if (isset($_POST['PRIMARYINT'])) {
        if (is_valid_network_interface($_POST['PRIMARYINT'])) {
            return set_primary_interface($_POST['PRIMARYINT']);
        } else {
            $page .= txt(t("settings_network_primaryint_invalid"));
            if (strlen($_POST['PRIMARYINT'])) {
                $page .= "<div class='alert alert-error text-center'>".'"'.$_POST['PRIMARYINT'].'"'."</div>\n";
            } else {
                $page .= "<div class='alert alert-error text-center'>".t("settings_network_primaryint_empty")."</div>\n";
            }
        }
    }

    $page .= createForm(array('class'=>'form-horizontal'));
    $page .= addSelect('PRIMARYINT', t("settings_network_form_primaryint"), getnetworkinterfaces(), array('type'=>'text','required'=>'true', 'selected'=>getnetworkprimary()), '', t("settings_network_form_primaryint_tooltip"), null, getnetworkprimary());

    $buttons = addButton(array('label'=>t("settings_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath.$urlpath));
    $buttons .= addSubmit(array('label'=>t('settings_button_network_setprimary'),'class'=>'btn btn-success'));

    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function indexSources()
{
    global $urlpath, $staticPath, $SOURCES_MAIN, $SOURCESD_PATH;

    $page = "";
    $buttons = "";

    $page .= hlc(t("settings_sources_title"), 2);
    $page .= txt(t("settings_sources_main_pre")."<em>".$SOURCES_MAIN."</em>".t("settings_sources_main_post"));
    $page .= ptxt(file_get_contents($SOURCES_MAIN), str_replace(".", "", str_replace("/", "", $SOURCES_MAIN)));
    $buttons .= addButton(array('label'=>t("settings_button_sources_pre").$SOURCES_MAIN.t("settings_button_sources_post"),'class'=>'btn btn-primary', 'href'=>$staticPath.$urlpath.'/sourceManage?file='.$SOURCES_MAIN));

    foreach (scandir($SOURCESD_PATH) as $key => $value) {
        if ($value != '.' && $value != '..') {
            $page .= txt(t("settings_sources_file_pre")."<em>".$SOURCESD_PATH.'/'.$value."</em>".t("settings_sources_file_post"));
            $page .= ptxt(file_get_contents($SOURCESD_PATH.'/'.$value), str_replace(".", "", $value));
            $buttons .= addButton(array('label'=>t("settings_button_sources_pre").$value.t("settings_button_sources_post"),'class'=>'btn btn-primary', 'href'=>$staticPath.$urlpath.'/sourceManage?file='.$SOURCESD_PATH.'/'.$value));
        }
    }

    $buttons .= addButton(array('label'=>t("settings_button_sources_addfile"),'class'=>'btn btn-info', 'href'=>$staticPath.$urlpath.'/addSourceFile'));

    return array($page,$buttons);
}

function sourceManage()
{
    global $urlpath, $staticPath;

    $page = "";
    $buttons = "";

    if (isset($_GET['file']) && is_string($_GET['file']) && validSourceFile($_GET['file'])) {
        $subtitle = $_GET['file'];
    } else {
        $subtitle = t("settings_sources_subtitle");
    }

    $page .= hlc(t("settings_sources_title"));
    $page .= hl($subtitle, 4);

    if ($subtitle == t("settings_sources_subtitle")) {
        $page .= txt(t("settings_common_error"));
        $page .= "<div class='alert alert-error text-center'>".t("settings_sources_fileinvalid_pre")."<em>".$_GET['file']."</em>".t("settings_sources_fileinvalid_post")."</div>\n";
        $buttons = addButton(array('label'=>t("settings_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath.$urlpath));
    } else {
        if (isset($_GET['line']) && ctype_digit($_GET['line']) && isset($_GET['toggle'])) {
            toggleSource($_GET['file'], $_GET['line']);
        }

        $page .= par(t("settings_sources_description1").$_GET['file'].t("settings_sources_description2").' '.t("settings_sources_description3"));

        $page .= sourceFileTable($_GET['file'])['page'];

        $buttons .= addButton(array('label'=>t("settings_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath.$urlpath));

        //if ( isset ($_GET['add']))
        //else
        //	$buttons .= addButton(array('label'=>t("settings_button_source_addline"),'class'=>'btn btn-info', 'href'=>"?file=".$_GET['file']."&add=".true));
    }

    $page .= $buttons;

    return(array('type'=>'render','page'=>$page));
}

function sourceFileTable($file)
{
    $table = "";

    $table .= addTableHeader(array(t('settings_sources_header1'), t('settings_sources_header2') , t('settings_sources_header3')));
    foreach (file($file) as $line => $content) {
        $table .= addTableRow(
            array("<strong>".$line."</strong>",
                                     (strpos(trim($content), '#') !== false ? trim($content) : "<strong>".trim($content)."<strong>"),
                                     sourceLineButton($content, $file, $line))
        );
    }

    $table .= addTableFooter();

    return(array('type'=>'ajax','page'=>$table));
}

function sourceLineButton($content, $file, $line)
{
    if (!is_null($content) && is_string($content) && (strpos($content, 'deb') !==false)) {
        if (strpos(trim($content), '#') !== false) {
            return (addButton(array('label'=>t("settings_button_enable"),'class'=>'btn btn-info', 'href'=>"?file=".$file."&line=".$line."&toggle=".true)));
        } else {
            return (addButton(array('label'=>t("settings_button_disable"),'class'=>'btn btn-warning', 'href'=>"?file=".$file."&line=".$line."&toggle=".true)));
        }
    }

    return;
}

function addSourceFile()
{
    global $urlpath, $staticPath, $SOURCESD_PATH;

    $page = "";
    $buttons = "";

    $page .= hlc(t("settings_sources_title"));
    $page .= hl(t("settings_sources_add_subtitle"), 4);

    if (empty($_POST) || empty($_POST['FILENAME']) || empty($_POST['CONTENT'])) {
        if (!empty($_POST) && empty($_POST['FILENAME'])) {
            $page .= txt(t("settings_sources_add_form_error"));
            $page .= "<div class='alert alert-error text-center'>".t("settings_sources_add_form_error_filename")."</div>\n";
        }

        if (!empty($_POST) && empty($_POST['CONTENT'])) {
            $page .= txt(t("settings_sources_add_form_error"));
            $page .= "<div class='alert alert-error text-center'>".t("settings_sources_add_form_error_content")."</div>\n";
        }

        $page .= par(t("settings_sources_add_desc"));

        if (!empty($_POST['CONTENT'])) {
            $sample_content = $_POST['CONTENT'];
        }

        $page .= createForm(array('class'=>'form-horizontal'));
        $page .= addInput('FILENAME', t('settings_sources_add_form_filename'), $_POST['FILENAME'], array('type'=>'text', 'pattern'=>'[A-Za-z0-9_\-\s\.]+.*(?!list)','required'=>'true'), 'false', t('settings_sources_add_form_filename_tooltip'));
        $page .= addTextArea('CONTENT', t('settings_sources_add_form_content'), t('settings_sources_add_form_content_placeholder'), array('rows'=>10, 'cols'=>"80", 'required'=>'true'), 'style="width: 600px"', t('settings_sources_add_form_content_tooltip'));

        $buttons .= addButton(array('label'=>t("settings_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath.$urlpath));
        $buttons .= addSubmit(array('label'=>t("settings_sources_add_form_submit"),'class'=>'btn btn-success','divOptions'=>array('class'=>'btn-group')));
    } else {
        $file = $SOURCESD_PATH.'/'.$_POST['FILENAME'].'.list';

        addSource($file, $_POST['CONTENT']);

        $buttons .= addButton(array('label'=>t("settings_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath.$urlpath));

        if (!validSourceFile($file)) {
            $page .= txt(t("settings_sources_add_contents_pre").$file.t("settings_sources_add_contents_post"));
            $page .= ptxt(file_get_contents($file), str_replace(".", "", $value));
        } else {
            $page .= txt(t("settings_sources_add_form_error"));
            $page .= "<div class='alert alert-error text-center'>".t("settings_sources_add_form_error_file")."</div>\n";
            $buttons .= addButton(array('label'=>t("settings_button_retry"),'class'=>'btn btn-warning', 'href'=>$staticPath.$urlpath.'/addSourceFile'));
        }
    }

    $page .= $buttons;
    return(array('type'=>'render','page'=>$page));
}

function toggleSource($file, $line)
{
    $sfile = file($file);
    if (strpos(trim($sfile[$line]), '#') !== false) {
        $sfile[$line] = trim(trim($sfile[$line]), '#')."\n";
    } else {
        $sfile[$line] = '#'.$sfile[$line];
    }

    file_put_contents($file, $sfile);

    return;
}

function getnetworkinterfaces()
{
    $interfaces;

    if (is_dir('/sys/class/net')) {
    }
    foreach (scandir('/sys/class/net') as $k=>$v) {
        if ($v !== '.' && $v !== '..') {
            $interfaces[$v] = $v;
        }
    }

    return $interfaces;
}


function getnetworkprimary()
{
    global $CLOUDY_CONF_FILE, $CLOUDY_CONF_DIR;

    if (file_exists($CLOUDY_CONF_DIR.$CLOUDY_CONF_FILE)) {
        $conf = parse_bash_file($CLOUDY_CONF_DIR.$CLOUDY_CONF_FILE);

        if (isset($conf['PRIMARYINTERFACE']) && $conf['PRIMARYINTERFACE'] !== "") {
            return ($conf['PRIMARYINTERFACE']);
        }
    }
}

function getnetworkprimaryhr()
{
    $gnp = getnetworkprimary();

    if ($gnp !== null) {
        return $gnp;
    } else {
        return t("settings_network_empty");
    }
}

function is_valid_network_interface($interface = null)
{
    foreach (getnetworkinterfaces() as $k=>$v) {
        if ($interface === $v) {
            return true;
        }
    }

    return false;
}
