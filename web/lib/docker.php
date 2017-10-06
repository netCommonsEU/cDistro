<?php
//docker.php

function docker_img(){
  global $dev, $title, $urlpath, $docker_pkg, $staticFile;

  $page = "";
  $buttons = "";
  $table = "";

  $page .= txt(t("docker_title_images"));

  $ret = execute_program_shell('docker images');

  $retarray = explode(PHP_EOL,$ret['output']);

  if ( array_key_exists(2, $retarray )) {
    $headers = get_fancyheaders_from_string($retarray[0]);
    $headers[] = t('Actions');
    $headerspos = get_headers_position_in_string($retarray[0]);
    $table .= addTableHeader($headers);
    foreach($retarray as $key => $value){
      if ($key > 0) {
        $fields = get_fields_in_string($value, $headerspos);
        if ($fields[0] != "") {
          $fields[] = addButton(array('label'=>t("docker_button_image_rmi"),'class'=>'btn btn-danger', 'href'=>"$urlpath/image/rmi/".trim($fields[2])."/".$fields[0]));
          $fields[] = addButton(array('label'=>t("docker_button_image_run"),'class'=>'btn btn-success', 'href'=>"$urlpath/image/run/".trim($fields[2])."/".$fields[0]));
          $table .= addTableRow($fields);
        }
      }
    }
    $table .= addTableFooter();
  }
  else {
    $page .= "<div class='alert alert-info text-center'>".t("docker_alert_img_not_available")."</div>\n";
  }

  $page .= $table;

  return ["page" => $page, "buttons" => $buttons];
}


function docker_ps_running_table($dock_filter = null){
  global $dev, $title, $urlpath, $docker_pkg, $staticFile;

  $page = "";
  $buttons = "";
  $dock_count = 0;

  $ret = execute_program_shell('docker ps -n=-1');

  $retarray = explode(PHP_EOL,$ret['output']);

  if ( array_key_exists(2, $retarray )) {
    $table = "";
    $headers = get_fancyheaders_from_string($retarray[0]);
    $headers[] = t('Action');
    $headerspos = get_headers_position_in_string($retarray[0]);
    $table .= addTableHeader($headers);
    foreach($retarray as $key => $value){
      if ($key > 0) {
        $fields = get_fields_in_string($value, $headerspos);

        if ($fields[0] != "") {
          $fields[0] = preg_replace('/\s+/', '', $fields[0]);
          $fields[6] = preg_replace('/\s+/', '', $fields[6]);
          $fields[] = addButton(array('label'=>t("docker_button_container_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/container/stop/".$fields[0]."/".$fields[6]));
          if (($dock_filter === null) || ( strpos($fields[6], $dock_filter.'_') !== false))
            {
            $table .= addTableRow($fields);
            $dock_count++;
        }
        }
      }
    }
    $table .= addTableFooter();
  }

  if ($dock_count > 0)
      $page .= $table;
  else
    $page .= "<div class='alert alert-info text-center'>".t("docker_alert_ps_not_running")."</div>\n";

  return ["page" => $page, "buttons" => $buttons];
}


function docker_ps_stopped_table($dock_filter = null){
  global $dev, $title, $urlpath, $docker_pkg, $staticFile;

  $page = "";
  $buttons = "";
  $dock_count = 0;

  $ret = execute_program_shell('docker ps -a -n=-1 -f status=exited -f status=created');

  $retarray = explode(PHP_EOL,$ret['output']);

  if ( array_key_exists(2, $retarray )) {
    $table = "";
    $headers = get_fancyheaders_from_string($retarray[0]);
    $headers[] = t('Actions');
    $headerspos = get_headers_position_in_string($retarray[0]);
    $table .= addTableHeader($headers);
    foreach($retarray as $key => $value){
      if ($key > 0) {
        $fields = get_fields_in_string($value, $headerspos);

        if ($fields[0] != "") {
          $fields[0] = preg_replace('/\s+/', '', $fields[0]);
          $fields[6] = preg_replace('/\s+/', '', $fields[6]);
          $fields[] = addButton(array('label'=>t("docker_button_container_rm"),'class'=>'btn btn-danger', 'href'=>"$urlpath/container/rm/".$fields[0]."/".$fields[6]));
          $fields[] = addButton(array('label'=>t("docker_button_container_restart"),'class'=>'btn btn-success', 'href'=>"$urlpath/container/restart/".$fields[0]."/".$fields[6]));
          if (($dock_filter === null) || ( strpos($fields[6], $dock_filter.'_') !== false)) {
              $table .= addTableRow($fields);
              $dock_count++;
          }
        }
      }
    }
    $table .= addTableFooter();
    }

    if ($dock_count > 0)
        $page .= $table;
    else
        $page .= "<div class='alert alert-info text-center'>".t("docker_alert_ps_not_stopped")."</div>\n";

  return ["page" => $page, "buttons" => $buttons];
}


function get_fancyheaders_from_string($headerstring){
  foreach (get_headers_from_string($headerstring) as $key => $value)
    $fancyheaderstring[] = preg_replace('/\s+/', '', ucwords(strtolower($value)));

    foreach ($fancyheaderstring as $key => $value) {
      switch ($value) {
        case "Name":
          $fancyheaderstring[$key] = t("docker_search_header_name");
          break;
        case "Description":
          $fancyheaderstring[$key] = t("docker_search_header_description");
          break;
        case "Stars":
          $fancyheaderstring[$key] = t("docker_search_header_stars");
          break;
        case "Official":
          $fancyheaderstring[$key] = t("docker_search_header_official");
          break;
        case "Automated":
          $fancyheaderstring[$key] = t("docker_search_header_automated");
          break;
      }
    }
  return $fancyheaderstring;
}

function get_fields_in_string($fieldsstring, $fieldspos){
  $fieldspos = array_reverse($fieldspos);
  $fields = array();

  foreach ($fieldspos as $key => $value) {
    $fields[] = substr($fieldsstring, $value);
    $fieldsstring = substr($fieldsstring, 0, $value);
  }

  return array_reverse($fields);
}

function get_headers_from_string($headerstring){
  return get_fields_in_string($headerstring, get_headers_position_in_string($headerstring));
}

function get_headers_position_in_string($headerstring){
  $arrstring = str_split($headerstring);
  $headerspos = array();

  foreach ($arrstring as $key => $value)
    if ($value != " " && ($key == 0 || ($key == 1 && $arrstring[0] == " ") || ($key > 1 && $arrstring[$key-1] == " " && $arrstring[$key-2] == " " ) ))
      $headerspos[] = $key;

  return $headerspos;
}

function docker_ps_running(){
    global $dev, $title, $urlpath, $docker_pkg, $staticFile;

    $dock_run = array();
    $ret = execute_program_shell('docker ps -n=-1');

    $retarray = explode(PHP_EOL,$ret['output']);

    if ( array_key_exists(2, $retarray )) {
      $headers = get_fancyheaders_from_string($retarray[0]);
      $headerspos = get_headers_position_in_string($retarray[0]);

      foreach($retarray as $key => $value){
        if ($key > 0) {
          $fields = get_fields_in_string($value, $headerspos);

          if ($fields[0] != "") {
            $fields[0] = preg_replace('/\s+/', '', $fields[0]);
            $fields[6] = preg_replace('/\s+/', '', $fields[6]);
            array_push($dock_run, $fields[6]);
          }
        }
      }
    }
    return ($dock_run);
}

function docker_ps_stopped(){
    global $dev, $title, $urlpath, $docker_pkg, $staticFile;

    $dock_stop = array();
    $ret = execute_program_shell('docker ps -a -n=-1 -f status=exited');

    $retarray = explode(PHP_EOL,$ret['output']);

    if ( array_key_exists(2, $retarray )) {
      $headers = get_fancyheaders_from_string($retarray[0]);
      $headerspos = get_headers_position_in_string($retarray[0]);

      foreach($retarray as $key => $value){
        if ($key > 0) {
          $fields = get_fields_in_string($value, $headerspos);

          if ($fields[0] != "") {
            $fields[6] = preg_replace('/\s+/', '', $fields[6]);
            array_push($dock_stop, $fields[6]);
          }
        }
      }
    }
    return ($dock_stop);
}

function _dockercontainerrm($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker stop " . $id . " && docker rm " . $id);
    setFlash( t("docker_flash_rm_pre") . $name . t("docker_flash_rm_mid") . $id . t("docker_flash_rm_post"));

  return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockercontainerpull($name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker pull " . $name);
    setFlash( t("docker_flash_pull_pre") . "<b>" . $name . "</b>" . t("docker_flash_pull_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockercontainerstop($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker stop " . $id);
    setFlash( t("docker_flash_stop_pre") . $name . t("docker_flash_stop_mid") . $id . t("docker_flash_stop_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}

function _dockercontainerstopcmd($id) {
    execute_program_detached("docker stop " . $id);
}


function _dockercontainerrestart($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker restart " . $id);
    setFlash( t("docker_flash_restart_pre") . $name . t("docker_flash_restart_mid") . $id . t("docker_flash_restart_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockerimagermi($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker rmi " . $id);
    setFlash( t("docker_flash_rmi_pre") . $name . t("docker_flash_rmi_mid") . $id . t("docker_flash_rmi_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockerimagerun($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker run " . $id);
    setFlash( t("docker_flash_run_pre") . $name . t("docker_flash_run_mid") . $id . t("docker_flash_run_post"));

	return(array('type'=> 'redirect', 'url' => $urlpath));
}

function _dockerrun($name = null, $ports = null, $options = null, $links = null, $image ) {
    global $Parameters, $urlpath, $staticFile;

    $command = "docker run ";

    if ($name !== null && $name !== "")
      $command .= "--name ".$name." ";

    foreach ($ports as $pkey => $pvalue) {
      $command .= "-p ".$pvalue.":".$pkey." ";
    }

    foreach ($options as $okey => $ovalue) {
      $command .= "-e ".$okey."=".$ovalue." ";
    }

    foreach ($links as $lkey => $lvalue) {
      $command .= "--link ".$lvalue." ";
    }

    $command .= "-d ".$image;

    execute_program_detached($command);

    setFlash( t("docker_flash_run_pre") . $image . t("docker_flash_run_post"));

	return(array('type'=> 'redirect', 'url' => $urlpath));
}
