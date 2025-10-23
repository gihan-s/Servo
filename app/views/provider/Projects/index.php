<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <title>Provider Dashboard - Service Requests & Projects</title>
    <style>
        /* Unified card styling to match servicePosts_client.php */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.75rem;
        }
        .service-requests .header-requests {
            width: 100%;
            position: sticky;
            padding: 10px;
            background-color: #fff;
            top: 0;
            z-index; 1;
        }
        .service-requests .item-list {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.75rem;
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

        /* Light themed status variants */
        .service-requests .status-chip.status-new,
        .service-requests .status-chip.status-pending,
        .service-requests .status-chip.status-progress,
        .service-requests .status-chip.status-review,
        .service-requests .status-chip.status-complete {
            border: 1px solid;
        }

        /* New Request */
        .service-requests .status-chip.status-new {
            background: #f2f7fd;
            color: #0369a1;
            border-color: #d4e6f6;
        }

        /* Pending Client Response */
        .service-requests .status-chip.status-pending {
            background: #fef3c7;
            color: #d97706;
            border-color: #fed7aa;
        }

        /* In Progress */
        .service-requests .status-chip.status-progress {
            background: #f2effd;
            color: #4c1d95;
            border-color: #d7ccfa;
        }

        /* Pending Review */
        .service-requests .status-chip.status-review {
            background: #f2f7fd;
            color: #0369a1;
            border-color: #d4e6f6;
        }

        /* Completed */
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

        /* Header and navigation styles */
        .header-requests h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .search-header {
            display: flex;
            width: 100%;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .search-button {
            display: flex;
            align-items: center;
            min-width: 250px;
            width: 100%;
            border-radius: 5rem 5rem 0 5rem;
            border: 1px solid #333;
            box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.1);
        }

        .search-button input {
            border: none;
            padding: 12px 16px;
            font-size: 14px;
            flex: 1;
            outline: none;
        }

        .search-button button {
            background: #333;
            color: #fff;
            height: 40px;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-size: 20px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, outline 0.3s ease;
        }

        .filter {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: all .2s ease;
        }

        .filter:hover {
            border-color: #008500;
            color: #008500;
        }

        .advance-search {
            display: flex;
            align-items: center;
        }

        .selection-input-field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .selection-input-field input {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            background: #fff;
            min-width: 180px;
            cursor: pointer;
        }

        .selection-input-field i {
            position: absolute;
            right: 16px;
            color: #64748b;
        }

        .selection-options {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-top: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .selection-options .opt {
            padding: 12px 16px;
            cursor: pointer;
            transition: background .2s ease;
        }

        .selection-options .opt:hover {
            background: #f8fafc;
        }

        .container-changer {
            display: flex;
            gap: 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .buttons {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all .2s ease;
            color: #64748b;
        }

        .buttons.active {
            color: #008500;
            border-bottom-color: #008500;
        }

        .buttons:hover {
            color: #008500;
        }

        .requests-section {
            display: none;
        }

        .requests-section.active {
            display: block;
        }

        /* Popup styles */
        .pop-up-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(2px);
            z-index: 6000;
            padding: 24px;
        }

        .pop-up-section.deactive {
            display: none;
        }

        .pop-up {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            width: min(680px, 96vw);
            max-height: 80vh;
            overflow: auto;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, .28);
            padding: 18px 18px 12px;
        }

        .pop-up-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .pop-up-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .pop-up-header i {
            color: #64748b;
            cursor: pointer;
            font-size: 18px;
        }

        .pop-up-header i:hover {
            color: #111827;
        }

        .pop-up-content {
            margin: 16px 0;
        }

        /* Filter popup styles */
        .filter-item {
            margin-bottom: 16px;
        }

        .filter-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            cursor: pointer;
            font-weight: 600;
        }

        .filter-title i {
            transition: transform 0.2s ease;
        }

        .filter-title i.rotated {
            transform: rotate(180deg);
        }

        .filter-options {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .filter-options.active {
            display: block;
        }

        .filter-options li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .filter-options li:hover {
            color: #008500;
        }

        .button-apply {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .button-apply button {
            background: #008500;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
        }

        .button-apply button:hover {
            background: #006600;
        }

        /* Modal styles */
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
            flex-wrap: wrap;
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

        /* Confirm modal */
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
            cursor: pointer;
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
            cursor: pointer;
        }

        .confirm-modal .btn-danger:hover {
            background: #fff5f5;
            border-color: #dc2626;
            color: #991b1b;
        }

        /* Form styles in modals */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #008500;
        }

        .range-value {
            text-align: center;
            font-weight: 600;
            color: #008500;
            margin-top: 4px;
        }

        .file-note {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Ongoing project progress bar */
        .progress-container { margin-top: 10px; }
        .progress-label { font-size: 12px; color: #475569; margin-bottom: 6px; }
        .progress-track { width: 100%; height: 8px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
        .progress-fill { height: 100%; width: 0; background: #008500; transition: width .4s ease; }

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
            
            .search-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-button {
                min-width: auto;
            }
            
            .container-changer {
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .modal-actions {
                justify-content: center !important;
            }
            
            .modal-actions button {
                flex: 1;
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
    <?php // Use filesystem path for includes (BASE_URL is for URLs, not filesystem)
    require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="main-content">
    <section class="service-requests">
        <div class="header-requests">
            <h1>My Service Requests & Projects</h1>
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
                <!--<div id="new-requests" class="buttons active" data-target="new-requests">New Requests</div>-->
                <div id="pending-requests" class="buttons active" data-target="pending-requests">Incoming Requests</div>
                <div id="in-progress-requests" class="buttons" data-target="in-progress-requests">Ongoing</div>
                <div id="pending-review" class="buttons" data-target="pending-review">Pending Review</div>
                <div id="completed-jobs" class="buttons" data-target="completed-jobs">Completed</div>
            </div>
        </div>
        <div class="request-content">
            <!-- New Requests Section -->
             <!--
            <div class="new-requests active requests-section" id="section-new">
                <p class="section-note">New requests from clients. Review and respond with proposals.</p>
                <div class="item-list">
                    <div class="search-item" data-status="new">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name">Sarah Johnson</div>
                                <div class="item-title">Logo Design for Tech Startup</div>
                                <div class="item-district">
                                    <span>Posted 15 Jul 2025 | 17:55</span>
                                    <span>Budget: $500-$800</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Request"><i
                                        class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-primary btn-propose" title="Send Proposal"><i
                                        class="fa-regular fa-paper-plane"></i> Propose</button>
                                <button class="btn-danger btn-decline" title="Decline Request"><i
                                        class="fa-regular fa-circle-xmark"></i> Decline</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-clock"></i> Timeline: 2 weeks</div>
                            <div><i class="fa-regular fa-tag"></i> Category: Graphic Design</div>
                        </div>
                        <div class="item-description">Looking for a modern, minimalist logo for our new SaaS platform. Should work well in both digital and print formats.</div>
                        <div class="status-bottom"><span class="status-chip status-new">New Request</span></div>
                    </div>
                </div>
                <div class="pagination" aria-label="New Requests Pagination">
                    <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                </div>
            </div>
    -->
            <!-- Incoming Requests Section -->
            <div class="pending-requests active requests-section" id="section-pending">
                <p class="section-note">Incoming service requests from potential clients.</p>
                <div class="item-list">
                    <div class="search-item" data-status="pending">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name">Michael Chen</div>
                                <div class="item-title">E-commerce Website Development</div>
                                <div class="item-district">
                                    <span>Proposed 12 Jul 2025</span>
                                    <span>Proposal: $2,500</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Proposal"><i
                                        class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i
                                        class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-danger btn-withdraw" title="Withdraw Proposal"><i
                                        class="fa-regular fa-trash"></i> Withdraw</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-clock"></i> Timeline: 4 weeks</div>
                            <div><i class="fa-regular fa-tag"></i> Proposed: $2,500</div>
                        </div>
                        <div class="item-description">Full e-commerce site with product catalog, shopping cart, and payment integration.</div>
                        <div class="status-bottom"><span class="status-chip status-pending">Pending Response</span></div>
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

            <!-- Ongoing Section -->
            <div class="in-progress-requests requests-section" id="section-progress">
                <p class="section-note">Active projects you're currently working on.</p>
                <div class="item-list">
                    <div class="search-item" data-status="progress">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name">Emma Wilson</div>
                                <div class="item-title">Mobile App UI/UX Design</div>
                                <div class="item-district">
                                    <span>Started 10 Jul 2025</span>
                                    <span>Budget: $1,200</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Project"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i class="fa-regular fa-messages"></i> Message</button>
                                <button class="btn-primary btn-update" title="Update Progress"><i class="fa-regular fa-arrow-up"></i> Update</button>
                                <button class="btn-primary btn-submit" title="Submit for Review"><i class="fa-regular fa-paper-plane"></i> Submit</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-regular fa-hourglass"></i> ETA 12d</div>
                            <div><i class="fa-regular fa-clock"></i> Logged 32h</div>
                        </div>
                        <div class="progress-container" aria-label="Project progress">
                            <div class="progress-label">Progress: <span class="progress-percent">65%</span> <span class="progress-detail" style="color:#64748b;">(32h of 50h)</span></div>
                            <div class="progress-track"><div class="progress-fill" style="width:65%"></div></div>
                        </div>
                        <div class="item-description">Designing user interface and experience for a fitness tracking mobile application.</div>
                        <div class="status-bottom"><span class="status-chip status-progress">In Progress</span></div>
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

            <!-- Pending Review Section -->
            <div class="pending-review requests-section" id="section-review">
                <p class="section-note">Project outputs submitted for review. Awaiting feedback or approval from client.</p>
                <div class="item-list">
                    <div class="search-item" data-status="review">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name">David Rodriguez</div>
                                <div class="item-title">Website Content Writing</div>
                                <div class="item-district">
                                    <span>Submitted 08 Jul 2025</span>
                                    <span>Payment: $600</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Submission"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i class="fa-regular fa-messages"></i> Message</button>
                            </div>
                        </div>
                        <div class="item-description">Wrote homepage, about us, and services page content for a digital marketing agency.</div>
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

            <!-- Completed Jobs Section -->
            <div class="completed-jobs requests-section" id="section-completed">
                <p class="section-note">Successfully completed projects and delivered work.</p>
                <div class="item-list">
                    <div class="search-item" data-status="complete">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name">Jennifer Lee</div>
                                <div class="item-title">Social Media Marketing Campaign</div>
                                <div class="item-district">
                                    <span>Completed 01 Jul 2025</span>
                                    <span>Earned: $1,500</span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Project"><i class="fa-regular fa-eye"></i> View</button>
                                <button class="btn-outline" title="Download Files"><i class="fa-regular fa-download"></i> Files</button>
                            </div>
                        </div>
                        <div class="item-description">30-day social media campaign with content creation and community management across 3 platforms.</div>
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
        </div>
    </section>

    <!-- Filter Popup -->
    <div class="pop-up-section filter-pop-up deactive">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Add Filters</div>
                <i class="fa-light fa-xmark" id="filter-pop-up-close"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                <div class="search-filters">
                    <div class="filter-item">
                        <div class="filter-title"><span>Project Budget</span><i
                                class="fa-light fa-chevron-down rotated"></i>
                        </div>
                        <ul class="filter-options active radios">
                            <li><input type="radio" name="budget" id="budget" checked>Any budget</li>
                            <li><input type="radio" name="budget" id="budget">Less than $500</li>
                            <li><input type="radio" name="budget" id="budget">$500 - $1,000</li>
                            <li><input type="radio" name="budget" id="budget">$1,000 - $2,500</li>
                            <li><input type="radio" name="budget" id="budget">$2,500 & above</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Project Duration</span><i class="fa-light fa-chevron-down"></i>
                        </div>
                        <ul class="filter-options radios">
                            <li><input type="radio" name="duration" id="duration" checked>Any duration</li>
                            <li><input type="radio" name="duration" id="duration">Less than 1 week</li>
                            <li><input type="radio" name="duration" id="duration">1-2 weeks</li>
                            <li><input type="radio" name="duration" id="duration">2-4 weeks</li>
                            <li><input type="radio" name="duration" id="duration">More than 4 weeks</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Project Category</span><i class="fa-light fa-chevron-down"></i>
                        </div>
                        <ul class="filter-options checkboxes">
                            <li><input type="checkbox" name="category" id="category" checked>Web Development</li>
                            <li><input type="checkbox" name="category" id="category">Graphic Design</li>
                            <li><input type="checkbox" name="category" id="category">Content Writing</li>
                            <li><input type="checkbox" name="category" id="category">Digital Marketing</li>
                            <li><input type="checkbox" name="category" id="category">Mobile Development</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="button-apply">
                <button>Apply filters</button>
            </div>
        </div>
    </div>

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
                    <div style="font-weight:700; color:#111827;" id="reqClient">Client Name</div>
                    <span style="font-size:12px; color:#64748b;">•</span>
                    <div style="font-size:13px; color:#475569;" id="reqDate">Requested —</div>
                </div>
                <div style="font-size:16px; font-weight:700; color:#111827;" id="reqTitle">Request Title</div>
                <div style="font-size:14px; color:#475569; line-height:1.6;" id="reqDescription">Request description
                    goes here.</div>
                <div style="display:flex; gap:10px; align-items:center;">
                    <span class="status-chip" style="background:#ecfdf5; color:#008500; border-color:#bbf7d0;">
                        <i class="fa-regular fa-tag"></i>
                        <span id="reqBudget">Budget: $0</span>
                    </span>
                    <span class="status-chip" style="background:#f2effd; color:#4c1d95; border-color:#d7ccfa;">
                        <i class="fa-regular fa-clock"></i>
                        <span id="reqTimeline">Timeline: —</span>
                    </span>
                </div>
                <!-- Modal Progress for Ongoing Projects -->
                <div id="modalProgressSection" class="progress-container" style="display:none;">
                    <div class="progress-label">Progress: <span id="modalProgressPercent">0%</span> <span id="modalProgressDetail" style="color:#64748b;">(0h of 0h)</span></div>
                    <div class="progress-track"><div id="modalProgressFill" class="progress-fill"></div></div>
                </div>
                <!-- Client Requirements -->
                <div id="modalRequirementsSection" class="modal-requirements">
                    <div style="font-weight:700; color:#111827; margin-top:4px;">Client Requirements</div>
                    <div id="reqRequirements" style="font-size:14px; color:#475569; line-height:1.6; margin-top:6px;">—</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" id="btnPropose" style="display:none;"><i class="fa-regular fa-paper-plane"></i> Send Proposal</button>
                <button class="btn-primary" id="btnSubmit" style="display:none;"><i class="fa-regular fa-paper-plane"></i> Submit for Review</button>
                <button class="btn-primary" id="btnUpdate" style="display:none;"><i class="fa-regular fa-arrow-up"></i> Update Progress</button>
                <button class="btn-outline" id="btnMessage"><i class="fa-regular fa-messages"></i> Message Client</button>
                <button class="btn-danger" id="btnDecline"><i class="fa-regular fa-circle-xmark"></i> Decline</button>
                <button class="btn-danger" id="btnWithdraw" style="display:none;"><i class="fa-regular fa-trash"></i> Withdraw</button>
            </div>
        </div>
    </div>

    <!-- Send Proposal Modal -->
    <div class="pop-up-section request-modal deactive" id="proposalModalRoot">
        <div class="pop-up" id="proposalModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Send Proposal</div>
                <i class="fa-light fa-xmark" id="proposalModalClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; gap:12px; align-items:center;">
                    <div style="font-weight:700; color:#111827;" id="proposalClient">Client Name</div>
                    <span style="font-size:12px; color:#64748b;">•</span>
                    <div style="font-size:13px; color:#475569;" id="proposalTitle">Request Title</div>
                </div>
                
                <div class="form-group">
                    <label for="proposalAmount">Proposal Amount ($)</label>
                    <input type="number" id="proposalAmount" placeholder="Enter your proposed amount">
                </div>
                
                <div class="form-group">
                    <label for="proposalTimeline">Estimated Timeline (days)</label>
                    <input type="number" id="proposalTimeline" placeholder="Enter estimated days to complete">
                </div>
                
                <div class="form-group">
                    <label for="proposalDescription">Proposal Details</label>
                    <textarea id="proposalDescription" rows="5" placeholder="Describe your approach, deliverables, and any additional information"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="proposalFiles">Attach Files (optional)</label>
                    <input type="file" id="proposalFiles" multiple>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" id="btnSendProposal"><i class="fa-regular fa-paper-plane"></i> Send Proposal</button>
            </div>
        </div>
    </div>

    <!-- Update Progress Modal -->
    <div class="pop-up-section request-modal deactive" id="progressModalRoot">
        <div class="pop-up" id="progressModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Update Progress</div>
                <i class="fa-light fa-xmark" id="progressModalClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; gap:12px; align-items:center;">
                    <div style="font-weight:700; color:#111827;" id="progressClient">Client Name</div>
                    <span style="font-size:12px; color:#64748b;">•</span>
                    <div style="font-size:13px; color:#475569;" id="progressTitle">Project Title</div>
                </div>
                
                <div class="form-group">
                    <label for="progressPercent">Progress Percentage</label>
                    <input type="range" id="progressPercent" min="0" max="100" value="0">
                    <div class="range-value"><span id="progressPercentValue">0%</span></div>
                </div>
                
                <div class="form-group">
                    <label for="hoursWorked">Hours Worked</label>
                    <input type="number" id="hoursWorked" placeholder="Enter hours worked on this project">
                </div>
                
                <div class="form-group">
                    <label for="progressDescription">Progress Update</label>
                    <textarea id="progressDescription" rows="5" placeholder="Describe what you've completed, any challenges, and next steps"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="progressFiles">Attach Files (optional)</label>
                    <input type="file" id="progressFiles" multiple>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" id="btnUpdateProgress"><i class="fa-regular fa-arrow-up"></i> Update Progress</button>
            </div>
        </div>
    </div>

    <!-- Submit for Review Modal -->
    <div class="pop-up-section request-modal deactive" id="submitModalRoot">
        <div class="pop-up" id="submitModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Submit for Review</div>
                <i class="fa-light fa-xmark" id="submitModalClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; gap:12px; align-items:center;">
                    <div style="font-weight:700; color:#111827;" id="submitClient">Client Name</div>
                    <span style="font-size:12px; color:#64748b;">•</span>
                    <div style="font-size:13px; color:#475569;" id="submitTitle">Project Title</div>
                </div>
                
                <div class="form-group">
                    <label for="submitDescription">Submission Notes</label>
                    <textarea id="submitDescription" rows="5" placeholder="Add any notes about your submission, key features, or instructions for the client"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="submitFiles">Deliverables</label>
                    <input type="file" id="submitFiles" multiple>
                    <div class="file-note">Upload all final deliverables for client review</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" id="btnSubmitForReview"><i class="fa-regular fa-paper-plane"></i> Submit for Review</button>
            </div>
        </div>
    </div>

    <!-- Confirm Action Modal -->
    <div class="pop-up-section confirm-modal deactive" id="confirmModalRoot">
        <div class="pop-up" id="confirmModal">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title" id="confirmTitle">Confirm Action</div>
                <i class="fa-light fa-xmark" id="confirmModalClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" id="confirmMessage">
                Are you sure you want to proceed with this action?
            </div>
            <div class="modal-actions">
                <button class="btn-secondary" id="btnCancelAction">Cancel</button>
                <button class="btn-danger" id="btnConfirmAction"><i class="fa-regular fa-circle-check"></i> Confirm</button>
            </div>
        </div>
    </div>
    </div> <!-- End of main-content -->

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

    <script>
        // Main functionality for provider interface
        document.addEventListener('DOMContentLoaded', function() {
            // Tab navigation
            const tabButtons = document.querySelectorAll('.container-changer .buttons');
            const tabSections = document.querySelectorAll('.requests-section');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    
                    // Update active tab
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show corresponding section
                    tabSections.forEach(section => {
                        section.classList.remove('active');
                        if (section.classList.contains(targetId)) {
                            section.classList.add('active');
                        }
                    });
                });
            });
            
            // Filter popup functionality
            const filterButton = document.getElementById('filter-pop-up');
            const filterPopup = document.querySelector('.filter-pop-up');
            const filterClose = document.getElementById('filter-pop-up-close');
            
            if (filterButton && filterPopup) {
                filterButton.addEventListener('click', function() {
                    filterPopup.classList.remove('deactive');
                });
                
                filterClose.addEventListener('click', function() {
                    filterPopup.classList.add('deactive');
                });
                
                filterPopup.addEventListener('click', function(e) {
                    if (e.target === filterPopup) {
                        filterPopup.classList.add('deactive');
                    }
                });
            }
            
            // Filter options toggle
            const filterTitles = document.querySelectorAll('.filter-title');
            
            filterTitles.forEach(title => {
                title.addEventListener('click', function() {
                    const options = this.nextElementSibling;
                    const icon = this.querySelector('i');
                    
                    options.classList.toggle('active');
                    icon.classList.toggle('rotated');
                });
            });
            
            // Sort selection functionality
            const sortInput = document.getElementById('selection-input');
            const sortOptions = document.getElementById('selection-options');
            
            if (sortInput && sortOptions) {
                sortInput.addEventListener('click', function() {
                    sortOptions.style.display = sortOptions.style.display === 'block' ? 'none' : 'block';
                });
                
                sortOptions.querySelectorAll('.opt').forEach(option => {
                    option.addEventListener('click', function() {
                        sortInput.value = this.textContent;
                        sortOptions.style.display = 'none';
                    });
                });
                
                // Close sort options when clicking outside
                document.addEventListener('click', function(e) {
                    if (!sortInput.contains(e.target) && !sortOptions.contains(e.target)) {
                        sortOptions.style.display = 'none';
                    }
                });
            }
            
            // Modal functionality
            initializeModals();
        });

        // Modal management
        function initializeModals() {
            // Request Details Modal
            const requestModalRoot = document.getElementById('requestModalRoot');
            const requestModalClose = document.getElementById('requestModalClose');
            
            if (requestModalRoot && requestModalClose) {
                requestModalClose.addEventListener('click', () => closeModal(requestModalRoot));
                requestModalRoot.addEventListener('click', (e) => {
                    if (e.target === requestModalRoot) closeModal(requestModalRoot);
                });
            }
            
            // Proposal Modal
            const proposalModalRoot = document.getElementById('proposalModalRoot');
            const proposalModalClose = document.getElementById('proposalModalClose');
            
            if (proposalModalRoot && proposalModalClose) {
                proposalModalClose.addEventListener('click', () => closeModal(proposalModalRoot));
                proposalModalRoot.addEventListener('click', (e) => {
                    if (e.target === proposalModalRoot) closeModal(proposalModalRoot);
                });
            }
            
            // Progress Modal
            const progressModalRoot = document.getElementById('progressModalRoot');
            const progressModalClose = document.getElementById('progressModalClose');
            
            if (progressModalRoot && progressModalClose) {
                progressModalClose.addEventListener('click', () => closeModal(progressModalRoot));
                progressModalRoot.addEventListener('click', (e) => {
                    if (e.target === progressModalRoot) closeModal(progressModalRoot);
                });
            }
            
            // Submit Modal
            const submitModalRoot = document.getElementById('submitModalRoot');
            const submitModalClose = document.getElementById('submitModalClose');
            
            if (submitModalRoot && submitModalClose) {
                submitModalClose.addEventListener('click', () => closeModal(submitModalRoot));
                submitModalRoot.addEventListener('click', (e) => {
                    if (e.target === submitModalRoot) closeModal(submitModalRoot);
                });
            }
            
            // Confirm Modal
            const confirmModalRoot = document.getElementById('confirmModalRoot');
            const confirmModalClose = document.getElementById('confirmModalClose');
            
            if (confirmModalRoot && confirmModalClose) {
                confirmModalClose.addEventListener('click', () => closeModal(confirmModalRoot));
                confirmModalRoot.addEventListener('click', (e) => {
                    if (e.target === confirmModalRoot) closeModal(confirmModalRoot);
                });
            }
            
            // Button event handlers
            setupButtonHandlers();
        }

        function setupButtonHandlers() {
            // View buttons - open request details modal
            document.querySelectorAll('.btn-view').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.search-item');
                    openRequestDetailsModal(card);
                });
            });
            
            // Propose buttons - open proposal modal
            document.querySelectorAll('.btn-propose').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.search-item');
                    openProposalModal(card);
                });
            });
            
            // Update buttons - open progress modal
            document.querySelectorAll('.btn-update').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.search-item');
                    openProgressModal(card);
                });
            });
            
            // Submit buttons - open submit modal
            document.querySelectorAll('.btn-submit').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.search-item');
                    openSubmitModal(card);
                });
            });
            
            // Decline buttons - open confirm modal
            document.querySelectorAll('.btn-decline').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.search-item');
                    openConfirmModal(
                        'Decline Request', 
                        'Are you sure you want to decline this request? This action cannot be undone.',
                        () => {
                            // Action to perform on confirm
                            card.remove();
                            alert('Request declined successfully.');
                        }
                    );
                });
            });
            
            // Withdraw buttons - open confirm modal
            document.querySelectorAll('.btn-withdraw').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.search-item');
                    openConfirmModal(
                        'Withdraw Proposal', 
                        'Are you sure you want to withdraw your proposal? This action cannot be undone.',
                        () => {
                            // Action to perform on confirm
                            card.remove();
                            alert('Proposal withdrawn successfully.');
                        }
                    );
                });
            });
            
            // Modal action buttons
            const btnPropose = document.getElementById('btnPropose');
            if (btnPropose) {
                btnPropose.addEventListener('click', function() {
                    closeModal(document.getElementById('requestModalRoot'));
                    openProposalModal();
                });
            }
            
            const btnUpdate = document.getElementById('btnUpdate');
            if (btnUpdate) {
                btnUpdate.addEventListener('click', function() {
                    closeModal(document.getElementById('requestModalRoot'));
                    openProgressModal();
                });
            }
            
            const btnSubmit = document.getElementById('btnSubmit');
            if (btnSubmit) {
                btnSubmit.addEventListener('click', function() {
                    closeModal(document.getElementById('requestModalRoot'));
                    openSubmitModal();
                });
            }
            
            const btnSendProposal = document.getElementById('btnSendProposal');
            if (btnSendProposal) {
                btnSendProposal.addEventListener('click', function() {
                    // Validate form
                    const amount = document.getElementById('proposalAmount').value;
                    const timeline = document.getElementById('proposalTimeline').value;
                    const description = document.getElementById('proposalDescription').value;
                    
                    if (!amount || !timeline || !description) {
                        alert('Please fill in all required fields.');
                        return;
                    }
                    
                    // Submit proposal (in a real app, this would be an API call)
                    alert('Proposal sent successfully!');
                    closeModal(document.getElementById('proposalModalRoot'));
                });
            }
            
            const btnUpdateProgress = document.getElementById('btnUpdateProgress');
            if (btnUpdateProgress) {
                btnUpdateProgress.addEventListener('click', function() {
                    // Validate form
                    const description = document.getElementById('progressDescription').value;
                    
                    if (!description) {
                        alert('Please provide a progress update.');
                        return;
                    }
                    
                    // Update progress (in a real app, this would be an API call)
                    alert('Progress updated successfully!');
                    closeModal(document.getElementById('progressModalRoot'));
                });
            }
            
            const btnSubmitForReview = document.getElementById('btnSubmitForReview');
            if (btnSubmitForReview) {
                btnSubmitForReview.addEventListener('click', function() {
                    // Validate form
                    const description = document.getElementById('submitDescription').value;
                    const files = document.getElementById('submitFiles').files;
                    
                    if (!description || files.length === 0) {
                        alert('Please provide submission notes and attach deliverables.');
                        return;
                    }
                    
                    // Submit for review (in a real app, this would be an API call)
                    alert('Project submitted for review successfully!');
                    closeModal(document.getElementById('submitModalRoot'));
                });
            }
            
            // Progress percentage slider
            const progressSlider = document.getElementById('progressPercent');
            if (progressSlider) {
                progressSlider.addEventListener('input', function() {
                    document.getElementById('progressPercentValue').textContent = this.value + '%';
                });
            }
            
            // Confirm modal actions
            const btnCancelAction = document.getElementById('btnCancelAction');
            const btnConfirmAction = document.getElementById('btnConfirmAction');
            
            if (btnCancelAction) {
                btnCancelAction.addEventListener('click', function() {
                    closeModal(document.getElementById('confirmModalRoot'));
                });
            }
            
            // Note: btnConfirmAction action is set dynamically in openConfirmModal
        }

        function openModal(modal) {
            modal.classList.remove('deactive');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modal) {
            modal.classList.add('deactive');
            document.body.style.overflow = '';
        }

        function openRequestDetailsModal(card) {
            const modal = document.getElementById('requestModalRoot');
            const client = card.querySelector('.item-name').textContent;
            const title = card.querySelector('.item-title').textContent;
            const description = card.querySelector('.item-description').textContent;
            const date = card.querySelector('.item-district span').textContent;
            
            // Extract budget and timeline information
            let budget = 'Budget: Not specified';
            let timeline = 'Timeline: Not specified';
            
            const middleItems = card.querySelectorAll('.item-middle div');
            middleItems.forEach(item => {
                const text = item.textContent;
                if (text.includes('Budget:')) budget = text;
                if (text.includes('Timeline:')) timeline = text;
            });
            
            // Set modal content
            document.getElementById('reqClient').textContent = client;
            document.getElementById('reqTitle').textContent = title;
            document.getElementById('reqDescription').textContent = description;
            document.getElementById('reqDate').textContent = date;
            document.getElementById('reqBudget').textContent = budget;
            document.getElementById('reqTimeline').textContent = timeline;
            
            // Show/hide buttons based on request status
            const status = card.getAttribute('data-status');
            const btnPropose = document.getElementById('btnPropose');
            const btnUpdate = document.getElementById('btnUpdate');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnWithdraw = document.getElementById('btnWithdraw');
            const btnDecline = document.getElementById('btnDecline');
            
            // Reset all buttons
            [btnPropose, btnUpdate, btnSubmit, btnWithdraw, btnDecline].forEach(btn => {
                if (btn) btn.style.display = 'none';
            });
            
            // Show appropriate buttons based on status
            switch(status) {
                case 'new':
                    if (btnPropose) btnPropose.style.display = '';
                    if (btnDecline) btnDecline.style.display = '';
                    break;
                case 'pending':
                    if (btnWithdraw) btnWithdraw.style.display = '';
                    break;
                case 'progress':
                    if (btnUpdate) btnUpdate.style.display = '';
                    if (btnSubmit) btnSubmit.style.display = '';
                    break;
            }
            
            // Show progress section for in-progress projects
            const progressSection = document.getElementById('modalProgressSection');
            if (progressSection) {
                if (status === 'progress') {
                    progressSection.style.display = 'block';
                    // Set progress values (in a real app, these would come from the data)
                    document.getElementById('modalProgressPercent').textContent = '65%';
                    document.getElementById('modalProgressDetail').textContent = '(32h of 50h)';
                    document.getElementById('modalProgressFill').style.width = '65%';
                } else {
                    progressSection.style.display = 'none';
                }
            }
            
            openModal(modal);
        }

        function openProposalModal(card) {
            const modal = document.getElementById('proposalModalRoot');
            
            if (card) {
                const client = card.querySelector('.item-name').textContent;
                const title = card.querySelector('.item-title').textContent;
                
                document.getElementById('proposalClient').textContent = client;
                document.getElementById('proposalTitle').textContent = title;
            }
            
            // Reset form
            document.getElementById('proposalAmount').value = '';
            document.getElementById('proposalTimeline').value = '';
            document.getElementById('proposalDescription').value = '';
            document.getElementById('proposalFiles').value = '';
            
            openModal(modal);
        }

        function openProgressModal(card) {
            const modal = document.getElementById('progressModalRoot');
            
            if (card) {
                const client = card.querySelector('.item-name').textContent;
                const title = card.querySelector('.item-title').textContent;
                
                document.getElementById('progressClient').textContent = client;
                document.getElementById('progressTitle').textContent = title;
            }
            
            // Reset form
            document.getElementById('progressPercent').value = '65';
            document.getElementById('progressPercentValue').textContent = '65%';
            document.getElementById('hoursWorked').value = '32';
            document.getElementById('progressDescription').value = '';
            document.getElementById('progressFiles').value = '';
            
            openModal(modal);
        }

        function openSubmitModal(card) {
            const modal = document.getElementById('submitModalRoot');
            
            if (card) {
                const client = card.querySelector('.item-name').textContent;
                const title = card.querySelector('.item-title').textContent;
                
                document.getElementById('submitClient').textContent = client;
                document.getElementById('submitTitle').textContent = title;
            }
            
            // Reset form
            document.getElementById('submitDescription').value = '';
            document.getElementById('submitFiles').value = '';
            
            openModal(modal);
        }

        function openConfirmModal(title, message, confirmAction) {
            const modal = document.getElementById('confirmModalRoot');
            
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            
            // Set up confirm action
            const btnConfirmAction = document.getElementById('btnConfirmAction');
            btnConfirmAction.onclick = function() {
                confirmAction();
                closeModal(modal);
            };
            
            openModal(modal);
        }

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.pop-up-section:not(.deactive)');
                openModals.forEach(modal => {
                    closeModal(modal);
                });
            }
        });
    </script>
</body>
</html>
