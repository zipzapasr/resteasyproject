<?php
$src = __DIR__ . '/../assets/images/about/about-rest.png';
$dst = __DIR__ . '/../assets/images/about/about-rest.webp';
$bg = __DIR__ . '/../assets/images/backgrounds/footer-top-bg3.jpg';
$bgDst = __DIR__ . '/../assets/images/backgrounds/footer-top-bg3.webp';

if (!function_exists('imagewebp')) {
    fwrite(STDERR, "GD webp not available\n");
    exit(1);
}

function convert_to_webp($src, $dst, $quality = 75) {
    $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));
    if ($ext === 'png') {
        $im = imagecreatefrompng($src);
        imagepalettetotruecolor($im);
        imagealphablending($im, true);
        imagesavealpha($im, true);
    } elseif ($ext === 'jpg' || $ext === 'jpeg') {
        $im = imagecreatefromjpeg($src);
    } else {
        return false;
    }
    $ok = imagewebp($im, $dst, $quality);
    imagedestroy($im);
    return $ok;
}

foreach ([[$src, $dst], [$bg, $bgDst]] as $pair) {
    if (!is_file($pair[0])) {
        echo "missing {$pair[0]}\n";
        continue;
    }
    if (convert_to_webp($pair[0], $pair[1], 72)) {
        echo basename($pair[0]) . ' ' . filesize($pair[0]) . ' -> ' . basename($pair[1]) . ' ' . filesize($pair[1]) . "\n";
    } else {
        echo "failed {$pair[0]}\n";
    }
}
