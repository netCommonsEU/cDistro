<?php
//docker.php

function docker_img(){
  global $dev, $title, $urlpath, $docker_pkg, $staticFile;

  $page = "";
  $buttons = "";

  $page .= txt(t("docker_title_images"));

  $ret = execute_program_shell('docker images');

  $retarray = explode(PHP_EOL,$ret['output']);

  $headers = get_fancyheaders_from_string($retarray[0]);
  $headers[] = t('Action');
  $headerspos = get_headers_position_in_string($retarray[0]);

  $table = "";

  $table .= addTableHeader($headers);
  foreach($retarray as $key => $value){
    if ($key > 0) {
      $fields = get_fields_in_string($value, $headerspos);

      if ($fields[0] != "") {
        $fields[] = addButton(array('label'=>t("default_button_dummy"),'class'=>'btn btn-default'));
        $table .= addTableRow($fields);
      }
    }
  }
  $table .= addTableFooter();

  $page .= $table;

  return ["page" => $page, "buttons" => $buttons];
}


function docker_ps_running(){
  global $dev, $title, $urlpath, $docker_pkg, $staticFile;

  $page = "";
  $buttons = "";

  $page .= txt(t("docker_title_containers_running"));

  $ret = execute_program_shell('docker ps -n=-1');

  $retarray = explode(PHP_EOL,$ret['output']);

  $headers = get_fancyheaders_from_string($retarray[0]);
  $headers[] = t('Action');
  $headerspos = get_headers_position_in_string($retarray[0]);

  $table = "";

  $table .= addTableHeader($headers);
  foreach($retarray as $key => $value){
    if ($key > 0) {
      $fields = get_fields_in_string($value, $headerspos);

      if ($fields[0] != "") {
        $fields[0] = preg_replace('/\s+/', '', $fields[0]);
        $fields[6] = preg_replace('/\s+/', '', $fields[6]);
        $fields[] = addButton(array('label'=>t("docker_button_container_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/container/stop/".$fields[0]."/".$fields[6]));
        $table .= addTableRow($fields);
      }
    }
  }
  $table .= addTableFooter();

  $page .= $table;

  return ["page" => $page, "buttons" => $buttons];
}


function docker_ps_stopped(){
  global $dev, $title, $urlpath, $docker_pkg, $staticFile;

  $page = "";
  $buttons = "";

  $page .= txt(t("docker_title_containers_stopped"));

  $ret = execute_program_shell('docker ps -a -n=-1 -f status=exited');

  $retarray = explode(PHP_EOL,$ret['output']);

  $headers = get_fancyheaders_from_string($retarray[0]);
  $headers[] = t('Action');
  $headerspos = get_headers_position_in_string($retarray[0]);

  $table = "";

  $table .= addTableHeader($headers);
  foreach($retarray as $key => $value){
    if ($key > 0) {
      $fields = get_fields_in_string($value, $headerspos);

      if ($fields[0] != "") {
        $fields[0] = preg_replace('/\s+/', '', $fields[0]);
        $fields[6] = preg_replace('/\s+/', '', $fields[6]);
        $fields[] = addButton(array('label'=>t("docker_button_container_rm"),'class'=>'btn btn-danger', 'href'=>"$urlpath/container/rm/".$fields[0]."/".$fields[6]));
        $table .= addTableRow($fields);
      }
    }
  }
  $table .= addTableFooter();

  $page .= $table;

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
