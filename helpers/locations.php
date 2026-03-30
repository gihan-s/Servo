<?php

function formatLocations(array $locations): string
{
    $FormattedLocations = [];

    foreach ($locations as $loc) {
        if ($loc['City'] === 'All' && $loc['District'] === 'All') {
            return 'All Districts';
        }

        if ($loc['City'] === 'All') {
            $FormattedLocations[] = $loc['District'] . ' District';
        } else {
            $FormattedLocations[] = $loc['City'];
        }
        
    }

    return implode(', ', array_unique($FormattedLocations));
}
