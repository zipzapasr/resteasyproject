<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');
require_once('smtp-config.php');

$mail = new PHPMailer();
$autoresponder = new PHPMailer();
$smtpCreds = resteasy_smtp_credentials();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['contact-form-name'] != '' AND $_POST['contact-form-email'] != '' AND $_POST['contact-form-subject'] != '' ) {

        $name = $_POST['contact-form-name'];
        $email = $_POST['contact-form-email'];
        $subject = $_POST['contact-form-subject'];
        $phone = $_POST['contact-form-phone'];
        $message = $_POST['contact-form-message'];

		$subject = isset($subject) ? $subject : 'New Message From Contact Form';

		$botcheck = $_POST['contact-form-botcheck'];

        $toemail = array('bookings@resteasyservices.com.au');
        $toname = 'Rest Easy Services';

		if( $botcheck == '' ) {

			$mail->SetFrom( $smtpCreds['from_email'] , $smtpCreds['from_name'] );
			$mail->AddReplyTo( $email , $name );
			foreach ($toemail as $addr) {
				$mail->AddAddress( $addr , $toname );
			}
			$mail->Subject = $subject;

			$autoresponder->SetFrom( $smtpCreds['from_email'] , $smtpCreds['from_name'] );
			$autoresponder->AddReplyTo( $smtpCreds['from_email'] , $smtpCreds['from_name'] );
			$autoresponder->AddAddress( $email , $name );
			$autoresponder->Subject = 'We\'ve received your Email';

			$ar_body = "Thank you for contacting us. We will reply within 24 hours.<br><br>Regards,<br>Rest Easy Services.";

			$name = isset($name) ? "Name: $name<br><br>" : '';
			$email = isset($email) ? "Email: $email<br><br>" : '';
			$phone = isset($phone) ? "Phone: $phone<br><br>" : '';
			$message = isset($message) ? "Message: $message<br><br>" : '';

			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

			$body = "$name $email $phone $message $referrer";

			$autoresponder->MsgHTML( $ar_body );
			$mail->MsgHTML( $body );
			$smtpError = null;
			$sendEmail = resteasy_smtp_send($mail, $smtpError);

			if( $sendEmail == true ):
				resteasy_smtp_send($autoresponder, $smtpError);
				echo 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
			else:
				echo 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $smtpError . '';
			endif;
		} else {
			echo 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
		}
	} else {
		echo 'Please <strong>Fill up</strong> all the Fields and Try Again.';
	}
} else {
	echo 'An <strong>unexpected error</strong> occured. Please Try Again later.';
}

?>
