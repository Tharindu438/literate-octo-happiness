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

    if (isset($_REQUEST['uid']) && isset($_REQUEST['video']) && isset($_REQUEST['category'])
        && !empty($_REQUEST['uid']) && !empty($_REQUEST['video']) && !empty($_REQUEST['category'])
    ) {

        $uid = $_REQUEST['uid'];
        $video = mysqli_real_escape_string($db, $_REQUEST['video']);
        $category = mysqli_real_escape_string($db, $_REQUEST['category']);
        $title = mysqli_real_escape_string($db, $_REQUEST['title']);
        $specialty = mysqli_real_escape_string($db, $_REQUEST['specialty']);
        $eventtype = mysqli_real_escape_string($db, $_REQUEST['eventtype']);
        $organizer = mysqli_real_escape_string($db, $_REQUEST['organizer']);
        $place = mysqli_real_escape_string($db, $_REQUEST['place']);
        $date = mysqli_real_escape_string($db, $_REQUEST['date']);
	$description = mysqli_real_escape_string($db, $_REQUEST['description']);
	$tags = mysqli_real_escape_string($db, $_REQUEST['tags']);


        $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

        if ($result->num_rows == 1) {

            $result = $db->query("SELECT * FROM `categories` WHERE `name` = '$category'");

            if ($result->num_rows == 1) {

                $file_tmp = $_FILES['file']['tmp_name'];
                $filename1 = time() . $video;
                move_uploaded_file($file_tmp,"videos/".$filename1);
                //file_put_contents("videos/".$filename, $fileData);
                $filename = "http://52.41.13.192/api/videos/" . $filename1;

$ffmpeg = '/usr/bin/ffmpeg';
$video =  'videos/'. $filename1;
$thumbnail = 'videos/thumb/thumb'. time(). '.png';
$interval = 3;
$size = '640x480';
exec("$ffmpeg -i $video -deinterlace -an -ss $interval -f mjpeg -t 1 -r 1 -y -s $size $thumbnail 2>&1");
//exec($cmd);
//echo $cmd;
$thumbfile = "http://52.41.13.192/api/". $thumbnail;

                $query = $db->query(sprintf("INSERT INTO `videos`(`video`, `uid`, `category`, `title`, `specialty`, `eventtype`, `organizer`, `place`, `date`, `thumb`, `description`, `tags`) 
VALUES ('$filename', '$uid', '$category', '$title', '$specialty', '$eventtype', '$organizer', '$place', '$date', '$thumbfile', '$description', '$tags')"));

                if ($query) {
                    $json = array("status" => 1, "response" => "success");
                } else {
                    $json = array("status" => 0, "response" => "server error");
                }

            } else {
                $json = array("status" => 0, "response" => "invalid category name");
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
