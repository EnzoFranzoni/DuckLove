<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Functions {

    public static function encryptDataMd5($string) {
        return md5($string);
    }

    public static function generateUsername($lastname, $firstname) {
        $lastname = Functions::formatString($lastname);
        $firstname = Functions::formatString($firstname);
        $firstname = Functions::truncateString($firstname, 1);
        $username = $firstname . $lastname;
        $username = Functions::truncateString($username, 12);
        return $username;
    }

    public static function generateUserPassword($length) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $password[] = $alphabet[$n];
        }
        return implode($password);
    }

    public static function formatString($string) {
        $search = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'];
        $replac = ['A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'];
        $string = str_replace($search, $replac, $string);
        $string = preg_replace('/([^.a-z]+)/i', '', $string);
        $string = preg_replace('/\s+/', '', $string);
        $string = trim($string);
        $string = strtolower($string);
        return $string;
    }

    public static function truncateString($string, $length) {
        return substr($string, 0, $length);
    }

    public static function checkNull($string) {
        if (is_null($string)) {
            return true;
        } elseif (is_string($string)) {
            $string = strtolower($string);
            return ($string == 'null');
        }
        return false;
    }

    public static function checkInteger($string) {
        if (is_int($string)) {
            return true;
        } elseif (is_string($string)) {
            return ctype_digit($string);
        }
        return false;
    }

    public static function checkStringOrNull($string) {
        return ($string == '' || Functions::checkNull($string));
    }

    public static function checkEmail($string) {
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }

    public static function sendMail($recipient, $subject, $htmlBody, $textBody = null, $mode = true) {
        $mail = new PHPMailer;

        $mail->setLanguage('fr', 'vendor/phpmailer/phpmailer/language/');

        $mail->CharSet = 'utf-8';

        if (DEBUG) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        }
        $mail->isSMTP();
        $mail->Host = sm_host;
        $mail->SMTPAuth = true;
        $mail->Username = sm_user;
        $mail->Password = sm_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = sm_port;

        $mail->setFrom(sm_user, PROJECT_NAME);
        $mail->addAddress($recipient);

        $mail->isHTML($mode);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        if ($textBody !== null && $mode) {
            $mail->AltBody = $textBody;
        }

        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }

        return true;
    }

}
