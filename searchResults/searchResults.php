<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="searchResultsStyle.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <title>Document</title>
</head>

<body>
    <div class="search-section">
        <div class="search-header">
            <div class="search-button">
                <input type="text" placeholder="Search for services">
                <button><i class="fa-light fa-magnifying-glass"></i></button>
            </div>
            <button class="filter"><i class="fa-light fa-filter-list"></i></button>
            <div class="sort-selection">
                <div class="selection-input-field">
                    <input type="selection-input" id="selection-input" name="sort" value="Sort By Relevence" disabled><i
                        class="fa-light fa-chevron-down"></i>
                </div>
                <div class="selection-options" id="selection-options">
                    <div class="opt">Sort By Relevence</div>
                    <div class="opt">Sort By Price</div>
                    <div class="opt">Sort By Rating</div>
                </div>
            </div>
        </div>
        <div class="search-bottom">
            <div class="search-filters">
                <div class="main-title"><span>Filters</span><hr></div>
                <div class="filter-item">
                    <div class="filter-title"><span>Hourly rate</span><i
                        class="fa-light fa-chevron-down rotated"></i></div>
                    <ul class="filter-options active radios">
                        <li><input type="radio" name="rate" id="rate" checked>Any hourly rate</li>
                        <li><input type="radio" name="rate" id="rate">Less than $10</li>
                        <li><input type="radio" name="rate" id="rate">$10 - $30</li>
                        <li><input type="radio" name="rate" id="rate">$30 - $60</li>
                        <li><input type="radio" name="rate" id="rate">$60 & above</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Project success</span><i
                        class="fa-light fa-chevron-down"></i></div>
                    <ul class="filter-options radios">
                        <li><input type="radio" name="success" id="success" checked>Any success rate</li>
                        <li><input type="radio" name="success" id="success">90% & up</li>
                        <li><input type="radio" name="success" id="success">80% & up</li>
                        <li><input type="radio" name="success" id="success">70% & up</li>
                        <li><input type="radio" name="success" id="success">Less than 70%</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Total Earnings</span><i
                        class="fa-light fa-chevron-down"></i></div>
                    <ul class="filter-options radios">
                        <li><input type="radio" name="earnings" id="earnings" checked>Any amount earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$1+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$100+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$1K+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$10K+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">No earnings yet</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Language</span><i
                        class="fa-light fa-chevron-down"></i></div>
                    <ul class="filter-options checkboxes">
                        <li><input type="checkbox" name="language" id="language" checked>Any amount earned</li>
                        <li><input type="checkbox" name="language" id="language">$1+ earned</li>
                        <li><input type="checkbox" name="language" id="language">$100+ earned</li>
                        <li><input type="checkbox" name="language" id="language">$1K+ earned</li>
                        <li><input type="checkbox" name="language" id="language">$10K+ earned</li>
                        <li><input type="checkbox" name="language" id="language">No earnings yet</li>
                    </ul>
                </div>
            </div>
            <div class="search-content">
                <div class="advance-search">
                    <div class="search-by-category selection">
                        
                    </div>
                    <div class="search-by-location selection">

                    </div>
                </div>
                <div class="search-item"></div>
            </div>
        </div>
    </div>

</body>
<script src="searchResultsScript.js"></script>

</html>