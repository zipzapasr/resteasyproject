<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');
require_once('smtp-config.php');

$mail = new PHPMailer();
$smtpCreds = resteasy_smtp_credentials();

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_name'] != '' AND $_POST['form_email'] != '' ) {

        $name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $message = $_POST['form_message'];

        $subject = isset($subject) ? $subject : 'New Message | Appointment Form';
        $phone = isset($_POST['form_phone']) ? $_POST['form_phone'] : '';

        $appontment_date = isset($_POST['form_appontment_date']) ? $_POST['form_appontment_date'] : '';

        $botcheck = $_POST['form_botcheck'];

        $toemail = array('bookings@resteasyservices.com.au');
        $toname = 'Rest Easy Services';

        if( $botcheck == '' ) {

            $mail->SetFrom( $smtpCreds['from_email'] , $smtpCreds['from_name'] );
            $mail->AddReplyTo( $email , $name );
            foreach ($toemail as $addr) {
                $mail->AddAddress( $addr , $toname );
            }
            $mail->Subject = $subject;

            $name = isset($name) ? "Name: $name<br><br>" : '';
            $email = isset($email) ? "Email: $email<br><br>" : '';
            $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
            $appontment_date = isset($appontment_date) ? "Appoinment Date: $appontment_date<br><br>" : '';
            $message = isset($message) ? "Message: $message<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$name $email $phone $appontment_date $message $referrer";

            $mail->MsgHTML( $body );
            $smtpError = null;
            $sendEmail = resteasy_smtp_send($mail, $smtpError);

            if( $sendEmail == true ):
                $message = 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
                $status = "true";
            else:
                $message = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $smtpError . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>Fill up</strong> all the Fields and Try Again.';
        $status = "false";
    }
} else {
    $message = 'An <strong>unexpected error</strong> occured. Please Try Again later.';
    $status = "false";
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>
