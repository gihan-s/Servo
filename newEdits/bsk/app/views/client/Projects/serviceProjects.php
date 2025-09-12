<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <style>
        /* Unified card styling to match servicePosts_client.php */
        .service-requests .item-list {
            display: grid;
            gap: 15px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }

        .service-requests .search-item {
            background: #fff;
            border-radius: 16px;
            padding: 26px 26px 22px;
            box-shadow: 0 0 6px 1px rgba(0, 0, 0, .1);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            overflow: hidden;
            border: none !important;
        }

        .service-requests .search-item::before {
            content: none;
        }

        .service-requests .search-item:hover {
            box-shadow: 0 3px 10px 2px rgba(0, 0, 0, .1);
            border: none !important;
        }

        .service-requests .search-item:focus-within {
            border: none !important;
        }

        .service-requests .search-item,
        .service-requests .search-item:hover,
        .service-requests .search-item:focus,
        .service-requests .search-item:focus-visible {
            outline: none !important;
        }

        .service-requests .search-item .button button:focus,
        .service-requests .search-item .button button:hover,
        .service-requests .search-item .button button:focus-visible {
            outline: none !important;
            box-shadow: none;
        }

        /* Head section refinements */
        .service-requests .item-head {
            display: flex;
            gap: 1.2rem;
            align-items: flex-start;
            margin-bottom: .5rem;
        }

        .service-requests .item-head .item-img img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f1f5f9;
        }

        .service-requests .item-head .item-name {
            font-size: 15px;
            font-weight: 600;
            color: #008500;
            line-height: 1.4;
            letter-spacing: .3px;
            text-transform: capitalize;
        }

        .service-requests .item-head .item-name:hover {
            text-decoration: underline;
        }

        .service-requests .item-head .item-title {
            font-size: 19px;
            font-weight: 700;
            line-height: 1.35;
            color: #1f2937;
            margin-top: 2px;
        }

        .service-requests .item-head .item-district {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            letter-spacing: .25px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .service-requests .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            letter-spacing: .5px;
            text-transform: uppercase;
            line-height: 1;
            background: transparent;
            border: 1px solid transparent;
        }

        .service-requests .status-bottom {
            display: flex;
            justify-content: flex-end;
            margin-top: 14px;
            padding: 0;
            position: relative;
            background: transparent;
        }

        /* Light themed status variants (only green tone derives from #008500) */
        /* Status base styling with colored dot indicators */
        /* Align with payment status color system */
    .service-requests .status-chip.status-awaiting,
    .service-requests .status-chip.status-progress,
    .service-requests .status-chip.status-review,
    .service-requests .status-chip.status-complete { border:1px solid; }

        /* Awaiting Acceptance -> pending style */
    .service-requests .status-chip.status-awaiting { background:#fef3c7; color:#d97706; border-color:#fed7aa; }

        /* draft style */
        /* In Progress -> active style */
    /* In Progress now violet */
    .service-requests .status-chip.status-progress { background:#f2effd; color:#4c1d95; border-color:#d7ccfa; }

        /* Pending Review -> refunded color family (blue) repurposed */
    .service-requests .status-chip.status-review { background:#f2f7fd; color:#0369a1; border-color:#d4e6f6; }

        /* Completed -> custom violet aligned to theme but distinct */
    /* Completed now green */
    .service-requests .status-chip.status-complete { background:#dcfce7; color:#047857; border-color:#bbf7d0; }

        /* Middle row */
        .service-requests .item-middle {
            display: flex;
            gap: 2.2rem;
            margin: .8rem 0 .4rem;
            color: #64748b;
            font-size: 12px;
            flex-wrap: wrap;
        }

        .service-requests .item-middle .success i {
            color: #008500;
        }

        /* Description */
        .service-requests .item-description {
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
            margin-top: .35rem;
        }

        /* Action buttons inside .button container */
        .service-requests .search-item .button {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-left: auto;
        }

        /* Base icon/utility buttons */
        .service-requests .search-item .button button {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #334155;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all .25s ease;
        }

        .service-requests .search-item .button button:hover {
            background: #e2e8f0;
            color: #111827;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #008500 !important;
            border: 1px solid #008500 !important;
            color: #fff !important;
            box-shadow: 0 4px 10px -2px rgba(0, 133, 0, .4);
        }

        .btn-primary:hover {
            filter: brightness(.95);
        }

        .btn-danger {
            background: #fee2e2 !important;
            color: #b91c1c !important;
            border: 1px solid #fecaca !important;
        }

        .btn-danger:hover {
            background: #fecaca !important;
        }

        .btn-outline {
            background: #fff !important;
            border: 1px solid #cbd5e1 !important;
            color: #334155 !important;
        }

        .btn-outline:hover {
            border-color: #008500 !important;
            color: #008500 !important;
        }

        /* Pagination buttons alignment with new style */
        .service-requests .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 40px auto 10px;
            flex-wrap: wrap;
        }

        .service-requests .pagination .page-btn {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: #374151;
            min-width: 40px;
            height: 40px;
            padding: 0 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .25s ease;
        }

        .service-requests .pagination .page-btn:hover {
            border-color: #008500;
            color: #008500;
            transform: translateY(-2px);
        }

        .service-requests .pagination .page-btn.active {
            background: linear-gradient(135deg, #008500, #006600);
            color: #ffffff;
            border: 1px solid #008500;
            box-shadow: 0 4px 12px rgba(0, 133, 0, 0.25);
        }

        .service-requests .pagination .page-btn.prev,
        .service-requests .pagination .page-btn.next {
            padding: 0 18px;
        }

        @media (max-width:600px) {
            .service-requests .pagination .page-btn {
                min-width: 36px;
                height: 36px;
                font-size: 13px;
            }
        }

        .section-note {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 14px;
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .service-requests .item-list {
                gap: 16px;
            }

            .service-requests .search-item {
                padding: 20px;
            }

            .service-requests .item-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .service-requests .search-item .button {
                margin-left: 0;
            }
        }
    </style>
    <title>Service Requests & Projects</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <h1>Service Requests & Projects</h1>
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
                <div id="pending-requests" class="buttons active" data-target="pending-requests">Pending Requests</div>
                <div id="in-progress-requests" class="buttons" data-target="in-progress-requests">In Progress</div>
                <div id="pending-review" class="buttons" data-target="pending-review">Pending Review</div>
                <div id="completed-jobs" class="buttons" data-target="completed-jobs">Completed</div>
            </div>
        </div>
        <div class="request-content">
            <div class="pending-requests active requests-section" id="section-pending">
                <p class="section-note">Requests you sent to providers after reviewing proposals. Awaiting provider
                    acceptance or action.</p>
                <div class="item-list">
                    <div class="search-item" data-status="awaiting">
                        <div class="item-head">
                            <div class="item-img"><img src="<?= BASE_URL ?>/assets/img/waving_graphic.png" alt="Provider avatar"></div>
                            <div class="item-main-dets">
                                <div class="item-name">Chethiya Bandara</div>
                                <div class="item-title">Social Media Post Series (8 graphics)</div>
                                <div class="item-district">
                                    <span>Sent 15 Jul 2025 | 17:55</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline" title="View Request" id="request-det-pop-up"><i
                                        class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline" title="Message Provider"><i
                                        class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger" title="Cancel Request"><i
                                        class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-tag"></i> Proposed: $40/hr</div>
                            <div><i class="fa-regular fa-shield-check"></i> Escrow Pending</div>
                        </div>
                        <div class="item-description">Awaiting provider confirmation for design of 8 event/class
                            promotional posts using provided branding.</div>
                        <div class="status-bottom"><span class="status-chip status-awaiting"><i
                                    class="fa-regular fa-hourglass"></i> Awaiting Acceptance</span></div>
                    </div>
                    <div class="search-item" data-status="awaiting">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt="Provider avatar"></div>
                            <div class="item-main-dets">
                                <div class="item-name">Nimal Perera</div>
                                <div class="item-title">Mobile App Layout Refinement</div>
                                <div class="item-district">
                                    <span>Sent 10 Aug 2025 | 14:30</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-tag"></i> Proposed: $50/hr</div>
                            <div><i class="fa-regular fa-shield-check"></i> Escrow Pending</div>
                        </div>
                        <div class="item-description">UI flow refinement + accessibility improvements for onboarding &
                            dashboard screens.</div>
                        <div class="status-bottom"><span class="status-chip status-awaiting"><i
                                    class="fa-regular fa-hourglass"></i> Awaiting Acceptance</span></div>
                    </div>

                    <div class="search-item" data-status="awaiting">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt="Provider avatar"></div>
                            <div class="item-main-dets">
                                <div class="item-name">Sunil Fernando</div>
                                <div class="item-title">Custom E‑commerce Build</div>
                                <div class="item-district">
                                    <span>Sent 5 Aug 2025 | 09:15</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-tag"></i> Proposed: $60/hr</div>
                            <div><i class="fa-regular fa-wallet"></i> Budget Range: $3k‑$4k</div>
                        </div>
                        <div class="item-description">Full stack store build with product management & card payments;
                            tech pref: Laravel + Vue.</div>
                        <div class="status-bottom"><span class="status-chip status-awaiting"><i
                                    class="fa-regular fa-hourglass"></i> Awaiting Acceptance</span></div>
                    </div>

                    <div class="search-item" data-status="awaiting">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt="Provider avatar"></div>
                            <div class="item-main-dets">
                                <div class="item-name">Kamal Silva</div>
                                <div class="item-title">Blog Content Batch (10 posts)</div>
                                <div class="item-district">
                                    <span>Sent 20 Jul 2025 | 11:00</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-tag"></i> Proposed: $30/hr</div>
                            <div><i class="fa-regular fa-chart-line"></i> Est: 20 hrs</div>
                        </div>
                        <div class="item-description">SEO blog series for SaaS onboarding topics; outlines already
                            prepared.</div>
                        <div class="status-bottom"><span class="status-chip status-awaiting"><i
                                    class="fa-regular fa-hourglass"></i> Awaiting Acceptance</span></div>
                    </div>

                    <div class="search-item" data-status="awaiting">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt="Provider avatar"></div>
                            <div class="item-main-dets">
                                <div class="item-name">Ruwan Wijesinghe</div>
                                <div class="item-title">Promo Video Editing (5 clips)</div>
                                <div class="item-district">
                                    <span>Sent 12 Aug 2025 | 16:20</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-tag"></i> Proposed: $45/hr</div>
                            <div><i class="fa-regular fa-chart-line"></i> Est: 18 hrs</div>
                        </div>
                        <div class="item-description">Short-form promotional edits incl. captions & audio sync for
                            product launch.</div>
                        <div class="status-bottom"><span class="status-chip status-awaiting"><i
                                    class="fa-regular fa-hourglass"></i> Awaiting Acceptance</span></div>
                    </div>

                    <div class="search-item" data-status="awaiting">
                        <div class="item-head">
                            <div class="item-img"><img src="sampleImg.jpg" alt="Provider avatar"></div>
                            <div class="item-main-dets">
                                <div class="item-name">Lalith Kumara</div>
                                <div class="item-title">Event Photography (Conference)</div>
                                <div class="item-district">
                                    <span>Sent 18 Aug 2025 | 13:45</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-tag"></i> Proposed: $55/hr</div>
                            <div><i class="fa-regular fa-calendar"></i> Event: 02 Sept</div>
                        </div>
                        <div class="item-description">Coverage for 6‑hour conference incl. edits & basic color grading.
                        </div>
                        <div class="status-bottom"><span class="status-chip status-awaiting"><i
                                    class="fa-regular fa-hourglass"></i> Awaiting Acceptance</span></div>
                    </div>

                </div>
                <div class="pagination" aria-label="Pending Requests Pagination">
                    <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="completed-jobs requests-section" id="section-completed">
                <p class="section-note">Fully completed and confirmed projects. You can review and reference past work
                    here.</p>
                <div class="item-list">
                    <div class="search-item" data-status="complete">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">Landing Page Copy Refresh</div>
                                <div class="item-district">
                                    <span>Completed 01 Aug 2025</span>
                                    <span>Total Paid: $750</span>
                                    <span>Duration: 9d</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-file"></i> Contract</button>
                                <button class="btn-primary"><i class="fa-regular fa-stars"></i> Review</button>
                            </div>
                        </div>
                        <div class="item-description">Delivered updated hero, feature blurbs and pricing copy focused on
                            conversion uplift.</div>
                        <div class="status-bottom"><span class="status-chip status-complete"><i
                                    class="fa-regular fa-circle-check"></i> Completed</span></div>
                    </div>

                </div>
                <div class="pagination" aria-label="Completed Jobs Pagination">
                    <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="pending-review requests-section" id="section-review">
                <p class="section-note">Providers marked these as finished. Review deliverables and release payment or
                    request changes.</p>
                <div class="item-list">
                    <div class="search-item" data-status="review">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">API Integration Phase 1</div>
                                <div class="item-district">
                                    <span>Submitted 03 Aug 2025</span>
                                    <span>Milestone: $1,200</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-primary"><i class="fa-regular fa-circle-check"></i> Approve &
                                    Release</button>
                                <button class="btn-outline"><i class="fa-regular fa-rotate-left"></i> Request
                                    Changes</button>
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                            </div>
                        </div>
                        <div class="item-description">OAuth, invoice and billing endpoints integrated. Validate callback
                            handling before release.</div>
                        <div class="status-bottom"><span class="status-chip status-review"><i
                                    class="fa-regular fa-clipboard-check"></i> Pending Review</span></div>
                    </div>

                </div>
                <div class="pagination" aria-label="Pending Review Pagination">
                    <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="in-progress-requests requests-section" id="section-progress">
                <p class="section-note">Active work in progress. Fund milestones, communicate, or mark work ready for
                    review.</p>
                <div class="item-list">
                    <div class="search-item" data-status="progress">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">3D Asset Pack Creation</div>
                                <div class="item-district">
                                    <span>Started 15 Aug 2025</span>
                                    <span>Hourly: $90</span>
                                    <span>Funded: $500</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-primary"><i class="fa-regular fa-dollar-sign"></i> Fund</button>
                                <button class="btn-outline"><i class="fa-regular fa-flag"></i> Mark For Review</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-hourglass"></i> ETA 6d</div>
                            <div><i class="fa-regular fa-chart-line"></i> Logged 12h</div>
                        </div>
                        <div class="item-description">Creating 15 optimized low‑poly environment props for prototype.
                        </div>
                        <div class="status-bottom"><span class="status-chip status-progress"><i
                                    class="fa-regular fa-spinner"></i> In Progress</span></div>
                    </div>
                    <div class="search-item" data-status="progress">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">Video Editing Retainer</div>
                                <div class="item-district">
                                    <span>Started 18 Aug 2025</span>
                                    <span>Hourly: $75</span>
                                    <span>Funded: $300</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-primary"><i class="fa-regular fa-dollar-sign"></i> Fund</button>
                                <button class="btn-outline"><i class="fa-regular fa-flag"></i> Mark For Review</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-hourglass"></i> Ongoing</div>
                            <div><i class="fa-regular fa-chart-line"></i> Logged 6h</div>
                        </div>
                        <div class="item-description">Weekly batch editing for marketing reels (10 clips per week) with
                            motion graphics.</div>
                        <div class="status-bottom"><span class="status-chip status-progress"><i
                                    class="fa-regular fa-spinner"></i> In Progress</span></div>
                    </div>

                    <div class="search-item" data-status="progress">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">Content Writing Batch</div>
                                <div class="item-district">
                                    <span>Started 20 Aug 2025</span>
                                    <span>Fixed: $500</span>
                                    <span>Funded: $500</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-outline"><i class="fa-regular fa-eye"></i> Deliveries</button>
                                <button class="btn-outline"><i class="fa-regular fa-flag"></i> Mark For Review</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-hourglass"></i> ETA 4d</div>
                            <div><i class="fa-regular fa-chart-line"></i> Progress 40%</div>
                        </div>
                        <div class="item-description">Producing 10 SEO blog posts + meta descriptions (phase 1).</div>
                        <div class="status-bottom"><span class="status-chip status-progress"><i
                                    class="fa-regular fa-spinner"></i> In Progress</span></div>
                    </div>

                    <div class="search-item" data-status="progress">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">Portfolio Web Build</div>
                                <div class="item-district">
                                    <span>Started 22 Aug 2025</span>
                                    <span>Hourly: $65</span>
                                    <span>Funded: $0</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-primary"><i class="fa-regular fa-dollar-sign"></i> Fund</button>
                                <button class="btn-outline"><i class="fa-regular fa-flag"></i> Mark For Review</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-hourglass"></i> ETA 8d</div>
                            <div><i class="fa-regular fa-chart-line"></i> Logged 0h</div>
                        </div>
                        <div class="item-description">Building responsive portfolio site with blog & CMS integration.
                        </div>
                        <div class="status-bottom"><span class="status-chip status-progress"><i
                                    class="fa-regular fa-spinner"></i> In Progress</span></div>
                    </div>

                </div>
                <div class="pagination" aria-label="In Progress Pagination">
                    <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
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