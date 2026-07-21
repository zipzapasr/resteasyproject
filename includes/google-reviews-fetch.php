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

function resteasy_normalize_legacy_google_reviews($data)
{
    $result = isset($data['result']) && is_array($data['result']) ? $data['result'] : array();

    $output = array(
        'name' => isset($result['name']) ? $result['name'] : '',
        'rating' => isset($result['rating']) ? $result['rating'] : null,
        'reviewCount' => isset($result['user_ratings_total']) ? $result['user_ratings_total'] : null,
        'reviews' => array(),
    );

    if (empty($result['reviews']) || !is_array($result['reviews'])) {
        return $output;
    }

    foreach ($result['reviews'] as $review) {
        $text = '';
        if (!empty($review['text'])) {
            $text = $review['text'];
        } elseif (!empty($review['original_text'])) {
            $text = $review['original_text'];
        }

        $output['reviews'][] = array(
            'author_name' => !empty($review['author_name']) ? $review['author_name'] : 'Google user',
            'profile_photo_url' => !empty($review['profile_photo_url']) ? $review['profile_photo_url'] : '',
            'relative_time_description' => !empty($review['relative_time_description'])
                ? $review['relative_time_description']
                : '',
            'text' => $text,
            'rating' => !empty($review['rating']) ? (int) $review['rating'] : 5,
            'review_url' => !empty($review['author_url']) ? $review['author_url'] : '',
        );
    }

    return $output;
}

function resteasy_google_reviews_http_get($url, $headers)
{
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
        return null;
    }

    return $response;
}

function resteasy_get_curated_reviews_file()
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'google-reviews-curated.json';
}

function resteasy_load_curated_google_reviews()
{
    $file = resteasy_get_curated_reviews_file();
    if (!is_readable($file)) {
        return array();
    }

    $data = json_decode(@file_get_contents($file), true);
    if (!is_array($data)) {
        return array();
    }
    if (isset($data['reviews']) && is_array($data['reviews'])) {
        $data = $data['reviews'];
    }

    $reviews = array();
    foreach ($data as $review) {
        if (!is_array($review) || empty($review['text'])) {
            continue;
        }
        $reviews[] = array(
            'author_name' => !empty($review['author_name']) ? $review['author_name'] : 'Google user',
            'profile_photo_url' => !empty($review['profile_photo_url']) ? $review['profile_photo_url'] : '',
            'relative_time_description' => !empty($review['relative_time_description'])
                ? $review['relative_time_description']
                : '',
            'text' => $review['text'],
            'rating' => !empty($review['rating']) ? (int) $review['rating'] : 5,
            'review_url' => !empty($review['review_url']) ? $review['review_url'] : '',
        );
    }

    return $reviews;
}

function resteasy_google_review_dedupe_key($review)
{
    $name = isset($review['author_name']) ? strtolower(trim($review['author_name'])) : '';
    $text = isset($review['text']) ? strtolower(trim(preg_replace('/\s+/', ' ', $review['text']))) : '';
    if (function_exists('mb_substr')) {
        $text = mb_substr($text, 0, 40);
    } else {
        $text = substr($text, 0, 40);
    }
    return $name . '|' . $text;
}

function resteasy_merge_google_reviews($primary, $secondary, $limit = 15)
{
    $merged = array();
    $seen = array();

    foreach (array_merge($primary, $secondary) as $review) {
        if (!is_array($review) || empty($review['text'])) {
            continue;
        }
        $key = resteasy_google_review_dedupe_key($review);
        if (isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;
        $merged[] = $review;
        if (count($merged) >= $limit) {
            break;
        }
    }

    return $merged;
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

    // Separate key used ONLY for the legacy Place Details web service below.
    // IMPORTANT: the legacy API rejects keys that have HTTP-referrer restrictions
    // ("API keys with referer restrictions cannot be used with this API"). This key
    // must be either unrestricted or restricted by the server's IP address, and must
    // have the (legacy) "Places API" enabled in Google Cloud. Leave empty to skip the
    // legacy call and use the Places API (New) below instead (most-relevant reviews).
    $legacyApiKey = '';

    $output = null;

    // 1) Legacy Place Details API. This is the ONLY Google endpoint that supports
    //    reviews_sort=newest, so it returns the 5 most recent reviews. The Places
    //    API (New) always returns the 5 "most relevant" reviews and ignores any sort.
    if ($legacyApiKey !== '') {
        $legacyUrl = 'https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query(array(
            'place_id' => $placeId,
            'reviews_sort' => 'newest',
            'reviews_no_translations' => 'true',
            'fields' => 'name,rating,user_ratings_total,reviews',
            'key' => $legacyApiKey,
        ));
        $legacyResponse = resteasy_google_reviews_http_get($legacyUrl, array());
        if ($legacyResponse !== null) {
            $legacyData = json_decode($legacyResponse, true);
            if (is_array($legacyData) && isset($legacyData['status']) && $legacyData['status'] === 'OK') {
                $normalized = resteasy_normalize_legacy_google_reviews($legacyData);
                if (!empty($normalized['reviews'])) {
                    $output = $normalized;
                }
            }
        }
    }

    // 2) Fall back to the Places API (New) if the legacy API is unavailable
    //    (e.g. not enabled on the project). Reviews will be "most relevant" here.
    if ($output === null) {
        $url = 'https://places.googleapis.com/v1/places/' . rawurlencode($placeId);
        $headers = array(
            'X-Goog-Api-Key: ' . $apiKey,
            'X-Goog-FieldMask: displayName,rating,userRatingCount,reviews',
            'Referer: ' . $referer,
        );
        $response = resteasy_google_reviews_http_get($url, $headers);
        if ($response !== null) {
            $data = json_decode($response, true);
            if (is_array($data)) {
                $normalized = resteasy_normalize_google_reviews($data);
                if (!empty($normalized['reviews'])) {
                    $output = $normalized;
                }
            }
        }
    }

    // Curated reviews let us show more than Google's hard cap of 5 reviews, and
    // guarantee the newest reviews appear even when the API returns "most relevant".
    $curated = resteasy_load_curated_google_reviews();

    if ($output === null && empty($curated)) {
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

    if ($output === null) {
        $output = array('name' => '', 'rating' => null, 'reviewCount' => null, 'reviews' => array());
    }

    if (!empty($curated)) {
        // Curated reviews are listed first (newest-first, owner-controlled order);
        // any additional live reviews not already curated are appended.
        $output['reviews'] = resteasy_merge_google_reviews($curated, $output['reviews'], 15);
    }

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
