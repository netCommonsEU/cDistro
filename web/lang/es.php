<?php
//List of string english
// lib/errors.php

// plug/menus/lang.menu.php
addS ('Catalan','Catalán');
addS ('English','Inglés');
addS ('Spanish','Español');
// lib/plug/controller/
addS ('Welcome!','Bienvenido !');
addS ('Define language to: ', 'Idioma seleccionado: ');

addLangFiles($documentPath . $lang_dir, "es");

// lib/view.php
addS ("lib-view_button_back","Volver");
addS ("lib-view_button_install_pre","Instalar el paquete ");
addS ("lib-view_button_install_post","");
addS ("lib-view_common_package_manager_title","Gestor de paquetes");
addS ("lib-view_common_package_manager_subtitle","Instalador y desinstalador automatizado de paquetes de Cloudy");
addS ('lib-view_package_not_installed_to_install','El siguiente paquete va a ser instalado:');
addS ('lib-view_package_not_installed_text','Haga clic en el botón para proceder con la instalación. Las dependencias del paquete se instalarán automáticamente.');


