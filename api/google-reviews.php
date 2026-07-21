<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=3600');

require_once dirname(__DIR__) . '/includes/google-reviews-fetch.php';

$data = resteasy_fetch_google_reviews();
if ($data === null || empty($data['reviews'])) {
    http_response_code(502);
    echo json_encode(array('error' => 'Unable to fetch Google reviews.'));
    exit;
}

echo json_encode($data);
