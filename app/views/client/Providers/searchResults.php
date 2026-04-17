<?php
if (!empty($cardsOnly)) {
?>
    <div class="item-list">
        <?php for ($i = 0; $i < 2; $i++): ?>
            <div class="search-item">
                <div class="post-header">
                    <div class="post-meta">
                        <div class="provider-info">
                            <div class="provider-image"><img src="sampleImg.jpg" alt="Provider"></div>
                            <div class="provider-details">
                                <div class="provider-name">Chethiya Bandara</div>
                                <div class="provider-location">Gampaha</div>
                                <div class="provider-meta-row">
                                    <span class="provider-rating"><i class="fa-solid fa-star"></i>4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="post-actions">
                        <button class="action-btn btn-view" title="Message Provider"><i class="fa-solid fa-messages"></i> Message</button>
                        <button class="action-btn btn-view" title="View Profile"><i class="fa-solid fa-user"></i> View Profile</button>
                        <button class="action-btn btn-edit" title="Hire"><i class="fa-solid fa-briefcase"></i> Hire</button>
                    </div>
                </div>
                <h3 class="post-title">Full Stack Web Developer - Expert in PHP, Laravel, and WordPress</h3>
                <div class="post-description">Turn Your Web App Idea into a Fast, Scalable, and Beautiful Reality - Delivered On Time, Every Time! Hi, I'm Junaid - a results-driven Full-Stack Web Application Developer specializing in React, Next.js, MERN stack, and API integrations. Whether you need</div>
                <div class="post-skills">
                    <span class="skills-label">Skills:</span>
                    <div class="skills-tags">
                        <span class="skill-tag">Content SEO</span>
                        <span class="skill-tag">Adobe XD</span>
                        <span class="skill-tag">Web Design</span>
                        <span class="skill-tag">Shopify</span>
                    </div>
                </div>
                <div class="post-footer">
                    <div class="post-details">
                        <div class="detail-item">
                            <span class="detail-label">Rate</span>
                            <span class="detail-value budget-amount">$40/hr</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Success</span>
                            <span class="detail-value">90%</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Earnings</span>
                            <span class="detail-value">$10K+</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
        <div class="pagination" aria-label="Service pagination">
            <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
<?php
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link to external CSS files -->
    <link rel="stylesheet" href="assets/css/cardList.css">
    <link rel="stylesheet" href="assets/css/clientPosts.css">
    <link rel="stylesheet" href="assets/css/searchResultsStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>Search for services..</title>
</head>

<body>
    <div class="search-section">
        <!-- Search Header -->
        <!--
        <div class="search-header">
            <div class="search-button">
                <input type="text" placeholder="Search for services">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <button class="filter"><i class="fa-solid fa-filter"></i></button>
        </div>  -->
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
                                disabled><i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="selection-options" id="selection-options">
                            <div class="opt">Sort By Relevence</div>
                            <div class="opt">Sort By Price</div>
                            <div class="opt">Sort By Rating</div>
                        </div>
                    </div>
                </div>-->
                <!-- Search Results -->

                <div class="item-list">
                    <?php for ($i = 0; $i < 2; $i++): ?>
                        <div class="search-item">
                            <div class="post-header">
                                <div class="post-meta">
                                    <div class="provider-info">
                                        <div class="provider-image"><img src="sampleImg.jpg" alt="Provider"></div>
                                        <div class="provider-details">
                                            <div class="provider-name">Chethiya Bandara</div>
                                            <div class="provider-location">Gampaha</div>
                                            <div class="provider-meta-row">
                                                <span class="provider-rating"><i class="fa-solid fa-star"></i>4.9</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <button class="action-btn btn-view" title="Message Provider"><i class="fa-solid fa-messages"></i> Message</button>
                                    <button class="action-btn btn-view" title="View Profile"><i class="fa-solid fa-user"></i> View Profile</button>
                                    <button class="action-btn btn-edit" title="Hire"><i class="fa-solid fa-briefcase"></i> Hire</button>
                                </div>
                            </div>
                            <h3 class="post-title">Full Stack Web Developer - Expert in PHP, Laravel, and WordPress</h3>
                            <div class="post-description">Turn Your Web App Idea into a Fast, Scalable, and Beautiful Reality - Delivered On Time, Every Time! Hi, I'm Junaid - a results-driven Full-Stack Web Application Developer specializing in React, Next.js, MERN stack, and API integrations. Whether you need</div>
                            <div class="post-skills">
                                <span class="skills-label">Skills:</span>
                                <div class="skills-tags">
                                    <span class="skill-tag">Content SEO</span>
                                    <span class="skill-tag">Adobe XD</span>
                                    <span class="skill-tag">Web Design</span>
                                    <span class="skill-tag">Shopify</span>
                                </div>
                            </div>
                            <div class="post-footer">
                                <div class="post-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Rate</span>
                                        <span class="detail-value budget-amount">$40/hr</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Success</span>
                                        <span class="detail-value">90%</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Earnings</span>
                                        <span class="detail-value">$10K+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                    <div class="pagination" aria-label="Provider Pagination">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
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
<script src="assets/js/searchResultsScript.js"></script>

</html>