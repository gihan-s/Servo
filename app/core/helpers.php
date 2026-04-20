<?php
function timeAgo($date) {
    if ($date == NULL) return NULL;
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    if ($diff < 60) return $diff . ' seconds ago';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    if ($diff < 2629746) return floor($diff / 604800) . ' weeks ago';
    return date('M d, Y', $timestamp);
}

function timeLeft($date) {
    if ($date == NULL) return NULL;
    $timestamp = strtotime($date);
    $diff = $timestamp - time();
    if ($diff <= 0) return 'Expired';
    if ($diff < 60) return $diff . ' seconds left';
    if ($diff < 3600) return floor($diff / 60) . ' minutes left';
    if ($diff < 86400) return floor($diff / 3600) . ' hours left';
    if ($diff < 604800) return floor($diff / 86400) . ' days left';
    if ($diff < 2629746) return floor($diff / 604800) . ' weeks left';
    return date('M d, Y', $timestamp);
}

function formatDuration($date) {
    if ($date == NULL) return NULL;
    $timestamp = strtotime($date);
    $diff = $timestamp - time();
    if ($diff <= 0) return '0 days';
    $days = (int) floor($diff / 86400);
    if ($days == 1) return '1 day';
    if ($days < 7) return $days . ' days';
    $weeks = (int) floor($days / 7);
    if ($weeks == 1) return '1 week';
    if ($weeks < 4) return $weeks . ' weeks';
    $months = (int) floor($days / 30);
    return $months . ' month' . ($months > 1 ? 's' : '');
}

function formatCurrency($amount) {
    if ($amount === NULL) return NULL;
    return 'LKR ' . number_format($amount, 0);
}

function formatFullName($firstName, $lastName) {
    $parts = array_filter([trim($firstName), trim($lastName)]);
    return implode(' ', $parts) ?: NULL;
}