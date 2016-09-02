<?php
/**
 * Created by PhpStorm.
 * User: Dumidu
 * Date: 6/11/2016
 * Time: 7:25 PM
 */
require('db_connect.php');
require('auth_token.php');

if (!empty($_REQUEST['key']) && ($_REQUEST['key'] == APIKEY)) {

    if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid']) && isset($_REQUEST['video']) && !empty($_REQUEST['video']) && isset($_REQUEST['reason']) && !empty($_REQUEST['reason'])) {

        $reported_by = mysqli_real_escape_string($db, $_REQUEST['uid']);
        $video = mysqli_real_escape_string($db, $_REQUEST['video']);
        $reason = mysqli_real_escape_string($db, $_REQUEST['reason']);

        $result1 = $db->query("SELECT * FROM `users` WHERE `uid` = '$reported_by'");

        if ($result1->num_rows == 1) {

            $result2 = $db->query("SELECT * FROM `videos` WHERE `id` = '$video'");

            if ($result2->num_rows == 1) {

                $vdata = $result2->fetch_assoc();
                $udata = $result1->fetch_assoc();


                $to = REPORT_EMAIL;
                $subject = "Report Video - " . $vdata['title'];

                $message = "
                            <html>
                            <head>
                            <title>Video has been Reported</title>
                            </head>
                            <body>
                            <p>Report Details!</p>
                            <table>
                            <tr>
                            <th>Reported By</th>
                            <td>" . $udata['username'] . "</td>
                            </tr>
                            <tr>
                            <th>Video Id</th>
                            <td>" . $vdata['id'] . "</td>
                            </tr>
                            <tr>
                            <th>Video Title</th>
                            <td>" . $vdata['title'] . "</td>
                            </tr>
                            <tr>
                            <th>reason</th>
                            <td>" . $reason . "</td>
                            </tr>
                            </table>
                            </body>
                            </html>
                            ";


                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                $headers .= 'From: <webmaster@journeyapp.tk>' . "\r\n";

                mail($to, $subject, $message, $headers);


                $json = array("status" => 1, "response" => 'reported');


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