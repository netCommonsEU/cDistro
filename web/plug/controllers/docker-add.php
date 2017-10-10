<?php

$urlpath="$staticFile/docker-add";
$returnpath="$staticFile/docker";
$docker_pkg = "docker-ce";
$dev = "docker0";
$predDir=$conf['DOCROOT']."/plug/resources/docker/containers/";

function index() {
  global $title, $urlpath, $docker_pkg, $staticFile, $Parameters, $predDir;

  $page = "";
  $buttons = "";

  //Capçalera
	$page .= hlc(t("docker_title"));
  $page .= hl(t("docker_add_subtitle"),4);

  $page .= par(t("docker_add_desc"));

  $page .= docker_predefined_containers_table()['page'];

  $buttons .= addButton(array('label'=>t("default_button_back"),'class'=>'btn btn-default', 'href'=>"$staticFile/docker"));

  $page .= $buttons;

  return array('type' => 'render','page' => $page);
}

function config() {
  global $title, $urlpath, $docker_pkg, $staticFile, $Parameters, $predDir;

  $page = "";
  $buttons = "";

  //Capçalera
	$page .= hlc(t("docker_title"));
  $page .= hl(t("docker_add_subtitle"),4);

  if (!file_exists($predDir.$Parameters[0])) {
    $page .= txt(t('docker_add_error_no_template'));
    $page .= "<div class='alert alert-error text-center'>".t("docker_alert_no_template_pre") . $Parameters[0] . t("docker_alert_no_template_post") . "</div>\n";
    $buttons .= addButton(array('label'=>t("docker_button_back"),'class'=>'btn btn-default', 'href'=>"$urlpath"));
  }

  else {
    $page .= par(t("docker_add_config_desc"));

    $fcontent = file_get_contents($predDir.$Parameters[0]);
    $jcontent = json_decode($fcontent, true);

    $name = ( isset ($jcontent["name"]) ? $jcontent["name"] : "");

    $buttons .= addButton(array('label'=>t("default_button_back"),'class'=>'btn btn-default', 'href'=>"$urlpath"));

    $page .= createForm(array('class'=>'form-horizontal'));
    $page .= addInput('image',t("docker_add_pdform_image"),$jcontent["image"],array('type'=>'text','readonly'=>'true','placeholder'=>$jcontent["image"]),"",t("docker_add_pdform_image_tooltip"));
    $page .= addInput('name',t("docker_add_pdform_name"),$name,array('type'=>'text','placeholder'=>$name,'pattern'=>"^[a-z0-9\-]+$"),"",t("docker_add_pdform_name_tooltip"));

    if (isset ($jcontent["ports"])) {
      foreach ($jcontent["ports"] as $pkey => $pvalue) {
        $page .= addInput('port_'.$pvalue,t("docker_add_pdform_port").$pvalue,$pkey,array('type'=>'number','placeholder'=>$pkey,'min'=>0,'max'=>65535),"",t("docker_add_pdform_port_tooltip_pre").$pvalue.t("docker_add_pdform_port_tooltip_post"));
      }
    }

    if (isset ($jcontent["options"])) {
      foreach ($jcontent["options"] as $okey => $ovalue) {
        $page .= addInput('option_'.$okey,t("docker_add_pdform_option").$okey,$ovalue,array('type'=>'text','placeholder'=>$ovalue),"",t("docker_add_pdform_option_tooltip_pre").$okey.t("docker_add_pdform_option_tooltip_post"));
      }
    }

    if (isset ($jcontent["links"])) {
      foreach ($jcontent["links"] as $lkey => $lvalue) {
        $page .= addInput('link_'.$lkey,t("docker_add_pdform_link"),$lvalue,array('type'=>'text','placeholder'=>$lvalue),"",t("docker_add_pdform_link_tooltip"));
      }
    }

    $buttons .= addSubmit(array('label'=>t("docker_button_pdform_run"),'class'=>'btn btn-success','divOptions'=>array('class'=>'btn-group')));
  }
  $page .= $buttons;

  return array('type' => 'render','page' => $page);
}

function config_post() {
  global $title, $returnpath, $docker_pkg, $staticFile, $Parameters, $predDir;

  $page = "";
  $buttons = "";

  //Capçalera
	$page .= hlc(t("docker_title"));
  $page .= hl(t("docker_add_subtitle"),4);

  if (isset($_POST) && isset($_POST["image"])){

    $page .= ptxt (print_r($_POST,1));

    $image = $_POST["image"];

    $name = ( isset ($_POST["name"]) ? $_POST["name"] : null);

    $ports = null;
    $options = null;
    $links = null;

    foreach ($_POST as $key => $value) {
      if ( startsWith($key,'port_')){
        $ports[str_replace("port_","",$key)] = $value;
      }
      if ( startsWith($key,'option_')){
        $options[str_replace("option_","",$key)] = $value;
      }
      if ( startsWith($key,'link_')){
        $links[] = $value;
      }
    }

    _dockerrun($name, $ports, $options, $links, $image );
    return(array('type'=> 'redirect', 'url' => $returnpath));
  }

  $page .= $buttons;
  return array('type' => 'render','page' => $page);
}

function launch() {
  global $title, $urlpath, $docker_pkg, $staticFile, $Parameters, $predDir, $returnpath;

  $page = "";
  $buttons = "";

  if (!file_exists($predDir.$Parameters[0])) {
    $page .= hlc(t("docker_title"));
    $page .= hl(t("docker_add_subtitle"),4);
    $page .= txt(t('docker_add_error_no_template'));
    $page .= "<div class='alert alert-error text-center'>".t("docker_alert_no_template_pre") . $Parameters[0] . t("docker_alert_no_template_post") . "</div>\n";
    $buttons .= addButton(array('label'=>t("docker_button_back"),'class'=>'btn btn-default', 'href'=>"$urlpath"));
  }

  else {
    $fcontent = file_get_contents($predDir.$Parameters[0]);
    $jcontent = json_decode($fcontent, true);

    $name = ( isset ($jcontent["name"]) && $jcontent["name"] !== null && $jcontent["name"] !== "" ? $jcontent["name"] : null );

    if (isset ($jcontent["ports"])) {
      foreach ($jcontent["ports"] as $pkey => $pvalue) {
        $ports[$pvalue] = $pkey;
      }
    }
    else {
      $ports = null;
    }

    if (isset ($jcontent["options"])) {
      foreach ($jcontent["options"] as $okey => $ovalue) {
        $options[$okey] = $ovalue;
      }
    }
    else {
      $options = null;
    }

    if (isset ($jcontent["links"])) {
      foreach ($jcontent["links"] as $lkey => $lvalue) {
        $links[$lkey] = $lvalue;
      }
    }
    else {
      $links = null;
    }

  _dockerrun($name, $ports, $options, $links, $jcontent["image"] );
  return(array('type'=> 'redirect', 'url' => $returnpath));
  }

  $page .= $buttons;
  return array('type' => 'render','page' => $page);
}

function docker_predefined_containers_table(){
  global $dev, $title, $urlpath, $docker_pkg, $staticFile, $predDir;

  $page = "";
  $buttons = "";
  $dock_count = 0;

  $allfiles = array_diff(scandir($predDir), array('.','..'));

  foreach ($allfiles as $key => $value) {
    if (endsWith ($value, '.json'))
    $files[] = $value;
  }

  print_r($files,1);

  if ( sizeof ($files) > 0 ){
    $table = "";
    $headers[] = t('docker_add_header_appname');
    $headers[] = t('docker_add_header_description');
    $headers[] = t('docker_add_header_ports');
    $headers[] = t('docker_add_header_options');
    $headers[] = t('docker_add_header_links');
    $headers[] = t('docker_add_header_actions');
    $headers[] = "";
    $table .= addTableHeader($headers);

    foreach($files as $fkey => $fvalue){
      $fcontent = file_get_contents($predDir."/".$fvalue);
      $jcontent = json_decode($fcontent, true);

      $fields = "";
      $fields[] = $jcontent["appname"];
      $fields[] = $jcontent["description"];

      $ports = "";
      if (isset($jcontent["ports"])) {
        foreach ($jcontent["ports"] as $pkey => $pvalue) {
          if ($ports !== "")
            $ports .= "<br>";
          $ports .= $pvalue."=>".$pkey;
        }
      }
      $fields[] = $ports;

      $options = "";
      if (isset($jcontent["options"])) {
        foreach ($jcontent["options"] as $okey => $ovalue) {
          if ($options !== "")
            $options .= "<br>";
          $options .= $okey."=".$ovalue;
        }
      }
      $fields[] = $options;

      $links = "";
      if (isset($jcontent["links"])) {
        foreach ($jcontent["links"] as $lkey => $lvalue) {
          if ($links !== "")
            $links .= ", ";
          $links .= $lvalue;
        }
      }
      $fields[] = $links;

      $fields[] = addButton(array('label'=>t("docker_button_pdcontainer_config"),'class'=>'btn btn-info', 'href'=>"$urlpath/config/".$fvalue)).addButton(array('label'=>t("docker_button_pdcontainer_run"),'class'=>'btn btn-success', 'href'=>"$urlpath/launch/".$fvalue));
      $table .= addTableRow($fields);
    }
    $table .= addTableFooter();
  }

  $page .= $table;
  return ["page" => $page, "buttons" => $buttons];
}
