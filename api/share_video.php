<?php
/**
 * Created by PhpStorm.
 * User: Dumidu
 * Date: 6/11/2016
 * Time: 7:25 PM
 */
require('db_connect.php');
require('auth_token.php');

$fileData = file_get_contents('php://input');

if (!empty($_REQUEST['key']) && ($_REQUEST['key'] == APIKEY)) {

    if (isset($_REQUEST['uid']) && isset($_REQUEST['video']) && !empty($_REQUEST['uid']) && !empty($_REQUEST['video'])) {

        $shared_by = mysqli_real_escape_string($db, $_REQUEST['uid']);
        $video = mysqli_real_escape_string($db, $_REQUEST['video']);

        $result1 = $db->query("SELECT * FROM `users` WHERE `uid` = '$shared_by'");

        if ($result1->num_rows == 1) {

            $result2 = $db->query("SELECT * FROM `videos` WHERE `id` = '$video'");

            if ($result2->num_rows == 1) {

                $udata = $result1->fetch_assoc();
                $vdata = $result2->fetch_assoc();

                $owner_id = $vdata['uid'];
                $shared_name = $udata['username'];
                $video_title = $vdata['title'];

                $query = $db->query(sprintf("INSERT INTO `shares`(`owner_id`, `shared_uid`, `shared_name`, `vid`, `video_title`) 
VALUES ('$owner_id', '$shared_by', '$shared_name', '$video', '$video_title')"));

                if ($query) {
                    $json = array("status" => 1, "response" => "success");
                } else {
                    $json = array("status" => 0, "response" => "server error");
                }

            } else {
                $json = array("status" => 0, "response" => "invalid video id");
            }

        } else {
            $json = array("status" => 0, "response" => "invalid user id");
        }

    } else {
        $json = array("status" => 0, "response" => "invalid information");
    }

} else {
    $json = array("status" => 0, "response" => "invalid api key");
}

/* Output header */
header('Content-type: application/json');
header("access-control-allow-origin: *");
echo json_encode($json);