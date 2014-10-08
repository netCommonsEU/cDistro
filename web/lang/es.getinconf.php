<?php
// plug/controllers/getinconf.php

//Getinconf client

addS ("getinconf_title","Cliente de Getinconf para microclouds");
addS ("getinconf_subtitle","Un cliente de Getinconf para configurar una red tinc-vpn para microclouds");
addS ("getinconf_subtitle_interface_status","Estado de la interfaz de red VPN");
addS ("getinconf_description",'Getinconf es una herramienta que automatiza la configuración de redes VPN basadas en <a href="http://www.tinc-vpn.org">tinc-vpn</a>. El cliente de Getinconf se conecta al servidor y recibe la configuración de la VPN y las claves necesarias para conectarse a los otros nodos del microcloud, creando una red virtual de microcloud de capa 2 segura.');
addS ("getinconf_form_server_url","URL del servidor Getinconf");
addS ("getinconf_form_server_url_help","La dirección URL del servidor Getinconf. La URL por defecto en Guifi.net es <strong>http://10.139.40.84/index.php</strong>");
addS ("getinconf_form_microcloud_network","Red del microcloud");
addS ("getinconf_form_microcloud_network_help","El nombre de la red del microcloud al que pertenece este nodo. El valor por defecto, <strong>demo</strong>, puede usarse para pruebas");
addS ("getinconf_form_network_password","Contraseña de la red");
addS ("getinconf_form_network_password_help","La contraseña de la red del microcloud. La contraseña de la red del microcloud <strong>demo</strong> es <strong>demo</strong>");
addS ("getinconf_form_community_network_device","Dispositivo de red");
addS ("getinconf_form_community_network_device_help","El dispositivo de red de este nodo que está conectado a la Red Comunitaria (los valors típicos son <strong>eth0</strong> o eth1). La interfaz debe tener una dirección IP válida dentro de la Red Comunitaria");
addS ("getinconf_settings", "Configuración del cliente de Getinconf:");
addS ("getinconf_tinc_status", "Estado del cliente de Getinconf:");
addS ("getinconf_tinc_status_running","El cliente de Getinconf está en marcha");
addS ("getinconf_tinc_status_stopped","El cliente de Getinconf está parado");
addS ("getinconf_button_save","Guardar la configuració");
addS ("getinconf_button_stop","Parar el cliente de Getinconf");
addS ("getinconf_button_start","Arrancar el cliente de Getinconf");
addS ("getinconf_button_uninstall","Desinstalar el cliente de Getinconf");
addS ("getinconf_button_interface","Mostrar el estado de la interfaz de VPN");
addS ("getinconf_button_back","Volver al client de Getinconf");
addS ("getinconf_interface_command_output_pre","Salida del comando");
addS ("getinconf_interface_command_output_post"," :");
addS ("getinconf_alert_stopping","El cliente de Getinconf está arrancando...");
addS ("getinconf_alert_starting","Se está parando el cliente de Getinconf...");
addS ("getinconf_alert_uninstall","El cliente de Getinconf se ha desinstalado");
addS ("getinconf_alert_saved","La configuración del cliente de Getinconf ha sido guardada");
addS ("getinconf_click_button_back", "Haga clic en el botón para volver al cliente de Getinconf");
?>