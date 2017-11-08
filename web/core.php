<?php
// Core


function getParameter()
{
    $spath = null;

    if (isset($_SERVER['PATH_INFO'])) {
        $spath = explode("/", $_SERVER['PATH_INFO']);
        array_shift($spath);
    }

    return ($spath);
}

$Parameters = getParameter();
