<?php
/**
 * Created by PhpStorm.
 * User: Dumidu
 * Date: 7/25/2016
 * Time: 2:50 PM
 */
require('db_connect.php');
require('auth_token.php');

if (!empty($_REQUEST['key']) && ($_REQUEST['key'] == APIKEY)) {


    if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid']) && isset($_REQUEST['filename']) && !empty($_REQUEST['filename']) && isset($_REQUEST['image']) && !empty($_REQUEST['image'])) {

        $uid = mysqli_real_escape_string($db, $_REQUEST['uid']);
        $img = $_REQUEST['image'];
        $file = mysqli_real_escape_string($db, $_REQUEST['filename']);

        $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

        if ($result->num_rows == 1) {
            $filename = time() . "-" . $file;

            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            file_put_contents('pictures/' . $filename, $data);

            $filename = "http://52.41.13.192/api/pictures/" . $filename;

            $query = $db->query(sprintf("UPDATE `users` SET `picture`='" . $filename . "' WHERE `uid` = '$uid'"));


            if ($query) {

                $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

                $data = $result->fetch_assoc();

                $token = $data['uid'];

                $json = array("status" => 1, "uid" => $token, "user" => $data);

            } else {
                $json = array("status" => 0, "response" => "server error");
            }

        } else {
            $json = array("status" => 0, "response" => "user not exists");
        }

    } else {
        $json = array("status" => 0, "response" => "invalid user token");
    }

} else {
    $json = array("status" => 0, "response" => "invalid api key");
}

/* Output header */
header('Content-type: application/json');
header("access-control-allow-origin: *");
echo json_encode($json);