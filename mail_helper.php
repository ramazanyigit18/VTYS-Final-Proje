<?php
function sendUserNotificationMail($email, $action)
{
    $subject = "";
    $message = "";

    if ($action == "INSERT") {
        $subject = "Hoş Geldiniz!";
        $message = "Merhaba,\n\nSistemimize başarıyla kaydoldunuz. Hoş geldiniz!";
    } elseif ($action == "DELETE") {
        $subject = "Hesabınız Silindi";
        $message = "Merhaba,\n\nHesabınız sistemimizden silinmiştir. Bilgi amaçlı bu maili alıyorsunuz.";
    }

    $headers = "From: noreply@siteniz.com\r\n" .
        "Content-Type: text/plain; charset=utf-8";

    mail($email, $subject, $message, $headers);
}
?>