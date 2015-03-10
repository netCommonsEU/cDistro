<?php
// plug/lang/*.menu.php

//Caracal

//alerts
addS ("caracal_java_installed_false","Java Runtime Environment no está instalado");
addS ("caracal_java_installed_true","Java Runtime Environment está instalado");

addS ("caracal_caracal_installed_false", "CaracalDB Server no está instalado");
addS ("caracal_caracal_installed_true", "CaracalDB está instalado");

addS ("caracal_java_install_fail", "La instalación de Java ha fallado");
addS ("caracal_download_fail", "La descarga de los ficheros binarios de CaracalDB ha fallado");
addS ("caracal_install_success", "CaracalDB se ha instalado correctamente");
addS ("caracal_install_fail", "La instalación de CaracalDB ha fallado");
addS ("caracal_uninstall_success", "Se ha desinstalado CaracalDB");
addS ("caracal_uninstall_fail", "La desinstalación de CaracalDB ha fallado");
addS ("caracal_running_true", "CaracalDB está ejecutándose");
addS ("caracal_running_false", "CaracalDB está parado");
addS ("caracal_start_success", "Se ha iniciado CaracalDB");
addS ("caracal_start_fail", "CaracalDB no se ha iniciado correctamente");
addS ("caracal_stop_success", "Se ha parado CaracalDB");
addS ("caracal_stop_fail", "CaracalDB no se ha parado correctamente");

addS ("caracal_saved_config", "Configuration file has been updated");

// Text
addS ("caracal_title","CaracalDB");
addS ("caracal_subtitle","Un almacén distribuido de claves-valores");
addS ("caracal_description",'<a href="https://github.com/CaracalDB">CaracalDB</a> es un almacén consistente, escalable, flexible y distribuido de claves-valores escrito en Java usando el entorno de componentes <a href="https://github.com/kompics/kompics">Kompics</a>. Puede leer más acerca de <a href="https://github.com/CaracalDB/CaracalDB">CaracalDB en Github</a>.');
addS ("caracal_status", "Estado de CaracalDB:");
addS ("caracal_config_file", "Configuración de CaracalDB");
addS ("caracal_config_edit", "Editar la configuración de CaracalDB");
addS ("caracal_config_path", "Fichero de configuración actual: ");
addS ("caracal_log", "Registros");
addS ("caracal_log_path", "Fichero de registros actual: ");

addS ("caracal_scroll_bottom", "Desplazarse hacia abajo hasta el final del registro");

// Buttons
addS ("caracal_button_install", "Instalar CaracalDB");
addS ("caracal_button_uninstall", "Desinstalar CaracalDB");

addS ("caracal_button_start", "Iniciar CaracalDB");
addS ("caracal_button_stop", "Parar CaracalDB");

addS ("caracal_button_save", "Guardar la configuración");

addS ("caracal_button_back", "Volver a CaracalDB");
addS ("caracal_button_reload", "Recargar la página");
addS ("caracal_button_log", "Ver los registros");

// Form
addS ("caracal_form_bsip", "IP de arranque");
addS ("caracal_form_bsip_help", "La dirección IP del servidor de arranque. El valor por defecto en Gufi.net es 10.228.207.42.");
addS ("caracal_form_bsport", "Puerto de arranque");
addS ("caracal_form_bsport_help", "El puerto en el que el servidor de arranque está escuchando las conexiones entrantes. El valor por defecto es 45678.");
addS ("caracal_form_localip", "IP servidor CaracalDB");
addS ("caracal_form_localip_help", "La dirección IP del servidor donde CaracalDB se va a ejecutar. El valor por defecto es la dirección IP de esta instancia de Cloudy.");
addS ("caracal_form_localport", "Puerto servidor CaracalDB");
addS ("caracal_form_localport_help", "El puerto en el que el servidor de CaracalDB estará escuchando las conexiones entrantes. El valor por defecto es 45678.");

?>