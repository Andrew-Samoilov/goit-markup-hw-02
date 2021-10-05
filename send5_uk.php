<?php
	require('../includes/class.phpmailer.php');
	require('../includes/class.smtp.php');
	
	if (!$_POST['g-recaptcha-response']) {
		echo "Неправильна капча";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit;
		}
	 else {
		//echo "Правильно УРА";
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$key = "6LcjQ3AUAAAAALoWEVpMPOgDPbZGOnoRZ5At0tqr";
		$query = $url.'?secret='.$key.'&response='.$_POST['g-recaptcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR'];

		$data = json_decode(file_get_contents($query));
		
		if ( $data->success == false){		
			echo "Капча введена неверно";
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit;
			}
		
	if (isset($_POST['button'])) $send = $_POST['button'];
	if (isset($_POST['name'])) $name = $_POST['name'];
	if (isset($_POST['mail'])) $mail = $_POST['mail'];
	if (isset($_POST['text'])) $text = $_POST['text'];

	$to = "Guide@toursdekiev.com.ua"; // вставте свой емаил
	$subject = "$name order from ToursDeKiev";        // вставте Темy сообщения
	$reply_to=$mail;
	$text .= "\nЗамовлено тур: ".$_SERVER['HTTP_REFERER'];
    $msg = "Им'я замовника: $name\nE-mail замовника: $mail\nПовідомлення: $text";

	if ($send != "button"){
		try{
			$mailer = new PHPMailer();
			$mailer->isSMTP();
				// Debug options
			$mailer->SMTPDebug  = 0; 
			$mailer->Debugoutput = 'html';
			$mailer->CharSet = "windows-1251";
				 
			$mailer->Host = 'smtp.yandex.ru';
			$mailer->Port = 465;
			$mailer->SMTPSecure = 'ssl';
			$mailer->SMTPAuth   = true;
			$mailer->Username   = "order@toursdekiev.com.ua";
			$mailer->Password   = "eolizerorder19";
			$mailer->SetFrom('order@toursdekiev.com.ua', 'Order from toursdekiev.com.ua');
			$mailer->AddAddress($to,'Guide');
			$mailer->AddReplyTo($reply_to);
			$mailer->Subject=$subject;
			$mailer->IsHTML(false);
			$mailer->Body=$msg;

			if (!$mailer->Send()) throw new Exception($mailer->ErrorInfo);
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
		$mess= "Уважаемый $name, ваше сообщение было успешно отправленно."; 
 
		echo '<META HTTP-EQUIV=Refresh Content="1; URL=http://toursdekiev.com.ua/uk/Thanks">';
	}
}

die();

?>