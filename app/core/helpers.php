<?php
function timeAgo($date) {
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