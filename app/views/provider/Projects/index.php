<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/providerProjects.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/incomingRequests.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/serviceProjects.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/projectDetailView.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <title>Provider Dashboard - Service Requests & Projects</title>

</head>

<body>
    <?php // Use filesystem path for includes (BASE_URL is for URLs, not filesystem)
    require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <div class="main-content">
        <section class="service-requests">
            <div class="header-requests">
                <h1 style="margin-bottom: 15px;">My Service Requests & Projects</h1>
              
                <div class="container-changer">
                    <!--<div id="new-requests" class="buttons active" data-target="new-requests">New Requests</div>-->
                    <div id="pending-requests" class="buttons active" data-target="pending-requests">Incoming Requests</div>
                    <div id="accepted-requests" class="buttons" data-target="accepted-requests">Accepted Requests</div>
                    <div id="in-progress-requests" class="buttons" data-target="in-progress-requests">Ongoing Projects</div>
                    <div id="pending-review" class="buttons" data-target="pending-review">Pending Review</div>
                    <div id="completed-jobs" class="buttons" data-target="completed-jobs">Completed</div>
                </div>
            </div>
            <div class="request-content">
              
                <!-- Incoming Requests Section -->
                <div class="pending-requests active requests-section" id="section-pending">
                    <p class="section-note">Incoming service requests from potential clients.</p>
                    <div class="item-list">

                    </div>
                    <div class="pagination" aria-label="Pending Requests Pagination">
                       
                    </div>
                </div>

                <!-- Accepted Requests Section -->
                <div class="accepted-requests requests-section" id="section-accepted">
                    <p class="section-note">Accepted requests awaiting client payment.</p>
                    <div class="item-list"></div>
                    <div class="pagination" aria-label="Accepted Requests Pagination"></div>
                </div>

                <!-- Ongoing Section -->
                <div class="in-progress-requests requests-section" id="section-progress">
                    <p class="section-note">Active projects you're currently working on.</p>
                    <div class="item-list"></div>
                    <div class="pagination" aria-label="In Progress Pagination"></div>
                </div>

                <!-- Pending Review Section -->
                <div class="pending-review requests-section" id="section-review">
                    <p class="section-note">Project outputs submitted for review. Awaiting feedback or approval from client.</p>
                    <div class="item-list">
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
                <div class="completed-jobs requests-section" id="section-completed">
                    <p class="section-note">Successfully completed projects and delivered work.</p>
                    <div class="item-list">
                    </div>
                    <div class="pagination" aria-label="Completed Jobs Pagination">
                    </div>
                </div>
            </div>
        </section>

        <!-- Filter Popup -->
        <div class="pop-up-section filter-pop-up deactive">
            <div class="pop-up deactive">
                <div class="pop-up-header">
                    <div class="pop-up-title">Add Filters</div>
                    <i class="fa-solid fa-xmark" id="filter-pop-up-close"></i>
                </div>
                <hr>
                <div class="pop-up-content">
                    <div class="search-filters">
                        <div class="filter-item">
                            <div class="filter-title"><span>Project Budget</span><i
                                    class="fa-solid fa-chevron-down rotated"></i>
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
                            <div class="filter-title"><span>Project Duration</span><i class="fa-solid fa-chevron-down"></i>
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
                            <div class="filter-title"><span>Project Category</span><i class="fa-solid fa-chevron-down"></i>
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
                        <div class="progress-track">
                            <div id="modalProgressFill" class="progress-fill"></div>
                        </div>
                    </div>
                    <!-- Client Requirements -->
                    <div id="modalRequirementsSection" class="modal-requirements">
                        <div style="font-weight:700; color:#111827; margin-top:4px;">Client Requirements</div>
                        <div id="reqRequirements" style="font-size:14px; color:#475569; line-height:1.6; margin-top:6px;">—</div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn-primary" id="btnPropose" style="display:none;"><i class="fa-solid fa-paper-plane"></i> Send Proposal</button>
                    <button class="btn-primary" id="btnSubmit" style="display:none;"><i class="fa-solid fa-paper-plane"></i> Submit for Review</button>
                    <button class="btn-primary" id="btnUpdate" style="display:none;"><i class="fa-solid fa-arrow-up"></i> Update Progress</button>


                    <button class="btn-outline" id="btnMessage"><i class="fa-solid fa-comments"></i> Message Client</button>
                    <button class="btn-danger" id="btnDecline"><i class="fa-solid fa-circle-xmark"></i> Reject</button>
                    <button class="btn-primary" id="btnAccept"><i class="fa-solid fa-circle-check"></i> Accept</button>
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
                        <label for="progressUpdateTitle">Update Title <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="progressUpdateTitle" placeholder="e.g. Completed homepage layout" maxlength="45">
                    </div>

                    <div class="form-group">
                        <label for="progressDescription">Update Description</label>
                        <textarea id="progressDescription" rows="4" placeholder="Describe what you've completed, any challenges, and next steps"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="hoursWorked">Hours Worked</label>
                        <input type="number" id="hoursWorked" min="0" step="0.5" placeholder="e.g. 4.5">
                    </div>

                    <div class="form-group">
                        <label for="progressFiles">Attach Files <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                        <input type="file" id="progressFiles" name="update_files[]" multiple
                            accept="image/*,.pdf,.zip,.txt">
                        <div class="file-note">Images, PDF, ZIP or TXT — max 10 MB each</div>
                    </div>

                    
                    <div class="form-group">
                        <label for="progressPercent">Progress Percentage</label>
                        <input type="range" id="progressPercent" min="0" max="100" value="0">
                        <div class="range-value"><span id="progressPercentValue">0%</span></div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn-primary" id="btnUpdateProgress">
                        <i class="fa-solid fa-arrow-up-right-dots"></i> Save Update
                    </button>
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

        <!-- Rejection Reason Modal -->
        <div class="pop-up-section confirm-modal deactive" id="rejectionModalRoot">
            <div class="pop-up" id="rejectionModal" style="max-width:500px;">
                <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                    <div class="pop-up-title">Reject Request</div>
                    <i class="fa-solid fa-xmark" id="rejectionModalClose" style="cursor:pointer;"></i>
                </div>
                <hr>
                <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                    <div style="font-size:14px; color:#475569;">
                        Please provide a reason for rejecting this request. The client will be notified.
                    </div>
                    <textarea id="rejectionReason"
                        rows="5"
                        placeholder="Enter your reason for rejection..."
                        style="padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit; font-size: 14px; resize: vertical;"></textarea>
                </div>
                <div class="modal-actions">
                    <button class="btn-secondary" id="btnCancelRejection">Cancel</button>
                    <button class="btn-danger" id="btnConfirmRejection"><i class="fa-solid fa-circle-xmark"></i> Reject Request</button>
                </div>
            </div>
        </div>

        <!-- Accept Confirmation Modal -->
        <div class="pop-up-section confirm-modal deactive" id="acceptModalRoot">
            <div class="pop-up" id="acceptModal" style="max-width:500px;">
                <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                    <div class="pop-up-title">Accept Request</div>
                    <i class="fa-solid fa-xmark" id="acceptModalClose" style="cursor:pointer;"></i>
                </div>
                <hr>
                <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                    <div style="font-size:14px; color:#475569;">
                        Are you sure you want to accept this request? You can start working on this project right away.
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn-secondary" id="btnCancelAccept">Cancel</button>
                    <button class="btn-primary" id="btnConfirmAccept"><i class="fa-solid fa-circle-check"></i> Accept Request</button>
                </div>
            </div>
        </div>
    </div> <!-- End of main-content -->

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

    <!-- Provider Requirements Dialog -->
    <div class="pop-up-section request-modal deactive" id="providerReqModalRoot">
        <div class="pop-up" style="max-width:720px; border-radius:16px; display:flex; flex-direction:column; max-height:88vh;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Project Requirements</div>
                <i class="fa-solid fa-xmark" id="providerReqModalClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div id="providerReqLoading" class="loading-state" style="padding:32px 0;">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading requirements&hellip;</p>
            </div>
            <div class="req-tabs" id="providerReqTabStrip" style="display:none; flex-shrink:0; padding:0 2px; margin-bottom:0;"></div>
            <div id="providerReqList" style="flex:1; overflow-y:auto; padding:15px 4px 12px; display:none;"></div>
        </div>
    </div>

    <!-- Requirement Rejection Modal -->
    <div class="pop-up-section confirm-modal deactive" id="reqRejectionModalRoot">
        <div class="pop-up" style="max-width:500px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Reject Requirement</div>
                <i class="fa-solid fa-xmark" id="reqRejectionClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
                <div style="font-size:14px; color:#475569;">Please provide a reason for rejecting this requirement. The client will be notified.</div>
                <textarea id="reqRejectionReason" rows="4" placeholder="Enter rejection reason&hellip;"
                    style="border:1px solid #e5e7eb; border-radius:8px; padding:10px 12px; font-size:14px; font-family:inherit; resize:vertical;"></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn-secondary" id="btnCancelReqRejection">Cancel</button>
                <button class="btn-danger" id="btnConfirmReqRejection"><i class="fa-solid fa-circle-xmark"></i> Reject</button>
            </div>
        </div>
    </div>

    <!-- Requirement Accept Confirm Modal -->
    <div class="pop-up-section confirm-modal deactive" id="reqApproveModalRoot">
        <div class="pop-up" style="max-width:460px; border-radius:16px;">
            <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="pop-up-title">Accept Requirement</div>
                <i class="fa-solid fa-xmark" id="reqApproveClose" style="cursor:pointer;"></i>
            </div>
            <hr>
            <div class="pop-up-content" style="display:flex; align-items:flex-start; gap:14px; padding:4px 0 8px;">
                <div style="width:44px; height:44px; border-radius:50%; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa-solid fa-circle-check" style="color:#16a34a; font-size:20px;"></i>
                </div>
                <div>
                    <div style="font-weight:700; font-size:15px; color:#111827; margin-bottom:5px;">Accept this requirement?</div>
                    <div style="font-size:13px; color:#6b7280; line-height:1.5;">The client will be notified that their requirement has been accepted.</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-secondary" id="btnCancelReqApprove">Cancel</button>
                <button class="btn-primary" id="btnConfirmReqApprove"><i class="fa-solid fa-circle-check"></i> Accept</button>
            </div>
        </div>
    </div>

    <!-- Provider Review Modal -->
    <div class="pop-up-section provider-review-popup deactive">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Leave a Review</div>
                <i class="fa-solid fa-xmark" id="provider-review-close"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                <form id="provider-review-form" onsubmit="return false;">
                    <input type="hidden" id="provider-review-post-id" value="">
                    <input type="hidden" id="provider-review-rating" value="">

                    <div class="form-group" style="margin-bottom:18px;">
                        <label style="font-weight:600; margin-bottom:8px; display:block;">Rating <span style="color:#ef4444;">*</span></label>
                        <div class="star-rating-input" style="font-size:28px; cursor:pointer; display:flex; gap:4px;">
                            <i class="fa-regular fa-star" data-value="1"></i>
                            <i class="fa-regular fa-star" data-value="2"></i>
                            <i class="fa-regular fa-star" data-value="3"></i>
                            <i class="fa-regular fa-star" data-value="4"></i>
                            <i class="fa-regular fa-star" data-value="5"></i>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:18px;">
                        <label for="provider-review-title" style="font-weight:600; margin-bottom:6px; display:block;">Title <span style="color:#94a3b8; font-weight:400;">(optional)</span></label>
                        <input type="text" id="provider-review-title" class="form-control" placeholder="Brief summary of your experience" maxlength="100" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                    </div>

                    <div class="form-group" style="margin-bottom:18px;">
                        <label for="provider-review-description" style="font-weight:600; margin-bottom:6px; display:block;">Description <span style="color:#94a3b8; font-weight:400;">(optional)</span></label>
                        <textarea id="provider-review-description" class="form-control" rows="4" placeholder="Share details about working with this client..." maxlength="512" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; resize:vertical;"></textarea>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                        <button type="button" class="btn-primary btn-submit-review"><i class="fa-solid fa-paper-plane"></i> Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/projectDetailView.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/incomingRequests.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/acceptedRequests.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/ongoingProjects.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/pendingReviewProjects.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/completedProjects.js"></script>
    <script>
        // Main functionality for provider interface
        document.addEventListener('DOMContentLoaded', function() {
            // Tab navigation
            const tabButtons = document.querySelectorAll('.container-changer .buttons');
            const tabSections = document.querySelectorAll('.requests-section');

            // Maps data-target value → reload function
            const tabReloaders = {
                'pending-requests':    () => window.requestsManager?.loadRequests(1),
                'accepted-requests':   () => window.acceptedRequestsManager?.loadRequests(1),
                'in-progress-requests':() => window.ongoingProjectsManager?.loadProjects(1),
                'pending-review':      () => window.pendingReviewProjectsManager?.loadProjects(1),
                'completed-jobs':      () => window.completedProjectsManager?.loadProjects(1),
            };

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

                    // Reload data for the activated tab
                    if (tabReloaders[targetId]) {
                        tabReloaders[targetId]();
                    }
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
                    // alert('Proposal sent successfully!');
                    showToast('success', 'Proposal sent successfully!');
                    closeModal(document.getElementById('proposalModalRoot'));
                });
            }

            const btnUpdateProgress = document.getElementById('btnUpdateProgress');
            if (btnUpdateProgress) {
                btnUpdateProgress.addEventListener('click', function() {
                    // Validate form
                    const description = document.getElementById('progressDescription').value;

                    // if (!description) {
                    //     alert('Please provide a progress update.');
                    //     return;
                    // }

                    // Update progress (in a real app, this would be an API call)
                    // alert('Progress updated successfully!');
                    // showToast('success', 'Progress updated successfully!');
                    closeModal(document.getElementById('progressModalRoot'));
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
            switch (status) {
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

        // ===== Provider Requirements Management =====
        let _providerReqCurrentPostId = null;
        let _providerReqData          = { pending: [], approved: [], rejected: [] };
        let _providerReqActiveTab     = 'pending';

        function openProviderRequirementsModal(postId) {
            _providerReqCurrentPostId = postId;
            _providerReqActiveTab     = 'pending';

            const root    = document.getElementById('providerReqModalRoot');
            const loading = document.getElementById('providerReqLoading');
            const strip   = document.getElementById('providerReqTabStrip');
            const list    = document.getElementById('providerReqList');

            if (loading) { loading.style.display = ''; loading.innerHTML = '<i class="fas fa-spinner fa-spin"></i><p>Loading requirements&hellip;</p>'; }
            if (strip)   { strip.style.display = 'none'; strip.innerHTML = ''; }
            if (list)    { list.innerHTML = ''; list.style.display = 'none'; }

            openModal(root);

            // Close button — clone to avoid duplicate listeners
            const closeBtn = document.getElementById('providerReqModalClose');
            if (closeBtn) {
                const newClose = closeBtn.cloneNode(true);
                closeBtn.parentNode.replaceChild(newClose, closeBtn);
                newClose.addEventListener('click', () => closeModal(root));
            }

            root.addEventListener('click', function _backdrop(e) {
                if (e.target === root) { closeModal(root); root.removeEventListener('click', _backdrop); }
            });

            loadProviderRequirements(postId);
        }

        function loadProviderRequirements(postId) {
            const loading = document.getElementById('providerReqLoading');
            const strip   = document.getElementById('providerReqTabStrip');
            const list    = document.getElementById('providerReqList');

            if (loading) { loading.style.display = ''; loading.innerHTML = '<i class="fas fa-spinner fa-spin"></i><p>Loading requirements&hellip;</p>'; }
            if (strip)   strip.style.display = 'none';
            if (list)    list.style.display = 'none';

            fetch(`${BASE_URL}/project/getrequirements/${postId}`)
                .then(r => r.json())
                .then(json => {
                    if (!json.success) throw new Error(json.error || 'Failed to load');

                    const reqs = json.data.requirements || [];
                    _providerReqData = { pending: [], approved: [], rejected: [] };
                    reqs.forEach(r => {
                        const s = r.Status || 'pending';
                        if (_providerReqData[s] !== undefined) _providerReqData[s].push(r);
                        else _providerReqData.pending.push(r);
                    });

                    if (loading) loading.style.display = 'none';
                    renderProviderReqTabStrip();
                    renderProviderReqPane(_providerReqActiveTab);
                    if (strip) strip.style.display = '';
                    if (list)  list.style.display  = '';
                })
                .catch(() => {
                    if (loading) loading.innerHTML = `
                        <div class="error-state">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Failed to load requirements.</p>
                            <button onclick="loadProviderRequirements(${postId})" class="retry-btn">Retry</button>
                        </div>`;
                });
        }

        function renderProviderReqTabStrip() {
            const strip = document.getElementById('providerReqTabStrip');
            if (!strip) return;

            const tabs = [
                { key: 'pending',  icon: 'fa-clock',        label: 'Pending'  },
                { key: 'approved', icon: 'fa-circle-check',  label: 'Accepted' },
                { key: 'rejected', icon: 'fa-circle-xmark',  label: 'Rejected' },
            ];

            strip.innerHTML = tabs.map(t => {
                const count  = (_providerReqData[t.key] || []).length;
                const active = t.key === _providerReqActiveTab ? 'active' : '';
                const badge  = count > 0
                    ? `<span class="req-tab-count req-tab-count-${t.key}">${count}</span>`
                    : '';
                return `<button class="req-tab ${active}" data-tab="${t.key}">
                    <i class="fa-solid ${t.icon}"></i> ${t.label}${badge}
                </button>`;
            }).join('');

            strip.querySelectorAll('.req-tab').forEach(btn => {
                btn.addEventListener('click', () => {
                    _providerReqActiveTab = btn.dataset.tab;
                    renderProviderReqTabStrip();
                    renderProviderReqPane(_providerReqActiveTab);
                });
            });
        }

        function renderProviderReqPane(status) {
            const list   = document.getElementById('providerReqList');
            const postId = _providerReqCurrentPostId;
            if (!list) return;

            list.innerHTML = buildProviderReqCardsHTML(_providerReqData[status] || [], status);

            list.querySelectorAll('.btn-req-approve').forEach(btn => {
                btn.addEventListener('click', () => providerApproveRequirement(parseInt(btn.dataset.reqId, 10), postId));
            });
            list.querySelectorAll('.btn-req-reject').forEach(btn => {
                btn.addEventListener('click', () => providerRejectRequirement(parseInt(btn.dataset.reqId, 10), postId));
            });
        }

        function _escHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        function buildProviderReqCardsHTML(requirements, status) {
            if (!requirements || requirements.length === 0) {
                const emptyMap = {
                    pending:  { icon: 'fa-clock',        text: 'No pending requirements.' },
                    approved: { icon: 'fa-circle-check', text: 'No accepted requirements yet.' },
                    rejected: { icon: 'fa-circle-xmark', text: 'No rejected requirements.' },
                };
                const e = emptyMap[status] || { icon: 'fa-clipboard-list', text: 'No requirements.' };
                return `<div class="req-empty">
                            <i class="fa-solid ${e.icon}"></i>
                            <p>${e.text}</p>
                        </div>`;
            }

            const statusMap = {
                pending:  { cls: 'req-status-pending',  icon: 'fa-clock',        label: 'Pending'  },
                approved: { cls: 'req-status-approved', icon: 'fa-circle-check', label: 'Approved' },
                rejected: { cls: 'req-status-rejected', icon: 'fa-circle-xmark', label: 'Rejected' },
            };

            return requirements.map(req => {
                const s = statusMap[req.Status] || statusMap['pending'];

                const createdDate = req.Created_At
                    ? new Date(req.Created_At).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                    : 'N/A';

                let dateLine = `<span class="req-date-item"><i class="fa-solid fa-calendar-plus"></i> Requested: ${createdDate}</span>`;
                if (req.Status === 'approved' && req.Approved_At) {
                    const d = new Date(req.Approved_At).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                    dateLine += `<span class="req-date-item req-date-approved"><i class="fa-solid fa-calendar-check"></i> Approved: ${d}</span>`;
                }
                if (req.Status === 'rejected' && req.Rejected_At) {
                    const d = new Date(req.Rejected_At).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                    dateLine += `<span class="req-date-item req-date-rejected"><i class="fa-solid fa-calendar-xmark"></i> Rejected: ${d}</span>`;
                }

                const filesHTML = req.files && req.files.length > 0
                    ? `<div class="req-files-block">
                           <div class="req-files-label"><i class="fa-solid fa-paperclip"></i> Attachments</div>
                           <div class="req-files">
                               ${req.files.map(f => {
                                   const name = f.split('/').pop();
                                   const url  = `${BASE_URL}/file/project-requirements/${f}`;
                                   return `<a href="${url}" target="_blank" class="req-file-chip" title="${_escHtml(name)}">
                                               <i class="fa-solid fa-file"></i> ${_escHtml(name)}
                                           </a>`;
                               }).join('')}
                           </div>
                       </div>`
                    : '';

                const rejectionReasonHTML = (req.Status === 'rejected' && req.Rejection_Reason)
                    ? `<div class="req-rejection-banner">
                           <i class="fa-solid fa-comment-slash"></i>
                           <div><strong>Rejection Reason:</strong> ${_escHtml(req.Rejection_Reason)}</div>
                       </div>`
                    : '';

                const actionButtons = req.Status === 'pending'
                    ? `<div class="req-card-actions">
                           <button class="req-btn req-btn-approve btn-req-approve" data-req-id="${req.Requirement_ID}">
                               <i class="fa-solid fa-circle-check"></i> Accept
                           </button>
                           <button class="req-btn req-btn-reject btn-req-reject" data-req-id="${req.Requirement_ID}">
                               <i class="fa-solid fa-circle-xmark"></i> Reject
                           </button>
                       </div>`
                    : '';

                return `
                    <div class="req-card req-card-${req.Status || 'pending'}">
                        <div class="req-card-stripe"></div>
                        <div class="req-card-body">
                            <div class="req-card-header">
                                <div class="req-card-title">${_escHtml(req.Requirement_Title || 'Untitled')}</div>
                                <span class="req-status-chip ${s.cls}">
                                    <i class="fa-solid ${s.icon}"></i> ${s.label}
                                </span>
                            </div>
                            ${req.Requirement_Description
                                ? `<div class="req-card-desc">${_escHtml(req.Requirement_Description)}</div>`
                                : ''}
                            ${filesHTML}
                            ${rejectionReasonHTML}
                        </div>
                        <div class="req-card-footer">
                            <div class="req-card-dates">${dateLine}</div>
                            ${actionButtons}
                        </div>
                    </div>`;
            }).join('');
        }

        function providerApproveRequirement(requirementId, postId) {
            const root = document.getElementById('reqApproveModalRoot');
            openModal(root);

            ['reqApproveClose', 'btnCancelReqApprove', 'btnConfirmReqApprove'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const clone = el.cloneNode(true);
                el.parentNode.replaceChild(clone, el);
            });

            const closeFn = () => closeModal(root);
            document.getElementById('reqApproveClose')?.addEventListener('click', closeFn);
            document.getElementById('btnCancelReqApprove')?.addEventListener('click', closeFn);
            root.addEventListener('click', function _bd(e) {
                if (e.target === root) { closeFn(); root.removeEventListener('click', _bd); }
            });

            const confirmBtn = document.getElementById('btnConfirmReqApprove');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', async () => {
                    confirmBtn.disabled = true;
                    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Accepting&hellip;';

                    try {
                        const res  = await fetch(`${BASE_URL}/project/update-requirement-status`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `requirement_id=${requirementId}&status=approved`
                        });
                        const data = await res.json();
                        if (data.success) {
                            closeModal(root);
                            if (typeof window.showSuccessToast === 'function') window.showSuccessToast('Success!', 'Requirement accepted.');
                            _providerReqActiveTab = 'approved';
                            loadProviderRequirements(postId);
                        } else {
                            if (typeof window.showErrorToast === 'function') window.showErrorToast('Error', data.error || 'Failed to accept');
                        }
                    } catch (e) {
                        if (typeof window.showErrorToast === 'function') window.showErrorToast('Error', 'An error occurred');
                    } finally {
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Accept';
                    }
                });
            }
        }

        function providerRejectRequirement(requirementId, postId) {
            const root  = document.getElementById('reqRejectionModalRoot');
            const input = document.getElementById('reqRejectionReason');
            if (input) input.value = '';
            openModal(root);

            // Clone buttons to remove stale listeners
            ['reqRejectionClose', 'btnCancelReqRejection', 'btnConfirmReqRejection'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const clone = el.cloneNode(true);
                el.parentNode.replaceChild(clone, el);
            });

            const closeFn = () => closeModal(root);
            document.getElementById('reqRejectionClose')?.addEventListener('click', closeFn);
            document.getElementById('btnCancelReqRejection')?.addEventListener('click', closeFn);

            const confirmBtn = document.getElementById('btnConfirmReqRejection');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', async () => {
                    const reason = document.getElementById('reqRejectionReason')?.value.trim() || '';
                    if (!reason) {
                        document.getElementById('reqRejectionReason')?.focus();
                        return;
                    }

                    confirmBtn.disabled = true;
                    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting&hellip;';

                    try {
                        const params = new URLSearchParams();
                        params.append('requirement_id', requirementId);
                        params.append('status', 'rejected');
                        params.append('rejection_reason', reason);

                        const res  = await fetch(`${BASE_URL}/project/update-requirement-status`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: params.toString()
                        });
                        const data = await res.json();

                        if (data.success) {
                            closeModal(root);
                            if (typeof window.showSuccessToast === 'function') window.showSuccessToast('Success!', 'Requirement rejected.');
                            _providerReqActiveTab = 'rejected';
                            loadProviderRequirements(postId);
                        } else {
                            if (typeof window.showErrorToast === 'function') window.showErrorToast('Error', data.error || 'Failed to reject');
                        }
                    } catch (e) {
                        if (typeof window.showErrorToast === 'function') window.showErrorToast('Error', 'An error occurred');
                    } finally {
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Reject';
                    }
                });
            }
        }
    </script>
</body>

</html>