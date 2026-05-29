<?php

function resteasy_get_google_reviews_cache_file()
{
    $cacheDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache';
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }
    return $cacheDir . DIRECTORY_SEPARATOR . 'google-reviews.json';
}

function resteasy_normalize_google_reviews($data)
{
    $output = array(
        'name' => isset($data['displayName']['text']) ? $data['displayName']['text'] : '',
        'rating' => isset($data['rating']) ? $data['rating'] : null,
        'reviewCount' => isset($data['userRatingCount']) ? $data['userRatingCount'] : null,
        'reviews' => array(),
    );

    if (empty($data['reviews']) || !is_array($data['reviews'])) {
        return $output;
    }

    foreach ($data['reviews'] as $review) {
        $text = '';
        if (!empty($review['text']['text'])) {
            $text = $review['text']['text'];
        } elseif (!empty($review['originalText']['text'])) {
            $text = $review['originalText']['text'];
        }

        $reviewUrl = '';
        if (!empty($review['googleMapsUri'])) {
            $reviewUrl = $review['googleMapsUri'];
        } elseif (!empty($review['authorAttribution']['uri'])) {
            $reviewUrl = $review['authorAttribution']['uri'];
        }

        $output['reviews'][] = array(
            'author_name' => !empty($review['authorAttribution']['displayName'])
                ? $review['authorAttribution']['displayName']
                : 'Google user',
            'profile_photo_url' => !empty($review['authorAttribution']['photoUri'])
                ? $review['authorAttribution']['photoUri']
                : '',
            'relative_time_description' => !empty($review['relativePublishTimeDescription'])
                ? $review['relativePublishTimeDescription']
                : '',
            'text' => $text,
            'rating' => !empty($review['rating']) ? (int) $review['rating'] : 5,
            'review_url' => $reviewUrl,
        );
    }

    return $output;
}

function resteasy_fetch_google_reviews($forceRefresh = false)
{
    static $cachedResult = null;

    if ($cachedResult !== null && !$forceRefresh) {
        return $cachedResult;
    }

    $cacheFile = resteasy_get_google_reviews_cache_file();
    $cacheTtl = 3600;

    if (!$forceRefresh && is_readable($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
        $cachedJson = @file_get_contents($cacheFile);
        $cachedData = json_decode($cachedJson, true);
        if (is_array($cachedData) && !empty($cachedData['reviews'])) {
            $cachedResult = $cachedData;
            return $cachedResult;
        }
    }

    $placeId = 'ChIJ20vQ2H_K1WoROevGTy-Tj8Y';
    $apiKey = 'AIzaSyCC_r5F1eC4o7ct4filjaurPn1Zxcre_Kk';
    $referer = 'https://resteasyservices.com.au/';
    $url = 'https://places.googleapis.com/v1/places/' . rawurlencode($placeId);
    $headers = array(
        'X-Goog-Api-Key: ' . $apiKey,
        'X-Goog-FieldMask: displayName,rating,userRatingCount,reviews',
        'Referer: ' . $referer,
    );

    $response = null;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 15,
        ));
        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false || $httpCode >= 400) {
            $response = null;
        }
    }

    if ($response === null) {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
                'timeout' => 15,
                'ignore_errors' => true,
            ),
        ));
        $response = @file_get_contents($url, false, $context);
    }

    if ($response === false || $response === null || $response === '') {
        if (is_readable($cacheFile)) {
            $cachedJson = @file_get_contents($cacheFile);
            $cachedData = json_decode($cachedJson, true);
            if (is_array($cachedData)) {
                $cachedResult = $cachedData;
                return $cachedResult;
            }
        }
        return null;
    }

    $data = json_decode($response, true);
    if (!is_array($data)) {
        return null;
    }

    $output = resteasy_normalize_google_reviews($data);
    $json = json_encode($output);
    if ($json !== false) {
        @file_put_contents($cacheFile, $json);
    }

    $cachedResult = $output;
    return $cachedResult;
}

function resteasy_truncate_review_text($text, $max = 320)
{
    if ($text === '') {
        return '';
    }
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text) <= $max) {
            return $text;
        }
        return rtrim(mb_substr($text, 0, $max)) . '…';
    }
    if (strlen($text) <= $max) {
        return $text;
    }
    return rtrim(substr($text, 0, $max)) . '…';
}
