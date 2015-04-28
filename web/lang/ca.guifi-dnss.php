<?php
// plug/controllers/guifi-dnss.php

//Common
addS ("guifi-dnss_common_appname","Guifi DNSServices");
addS ("guifi-dnss_common_desc","Sistema de configuració automàtica de servidors DNS per a nodes de Guifi.net");
addS ("guifi-dnss_common_status_pre","Estat de ");
addS ("guifi-dnss_common_status_post",":");
addS ("guifi-dnss_common_guifi:","Integració amb el web de Guifi.net:");
addS ("guifi-dnss_alert_running_pre","");
addS ("guifi-dnss_alert_running_post"," està en marxa");
addS ("guifi-dnss_alert_stopped_pre","");
addS ("guifi-dnss_alert_stopped_post"," està aturat");

//Alerts
addS ("guifi-dnss_alert_installed_pre","");
addS ("guifi-dnss_alert_installed_post"," està instal·lat");
addS ("guifi-dnss_alert_not_installed_pre","");
addS ("guifi-dnss_alert_not_installed_post"," no està instal·lat");
addS ("guifi-dnss_alert_guifi","Aquest dispositiu Cloudy està registrat al web de Guifi.net");
addS ("guifi-dnss_alert_not_guifi","Aquest dispositiu Cloudy no està registrat al web de Guifi.net");
addS ("guifi-dnss_alert_save","Desant la configuració...");

//Buttons
addS ("guifi-dnss_button_create_service","Crea el servei DNSServices al web de Guifi.net");
addS ("guifi-dnss_button_register","Registra aquest dispositiu Cloudy al web de Guifi.net");
addS ("guifi-dnss_button_back","Enrere");
addS ("guifi-dnss_button_install_pre","Instal·la ");
addS ("guifi-dnss_button_install_post","");
addS ("guifi-dnss_button_sinstall_pre","Desa la configuració i instal·la ");
addS ("guifi-dnss_button_sinstall_post","");
addS ("guifi-dnss_button_save","Desa i aplica la configuració");
addS ("guifi-dnss_button_configure_pre","Configura ");
addS ("guifi-dnss_button_configure_post","");
addS ("guifi-dnss_button_uninstall_pre","Desinstal·la ");
addS ("guifi-dnss_button_uninstall_post","");
addS ("guifi-dnss_button_stop_pre","Atura ");
addS ("guifi-dnss_button_stop_post","");
addS ("guifi-dnss_button_start_pre","Arrenca ");
addS ("guifi-dnss_button_start_post","");
addS ("guifi-dnss_button_unregistered_pre","Instal·la ");
addS ("guifi-dnss_button_unregistered_post"," sense registrar aquest dispositiu");
addS ("guifi-dnss_button_unregistereds_pre","Install ");
addS ("guifi-dnss_button_unregistereds_post"," sense declarar-lo al web");

//Index
addS ("guifi-dnss_index_desc","DNSServices és un sistema de configuració automàtica de servidors DNS per a nodes de Guifi.net. Proporciona resolució d'adreces tant per a noms de dominis creats pels usuaris al web de Guifi.net (incloent cerca DNS inversa) com per a noms de domini d'Internet.");
addS ("guifi-dnss_index_connected","Per instal·lar aquest servei, el dispositiu ha d'estar connectat tant a Guifi com a Internet.");
addS ("guifi-dnss_index_checkwiki","Per a més informació, podeu consultar aquesta pàgina wiki: ");
addS ("guifi-dnss_index_wikiurl","http://ca.wiki.guifi.net/wiki/Servidor_DNS");
addS ("guifi-dnss_index_not_guifi","Aquest dispositiu Cloudy no està vinculat amb cap dispositiu al web de Guifi.net.");
addS ("guifi-dnss_index_register_before_pre","Feu clic al botó de sota per registrar-lo abans d'instal·lar el servei ");
addS ("guifi-dnss_index_register_before_post",".");
addS ("guifi-dnss_index_register","Feu clic al botó de sota per registrar-lo.");
addS ("guifi-dnss_index_guifi","Feu clic al botó de sota per instal·lar el servei Guifi DNSServices.");

//Install
addS ("guifi-dnss_install_subtitle","Instal·lació i configuració");
addS ("guifi-dnss_install_declare","Per instal·lar aquest servei heu de declarar-lo al web de Guifi.net per obtenir un identificador (ID) de servei.");
addS ("guifi-dnss_install_autodeclare","Feu clic al botó de sota per crear el servei automàticament al web de Guifi.net.");
addS ("guifi-dnss_install_otherwise","Altrament, ompliu el formulari i feu clic al botó per instal·lar el servei sense declarar-lo.");
addS ("guifi-dnss_install_declared_pre","Per instal·lar ");
addS ("guifi-dnss_install_declared_post",", ompliu el formulari i feu clic al botó de sota.");
addS ("guifi-dnss_install_configure_pre","Empreu aquesta pàgina per canviar la configuració de ");
addS ("guifi-dnss_install_configure_post"," o per desinstal·lar-lo.");
addS ("guifi-dnss_install_value","L'identificador (ID) de servei s'ha autocompletat amb la informació recollida del web de Guifi.net.");

//Form
addS ("guifi-dnss_form_id","ID del servei");
addS ("guifi-dnss_form_id_tooltip","L'identificador (ID) del servei al web de Guifi.net (ex.: http://guifi.net/node/<strong>123456</strong>)");
addS ("guifi-dnss_form_url","URL dades DNS");
addS ("guifi-dnss_form_url_tooltip","La URL del servidor des d'on sincronitzar la base de dades de DNS (per defecte <strong>http://guifi.net</strong>). No afegiu la barra al final (/).");

?>