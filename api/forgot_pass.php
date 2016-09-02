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

    if ((isset($_REQUEST['email']) && !empty($_REQUEST['email']))
    ) {

            $email = mysqli_real_escape_string($db, $_REQUEST['email']);

            $result = $db->query("SELECT * FROM `users` WHERE `email` = '$email' AND `login_type` = '0'");

            if ($result->num_rows == 1) {

                $data = $result->fetch_assoc();

                $pass = decrypt($data['password']);

                $to = $data['email'];
                $subject = "Password Remember";

                $message = "
                            <html>
                            <head>
                            <title>Password Remember</title>
                            </head>
                            <body>
                            <p>This email contains Sign In details!</p>
                            <table>
                            <tr>
                            <th>Username</th>
                            <td>" . $data['username'] . "</td>
                            </tr>
                            <tr>
                            <th>Password</th>
                            <td>". $pass ."</td>
                            </tr>
                            </table>
                            </body>
                            </html>
                            ";


                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                $headers .= 'From: <webmaster@journeyapp.tk>' . "\r\n";

                mail($to,$subject,$message,$headers);


                $json = array("status" => 1, "response" => 'email sent');

            } else {
                $json = array("status" => 0, "response" => "no account exists");
            }


    } else {
        $json = array("status" => 0, "response" => "invalid email address");
    }

} else {
    $json = array("status" => 0, "response" => "invalid api key");
}

/* Output header */
header('Content-type: application/json');
header("access-control-allow-origin: *");
echo json_encode($json);