<?php
// plug/lang/*.menu.php

//Caracal

//alerts
addS ("caracal_java_installed_false","Java Runtime Environment no està instal·lat");
addS ("caracal_java_installed_true","Java Runtime Environment està instal·lat");

addS ("caracal_caracal_installed_false", "CaracalDB Server no està instal·lat");
addS ("caracal_caracal_installed_true", "CaracalDB està instal·lat");

addS ("caracal_java_install_fail", "La instal·lació de Java ha fallat");
addS ("caracal_download_fail", "La descàrrega dels fitxers binaris de CaracalDB ha fallat");
addS ("caracal_install_success", "CaracalDB s'ha instal·lat correctament");
addS ("caracal_install_fail", "La instal·lació de CaracalDB ha fallat");
addS ("caracal_uninstall_success", "S'ha desinstal·lat CaracalDB");
addS ("caracal_uninstall_fail", "La desinstal·lació de CaracalDB ha fallat");
addS ("caracal_running_true", "CaracalDB està en marxa");
addS ("caracal_running_false", "CaracalDB està aturat");
addS ("caracal_start_success", "S'ha iniciat CaracalDB");
addS ("caracal_start_fail", "CaracalDB no s'ha iniciat correctament");
addS ("caracal_stop_success", "S'ha aturat CaracalDB");
addS ("caracal_stop_fail", "CaracalDB no s'ha aturat correctament");

addS ("caracal_saved_config", "El fitxer de configuració s'ha actualitzat");

// Text
addS ("caracal_title","CaracalDB");
addS ("caracal_subtitle","Un emmagatzematge distribuït de claus-valors");
addS ("caracal_description",'<a href="https://github.com/CaracalDB">CaracalDB</a> és un emmagatzematge consistent, escalable, flexible i distribuït de claus-valors escrit en Java emprant l\'entorn de components <a href="https://github.com/kompics/kompics">Kompics</a>. Podeu llegir més sobre <a href="https://github.com/CaracalDB/CaracalDB">CaracalDB a Github</a>.');
addS ("caracal_status", "Estat de CaracalDB:");
addS ("caracal_config_file", "Configuració de CaracalDB");
addS ("caracal_config_edit", "Editar la configuració de CaracalDB");
addS ("caracal_config_path", "Fitxer de configuració actual: ");
addS ("caracal_log", "Registres");
addS ("caracal_log_path", "Fitxer de registres actual: ");

addS ("caracal_scroll_bottom", "Desplaça't avall fins al final del registre");

// Buttons
addS ("caracal_button_install", "Instal·la CaracalDB");
addS ("caracal_button_uninstall", "Desinstal·la CaracalDB");

addS ("caracal_button_start", "Inicia CaracalDB");
addS ("caracal_button_stop", "Atura CaracalDB");

addS ("caracal_button_save", "Desa la configuración");

addS ("caracal_button_back", "Torna a CaracalDB");
addS ("caracal_button_reload", "Recarrega la pàgina");
addS ("caracal_button_log", "Mostra els registres");

// Form
addS ("caracal_form_bsip", "IP d'arrencada");
addS ("caracal_form_bsip_help", "L'adreça IP del servidor d'arrencada. El valor per defecte a Gufi.net és 10.228.207.42.");
addS ("caracal_form_bsport", "Port d'arrencada");
addS ("caracal_form_bsport_help", "El port al qual el servidor d'arrencada està escoltant les connexions entrants. El valor per defecte és 45678.");
addS ("caracal_form_localip", "IP servidor CaracalDB");
addS ("caracal_form_localip_help", "L'adreça IP del servidor on CaracalDB s'executarà. El valor per defecte és l'adreça IP d'aquesta instància de Cloudy.");
addS ("caracal_form_localport", "Port servidor CaracalDB");
addS ("caracal_form_localport_help", "El port al qual el servidor de CaracalDB estarà escoltant les connexions entrants. El valor per defecte és 45678.");

?>