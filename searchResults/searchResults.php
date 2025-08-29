<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link to external CSS files -->
    <link rel="stylesheet" href="searchResultsStyle.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <title>Search for services..</title>
</head>

<body>
    <div class="search-section">
        <!-- Search Header -->

        <div class="search-header">
            <div class="search-button">
                <input type="text" placeholder="Search for services">
                <button><i class="fa-light fa-magnifying-glass"></i></button>
            </div>
            <button class="filter"><i class="fa-light fa-filter-list"></i></button>
        </div>
        <div class="search-bottom">
            <!-- Search Filters -->

            <div class="search-filters">
                <div class="main-title"><span>Filters</span>
                    <hr>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Hourly rate</span><i class="fa-light fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options active radios">
                        <li><input type="radio" name="rate" id="rate" checked>Any hourly rate</li>
                        <li><input type="radio" name="rate" id="rate">Less than $10</li>
                        <li><input type="radio" name="rate" id="rate">$10 - $30</li>
                        <li><input type="radio" name="rate" id="rate">$30 - $60</li>
                        <li><input type="radio" name="rate" id="rate">$60 & above</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Project success</span><i class="fa-light fa-chevron-down"></i></div>
                    <ul class="filter-options radios">
                        <li><input type="radio" name="success" id="success" checked>Any success rate</li>
                        <li><input type="radio" name="success" id="success">90% & up</li>
                        <li><input type="radio" name="success" id="success">80% & up</li>
                        <li><input type="radio" name="success" id="success">70% & up</li>
                        <li><input type="radio" name="success" id="success">Less than 70%</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Total Earnings</span><i class="fa-light fa-chevron-down"></i></div>
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
                    <div class="filter-title"><span>Language</span><i class="fa-light fa-chevron-down"></i></div>
                    <ul class="filter-options checkboxes">
                        <li><input type="checkbox" name="language" id="language" checked>English</li>
                        <li><input type="checkbox" name="language" id="language">Sinhala</li>
                        <li><input type="checkbox" name="language" id="language">Tamil</li>
                        <li><input type="checkbox" name="language" id="language">Other</li>
                    </ul>
                </div>
                <div class="button-apply">
                    <button>Apply filters</button>
                </div>
            </div>
            <!-- Search Content -->

            <div class="search-content">
                <div class="advance-search">
                    <div class="search-selection">
                        <div class="search-by-category selection" id="category-pop-up"
                            style="border-radius: 0.5rem 0 0 0.5rem;">
                            <i class="fa-solid fa-tag"></i><span>Web Development</span>
                        </div>
                        <hr>
                        <div class="search-by-location selection" id="location-pop-up"
                            style="border-radius: 0 0 0.5rem 0.5rem ; ">
                            <i class="fa-solid fa-location-dot"></i><span>All in SriLanka</span>
                        </div>
                    </div>
                    <div class="sort-selection">
                        <div class="selection-input-field">
                            <input type="selection-input" id="selection-input" name="sort" value="Sort By Relevence"
                                disabled><i class="fa-light fa-chevron-down"></i>
                        </div>
                        <div class="selection-options" id="selection-options">
                            <div class="opt">Sort By Relevence</div>
                            <div class="opt">Sort By Price</div>
                            <div class="opt">Sort By Rating</div>
                        </div>
                    </div>
                </div>
                <!-- Search Results -->

                <div class="item-list">
                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Chethiya Bandara</div>
                                <div class="item-title">Full Stack Web Developer - Expert in PHP, Laravel, and WordPress
                                </div>
                                <div class="item-district">Gampaha</div>
                            </div>
                            <div class="button"><button>View profile</button></div>
                        </div>
                        <div class="item-middle">
                            <div class="rate">$40/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>90% Project Success</div>
                            <div class="total-earn">$10K+ earned</div>
                        </div>
                        <div class="item-tags">
                            <span>Conten SEO</span>
                            <span>Adobe XD</span>
                            <span>Web Design</span>
                            <span>Shopify</span>
                        </div>
                        <div class="item-description">Turn Your Web App Idea into a Fast, Scalable, and Beautiful
                            Reality —
                            Delivered On Time, Every Time! Hi, I’m Junaid — a results-driven Full-Stack Web Application
                            Developer specializing in React, Next.js, MERN stack, and API integrations. Whether you need
                        </div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>
                    <hr>
                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Chethiya Bandara</div>
                                <div class="item-title">Full Stack Web Developer - Expert in PHP, Laravel, and WordPress
                                </div>
                                <div class="item-district">Gampaha</div>
                            </div>
                            <div class="button"><button>View profile</button></div>
                        </div>
                        <div class="item-middle">
                            <div class="rate">$40/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>90% Project Success</div>
                            <div class="total-earn">$10K+ earned</div>
                        </div>
                        <div class="item-tags">
                            <span>Conten SEO</span>
                            <span>Adobe XD</span>
                            <span>Web Design</span>
                            <span>Shopify</span>
                        </div>
                        <div class="item-description">Turn Your Web App Idea into a Fast, Scalable, and Beautiful
                            Reality —
                            Delivered On Time, Every Time! Hi, I’m Junaid — a results-driven Full-Stack Web Application
                            Developer specializing in React, Next.js, MERN stack, and API integrations. Whether you need
                        </div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pop-up for Category Selection -->

    <div class="pop-up-section deactive category-pop-up">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Select Category</div>
                <i class="fa-light fa-xmark" id="category-pop-up"></i>
            </div>
            <hr>
            <div class="pop-search-button">
                <i class="fa-light fa-magnifying-glass"></i><input type="text" placeholder="Search categories....">
            </div>
            <div class="pop-up-content">
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Web Development</div>
                    <div class="pop-up-item-count">(100)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Graphic Design</div>
                    <div class="pop-up-item-count">(50)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Digital Marketing</div>
                    <div class="pop-up-item-count">(30)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Content Writing</div>
                    <div class="pop-up-item-count">(20)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Data Entry</div>
                    <div class="pop-up-item-count">(10)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Web Development</div>
                    <div class="pop-up-item-count">(100)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Graphic Design</div>
                    <div class="pop-up-item-count">(50)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Digital Marketing</div>
                    <div class="pop-up-item-count">(30)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Content Writing</div>
                    <div class="pop-up-item-count">(20)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Data Entry</div>
                    <div class="pop-up-item-count">(10)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Web Development</div>
                    <div class="pop-up-item-count">(100)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Graphic Design</div>
                    <div class="pop-up-item-count">(50)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Digital Marketing</div>
                    <div class="pop-up-item-count">(30)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Content Writing</div>
                    <div class="pop-up-item-count">(20)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Data Entry</div>
                    <div class="pop-up-item-count">(10)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pop-up for Location Selection -->

    <div class="pop-up-section deactive location-pop-up">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Select Location</div>
                <i class="fa-light fa-xmark" id="location-pop-up"></i>
            </div>
            <hr>
            <div class="pop-search-button">
                <i class="fa-light fa-magnifying-glass"></i><input type="text" placeholder="Search location....">
            </div>
            <div class="pop-up-content">
                <div class="pop-up-item">
                    <div class="pop-up-item-name">All in SriLanka</div>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Colombo</span><i class="fa-light fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options radios">
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">All in Colombo</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Bambalapitiya</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Kollupitiya</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Gampaha</span><i class="fa-light fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options radios">
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">All in Gampaha</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Yakkala</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Mirigama</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Kandy</span><i class="fa-light fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options radios">
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">All in Kandy</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Kadugannawa</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Gampola</div>
                            </div>
                        </li>
                    </ul>
                </div>


            </div>
        </div>
    </div>

</body>
<script src="searchResultsScript.js"></script>

</html>