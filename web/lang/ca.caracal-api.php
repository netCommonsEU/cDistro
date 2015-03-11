<?php
// plug/lang/*.menu.php

//CaracalDB API

// Alerts
addS ("caracalapi_java_installed_false", "El Java Runtime Environment no està instal·lat");
addS ("caracalapi_java_installed_true", "El Java Runtime Environment està instal·lat");

addS ("caracalapi_caracalapi_installed_false", "L'API REST de CaracalDB no està instal·lada");
addS ("caracalapi_caracalapi_installed_true", "L'API REST de CaracalDB està instal·lada");

addS ("caracalapi_java_install_fail", "La instal·lació de Java ha fallat");
addS ("caracalapi_download_fail", "La descàrrega dels fitxers binaris de l'API REST de CaracalDB ha fallat");
addS ("caracalapi_dependeny_fail", "Ha fallat la instal·lació de les dependències");
addS ("caracalapi_install_success", "S'ha instal·lat l'API REST de CaracalDB");
addS ("caracalapi_install_fail", "La instal·lació de l'API REST de CaracalDB ha fallat");
addS ("caracalapi_uninstall_success", "L'API REST de CaracalDB s'ha desinstal·lat correctament");
addS ("caracalapi_uninstall_fail", "La desinstal·lació de l'API REST de CaracalDB ha fallat");
addS ("caracalapi_running_true", "L'API REST de CaracalDB està en marxa");
addS ("caracalapi_running_false", "L'API REST de CaracalDB està aturada");
addS ("caracalapi_start_success", "L'API REST de CaracalDB s'ha iniciat correctament");
addS ("caracalapi_start_fail", "L'arrencada de l'API REST de CaracalDB ha fallat");
addS ("caracalapi_stop_success", "S'ha aturat l'API REST de CaracalDB");
addS ("caracalapi_stop_fail", "L'aturada de l'API REST de CaracalDB ha fallat");

addS ("caracalapi_saved_config", "S'ha actualitzat el fitxer de configuració");

// Text
addS ("caracalapi_title","API REST de CaracalDB");
addS ("caracalapi_subtitle","Una API REST i una IU web per a l'emmagatzematge de claus-valors distribuït CaracalDB");
addS ("caracalapi_description",'L\'API de CaracalDB és una API REST basada en JSON per accedir a CaracalDB des de serveis externs. El paquet inclou també una web senzilla que fa d\'interfície d\'usuari per a l\'API. Per a més informació podeu llegir sobre <a href="https://github.com/CaracalDB/CaracalDB">CaracalDB a Github</a>.');
addS ("caracalapi_status", "Estat de l'API de CaracalDB:");
addS ("caracalapi_config_file", "Configuració");
addS ("caracalapi_config_edit", "Editar la configuració de l'API de CaracalDB");
addS ("caracalapi_config_path", "Fitxer de configuració actual: ");
addS ("caracalapi_log", "Registres");
addS ("caracalapi_log_path", "Fitxer de registres actual: ");

addS ("caracalapi_scroll_down", "Desplaça't avall fins al final del registre");

// Buttons
addS ("caracalapi_button_install", "Instal·la l'API de CaracalDB");
addS ("caracalapi_button_uninstall", "Desinstal·la l'API de CaracalDB");

addS ("caracalapi_button_start", "Inicia l'API de CaracalDB");
addS ("caracalapi_button_stop", "Atura l'API de CaracalDB");

addS ("caracalapi_button_save", "Desa la configuració");

addS ("caracalapi_button_status", "Torna a l'API de CaracalDB");
addS ("caracalapi_button_reload", "Recarrega la pàgina");
addS ("caracalapi_button_log", "Mostra els registres");
addS ("caracalapi_button_ui", "Interfície web de l'API de CaracalDB");

// Form
addS ("caracalapi_form_bsip", "IP d'arrencada");
addS ("caracalapi_form_bsip_help", "L'adreça IP del servidor d'arrencada. El valor per defecte a Gufi.net és 10.228.207.42.");
addS ("caracalapi_form_bsport", "Port d'arrencada");
addS ("caracalapi_form_bsport_help", "El port al qual el servidor d'arrencada està escoltant les connexions entrants. El valor per defecte és 45678.");
addS ("caracalapi_form_localip", "IP servidor CaracalDB");
addS ("caracalapi_form_localip_help", "L'adreça IP del servidor de CaracalDB. El valor per defecte és l'adreça IP d'aquesta instància de Cloudy.");
addS ("caracalapi_form_localport", "Port servidor CaracalDB");
addS ("caracalapi_form_localport_help", "El port al qual el servidor de CaracalDB està escoltant les connexions entrants. El valor per defecte és 45678.");
addS ("caracalapi_form_webaddr", "Nom de màquina API CaracalDB");
addS ("caracalapi_form_webaddr_help", "El nom de màquina del servidor on l'API de CaracalDB s'executarà. El valor per defecte és localhost.");
addS ("caracalapi_form_webport", "Port web API CaracalDB");
addS ("caracalapi_form_webport_help", "El port on la interfície web de l'API de CaracalDB API estarà escoltant les connexions entrants. El valor per defecte és 8088.");

?>