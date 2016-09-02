<?php
/**
 * Created by PhpStorm.
 * User: Dumidu
 * Date: 6/11/2016
 * Time: 7:29 PM
 */

header('Content-Type: text/plain; charset=utf-8;');
session_start();
require_once('config.php');
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);

mysqli_set_charset($db, 'utf8');

if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}