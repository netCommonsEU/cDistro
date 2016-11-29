<?php
$urlpath="$staticFile/docker-search";
$docker_pkg = "docker.io";
$dev = "docker0";

function index() {
  global $title, $urlpath, $docker_pkg, $staticFile, $Parameters;

  //Capçalera
	$page = hlc(t("docker_title"));
  $page .= hl(t("docker_search_subtitle"),4);

  $page .= par(t("docker_search_desc"));

  $buttons .= addButton(array('label'=>t("default_button_back"),'class'=>'btn btn-default', 'href'=>"$urlpath/docker"));


	//Formulari
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('search',t("docker_search_form_search"),$con['search'],array('type'=>'text','required'=>true,'placeholder'=>'etherpad','pattern'=>"[a-z0-9_-\.]+"),"",t("docker_search_form_search_tooltip"));
  $page .= addCheckbox('automated',t("docker_search_form_automated"),$con['automated'],array('type'=>'checkbox'),"",t("docker_search_form_automated_tooltip"));
	$buttons .= addSubmit(array('label'=>t("docker_button_search"),'class'=>'btn btn-success','divOptions'=>array('class'=>'btn-group')));

  $page .= $buttons;

  return array('type' => 'render','page' => $page);
}

function index_post(){
  global $staticFile, $staticPath, $urlpath;

  //Capçalera
  $page = hlc(t("docker_title"));
  $page .= hl(t("docker_search_subtitle"),4);

  $POSTdata = array();
  foreach ($_POST as $key => $value) {
    $POSTdata[$key] = $value;
  }

  $eps_options = '--no-trunc ';

  if ($POSTdata['automated'])
    $eps_options .= '--automated ';

  $ret = execute_program_shell('docker search ' . $eps_options . $POSTdata['search']);

  //$page .= ptxt(print_r($ret['output'], true));

  $retarray = explode(PHP_EOL,$ret['output']);

  //$page .= ptxt(print_r($retarray, true));

  foreach ($retarray as $key => $value) {
//    $page .= txt($key . ': ' . $value);
  }

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


  return array('type' => 'render','page' => $page);

}


function get_fancyheaders_from_string($headerstring){
  foreach (get_headers_from_string($headerstring) as $key => $value)
    $fancyheaderstring[] = ucwords(strtolower($value));

  return $fancyheaderstring;
}

function get_fields_in_string($fieldsstring, $fieldspos){
  $page .= txt($fieldsstring);
  $fieldspos = array_reverse($fieldspos);
  $fields = array();

  foreach ($fieldspos as $key => $value) {
    $fields[] = substr($fieldsstring, $value);
    $fieldsstring = substr($fieldsstring, 0, $value);
  }

  return array_reverse($fields);
}

function get_headers_from_string($headerstring){
  return preg_split('/\s+/', $headerstring);
}

function get_headers_position_in_string($headerstring){
  $arrstring = str_split($headerstring);
  $headerspos = array();

  foreach ($arrstring as $key => $value)
    if ($value != " " && ($key == 0 || $arrstring[$key-1] == " " )) {
      $headerspos[] = $key;
    }

  return $headerspos;
}
