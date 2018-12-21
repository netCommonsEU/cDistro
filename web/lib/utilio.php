<?php
    //utils I/O utilio.php

define('S_IFMT', 0170000);
define('S_IFIFO', 0010000);

function load_conffile($file, $default = null)
{
    $variables = "";

    if ($default != null) {
        $variables = array();
        foreach ($default as $k=>$v) {
            $variables[$k]=(string)$v['default'];
        }
    }
    if (!file_exists($file)) {
        if (is_array($variables)) {
            return($variables);
        }
        notFileExist($file);
    }
    if (($v = parse_ini_file_hash($file)) == false) {
        if (is_array($variables)) {
            return($variables);
        }
        notReadFile($file);
    }
    return(del_quotes($v));
}

function load_singlevalue($file, $varis)
{
    global $debug;

    if (!file_exists($file)) {
        $variables = array();
        foreach ($varis as $k=>$v) {
            $variables[$k]=(string)$v['default'];
        }
        return($variables);
    }

    $v = array();
    // llegir fitxer
    $c = file_get_contents($file);

    if ($debug) {
        echo "<pre>";
    }

    foreach ($varis as $vari=>$vals) {
        $p = "/{$vari}[ \t]*=[ \t]*([^;]*)/";
        preg_match($p, $c, $a);
        if ($debug) {
            print_r($a);
        }
        if (is_array($a) && isset($a[1])) {
            $v[$vari] = $a[1];
        } else {
            $v[$vari] = $vals['default'];
        }
        if (substr($v[$vari], 0, 1) == "'") {
            $v[$vari] = substr($v[$vari], 1);
        }
        if (substr($v[$vari], -1, 1) == "'") {
            $v[$vari] = substr($v[$vari], 0, strlen($v[$vari])-1);
        }
        if ($debug) {
            echo "v=$v[$vari]";
        }
    }

    if ($debug) {
        echo "</pre>";
    }

    return($v);
}

function write_conffile($file, $dates, $preinfo="", $postinfo="")
{
    //Prepare file
    $str = $preinfo;
    foreach (add_quotes($dates) as $k=>$v) {
        $str .="$k=$v\n";
    }
    $str .= $postinfo;

    if (!file_exists($file)) {
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0755, true);
        }
    }

    if ((file_put_contents($file, $str)) == false) {
        notWriteFile($file);
    }
}

function write_merge_conffile($file, $dates)
{
    global $debug;

    if (file_exists($file)) {
        $conf = parse_ini_file_hash($file);
        $str = "";

        if ($debug) {
            echo "<pre>";
        }

        foreach ($dates as $k=>$v) {
            if (array_key_exists($k, $conf)) {
                $cmd = "sed -i -e 's|".$k." *= *[^;]*|".$k."=".$v."|g' ".$file;

                if ($debug) {
                    echo $cmd;
                }

                shell_exec($cmd);
            } else {
                $str .="$k=$v\n";
            }
        }
        if ($debug) {
            echo "</pre>";
        }

        if ($str != "") {
            file_put_contents($file, $str, FILE_APPEND);
        }
    } else {
        write_conffile($file, $dates);
    }
}


function exec_user($cmd, $user)
{
    $cmd = "/bin/su ".$user." -c '" . addslashes($cmd) . "'";
    return (exec($cmd));
}

function shell_exec_user($cmd, $user)
{
    $cmd = "/bin/su ".$user." -c '" . addslashes($cmd) . "'";
    return (shell_exec($cmd));
}

function isPackageInstall($pkg)
{
    $cmd = 'dpkg-query -W --showformat=\'${Status}\n\' '.$pkg.'| grep "install ok installed"; echo $?';
    return (shell_exec($cmd) == 0);
}

function packageInstallationInfo($pkg)
{
    $cmd = 'dpkg-query -s '.$pkg;
    return (shell_exec($cmd));
}

function installPackage($pkg)
{
    $cmd = "apt-get install -y ".$pkg." 2>&1";
    return (shell_exec($cmd));
}

function uninstallPackage($pkg)
{
    $cmd = "apt-get purge -y ".$pkg." 2>&1";
    return (shell_exec($cmd));
}

function package_default_variables($dts, $default, $pkgname, $undefined_variables=null)
{
    global $debug;

    $str = "";
    foreach ($dts as $k=>$v) {
        $variable = $default[$k];

        $cmd="echo \"".$pkgname."	".$variable['vdeb']."	".$variable['kdeb']."	".$v."\" | debconf-set-selections 2>&1" ;
        if ($debug) {
            $str .= $cmd."\n";
        }

        $str .= shell_exec($cmd);
        if ($debug) {
            $str .= "\n";
        }
    }
    if (is_array($undefined_variables)) {
        foreach ($undefined_variables as $k => $v) {
            if (isset($v['debpkg'])) {
                $p = $v['debpkg'];
            } else {
                $p = $pkgname;
            }
            $cmd="echo \"".$p."	".$v['vdeb']."	".$v['kdeb']."	".$v['default']."\" | debconf-set-selections 2>&1" ;
            if ($debug) {
                $str .= $cmd."\n";
            }

            $str .= shell_exec($cmd);
            if ($debug) {
                $str .= "\n";
            }
        }
    }

    return($str);
}

function execute_program($cmd)
{
    if (exec("$cmd 2>&1", $output, $return) == false) {
        errorExecuteExternalProgram($cmd);
    }

    return(array('output'=>$output,'return'=>$return));
}
function execute_program_shell($cmd)
{
    $ret = shell_exec($cmd." 2>&1");
    return(array('output'=>$ret,'return'=>""));
}

function execute_shell($cmd)
{
    if (($ret = shell_exec($cmd." > /dev/null 2>&1; echo $?")) == null) {
        errorExecuteExternalProgram($cmd);
    }
    return(array('output'=>"",'return'=>$ret));
}
function execute_bg_shell($cmd)
{
    exec('bash -c "exec nohup setsid '.$cmd.' > /dev/null 2>&1 &"');
}
function cmd_exec($cmd, &$stdout, &$stderr)
{
    $outfile = tempnam(".", "cmd");
    $errfile = tempnam(".", "cmd");
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("file", $outfile, "w"),
        2 => array("file", $errfile, "w")
    );
    $proc = proc_open($cmd, $descriptorspec, $pipes);

    if (!is_resource($proc)) {
        return 255;
    }

    fclose($pipes[0]);

    $exit = proc_close($proc);
    $stdout = file($outfile);
    $stderr = file($errfile);

    unlink($outfile);
    unlink($errfile);
    return $exit;
}
function execute_proc($cmd)
{
    if (($return = cmd_exec("$cmd", $output, $outerr)) == null) {
        errorExecuteExternalProgram($cmd, serialize($output)."-".serialize($outerr));
    }
    return(array('output'=>$output,'return'=>$return));
}

function execute_program_detached($c)
{
    $fdpipe="/tmp/cDistrod";

    $s = stat($fdpipe);
    $mode = $s['mode'];
    if (S_IFIFO != ($mode & S_IFMT)) {
        setFlash('<i>'.$fdpipe.'</i> '.t('is not file type FIFO, maybe daemon is not running.'), "error");
    } else {
        $fh = fopen($fdpipe, "a");
        if ($fh==false) {
            setFlash(t("Some problemes in open file!"));
        }

        fprintf($fh, "%s\n", $c);
        fclose($fh);
    }
}

function execute_program_detached_user($cmd, $user)
{
    $cmd = "/bin/su ".$user." -c '" . addslashes($cmd) . "'";
    execute_program_detached($cmd);
}

function avahi_search()
{
    $ret = execute_program("SEARCH_ONLY=avahi /usr/sbin/avahi-ps search");
    $services = $ret['output'];
    $aServices = array();
    foreach ($services as $service) {
        $lServer = explode(";", $service);
        if (count($lServer) > 4) {
            $type = $lServer[0];
            $pos = strrpos($type, ".");
            $type = substr($type, 1, $pos - 1);
            $aServices[] = array('type'=> $type, 'description'=>$lServer[1], 'host'=>$lServer[2], 'ip'=>$lServer[3], 'port'=>$lServer[4],'txt'=>$lServer[5]);
        }
    }
    /*echo "<pre>";
    print_r($aServices);
    echo "</pre>";*/
    return ($aServices);
}

function active_services()
{
    $ret = execute_program("/usr/sbin/avahi-service isActive");
    $services = $ret['output'];
    $aServices = array();
    foreach ($services as $service) {
        $lServer = explode(" ", $service);
        $aServices[] = array('name'=>$lServer[0], 'status'=>$lServer[1]);
    }
    /*	echo "<pre>";
        print_r($aServices);
        echo "</pre>";*/
    return ($aServices);
}

function avahi_publish($type, $description, $port, $txt)
{
    $cmd = '/usr/sbin/avahi-ps publish "'.$description.'" '.$type.' '.$port.' "'.$txt.'"';
    execute_program_detached($cmd);

    return($cmd);
}

function avahi_unpublish($type, $port)
{
    $cmd = '/usr/sbin/avahi-ps unpublish '.$type.' '.$port;
    execute_program_detached($cmd);

    return($cmd);
}

function getCommunityDev()
{
    $cmd = '/usr/sbin/avahi-ps info communitydev';
    $ret = execute_program($cmd);
    //$ret = $cmd;

    return($ret);
}

function getCommunityIP()
{
    $cmd = '/usr/sbin/avahi-ps info ip';
    $ret = execute_program($cmd);
    //$ret = $cmd;

    return($ret);
}

function getCommunityDevMAC()
{
    $cmd = 'cat /sys/class/net/'.getCommunityDev()['output'][0].'/address';
    $ret = execute_program($cmd);

    return($ret);
}

function port_listen($port)
{
    $cmd='netstat -nlt|grep ":'.$port.'"|grep -q LISTEN; echo $?';
}

function check_arch($list_arch = null)
{
    if (! is_array($list_arch)) {
        return true;
    }
    $cmd = 'arch';
    $ret = execute_program_shell($cmd);
    $myarch = rtrim($ret['output']);
    return (in_array($myarch, $list_arch));
}

function has_virtualization_extensions()
{
    $cmd = "egrep '(vmx|svm)' /proc/cpuinfo|wc -l";
    $ret = execute_program_shell($cmd);
    $vtx = rtrim($ret['output']);
    return($vtx != "0");
}

function add_quotes($dates)
{
    foreach (del_quotes($dates) as $k=>$v) {
        $dates[$k] = '"'.$dates[$k].'"';
    }
    return $dates;
}

function del_quotes($dates)
{
    foreach ($dates as $k => $v) {
        if (strpos($v, '"') === 0) {
            $v = substr($v, 1, (strlen($v)-1));
        }

        if (strrpos($v, '"') === (strlen($v)-1)) {
            $v=substr($v, 0, -1);
        }
        $dates[$k] = $v;
    }
    return $dates;
}

function _getHttp($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resposta = curl_exec($ch);
    curl_close($ch);

    return($resposta);
}

function _delTree($dir)
{
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

    //From https://stackoverflow.com/a/834355
    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 ||
        (substr($haystack, -$length) === $needle);
    }

    //From https://stackoverflow.com/a/834355
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }


//Based on http://php.net/manual/en/function.parse-ini-string.php#111845
function parse_ini_file_hash($file)
{
    if (empty($file)) {
        return false;
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $ret = array();
    $inside_section = false;

    foreach ($lines as $line) {
        $line = trim($line);

        if (!$line || $line[0] == "#" || $line[0] == ";") {
            continue;
        }

        if ($line[0] == "[" && $endIdx = strpos($line, "]")) {
            $inside_section = substr($line, 1, $endIdx-1);
            continue;
        }

        if (!strpos($line, '=')) {
            continue;
        }

        $tmp = explode("=", $line, 2);

        if ($inside_section) {
            $key = rtrim($tmp[0]);
            $value = ltrim($tmp[1]);

            if (preg_match("/^\".*\"$/", $value) || preg_match("/^'.*'$/", $value)) {
                $value = mb_substr($value, 1, mb_strlen($value) - 2);
            }

            $t = preg_match("^\[(.*?)\]^", $key, $matches);
            if (!empty($matches) && isset($matches[0])) {
                $arr_name = preg_replace('#\[(.*?)\]#is', '', $key);

                if (!isset($ret[$inside_section][$arr_name]) || !is_array($ret[$inside_section][$arr_name])) {
                    $ret[$inside_section][$arr_name] = array();
                }

                if (isset($matches[1]) && !empty($matches[1])) {
                    $ret[$inside_section][$arr_name][$matches[1]] = $value;
                } else {
                    $ret[$inside_section][$arr_name][] = $value;
                }
            } else {
                $ret[$inside_section][trim($tmp[0])] = $value;
            }
        } else {
            $ret[trim($tmp[0])] = ltrim($tmp[1]);
        }
    }
    return $ret;
}
