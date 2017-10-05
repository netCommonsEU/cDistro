<?php

$urlpath="$staticFile/docker-add";
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
    $headers[] = t('Name');
    $headers[] = t('Description');
    $headers[] = t('Ports');
    $headers[] = t('Options');
    $headers[] = t('Requires');
    $headers[] = t('Actions');
    $table .= addTableHeader($headers);

    foreach($files as $key => $value){
      $fcontent = file_get_contents($predDir."/".$value);
      $jcontent = json_decode($fcontent, true);

      $fields = "";
      $fields[] = $jcontent["appname"];
      $fields[] = $jcontent["description"];

      $ports = "";
      foreach ($jcontent["ports"] as $pkey => $pvalue) {
        if ($ports !== "")
          $ports .= "<br>";
        $ports .= $pvalue."=>".$pkey;
      }
      $fields[] = $ports;

      $options = "";
      foreach ($jcontent["options"] as $okey => $ovalue) {
        if ($options !== "")
          $options .= "<br>";
        $options .= $okey."=".$ovalue;
      }
      $fields[] = $options;
s
      $links = "";
      foreach ($jcontent["links"] as $lkey => $lvalue) {
        if ($links !== "")
          $links .= ", ";
        $links .= $lvalue;
      }
      $fields[] = $links;

      $fields[] = addButton(array('label'=>t("docker_button_container_stop"),'class'=>'btn btn-info', 'href'=>"$urlpath/container/stop/".$fields[0]."/".$fields[1]))."<br>".
                  addButton(array('label'=>t("docker_button_container_stop"),'class'=>'btn btn-success', 'href'=>"$urlpath/container/stop/".$fields[0]."/".$fields[1]));
      $table .= addTableRow($fields);
    }
    $table .= addTableFooter();
  }

  $page .= $table;
  return ["page" => $page, "buttons" => $buttons];
}
