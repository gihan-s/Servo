<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clientPosts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
    <title>Find Services and Providers</title>
    <style>
        .results-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
            align-items: start;
        }

        .filters-panel {
            position: sticky;
            top: 150px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #fff;
            padding: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
        }

        .filters-panel h3 {
            margin-bottom: 12px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-item {
            border-top: 1px solid #f1f5f9;
            padding: 12px 0;
        }

        .filter-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-title i {
            transition: transform 0.2s ease;
        }

        .filter-title i.rotated {
            transform: rotate(180deg);
        }

        .filter-options {
            list-style: none;
            margin-top: 10px;
            padding-left: 0;
            display: none;
            height: auto;
            max-height: none;
            overflow: visible;
        }

        .filter-options.active {
            display: block;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        .filters-panel .filter-item .filter-options {
            height: auto;
            max-height: none;
            overflow: visible;
            transition: none;
        }

        .filters-panel .filter-item .filter-options.active {
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        .filter-options li {
            font-size: 14px;
            color: #4b5563;
            margin: 8px 0;
            display: flex;
            gap: 8px;
            align-items: center;
            cursor: pointer;
        }

        .button-apply {
            margin-top: 8px;
        }

        .button-apply button {
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 11px 14px;
            background: #008500;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .button-apply button:hover {
            background: #006d00;
        }

        .results-panel .requests-section {
            display: none;
        }

        .results-panel .requests-section.active {
            display: block;
        }

        .item-list {
            display: grid;
            gap: 15px;
        }

        .profiles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .profile-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
        }

        .profile-cover {
            height: 84px;
            background: linear-gradient(120deg, #0f172a 0%, #1e3a8a 55%, #008500 100%);
        }

        .profile-body {
            padding: 0 16px 16px;
        }

        .profile-head {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -38px;
            margin-bottom: 14px;
            text-align: center;
        }

        .profile-avatar {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            border: 3px solid #ffffff;
            object-fit: cover;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
            margin-bottom: 10px;
        }

        .profile-name {
            font-size: 19px;
            font-weight: 700;
            color: #008500;
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .profile-services {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 14px;
        }

        .stat-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 10px;
            text-align: center;
            font-size: 12px;
            color: #475569;
            font-weight: 600;
        }

        .stat-pill b {
            color: #0f172a;
            font-size: 13px;
        }

        .provider-bio {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 12px;
            text-align: left;
            min-height: 68px;
        }

        .provider-social {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        .provider-social a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .provider-social a:hover {
            transform: translateY(-1px);
        }

        .provider-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 12px;
        }

        @keyframes providerSwipeUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .provider-card-enter {
            opacity: 0;
            transform: translateY(28px);
        }

        .provider-card-enter.is-visible {
            animation: providerSwipeUp 0.45s ease forwards;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 16px;
        }

        .page-btn {
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 8px;
            min-width: 36px;
            height: 36px;
            cursor: pointer;
        }

        .page-btn.active {
            background: #008500;
            color: #fff;
            border-color: #008500;
        }

        /* Service card helpers (core card typography/layout comes from clientPosts.css) */
        .provider-info {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .provider-image {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            overflow: hidden;
        }

        .provider-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .provider-details {
            flex: 1;
        }

        .provider-location {
            font-size: 13px;
            color: #6b7280;
        }

        .provider-meta-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
            font-size: 13px;
            color: #6b7280;
        }

        .provider-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #f59e0b;
            font-weight: 600;
        }

        @media (max-width: 960px) {
            .results-layout {
                grid-template-columns: 1fr;
            }

            .filters-panel {
                position: static;
            }

            .header-requests {
                position: static;
            }
        }
    </style>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <div class="header-top">
                <h1>Services / Providers</h1>
            </div>
            <div class="container-changer">
                <div class="tab-buttons">
                    <div id="services" class="buttons active">Services</div>
                    <div id="providers" class="buttons">Providers</div>
                </div>
            </div>
        </div>

        <div class="request-content">
            <div class="search-header">
                <div class="search-button">
                    <input type="text" placeholder="Search providers and services...">
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="advance-search">
                    <span>Sort By: </span>
                    <div class="select-container" style="width: 150px;">
                        <div class="text-container">
                            <div class="label dropdown-label" style="visibility: hidden;"></div>
                            <input type="text" id="sortDropdown" class="text-field-dropdown"
                                style="padding: 10px; background-color: var(--containerColor);" value="Date (Newest)"
                                readonly>
                        </div>
                        <div class="options" id="sortOptions" style='max-height:none;'>
                            <div data-sort="date_desc">Date (Newest)</div>
                            <div data-sort="date_asc">Date (Oldest)</div>
                            <div data-sort="price_desc">Price (High)</div>
                            <div data-sort="price_asc">Price (Low)</div>
                            <div data-sort="views_desc">Views (Most)</div>
                            <div data-sort="views_asc">Views (Least)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="results-layout">
                <aside class="filters-panel">
                    <h3>Filters</h3>
                    <div class="filter-item">
                        <div class="filter-title"><span>Hourly rate</span><i class="fa-solid fa-chevron-down rotated"></i></div>
                        <ul class="filter-options active radios">
                            <li><input type="radio" name="rate" checked>Any hourly rate</li>
                            <li><input type="radio" name="rate">Less than $10</li>
                            <li><input type="radio" name="rate">$10 - $30</li>
                            <li><input type="radio" name="rate">$30 - $60</li>
                            <li><input type="radio" name="rate">$60 & above</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Project success</span><i class="fa-solid fa-chevron-down"></i></div>
                        <ul class="filter-options radios">
                            <li><input type="radio" name="success" checked>Any success rate</li>
                            <li><input type="radio" name="success">90% & up</li>
                            <li><input type="radio" name="success">80% & up</li>
                            <li><input type="radio" name="success">70% & up</li>
                            <li><input type="radio" name="success">Less than 70%</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Total Earnings</span><i class="fa-solid fa-chevron-down"></i></div>
                        <ul class="filter-options radios">
                            <li><input type="radio" name="earnings" checked>Any amount earned</li>
                            <li><input type="radio" name="earnings">$1+ earned</li>
                            <li><input type="radio" name="earnings">$100+ earned</li>
                            <li><input type="radio" name="earnings">$1K+ earned</li>
                            <li><input type="radio" name="earnings">$10K+ earned</li>
                            <li><input type="radio" name="earnings">No earnings yet</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Language</span><i class="fa-solid fa-chevron-down"></i></div>
                        <ul class="filter-options checkboxes">
                            <li><input type="checkbox" name="language" checked>English</li>
                            <li><input type="checkbox" name="language">Sinhala</li>
                            <li><input type="checkbox" name="language">Tamil</li>
                            <li><input type="checkbox" name="language">Other</li>
                        </ul>
                    </div>
                    <div class="button-apply">
                        <button type="button">Apply filters</button>
                    </div>
                </aside>

                <div class="results-panel">
                    <section class="services requests-section active">
                        <?php $cardsOnly = true; include __DIR__ . '/searchResults.php'; ?>
                    </section>

                    <section class="providers requests-section">
                        <?php $cardsOnly = true; include __DIR__ . '/searchForProvider.php'; ?>
                    </section>
                </div>
            </div>
        </div>
    </section>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceTab = document.getElementById('services');
        const providerTab = document.getElementById('providers');
        const serviceSection = document.querySelector('.services.requests-section');
        const providerSection = document.querySelector('.providers.requests-section');

        function showProvidersWithLoading() {
            if (!providerSection) {
                return;
            }

            if (typeof window.loadProviders === 'function') {
                window.loadProviders(1);
            }
        }

        serviceTab.addEventListener('click', function () {
            serviceTab.classList.add('active');
            providerTab.classList.remove('active');
            serviceSection.classList.add('active');
            providerSection.classList.remove('active');
        });

        providerTab.addEventListener('click', function () {
            providerTab.classList.add('active');
            serviceTab.classList.remove('active');
            providerSection.classList.add('active');
            serviceSection.classList.remove('active');
            showProvidersWithLoading();
        });

        const sortInput = document.getElementById('sortDropdown');
        const sortOptions = document.getElementById('sortOptions');
        const sortContainer = sortInput ? sortInput.closest('.select-container') : null;

        if (sortInput && sortContainer) {
            sortInput.addEventListener('click', function () {
                sortContainer.classList.toggle('dropdown-view');
            });
        }

        sortOptions.querySelectorAll('div').forEach(option => {
            option.addEventListener('click', function () {
                sortInput.value = this.textContent;
                if (sortContainer) {
                    sortContainer.classList.remove('dropdown-view');
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.select-container') && sortContainer) {
                sortContainer.classList.remove('dropdown-view');
            }
        });

        document.querySelectorAll('.filter-title').forEach(function (title) {
            title.addEventListener('click', function () {
                const icon = this.querySelector('i');
                const list = this.nextElementSibling;
                icon.classList.toggle('rotated');
                list.classList.toggle('active');
            });
        });

        // Handle filter checkbox and radio inputs when label is clicked
        document.querySelectorAll('.filter-options li').forEach(function (li) {
            li.addEventListener('click', function (e) {
                // Don't trigger if clicking directly on the input
                if (e.target.tagName === 'INPUT') {
                    return;
                }
                const input = this.querySelector('input');
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = !input.checked;
                    } else if (input.type === 'radio') {
                        input.checked = true;
                    }
                }
            });
        });
    });
</script>

</html>
