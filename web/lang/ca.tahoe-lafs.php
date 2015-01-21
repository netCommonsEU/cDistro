<?php
// plug/controllers/tahoe-lafs.php

//alerts

addS ("tahoe-lafs_alert_checking_introducer","S'està comprovant l'estat de l'introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_checking_storage","S'està comprovant l'estat del node d'emmagatzematge de Tahoe-LAFS");
addS ("tahoe-lafs_alert_installed_already","Tahoe-LAFS ja està instal·lat");
addS ("tahoe-lafs_alert_installed_empty","Tahoe-LAFS està instal·lat però encara no s'ha creat cap introducer ni cap node d'emmagatzematge");
addS ("tahoe-lafs_alert_installed_failed","La instal·lació de Tahoe-LAFS ha fallat");
addS ("tahoe-lafs_alert_installed_successful","Tahoe-LAFS s'ha instal·lat correctament");
addS ("tahoe-lafs_alert_introducer_configured","Hi ha un introducer de Tahoe-LAFS configurat");
addS ("tahoe-lafs_alert_introducer_deletion_failed","No s'ha pogut esborrar l'introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_deletion_successful","L'introducer de Tahoe-LAFS s'ha esborrat correctament");
addS ("tahoe-lafs_alert_introducer_failed","Ha fallat la creació de l'introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_invalid_grid","El nom de la xarxa d'emmagatzematge és invàlid");
addS ("tahoe-lafs_alert_introducer_invalid_name","El nom de l'introducer és invàlid");
addS ("tahoe-lafs_alert_introducer_invalid_port","El port web de l'introducer és invàlid");
addS ("tahoe-lafs_alert_introducer_maximum","Als noms es permet un màxim de 80 caràcters alfanumèrics, guionets i guions baixos");
addS ("tahoe-lafs_alert_introducer_not_created","Encara no s'ha creat l'introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_running","L'introducer de Tahoe-LAFS està en marxa");
addS ("tahoe-lafs_alert_introducer_started","L'introducer de Tahoe-LAFS s'ha arrencat correctament");
addS ("tahoe-lafs_alert_introducer_start_fail","No s'ha pogut arrencar l'introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_stopped","L'introducer de Tahoe-LAFS està aturat");
addS ("tahoe-lafs_alert_introducer_stop_uninstall","Atureu-lo i esborreu-lo abans de desinstal·lar Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_successful","L'introducer de Tahoe-LAFS s'ha creat correctament");
addS ("tahoe-lafs_alert_not_installed","Tahoe-LAFS no està instal·lat");
addS ("tahoe-lafs_alert_request_incorrect","S'han rebut uns paràmetres incorrectes");
addS ("tahoe-lafs_alert_storage_configured","Hi ha un node d'emmagatzematge de Tahoe-LAFS configurat");
addS ("tahoe-lafs_alert_storage_deletion_failed","No s'ha pogut esborrar el node d'emmagatzematge de Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_deletion_successful","El node d'emmagatzematge de Tahoe-LAFS s'ha esborrat correctament");
addS ("tahoe-lafs_alert_storage_failed","La creació del node d'emmagatzematge de Tahoe-LAFS ha fallat");
addS ("tahoe-lafs_alert_storage_invalid_FURL","La FURL és invàlida");
addS ("tahoe-lafs_alert_storage_invalid_name","El nom del node d'emmagatzematge és invàlid");
addS ("tahoe-lafs_alert_storage_maximum","Als noms es permet un màxim de 80 caràcters alfanumèrics, guionets i guions baixos");
addS ("tahoe-lafs_alert_storage_not_created","Encara no s'ha creat el node d'emmagatzematge de Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_running","El node d'emmagatzematge de Tahoe-LAFS està en marxa");
addS ("tahoe-lafs_alert_storage_started","El node d'emmagatzematge de Tahoe-LAFS s'ha arrencat correctament");
addS ("tahoe-lafs_alert_storage_start_fail","No s'ha pogut arrencar el node d'emmagatzematge de Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_stopped","El node d'emmagatzematge de Tahoe-LAFS està aturat");
addS ("tahoe-lafs_alert_storage_stop_uninstall","Atureu-lo i esborreu-lo abans de desintal·lar Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_successful","El node d'emmagatzematge de Tahoe-LAFS s'ha creat correctament");
addS ("tahoe-lafs_alert_uninstalled_already","Tahoe-LAFS ja està desinstal·lat");
addS ("tahoe-lafs_alert_uninstalled_failed","La instal·lació de Tahoe-LAFS ha fallat");
addS ("tahoe-lafs_alert_uninstalled_successful","Tahoe-LAFS s'ha desinstal·lat correctament");

//buttons
addS ("tahoe-lafs_button_back","Torna a Tahoe-LAFS");
addS ("tahoe-lafs_button_create_introducer","Crea un introducer");
addS ("tahoe-lafs_button_create_introducer_start_grid","Crea un introducer i comença una xarxa d'emmagatzematge");
addS ("tahoe-lafs_button_create_storage","Crea un node d'emmagatzematge");
addS ("tahoe-lafs_button_create_storage_join_grid","Crea un node d'emmagatzematge i uneix-te a una xarxa");
addS ("tahoe-lafs_button_delete_introducer","Esborra l'introducer");
addS ("tahoe-lafs_button_delete_storage","Esborra el node d'emmagatzematge");
addS ("tahoe-lafs_button_install","Instal·la Tahoe-LAFS");
addS ("tahoe-lafs_button_introducer_private","Fes l'introducer privat");
addS ("tahoe-lafs_button_introducer_public","Fes l'introducer públic");
addS ("tahoe-lafs_button_introducer_retry","Torna-ho a provar");
addS ("tahoe-lafs_button_manage_introducer","Administra l'introducer");
addS ("tahoe-lafs_button_manage_storage","Administra el node d'emmagatzematge");
addS ("tahoe-lafs_button_retry_install","Torna-ho a provar");
addS ("tahoe-lafs_button_retry_uninstall","Torna-ho a provar");
addS ("tahoe-lafs_button_start_introducer","Arrenca l'introducer");
addS ("tahoe-lafs_button_start_storage","Arrenca el node d'emmagatzematge");
addS ("tahoe-lafs_button_stop_introducer","Atura l'introducer");
addS ("tahoe-lafs_button_stop_storage","Atura el node d'emmagatzematge");
addS ("tahoe-lafs_button_storage_retry","Torna-ho a provar");
addS ("tahoe-lafs_button_uninstall","Desinstal·la Tahoe-LAFS");

//common
addS ("tahoe-lafs_common_empty","buit");
addS ("tahoe-lafs_common_status:","Estat de Tahoe-LAFS:");
addS ("tahoe-lafs_common_title","Tahoe-LAFS");

//createIntroducer
addS ("tahoe-lafs_createintroducer_folder","Carpeta");
addS ("tahoe-lafs_createintroducer_folder_tooltip","L'introducer s'instal·larà en aquesta carpeta.");
addS ("tahoe-lafs_createintroducer_grid_name_example","Xarxa-Exemple-");
addS ("tahoe-lafs_createintroducer_grid_name","Nom de la xarxa");
addS ("tahoe-lafs_createintroducer_grid_name_tooltip","Un nom curt per identificar la xarxa d'emmagatzematge.");
addS ("tahoe-lafs_createintroducer_instructions_1","Per desplegar una xarxa d'emmagatzematge amb Tahoe-LAFS cal un <strong>introducer</strong> i múltiples <strong>nodes d'emmagatzematge</strong> distribuïts per la xarxa.");
addS ("tahoe-lafs_createintroducer_instructions_2","Feu servir aquesta pàgina per configurar un introducer en aquesta màquina i iniciar una xarxa d'emmagatzematge.");
addS ("tahoe-lafs_createintroducer_instructions_3","Un cop configurat, els nodes d'emmagatzematge podran unir-se a l'introducer per desplegar una xarxa d'emmagatzematge.");
addS ("tahoe-lafs_createintroducer_introducer_name","Nom de l'introducer");
addS ("tahoe-lafs_createintroducer_introducer_name_example","ElMeuIntroducer");
addS ("tahoe-lafs_createintroducer_introducer_name_tooltip","Un nom curt per identificar l'introducer a la xarxa d'emmagatzematge.");
addS ("tahoe-lafs_createintroducer_public","Públic");
addS ("tahoe-lafs_createintroducer_public_tooltip","Anuncia el servei d'introducer a la comunitat i permet que els nodes d'emmagatzematge puguin unir-se a la xarxa d'emmagatzematge.");
addS ("tahoe-lafs_createintroducer_result","Resultat de la creació de l'introducer:");
addS ("tahoe-lafs_createintroducer_starting","Arrencada de l'introducer de Tahoe-LAFS:");
addS ("tahoe-lafs_createintroducer_subtitle","Creació d'un introducer");
addS ("tahoe-lafs_createintroducer_web_port_tooltip","El port on s'executarà la interfície web d'administració de l'introducer.");
addS ("tahoe-lafs_createintroducer_web_port","Port web");

//createNode_get
addS ("tahoe-lafs_createnode_folder","Carpeta");
addS ("tahoe-lafs_createnode_folder_tooltip","La ruta d'instal·lació del node d'emmagatzematge.");
addS ("tahoe-lafs_createnode_FURL","FURL de l'introducer");
addS ("tahoe-lafs_createnode_FURL_tooltip_1","La FURL de l'introducer al qual volgueu unir el node d'emmagatzematge.");
addS ("tahoe-lafs_createnode_FURL_tooltip_2","Aquest valor s'ha obtingut de la informació que publica l'introducer a la comunitat.");
addS ("tahoe-lafs_createnode_FURL_tooltip_3","Si voleu modificar aquest valor, dirigiu-vos a la pàgina principal de Tahoe-LAFS i creeu manualment un node d'emmagatzematge.");
addS ("tahoe-lafs_createnode_FURL_tooltip_4","El valor per defecte s'ha obtingut de l'introducer que hi ha configurat en aquesta màquina.");
addS ("tahoe-lafs_createnode_FURL_tooltip_5","Si voleu unir-vos a un altre introducer, modifiqueu aquest camp en conseqüència.");
addS ("tahoe-lafs_createnode_instructions_1","Per desplegar una xarxa d'emmagatzematge amb Tahoe-LAFS cal un <strong>introducer</strong> i múltiples <strong>nodes d'emmagatzematge</strong> distribuïts per la xarxa.");
addS ("tahoe-lafs_createnode_instructions_2","Feu servir aquesta pàgina per configurar un node d'emmagatzematge i unir-vos a una xarxa d'emmagatzematge.");
addS ("tahoe-lafs_createnode_name_example","ElMeuNode");
addS ("tahoe-lafs_createnode_name","Nom del node d'emmagatzematge");
addS ("tahoe-lafs_createnode_name_tooltip","Un nom curt per identificar el node d'emmagatzematge a la xarxa d'emmagatzematge.");
addS ("tahoe-lafs_createnode_result","Resultat del procés de creació del node d'emmagatzematge:");
addS ("tahoe-lafs_createnode_starting","Arrencada del node d'emmagatzematge de Tahoe-LAFS:");
addS ("tahoe-lafs_createnode_subtitle","Creació d'un node d'emmagatzematge");

//deleteIntroducer
addS ("tahoe-lafs_deleteintroducer_result","Resultat del procés d'esborrat de l'introducer:");
addS ("tahoe-lafs_deleteintroducer_subtitle","Esborrat de l'introducer");

//deleteNode
addS ("tahoe-lafs_deletenode_result","Resultat del procés d'esborrat del node d'emmagatzematge:");
addS ("tahoe-lafs_deletenode_subtitle","Esborrat del node d'emmagatzematge");

//flash
addS ("tahoe-lafs_flash_publishing_introducer","S'està publicant l'introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_restarting_introducer","S'està reiniciant l'introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_restarting_storage","S'està reiniciant el node d'emmagatzematge de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_starting_introducer","S'està arrencant l'introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_starting_storage","S'està arrencant el node d'emmagatzematge de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_stopping_introducer","S'està aturant l'introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_stopping_storage","S'està aturant el node d'emmagatzematge de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_unpublishing_introducer","Despublicant l'introducer de Tahoe-LAFS...");

//index
addS ("tahoe-lafs_index_description1","Tahoe-LAFS és un sistema d'emmagatzematge al núvol lliure i obert.");
addS ("tahoe-lafs_index_description2","Funciona distribuint les vostres dades per múltiples servidors.");
addS ("tahoe-lafs_index_description3","Encara que algun dels servidors falli o sigui pres per un atacant, el sistema de fitxers sencer continua funcionant correctament, preservant la vostra privacitat i seguretat.");
addS ("tahoe-lafs_index_instructions_1","Per desplegar una xarxa d'emmagatzematge amb Tahoe-LAFS necessiteu un <strong>introducer</strong> i múltiples <strong>nodes d'emmagatzematge</strong> distribuïts per la xarxa.");
addS ("tahoe-lafs_index_instructions_2","Feu clic al botó per instal·lar Tahoe-LAFS i començar a crear una xarxa d'emmagatzematge o per unir-vos a una de ja existent.");
addS ("tahoe-lafs_index_instructions_3","Feu clic als botons per començar a crear una xarxa d'emmagatzematge o per unir-vos a una de ja existent.");
addS ("tahoe-lafs_index_subtitle","Un sistema d'emmagatzematge al núvol que distribueix les vostres dades per múltiples servidors");

//install
addS ("tahoe-lafs_install_details","Detalls del procés d'instal·lació:");
addS ("tahoe-lafs_install_post","Detalls del procés de post-instal·lació:");
addS ("tahoe-lafs_install_result","Resultat del procés d'instal·lació:");
addS ("tahoe-lafs_install_subtitle","Instal·lació");

//introducer
addS ("tahoe-lafs_introducer_announcement","Anunci del servei:");
addS ("tahoe-lafs_introducer_FURL","FURL de l'introducer:");
addS ("tahoe-lafs_introducer_grid","Nom de la xarxa d'emmagatzematge:");
addS ("tahoe-lafs_introducer_instructions_1","Per desplegar una xarxa d'emmagatzematge amb Tahoe-LAFS necessiteu un <strong>introducer</strong> i múltiples <strong>nodes d'emmagatzematge</strong> distribuïts per la xarxa.");
addS ("tahoe-lafs_introducer_instructions_2","Feu clic al botó per configurar un introducer en aquesta màquina.");
addS ("tahoe-lafs_introducer_instructions_3","Un cop fet, els nodes d'emmagatzematge podran unir-se a l'introducer per desplegar la xarxa d'emmagatzematge.");
addS ("tahoe-lafs_introducer_private","La xarxa d'emmagatzematge és privada");
addS ("tahoe-lafs_introducer_public","La xarxa d'emmagatzematge és pública i s'està anunciant a la comunitat");
addS ("tahoe-lafs_introducer_status","Estat de l'introducer de Tahoe-LAFS:");
addS ("tahoe-lafs_introducer_subtitle","Introducer");
addS ("tahoe-lafs_introducer_web","Interfície web de l'introducer:");

//node
addS ("tahoe-lafs_node_FURL","Introducer FURL:");
addS ("tahoe-lafs_node_instructions_1","Per desplegar una xarxa d'emmagatzematge amb Tahoe-LAFS necessiteu un <strong>introducer</strong> i múltiples <strong>nodes d'emmagatzematge</strong> distribuïts per la xarxa.");
addS ("tahoe-lafs_node_instructions_2","Feu clic al botó per configurar un node d'emmagatzematge en aquesta màquina i unir-vos a una xarxa d'emmagatzematge.");
addS ("tahoe-lafs_node_status","Estat del node d'emmagatzematge de Tahoe-LAFS:");
addS ("tahoe-lafs_node_subtitle","Node d'emmagatzematge");
addS ("tahoe-lafs_node_web","Pàgina web del node d'emmagatzematge de Tahoe-LAFS (només accessible des de localhost):");

//uninstall
addS ("tahoe-lafs_uninstall_details","Detalls del procés de desinstal·lació:");
addS ("tahoe-lafs_uninstall_post","Detalls del procés de post-desinstal·lació:");
addS ("tahoe-lafs_uninstall_result","Resultat del procés de desinstal·lació:");
addS ("tahoe-lafs_uninstall_subtitle","Desinstal·lació");
?>