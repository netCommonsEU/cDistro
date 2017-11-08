<?php
//docker_form.menu.php
global $docker_pkg;

if (isPackageInstall($docker_pkg)) {
    addMenu('Docker FORM', 'docker-form', t('menus_cloud_enterprise'));
}
