<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    
    
    <script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>
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
        .service-requests .status-chip.status-complete {
            border: 1px solid;
        }

        /* Awaiting Acceptance -> pending style */
        .service-requests .status-chip.status-awaiting {
            background: #fef3c7;
            color: #d97706;
            border-color: #fed7aa;
        }

        /* draft style */
        /* In Progress -> active style */
        /* In Progress now violet */
        .service-requests .status-chip.status-progress {
            background: #f2effd;
            color: #4c1d95;
            border-color: #d7ccfa;
        }

        /* Pending Review -> refunded color family (blue) repurposed */
        .service-requests .status-chip.status-review {
            background: #f2f7fd;
            color: #0369a1;
            border-color: #d4e6f6;
        }

        /* Completed -> custom violet aligned to theme but distinct */
        /* Completed now green */
        .service-requests .status-chip.status-complete {
            background: #dcfce7;
            color: #047857;
            border-color: #bbf7d0;
        }

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
                <div id="in-progress-requests" class="buttons" data-target="in-progress-requests">Approved Requests
                </div>
                <div id="ongoing-projects" class="buttons" data-target="ongoing-projects">Ongoing Projects</div>
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
                            <div class="item-main-dets">
                                <div class="item-name">Chethiya Bandara</div>
                                <div class="item-title">Social Media Post Series (8 graphics)</div>
                                <div class="item-district">
                                    <span>Sent 15 Jul 2025 | 17:55</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Request"><i
                                        class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline" title="Message Provider" aria-label="Message"><i
                                        class="fa-regular fa-messages"></i></button>
                                <button class="btn-danger" title="Cancel Request"><i
                                        class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-tag"></i> Proposed: $40/hr</div>
                        </div>
                        <div class="item-description">Awaiting provider confirmation for design of 8 event/class
                            promotional posts using provided branding.</div>
                        <div class="status-bottom"><span class="status-chip status-awaiting">Direct Request</span></div>
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
                                <button class="btn-primary btn-approve"><i class="fa-regular fa-circle-check"></i> Approve</button>
                                <button class="btn-outline btn-request-changes"><i class="fa-regular fa-rotate-left"></i> Request
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
                <p class="section-note">Approved requests with providers. Fund milestones, communicate, or mark work
                    ready for
                    review.</p>
                <div class="item-list">
                    <div class="search-item" data-status="progress">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">3D Asset Pack Creation</div>
                                <div class="item-district">
                                    <span>Started 15 Aug 2025</span>
                                    <span>Hourly: $90</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-primary btn-pay"><i class="fa-regular fa-dollar-sign"></i> Pay</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-hourglass"></i> Estimated Time 6d</div>
                        </div>
                        <div class="item-description">Creating 15 optimized low‑poly environment props for prototype.
                        </div>
                        <div class="status-bottom"><span class="status-chip status-progress">bid request</span></div>
                    </div>

                </div>
                <div class="pagination" aria-label="Approved Requests Pagination">
                    <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="ongoing-projects requests-section" id="section-progress">
                <p class="section-note">Approved requests with providers. Fund milestones, communicate, or mark work
                    ready for
                    review.</p>
                <div class="item-list">
                    <div class="search-item" data-status="progress">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">3D Asset Pack Creation</div>
                                <div class="item-district">
                                    <span>Started 15 Aug 2025</span>
                                    <span>Hourly: $90</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Project"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline" title="Message Provider"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-hourglass"></i> ETA 6d</div>
                        </div>
                        <div class="progress-container" aria-label="Project progress">
                            <div class="progress-label">Progress: <span class="progress-percent">0%</span> <span class="progress-detail" style="color:#64748b;">(0h of 0h)</span></div>
                            <div class="progress-track"><div class="progress-fill"></div></div>
                        </div>
                        <div class="item-description">Creating 15 optimized low‑poly environment props for prototype.
                        </div>
                        <div class="status-bottom"><span class="status-chip status-progress">Direct Request</span></div>
                    </div>

                </div>
                <div class="pagination" aria-label="Approved Requests Pagination">
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

    <style>
        /* Themed Request Modal */
        .request-modal {
            position: absolute;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(2px);
            z-index: 6000;
            padding: 24px;
        }

        .request-modal.active {
            display: flex;
        }

        .request-modal.deactive {
            display: none;
        }

        .request-modal .pop-up {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            width: min(680px, 96vw);
            max-height: 80vh;
            overflow: auto;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, .28);
            padding: 18px 18px 12px;
        }

        .request-modal .pop-up-header .pop-up-title {
            color: #008500;
            font-weight: 800;
        }

        .request-modal .pop-up-header i {
            color: #008500;
        }

        .request-modal .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #008500;
            border: 1px solid #bbf7d0;
            font-weight: 700;
            font-size: 12px;
        }

        .request-modal .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 12px;
        }

        .request-modal .btn-primary,
        .request-modal .btn-danger,
        .request-modal .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            border-width: 1px;
            border-style: solid;
            cursor: pointer;
            line-height: 1;
            transition: all .2s ease-in-out;
        }

        .request-modal .btn-primary {
            background: #008500;
            border-color: #008500;
            color: #ffffff;
        }

        .request-modal .btn-primary:hover {
            box-shadow: 0 6px 18px -6px rgba(0, 133, 0, .45);
            transform: translateY(-1px);
        }

        .request-modal .btn-outline {
            background: #fff;
            border-color: #008500;
            color: #008500;
        }

        .request-modal .btn-outline:hover {
            background: #ecfdf5;
            border-color: #008500;
            color: #006b00;
        }

        .request-modal .btn-danger {
            background: #fff;
            border-color: #ef4444;
            color: #b91c1c;
        }

        .request-modal .btn-danger:hover {
            background: #fff5f5;
            border-color: #dc2626;
            color: #991b1b;
        }

        .request-modal .btn-primary i,
        .request-modal .btn-danger i,
        .request-modal .btn-outline i {
            font-size: 14px;
        }

        /* Confirm Cancel Modal (overlay above request modal) */
        .confirm-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .5);
            backdrop-filter: blur(2px);
            z-index: 7000;
            padding: 24px;
        }

        .confirm-modal.active {
            display: flex;
        }

        .confirm-modal.deactive {
            display: none;
        }

        .confirm-modal .pop-up {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            width: min(460px, 95vw);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, .28);
            padding: 18px;
        }

        .confirm-modal .pop-up-header .pop-up-title {
            color: #111827;
            font-weight: 800;
        }

        .confirm-modal .pop-up-content {
            color: #475569;
            font-size: 14px;
            line-height: 1.6;
        }

        .confirm-modal .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
        }

        .confirm-modal .btn-secondary {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #111827;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
        }

        .confirm-modal .btn-secondary:hover {
            border-color: #008500;
            color: #008500;
        }

        .confirm-modal .btn-danger {
            background: #fff;
            border: 1px solid #ef4444;
            color: #b91c1c;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
        }

        .confirm-modal .btn-danger:hover {
            background: #fff5f5;
            border-color: #dc2626;
            color: #991b1b;
        }

        /* Ongoing project progress bar */
        .progress-container { margin-top: 10px; }
        .progress-label { font-size: 12px; color: #475569; margin-bottom: 6px; }
        .progress-track { width: 100%; height: 8px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
        .progress-fill { height: 100%; width: 0; background: #008500; transition: width .4s ease; }
    </style>

    <!-- Request Details Modal -->
    <div class="pop-up-section request-modal deactive" id="requestModalRoot">
        <div class="pop-up" id="requestModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Request Details</div>
                <i class="fa-light fa-xmark" id="requestModalClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; gap:12px; align-items:center;">
                    <div style="font-weight:700; color:#111827;" id="reqProvider">Provider Name</div>
                    <span style="font-size:12px; color:#64748b;">•</span>
                    <div style="font-size:13px; color:#475569;" id="reqDate">Requested —</div>
                </div>
                <div style="font-size:16px; font-weight:700; color:#111827;" id="reqTitle">Request Title</div>
                <div style="font-size:14px; color:#475569; line-height:1.6;" id="reqDescription">Request description
                    goes here.</div>
                <div style="display:flex; gap:10px; align-items:center;">
                    <span class="status-chip" style="background:#ecfdf5; color:#008500; border-color:#bbf7d0;">
                        <i class="fa-regular fa-tag"></i>
                        <span id="reqPrice">Proposed: $0</span>
                    </span>
                </div>
                <!-- Modal Progress for Ongoing Projects -->
                <div id="modalProgressSection" class="progress-container" style="display:none;">
                    <div class="progress-label">Progress: <span id="modalProgressPercent">0%</span> <span id="modalProgressDetail" style="color:#64748b;">(0h of 0h)</span></div>
                    <div class="progress-track"><div id="modalProgressFill" class="progress-fill"></div></div>
                </div>
                <!-- Provider Remarks (Ongoing Projects) -->
                <div id="modalRemarksSection" class="modal-remarks" style="display:none;">
                    <div style="font-weight:700; color:#111827; margin-top:4px;">Provider Remarks</div>
                    <div id="reqRemarks" style="font-size:14px; color:#475569; line-height:1.6; margin-top:6px;">—</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" id="btnPay" style="display:none;"><i class="fa-regular fa-dollar-sign"></i> Pay</button>
                <button class="btn-primary" id="btnApprove" style="display:none;"><i class="fa-regular fa-circle-check"></i> Approve</button>
                <button class="btn-outline" id="btnRequestChanges" style="display:none;"><i class="fa-regular fa-rotate-left"></i> Request Changes</button>
                <button class="btn-danger" id="btnDecline"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            </div>
        </div>
    </div>

    <!-- Confirm Cancel Modal -->
    <div class="pop-up-section confirm-modal deactive" id="confirmCancelRoot">
        <div class="pop-up" id="confirmCancel">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Confirm Cancel</div>
                <i class="fa-light fa-xmark" id="confirmCancelClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                Are you sure you want to cancel this request? This action cannot be undone.
            </div>
            <div class="modal-actions">
                <button class="btn-secondary" id="btnKeep">Keep</button>
                <button class="btn-danger" id="btnConfirmCancel"><i class="fa-regular fa-circle-xmark"></i> Yes,
                    Cancel</button>
            </div>
        </div>
    </div>

    <!-- Request Changes Modal -->
    <div class="pop-up-section request-modal deactive" id="requestChangesRoot">
        <div class="pop-up" id="requestChangesModal" style="max-width:640px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Request Changes</div>
                <i class="fa-light fa-xmark" id="requestChangesClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                <label style="font-weight:700; color:#111827;" for="changesDescription">Describe changes</label>
                <textarea id="changesDescription" rows="5" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px; color:#111827;" placeholder="Add clear feedback and requested adjustments"></textarea>
                <div>
                    <label style="font-weight:700; color:#111827; display:block; margin-bottom:6px;">Attach files (optional)</label>
                    <input id="changesFile" type="file" multiple style="display:block;">
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" id="btnSubmitRequestChanges"><i class="fa-regular fa-paper-plane"></i> Request</button>
            </div>
        </div>
    </div>

</body>


</html>
<script>
    // Modal wiring for request details in Pending Requests and Pay flow in Approved Requests
    (function () {
        const root = document.getElementById('requestModalRoot');
        const modal = document.getElementById('requestModal');
        const closeBtn = document.getElementById('requestModalClose');
        const btnAccept = document.getElementById('btnAccept');
        const btnDecline = document.getElementById('btnDecline');
        const btnPay = document.getElementById('btnPay');
        const reqProvider = document.getElementById('reqProvider');
        const reqTitle = document.getElementById('reqTitle');
        const reqDescription = document.getElementById('reqDescription');
        const reqPrice = document.getElementById('reqPrice');
        const reqDate = document.getElementById('reqDate');
    const modalProgressSection = document.getElementById('modalProgressSection');
    const modalProgressFill = document.getElementById('modalProgressFill');
    const modalProgressPercent = document.getElementById('modalProgressPercent');
    const modalProgressDetail = document.getElementById('modalProgressDetail');
    const modalRemarksSection = document.getElementById('modalRemarksSection');
    const reqRemarks = document.getElementById('reqRemarks');

        function openModal() { root.classList.remove('deactive'); root.classList.add('active'); document.body.style.overflow = 'hidden'; }
        function closeModal() { root.classList.remove('active'); root.classList.add('deactive'); document.body.style.overflow = ''; }

        function extractText(el, selector) {
            const node = el.querySelector(selector);
            return node ? node.textContent.trim() : '';
        }

    const confirmRoot = document.getElementById('confirmCancelRoot');
        const confirmClose = document.getElementById('confirmCancelClose');
        const btnKeep = document.getElementById('btnKeep');
        const btnConfirmCancel = document.getElementById('btnConfirmCancel');

    function openConfirm() { confirmRoot.classList.remove('deactive'); confirmRoot.classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeConfirm() { confirmRoot.classList.remove('active'); confirmRoot.classList.add('deactive'); document.body.style.overflow = ''; }

    // Request Changes modal helpers
    const reqChangesRoot = document.getElementById('requestChangesRoot');
    const reqChangesClose = document.getElementById('requestChangesClose');
    const btnSubmitRequestChanges = document.getElementById('btnSubmitRequestChanges');
    function openRequestChanges() { reqChangesRoot.classList.remove('deactive'); reqChangesRoot.classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeRequestChanges() { reqChangesRoot.classList.remove('active'); reqChangesRoot.classList.add('deactive'); document.body.style.overflow = ''; }

        // Helper to extract a price/summary line from various card formats
        function getPriceSummary(card) {
            const middleTexts = Array.from(card.querySelectorAll('.item-middle div')).map(d => d.textContent.trim());
            // Look for typical price markers
            let match = middleTexts.find(t => /(Proposed:|Hourly:|Milestone:|Funded:)/i.test(t));
            if (!match) {
                // Fallback: look into item-district spans for price-like info
                const districtTexts = Array.from(card.querySelectorAll('.item-district span')).map(s => s.textContent.trim());
                match = districtTexts.find(t => /(Proposed:|Hourly:|Milestone:|Funded:|Price:)/i.test(t)) || '';
            }
            return match || '';
        }

        // Pending Requests: View opens modal with details; Pay is not shown in this context
        document.querySelectorAll('.pending-requests .search-item').forEach(card => {
            const viewBtn = card.querySelector('.btn-view');
            const acceptBtn = card.querySelector('.btn-accept');
            const cancelBtn = card.querySelector('.btn-danger');
            if (viewBtn) {
                viewBtn.addEventListener('click', () => {
                    reqProvider.textContent = extractText(card, '.item-name') || 'Provider';
                    reqTitle.textContent = extractText(card, '.item-title') || 'Request Title';
                    reqDescription.textContent = extractText(card, '.item-description') || '';
                    const priceLine = getPriceSummary(card);
                    reqPrice.textContent = priceLine.replace(/\s+/g, ' ').trim() || 'Proposed: —';
                    reqDate.textContent = (extractText(card, '.item-district span') || '').trim();
                    // Hide Pay for pending view and hide progress/remarks by default
                    if (btnPay) btnPay.style.display = 'none';
                    // Ensure Cancel is visible in this context
                    if (btnDecline) btnDecline.style.display = '';
                    // Hide Approve/Request Changes in non-approve contexts
                    (document.getElementById('btnApprove')||{}).style && (document.getElementById('btnApprove').style.display = 'none');
                    (document.getElementById('btnRequestChanges')||{}).style && (document.getElementById('btnRequestChanges').style.display = 'none');
                    if (modalProgressSection) modalProgressSection.style.display = 'none';
                    if (modalRemarksSection) modalRemarksSection.style.display = 'none';
                    openModal();
                });
            }
            if (acceptBtn) {
                acceptBtn.addEventListener('click', () => {
                    // TODO: Hook to backend accept action
                    alert('Accepted (demo)');
                });
            }
            if (cancelBtn) {
                cancelBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openConfirm();
                });
            }
        });

        // Approved Requests: clicking Pay opens the same modal with Pay button visible
        document.querySelectorAll('.in-progress-requests .search-item, .ongoing-projects .search-item').forEach(card => {
            const payBtn = card.querySelector('.btn-pay');
            const viewBtn = card.querySelector('.btn-view');
            const cancelBtn = card.querySelector('.btn-danger');
            if (payBtn) {
                payBtn.addEventListener('click', () => {
                    reqProvider.textContent = extractText(card, '.item-name') || extractText(card, '.item-provider') || 'Provider';
                    reqTitle.textContent = extractText(card, '.item-title') || 'Request Title';
                    reqDescription.textContent = extractText(card, '.item-description') || '';
                    const priceLine = getPriceSummary(card);
                    reqPrice.textContent = priceLine.replace(/\s+/g, ' ').trim() || 'Amount: —';
                    // Prefer first two spans for date-like info
                    const datespan = Array.from(card.querySelectorAll('.item-district span')).map(s => s.textContent.trim())[0] || '';
                    reqDate.textContent = datespan;
                    // Show Pay button in this context
                    if (btnPay) btnPay.style.display = '';
                    // Ensure Cancel is visible in this context
                    if (btnDecline) btnDecline.style.display = '';
                    // Hide Approve/Request Changes in non-approve contexts
                    (document.getElementById('btnApprove')||{}).style && (document.getElementById('btnApprove').style.display = 'none');
                    (document.getElementById('btnRequestChanges')||{}).style && (document.getElementById('btnRequestChanges').style.display = 'none');
                    // For Approved Requests Pay popup: hide progress and provider remarks
                    if (modalProgressSection) modalProgressSection.style.display = 'none';
                    if (modalRemarksSection) modalRemarksSection.style.display = 'none';
                    openModal();
                });
            }
            if (viewBtn) {
                viewBtn.addEventListener('click', () => {
                    // Populate modal the same way, but hide Pay for simple view
                    reqProvider.textContent = extractText(card, '.item-name') || extractText(card, '.item-provider') || 'Provider';
                    reqTitle.textContent = extractText(card, '.item-title') || 'Project Details';
                    reqDescription.textContent = extractText(card, '.item-description') || '';
                    const priceLine = getPriceSummary(card);
                    reqPrice.textContent = priceLine.replace(/\s+/g, ' ').trim() || '';
                    const datespan = Array.from(card.querySelectorAll('.item-district span')).map(s => s.textContent.trim())[0] || '';
                    reqDate.textContent = datespan;
                    if (btnPay) btnPay.style.display = 'none';
                    // Ensure Cancel is visible in this context
                    if (btnDecline) btnDecline.style.display = '';
                    // Hide Approve/Request Changes in non-approve contexts
                    (document.getElementById('btnApprove')||{}).style && (document.getElementById('btnApprove').style.display = 'none');
                    (document.getElementById('btnRequestChanges')||{}).style && (document.getElementById('btnRequestChanges').style.display = 'none');
                    // When viewing from ongoing projects, also show progress and remarks
                    const etaText = Array.from(card.querySelectorAll('.item-middle div')).map(d => d.textContent.trim()).find(t => /ETA\s+/i.test(t)) || '';
                    const loggedText = Array.from(card.querySelectorAll('.item-middle div')).map(d => d.textContent.trim()).find(t => /Logged\s+/i.test(t)) || '';
                    const daysMatch = etaText.match(/ETA\s*(\d+)d/i);
                    const hoursMatch = loggedText.match(/Logged\s*(\d+)h/i);
                    const totalHours = daysMatch ? parseInt(daysMatch[1], 10) * 8 : 0;
                    const spentHours = hoursMatch ? parseInt(hoursMatch[1], 10) : 0;
                    const percent = totalHours > 0 ? Math.min(100, Math.round((spentHours / totalHours) * 100)) : 0;
                    if (modalProgressSection) {
                        modalProgressSection.style.display = '';
                        if (modalProgressFill) modalProgressFill.style.width = percent + '%';
                        if (modalProgressPercent) modalProgressPercent.textContent = percent + '%';
                        if (modalProgressDetail) modalProgressDetail.textContent = `(${spentHours}h of ${totalHours}h)`;
                    }
                    const remarksEl = card.querySelector('.item-remarks');
                    if (modalRemarksSection) {
                        modalRemarksSection.style.display = '';
                        reqRemarks.textContent = remarksEl ? remarksEl.textContent.trim() : 'No remarks from provider yet.';
                    }
                    openModal();
                });
            }
            if (cancelBtn) {
                cancelBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openConfirm();
                });
            }
        });

        // Pending Review: Approve button opens modal with details, progress, remarks, and Approve/Request Changes actions
        document.querySelectorAll('.pending-review .search-item').forEach(card => {
            const approveBtn = card.querySelector('.btn-approve');
            const requestBtn = card.querySelector('.btn-request-changes');
            if (approveBtn) {
                approveBtn.addEventListener('click', () => {
                    reqProvider.textContent = extractText(card, '.item-name') || extractText(card, '.item-provider') || 'Provider';
                    reqTitle.textContent = extractText(card, '.item-title') || 'Submission Details';
                    reqDescription.textContent = extractText(card, '.item-description') || '';
                    const priceLine = getPriceSummary(card);
                    reqPrice.textContent = priceLine.replace(/\s+/g, ' ').trim() || '';
                    const datespan = Array.from(card.querySelectorAll('.item-district span')).map(s => s.textContent.trim())[0] || '';
                    reqDate.textContent = datespan;
                    // Buttons visibility for approve context
                    if (btnPay) btnPay.style.display = 'none';
                    const btnApprove = document.getElementById('btnApprove');
                    const btnRequestChanges = document.getElementById('btnRequestChanges');
                    if (btnApprove) btnApprove.style.display = '';
                    if (btnRequestChanges) btnRequestChanges.style.display = '';
                    // Ensure only Approve and Request Changes are visible (hide Cancel)
                    if (btnDecline) btnDecline.style.display = 'none';
                    // Progress and remarks similar to ongoing
                    const etaText = Array.from(card.querySelectorAll('.item-middle div')).map(d => d.textContent.trim()).find(t => /ETA\s+/i.test(t)) || '';
                    const loggedText = Array.from(card.querySelectorAll('.item-middle div')).map(d => d.textContent.trim()).find(t => /Logged\s+/i.test(t)) || '';
                    const daysMatch = etaText.match(/ETA\s*(\d+)d/i);
                    const hoursMatch = loggedText.match(/Logged\s*(\d+)h/i);
                    const totalHours = daysMatch ? parseInt(daysMatch[1], 10) * 8 : 0;
                    const spentHours = hoursMatch ? parseInt(hoursMatch[1], 10) : 0;
                    const percent = totalHours > 0 ? Math.min(100, Math.round((spentHours / totalHours) * 100)) : 0;
                    if (modalProgressSection) {
                        modalProgressSection.style.display = '';
                        if (modalProgressFill) modalProgressFill.style.width = percent + '%';
                        if (modalProgressPercent) modalProgressPercent.textContent = percent + '%';
                        if (modalProgressDetail) modalProgressDetail.textContent = `(${spentHours}h of ${totalHours}h)`;
                    }
                    const remarksEl = card.querySelector('.item-remarks');
                    if (modalRemarksSection) {
                        modalRemarksSection.style.display = '';
                        reqRemarks.textContent = remarksEl ? remarksEl.textContent.trim() : 'No remarks from provider yet.';
                    }
                    openModal();
                });
            }
            if (requestBtn) {
                requestBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openRequestChanges();
                });
            }
        });

        // Wire Request Changes in-modal button
        const btnRequestChangesInModal = document.getElementById('btnRequestChanges');
        btnRequestChangesInModal && btnRequestChangesInModal.addEventListener('click', (e) => {
            e.preventDefault();
            openRequestChanges();
        });

        // Request Changes modal close + submit
        reqChangesClose && reqChangesClose.addEventListener('click', closeRequestChanges);
        reqChangesRoot && reqChangesRoot.addEventListener('click', (e) => { if (e.target === reqChangesRoot) closeRequestChanges(); });
        btnSubmitRequestChanges && btnSubmitRequestChanges.addEventListener('click', () => {
            // TODO: Send description + files to backend endpoint
            alert('Changes requested (demo)');
            closeRequestChanges();
            closeModal();
        });

        closeBtn && closeBtn.addEventListener('click', closeModal);
        root && root.addEventListener('click', (e) => { if (e.target === root) closeModal(); });
        window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

        btnDecline && btnDecline.addEventListener('click', (e) => { e.preventDefault(); openConfirm(); });

        // Confirm modal interactions
        confirmClose && confirmClose.addEventListener('click', closeConfirm);
        confirmRoot && confirmRoot.addEventListener('click', (e) => { if (e.target === confirmRoot) closeConfirm(); });
        btnKeep && btnKeep.addEventListener('click', closeConfirm);
        btnConfirmCancel && btnConfirmCancel.addEventListener('click', () => {
            closeConfirm();
            closeModal();
            alert('Request cancelled (demo)');
        });
        btnAccept && btnAccept.addEventListener('click', () => { alert('Accepted (demo)'); closeModal(); });
        // Stub for payment action
        btnPay && btnPay.addEventListener('click', () => {
            // TODO: Integrate with payment flow endpoint
            alert('Proceed to payment (demo)');
            closeModal();
        });

        // Compute ongoing project progress bars (from item-middle ETA / Logged)
        document.querySelectorAll('.ongoing-projects .search-item').forEach(card => {
            const etaText = Array.from(card.querySelectorAll('.item-middle div')).map(d => d.textContent.trim()).find(t => /ETA\s+/i.test(t)) || '';
            const loggedText = Array.from(card.querySelectorAll('.item-middle div')).map(d => d.textContent.trim()).find(t => /Logged\s+/i.test(t)) || '';
            // Extract hours from patterns like "ETA 6d" or "Logged 12h"
            const daysMatch = etaText.match(/ETA\s*(\d+)d/i);
            const hoursMatch = loggedText.match(/Logged\s*(\d+)h/i);
            const totalHours = daysMatch ? parseInt(daysMatch[1], 10) * 8 : 0; // assume 8h per day
            const spentHours = hoursMatch ? parseInt(hoursMatch[1], 10) : 0;
            const percent = totalHours > 0 ? Math.min(100, Math.round((spentHours / totalHours) * 100)) : 0;
            const fill = card.querySelector('.progress-fill');
            const pctEl = card.querySelector('.progress-percent');
            const detailEl = card.querySelector('.progress-detail');
            if (fill) fill.style.width = percent + '%';
            if (pctEl) pctEl.textContent = percent + '%';
            if (detailEl) detailEl.textContent = `(${spentHours}h of ${totalHours}h)`;
        });
    })();
</script>