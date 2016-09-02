<?php
/**
 * Created by PhpStorm.
 * User: Dumidu
 * Date: 6/11/2016
 * Time: 8:07 PM
 */

/**
 * Login password encryption
 * Returns an encrypted & utf8-encoded
 * @param $pure_string
 * @return string
 */
function encrypt($pure_string)
{
    $cryptKey = "qJB0rGtIn5UB1xG03efyCp";

    $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $pure_string, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
    return ($qEncoded);
}

/**
 * Login Password decryption
 * Returns decrypted original string
 * @param $encrypted_string
 * @return string
 */
function decrypt($encrypted_string)
{
    $cryptKey = "qJB0rGtIn5UB1xG03efyCp";

    $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($encrypted_string), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
    return ($qDecoded);
}