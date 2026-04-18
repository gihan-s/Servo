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
            <!-- Incoming Requests Section -->
            <div class="pending-requests active requests-section" id="section-pending" data-section="pending-requests">
                <p class="section-note">Incoming service requests from potential clients.</p>
                <div class="item-list">
                    <?php if (empty($incomingRequests)): ?>
                        <div class="search-item">
                            <div class="item-head">
                                <div class="item-main-dets">
                                    <div class="item-title">No incoming requests at this time.</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($incomingRequests as $request): ?>
                    <div class="search-item" data-status="pending" data-client="<?= htmlspecialchars($request['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-title="<?= htmlspecialchars($request['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-category="<?= htmlspecialchars($request['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-posted="<?= htmlspecialchars($request['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-budget="<?= htmlspecialchars($request['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-timeline="<?= htmlspecialchars($request['Timeline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-status-label="<?= htmlspecialchars($request['Status'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-description="<?= htmlspecialchars($request['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name"><?= htmlspecialchars($request['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-title"><?= htmlspecialchars($request['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-district">
                                    <span><?= htmlspecialchars($request['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                    <span><?= htmlspecialchars($request['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Proposal"><i class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i class="fa-solid fa-message"></i> Message</button>
                                <button class="btn-danger btn-withdraw" title="Withdraw Proposal"><i class="fa-solid fa-trash"></i> Withdraw</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-solid fa-clock"></i> Timeline: <?= htmlspecialchars($request['Timeline'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                            <div><i class="fa-solid fa-tag"></i> <?= htmlspecialchars($request['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                        <div class="item-description"><?= htmlspecialchars($request['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="status-bottom"><span class="status-chip status-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $request['Status'] ?? 'pending')), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($request['Status'] ?? 'Pending', ENT_QUOTES, 'UTF-8') ?></span></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Ongoing Section -->
            <div class="in-progress-requests requests-section" id="section-progress" data-section="in-progress-requests">
                <p class="section-note">Active projects you're currently working on.</p>
                <div class="item-list">
                    <?php if (empty($ongoingProjects)): ?>
                        <div class="search-item">
                            <div class="item-head">
                                <div class="item-main-dets">
                                    <div class="item-title">No ongoing projects at this time.</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($ongoingProjects as $project): ?>
                    <div class="search-item" data-status="progress" data-client="<?= htmlspecialchars($project['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-title="<?= htmlspecialchars($project['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-category="<?= htmlspecialchars($project['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-posted="<?= htmlspecialchars($project['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-budget="<?= htmlspecialchars($project['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-timeline="<?= htmlspecialchars($project['Timeline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-status-label="<?= htmlspecialchars($project['Status'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-progress="<?= htmlspecialchars($project['Progress'] ?? '0', ENT_QUOTES, 'UTF-8') ?>" data-progress-detail="<?= htmlspecialchars($project['Progress_Detail'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-logged="<?= htmlspecialchars($project['Hours_Logged'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-description="<?= htmlspecialchars($project['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name"><?= htmlspecialchars($project['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-title"><?= htmlspecialchars($project['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-district">
                                    <span><?= htmlspecialchars($project['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                    <span><?= htmlspecialchars($project['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
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
                            <div><i class="fa-solid fa-hourglass"></i> <?= htmlspecialchars($project['Timeline'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                            <div><i class="fa-solid fa-clock"></i> Logged <?= htmlspecialchars($project['Hours_Logged'] ?? '0h', ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                        <?php if (isset($project['Progress'])): ?>
                        <div class="progress-container" aria-label="Project progress">
                            <div class="progress-label">Progress: <span class="progress-percent"><?= htmlspecialchars($project['Progress'], ENT_QUOTES, 'UTF-8') ?>%</span> <span class="progress-detail" style="color:#64748b;">(<?= htmlspecialchars($project['Progress_Detail'] ?? '', ENT_QUOTES, 'UTF-8') ?>)</span></div>
                            <div class="progress-track"><div class="progress-fill" style="width:<?= htmlspecialchars($project['Progress'], ENT_QUOTES, 'UTF-8') ?>%"></div></div>
                        </div>
                        <?php endif; ?>
                        <div class="item-description"><?= htmlspecialchars($project['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="status-bottom"><span class="status-chip status-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $project['Status'] ?? 'progress')), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($project['Status'] ?? 'In Progress', ENT_QUOTES, 'UTF-8') ?></span></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Pending Review Section -->
            <div class="pending-review requests-section" id="section-review" data-section="pending-review">
                <p class="section-note">Project outputs submitted for review. Awaiting feedback or approval from client.</p>
                <div class="item-list">
                    <?php if (empty($pendingReviewProjects)): ?>
                        <div class="search-item">
                            <div class="item-head">
                                <div class="item-main-dets">
                                    <div class="item-title">No projects pending review at this time.</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($pendingReviewProjects as $review): ?>
                    <div class="search-item" data-status="review" data-client="<?= htmlspecialchars($review['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-title="<?= htmlspecialchars($review['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-category="<?= htmlspecialchars($review['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-posted="<?= htmlspecialchars($review['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-budget="<?= htmlspecialchars($review['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-timeline="<?= htmlspecialchars($review['Timeline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-status-label="<?= htmlspecialchars($review['Status'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-description="<?= htmlspecialchars($review['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name"><?= htmlspecialchars($review['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-title"><?= htmlspecialchars($review['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-district">
                                    <span><?= htmlspecialchars($review['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                    <span><?= htmlspecialchars($review['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Submission"><i class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-outline btn-message" title="Message Client"><i class="fa-solid fa-message"></i> Message</button>
                            </div>
                        </div>
                        <div class="item-description"><?= htmlspecialchars($review['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="status-bottom"><span class="status-chip status-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $review['Status'] ?? 'review')), ENT_QUOTES, 'UTF-8') ?>"><i class="fa-solid fa-clipboard-check"></i> <?= htmlspecialchars($review['Status'] ?? 'Pending Review', ENT_QUOTES, 'UTF-8') ?></span></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Completed Jobs Section -->
            <div class="completed-jobs requests-section" id="section-completed" data-section="completed-jobs">
                <p class="section-note">Successfully completed projects and delivered work.</p>
                <div class="item-list">
                    <?php if (empty($completedJobs)): ?>
                        <div class="search-item">
                            <div class="item-head">
                                <div class="item-main-dets">
                                    <div class="item-title">No completed jobs yet.</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($completedJobs as $job): ?>
                    <div class="search-item" data-status="complete" data-client="<?= htmlspecialchars($job['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-title="<?= htmlspecialchars($job['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-category="<?= htmlspecialchars($job['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-posted="<?= htmlspecialchars($job['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-budget="<?= htmlspecialchars($job['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-timeline="<?= htmlspecialchars($job['Timeline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-status-label="<?= htmlspecialchars($job['Status'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-description="<?= htmlspecialchars($job['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-name"><?= htmlspecialchars($job['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-title"><?= htmlspecialchars($job['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="item-district">
                                    <span><?= htmlspecialchars($job['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                    <span><?= htmlspecialchars($job['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-outline btn-view" title="View Project"><i class="fa-solid fa-eye"></i> View</button>
                                <button class="btn-outline" title="Download Files"><i class="fa-solid fa-download"></i> Files</button>
                            </div>
                        </div>
                        <div class="item-description"><?= htmlspecialchars($job['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="status-bottom"><span class="status-chip status-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $job['Status'] ?? 'complete')), ENT_QUOTES, 'UTF-8') ?>"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($job['Status'] ?? 'Completed', ENT_QUOTES, 'UTF-8') ?></span></div>
                    </div>
                    <?php endforeach; ?>
                </div>
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


