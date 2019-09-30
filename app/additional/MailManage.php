<?php

namespace app\additional;

class MailManage {
    
    private static function createLinkWithCode($link, $data) {
        $result = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'];
        $result .= $link;
        $result .= $data;
        return $result;
    }
    
    private static function send($mail_to, $mail_subject, $mail_message) {
        $encoding = "utf-8";

        $subject_preferences = array(
            "input-charset" => $encoding,
            "output-charset" => $encoding,
            "line-length" => 76,
            "line-break-chars" => "\r\n"
        );
        $from_mail = "noreply@".$_SERVER['HTTP_HOST'];
        $from_name = "noreply";
        $header = "Content-type: text/html; charset=".$encoding."\r\n";
        $header .= "From: ".$from_name." <".$from_mail.">"."\r\n";
        $header .= "Reply-To: ".$from_mail."\r\n";
        $header .= "MIME-Version: 1.0"."\r\n";
        $header .= "X-Mailer: PHP/".phpversion()."\r\n";
        $header .= "Content-Transfer-Encoding: 8bit"."\r\n";
        $header .= "Date: ".date("r (T)")."\r\n";
        $header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

        mail($mail_to, $mail_subject, $mail_message, $header);
    }
    public static function registerConfirmation($email, $token) {
        $path = "/activate";
        $data = "?email=".$email."&token=".$token;

        $link = self::createLinkWithCode($path, $data);
        $subject = "Camagru registration!";
        $message = "<a href=".$link.">Confirm</a> your registration!<br>";
        self::send($email, $subject, $message);
    }

    public static function restorePasswordConfirmation($email, $token) {
        $path = "/restore_pass";
        $data = "?email=".$email."&token=".$token;

        $link = self::createLinkWithCode($path, $data);
        $subject = "Camagru restore password!";
        $message = "<a href=".$link.">Restore</a> your password!<br>";
        self::send($email, $subject, $message);
    }

    public static function likeNotification($email) {
        $subject = "Camagru like!";
        $message = "New like at one of your photo!";
        self::send($email, $subject, $message);
    }

    public static function messageNotification($email) {
        $subject = "Camagru message!";
        $message = "New message at one of your photo!";
        self::send($email, $subject, $message);
    }
}