<?php
function minify_css($css) {
    $css = preg_replace('!/\*[^*]*\*+(?:[^/*][^*]*\*+)*/!', '', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    $css = preg_replace('/\s*([{};,:])\s*/', '$1', $css);
    $css = str_replace(';}', '}', $css);
    return trim($css);
}

$files = [
    'assets/css/style.css',
    'assets/css/responsive.css',
    'assets/css/color-1.css',
    'assets/vendors/animate/custom-animate.css',
    'assets/vendors/thm-icons/style.css',
];

foreach ($files as $f) {
    if (!is_file($f)) {
        echo "skip $f\n";
        continue;
    }
    $out = preg_replace('/\.css$/', '.min.css', $f);
    $src = file_get_contents($f);
    $min = minify_css($src);
    file_put_contents($out, $min);
    echo sprintf("%s %d -> %s %d (saved %d)\n", $f, strlen($src), $out, strlen($min), strlen($src) - strlen($min));
}
