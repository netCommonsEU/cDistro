<?php
// plug/lang/*.menu.php

//CaracalDB API

// Alerts
addS ("caracalapi_java_installed_false", "Java Runtime Environment no está instalado");
addS ("caracalapi_java_installed_true", "Java Runtime Environment está instalado");

addS ("caracalapi_caracalapi_installed_false", "La API REST de CaracalDB no está instalada");
addS ("caracalapi_caracalapi_installed_true", "La API REST de CaracalDB está instalada");

addS ("caracalapi_java_install_fail", "La instalación de Java ha fallado");
addS ("caracalapi_download_fail", "La descarga de los ficheros binarios de la API REST de CaracalDB ha fallado");
addS ("caracalapi_dependeny_fail", "Ha fallado la instalación de las dependencias");
addS ("caracalapi_install_success", "Se ha instalado la API REST de CaracalDB");
addS ("caracalapi_install_fail", "La instalación de la API REST de CaracalDB ha fallado");
addS ("caracalapi_uninstall_success", "La API REST de CaracalDB se ha desinstalado correctamente");
addS ("caracalapi_uninstall_fail", "La desinstalación de la API REST de CaracalDB ha fallado");
addS ("caracalapi_running_true", "La API REST de CaracalDB está ejecutándose");
addS ("caracalapi_running_false", "La API REST de CaracalDB está parada");
addS ("caracalapi_start_success", "La API REST de CaracalDB se ha iniciado correctamente");
addS ("caracalapi_start_fail", "El arranque de la API REST de CaracalDB ha fallado");
addS ("caracalapi_stop_success", "Se ha parado la API REST de CaracalDB");
addS ("caracalapi_stop_fail", "La parada de la API REST de CaracalDB ha fallado");

addS ("caracalapi_saved_config", "Se ha actualizado el fichero de configuración");

// Text
addS ("caracalapi_title","API REST de CaracalDB");
addS ("caracalapi_subtitle","Una API REST y una IU web para el almacén de claves-valores distribuido CaracalDB");
addS ("caracalapi_description",'La API de CaracalDB es una API REST basada en JSON para acceder a CaracalDB desde servicios externos. El paquete incluye también una web sencilla que sirve de interfaz de usuario para la API. Para más información puede leer sobre <a href="https://github.com/CaracalDB/CaracalDB">CaracalDB en Github</a>.');
addS ("caracalapi_status", "Estado de la API de CaracalDB:");
addS ("caracalapi_config_file", "Configuración");
addS ("caracalapi_config_edit", "Editar la configuración de la API de CaracalDB");
addS ("caracalapi_config_path", "Fichero de configuración actual: ");
addS ("caracalapi_log", "Registros");
addS ("caracalapi_log_path", "Fichero de registros actual: ");

addS ("caracalapi_scroll_down", "Desplazarse hacia abajo hasta el final del registro");

// Buttons
addS ("caracalapi_button_install", "Instalar la API de CaracalDB");
addS ("caracalapi_button_uninstall", "Desinstalar la API de CaracalDB");

addS ("caracalapi_button_start", "Iniciar la API de CaracalDB");
addS ("caracalapi_button_stop", "Parar la API de CaracalDB");

addS ("caracalapi_button_save", "Guardar la configuració");

addS ("caracalapi_button_status", "Volver a la API de CaracalDB");
addS ("caracalapi_button_reload", "Recargar la página");
addS ("caracalapi_button_log", "Mostrar los registros");
addS ("caracalapi_button_ui", "Interfaz web de la API de CaracalDB");

// Form
addS ("caracalapi_form_bsip", "IP de arranque");
addS ("caracalapi_form_bsip_help", "La dirección IP del servidor de arranque. El valor por defecto en Gufi.net es 10.228.207.42.");
addS ("caracalapi_form_bsport", "Puerto de arranque");
addS ("caracalapi_form_bsport_help", "El port en el que el servidor de arranque está atendiendo las conexiones entrantes. El valor por defecto es 45678.");
addS ("caracalapi_form_localip", "IP servidor CaracalDB");
addS ("caracalapi_form_localip_help", "La dirección IP del servidor de CaracalDB. El valor por defecto es la dirección IP de esta instancia de Cloudy.");
addS ("caracalapi_form_localport", "Puerto servidor CaracalDB");
addS ("caracalapi_form_localport_help", "El puerto en el que el servidor de CaracalDB está atendiendo las conexiones entrants. El valor por defecto es 45678.");
addS ("caracalapi_form_webaddr", "Nombre de máquina API CaracalDB");
addS ("caracalapi_form_webaddr_help", "El nombre de máquina del servidor donde la API de CaracalDB se ejecutará. El valor por defecto es localhost.");
addS ("caracalapi_form_webport", "Puerto web API CaracalDB");
addS ("caracalapi_form_webport_help", "El puerto en que la interfaz web de la API de CaracalDB API estará atendiendo las conexiones entrantes. El valor por defecto es 8088.");

?>