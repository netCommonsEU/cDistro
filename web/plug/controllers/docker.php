<?php
$urlpath="$staticFile/docker";
$docker_pkg = "docker.io";
$dev = "docker0";

function index() {
        global $title, $urlpath, $docker_pkg, $staticFile;

        $page = hlc(t("docker_title"));
        $page .= hl(t("docker_desc"), 4);

        if (!isPackageInstall($docker_pkg)) {
                $page .= "<div class='alert alert-error text-center'>".t("docker_not_installed")."</div>\n";
                $page .= addButton(array('label'=>t("docker_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
                return array('type'=>'render','page'=>$page);
        } elseif (!isRunning()) {
                $page .= "<div class='alert alert-error text-center'>".t("docker_not_running")."</div>\n";
                $page .= addButton(array('label'=>t("docker_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/start"));
                $page .= addButton(array('label'=>t('docker_remove'),'class'=>'btn btn-danger', 'href'=>$staticFile.'/default/uninstall/'.$docker_pkg));
                return array('type'=>'render','page'=>$page);
        } else {
                //$page .= ptxt(info_docker());
                $page .= "<div class='alert alert-success text-center'>".t("docker_running")."</div>\n";
                $page .= addButton(array('label'=>t("docker_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop"));

		//Codi modificat: Docker GUI
		$page = docker_Admin($page);

                return array('type' => 'render','page' => $page);
        }
}

function isRunning(){
        $cmd = "/usr/bin/docker ps";
        $ret = execute_program($cmd);
  return ( $ret['return'] ==  0 );
}
function install(){
  global $title, $urlpath, $docker_pkg;

  $page = package_not_install($docker_pkg,t("docker_desc"));
  return array('type' => 'render','page' => $page);
}
function start() {
        global $urlpath;

        execute_program_detached("service docker start");
        setFlash(t('docker_start_message'),"success");
        return(array('type'=> 'redirect', 'url' => $urlpath));
}
function stop() {
        global $urlpath;

        execute_program_detached("service docker stop");
        setFlash(t('docker_stop_message'),"success");
        return(array('type'=> 'redirect', 'url' => $urlpath));
}
function info_docker(){
        global $dev;

        $cmd = "/sbin/ip addr show dev $dev";
        $ret = execute_program($cmd);
  return ( implode("\n", $ret['output']) );

}


/////////////////////////////////////////////////////////////////////////
//Codi afegit per:                                                     //
//   Miguel Moreno - mmorenoreal@uoc.edu - mmoreno803@gmail.com        //
//   UOC - MEI - TFM: Sistemes Distribuïts)                            //
/////////////////////////////////////////////////////////////////////////


//Funcions Principals//
//-------------------//

//Funció que crea la taula amb la informació a mostrar i els botons 
//associats a cada contenidor
function docker_Admin($page_tmp){
        global $dev,$urlpath;
	$containerspath="/var/local/cDistro/plug/resources/docker/containers/";

        $page_tmp .= hl("", 4);
        $page_tmp .= hl("Your Containers", 2);

	//llistem els fitxers existents
	$cmd = "ls ".$containerspath;
	$llista = execute_program($cmd);

	//Iniciem la taula
        $page_tmp .= addTableHeader(array("Name","Status","Publish","Actions","Aplication","Config"), array('class'=>'table table-striped'));
	$taula_running="";
	$taula_stopped="";
	$taula_notinstalled="";

	//Per a cada fitxer trobat
	foreach($llista['output'] as $container){
		//Obtenim info dels fitxers i de docker
		$cInfo = get_container_info($container);
		$cDocker = get_docker_info($container);

		//Definim botons
		$bStart=botonStartStop($cInfo,$cDocker);
		$bDelete=botonDelete($cInfo,$cDocker);
		$bPublish=botonPublish($cInfo,$cDocker);
		$bApp=botonApp($cInfo,$cDocker);
		$bInstall=botonInst($cInfo,$cDocker);
		$bEdit=botonEdit($cInfo,$cDocker);
		$bRemove=botonRemove($cInfo,$cDocker);
		$bNew=botonNouContainer();

		//Si està instal·lat, es mostra la botonera
		if($cDocker['id']!="NotFound"){
			if($cDocker['status']=="Running"){
				$taula_running .= addTableRow(array($cInfo['name'],$cDocker[status],$cInfo['pub'],$bStart.$bDelete,$bPublish.$bApp,$bEdit));
			}else{
				$taula_stopped .= addTableRow(array($cInfo['name'],$cDocker[status],$cInfo['pub'],$bStart.$bDelete,$bPublish.$bApp,$bEdit));
			}
		}else{  //Si no ho està, es dona l'opció de instal·lar-lo
			if($cInfo['id']=="Installing"){
				//Si s'està instal·lant
				$taula_notinstalled .= addTableRow(array($cInfo['name'],"Installing..."," "," ","Wait please..."));
			}else{
				//Si no ho està, es dona l'opció de instal·lar-lo
				$taula_notinstalled .= addTableRow(array($cInfo['name'],"Not installed",$cInfo['pub'],$bInstall,"",$bEdit.$bRemove));
			}
		}
	}

	//Taula: primer es mostren els containers en execució
	$page_tmp .= $taula_running;
	$page_tmp .= $taula_stopped;
	$page_tmp .= $taula_notinstalled;

        //Tanquem la taula i retornem resultat
        $page_tmp .= addTableFooter();
	$page_tmp .= $bNew;
        return ( $page_tmp );
}

//Funció auxiliar per obtenir la informació d'una imatge en concret
//Si existeix el fitxer de configuració, retorna tota la informació
//Si no existeix, retorna 0
function get_container_info($image){
        global $dev,$urlpath;
        $containerspath="/var/local/cDistro/plug/resources/docker/containers/";

        //Llegim el fitxer de configuració
        $cmd = "cat ".$containerspath.$image;
        $properties = execute_program($cmd);

        //Existeix el fitxer de configuració?
        if(count($properties['output'])==1){
                return("0"); //Retornem Error
        }else{
                //Es recuperen les propietats
                foreach ($properties['output'] as $prop){
                        $prop=explode("=",$prop);
                        switch($prop[0]){
                                case "Name":
                                        $name=$prop[1]; break;
                                case "Run":
                                        $run=$prop[1]; break;
                                case "Id":
                                        $id=$prop[1]; break;
                                case "Port":
                                        $port=$prop[1]; break;
                                case "Img":
                                        $img=$prop[1]; break;
                                case "Pub":
                                        $pub=$prop[1]; break;
                        }
                }
        }
        return(array('name'=>$name,'run'=>$run,'id'=>$id,'port'=>$port,'img'=>$img,'pub'=>$pub));
}


//Funció auxiliar per obtenir la informació del contenidor docker en execució
//per una determinada imatge.
//Si la imatge no existeix al sistema Docker retorna ("0","Not Installed)
//Si existeix, retorna el seu ID i Status
function get_docker_info($image){
        global $dev,$urlpath;

        //Consultem els contenidors existents
        $cmd = "docker ps -a";
        $ret = execute_program($cmd);

        //Fem servir la capçalera per trobar la posició de Status
        $status_pos = strpos($ret['output'][0],"STATUS");

        //Inicialitzem variables per si no trobem el contenidor
	$id="NotFound";
        $status="Not Installed";
        //Busquem a cada contenidor
        foreach($ret['output'] as $container){
                if(strpos($container, $image)){
                        $id=substr($container,0,12);  //Obtenim ID
                        //$id=1;
			$status=substr($container,$status_pos,2); //Status
                        if($status=="Up") $status="Running";
                        else $status="Stopped";
			$info=substr($container,0,12);
                }
        }
        return(array('id'=>$id, 'status'=>$status));
}


//Definició dels botons//
//--------------------//

function botonStartStop($cInfo,$cDocker){
	global $dev,$urlpath;
	if($cDocker['status']=="Running"){
		return(addButton(array('label'=>"Stop",'class'=>'btn btn-danger', 'href'=>"$urlpath/containerStop/".$cInfo['img'])));
	}else{
		return($botonOn=addButton(array('label'=>"Start",'class'=>'btn btn-success', 'href'=>"$urlpath/containerStart/".$cInfo['img'])));
	}
}

function botonDelete($cInfo,$cDocker){
	global $dev,$urlpath;
	if($cDocker['status']=="Running"){
		return(addButton(array('label'=>"Uninstall", 'class'=>'btn btn-default disabled')));
        }else{
		return(addButton(array('label'=>"Uninstall",'class'=>'btn btn-danger', 'href'=>"$urlpath/containerDelete/".$cInfo['img'])));
        }
}

function botonPublish($cInfo,$cDocker){
	global $dev,$urlpath;
	if($cDocker['status']=="Running" && $cInfo['pub']=="No"){
		return(addButton(array('label'=>"Publish",'class'=>'btn btn-info', 'href'=>"$urlpath/containerPublish/".$cInfo['img'])));
	}else if($cDocker['status']=="Running" && $cInfo['pub']=="Yes"){ 
                return(addButton(array('label'=>"Unpubli",'class'=>'btn btn-info', 'href'=>"$urlpath/containerUnpublish/".$cInfo['img'])));
	}else{
		return(addButton(array('label'=>"Publish",'class'=>'btn btn-default disabled')));
	}
}
function botonApp($cInfo,$cDocker){
        //obtenim el hostname
        $cmd="hostname -f";
        $hostname=execute_program($cmd);

        //Si el contenidor està en execució, mostrem el botó
        if($cDocker['status']=="Running"){
                return(addButton(array('label'=>"Enter App",'class'=>'btn btn-info', 'href'=>"http://".$hostname['output'][0].":".$cInfo['port'])));
        }else{
                return(addButton(array('label'=>"Enter App",'class'=>'btn btn-default disabled')));
        }
}

function botonInst($cInfo,$cDocker){
        global $dev,$urlpath;
        return(addButton(array('label'=>"Install",'class'=>'btn btn-success', 'href'=>"$urlpath/containerInstall/".$cInfo['img'])));
}

function botonEdit($cInfo,$cDocker){
        global $dev,$urlpath;

                return(addButton(array('label'=>"Edit",'class'=>'btn btn-info', 'href'=>"$urlpath_/index.php/docker-form/index/".$cInfo['img'])));

}

function botonNouContainer($cInfo,$cDocker){
        global $dev,$urlpath;

                return(addButton(array('label'=>"New Container",'class'=>'btn btn-info', 'href'=>"$urlpath_/index.php/docker-form/index/")));

}

function botonRemove($cInfo,$cDocker){
        global $dev,$urlpath;
        return(addButton(array('label'=>"Remove",'class'=>'btn btn-danger', 'href'=>"$urlpath/removeConfig/".$cInfo['img'])));
}



//Accions dels botons//
//-------------------//

function containerStart() {
        global $urlpath,$Parameters;
	$respath="/var/local/cDistro/plug/resources/docker/";

	//Recollim els paràmetres
	$cIm = $Parameters[0];

	//Obtenir informació de Docker
        $con = get_docker_info($cIm);
        $cId = $con['id'];

	//Arranquem el contenidor
        execute_program_detached("docker start CONTAINER ".$cId);
	setFlash("Container Started","success");

	//Publiquem el servei si aplica
        $conInfo=get_container_info($cIm);
	if($conInfo['pub']=="Yes"){
	        execute_program_detached("/usr/sbin/avahi-ps publish ".$conInfo['name']." Docker ".$conInfo['port']);
		execute_program_detached($respath."monitor ".$conInfo['port']);
		setFlash("Container Published ".$cIm."success");
	}
        return(array('type'=> 'redirect', 'url' => $urlpath));
}

function containerStop() {
        global $urlpath,$Parameters;

	//Recollim els paràmetres
	$cIm = $Parameters[0];

	//Obtenir informació de Docker
	$con = get_docker_info($cIm);
	$cId = $con['id'];


	//Es para el contenidor i es despublica
	execute_program_detached("docker stop CONTAINER ".$cId);
        setFlash("Container Stopped. ","success");
	$conInfo=get_container_info($cIm);
	execute_program_detached("/usr/sbin/avahi-ps unpublish Docker ".$conInfo['port']);

	sleep(2); //Esperem dos segons per donar temps al contenidor per parar-se

	return(array('type'=> 'redirect', 'url' => $urlpath));
}

function containerDelete() {
        global $urlpath,$Parameters;
	$respath="/var/local/cDistro/plug/resources/docker/";

	//Recollim els paràmetres
        $cIm = $Parameters[0];

        //Obtenir informació de Docker
        $con = get_docker_info($cIm);
        $cId = $con['id'];
	$conInfo=get_container_info($cIm);

        execute_program_detached("docker rm ".$cId);
	execute_program_detached($respath."delete ".$conInfo['img']);

        setFlash("Container Deleted. ","success");
        return(array('type'=> 'redirect', 'url' => $urlpath));
}

function containerPublish() {
        global $urlpath,$Parameters;
        $respath="/var/local/cDistro/plug/resources/docker/";

        //Obtenim el contenidor a Publicar (Paràmetre de la funció)
        $con = $Parameters[0];
        //Obtenim informació associada al contenidor
        $conInfo=get_container_info($con);

        //Publiquem el servei
        execute_program_detached("/usr/sbin/avahi-ps publish ".$conInfo['name']." Docker ".$conInfo['port']);
        //Modifiquem el paràmetre de configuració
        execute_program_detached($respath."publish ".$conInfo['img']);

        setFlash("Container Published. ","success");
        return(array('type'=> 'redirect', 'url' => $urlpath));
}

function containerUnpublish() {
        global $urlpath,$Parameters;
        $respath="/var/local/cDistro/plug/resources/docker/";

        //Obtenim el contenidor a Publicar (Paràmetre de la funció)
        $con = $Parameters[0];
        //Obtenim informació associada al contenidor
        $conInfo=get_container_info($con);

        //Publiquem el servei
        execute_program_detached("/usr/sbin/avahi-ps unpublish Docker ".$conInfo['port']);
        //Modifiquem el paràmetre de configuració
        execute_program_detached($respath."unpublish ".$conInfo['img']);

        setFlash("Container Unpublished. ","success");
        return(array('type'=> 'redirect', 'url' => $urlpath));
}


function removeConfig() {
        global $urlpath,$Parameters;
        $respath="/var/local/cDistro/plug/resources/docker/";
	
        //Obtenim el contenidor a Publicar (Paràmetre de la funció)
        $con = $Parameters[0];
        //Obtenim informació associada al contenidor
        $conInfo=get_container_info($con);

        //Esborrem el fitxer de configuració
        execute_program_detached($respath."remove ".$conInfo['img']);

        setFlash("Container Removed. ","success");
        return(array('type'=> 'redirect', 'url' => $urlpath));
	
}

function containerInstall() {
	global $urlpath,$Parameters;
	$respath="/var/local/cDistro/plug/resources/docker/";

        //Obtenim el contenidor a Instal·lar (Paràmetre de la funció)
        $con = $Parameters[0];
        //Obtenim informació associada al contenidor
        $conInfo=get_container_info($con);

	//executem la instal·lació
	execute_program_detached($respath."install ".$conInfo['run']);
	//execute_program_detached("docker run -d -p 8084:80 mkodockx/docker-kanban");
	//Alertem que el contenidor està en fase d'instal·lació
	execute_program_detached($respath."installing ".$conInfo['img']);

	setFlash("Installing Container... ".$conInfo['run'],"success");
	return(array('type'=> 'redirect', 'url' => $urlpath));
}
