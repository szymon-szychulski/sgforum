<?php

/*
	mailer class
*/

namespace SGF;
use SGF;

if (!defined('SGF')) exit('what are you doing here?');

class Mailer {
	protected static $mail;

	public static function connect($data='') {
		if (!self::$mail) {
			require_once Config::$vars['paths']['libs'] . '/PHPMailer/PHPMailerAutoload.php';

			self::$mail = new \PHPMailer;

			if (empty($data)) {
				$data = Config::$vars['mail'];
			}

			self::$mail->CharSet = $data['charset'];

			self::$mail->isSMTP();
			self::$mail->Host = $data['host']; 
			self::$mail->SMTPAuth = true;
			self::$mail->Username = $data['user'];
			self::$mail->Password = $data['pass'];
			self::$mail->SMTPSecure = 'tls';
			self::$mail->Port = $data['port'];
		}
	}

	public static function simple_prepare($to_name, $to_email, $subject, $body) {
		self::$mail->setFrom('no-reply@' . Config::$vars['names']['domain'], Config::$vars['names']['domain']);

		self::$mail->addAddress($to_name, $to_email);

		self::$mail->isHTML(true);

		self::$mail->Subject = $subject;

		self::$mail->Body = $body;

		self::$mail->AltBody = strip_tags(str_replace('<br>', '\n', self::$mail->Body));
	}

	public static function send() {
		return self::$mail->send();
	}

	public static function showError() {
		self::$mail->SMTPDebug = 3;
		return '<p>Mailer error: ' . self::$mail->ErrorInfo . '</p>';
	}
}
?>