<?php
// plug/controllers/tahoe-lafs.php

//alerts

addS ("tahoe-lafs_alert_checking_introducer","Comprobando el estado del introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_checking_storage","Comprobando el estado del nodo de almacenamiento de Tahoe-LAFS");
addS ("tahoe-lafs_alert_installed_already","Tahoe-LAFS ya está instalado");
addS ("tahoe-lafs_alert_installed_empty","Tahoe-LAFS está instalado pero todavía no se ha creado ningún introducer ni ningún nodo de almacenamiento");
addS ("tahoe-lafs_alert_installed_failed","La instalación de Tahoe-LAFS ha fallado");
addS ("tahoe-lafs_alert_installed_successful","Tahoe-LAFS se ha instalado correctamente");
addS ("tahoe-lafs_alert_introducer_configured","Hay un introducer de Tahoe-LAFS configurado");
addS ("tahoe-lafs_alert_introducer_deletion_failed","No se ha podido borrar el introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_deletion_successful","El introducer de Tahoe-LAFS se ha borrado correctamente");
addS ("tahoe-lafs_alert_introducer_failed","La creación del introducer de Tahoe-LAFS ha fallado");
addS ("tahoe-lafs_alert_introducer_invalid_grid","El nombre de la red de almacenamiento es inválido");
addS ("tahoe-lafs_alert_introducer_invalid_name","El nombre del introducer es inválido");
addS ("tahoe-lafs_alert_introducer_invalid_port","El puerto web del introducer es inválido");
addS ("tahoe-lafs_alert_introducer_maximum","En los nombres se permite un máximo de 80 caracteres alfanuméricos, guiones y guiones bajos");
addS ("tahoe-lafs_alert_introducer_not_created","Todavía no se ha creado el introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_running","El introducer de Tahoe-LAFS está ejecutándose");
addS ("tahoe-lafs_alert_introducer_started","El introducer de Tahor-LAFS se ha iniciado correctamente");
addS ("tahoe-lafs_alert_introducer_start_fail","No se ha podido iniciar el introducer de Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_stopped","El introducer de Tahoe-LAFS está parado");
addS ("tahoe-lafs_alert_introducer_stop_uninstall","Párelo y bórrelo antes de desinstalar Tahoe-LAFS");
addS ("tahoe-lafs_alert_introducer_successful","El introducer de Tahoe-LAFS se ha creado correctamente");
addS ("tahoe-lafs_alert_not_installed","Tahoe-LAFS no está instalado");
addS ("tahoe-lafs_alert_request_incorrect","Se han recibido unos parámetros incorrectos");
addS ("tahoe-lafs_alert_storage_configured","Hay un nodo de almacenamiento de Tahoe-LAFS configurado");
addS ("tahoe-lafs_alert_storage_deletion_failed","No se ha podido borrar el nodo de almacenamiento de Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_deletion_successful","El nodo de almacenamiento de Tahoe-LAFS se ha borrado correctamente");
addS ("tahoe-lafs_alert_storage_failed","La creación del nodo de almacenamiento de Tahoe-LAFS ha fallado");
addS ("tahoe-lafs_alert_storage_invalid_FURL","La FURL es inválida");
addS ("tahoe-lafs_alert_storage_invalid_name","El nombre del nodo de almacenamiento es inválido");
addS ("tahoe-lafs_alert_storage_maximum","En los nombres se permite un máximo de 80 caracteres alfanuméricos, guiones y guiones bajos");
addS ("tahoe-lafs_alert_storage_not_created","Todavía no se ha creado el nodo de almacenamiento de Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_running","El nodo de almacenamiento de Tahoe-LAFS está ejecutándose");
addS ("tahoe-lafs_alert_storage_started","El nodo de almacenamiento de Tahoe-LAFS se ha iniciado correctamente");
addS ("tahoe-lafs_alert_storage_start_fail","No se ha podido iniciar el nodo de almacenamiento de Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_stopped","El nodo de almacenamiento de Tahoe-LAFS está parado");
addS ("tahoe-lafs_alert_storage_stop_uninstall","Párelo y bórrelo antes de desinstalar Tahoe-LAFS");
addS ("tahoe-lafs_alert_storage_successful","El nodo de almacenamiento de Tahoe-LAFS se ha creado correctamente");
addS ("tahoe-lafs_alert_uninstalled_already","Tahoe-LAFS ya está desinstalado");
addS ("tahoe-lafs_alert_uninstalled_failed","La instalación de Tahoe-LAFS ha fallado");
addS ("tahoe-lafs_alert_uninstalled_successful","Tahoe-LAFS se ha desinstalado correctamente");

//buttons
addS ("tahoe-lafs_button_back","Volver a Tahoe-LAFS");
addS ("tahoe-lafs_button_create_introducer","Crear un introducer");
addS ("tahoe-lafs_button_create_introducer_start_grid","Crear un introducer y comenzar una red de almacenamiento");
addS ("tahoe-lafs_button_create_storage","Crear un nodo de almacenamiento");
addS ("tahoe-lafs_button_create_storage_join_grid","Crear un nodo de almacenamiento y unirse a una red");
addS ("tahoe-lafs_button_delete_introducer","Borrar el introducer");
addS ("tahoe-lafs_button_delete_storage","Borrar el nodo de almacenamiento");
addS ("tahoe-lafs_button_install","Instalar Tahoe-LAFS");
addS ("tahoe-lafs_button_introducer_private","Hacer el introducer privado");
addS ("tahoe-lafs_button_introducer_public","Hacer el introducer público");
addS ("tahoe-lafs_button_introducer_retry","Reintentar");
addS ("tahoe-lafs_button_manage_introducer","Administrar el introducer");
addS ("tahoe-lafs_button_manage_storage","Administrar el nodo de almacenamiento");
addS ("tahoe-lafs_button_retry_install","Reintentar");
addS ("tahoe-lafs_button_retry_uninstall","Reintentar");
addS ("tahoe-lafs_button_start_introducer","Iniciar el introducer");
addS ("tahoe-lafs_button_start_storage","Iniciar el nodo de almacenamiento");
addS ("tahoe-lafs_button_stop_introducer","Parar el introducer");
addS ("tahoe-lafs_button_stop_storage","Parar el nodo de almacenamiento");
addS ("tahoe-lafs_button_storage_retry","Reintentar");
addS ("tahoe-lafs_button_uninstall","Desinstalar Tahoe-LAFS");

//common
addS ("tahoe-lafs_common_empty","vacío");
addS ("tahoe-lafs_common_status:","Estado de Tahoe-LAFS:");
addS ("tahoe-lafs_common_title","Tahoe-LAFS");

//createIntroducer
addS ("tahoe-lafs_createintroducer_folder","Directorio");
addS ("tahoe-lafs_createintroducer_folder_tooltip","El introducer se instalará en este directorio.");
addS ("tahoe-lafs_createintroducer_grid_name_example","Red-Ejemplo-");
addS ("tahoe-lafs_createintroducer_grid_name","Nombre de la red");
addS ("tahoe-lafs_createintroducer_grid_name_tooltip","Un nombre corto para identificar la red de almacenamiento.");
addS ("tahoe-lafs_createintroducer_instructions_1","Para desplegar una red de almacenamiento con Tahoe-LAFS hace falta un <strong>introducer</strong> y múltiples <strong>nodos de almacenamiento</strong> distribuidos por la red.");
addS ("tahoe-lafs_createintroducer_instructions_2","Utilice esta página para configurar un introducer en esta máquina e iniciar una red de almacenamiento.");
addS ("tahoe-lafs_createintroducer_instructions_3","Una vez configurado, los nodos de almacenamiento podrán unirse al introducer para desplegar la red de almacenamiento.");
addS ("tahoe-lafs_createintroducer_introducer_name","Nombre del introducer");
addS ("tahoe-lafs_createintroducer_introducer_name_example","MiIntroducer");
addS ("tahoe-lafs_createintroducer_introducer_name_tooltip","Un nombre corto para identificar el introducer en la red de almacenamiento.");
addS ("tahoe-lafs_createintroducer_public","Público");
addS ("tahoe-lafs_createintroducer_public_tooltip","Anunciar el servicio de introducer a la comunidad y permitir que los nodos de almacenamiento puedan unirse a la red de almacenamiento.");
addS ("tahoe-lafs_createintroducer_result","Resultado de la creación del introducer:");
addS ("tahoe-lafs_createintroducer_starting","Inicio del introducer de Tahoe-LAFS:");
addS ("tahoe-lafs_createintroducer_subtitle","Creción de un introducer");
addS ("tahoe-lafs_createintroducer_web_port_tooltip","El puerto donde se ejecutará la interfaz web de administración del introducer.");
addS ("tahoe-lafs_createintroducer_web_port","Puerto web");

//createNode_get
addS ("tahoe-lafs_createnode_folder","Directorio");
addS ("tahoe-lafs_createnode_folder_tooltip","La ruta de instalación del nodo de almacenamiento.");
addS ("tahoe-lafs_createnode_FURL","FURL del introducer");
addS ("tahoe-lafs_createnode_FURL_tooltip_1","La FURL del introducer al que quiera unir el nodo de almacenamiento.");
addS ("tahoe-lafs_createnode_FURL_tooltip_2","Este valor se ha obtenido de la información que publica el introducer a la comunidad.");
addS ("tahoe-lafs_createnode_FURL_tooltip_3","Si quiere modificar este valor, diríjase a la página principal de Tahoe-LAFS y cree manualmente un nodo de almacenamiento.");
addS ("tahoe-lafs_createnode_FURL_tooltip_4","El valor por defecto se ha obtenido del introducer que hay configurado en esta máquina.");
addS ("tahoe-lafs_createnode_FURL_tooltip_5","Si quiere unirse a otro introducer, modifique este campo en consecuencia.");
addS ("tahoe-lafs_createnode_instructions_1","Para desplegar una red de almacenamiento con Tahoe-LAFS hace falta un <strong>introducer</strong> y múltiples <strong>nodos de almacenamiento</strong> distribuidos por la red.");
addS ("tahoe-lafs_createnode_instructions_2","Utilice esta página para configurar un nodo de almacenamiento y unirse a una red de almacenamiento.");
addS ("tahoe-lafs_createnode_name_example","MiNodo");
addS ("tahoe-lafs_createnode_name","Nombre del nodo de almacenamiento");
addS ("tahoe-lafs_createnode_name_tooltip","Un nombre corto para identificar el nodo de almacenamiento en la red de almacenamiento.");
addS ("tahoe-lafs_createnode_result","Resultado del proceso de creación del nodo de almacenamiento:");
addS ("tahoe-lafs_createnode_starting","Inicio del nodo de almacenamiento de Tahoe-LAFS:");
addS ("tahoe-lafs_createnode_subtitle","Creación de un nodo de almacenamiento");

//deleteIntroducer
addS ("tahoe-lafs_deleteintroducer_result","Resultado del proceso de borrado del introducer:");
addS ("tahoe-lafs_deleteintroducer_subtitle","Borrado del introducer");

//deleteNode
addS ("tahoe-lafs_deletenode_result","Resultado del proceso de borrado del nodo de almacenamiento:");
addS ("tahoe-lafs_deletenode_subtitle","Borrado del nodo de almacenamiento");

//flash
addS ("tahoe-lafs_flash_publishing_introducer","Se está publicando el introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_restarting_introducer","Se está reiniciando el introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_restarting_storage","Se está reiniciando el nodo de almacenamiento de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_starting_introducer","Se está iniciando el introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_starting_storage","Se está iniciando el nodo de almacenamiento de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_stopping_introducer","Se está parando el introducer de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_stopping_storage","Se está parando el nodo de almacenamiento de Tahoe-LAFS...");
addS ("tahoe-lafs_flash_unpublishing_introducer","Despublicando el introducer de Tahoe-LAFS...");

//index
addS ("tahoe-lafs_index_description1","Tahoe-LAFS es un sistema de almacenamiento en la nube libre y abierto.");
addS ("tahoe-lafs_index_description2","Funciona distribuyendo sus datos por múltiples servidores.");
addS ("tahoe-lafs_index_description3","Aunque alguno de los servidores falle o sea tomado por un atacante, el sistema de ficheros completo siguecompleto sigue funcionando correctamente, preservando su privacidad y seguridad.");
addS ("tahoe-lafs_index_instructions_1","Para desplegar una red de almacenamiento con Tahoe-LAFS hace falta un <strong>introducer</strong> y múltiples <strong>nodos de almacenamiento</strong> distribuidos por la red.");
addS ("tahoe-lafs_index_instructions_2","Haga clic en el botón para instalar Tahoe-LAFS y comenzar a crear una red de almacenamiento o para unirse a una ya existente.");
addS ("tahoe-lafs_index_instructions_3","Haga clic en los botones para comenzar a crear una red de almacenamiento o para unirse a una ya existente.");
addS ("tahoe-lafs_index_subtitle","Un sistema de almacenamiento en la nube que distribuye sus datos en múltiples servidores");

//install
addS ("tahoe-lafs_install_details","Detalles del proceso de instalación:");
addS ("tahoe-lafs_install_post","Detalles del proceso de post-instalación:");
addS ("tahoe-lafs_install_result","Resultado del proceso de instalación:");
addS ("tahoe-lafs_install_subtitle","Instalación");

//introducer
addS ("tahoe-lafs_introducer_announcement","Anuncio del servicio:");
addS ("tahoe-lafs_introducer_FURL","FURL del introducer:");
addS ("tahoe-lafs_introducer_grid","Nombre de la red de almacenamiento:");
addS ("tahoe-lafs_introducer_instructions_1","Para desplegar una red de almacenamiento con Tahoe-LAFS hace falta un <strong>introducer</strong> y múltiples <strong>nodos de almacenamiento</strong> distribuidos por la red.");
addS ("tahoe-lafs_introducer_instructions_2","Haga clic en el botón para configurar un introducer en esta máquina.");
addS ("tahoe-lafs_introducer_instructions_3","Una vez configurado, los nodos de almacenamiento podrán unirse al introducer para desplegar la red de almacenamiento.");
addS ("tahoe-lafs_introducer_private","La red de almacenamiento es privada");
addS ("tahoe-lafs_introducer_public","La red de almacenamiento es pública y se está anunciando a la comunidad");
addS ("tahoe-lafs_introducer_status","Estado del introducer de Tahoe-LAFS:");
addS ("tahoe-lafs_introducer_subtitle","Introducer");
addS ("tahoe-lafs_introducer_web","Interfaz web del introducer:");

//node
addS ("tahoe-lafs_node_FURL","FURL del introducer:");
addS ("tahoe-lafs_node_instructions_1","Para desplegar una red de almacenamiento con Tahoe-LAFS hace falta un <strong>introducer</strong> y múltiples <strong>nodos de almacenamiento</strong> distribuidos por la red.");
addS ("tahoe-lafs_node_instructions_2","Haga clic en el botón para configurar un nodo de almacenamiento en esta máquina y unirse a una red de almacenamiento.");
addS ("tahoe-lafs_node_status","Estado del nodo de almacenamiento de Tahoe-LAFS:");
addS ("tahoe-lafs_node_subtitle","Nodo de almacenamiento");
addS ("tahoe-lafs_node_web","Página web del nodo de almacenamiento de Tahoe-LAFS (solo accesible desde localhost):");

//uninstall
addS ("tahoe-lafs_uninstall_details","Detalles del proceso de desinstalación:");
addS ("tahoe-lafs_uninstall_post","Detalles del proceso de post-desinstalación:");
addS ("tahoe-lafs_uninstall_result","Resultado del proceso de desinstalación:");
addS ("tahoe-lafs_uninstall_subtitle","Desinstalación");
?>