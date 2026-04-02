<?php

/**
 * Social Media Icons Configuration
 * 
 * This file stores social media icon configurations that can be accessed
 * by social media type throughout the application.
 */

// Social Media Icons Configuration
const SOCIAL_MEDIA_ICONS = [
    'facebook' => [
        'name' => 'Facebook',
        'icon_class' => 'fa-facebook',
        'color' => '#1877F2',
        'url_pattern' => 'https://facebook.com/',
    ],
    'twitter' => [
        'name' => 'Twitter',
        'icon_class' => 'fa-twitter',
        'color' => '#1DA1F2',
        'url_pattern' => 'https://twitter.com/',
    ],
    'instagram' => [
        'name' => 'Instagram',
        'icon_class' => 'fa-instagram',
        'color' => '#E4405F',
        'url_pattern' => 'https://instagram.com/',
    ],
    'linkedin' => [
        'name' => 'LinkedIn',
        'icon_class' => 'fa-linkedin',
        'color' => '#0A66C2',
        'url_pattern' => 'https://linkedin.com/in/',
    ],
    'youtube' => [
        'name' => 'YouTube',
        'icon_class' => 'fa-youtube',
        'color' => '#FF0000',
        'url_pattern' => 'https://youtube.com/@',
    ],
    'github' => [
        'name' => 'GitHub',
        'icon_class' => 'fa-github',
        'color' => '#333333',
        'url_pattern' => 'https://github.com/',
    ],
    'tiktok' => [
        'name' => 'TikTok',
        'icon_class' => 'fa-tiktok',
        'color' => '#000000',
        'url_pattern' => 'https://tiktok.com/@',
    ],
    'pinterest' => [
        'name' => 'Pinterest',
        'icon_class' => 'fa-pinterest',
        'color' => '#E60023',
        'url_pattern' => 'https://pinterest.com/',
    ],
    'whatsapp' => [
        'name' => 'WhatsApp',
        'icon_class' => 'fa-whatsapp',
        'color' => '#25D366',
        'url_pattern' => 'https://wa.me/',
    ],
];

/**
 * Get social media icon by type
 * 
 * @param string $type The social media type (e.g., 'facebook', 'twitter')
 * @return array|null The icon data array or null if not found
 */
function getSocialMediaIcon($type)
{
    $type = strtolower($type);
    return SOCIAL_MEDIA_ICONS[$type] ?? null;
}

/**
 * Get all social media icons
 * 
 * @return array All social media icons configuration
 */
function getAllSocialMediaIcons()
{
    return SOCIAL_MEDIA_ICONS;
}

/**
 * Get social media icon class
 * 
 * @param string $type The social media type
 * @return string|null The icon class (e.g., 'fa-facebook') or null if not found
 */
function getSocialMediaIconClass($type)
{
    $icon = getSocialMediaIcon($type);
    return $icon['icon_class'] ?? null;
}

/**
 * Get social media name
 * 
 * @param string $type The social media type
 * @return string|null The social media name or null if not found
 */
function getSocialMediaName($type)
{
    $icon = getSocialMediaIcon($type);
    return $icon['name'] ?? null;
}

/**
 * Get social media color
 * 
 * @param string $type The social media type
 * @return string|null The social media brand color or null if not found
 */
function getSocialMediaColor($type)
{
    $icon = getSocialMediaIcon($type);
    return $icon['color'] ?? null;
}

/**
 * Get social media URL pattern
 * 
 * @param string $type The social media type
 * @return string|null The URL pattern or null if not found
 */
function getSocialMediaUrlPattern($type)
{
    $icon = getSocialMediaIcon($type);
    return $icon['url_pattern'] ?? null;
}

/**
 * Build complete social media URL
 * 
 * @param string $type The social media type
 * @param string $handle The user's handle/path
 * @return string|null The complete URL or null if type not found
 */
function buildSocialMediaUrl($type, $handle)
{
    $pattern = getSocialMediaUrlPattern($type);
    if (!$pattern) {
        return null;
    }
    return $pattern . $handle;
}

?>
