<?php
// plug/controllers/guifi-dnss.php

//Common
addS ("guifi-dnss_common_appname","Guifi DNSServices");
addS ("guifi-dnss_common_desc","Sistema de configuración automática de servidores DNS para nodos de Guifi.net");
addS ("guifi-dnss_common_status_pre","Estado de ");
addS ("guifi-dnss_common_status_post",":");
addS ("guifi-dnss_common_guifi:","Integración con la web de Guifi.net:");
addS ("guifi-dnss_alert_running_pre","");
addS ("guifi-dnss_alert_running_post"," está en marcha");
addS ("guifi-dnss_alert_stopped_pre","");
addS ("guifi-dnss_alert_stopped_post"," está parado");

//Alerts
addS ("guifi-dnss_alert_installed_pre","");
addS ("guifi-dnss_alert_installed_post"," está instalado");
addS ("guifi-dnss_alert_not_installed_pre","");
addS ("guifi-dnss_alert_not_installed_post"," no está instalado");
addS ("guifi-dnss_alert_guifi","Este dispositivo Cloudy está registrado en la web de Guifi.net");
addS ("guifi-dnss_alert_not_guifi","Este dispositivo Cloudy no está registrado en la web de Guifi.net");
addS ("guifi-dnss_alert_save","Guardando la configuración...");

//Buttons
addS ("guifi-dnss_button_create_service","Crear el servicio DNSServices en la web de Guifi.net");
addS ("guifi-dnss_button_register","Registrar este dispositivo Cloudy en la web de Guifi.net");
addS ("guifi-dnss_button_back","Volver");
addS ("guifi-dnss_button_install_pre","Instalar ");
addS ("guifi-dnss_button_install_post","");
addS ("guifi-dnss_button_sinstall_pre","Guardar la configuración e instalar ");
addS ("guifi-dnss_button_sinstall_post","");
addS ("guifi-dnss_button_save","Guardar y aplicar la configuración");
addS ("guifi-dnss_button_configure_pre","Configurar ");
addS ("guifi-dnss_button_configure_post","");
addS ("guifi-dnss_button_uninstall_pre","Desinstalar ");
addS ("guifi-dnss_button_uninstall_post","");
addS ("guifi-dnss_button_stop_pre","Parar ");
addS ("guifi-dnss_button_stop_post","");
addS ("guifi-dnss_button_start_pre","Arrancar ");
addS ("guifi-dnss_button_start_post","");
addS ("guifi-dnss_button_unregistered_pre","Instalar ");
addS ("guifi-dnss_button_unregistered_post"," sin registrar este dispositivo");
addS ("guifi-dnss_button_unregistereds_pre","Instalar ");
addS ("guifi-dnss_button_unregistereds_post"," sin declararlo en la web");

//Index
addS ("guifi-dnss_index_desc","DNSServices es un sistema de configuración automática de servidores DNS para nodos de Guifi.net. Proporciona resolución de direcciones tanto para nombres de dominio creados por los usuarios en la web de Guifi.net Guifi.net (incluyendo búsqueda DNS inversa) como para nombres de dominio de Internet.");
addS ("guifi-dnss_index_connected","Para instalar este servicio, el dispositivo debe estar conectado tanto a Guifi como a Internet.");
addS ("guifi-dnss_index_checkwiki","Para más información, puede consultar esta página wiki: ");
addS ("guifi-dnss_index_wikiurl","http://es.wiki.guifi.net/wiki/Servidor_DNS");
addS ("guifi-dnss_index_not_guifi","Este dispositivo Cloudy no está vinculado con ningún dispositivo en la web  de Guifi.net.");
addS ("guifi-dnss_index_register_before_pre","Haga clic en el botón a continuación para registrarlo antes de instalar el servicio ");
addS ("guifi-dnss_index_register_before_post",".");
addS ("guifi-dnss_index_register","Haga clic en el botón a continuación para registrarlo.");
addS ("guifi-dnss_index_guifi","Haga clic en el botón a continuación para instalar el servicio Guifi DNSServices.");

//Install
addS ("guifi-dnss_install_subtitle","Instalación y configuración");
addS ("guifi-dnss_install_declare","Para instalar este servicio debe declararlo en la web de Guifi.net para obtener un identificador (ID) de servicio.");
addS ("guifi-dnss_install_autodeclare","Haga clic en el botón a continuación para crear el servicio automáticamente en la web de Guifi.net.");
addS ("guifi-dnss_install_otherwise","Alternativamente, rellene el formulari y haga clic en el botón a continuación para instalar el servicio sin declararlo.");
addS ("guifi-dnss_install_declared_pre","Para instalar ");
addS ("guifi-dnss_install_declared_post",", rellene el formulario y haga clic en el botón a continuación.");
addS ("guifi-dnss_install_configure_pre","Use esta página para cambiar la configuración de  ");
addS ("guifi-dnss_install_configure_post"," o para desinstalarlo.");
addS ("guifi-dnss_install_value","El identificador (ID) del servicio se ha autocompletado con la información recopilada de la web de Guifi.net.");

//Form
addS ("guifi-dnss_form_id","ID del servicio");
addS ("guifi-dnss_form_id_tooltip","El identificador (ID) del servicio en la web de Guifi.net (ej.: http://guifi.net/node/<strong>123456</strong>)");
addS ("guifi-dnss_form_url","URL datos DNS");
addS ("guifi-dnss_form_url_tooltip","La URL del servidor desde el que sincronitzar la base de datos de DNS (por defecte <strong>http://guifi.net</strong>). No añada la barra al final (/).");

?>