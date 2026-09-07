<?php
/**
 * Google Form + enquiry email settings (used by contact forms site-wide).
 */
// Who receives website enquiries (main form uses sendemail.php)
$resteasyFormRecipientEmail = array(
    'bookings@resteasyservices.com.au',
);
$resteasyGoogleForm = array(
    'action' => 'https://docs.google.com/forms/d/e/1FAIpQLSdCIbBIsKXWtOcwRe28oKjqaYnTl6guL9RDVwQ7PWZQjdRPrA/formResponse',
    'fbzx' => '5596094188197341058',
    'fields' => array(
        'name' => 'entry.1851744550',
        'email' => 'entry.317856632',
        'phone' => 'entry.2075248938',
        'suburb' => 'entry.1778987614',
        'service' => 'entry.814260044',
        'message' => 'entry.230954203',
    ),
);
$resteasyEnquiryEmailUrl = '/assets/inc/sendemail.php';
