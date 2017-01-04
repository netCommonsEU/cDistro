<?php
//docker.php

function get_fancyheaders_from_string($headerstring){
  foreach (get_headers_from_string($headerstring) as $key => $value)
    $fancyheaderstring[] = ucwords(strtolower($value));

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
