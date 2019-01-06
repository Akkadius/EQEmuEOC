<?php

$offset         = -50;
$Minified       = 0;
$SessionTimeout = 999;
$secureEOC      = false;
$AccessStatus   = 250;

date_default_timezone_set('UTC');

$allaclonecfg = '../allaclone/includes/config.php';
$search       = '$cfgversion';
// Read from allaclone cfg file
if (file_exists($allaclonecfg)) {
    $lines = file($allaclonecfg);
    foreach ($lines as $line) {
        if (strpos($line, $search) !== false) {
            $input = $line;
            preg_match('~\'(.*?)\'~', $input, $output);
            $cfgversion = $output[1];
        }
    }
} else {
    $cfgversion = 0;
}

/**
 * MySQL
 */
$dbhost   = "localhost";
$dbname   = "";
$dbuser   = "";
$dbpasswd = "";

/**
 * Commander config
 */
$WS_IP    = '127.0.0.1';
$WS_PORT  = '9081';
$WS_TOKEN = 'somerandomstringofjunk';

/* EoC Local Database Storage */
$eoc_dbhost   = $dbhost;
$eoc_dbname   = $dbname;
$eoc_dbuser   = $dbuser;
$eoc_dbpasswd = $dbpasswd;
$eoc_local    = mysql_connect($eoc_dbhost, $eoc_dbuser, $eoc_dbpasswd);

if ($eoc_local) {
    mysql_select_db($eoc_dbname, $eoc_local) or die("Impossible to select $eoc_dbname : " . mysql_error());
}
function connect_local()
{
    global $eoc_local, $eoc_dbname;
    if ($eoc_local) {
        mysql_select_db($eoc_dbname, $eoc_local) or die("Impossible to select $eoc_dbname : " . mysql_error());
    }
}

$db = mysql_connect($dbhost, $dbuser, $dbpasswd);
if ($db) {
    mysql_select_db($dbname, $db) or die("Impossible to select $dbname : " . mysql_error());
}
function connect_return()
{
    global $db, $dbname, $eoc_dbname;
    if ($db) {
        mysql_select_db($db, $dbname) or die("Impossible to select $eoc_dbname : " . mysql_error());
    }
}

$mysql_result_limit = 1000; /* Rows returned from Query */
if ($dbhost == "localhost") {
    $dbhost = "localhost (Test PEQ DB)";
}

$App_Title = "EOC 2.0";
$FJS       = "";
$Mod       = "";

/**
 * 0 = Production
 * 1 = All Debug (Browser and logs)
 * 2 = Moderate Debugging (Just Logs)
 */
$Debug = 2;
if ($Debug == 1) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else if ($Debug == 2) {
    error_reporting(E_ERROR);
}

ini_set('session.gc_maxlifetime', $SessionTimeout);
ini_set('session.gc_probability', 1);
session_set_cookie_params($SessionTimeout);
session_start(); #::: Store Session Variables

/**
 * Session
 */
if (!$_COOKIE['SESS_ID']) {
    $expire_time = time() + (20 * 365 * 24 * 60 * 60); /* A long time */
    setcookie("SESS_ID", session_id(), $expire_time);
}

foreach (array_keys($_POST) as $key) {
    $c[$key] = mysql_real_escape_string($_POST[$key]);
}
foreach (array_keys($_GET) as $key) {
    $c[$key] = mysql_real_escape_string($_GET[$key]);
}
