<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require_once 'SimpleXLSX.php';

$isFirst = true;
if ($xlsx = SimpleXLSX::parse($xlsxPath)) {
    $data = $xlsx->rows();
    foreach($data as $row) {
        if ($isFirst) {
            $isFirst = false;
        } else {
            $name = $row[1];
            $email = $row[2];
            $id = $row[3];
            $bool = $row[4];
            if ($bool === 1 && $name != null && $email != null && $id != null) {
                $path = "$dirPath". '/' ."$id". '*.pdf';
                $file = glob($path);
                $file = basename($file[0]);
                $attachment = "$dirPath". '/' ."$file";

                $mail = new PHPMailer(true);
                try {

                    $mail->CharSet = 'UTF-8';
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host       = '';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = '';
                    $mail->Password   = '';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port       = 465;

                    $mail->setFrom('', '');
                    $mail->addAddress($email);
                    //$mail->addAddress('receiver2@gfg.com', 'Name');

                    $mail->addAttachment($attachment);

                    $mail->isHTML(true);
                    $mail->Subject = "$name" . ' rēķins';
                    $mail->Body    = 'Ikmēneša rēķins par pakalpojumiem <b>' . $name . '</b> ';
                    $mail->AltBody = 'Ikmēneša rēķins par pakalpojumiem ' . $name;
                    $mail->send();

                    //-----success log-----
                    date_default_timezone_set('Europe/Riga');
                    $log = fopen("log.txt", "a") or die("Can't open file");
                    $txt = date('Y-m-d H:i')."; ".$name."; ".$email."; ".$file."; "."rēķins nosūtīts.\r\n";
                    fwrite($log, $txt);
                    fclose($log);
                    //-----success log-----

                    echo "<span style='color:#0c5c0c;'>Rēķins veiksmīgi nosūtīts! Adrese: ".$email."</span><br>";
                } catch (Exception $e) {

                    //-----error log-----
                    date_default_timezone_set('Europe/Riga');
                    $log = fopen("log.txt", "a") or die("Can't open file");
                    $txt = date('Y-m-d H:i')."; ".$name."; ".$email."; ".$file."; "."Error: {$mail->ErrorInfo}\r\n";
                    fwrite($log, $txt);
                    fclose($log);
                    //-----error log-----

                    echo "<span style='color:red'>Rēķins uz: ".$email." nevar tikt nosūtīts. Error: {$mail->ErrorInfo}</span><br>";
                }
            }
        }
    }
}

?>