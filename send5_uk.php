<?php
	require('../includes/class.phpmailer.php');
	require('../includes/class.smtp.php');
	
	if (!$_POST['g-recaptcha-response']) {
		echo "����������� �����";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit;
		}
	 else {
		//echo "��������� ���";
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$key = "6LcjQ3AUAAAAALoWEVpMPOgDPbZGOnoRZ5At0tqr";
		$query = $url.'?secret='.$key.'&response='.$_POST['g-recaptcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR'];

		$data = json_decode(file_get_contents($query));
		
		if ( $data->success == false){		
			echo "����� ������� �������";
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit;
			}
		
	if (isset($_POST['button'])) $send = $_POST['button'];
	if (isset($_POST['name'])) $name = $_POST['name'];
	if (isset($_POST['mail'])) $mail = $_POST['mail'];
	if (isset($_POST['text'])) $text = $_POST['text'];

	$to = "Guide@toursdekiev.com.ua"; // ������� ���� �����
	$subject = "$name order from ToursDeKiev";        // ������� ���y ���������
	$reply_to=$mail;
	$text .= "\n��������� ���: ".$_SERVER['HTTP_REFERER'];
    $msg = "��'� ���������: $name\nE-mail ���������: $mail\n�����������: $text";

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
		$mess= "��������� $name, ���� ��������� ���� ������� �����������."; 
 
		echo '<META HTTP-EQUIV=Refresh Content="1; URL=http://toursdekiev.com.ua/uk/Thanks">';
	}
}

die();

?>