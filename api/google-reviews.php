<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

require_once dirname(__DIR__) . '/includes/google-reviews-fetch.php';

$forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] === '1';
$data = resteasy_fetch_google_reviews($forceRefresh);
if ($data === null || empty($data['reviews'])) {
    http_response_code(502);
    echo json_encode(array('error' => 'Unable to fetch Google reviews.'));
    exit;
}

echo json_encode($data);
