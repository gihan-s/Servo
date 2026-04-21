<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clientPosts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/searchServices.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
    <style>
        .location-filter-search {
            margin: 6px 0 10px;
        }

        .location-filter-search input {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #d6dbe2;
            font-size: 13px;
            background: #fff;
        }

        .location-district-group {
            display: block !important;
            border-top: 1px dashed #edf2f7;
            padding-top: 8px;
            margin-top: 4px;
        }

        .location-district-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1f2937;
        }

        .location-expand-toggle {
            width: 22px;
            height: 22px;
            border: 1px solid #d6dbe2;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #475569;
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
        }

        .location-expand-toggle i {
            font-size: 11px;
            transition: transform 0.2s ease;
        }

        .location-district-group.expanded .location-expand-toggle i {
            transform: rotate(90deg);
        }

        .location-city-list {
            display: none;
            width: 100%;
            list-style: none;
            margin: 6px 0 0 18px;
            padding: 0;
        }

        .location-district-group.expanded .location-city-list {
            display: block;
        }

        .location-city-list li {
            display: flex;
            align-items: center;
            padding: 4px 0;
        }

        .location-city-list li input {
            margin-right: 8px;
        }

        .location-city-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding-left: 8px;
            color: #334155;
            font-weight: 500;
        }

        .location-city-label::before {
            content: '\21B3';
            color: #94a3b8;
            font-size: 12px;
            line-height: 1;
        }
    </style>
    <title>Find Services and Providers</title>
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
                <aside class="filters-panel" id="services-filters-panel">
                    <h3>Filters</h3>
                    <div class="filter-item">
                        <div class="filter-title"><span>Location</span><i class="fa-solid fa-chevron-down rotated"></i></div>
                        <div class="location-filter-search">
                            <input type="text" id="locationFilterSearch" placeholder="Search district or city...">
                        </div>
                        <ul class="filter-options active checkboxes" id="locationFilterOptions">
                            <li>
                                <input type="checkbox" id="location-all" name="location_all" value="all">
                                <label for="location-all">All Locations</label>
                            </li>
                            <?php if (!empty($locationTree) && is_array($locationTree)): ?>
                                <?php foreach ($locationTree as $districtData): ?>
                                    <?php
                                    $districtName = (string) ($districtData['district'] ?? '');
                                    $districtKey = preg_replace('/[^a-z0-9_]+/i', '_', strtolower($districtName));
                                    $cities = (array) ($districtData['cities'] ?? []);
                                    ?>
                                    <li class="location-district-group" data-district="<?= htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') ?>">
                                        <div class="location-district-row">
                                            <button
                                                type="button"
                                                class="location-expand-toggle"
                                                data-district="<?= htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') ?>"
                                                aria-label="Expand <?= htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') ?> cities"
                                                aria-expanded="false"
                                            >
                                                <i class="fa-solid fa-chevron-right"></i>
                                            </button>
                                            <input
                                                type="checkbox"
                                                class="location-district-checkbox"
                                                id="location-district-<?= htmlspecialchars($districtKey, ENT_QUOTES, 'UTF-8') ?>"
                                                name="location_districts[]"
                                                value="<?= htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') ?>"
                                                data-district="<?= htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                            <label for="location-district-<?= htmlspecialchars($districtKey, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') ?></label>
                                        </div>
                                        <?php if (!empty($cities)): ?>
                                            <ul class="location-city-list">
                                                <?php foreach ($cities as $cityName): ?>
                                                    <?php $cityKey = preg_replace('/[^a-z0-9_]+/i', '_', strtolower($districtName . '_' . (string) $cityName)); ?>
                                                    <li data-city="<?= htmlspecialchars((string) $cityName, ENT_QUOTES, 'UTF-8') ?>">
                                                        <input
                                                            type="checkbox"
                                                            class="location-city-checkbox"
                                                            id="location-city-<?= htmlspecialchars($cityKey, ENT_QUOTES, 'UTF-8') ?>"
                                                            name="location_cities[]"
                                                            value="<?= htmlspecialchars((string) $cityName, ENT_QUOTES, 'UTF-8') ?>"
                                                            data-district="<?= htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') ?>"
                                                        >
                                                        <label class="location-city-label" for="location-city-<?= htmlspecialchars($cityKey, ENT_QUOTES, 'UTF-8') ?>\"><?= htmlspecialchars((string) $cityName, ENT_QUOTES, 'UTF-8') ?></label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Categories</span><i class="fa-solid fa-chevron-down rotated"></i></div>
                        <ul class="filter-options active checkboxes">
                            <?php if (!empty($categories) && is_array($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <li>
                                        <input type="checkbox" name="category_ids[]" value="<?= (int) ($category['Category_ID'] ?? 0) ?>">
                                        <?= htmlspecialchars((string) ($category['Name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li style="color:#6b7280;">No categories available</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Pricing Type</span><i class="fa-solid fa-chevron-down rotated"></i></div>
                        <ul class="filter-options active checkboxes">
                            <li><input type="checkbox" name="pricing_type" value="Hourly">Hourly</li>
                            <li><input type="checkbox" name="pricing_type" value="Daily">Daily</li>
                            <li><input type="checkbox" name="pricing_type" value="Fixed">Fixed</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Price Range</span><i class="fa-solid fa-chevron-down rotated"></i></div>
                        <ul class="filter-options active radios">
                            <li><input type="radio" name="price_range" value="" checked>Any price</li>
                            <li><input type="radio" name="price_range" value="below_5000">Below 5,000</li>
                            <li><input type="radio" name="price_range" value="5000_10000">5,000 - 10,000</li>
                            <li><input type="radio" name="price_range" value="10000_50000">10,000 - 50,000</li>
                            <li><input type="radio" name="price_range" value="50000_100000">50,000 - 100,000</li>
                            <li><input type="radio" name="price_range" value="above_100000">Above 100,000</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Successive Rate</span><i class="fa-solid fa-chevron-down rotated"></i></div>
                        <ul class="filter-options active radios">
                            <li><input type="radio" name="completion_range" value="" checked>Any successive rate</li>
                            <li><input type="radio" name="completion_range" value="below_25">Below 25%</li>
                            <li><input type="radio" name="completion_range" value="25_50">25% - 50%</li>
                            <li><input type="radio" name="completion_range" value="50_75">50% - 75%</li>
                            <li><input type="radio" name="completion_range" value="above_75">Above 75%</li>
                        </ul>
                    </div>
                </aside>

                <div class="results-panel">
                    <section class="services requests-section active">
                        <div class="item-list">
                            <div id="services-cards-root" style="display: grid; gap: 24px; grid-template-columns: 1fr;">
                                <div class="loading-state">
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                    <p>Loading services...</p>
                                </div>
                            </div>
                            <div class="pagination" id="services-pagination" aria-label="Service pagination"></div>
                        </div>
                    </section>

                    <section class="providers requests-section">
                        <div class="profiles-grid" id="providers-grid">
                            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #6b7280;">
                                <i class="fa-solid fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 20px; color: #008500; opacity: 0.8;"></i>
                                <p style="font-size: 16px; font-weight: 500; color: #1f2937; margin: 0;">Loading providers...</p>
                            </div>
                        </div>
                        <div class="load-more-wrap" id="provider-load-more" style="display:flex; justify-content:center; margin-top:35px;"></div>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <div class="dialog-box-2" id="provider-services-modal">
        <div class="dialog-content" style="width: 700px;">
            <div class="dialog-title">
                <div class="title">Services by <span id="modal-provider-name"></span></div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeProviderServicesModal()"></i>
                </div>
            </div>
            <div id="modal-services-content" style="padding: 20px 0;"></div>
            <div class="modal-actions" style="padding: 0 20px 20px; justify-content: flex-end;">
                <button type="button" class="action-btn btn-delete" onclick="closeProviderServicesModal()">Close</button>
            </div>
        </div>
    </div>

    <div class="dialog-box-2" id="provider-request-modal">
        <div class="dialog-content" style="width: 720px;">
            <div class="dialog-title">
                <div class="title">Send Request</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeProviderRequestModal()"></i>
                </div>
            </div>
            <form id="provider-request-form" onsubmit="return false;">
                <input type="hidden" id="provider-request-service-id" name="provider_categories_id">
                <input type="hidden" id="provider-request-provider-id" name="provider_id">
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label">Title</div>
                        <input type="text" class="text-field" name="title" id="provider-request-title">
                    </div>
                </div>
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label">Description</div>
                        <textarea class="text-field" spellcheck="false" name="description" id="provider-request-description"></textarea>
                    </div>
                </div>
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label">Requesting Price</div>
                        <input type="text" class="text-field" name="requesting_price" id="provider-request-price">
                    </div>
                </div>
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label">Estimated Date</div>
                        <input type="date" class="text-field" name="est_date" id="provider-request-est-date">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="action-btn btn-delete" onclick="closeProviderRequestModal()">Cancel</button>
                    <button type="button" class="action-btn btn-edit" onclick="submitProviderServiceRequest()"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                </div>
            </form>
        </div>
    </div>

    <div class="dialog-box-2" id="provider-profile-modal">
        <div class="dialog-content" style="width: 980px; max-width: calc(100vw - 32px); overflow: hidden;">
            <div class="dialog-title" style="margin-bottom: 0;">
                <div class="title">Provider Profile</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeProviderProfileModal()"></i>
                </div>
            </div>
            <div id="provider-profile-content" style="padding: 20px 0;"></div>
        </div>
    </div>

    <div class="dialog-box-2" id="provider-service-details-modal">
        <div class="dialog-content" style="width: 700px;">
            <div class="dialog-title">
                <div class="title" id="service-details-title">Service Details</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeServiceDetailsModal()"></i>
                </div>
            </div>
            <div id="service-details-content" style="padding: 20px 0;"></div>
        </div>
    </div>

    <div class="dialog-box-2" id="request-confirmation-modal">
        <div class="dialog-content" style="width: 520px;">
            <div class="dialog-title">
                <div class="title">Confirm Request</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeRequestConfirmationModal(false)"></i>
                </div>
            </div>
            <div class="dialog-body" style="padding: 0 20px 14px;">
                <p id="request-confirmation-message" style="margin: 0; color: #334155; line-height: 1.7;"></p>
            </div>
            <div class="modal-actions" style="padding: 0 20px 20px; justify-content: flex-end;">
                <button type="button" class="action-btn btn-delete" onclick="closeRequestConfirmationModal(false)">Cancel</button>
                <button type="button" class="action-btn btn-edit" onclick="closeRequestConfirmationModal(true)"><i class="fa-solid fa-paper-plane"></i> Continue</button>
            </div>
        </div>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceTab = document.getElementById('services');
        const providerTab = document.getElementById('providers');
        const serviceSection = document.querySelector('.services.requests-section');
        const providerSection = document.querySelector('.providers.requests-section');
        const sortInput = document.getElementById('sortDropdown');
        const sortOptions = document.getElementById('sortOptions');
        const sortContainer = sortInput ? sortInput.closest('.select-container') : null;
        const searchInput = document.querySelector('.search-button input');
        const servicesFiltersPanel = document.getElementById('services-filters-panel');

        const serviceSortConfig = [
            { value: 'price_asc', label: 'Price (Low -> High)' },
            { value: 'price_desc', label: 'Price (High -> Low)' },
            { value: 'completion_asc', label: 'Successive Rate (Low -> High)' },
            { value: 'completion_desc', label: 'Successive Rate (High -> Low)' },
            { value: 'category_asc', label: 'Service Category (A -> Z)' },
            { value: 'category_desc', label: 'Service Category (Z -> A)' },
        ];

        const providerSortConfig = [
            { value: 'name_asc', label: 'Name (A -> Z)' },
            { value: 'name_desc', label: 'Name (Z -> A)' },
            { value: 'rating_asc', label: 'Rating (Low -> High)' },
            { value: 'rating_desc', label: 'Rating (High -> Low)' },
        ];

        const serviceState = {
            page: 1,
            totalPages: 1,
            loading: false,
            search: '',
            sort: 'price_asc',
            priceRange: '',
            completionRange: '',
            pricingTypes: [],
            categoryIds: [],
            locationDistricts: [],
            locationCities: [],
        };

        const providerState = {
            page: 1,
            totalPages: 1,
            loading: false,
            search: '',
            sort: 'name_asc',
        };

        let activeTab = 'services';
        const serviceCardsCache = {};
        const providerServiceCache = {};
        const providerProfileCache = {};
        let providerDialogZIndex = 2000;
        let hasSortOptionClickListener = false;
        let requestConfirmationResolver = null;

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function truncateText(value, maxLength = 160) {
            const text = String(value ?? '').trim();
            if (text.length <= maxLength) {
                return text;
            }
            return `${text.slice(0, maxLength).trimEnd()}...`;
        }

        function getServicePriceTypesFromFilters() {
            return Array.from(document.querySelectorAll('input[name="pricing_type"]:checked')).map(input => input.value);
        }

        function getServiceCategoryIdsFromFilters() {
            return Array.from(document.querySelectorAll('input[name="category_ids[]"]:checked')).map(input => input.value);
        }

        function getServiceLocationDistrictsFromFilters() {
            return Array.from(document.querySelectorAll('input[name="location_districts[]"]:checked')).map(input => input.value);
        }

        function getServiceLocationCitiesFromFilters() {
            return Array.from(document.querySelectorAll('input[name="location_cities[]"]:checked')).map(input => input.value);
        }

        function applyServiceFiltersFromUI() {
            const priceRange = document.querySelector('input[name="price_range"]:checked');
            const completionRange = document.querySelector('input[name="completion_range"]:checked');
            serviceState.priceRange = priceRange ? priceRange.value : '';
            serviceState.completionRange = completionRange ? completionRange.value : '';
            serviceState.pricingTypes = getServicePriceTypesFromFilters();
            serviceState.categoryIds = getServiceCategoryIdsFromFilters();
            serviceState.locationDistricts = getServiceLocationDistrictsFromFilters();
            serviceState.locationCities = getServiceLocationCitiesFromFilters();
        }

        function initializeLocationFilter() {
            const allCheckbox = document.getElementById('location-all');
            const districtCheckboxes = Array.from(document.querySelectorAll('.location-district-checkbox'));
            const cityCheckboxes = Array.from(document.querySelectorAll('.location-city-checkbox'));
            const searchInput = document.getElementById('locationFilterSearch');
            const districtGroups = Array.from(document.querySelectorAll('.location-district-group'));
            const expandButtons = Array.from(document.querySelectorAll('.location-expand-toggle'));

            const setDistrictExpanded = function (group, expanded) {
                if (!group) return;
                group.classList.toggle('expanded', expanded);
                const btn = group.querySelector('.location-expand-toggle');
                if (btn) {
                    btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                }
            };

            // Cities are collapsed by default.
            districtGroups.forEach(group => setDistrictExpanded(group, false));

            expandButtons.forEach(btn => {
                btn.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const districtName = btn.dataset.district || '';
                    const group = districtGroups.find(g => (g.dataset.district || '') === districtName);
                    if (!group) return;
                    const shouldExpand = !group.classList.contains('expanded');
                    setDistrictExpanded(group, shouldExpand);
                });
            });

            const syncAllCheckbox = function () {
                if (!allCheckbox) return;
                const allCount = districtCheckboxes.length + cityCheckboxes.length;
                const checkedCount = districtCheckboxes.filter(cb => cb.checked).length + cityCheckboxes.filter(cb => cb.checked).length;
                allCheckbox.checked = allCount > 0 && checkedCount === allCount;
            };

            const syncDistrictCheckbox = function (districtName) {
                const district = districtCheckboxes.find(cb => cb.dataset.district === districtName);
                if (!district) return;
                const districtCities = cityCheckboxes.filter(cb => cb.dataset.district === districtName);
                if (districtCities.length === 0) {
                    return;
                }
                district.checked = districtCities.every(cb => cb.checked);
            };

            if (allCheckbox) {
                allCheckbox.addEventListener('change', function () {
                    const checked = allCheckbox.checked;
                    districtCheckboxes.forEach(cb => {
                        cb.checked = checked;
                    });
                    cityCheckboxes.forEach(cb => {
                        cb.checked = checked;
                    });

                    applyServiceFiltersFromUI();
                    loadServiceCards(1);
                });
            }

            districtCheckboxes.forEach(districtCheckbox => {
                districtCheckbox.addEventListener('change', function () {
                    const districtName = districtCheckbox.dataset.district;
                    const districtCities = cityCheckboxes.filter(cb => cb.dataset.district === districtName);
                    const group = districtGroups.find(g => (g.dataset.district || '') === districtName);
                    districtCities.forEach(cityCheckbox => {
                        cityCheckbox.checked = districtCheckbox.checked;
                    });
                    if (districtCheckbox.checked) {
                        setDistrictExpanded(group, true);
                    }
                    syncAllCheckbox();

                    applyServiceFiltersFromUI();
                    loadServiceCards(1);
                });
            });

            cityCheckboxes.forEach(cityCheckbox => {
                cityCheckbox.addEventListener('change', function () {
                    const districtName = cityCheckbox.dataset.district;
                    syncDistrictCheckbox(districtName);
                    syncAllCheckbox();

                    applyServiceFiltersFromUI();
                    loadServiceCards(1);
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const term = searchInput.value.trim().toLowerCase();

                    districtGroups.forEach(group => {
                        const districtName = (group.dataset.district || '').toLowerCase();
                        const cityRows = Array.from(group.querySelectorAll('.location-city-list li'));
                        let anyCityVisible = false;

                        cityRows.forEach(cityRow => {
                            const cityName = (cityRow.dataset.city || '').toLowerCase();
                            const visible = term === '' || cityName.includes(term) || districtName.includes(term);
                            cityRow.style.display = visible ? '' : 'none';
                            if (visible) {
                                anyCityVisible = true;
                            }
                        });

                        const districtVisible = term === '' || districtName.includes(term) || anyCityVisible;
                        group.style.display = districtVisible ? '' : 'none';

                        if (term === '') {
                            setDistrictExpanded(group, false);
                        } else {
                            const shouldExpand = districtName.includes(term) || anyCityVisible;
                            setDistrictExpanded(group, shouldExpand);
                        }
                    });
                });
            }
        }

        function renderSortOptions(config, selectedValue) {
            if (!sortOptions || !sortInput) {
                return;
            }

            sortOptions.innerHTML = config.map(item => `<div class="sort-option" data-sort="${item.value}">${item.label}</div>`).join('');
            const selected = config.find(item => item.value === selectedValue) || config[0];
            sortInput.value = selected ? selected.label : '';

            if (!hasSortOptionClickListener) {
                const applySortSelection = function (option) {
                    const sortValue = option.getAttribute('data-sort') || '';
                    const label = option.textContent || '';
                    sortInput.value = label;

                    if (activeTab === 'services') {
                        serviceState.sort = sortValue;
                        loadServiceCards(1);
                    } else {
                        providerState.sort = sortValue;
                        loadProviders(1, false);
                    }

                    if (sortContainer) {
                        sortContainer.classList.remove('dropdown-view');
                    }
                };

                // Use mousedown so selection is applied before global body mousedown closes dropdowns.
                sortOptions.addEventListener('mousedown', function (event) {
                    const option = event.target.closest('.sort-option');
                    if (!option) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    applySortSelection(option);
                });

                // Keep click as a fallback (e.g. keyboard-triggered clicks).
                sortOptions.addEventListener('click', function (event) {
                    const option = event.target.closest('.sort-option');
                    if (!option) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    applySortSelection(option);
                });

                hasSortOptionClickListener = true;
            }
        }

        function renderNoResultsState(title, subtitle) {
            return `
                <div class="empty-state">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h3>${escapeHtml(title)}</h3>
                    <p>${escapeHtml(subtitle)}</p>
                </div>
            `;
        }

        function showRequestConfirmationModal() {
            const modal = document.getElementById('request-confirmation-modal');
            const message = document.getElementById('request-confirmation-message');

            if (!modal || !message) {
                return Promise.resolve(false);
            }

            message.textContent = 'There is a request ongoing for the same service. Do you want to send another request?';

            return new Promise(resolve => {
                requestConfirmationResolver = resolve;
                bringProviderDialogToFront(modal);
                modal.classList.add('dialog-box-2-view');
            });
        }

        function closeRequestConfirmationModal(confirmed) {
            const modal = document.getElementById('request-confirmation-modal');
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }

            if (typeof requestConfirmationResolver === 'function') {
                requestConfirmationResolver(Boolean(confirmed));
            }
            requestConfirmationResolver = null;
        }

        function renderServiceCardMarkup(service) {
            serviceCardsCache[service.Provider_Categories_ID] = service;

            const providerName = `${service.First_Name || ''} ${service.Last_Name || ''}`.trim() || 'Provider';
            const providerLocation = (service.formatted_location || '').trim() || 'Location not specified';
            const providerRating = service.provider_star_rating !== undefined && service.provider_star_rating !== null
                ? Number(service.provider_star_rating).toFixed(1)
                : `${Math.max(0, Math.min(5, Number(service.Provider_Rating || 0) / 20)).toFixed(1)}`;
            const successiveRate = service.success_rate_display || `${Math.max(0, Math.min(100, Math.round(Number(service.Rating || 0))))}%`;
            const providerImage = service.Profile_Picture
                ? `<?= BASE_URL ?>/file/user-files/${service.Profile_Picture}`
                : 'sampleImg.jpg';
            const messageUrl = `<?= BASE_URL ?>/messages?new=${encodeURIComponent(String(Number(service.Provider_ID) || 0))}`;
            const skills = (service.skills || []).slice(0, 6);
            const skillsTags = skills.length
                ? skills.map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('')
                : '<span class="skill-tag">No skills listed</span>';
            const shortDescription = truncateText(service.Description || '', 180);

            return `
                <div class="search-item">
                    <div class="post-header">
                        <div class="post-meta">
                            <div class="provider-info">
                                <div class="provider-image"><img src="${providerImage}" alt="Provider"></div>
                                <div class="provider-details">
                                    <div class="provider-name">${escapeHtml(providerName)}</div>
                                    <div class="provider-location">${escapeHtml(providerLocation)}</div>
                                    <div class="provider-meta-row">
                                        <span class="provider-rating"><i class="fa-solid fa-star"></i>${escapeHtml(providerRating)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post-actions">
                            <button class="action-btn btn-view" title="Message Provider" onclick="window.location.href='${messageUrl}'"><i class="fa-solid fa-messages"></i> Message</button>
                            <button class="action-btn btn-view" title="View Service" onclick="openServiceCardDetailsModal(${service.Provider_Categories_ID})"><i class="fa-solid fa-eye"></i> View Service</button>
                            <button class="action-btn btn-edit service-hire-btn" title="Send Request" data-service-id="${service.Provider_Categories_ID}" data-request-status="${service.request_status}"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                        </div>
                    </div>
                    <h3 class="post-title">${escapeHtml(service.Title || 'Untitled Service')}</h3>
                    <div class="post-description">${escapeHtml(shortDescription)}</div>
                    <div class="post-skills">
                        <span class="skills-label">Skills:</span>
                        <div class="skills-tags">${skillsTags}</div>
                    </div>
                    <div class="post-footer">
                        <div class="post-details">
                            <div class="detail-item">
                                <span class="detail-label">Rate</span>
                                <span class="detail-value budget-amount">
                                    ${escapeHtml(service.price_display || 'Contact for price')}${service.rate_type_display && service.rate_type_display !== 'N/A' ? ` <span style="font-size:12px; color:#6b7280; font-weight:600;">/ ${escapeHtml(service.rate_type_display)}</span>` : ''}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Successive Rate</span>
                                <span class="detail-value">${escapeHtml(successiveRate)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderServiceCardDetailsMarkup(service) {
            const linksHTML = (service.show_links || []).map(link => {
                const label = escapeHtml(link.label || 'Link');
                const url = escapeHtml(link.url || '#');
                return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="action-btn btn-view" style="padding: 7px 12px; font-size: 12px; text-decoration: none;"><i class="fa-solid fa-link"></i> ${label}</a>`;
            }).join('');

            const formattedLocation = (service.formatted_location || '').trim();
            const locationsHTML = formattedLocation ? `<span class="skill-tag">${escapeHtml(formattedLocation)}</span>` : '';

            const skillsHTML = (service.skills || []).map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('');
            const successiveRate = service.success_rate_display || `${Math.max(0, Math.min(100, Math.round(Number(service.Rating || 0))))}%`;

            return `
                <div style="display:grid; gap:14px; padding:0 20px 10px;">
                    <div style="padding:16px; border:1px solid #e5e7eb; border-radius:14px; background:linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
                        <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap; margin-bottom:12px;">
                            <div style="min-width:0; flex:1;">
                                <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#008500; margin-bottom:8px;">Service Overview</div>
                                <h3 style="font-size:20px; margin:0; color:#111827; line-height:1.35;">${escapeHtml(service.Title || 'Untitled Service')}</h3>
                            </div>
                            <div style="display:flex; flex-direction:column; gap:8px; align-items:flex-end; min-width:160px;">
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#ecfdf5; color:#166534; font-size:13px; font-weight:700;">
                                    <i class="fa-solid fa-layer-group"></i>
                                    ${escapeHtml(service.Category_Name || 'Service')}
                                </div>
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#eff6ff; color:#1d4ed8; font-size:13px; font-weight:700;">
                                    <i class="fa-solid fa-tag"></i>
                                    ${escapeHtml(service.price_display || 'Contact for price')}
                                </div>
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#f5f3ff; color:#6d28d9; font-size:13px; font-weight:700;">
                                    <i class="fa-regular fa-clock"></i>
                                    ${escapeHtml(service.rate_type_display || 'N/A')}
                                </div>
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#fff7ed; color:#9a3412; font-size:13px; font-weight:700;">
                                    <i class="fa-solid fa-chart-line"></i>
                                    ${escapeHtml(successiveRate)}
                                </div>
                            </div>
                        </div>
                        <div style="padding-top:12px; border-top:1px solid #e5e7eb;">
                            <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Description</div>
                            <p style="margin:0; color:#4b5563; line-height:1.7; white-space:pre-wrap; font-size:14px;">${escapeHtml(service.Description || 'No description available.')}</p>
                        </div>
                    </div>

                    ${skillsHTML ? `<div style="padding:14px 16px; border:1px solid #e5e7eb; border-radius:14px; background:#fff;"><div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:10px;">Skills</div><div style="display:flex; flex-wrap:wrap; gap:8px;">${skillsHTML}</div></div>` : ''}
                    ${locationsHTML ? `<div style="padding:14px 16px; border:1px solid #e5e7eb; border-radius:14px; background:#fff;"><div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:10px;">Locations</div><div style="display:flex; flex-wrap:wrap; gap:8px;">${locationsHTML}</div></div>` : ''}
                    ${linksHTML ? `<div style="padding:14px 16px; border:1px solid #e5e7eb; border-radius:14px; background:#fff;"><div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:10px;">Links</div><div style="display:flex; flex-wrap:wrap; gap:8px;">${linksHTML}</div></div>` : ''}

                    <div style="display:flex; justify-content:flex-end; gap:8px; padding-top:4px;">
                        <button type="button" class="action-btn btn-delete" onclick="closeServiceDetailsModal()">Close</button>
                        <button type="button" class="action-btn btn-edit" onclick="openServiceCardRequestModal(${Number(service.Provider_Categories_ID) || 0})"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                    </div>
                </div>
            `;
        }

        function renderServicePagination() {
            const pagination = document.getElementById('services-pagination');
            if (!pagination) return;

            if (serviceState.totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let html = `<button class="page-btn prev" ${serviceState.page <= 1 ? 'disabled' : ''} data-page="${serviceState.page - 1}"><i class="fa-solid fa-chevron-left"></i></button>`;
            for (let page = 1; page <= serviceState.totalPages; page += 1) {
                html += `<button class="page-btn ${page === serviceState.page ? 'active' : ''}" data-page="${page}">${page}</button>`;
            }
            html += `<button class="page-btn next" ${serviceState.page >= serviceState.totalPages ? 'disabled' : ''} data-page="${serviceState.page + 1}"><i class="fa-solid fa-chevron-right"></i></button>`;

            pagination.innerHTML = html;
            pagination.querySelectorAll('.page-btn[data-page]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const page = parseInt(this.getAttribute('data-page') || '1', 10);
                    if (!Number.isNaN(page) && page >= 1 && page <= serviceState.totalPages) {
                        loadServiceCards(page);
                    }
                });
            });
        }

        function attachHireButtonListeners() {
            const root = document.getElementById('services-cards-root');
            if (!root) return;
            root.removeEventListener('click', handleHireButtonClick);
            root.addEventListener('click', handleHireButtonClick);
        }

        function handleHireButtonClick(e) {
            const button = e.target.closest('.service-hire-btn');
            if (!button) return;

            e.preventDefault();
            const serviceId = button.getAttribute('data-service-id');

            if (serviceId) {
                openServiceCardRequestModal(parseInt(serviceId, 10));
            }
        }

        function buildServiceQuery(page) {
            const params = new URLSearchParams();
            params.set('page', String(page));
            params.set('limit', '12');

            if (serviceState.search) params.set('q', serviceState.search);
            if (serviceState.sort) params.set('sort', serviceState.sort);
            if (serviceState.priceRange) params.set('price_range', serviceState.priceRange);
            if (serviceState.completionRange) params.set('completion_range', serviceState.completionRange);
            if (serviceState.pricingTypes.length) params.set('price_types', serviceState.pricingTypes.join(','));
            if (serviceState.categoryIds.length) params.set('category_ids', serviceState.categoryIds.join(','));
            if (serviceState.locationDistricts.length) params.set('location_districts', serviceState.locationDistricts.join(','));
            if (serviceState.locationCities.length) params.set('location_cities', serviceState.locationCities.join(','));

            return params.toString();
        }

        function loadServiceCards(page = 1) {
            const root = document.getElementById('services-cards-root');
            const pagination = document.getElementById('services-pagination');
            if (!root || serviceState.loading) return;

            serviceState.loading = true;
            root.innerHTML = `<div class="loading-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Loading services...</p></div>`;
            if (pagination) pagination.innerHTML = '';

            fetch(`<?= BASE_URL ?>/providers/services?${buildServiceQuery(page)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(text => JSON.parse(text))
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load services');
                    }

                    const services = data.services || [];
                    root.innerHTML = services.length
                        ? services.map(renderServiceCardMarkup).join('')
                        : renderNoResultsState('No Results Found', 'Try a different keyword or adjust your service filters.');

                    serviceState.page = data.pagination?.current_page || page;
                    serviceState.totalPages = data.pagination?.total_pages || 1;
                    renderServicePagination();
                    attachHireButtonListeners();
                })
                .catch(error => {
                    console.error('Error loading services:', error);
                    root.innerHTML = '<div style="text-align: center; padding: 30px; color: red;">Error loading services</div>';
                })
                .finally(() => {
                    serviceState.loading = false;
                });
        }

        function bringProviderDialogToFront(modal) {
            if (!modal) return;
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            providerDialogZIndex += 1;
            modal.style.zIndex = String(providerDialogZIndex);
        }

        function renderProviderCard(provider) {
            providerProfileCache[provider.Provider_ID] = provider;

            const categoryTags = Array.isArray(provider.categories) && provider.categories.length ? provider.categories : (provider.skills || []);
            const categoriesHTML = categoryTags.map(category => `<span class="skill-tag">${escapeHtml(category)}</span>`).join('');
            const availableSocialLinks = (provider.social_links || []).filter(social => String(social?.link || '').trim());
            const socialHTML = availableSocialLinks.map(social => {
                const iconPrefix = (social.icon_class === 'fa-envelope' || social.icon_class === 'fa-link') ? 'fa-solid' : 'fa-brands';
                return `<a href="${escapeHtml(social.link)}" title="${escapeHtml(social.name)}" target="_blank" style="background-color: ${escapeHtml(social.color || '#008500')}"><i class="${iconPrefix} ${escapeHtml(social.icon_class)}"></i></a>`;
            }).join('');
            const messageUrl = `<?= BASE_URL ?>/messages?new=${encodeURIComponent(String(Number(provider.Provider_ID) || 0))}`;
            const shortBio = truncateText(provider.Bio || 'Experienced professional ready to help with your project.', 150);

            return `
                <div class="profile-card search-item">
                    <div class="profile-cover"></div>
                    <div class="profile-body">
                        <div class="profile-head">
                            <img class="profile-avatar" src="<?= BASE_URL . '/file/user-files/'?>${escapeHtml(provider.avatar || '')}" alt="Profile Image" style="object-fit: cover;">
                            <h3 class="profile-name">${escapeHtml((provider.First_Name || '') + ' ' + (provider.Last_Name || ''))}</h3>
                            <div class="profile-services">${categoriesHTML || '<span class="skill-tag">View Services</span>'}</div>
                        </div>

                        <div class="profile-stats" style="grid-template-columns: 1fr;">
                            <div class="stat-pill">Rating<br><b><i class="fa-solid fa-star" style="color:#f59e0b;"></i> ${escapeHtml(provider.rating)}</b></div>
                        </div>

                        <div class="provider-bio" style="padding: 0 18px; box-sizing: border-box;">${escapeHtml(shortBio)}</div>

                        <div class="provider-actions">
                            <button class="action-btn btn-view" type="button" onclick="window.location.href='${messageUrl}'"><i class="fa-solid fa-messages"></i> Message</button>
                            <button class="action-btn btn-view" type="button" onclick="openProviderProfileModal(${provider.Provider_ID})"><i class="fa-solid fa-user"></i> View Profile</button>
                            <button class="action-btn btn-edit" type="button" data-provider-id="${provider.Provider_ID}" data-provider-name="${escapeHtml((provider.First_Name || '') + ' ' + (provider.Last_Name || ''))}" onclick="openProviderServicesModal(this)"><i class="fa-solid fa-briefcase"></i> Hire</button>
                        </div>

                        <div class="provider-social">${socialHTML || '<span style="font-size: 12px; color: #999;">No social links</span>'}</div>
                    </div>
                </div>
            `;
        }

        function renderLoadMoreButton() {
            const loadMoreContainer = document.getElementById('provider-load-more');
            if (!loadMoreContainer) return;

            if (providerState.page >= providerState.totalPages) {
                loadMoreContainer.innerHTML = '';
                return;
            }

            loadMoreContainer.innerHTML = '<button type="button" class="action-btn btn-view" id="load-more-providers-btn">Load More</button>';
            const button = document.getElementById('load-more-providers-btn');
            if (button) {
                button.addEventListener('click', function () {
                    loadProviders(providerState.page + 1, true);
                });
            }
        }

        function buildProviderQuery(page) {
            const params = new URLSearchParams();
            params.set('page', String(page));
            params.set('limit', '12');
            if (providerState.search) params.set('q', providerState.search);
            if (providerState.sort) params.set('sort', providerState.sort);
            return params.toString();
        }

        function loadProviders(page = 1, append = false) {
            const providersGrid = document.getElementById('providers-grid');
            const loadMoreContainer = document.getElementById('provider-load-more');
            if (!providersGrid || !loadMoreContainer || providerState.loading) return;

            providerState.loading = true;

            if (append) {
                loadMoreContainer.innerHTML = '<div style="padding: 10px 0 0; width: 100%; text-align: center; color: #6b7280;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 10px; color: #008500; opacity: 0.8;"></i><p style="font-size: 14px; font-weight: 500; color: #1f2937; margin: 0;">Loading providers...</p></div>';
            } else {
                providersGrid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #6b7280;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 20px; color: #008500; opacity: 0.8;"></i><p style="font-size: 16px; font-weight: 500; color: #1f2937; margin: 0;">Loading providers...</p></div>';
                loadMoreContainer.innerHTML = '';
            }

            fetch(`<?= BASE_URL ?>/providers/search?${buildProviderQuery(page)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => JSON.parse(data))
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Error loading providers');
                    }

                    const cardsHTML = (data.providers || []).map(renderProviderCard).join('');
                    if (append && cardsHTML) {
                        providersGrid.insertAdjacentHTML('beforeend', cardsHTML);
                    } else {
                        providersGrid.innerHTML = cardsHTML || renderNoResultsState('No Results Found', 'Try a different keyword or sorting option for providers.');
                    }

                    providerState.page = data.pagination?.current_page || page;
                    providerState.totalPages = data.pagination?.total_pages || page;
                    renderLoadMoreButton();
                })
                .catch(error => {
                    console.error('Error loading providers:', error);
                    providersGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: red;">Error loading providers</p>';
                    loadMoreContainer.innerHTML = '';
                })
                .finally(() => {
                    providerState.loading = false;
                });
        }

        function openServiceCardDetailsModal(serviceId) {
            const service = serviceCardsCache[serviceId];
            if (!service) {
                showToast('error', 'Service Not Found', 'Service details not available. Please refresh and try again.');
                return;
            }

            const detailsModal = document.getElementById('provider-service-details-modal');
            const detailsTitle = document.getElementById('service-details-title');
            const detailsContent = document.getElementById('service-details-content');

            if (!detailsModal || !detailsTitle || !detailsContent) {
                showToast('error', 'Form Not Available', 'Service details modal is not available.');
                return;
            }

            bringProviderDialogToFront(detailsModal);
            detailsTitle.textContent = service.Title || 'Service Details';
            detailsContent.innerHTML = renderServiceCardDetailsMarkup(service);
            detailsModal.classList.add('dialog-box-2-view');
        }

        function closeServiceDetailsModal() {
            const detailsModal = document.getElementById('provider-service-details-modal');
            if (detailsModal) {
                detailsModal.classList.remove('dialog-box-2-view');
            }
        }

        function openServiceCardRequestModal(serviceId) {
            const service = serviceCardsCache[serviceId] || providerServiceCache[serviceId];
            if (!service) {
                showToast('error', 'Service Not Found', 'Service details not available. Please refresh and try again.');
                return;
            }

            const modal = document.getElementById('provider-request-modal');
            const form = document.getElementById('provider-request-form');
            if (!modal || !form) {
                showToast('error', 'Form Not Available', 'Request form is not available. Please refresh the page.');
                return;
            }

            form.reset();
            clearProviderRequestValidation(form);
            resetProviderRequestFloatingLabels(form);
            document.getElementById('provider-request-service-id').value = service.Provider_Categories_ID || '';
            document.getElementById('provider-request-provider-id').value = service.Provider_ID || '';

            bringProviderDialogToFront(modal);
            modal.classList.add('dialog-box-2-view');
        }

        function openProviderServicesModal(button) {
            const providerId = button.getAttribute('data-provider-id');
            const providerName = button.getAttribute('data-provider-name');
            if (!providerId) {
                showToast('error', 'Error', 'Provider ID not found');
                return;
            }

            const modal = document.getElementById('provider-services-modal');
            const modalTitle = document.getElementById('modal-provider-name');
            if (!modal || !modalTitle) {
                showToast('error', 'Modal Error', 'Modal element not found');
                return;
            }

            modalTitle.textContent = providerName || 'Provider';
            bringProviderDialogToFront(modal);
            modal.classList.add('dialog-box-2-view');
            loadProviderServices(providerId);
        }

        function closeProviderServicesModal() {
            const modal = document.getElementById('provider-services-modal');
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }
            closeServiceDetailsModal();
        }

        function openProviderProfileModal(providerId) {
            const provider = providerProfileCache[providerId];
            const modal = document.getElementById('provider-profile-modal');
            const content = document.getElementById('provider-profile-content');

            if (!provider || !modal || !content) {
                showToast('error', 'Profile Not Available', 'Provider profile could not be loaded.');
                return;
            }

            const providerName = `${provider.First_Name || ''} ${provider.Last_Name || ''}`.trim() || 'Provider';
            const providerLocation = [provider.formatted_location, provider.Location, provider.location, provider.Address]
                .find(value => String(value || '').trim()) || '';
            const categories = Array.isArray(provider.categories) && provider.categories.length ? provider.categories : (provider.skills || []);
            const skills = Array.isArray(provider.skills) && provider.skills.length ? provider.skills : [];
            const categoriesHTML = categories.length ? categories.map(item => `<span class="skill-tag">${escapeHtml(item)}</span>`).join('') : '<span class="skill-tag">No categories listed</span>';
            const skillsHTML = skills.length ? skills.map(item => `<span class="skill-tag">${escapeHtml(item)}</span>`).join('') : '<span class="skill-tag">No skills listed</span>';
            const availableSocialLinks = (provider.social_links || []).filter(social => String(social?.link || '').trim());
            const socialHTML = availableSocialLinks.length
                ? availableSocialLinks.map(social => {
                    const iconPrefix = (social.icon_class === 'fa-envelope' || social.icon_class === 'fa-link') ? 'fa-solid' : 'fa-brands';
                    return `<a href="${escapeHtml(social.link || '#')}" title="${escapeHtml(social.name || 'Link')}" target="_blank" rel="noopener noreferrer" style="background-color: ${escapeHtml(social.color || '#008500')};"><i class="${iconPrefix} ${escapeHtml(social.icon_class || 'fa-link')}"></i></a>`;
                }).join('')
                : '<span style="font-size: 13px; color: #94a3b8;">No social links</span>';

            content.innerHTML = `
                <div style="display:grid; gap:18px; padding:0 20px 16px;">
                    <div style="position:relative; overflow:hidden; border-radius:22px; border:1px solid #e5e7eb; background:linear-gradient(135deg, #0f172a 0%, #0f3d2e 48%, #008500 100%); color:#fff; box-shadow:0 18px 40px rgba(15,23,42,0.18);">
                        <div style="position:absolute; inset:0; background: radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 28%), radial-gradient(circle at left bottom, rgba(255,255,255,0.12), transparent 22%);"></div>
                        <div style="position:relative; padding:28px; display:grid; grid-template-columns: 132px minmax(0,1fr) auto; gap:22px; align-items:center;">
                            <div style="width:132px; height:132px; border-radius:28px; padding:5px; background:rgba(255,255,255,0.16); box-shadow:0 16px 30px rgba(0,0,0,0.18);">
                                <img src="<?= BASE_URL ?>/file/user-files/${escapeHtml(provider.avatar || '')}" alt="${escapeHtml(providerName)}" style="width:100%; height:100%; object-fit:cover; border-radius:23px; background:#fff;">
                            </div>
                            <div style="min-width:0;">
                                <div style="font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; opacity:0.85; margin-bottom:8px;">Provider Profile</div>
                                <h2 style="margin:0; font-size:30px; line-height:1.2; font-weight:800;">${escapeHtml(providerName)}</h2>
                                <div style="margin-top:10px; display:flex; flex-wrap:wrap; gap:10px; align-items:center; color:rgba(255,255,255,0.92); font-size:14px;">
                                    ${providerLocation ? `<span style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:rgba(255,255,255,0.14);"><i class="fa-solid fa-location-dot"></i>${escapeHtml(providerLocation)}</span>` : ''}
                                    <span style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:rgba(255,255,255,0.14);"><i class="fa-solid fa-star" style="color:#fbbf24;"></i>${escapeHtml(provider.rating || 0)}</span>
                                </div>
                            </div>
                            <div style="display:flex; flex-direction:column; gap:10px; justify-self:end;">
                                <button type="button" class="action-btn btn-view" style="border-color: rgba(255,255,255,0.3); color:#fff; background:rgba(255,255,255,0.08);" onclick="openProviderServicesFromProfile(${provider.Provider_ID}, '${escapeHtml(providerName).replace(/'/g, "\\'")}')"><i class="fa-solid fa-briefcase"></i> View Services</button>
                                <button type="button" class="action-btn btn-edit" onclick="openProviderServicesFromProfile(${provider.Provider_ID}, '${escapeHtml(providerName).replace(/'/g, "\\'")}')"><i class="fa-solid fa-paper-plane"></i> Hire</button>
                            </div>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns: minmax(0, 1.35fr) minmax(320px, 0.85fr); gap:18px; align-items:start;">
                        <div style="display:grid; gap:18px;">
                            <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">About</div><p style="margin:0; color:#334155; line-height:1.8; font-size:14px; white-space:pre-wrap;">${escapeHtml(provider.Bio || 'Experienced professional ready to help with your project.')}</p></div>
                            <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Categories</div><div style="display:flex; flex-wrap:wrap; gap:8px;">${categoriesHTML}</div></div>
                            <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Skills</div><div style="display:flex; flex-wrap:wrap; gap:8px;">${skillsHTML}</div></div>
                        </div>
                        <div style="display:grid; gap:18px;">
                            <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
                                <div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Stats</div>
                                <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:10px;">
                                    <div class="stat-pill" style="background:#eff6ff; border-color:#bfdbfe;"><b>${escapeHtml(provider.rating || 0)}</b><br>Rating</div>
                                    <div class="stat-pill" style="background:#f8fafc; border-color:#e2e8f0;"><b>${Array.isArray(provider.categories) ? provider.categories.length : 0}</b><br>Categories</div>
                                    <div class="stat-pill" style="background:#fdf4ff; border-color:#f5d0fe;"><b>${provider.social_links ? provider.social_links.length : 0}</b><br>Links</div>
                                </div>
                            </div>
                            <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Social Links</div><div class="provider-social" style="justify-content:flex-start; gap:10px; padding-top:0; border-top:none;">${socialHTML}</div></div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:2px;"><button type="button" class="action-btn btn-delete" onclick="closeProviderProfileModal()">Close</button></div>
                </div>
            `;

            bringProviderDialogToFront(modal);
            modal.classList.add('dialog-box-2-view');
        }

        function closeProviderProfileModal() {
            const modal = document.getElementById('provider-profile-modal');
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }
        }

        function openProviderServicesFromProfile(providerId, providerName) {
            const modal = document.getElementById('provider-services-modal');
            const modalTitle = document.getElementById('modal-provider-name');
            if (!providerId || !modal || !modalTitle) {
                showToast('error', 'Modal Error', 'Provider services modal not found');
                return;
            }
            modalTitle.textContent = providerName || 'Provider';
            bringProviderDialogToFront(modal);
            modal.classList.add('dialog-box-2-view');
            loadProviderServices(providerId);
        }

        function renderServiceCardInProviderModal(service) {
            providerServiceCache[service.Provider_Categories_ID] = service;
            const skillsHTML = (service.skills || []).map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('');
            const linksHTML = (service.show_links || []).map(link => `<a href="${escapeHtml(link.url)}" target="_blank" rel="noopener noreferrer" class="action-btn btn-view" style="padding: 7px 12px; font-size: 12px; text-decoration: none;"><i class="fa-solid fa-link"></i> ${escapeHtml(link.label)}</a>`).join('');
            const successiveRate = service.success_rate_display || `${Math.max(0, Math.min(100, Math.round(Number(service.Rating || 0))))}%`;

            return `
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background: #f9fafb; transition: all 0.2s ease;">
                    <div style="margin-bottom: 12px;">
                        <h3 style="font-size: 16px; font-weight: 600; color: #1f2937; margin: 0 0 8px 0; line-height: 1.4;">${escapeHtml(service.Title || 'Untitled Service')}</h3>
                        <p style="font-size: 14px; color: #6b7280; margin: 0 0 12px 0; line-height: 1.5;">${escapeHtml(service.Description ? service.Description.substring(0, 150) + (service.Description.length > 150 ? '...' : '') : 'No description')}</p>
                    </div>
                    ${skillsHTML ? `<div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 6px;">${skillsHTML}</div>` : ''}
                    ${linksHTML ? `<div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 8px;">${linksHTML}</div>` : ''}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                        <div style="font-size: 14px; color: #4b5563;">
                            <span style="font-weight: 600; color: #008500;">${escapeHtml(service.price_display || 'Contact for price')}</span>
                            <span style="color: #9ca3af; font-size: 13px;"> • ${escapeHtml(service.rate_type_display || service.Price_Type || 'N/A')}</span>
                        </div>
                        <span style="font-size: 12px; color: #9ca3af;">${escapeHtml(service.Category_Name || service.CategoryName || 'Service')}</span>
                    </div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
                        <span class="skill-tag" style="background:#fff7ed; color:#9a3412; border-color:#fed7aa;">Successive Rate: ${escapeHtml(successiveRate)}</span>
                    </div>
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <button class="action-btn btn-view" type="button" style="padding: 7px 12px; font-size: 12px;" onclick="openServiceCardRequestModal(${service.Provider_Categories_ID})"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                        <button class="action-btn btn-edit" type="button" style="padding: 7px 12px; font-size: 12px;" onclick="openServiceDetailsModal(${service.Provider_Categories_ID})"><i class="fa-solid fa-eye"></i> View Service</button>
                    </div>
                </div>
            `;
        }

        function loadProviderServices(providerId) {
            const modalContent = document.getElementById('modal-services-content');
            if (!modalContent) return;

            modalContent.innerHTML = '<div style="text-align: center; padding: 60px 20px; color: #6b7280;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 20px; color: #008500; opacity: 0.8;"></i><p style="font-size: 16px; font-weight: 500; color: #1f2937;">Loading services...</p></div>';

            fetch(`<?= BASE_URL ?>/providers/services?provider_id=${providerId}&limit=10`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => JSON.parse(data))
                .then(data => {
                    if (!data.success) {
                        modalContent.innerHTML = `<p style="text-align: center; padding: 40px; color: #ef4444;">${escapeHtml(data.message || 'Failed to load services')}</p>`;
                        return;
                    }

                    if (!data.services || data.services.length === 0) {
                        modalContent.innerHTML = '<p style="text-align: center; padding: 40px; color: #6b7280;">No services found for this provider.</p>';
                        return;
                    }

                    const servicesHTML = data.services.map(service => renderServiceCardInProviderModal(service)).join('');
                    modalContent.innerHTML = `<div style="display: grid; grid-template-columns: 1fr; gap: 16px; padding: 0 20px;">${servicesHTML}</div>`;
                })
                .catch(error => {
                    console.error('Error loading services:', error);
                    modalContent.innerHTML = `<p style="text-align: center; padding: 40px; color: #ef4444;">Error loading services: ${escapeHtml(error.message)}</p>`;
                });
        }

        function openServiceDetailsModal(serviceId) {
            const service = providerServiceCache[serviceId] || serviceCardsCache[serviceId];
            const detailsModal = document.getElementById('provider-service-details-modal');
            const detailsTitle = document.getElementById('service-details-title');
            const detailsContent = document.getElementById('service-details-content');

            if (!service || !detailsModal || !detailsTitle || !detailsContent) {
                showToast('error', 'Service Not Found', 'Service details not available');
                return;
            }

            detailsTitle.textContent = service.Title || 'Service Details';
            detailsContent.innerHTML = renderServiceCardDetailsMarkup(service);
            bringProviderDialogToFront(detailsModal);
            detailsModal.classList.add('dialog-box-2-view');
        }

        function resetProviderRequestFloatingLabels(form) {
            if (!form) return;
            form.querySelectorAll('.text-label, .search-dropdown-label, .dropdown-label').forEach(label => {
                label.classList.remove('label-float');
                label.style.color = 'var(--textFieldLabelColor)';
            });
        }

        function clearProviderRequestValidation(form) {
            if (!form) return;
            form.querySelectorAll('.validation-tooltip').forEach(tooltip => tooltip.remove());
            form.querySelectorAll('.text-field, .text-field-search-dropdown, textarea').forEach(input => {
                input.classList.remove('error');
            });
        }

        function validateProviderRequestForm() {
            const form = document.getElementById('provider-request-form');
            if (!form) return false;
            clearProviderRequestValidation(form);

            const titleInput = document.getElementById('provider-request-title');
            const descriptionInput = document.getElementById('provider-request-description');
            const estDateInput = document.getElementById('provider-request-est-date');
            let isValid = true;

            if (!titleInput || !titleInput.value.trim()) {
                if (titleInput) showValidationTooltip(titleInput, 'Title is required');
                isValid = false;
            }
            if (!descriptionInput || !descriptionInput.value.trim()) {
                if (descriptionInput) showValidationTooltip(descriptionInput, 'Description is required');
                isValid = false;
            }
            if (!estDateInput || !estDateInput.value) {
                if (estDateInput) showValidationTooltip(estDateInput, 'Estimated date is required');
                isValid = false;
            }

            return isValid;
        }

        function closeProviderRequestModal() {
            const modal = document.getElementById('provider-request-modal');
            const form = document.getElementById('provider-request-form');
            if (form) {
                clearProviderRequestValidation(form);
                form.reset();
                resetProviderRequestFloatingLabels(form);
            }
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }
        }

        async function submitProviderServiceRequest(skipConfirmation = false) {
            const serviceId = document.getElementById('provider-request-service-id').value;
            const providerId = document.getElementById('provider-request-provider-id').value;
            if (!serviceId || !providerId) {
                showToast('error', 'Form Error', 'Service request form not available');
                return;
            }

            if (!validateProviderRequestForm()) {
                return;
            }

            if (!skipConfirmation) {
                const serviceNumericId = parseInt(serviceId, 10);
                const service = serviceCardsCache[serviceNumericId] || providerServiceCache[serviceNumericId];
                const currentStatus = (service?.request_status || '').trim().toLowerCase();

                if (currentStatus && currentStatus !== 'open') {
                    const confirmed = await showRequestConfirmationModal();
                    if (!confirmed) {
                        return;
                    }
                }
            }

            const payload = new URLSearchParams({
                provider_categories_id: serviceId,
                provider_id: providerId,
                title: (document.getElementById('provider-request-title').value || '').trim(),
                description: (document.getElementById('provider-request-description').value || '').trim(),
                requesting_price: (document.getElementById('provider-request-price').value || '').trim() || '0',
                price_type: 'Fixed',
                est_date: document.getElementById('provider-request-est-date').value,
                level: 'Beginner',
                end_at: ''
            });

            fetch(`<?= BASE_URL ?>/requests/direct-request`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: payload.toString()
            })
                .then(response => response.text())
                .then(text => JSON.parse(text))
                .then(data => {
                    if (!data.success) {
                        showToast('error', 'Request Failed', data.message || 'Failed to send request');
                        return;
                    }

                    const id = parseInt(serviceId, 10);
                    if (serviceCardsCache[id]) serviceCardsCache[id].request_status = (data.request_status || 'open');
                    if (providerServiceCache[id]) providerServiceCache[id].request_status = (data.request_status || 'open');

                    closeServiceDetailsModal();
                    closeProviderRequestModal();
                    closeProviderServicesModal();
                    showToast('success', 'Success', data.message || 'Service request sent successfully');
                    loadServiceCards(serviceState.page);
                })
                .catch(error => {
                    console.error('Error sending service request:', error);
                    showToast('error', 'Error', 'Failed to send service request');
                });
        }

        function setActiveTab(tabName) {
            activeTab = tabName;

            if (tabName === 'services') {
                document.querySelector('.results-layout')?.classList.remove('providers-mode');
                serviceTab.classList.add('active');
                providerTab.classList.remove('active');
                serviceSection.classList.add('active');
                providerSection.classList.remove('active');
                servicesFiltersPanel.classList.remove('hidden');
                renderSortOptions(serviceSortConfig, serviceState.sort);
                searchInput.value = serviceState.search;
            } else {
                document.querySelector('.results-layout')?.classList.add('providers-mode');
                providerTab.classList.add('active');
                serviceTab.classList.remove('active');
                providerSection.classList.add('active');
                serviceSection.classList.remove('active');
                servicesFiltersPanel.classList.add('hidden');
                renderSortOptions(providerSortConfig, providerState.sort);
                searchInput.value = providerState.search;
                loadProviders(1, false);
            }
        }

        const debounce = (fn, delay) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn(...args), delay);
            };
        };

        const debouncedSearch = debounce(function (value) {
            if (activeTab === 'services') {
                serviceState.search = value;
                loadServiceCards(1);
            } else {
                providerState.search = value;
                loadProviders(1, false);
            }
        }, 250);

        if (sortInput && sortContainer) {
            sortInput.addEventListener('click', function () {
                sortContainer.classList.toggle('dropdown-view');
            });
        }

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

        document.querySelectorAll('.filter-options li').forEach(function (li) {
            li.addEventListener('click', function (e) {
                // Location filter has its own dedicated district/city checkbox logic.
                // Skip the generic handler to avoid city clicks toggling the parent district.
                if (li.closest('#locationFilterOptions')) {
                    return;
                }

                if (e.target.tagName === 'INPUT') return;
                const input = this.querySelector('input');
                if (!input) return;

                if (input.type === 'checkbox') {
                    input.checked = !input.checked;
                } else if (input.type === 'radio') {
                    input.checked = true;
                }

                // Trigger realtime filter listeners even when user clicks the row label area.
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });

        initializeLocationFilter();

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                debouncedSearch(this.value.trim());
            });
        }

        document.querySelectorAll('input[name="price_range"], input[name="completion_range"], input[name="pricing_type"], input[name="category_ids[]"]').forEach(function (input) {
            input.addEventListener('change', function () {
                applyServiceFiltersFromUI();
                loadServiceCards(1);
            });
        });

        const requestForm = document.getElementById('provider-request-form');
        if (requestForm) {
            requestForm.querySelectorAll('.text-field, .text-field-search-dropdown, textarea').forEach(input => {
                const clearValidation = () => {
                    input.classList.remove('error');
                    const tooltip = input.parentNode ? input.parentNode.querySelector('.validation-tooltip') : null;
                    if (tooltip) {
                        tooltip.remove();
                    }
                };
                input.addEventListener('input', clearValidation);
                input.addEventListener('change', clearValidation);
                input.addEventListener('focus', clearValidation);
            });
        }

        serviceTab.addEventListener('click', function () {
            setActiveTab('services');
        });

        providerTab.addEventListener('click', function () {
            setActiveTab('providers');
        });

        applyServiceFiltersFromUI();
        setActiveTab('services');
        loadServiceCards(1);

        window.loadProviders = loadProviders;
        window.openProviderServicesModal = openProviderServicesModal;
        window.openProviderProfileModal = openProviderProfileModal;
        window.closeProviderProfileModal = closeProviderProfileModal;
        window.openProviderServicesFromProfile = openProviderServicesFromProfile;
        window.closeProviderServicesModal = closeProviderServicesModal;
        window.loadProviderServices = loadProviderServices;
        window.closeServiceDetailsModal = closeServiceDetailsModal;
        window.openServiceCardDetailsModal = openServiceCardDetailsModal;
        window.openServiceCardRequestModal = openServiceCardRequestModal;
        window.openServiceDetailsModal = openServiceDetailsModal;
        window.closeProviderRequestModal = closeProviderRequestModal;
        window.submitProviderServiceRequest = submitProviderServiceRequest;
        window.closeRequestConfirmationModal = closeRequestConfirmationModal;
    });
</script>

</html>
