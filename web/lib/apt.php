<?php
//apt.php

function addSource($file, $content)
{
    if (validSourceFile($file)) {
        $content = "\n".$content;
    }

    file_put_contents($file, $content, $flags = FILE_APPEND);

    return;
}


function aptArch()
{
    $uname=posix_uname();
    switch ($uname['machine']) {
        case "i686":
        case "i386":
            $apt_arch = "i386";
            break;
        case "x86_64":
            $apt_arch = "amd64";
            break;
        case "armv6l":
        case "armv67":
            $apt_arch = "armhf";
            break;
    }

    return $apt_arch;
}


function aptRelease()
{
    return trim(execute_program_shell('lsb_release -cs')['output'], " \t\n\r\0\x0B");
}



function validSourceFile($file)
{
    if (file_exists($file) && ((substr($file, 0, strlen('/etc/apt/sources.list')) === '/etc/apt/sources.list') || (substr($file, 0, strlen('/etc/apt/sources.list.d/')) === '/etc/apt/sources.list.d/'))) {
        $finfo = new finfo(FILEINFO_MIME);
        if (strpos($finfo->buffer($file), 'text/plain') !== false) {
            return true;
        }
    }
    return false;
}
