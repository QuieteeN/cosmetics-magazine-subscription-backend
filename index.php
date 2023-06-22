<?php 

    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';
    require 'phpmailer/Exception.php';

    $params = json_decode(file_get_contents('php://input'), true);

    $fio            = $params['fio'];
    $birthday       = $params['birthday'];
    $phoneNumber    = $params['phoneNumber'];
    $email          = $params['mail'];
    $address        = $params['address'];

    // Decoding Symbols
    $fio            = htmlspecialchars($fio);
    $birthday       = htmlspecialchars($birthday);
    $phoneNumber    = htmlspecialchars($phoneNumber);
    $email          = htmlspecialchars($email);
    $address        = htmlspecialchars($address);

    // Decoding url
    $fio            = urldecode($fio);
    $birthday       = urldecode($birthday);
    $phoneNumber    = urldecode($phoneNumber);
    $email          = urldecode($email);
    $address        = urldecode($address);

    // Delete Spaces in Start and End
    $fio            = trim($fio);
    $birthday       = trim($birthday);
    $phoneNumber    = trim($phoneNumber);
    $email          = trim($email);
    $address        = trim($address);


    $title = "Новый зарегестрированный";

    $body = "
    <b>ФИО:</b> $fio<br>
    <b>Почта:</b> $email<br>
    <b>Дата рождения(год, месяц, день):</b> $birthday<br>
    <b>Номер телефона:</b> $phoneNumber<br>
    <b>Адрес:</b> $address<br><br>";

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
        $mail->isSMTP();   
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth   = true;
        //$mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};
    
        // Настройки вашей почты
        $mail->Host       = 'smtp.mail.ru'; // SMTP сервера вашей почты
        $mail->Username   = 'jack.johnson.95@inbox.ru'; // Логин на почте
        $mail->Password   = 'aunzqbWXPnmvQqpUbzk6'; // Пароль на почте
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('jack.johnson.95@inbox.ru', ''.$fio); // Адрес самой почты и имя отправителя
    
        // Получатель письма
        $mail->addAddress('jack.johnson.95@inbox.ru');  
    
        // Отправка сообщения
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $body;    
        
        // Проверяем отравленность сообщения
        if ($mail->send()) {
            $result = "success";
            $status = "Сообщение отправлено.";
        } 
        else {$result = "error";}
    
    } catch (Exception $e) {
        $result = "error";
        $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
    }
    
    // Отображение результата
    echo json_encode(["result" => $result, "status" => $status]);

?>
