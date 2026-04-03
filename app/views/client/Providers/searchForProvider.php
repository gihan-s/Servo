<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link to external CSS files -->
    <link rel="stylesheet" href="assets/css/cardList.css">
    <link rel="stylesheet" href="assets/css/searchForProviderStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>Search for services..</title>
</head>

<body>
    <div class="search-section">
        <!-- Search Header -->
        <!--
        <h1>Service Providers</h1>
        <div class="search-header">
            <div class="search-button">
                <input type="text" placeholder="Search for service providers...">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <button class="filter"><i class="fa-solid fa-filter"></i></button>
        </div>-->
        <div class="search-bottom">

            <!-- Search Filters -->

            <div class="search-filters">
                <div class="main-title"><span>Filters</span>
                    <hr>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Hourly rate</span><i class="fa-solid fa-chevron-down rotated"></i>
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
                    <div class="filter-title"><span>Category</span><i class="fa-solid fa-chevron-down"></i></div>
                    <ul class="filter-options checkboxes">
                        <li><input type="checkbox" name="category" id="category" checked>Web Developer</li>
                        <li><input type="checkbox" name="category" id="category">Logo Designer</li>
                        <li><input type="checkbox" name="category" id="category">Tamil</li>
                        <li><input type="checkbox" name="category" id="category">Other</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Project success</span><i class="fa-solid fa-chevron-down"></i></div>
                    <ul class="filter-options radios">
                        <li><input type="radio" name="success" id="success" checked>Any success rate</li>
                        <li><input type="radio" name="success" id="success">90% & up</li>
                        <li><input type="radio" name="success" id="success">80% & up</li>
                        <li><input type="radio" name="success" id="success">70% & up</li>
                        <li><input type="radio" name="success" id="success">Less than 70%</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Total Earnings</span><i class="fa-solid fa-chevron-down"></i></div>
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
                    <div class="filter-title"><span>Language</span><i class="fa-solid fa-chevron-down"></i></div>
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
                <!--
                <div class="advance-search">
                    <div class="sort-selection">
                        <div class="selection-input-field">
                            <input type="selection-input" id="selection-input" name="sort" value="Sort By Relevence"
                                disabled><i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="selection-options" id="selection-options">
                            <div class="opt">Sort By Relevence</div>
                            <div class="opt">Sort By Price</div>
                            <div class="opt">Sort By Rating</div>
                        </div>
                    </div>
                </div> -->
                <!-- Search Results -->

                <section class="search-results-section">
                    <div class="profiles-grid">
                        <div class="profile-card">
                            <img src="sampleImg.jpg" alt="Profile Image">
                            <h3>Alex Brown</h3>
                            <div class="categories">Data Analysis, Machine Learning</div>
                            <hr>
                            <div class="item-middle">
                                <div class="rate">Rs.400/hr</div>
                                <div class="success"><i class="fa-solid fa-shield-check"></i>90% Success</div>
                                <div class="total-earn">$10K+ earned</div>
                            </div>
                            <hr>
                            <div class="description">Data enthusiast with expertise in predictive modeling and
                                statistical analysis.</div>
                            <div class="languages">Speaks: Sinhala, Tamil, English</div>
                            <div class="social-links">
                                <a href="#" title="Twitter" style="background-color: #080808"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#" title="LinkedIn" style="background-color: #0077B5"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#" title="Email" style="background-color: #EB483B"><i class="fa-solid fa-envelope"></i></a>
                                <a href="#" title="Instagram" style="background-color: #E63D6A"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="profile-card">
                            <img src="sampleImg.jpg" alt="Profile Image">
                            <h3>Alex Brown</h3>
                            <div class="categories">Data Analysis, Machine Learning</div>
                            <hr>
                            <div class="item-middle">
                                <div class="rate">Rs.400/hr</div>
                                <div class="success"><i class="fa-solid fa-shield-check"></i>90% Success</div>
                                <div class="total-earn">$10K+ earned</div>
                            </div>
                            <hr>
                            <div class="description">Data enthusiast with expertise in predictive modeling and
                                statistical analysis.</div>
                            <div class="languages">Speaks: Sinhala, Tamil, English</div>
                            <div class="social-links">
                                <a href="#" title="Twitter" style="background-color: #080808"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#" title="LinkedIn" style="background-color: #0077B5"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#" title="Email" style="background-color: #EB483B"><i class="fa-solid fa-envelope"></i></a>
                                <a href="#" title="Instagram" style="background-color: #E63D6A"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="profile-card">
                            <img src="sampleImg.jpg" alt="Profile Image">
                            <h3>Alex Brown</h3>
                            <div class="categories">Data Analysis, Machine Learning</div>
                            <hr>
                            <div class="item-middle">
                                <div class="rate">Rs.400/hr</div>
                                <div class="success"><i class="fa-solid fa-shield-check"></i>90% Success</div>
                                <div class="total-earn">$10K+ earned</div>
                            </div>
                            <hr>
                            <div class="description">Data enthusiast with expertise in predictive modeling and
                                statistical analysis.</div>
                            <div class="languages">Speaks: Sinhala, Tamil, English</div>
                            <div class="social-links">
                                <a href="#" title="Twitter" style="background-color: #080808"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#" title="LinkedIn" style="background-color: #0077B5"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#" title="Email" style="background-color: #EB483B"><i class="fa-solid fa-envelope"></i></a>
                                <a href="#" title="Instagram" style="background-color: #E63D6A"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="profile-card">
                            <img src="sampleImg.jpg" alt="Profile Image">
                            <h3>Alex Brown</h3>
                            <div class="categories">Data Analysis, Machine Learning</div>
                            <hr>
                            <div class="item-middle">
                                <div class="rate">Rs.400/hr</div>
                                <div class="success"><i class="fa-solid fa-shield-check"></i>90% Success</div>
                                <div class="total-earn">$10K+ earned</div>
                            </div>
                            <hr>
                            <div class="description">Data enthusiast with expertise in predictive modeling and
                                statistical analysis.</div>
                            <div class="languages">Speaks: Sinhala, Tamil, English</div>
                            <div class="social-links">
                                <a href="#" title="Twitter" style="background-color: #080808"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#" title="LinkedIn" style="background-color: #0077B5"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#" title="Email" style="background-color: #EB483B"><i class="fa-solid fa-envelope"></i></a>
                                <a href="#" title="Instagram" style="background-color: #E63D6A"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Unified Pagination Component -->
                    <nav class="pagination" aria-label="Provider profile pages">
                        <button class="page-btn prev" data-page="prev" disabled title="Previous page">
                            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <button class="page-btn active" data-page="1" aria-current="page">1</button>
                        <button class="page-btn" data-page="2">2</button>
                        <button class="page-btn" data-page="3">3</button>
                        <button class="page-btn next" data-page="next" title="Next page">
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </nav>
                </section>
            </div>
        </div>
    </div>

    <!-- Pop-up for Category Selection -->

    <div class="pop-up-section deactive category-pop-up">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Select Category</div>
                <i class="fa-solid fa-xmark" id="category-pop-up"></i>
            </div>
            <hr>
            <div class="pop-search-button">
                <i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search categories....">
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
                <i class="fa-solid fa-xmark" id="location-pop-up"></i>
            </div>
            <hr>
            <div class="pop-search-button">
                <i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search location....">
            </div>
            <div class="pop-up-content">
                <div class="pop-up-item">
                    <div class="pop-up-item-name">All in SriLanka</div>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Colombo</span><i class="fa-solid fa-chevron-down rotated"></i>
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
                    <div class="filter-title"><span>Gampaha</span><i class="fa-solid fa-chevron-down rotated"></i>
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
                    <div class="filter-title"><span>Kandy</span><i class="fa-solid fa-chevron-down rotated"></i>
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
<script src="<?= BASE_URL ?>/assets/js/searchForProviderScript.js"></script>

</html>