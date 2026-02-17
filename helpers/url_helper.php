<?php
// Simple page redirect
function redirect($page) {
    header('location: ' . URLROOT . '/' . $page);
    exit;
}

// Generate a URL for site assets
function assets($path) {
    return URLROOT . '/public/' . $path;
}

// Clean URL strings for SEO
function slugify($text) {
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Trim
    $text = trim($text, '-');

    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // Lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}