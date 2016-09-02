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


    if ((isset($_REQUEST['uid']) && !empty($_REQUEST['uid']))) {

        $uid = mysqli_real_escape_string($db, $_REQUEST['uid']);

        $result = $db->query("SELECT * FROM `users` WHERE `uid` = '$uid'");

        if ($result->num_rows == 1) {

            $query = (isset($_REQUEST['query']) ? mysqli_real_escape_string($db, $_REQUEST['query']) : "");
            $category = (isset($_REQUEST['category']) ? mysqli_real_escape_string($db, $_REQUEST['category']) : "");
            $type = (isset($_REQUEST['type']) ? mysqli_real_escape_string($db, $_REQUEST['type']) : "");

            $sql = "";

            if (strlen($query) > 0) {

                $queString = "";

                $que = explode(',', $query);
                foreach ($que as $quer) {

                    if (strlen($quer) > 1) {

                        if (strlen($queString) > 1) {
                            $queString .= " OR ";
                        }

                        $sql .= "((`title` LIKE '%" . $quer . "%') OR (`description` LIKE '%" . $quer . "%') 
                         OR (`category` LIKE '%" . $quer . "%') OR (`tags` LIKE '%" . $quer . "%') 
                         OR (`specialty` LIKE '%" . $quer . "%') OR (`eventtype` LIKE '%" . $quer . "%') 
                         OR (`organizer` LIKE '%" . $quer . "%') OR (`place` LIKE '%" . $quer . "%'))";
                    }

                }

                $sql .= " ( " . $queString . " ) ";

            }


            if (strlen($category) > 0) {

                if (strlen($sql) > 1) {
                    $sql .= " AND ";
                }

                $catString = "";

                $cat = explode(',', $category);
                foreach ($cat as $curr) {

                    if (strlen($curr) > 1) {

                        if (strlen($catString) > 1) {
                            $catString .= " OR ";
                        }

                        $catString .= "`category` = '" . $curr . "'";
                    }

                }

                $sql .= " ( " . $catString . " ) ";
            }


            if (strlen($type) > 0) {
                if (strlen($sql) > 1 && substr($sql, -4) != "AND ") {
                    $sql .= " AND ";
                }

                $typeString = "";

                $cat = explode(',', $type);
                foreach ($cat as $curr) {

                    if (strlen($curr) > 1) {

                        if (strlen($typeString) > 1) {
                            $typeString .= " OR ";
                        }

                        $typeString .= "`eventtype` = '" . $curr . "'";
                    }

                }

                $sql .= " ( " . $typeString . " ) ";

            }

            if (strlen($sql) > 1) {
                $sql = " WHERE " . $sql;
            }

            var_dump($sql);

            $result = $db->query("SELECT * FROM `videos` " . $sql);

            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $line = array();
                $line['id'] = $row['id'];
                $line['video'] = $row['video'];
                $line['uid'] = $row['uid'];
                $line['category'] = $row['category'];
                $line['title'] = $row['title'];
                $line['description'] = $row['description'];
                $line['tags'] = $row['tags'];
                $line['specialty'] = $row['specialty'];
                $line['eventtype'] = $row['eventtype'];
                $line['organizer'] = $row['organizer'];
                $line['place'] = $row['place'];
                $line['date'] = $row['date'];
                $line['thumb'] = $row['thumb'];

                $rows[] = $line;

            }
            $json = array("status" => 1, "videos" => $rows);

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
