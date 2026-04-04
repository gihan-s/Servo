<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/providerProjects.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/serviceProjects.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css" />
    <title>Provider Dashboard - Service Requests & Projects</title>
    <style>
        html,
        body {
            height: 100%;
        }

        body.projects-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body.projects-page .main-content {
            flex: 1 0 auto;
            width: 100%;
        }

        body.projects-page footer {
            margin-top: auto;
        }
    </style>
    
</head>
<body class="projects-page">
    <?php 
    require_once __DIR__ . '/../../includes/navbar.php';
    require_once __DIR__ . '/../../components/SearchHeader.php';
    require_once __DIR__ . '/../../components/FilterModal.php';
    ?>
    <div class="main-content">
    <section class="service-requests" style="padding-top: 12px; padding-bottom: 28px;">
        <?php
        $searchHeader = new SearchHeader([
            'inputId' => 'projectsSearchInput',
            'placeholder' => 'Search by title, category, or client...',
            'filterBtnId' => 'projectsFilterBtn',
            'searchBtnId' => 'projectsSearchBtn',
            'title' => 'Projects',
            'note' => 'Track incoming project requests, ongoing projects, and project history',
            'showTabs' => true,
            'tabs' => [
                ['id' => 'pending-requests', 'label' => 'Incoming Requests', 'target' => 'pending-requests', 'active' => true],
                ['id' => 'in-progress-requests', 'label' => 'Ongoing', 'target' => 'in-progress-requests'],
                ['id' => 'pending-review', 'label' => 'Pending Review', 'target' => 'pending-review'],
                ['id' => 'completed-jobs', 'label' => 'Completed', 'target' => 'completed-jobs']
            ]
        ]);
        $searchHeader->render();
        ?>
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
                                        class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-primary btn-propose" title="Send Proposal"><i
                                        class="fa-solid fa-paper-plane"></i> Propose</button>
                                <button class="btn-danger btn-decline" title="Decline Request"><i
                                        class="fa-solid fa-circle-xmark"></i> Decline</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-solid fa-clock"></i> Timeline: 2 weeks</div>
                            <div><i class="fa-solid fa-tag"></i> Category: Graphic Design</div>
                        </div>
                        <div class="item-description">Looking for a modern, minimalist logo for our new SaaS platform. Should work well in both digital and print formats.</div>
                        <div class="status-bottom"><span class="status-chip status-new">New Request</span></div>
                    </div>
                </div>
                <div class="pagination" aria-label="New Requests Pagination">
                    <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
    -->
            <!-- Incoming Requests Section -->
            <div class="pending-requests active requests-section" id="section-pending" data-section="pending-requests">
                <p class="section-note">Incoming service requests from potential clients.</p>
                <div class="item-list">
                    <div class="search-item" data-status="pending" data-client="Michael Chen" data-title="E-commerce Website Development" data-category="Web Development" data-posted="Proposed 12 Jul 2025" data-budget="Proposal: $2,500" data-timeline="4 weeks" data-status-label="Pending Response" data-requirements="Full e-commerce site with product catalog, shopping cart, and payment integration." data-description="Full e-commerce site with product catalog, shopping cart, and payment integration.">
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
                                        class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i
                                        class="fa-solid fa-message"></i> Message</button>
                                <button class="btn-danger btn-withdraw" title="Withdraw Proposal"><i
                                        class="fa-solid fa-trash"></i> Withdraw</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-solid fa-clock"></i> Timeline: 4 weeks</div>
                            <div><i class="fa-solid fa-tag"></i> Proposed: $2,500</div>
                        </div>
                        <div class="item-description">Full e-commerce site with product catalog, shopping cart, and payment integration.</div>
                        <div class="status-bottom"><span class="status-chip status-pending">Pending Response</span></div>
                    </div>
                </div>
                <div class="pagination" aria-label="Pending Requests Pagination">
                    <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Ongoing Section -->
            <div class="in-progress-requests requests-section" id="section-progress" data-section="in-progress-requests">
                <p class="section-note">Active projects you're currently working on.</p>
                <div class="item-list">
                    <div class="search-item" data-status="progress" data-client="Emma Wilson" data-title="Mobile App UI/UX Design" data-category="UI/UX Design" data-posted="Started 10 Jul 2025" data-budget="Budget: $1,200" data-timeline="ETA 12d" data-status-label="In Progress" data-progress="65" data-progress-detail="32h of 50h" data-logged="32h" data-requirements="Designing user interface and experience for a fitness tracking mobile application." data-description="Designing user interface and experience for a fitness tracking mobile application.">
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
                                <button class="btn-outline btn-view" title="View Project"><i class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i class="fa-solid fa-message"></i> Message</button>
                                <button class="btn-primary btn-update" title="Update Progress"><i class="fa-solid fa-arrow-up"></i> Update</button>
                                <button class="btn-primary btn-submit" title="Submit for Review"><i class="fa-solid fa-paper-plane"></i> Submit</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-solid fa-hourglass"></i> ETA 12d</div>
                            <div><i class="fa-solid fa-clock"></i> Logged 32h</div>
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
                    <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Pending Review Section -->
            <div class="pending-review requests-section" id="section-review" data-section="pending-review">
                <p class="section-note">Project outputs submitted for review. Awaiting feedback or approval from client.</p>
                <div class="item-list">
                    <div class="search-item" data-status="review" data-client="David Rodriguez" data-title="Website Content Writing" data-category="Content Writing" data-posted="Submitted 08 Jul 2025" data-budget="Payment: $600" data-timeline="Not specified" data-status-label="Pending Review" data-requirements="Wrote homepage, about us, and services page content for a digital marketing agency." data-description="Wrote homepage, about us, and services page content for a digital marketing agency.">
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
                                <button class="btn-outline btn-view" title="View Submission"><i class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i class="fa-solid fa-message"></i> Message</button>
                            </div>
                        </div>
                        <div class="item-description">Wrote homepage, about us, and services page content for a digital marketing agency.</div>
                        <div class="status-bottom"><span class="status-chip status-review"><i
                                    class="fa-solid fa-clipboard-check"></i> Pending Review</span></div>
                    </div>
                </div>
                <div class="pagination" aria-label="Pending Review Pagination">
                    <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Completed Jobs Section -->
            <div class="completed-jobs requests-section" id="section-completed" data-section="completed-jobs">
                <p class="section-note">Successfully completed projects and delivered work.</p>
                <div class="item-list">
                    <div class="search-item" data-status="complete" data-client="Jennifer Lee" data-title="Social Media Marketing Campaign" data-category="Digital Marketing" data-posted="Completed 01 Jul 2025" data-budget="Earned: $1,500" data-timeline="Not specified" data-status-label="Completed" data-requirements="30-day social media campaign with content creation and community management across 3 platforms." data-description="30-day social media campaign with content creation and community management across 3 platforms.">
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
                                <button class="btn-outline btn-view" title="View Project"><i class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-outline" title="Download Files"><i class="fa-solid fa-download"></i> Files</button>
                            </div>
                        </div>
                        <div class="item-description">30-day social media campaign with content creation and community management across 3 platforms.</div>
                        <div class="status-bottom"><span class="status-chip status-complete"><i
                                    class="fa-solid fa-circle-check"></i> Completed</span></div>
                    </div>
                </div>
                <div class="pagination" aria-label="Completed Jobs Pagination">
                    <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Popup -->
    <?php
    $filterModal = new FilterModal([
        'modalRootId' => 'projectsFilterRoot',
        'modalId' => 'projectsFilterModal',
        'closeId' => 'projectsFilterClose',
        'applyId' => 'projectsFilterApply',
        'clearId' => 'projectsFilterClear',
        'filters' => [
            [
                'type' => 'checkbox',
                'label' => 'Category',
                'id' => 'projectsCategoryList'
            ]
        ]
    ]);
    $filterModal->render();
    ?>

    <!-- Request Details Modal -->
    <div class="pop-up-section request-modal deactive" id="requestModalRoot">
        <div class="pop-up" id="requestModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Request Details</div>
                <i class="fa-solid fa-xmark" id="requestModalClose" style="cursor:pointer;"></i>
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
                        <i class="fa-solid fa-tag"></i>
                        <span id="reqBudget">Budget: $0</span>
                    </span>
                    <span class="status-chip" style="background:#f2effd; color:#4c1d95; border-color:#d7ccfa;">
                        <i class="fa-solid fa-clock"></i>
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
                <div id="reqAdditionalSection" class="modal-requirements" style="display:none;">
                    <div style="font-weight:700; color:#111827; margin-top:4px;">Additional Details</div>
                    <div id="reqAdditionalDetails" style="display:flex; flex-direction:column; gap:8px; margin-top:6px;"></div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary" id="btnPropose" style="display:none;"><i class="fa-solid fa-paper-plane"></i> Send Proposal</button>
                <button class="btn-primary" id="btnSubmit" style="display:none;"><i class="fa-solid fa-paper-plane"></i> Submit for Review</button>
                <button class="btn-primary" id="btnUpdate" style="display:none;"><i class="fa-solid fa-arrow-up"></i> Update Progress</button>
                <button class="btn-outline" id="btnMessage"><i class="fa-solid fa-message"></i> Message Client</button>
                <button class="btn-danger" id="btnDecline"><i class="fa-solid fa-circle-xmark"></i> Decline</button>
                <button class="btn-danger" id="btnWithdraw" style="display:none;"><i class="fa-solid fa-trash"></i> Withdraw</button>
            </div>
        </div>
    </div>

    <!-- Send Proposal Modal -->
    <div class="pop-up-section request-modal deactive" id="proposalModalRoot">
        <div class="pop-up" id="proposalModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Send Proposal</div>
                <i class="fa-solid fa-xmark" id="proposalModalClose" style="cursor:pointer;"></i>
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
                <button class="btn-primary" id="btnSendProposal"><i class="fa-solid fa-paper-plane"></i> Send Proposal</button>
            </div>
        </div>
    </div>

    <!-- Update Progress Modal -->
    <div class="pop-up-section request-modal deactive" id="progressModalRoot">
        <div class="pop-up" id="progressModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Update Progress</div>
                <i class="fa-solid fa-xmark" id="progressModalClose" style="cursor:pointer;"></i>
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
                <button class="btn-primary" id="btnUpdateProgress"><i class="fa-solid fa-arrow-up"></i> Update Progress</button>
            </div>
        </div>
    </div>

    <!-- Submit for Review Modal -->
    <div class="pop-up-section request-modal deactive" id="submitModalRoot">
        <div class="pop-up" id="submitModal" style="max-width:680px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Submit for Review</div>
                <i class="fa-solid fa-xmark" id="submitModalClose" style="cursor:pointer;"></i>
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
                <button class="btn-primary" id="btnSubmitForReview"><i class="fa-solid fa-paper-plane"></i> Submit for Review</button>
            </div>
        </div>
    </div>

    <!-- Confirm Action Modal -->
    <div class="pop-up-section confirm-modal deactive" id="confirmModalRoot">
        <div class="pop-up" id="confirmModal">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title" id="confirmTitle">Confirm Action</div>
                <i class="fa-solid fa-xmark" id="confirmModalClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" id="confirmMessage">
                Are you sure you want to proceed with this action?
            </div>
            <div class="modal-actions">
                <button class="btn-secondary" id="btnCancelAction">Cancel</button>
                <button class="btn-danger" id="btnConfirmAction"><i class="fa-solid fa-circle-check"></i> Confirm</button>
            </div>
        </div>
    </div>
    </div> <!-- End of main-content -->

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

    <script type="module" src="<?= BASE_URL ?>/assets/js/providerProjects.js"></script>
</body>
</html>


