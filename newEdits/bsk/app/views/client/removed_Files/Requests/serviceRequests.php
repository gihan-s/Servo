<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <title>Document</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <h1>Job Requests</h1>
            <div class="search-header">
                <div class="search-button">
                    <input type="text" placeholder="Search for Requests...">
                    <button><i class="fa-light fa-magnifying-glass"></i></button>
                </div>
                <button class="filter" id="filter-pop-up"><i
                        class="fa-light fa-filter-list"></i><span>Filter</span></button>
                <div class="advance-search">
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
            </div>
            <div class="container-changer">
                <div id="pending-requests" class="buttons active">Pending Requests</div>
                <div id="payment-pending-requests" class="buttons">Payment Pending Request</div>
                <div id="declined-requests" class="buttons">Declined Requests</div>
            </div>
        </div>
        <div class="request-content">
            <div class="pending-requests active requests-section">
                <div class="item-list">
                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Chethiya Bandara</div>
                                <div class="item-title">Need a Graphic designer to design class post</div>
                                <div class="item-district">15 July 2025 | 17.55</div>
                            </div>
                            <div class="button">
                                <button title="Message"><i class="fa-light fa-messages"></i></button>
                                <button title="Request Details" id="request-det-pop-up"><i
                                        class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                                <button class="accept"><i class="fa-regular fa-circle-check"
                                        style="padding-right:0.5rem"></i>Accept</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $40/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>90% Trustable</div>
                        </div>
                        <div class="item-description">Turn Your Web App Idea into a Fast, Scalable, and Beautiful
                            Reality —
                            Delivered On Time, Every Time! Hi, I’m Junaid — a results-driven Full-Stack Web Application
                            Developer specializing in React, Next.js, MERN stack, and API integrations. Whether you need
                        </div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>
                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Nimal Perera</div>
                                <div class="item-title">Need a UI/UX designer for mobile app layout</div>
                                <div class="item-district">10 August 2025 | 14:30</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                                <button class="accept"><i class="fa-regular fa-circle-check"
                                        style="padding-right:0.5rem"></i>Accept</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $50/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>85% Trustable</div>
                        </div>
                        <div class="item-description">Transform your app with sleek, user-friendly designs — delivered
                            with precision and creativity. I’m Priya, a UI/UX expert specializing in mobile and web
                            interfaces.</div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Sunil Fernando</div>
                                <div class="item-title">Require a developer for e-commerce website</div>
                                <div class="item-district">5 August 2025 | 09:15</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                                <button class="accept"><i class="fa-regular fa-circle-check"
                                        style="padding-right:0.5rem"></i>Accept</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $60/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>92% Trustable</div>
                        </div>
                        <div class="item-description">Build a robust e-commerce platform with seamless payment
                            integration. I’m Ravi, a developer skilled in Shopify, WooCommerce, and custom solutions.
                        </div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Kamal Silva</div>
                                <div class="item-title">Need a content writer for blog posts</div>
                                <div class="item-district">20 July 2025 | 11:00</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                                <button class="accept"><i class="fa-regular fa-circle-check"
                                        style="padding-right:0.5rem"></i>Accept</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $30/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>88% Trustable</div>
                        </div>
                        <div class="item-description">Craft engaging and SEO-optimized blog content to boost your online
                            presence. I’m Anu, a writer with expertise in tech and lifestyle topics.</div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Ruwan Wijesinghe</div>
                                <div class="item-title">Seeking a video editor for promotional clips</div>
                                <div class="item-district">12 August 2025 | 16:20</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                                <button class="accept"><i class="fa-regular fa-circle-check"
                                        style="padding-right:0.5rem"></i>Accept</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $45/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>95% Trustable</div>
                        </div>
                        <div class="item-description">Create stunning promotional videos with smooth edits and effects.
                            I’m Sam, a video editor experienced in Adobe Premiere and After Effects.</div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Lalith Kumara</div>
                                <div class="item-title">Need a photographer for event coverage</div>
                                <div class="item-district">18 August 2025 | 13:45</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                                <button class="accept"><i class="fa-regular fa-circle-check"
                                        style="padding-right:0.5rem"></i>Accept</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $55/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>87% Trustable</div>
                        </div>
                        <div class="item-description">Capture your event with professional-grade photography. I’m Nisha,
                            a photographer skilled in portrait and event photography.</div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                </div>
                <div class="pagination">
                    <button class="prev-page"><i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <span class="page-number">1</span>
                    <button class="next-page">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="payment-pending-requests requests-section">
                <p style="margin-bottom:0.5rem; padding: 0.5rem; color:#333;">Once the client done the full payment,
                    request will be remove from here and automatically move to the your projects section. You still have
                    chance to cancel the request before the payment is done!!!</p>
                <div class="item-list">
                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Himath Adithya</div>
                                <div class="item-title">Need a Good 3D artist</div>
                                <div class="item-district">15 August 2025 | 12.55</div>
                            </div>
                            <div class="button">
                                <button title="Message"><i class="fa-light fa-messages"></i></button>
                                <button title="Request Details"><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $90/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>79% Trustable</div>
                        </div>
                        <div class="item-description">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Iusto
                            vitae placeat molestias laborum similique! Rem doloribus culpa vero saepe omnis iure magni
                            ex ut, nulla, incidunt placeat architecto ipsa et.
                        </div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>
                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Ravi Kumar</div>
                                <div class="item-title">Need a Skilled Video Editor</div>
                                <div class="item-district">18 August 2025 | 14:30</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $75/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>83% Trustable</div>
                        </div>
                        <div class="item-description">Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                            Quisquam, quod. Fugiat laborum necessitatibus dolore, esse quisquam asperiores deserunt
                            maiores facilis nisi, velit illum.</div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Priya Menon</div>
                                <div class="item-title">Need a Content Writer for Marketing</div>
                                <div class="item-district">20 August 2025 | 10:15</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $45/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>91% Trustable</div>
                        </div>
                        <div class="item-description">Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                            Explicabo, temporibus. Quisquam, perspiciatis. Quod, doloribus! Repellat, tempora
                            asperiores! Quis, ipsum facilis.</div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Suresh Patel</div>
                                <div class="item-title">Need a Web Developer for Portfolio Site</div>
                                <div class="item-district">22 August 2025 | 16:45</div>
                            </div>
                            <div class="button">
                                <button><i class="fa-light fa-messages"></i></button>
                                <button><i class="fa-light fa-memo-circle-info"></i></button>
                                <button class="decline"><i class="fa-regular fa-circle-xmark"
                                        style="padding-right:0.5rem"></i>Decline</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $65/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>87% Trustable</div>
                        </div>
                        <div class="item-description">Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                            Voluptatem, molestiae. Quisquam, ratione. Quis, odio! Repellat, tempora asperiores! Quis,
                            ipsum facilis.</div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                </div>
                <div class="pagination">
                    <button class="prev-page"><i class="fas fa-chevron-left"></i> Previous</button>
                    <span class="page-number">1</span>
                    <button class="next-page">Next <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="declined-requests requests-section">
                <div class="item-list">
                    <div class="search-item">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt=""></div>
                            <div class="item-main-dets">
                                <div class="item-name">Pasindu Gihan</div>
                                <div class="item-title">Need a Graphic designer to design class post</div>
                                <div class="item-district">15 July 2025 | 17.55</div>
                            </div>
                            <div class="button" style="align-items:center;">
                                <button title="Message"><i class="fa-light fa-messages"></i></button>
                                <button title="Request Details"><i class="fa-light fa-memo-circle-info"></i></button>
                                Status: <span
                                    style="background-color:red; padding:0 0.5rem; border-radius: 0.2rem; color: #fff; height:fit-content">Declined</span>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div class="rate"><span>Requested Price:</span> $40/hr</div>
                            <div class="success"><i class="fa-solid fa-shield-check"></i>90% Trustable</div>
                        </div>
                        <div class="item-description">Turn Your Web App Idea into a Fast, Scalable, and Beautiful
                            Reality —
                            Delivered On Time, Every Time! Hi, I’m Junaid — a results-driven Full-Stack Web Application
                            Developer specializing in React, Next.js, MERN stack, and API integrations. Whether you need
                        </div>
                        <div class="bottom-button"><button>View profile</button></div>
                    </div>

                </div>
                <div class="pagination">
                    <button class="prev-page"><i class="fas fa-chevron-left"></i> Previous</button>
                    <span class="page-number">1</span>
                    <button class="next-page">Next <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>


    <div class="pop-up-section filter-pop-up deactive">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Add Filters</div>
                <i class="fa-light fa-xmark" id="filter-pop-up"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                <div class="search-filters">
                    <div class="filter-item">
                        <div class="filter-title"><span>Hourly rate</span><i
                                class="fa-light fa-chevron-down rotated"></i>
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
                        <div class="filter-title"><span>Project success</span><i class="fa-light fa-chevron-down"></i>
                        </div>
                        <ul class="filter-options radios">
                            <li><input type="radio" name="success" id="success" checked>Any success rate</li>
                            <li><input type="radio" name="success" id="success">90% & up</li>
                            <li><input type="radio" name="success" id="success">80% & up</li>
                            <li><input type="radio" name="success" id="success">70% & up</li>
                            <li><input type="radio" name="success" id="success">Less than 70%</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Total Earnings</span><i class="fa-light fa-chevron-down"></i>
                        </div>
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
                </div>

            </div>
            <div class="button-apply">
                <button>Apply filters</button>
            </div>
        </div>
    </div>


    <div class="pop-up-section request-det-pop-up deactive">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Request Details</div>
                <i class="fa-light fa-xmark" id="request-det-pop-up"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                <div class="request-details">
                    <ul>
                        <li>
                            <span class="det-title">Client :</span>
                            <span class="det"><a href="">Chethiya Bandara</a></span>
                        </li>
                        <li>
                            <span class="det-title">Title :</span>
                            <span class="det">Need a Graphic designer to design class post</span>
                        </li>
                        <li>
                            <span class="det-title">Description :</span>
                            <span class="det">Turn Your Web App Idea into a Fast, Scalable, and Beautiful Reality —
                                Delivered On Time, Every Time! Hi, I’m Junaid — a results-driven Full-Stack Web
                                Application Developer specializing in React, Next.js, MERN stack, and API integrations.
                                Whether you need</span>
                        </li>
                        <li>
                            <span class="det-title">Requested Price :</span>
                            <span class="det">$40/hr</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="button" style="width:100%">
                <button class="decline"><i class="fa-regular fa-circle-xmark"
                        style="padding-right:0.5rem"></i>Decline</button>
                <button class="accept"><i class="fa-regular fa-circle-check"
                        style="padding-right:0.5rem"></i>Accept</button>
            </div>
        </div>
    </div>



</body>

<script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>

</html>