
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Global variables for JavaScript -->
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
        window.currentProjectId = null;
        let requirementList = [];
        let removedRequirements = [];
    </script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clientPosts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/serviceProjects.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/incomingRequests.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/projectDetailView.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/projectDetailView.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <div class="header-top" style="display:flex; justify-content: space-between; align-items: center;">
                <h1>Service Requests & Projects</h1>
            </div>
        </div>
        <div class="container-changer">
            <div class="tab-buttons">
                <div id="pending" class="buttons active" data-target="pending">Pending Requests</div>
                <div id="accepted" class="buttons" data-target="accepted">Approved Requests</div>
                <div id="ongoing" class="buttons" data-target="ongoing">Ongoing Projects</div>
                <div id="pending-review" class="buttons" data-target="pending-review">Pending Review</div>
                <div id="completed" class="buttons" data-target="completed">Completed Projects</div>
            </div>
        </div>
        <div class="request-content">
            <div class="search-header">

                <div class="search-button">
                    <input type="text" id="searchInput" placeholder="Search my service requests...">
                    <button id="searchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>

                <div class="advance-search">
                    <span>Sort By: </span>
                    <div class="select-container" style="width: 150px;">

                        <div class="text-container">
                                <div class="label dropdown-label" style="visibility: hidden;"></div>
                                <input type="text" id="sortDropdown" class="text-field-dropdown"
                                    style="padding: 10px; background-color: var(--containerColor);" value="Date (Newest)"
                                    readonly>
                        </div>

                        <div class="options" id="sortOptions" style='max-height:none;'>
                                <div data-sort="date_desc">Date (Newest)</div>
                                <div data-sort="date_asc">Date (Oldest)</div>
                                <div data-sort="price_desc">Price (High)</div>
                                <div data-sort="price_asc">Price (Low)</div>
                                <div data-sort="views_desc">Views (Most)</div>
                                <div data-sort="views_asc">Views (Least)</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PENDING REQUESTS SECTION -->
            <div class="pending requests-section">
                <div class="item-list">
                </div>
            </div>

            <!-- ACCEPTED REQUESTS SECTION -->
            <div class="accepted requests-section" style="display: none;">
                <div class="item-list">
                </div>
            </div>

            <!-- ONGOING PROJECTS SECTION -->
            <div class="ongoing requests-section" style="display: none;">
                <div class="item-list">
                </div>
            </div>

             <!-- COMPLETED JOBS SECTION -->
             <div class="completed requests-section" style="display: none;">
                <div class="item-list">
                </div>
             </div>

             <!-- PENDING REVIEW SECTION -->
             <div class="pending-review requests-section" style="display: none;">
                <div class="item-list">
                </div>
            </div>
            

        </div>   
    </section>
        

</body>

<div class="dialog-box-2" id="view-post-popup">
    <div class="dialog-content">
        <div class="dialog-title">
            <div class="title">Post Details</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('view-post-popup')"></i>
            </div>
        </div>
        <div class="dialog-body"></div>
    </div>
</div>

<div class="dialog-box-2" id="confirm-payment">
    <div class="dialog-content">
        <div class="dialog-title">
            <div class="title">Confirm Payment</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('confirm-payment')"></i>
            </div>
        </div>
        <div class="dialog-body"></div>
        <div class="pop-up-content">
            Are you sure you want to confirm this payment?
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-delete" id="confirmKeep"
                onclick="closeDialogBox('confirm-payment')">Cancel</button>
            <button class="action-btn btn-edit" id="confirmPaymentBtn"><i class="fa-solid fa-check"></i> Confirm</button>
        </div>

    </div>

</div>

<div class="dialog-box-2" id="confirm-cancel">
    <div class="dialog-content" style="width: 400px;">
        <div class="dialog-title">
            <div class="title">Confirm Cancel</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('confirm-cancel')"></i>
            </div>
        </div>
        <div class="pop-up-content">
            Are you sure you want to cancel this request? This action cannot be undone.
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-view" id="confirmKeep"
                onclick="closeDialogBox('confirm-cancel')">Keep</button>
            <button class="action-btn btn-delete" id="confirmCancelBtn"><i class="fa-solid fa-circle-xmark"></i> Yes,
                Cancel</button>
        </div>

    </div>

</div>

<div class="dialog-box-2" id="cancel-ongoing-project">
    <div class="dialog-content" style="width: 400px;">
        <div class="dialog-title">
            <div class="title">Confirm Cancel</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('cancel-ongoing-project')"></i>
            </div>
        </div>
        <div class="pop-up-content">
            Send Cancelling request to the provider. If project get cancelled you will charge additional amount and other amount will refunded to your account
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-view" id="confirmKeep"
                onclick="closeDialogBox('cancel-ongoing-project')">Keep</button>
            <button class="action-btn btn-delete" id="confirmCancelBtn"><i class="fa-solid fa-circle-xmark"></i> Yes,
                Cancel</button>
        </div>

    </div>

</div>

<div class="dialog-box-2" id="update-requirements-popup">
    <div class="dialog-content" style="width:720px; max-width:95vw; display:flex; flex-direction:column; max-height:88vh;">
        <div class="dialog-title">
            <div class="title">Project Requirements</div>
            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('update-requirements-popup')"></i>
            </div>
        </div>

        <!-- Tab strip -->
        <div class="req-tabs" style="display:flex; gap:0; border-bottom:2px solid #e5e7eb; margin-bottom:16px; flex-shrink:0;">
            <button class="req-tab active" data-tab="req-list-pane">
                <i class="fa-solid fa-list-check"></i> Current Requirements
            </button>
            <button class="req-tab" data-tab="req-add-pane">
                <i class="fa-solid fa-plus"></i> Add New
            </button>
        </div>

        <!-- Current requirements pane -->
        <div id="req-list-pane" class="req-pane" style="flex:1; overflow-y:auto; min-height:0;">
            <div class="loading-state" id="req-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading requirements…</p>
            </div>
            <div id="req-list-body" style="display:none;"></div>
        </div>

        <!-- Add new requirement pane -->
        <div id="req-add-pane" class="req-pane" style="display:none; flex:1; overflow-y:auto; min-height:0;">
            <form id="req-add-form" onsubmit="return false;" style="display:flex; flex-direction:column; gap:14px; padding:2px 0 8px;">
                <div class="req-form-group">
                    <label for="reqTitle">Title <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="reqTitle" maxlength="200" placeholder="e.g. Add dark mode support">
                </div>
                <div class="req-form-group">
                    <label for="reqDescription">Description <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                    <textarea id="reqDescription" rows="4" placeholder="Describe the requirement in detail…"></textarea>
                </div>
                <div class="req-form-group">
                    <label for="reqFiles">Attach Files <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                    <input type="file" id="reqFiles" name="req_files[]" multiple accept="image/*,.pdf,.zip,.txt">
                    <div style="font-size:12px; color:#6b7280; margin-top:4px;">Images, PDF, ZIP or TXT — max 10 MB each</div>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:8px; padding-top:4px;">
                    <button type="button" class="action-btn btn-delete" onclick="document.getElementById('req-add-form').reset()">Reset</button>
                    <button type="button" class="action-btn btn-edit" id="btnSubmitReq">
                        <i class="fa-solid fa-paper-plane"></i> Submit Requirement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="dialog-box-2" id="complete-project-popup">
    <div class="dialog-content" style="width: 560px; max-width: 95vw;">
        <div class="dialog-title">
            <div class="title">Complete Project &amp; Leave Review</div>
            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('complete-project-popup')"></i>
            </div>
        </div>
        <div class="dialog-body">
            <p style="margin: 0 0 16px; color: #6b7280; font-size: 14px;">
                Completing this project is permanent. Please rate your experience working with this provider.
            </p>
            <!-- Star Rating -->
            <div class="req-form-group" style="margin-bottom: 16px;">
                <label>Rating <span style="color:#ef4444;">*</span></label>
                <div class="star-rating-input" id="starRatingInput">
                    <i class="fa-regular fa-star" data-value="1"></i>
                    <i class="fa-regular fa-star" data-value="2"></i>
                    <i class="fa-regular fa-star" data-value="3"></i>
                    <i class="fa-regular fa-star" data-value="4"></i>
                    <i class="fa-regular fa-star" data-value="5"></i>
                </div>
                <input type="hidden" id="reviewRating" value="">
                <div id="ratingError" style="color:#ef4444; font-size:12px; margin-top:4px; display:none;">Please select a rating before submitting.</div>
            </div>
            <!-- Title -->
            <div class="req-form-group" style="margin-bottom: 12px;">
                <label for="reviewTitle">Review Title <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                <input type="text" id="reviewTitle" placeholder="e.g. Great work!" maxlength="100">
            </div>
            <!-- Description -->
            <div class="req-form-group" style="margin-bottom: 12px;">
                <label for="reviewDescription">Comments <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                <textarea id="reviewDescription" rows="4" placeholder="Share your experience working with this provider..."></textarea>
            </div>
            <!-- Files -->
            <div class="req-form-group">
                <label for="reviewFiles">Attach Files <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                <input type="file" id="reviewFiles" name="review_files[]" multiple accept="image/*,.pdf,.zip,.txt">
                <div style="font-size:12px; color:#6b7280; margin-top:4px;">Images, PDF, ZIP or TXT — max 10 MB each</div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-delete" onclick="closeDialogBox('complete-project-popup')">Cancel</button>
            <button class="action-btn btn-edit" id="btnConfirmCompleteProject">
                <i class="fa-solid fa-circle-check"></i> Complete &amp; Submit Review
            </button>
        </div>
    </div>
</div>

<div class="dialog-box-2" id="reopen-project-popup">
    <div class="dialog-content" style="width: 560px; max-width: 95vw;">
        <div class="dialog-title">
            <div class="title">Request Changes</div>
            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('reopen-project-popup')"></i>
            </div>
        </div>
        <div class="dialog-body">
            <p style="margin: 0 0 12px; color: #6b7280; font-size: 14px;">
                This will move the project back to ongoing. Add feedback for the provider.
            </p>
            <div class="req-form-group" style="margin-bottom: 12px;">
                <label for="reopenReason">Reason <span style="color:#ef4444;">*</span></label>
                <textarea id="reopenReason" rows="5" placeholder="Describe what needs to be revised..."></textarea>
            </div>
            <div class="req-form-group">
                <label for="reopenFiles">Attach Files <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                <input type="file" id="reopenFiles" name="reopen_files[]" multiple accept="image/*,.pdf,.zip,.txt">
                <div style="font-size:12px; color:#6b7280; margin-top:4px;">Images, PDF, ZIP or TXT — max 10 MB each</div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-delete" onclick="closeDialogBox('review-popup')">Cancel</button>
            <button class="action-btn btn-edit" id="saveReviewCommentsBtn" onclick="addReviewComments()">
                <i class="fa-solid fa-check"></i> Submit
            </button>
        </div>
    </div>
</div>

<div class="dialog-box-2" id="create-post-popup">
    <div class="dialog-content">
        <div class="dialog-title">
            <div class="title">Create A New Service Request</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('create-post-popup')"></i>
            </div>
        </div>
        <form id="create-post-form" class="create-post-form" onsubmit="return false;">
            
            <div class="input-grid-1">
                <div style="display: flex; gap: 15px; margin-bottom: 5px;">

                    <div class="search-select-container add-option" style="width: 100%;">

                        <div class="text-container">
                            <div class="label search-dropdown-label" id="field-skill-label">Skill</div>
                            <input type="text" class="text-field-search-dropdown" id="SkillAddInput" autocomplete="off"
                                onkeydown="return false">
                        </div>

                        <div class="options">

                            <span class="text-container">
                                <input type="text" class="text-field-search" placeholder="Enter new skill to add">
                            </span>

                            <div class="option-list" id="SkillsOptionList">

                            </div>
                        </div>
                    </div>

                    <button class="button" style="white-space: nowrap;" onclick="addSkill();">
                        <i class="fa-solid fa-plus" style="margin-right: 10px;"></i>Add
                    </button>

                </div>
            </div>
            <div class="chip-wrapper" id="SkillsChips" style="margin-bottom:20px">
                <input type="hidden" id="Skills" name="skills">

                <p>No skill selected</p>
            </div>
            

            <div class="modal-actions">
                <button type="reset" class="action-btn btn-delete" id="create-post-pop-up" data-role="cancel"
                    onclick="inputReset('create-post-form')">Reset</button>
                <button type="button" class="action-btn btn-view" onclick="submitPost('draft')" id="create-post-pop-up"
                    data-role="save-draft">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Draft
                </button>
                <button type="button" class="action-btn btn-edit" onclick="viewDialogBox('confirm-publish')"
                    id="create-post-pop-up" data-role="publish">
                    <i class="fa-solid fa-rocket"></i>
                    Publish Request
                </button>
                <button type="button" class="action-btn btn-edit" onclick="saveEditedPost()" data-role="save-post"
                    style="display:none;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Request
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    let currentSort = 'date_desc';
    let currentSearch = '';
    let searchTimeout = null;
    const PAGE_SIZE = 5;
    const postsState = {
        pending: { posts: [], visibleCount: 0 },
        accepted: { posts: [], visibleCount: 0 },
        ongoing: { posts: [], visibleCount: 0 },
        'pending-review': { posts: [], visibleCount: 0 },
        'completed': { posts: [], visibleCount: 0 }
    }

    function getListContainer(status) {
        return document.querySelector(`.${status} .item-list`);
    }

    function clearLoadMoreButton(status) {
        const section = document.querySelector(`.${status}`);
        if (!section) return;
        const existing = section.querySelector('.load-more-wrap');
        if (existing) existing.remove();
    }

    function loadPosts(tab = 'ongoing') {
        const container = getListContainer(tab);
        if (!container) return;

        container.innerHTML = `
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading posts...</p>
        </div>
    `;
        clearLoadMoreButton(tab);

        console.log('Loading posts for tab:', tab);

        // Build the correct API URL based on tab
        let apiUrl;
        if (tab === 'ongoing') {
            // Ongoing projects come from the project table
            apiUrl = `${window.BASE_URL}/projects/ongoing?sort=${currentSort}&search=${encodeURIComponent(currentSearch)}`;
        } else if (tab === 'pending-review') {
            // Pending-review projects also come from the project table
            apiUrl = `${window.BASE_URL}/projects/pending-review?sort=${currentSort}&search=${encodeURIComponent(currentSearch)}`;
        } else if (tab === 'completed') {
            // Completed projects come from the project table
            apiUrl = `${window.BASE_URL}/projects/completed?sort=${currentSort}&search=${encodeURIComponent(currentSearch)}`;
        } else {
            // Map tab name to actual Request_Status value
            const statusMap = { 'pending': 'ongoing', 'accepted': 'accepted', 'completed': 'completed' };
            const apiStatus = statusMap[tab] || tab;
            apiUrl = `${window.BASE_URL}/projects/list?status=${apiStatus}&sort=${currentSort}&search=${encodeURIComponent(currentSearch)}`;
        }

        fetch(apiUrl)
            //.then(response => response.text())
            
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Parsed data:', data);

                // Add delay to make loading animation visible
                return new Promise(resolve => setTimeout(() => resolve(data), 300));
            })
            .then(data => {
                if (data.success && data.posts) {
                    postsState[tab].posts = data.posts;
                    postsState[tab].visibleCount = Math.min(PAGE_SIZE, data.posts.length);
                    renderPosts(tab);
                    return;
                }

                postsState[tab].posts = [];
                postsState[tab].visibleCount = 0;
                showEmptyState(tab, container);
            })
            .catch(error => {
                console.error('Error loading posts:', error);
                container.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Failed to load posts. Please try again.</p>
                    <p style="font-size: 12px; color: #999;">${error.message}</p>
                    <button onclick="loadPosts('${tab}')" class="retry-btn">Retry</button>
                </div>
            `;
                clearLoadMoreButton(tab);
            });
    }

    function renderLoadMoreButton(status) {
        const section = document.querySelector(`.${status}`);
        const container = getListContainer(status);
        if (!section || !container) return;

        clearLoadMoreButton(status);

        const state = postsState[status];
        if (!state || state.visibleCount >= state.posts.length) {
            return;
        }

        const wrap = document.createElement('div');
        wrap.className = 'load-more-wrap';
        wrap.style.display = 'flex';
        wrap.style.justifyContent = 'center';
        wrap.style.marginTop = '35px';

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'action-btn btn-view';
        button.textContent = 'Load More';
        button.addEventListener('click', function () {
            state.visibleCount = Math.min(state.visibleCount + PAGE_SIZE, state.posts.length);
            renderPosts(status);
        });

        wrap.appendChild(button);
        section.appendChild(wrap);
    }

    function renderPosts(status) {
        const container = getListContainer(status);
        if (!container) return;

        const state = postsState[status];
        const visiblePosts = state.posts.slice(0, state.visibleCount);

        if (visiblePosts.length === 0) {
            showEmptyState(status, container);
            clearLoadMoreButton(status);
            return;
        }

        container.innerHTML = '';
        visiblePosts.forEach(item => {
            console.log('Rendering post:', item);
            let postHTML;
            if (status === 'pending') {
                postHTML = createPendingCard(item.post, item.skills);
            } else if (status === 'accepted') {
                postHTML = createAcceptedCard(item.post, item.skills);
            } else if (status === 'ongoing') {
                postHTML = createOngoingCard(item.post, item.skills);
            } else if (status === 'pending-review') {
                postHTML = createPendingReviewCard(item.post, item.skills);
            } else if (status === 'completed') {
                postHTML = createCompletedCard(item.post, item.skills);
            } else {
                postHTML = createPostCard(item.post, item.skills, status);
            }
            container.insertAdjacentHTML('beforeend', postHTML);
        });

        renderLoadMoreButton(status);
    }

    function showEmptyState(status, container) {
        let message = '';
        let icon = 'fa-inbox';

        if (status === 'pending') {
            message = `
                <h2>No pending requests right now</h2>
                <p>Your pending requests will appear here.</p>
            `;
        } else if (status === 'accepted') {
            message = `
                <h2>No accepted requests right now</h2>
                <p>Your accepted requests will appear here.</p>
            `;
        } else if (status === 'ongoing') {
            message = `
                <h2>No ongoing projects right now</h2>
                <p>Your ongoing projects will appear here.</p>
            `;
        }
        else if (status === 'pending-review') {
            message = `
                <h2>No pending reviews right now</h2>
                <p>Your pending reviews will appear here.</p>
            `;
        } else if (status === 'completed') {
            message = `
                <h2>No completed projects right now</h2>
                <p>Your completed projects will appear here.</p>
            `;
        }

        container.innerHTML = `
            <section class="empty-state">
                <i class="fas ${icon}"></i>
                ${message}
            </section>
        `;
    }

    function createPostCard(post, skills, status = 'pending') {
        //const skillsHTML = skills && skills.length > 0
        //    ? skills.map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('')
        //    : '<span class="no-skills">---No skills specified---</span>';

        const description = post.Description || '';
        const snippet = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);

        const providerName = post.Provider_Name || 'Unassigned provider';

        //const publishedDate = formatDate(post.Published_At || post.Created_At);
        //const daysLeft = calculateDaysLeft(post.End_At);
        //if (daysLeft == 'Expired') {
        //    updateAsExpired(post.Post_ID);
        //}

        // Different buttons based on status
        let actionsHTML = '';
        if (status === 'pending') {
            actionsHTML = `
                <a href="<?= $navRight[0]['href'] ?>" class="action-btn btn-edit <?= ('./' . basename( $_SERVER['REQUEST_URI'])) === $navRight[0]['href'] ? 'active' : '' ?>"
                aria-label="Messages">
                    <i class="fas fa-comments"></i>Messages
                </a>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-delete" onclick="cancelRequest(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Cancel
                </button>
            `;
        } else if (status === 'accepted') {
            actionsHTML = `
                <a href="<?= $navRight[0]['href'] ?>" class="action-btn btn-edit <?= ('./' . basename( $_SERVER['REQUEST_URI'])) === $navRight[0]['href'] ? 'active' : '' ?>"
                aria-label="Messages">
                    <i class="fas fa-comments"></i>Messages
                </a>
                <button class="action-btn btn-edit" onclick="payPayment(${post.Post_ID})">
                    <i class="fas fa-credit-card"></i> Pay
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-delete" onclick="cancelRequest(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Cancel
                </button>
            `;
        } else if (status === 'ongoing') {
            actionsHTML = `
                <button class="action-btn btn-edit" onclick="updateRequest(${post.Post_ID})">
                    <i class="fas fa-redo"></i> Update
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-delete" onclick="cancelOngoingProject(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Cancel
                </button>
            `;
        }
        else if (status === 'pending-review') {
            actionsHTML = `
                <button class="action-btn btn-edit" onclick="submitReview(${post.Post_ID})">
                    <i class="fas fa-star"></i> Submit Review
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
            `;
        }
        else if (status === 'completed') {
            actionsHTML = `
                <a href="<?= $navRight[0]['href'] ?>" class="action-btn btn-edit <?= ('./' . basename( $_SERVER['REQUEST_URI'])) === $navRight[0]['href'] ? 'active' : '' ?>"
                aria-label="Messages">
                    <i class="fas fa-comments"></i>Messages
                </a>
                <button class="action-btn btn-edit" onclick="updateRequest(${post.Post_ID})">
                    <i class="fas fa-redo"></i> Change Requirements
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-view" onclick="Complete(${post.Post_ID})">
                    <i class="fas fa-check"></i> Complete
                </button>
            `;
        }


        // Different date label based on status
        //let dateLabel = '';
        //if (status === 'draft') {
        //    dateLabel = `Created ${publishedDate}`;
        //} else if (status === 'expired') {
        //    dateLabel = `Expired ${publishedDate}`;
        //} else {
        //    dateLabel = `Published ${publishedDate}`;
        //}

        // Different footer based on status
        /*let footerHTML = '';
        if (status === 'expired') {
            footerHTML = `
                <div class="post-footer">
                    <div class="post-details">
                        <div class="detail-item">
                            <span class="detail-label">Budget</span>
                            <span class="detail-value budget-amount">LKR ${post.Requesting_Price || 0}/= (${post.Price_Type || 'Fixed'})</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Final Proposals</span>
                            <span class="detail-value proposals-count">${post.Proposal_Count || post.ProposalsCount || 0}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value project-duration">${post.Duration || 0} ${post.Duration_Type || 'Days'}</span>
                        </div>
                    </div>
                </div>
            `;
        } else {
            footerHTML = `
                <div class="post-footer">
                    <div class="post-details">
                        <div class="detail-item">
                            <span class="detail-label">Budget</span>
                            <span class="detail-value budget-amount">LKR ${post.Requesting_Price || 0}/= (${post.Price_Type || 'Fixed'})</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Proposals Received</span>
                            <span class="detail-value proposals-count">${post.Proposal_Count || post.ProposalsCount || 0}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Level</span>
                            <span class="detail-value project-level">${post.Level || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value project-duration">${post.Duration || 0} ${post.Duration_Type || 'Days'}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Different engagement stats based on status
        let engagementHTML = '';
        if (status === 'expired') { // Ensure post is marked as expired
            engagementHTML = `
                <div class="engagement-stats">
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span>${post.Views || post.View_Count || 0} views</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-clock"></i>
                        <span>Expired</span>
                    </div>
                </div>
            `;
        } else if (status === 'draft') {
            engagementHTML = ''; // No engagement stats for drafts
        } else {
            engagementHTML = `
                <div class="engagement-stats">
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span>${post.Views || post.View_Count || 0} views</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-clock"></i>
                        <span>${daysLeft}</span>
                    </div>
                </div>
            `;
        }*/

        // Post type badge
        console.log('Post :', post);
        const postTypeLabel = post.Post_Type === 'direct' ? 'Direct Request' : 'Bid Request';
        const postTypeHTML = `
            <div class="post-type-badge">
                <span class="badge-label ${post.Post_Type === 'direct' ? 'direct' : 'bid'}">${postTypeLabel}</span>
            </div>
        `;

        let progressHTML = '';
        if (status === 'ongoing') {
            const progress = post.Progress || 0;
            // const hoursWorked = Math.round((progress / 100) * 80);
            progressHTML = `<div class="progress-container" aria-label="Project progress">
                <div class="progress-label">Progress: <span class="progress-percent">${progress}%</span> </div>
                <div class="progress-track"><div class="progress-fill" style="width: ${progress}%;"></div></div>
            </div>`;
        }

        return `
            <div class="search-item">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                <div class="item-head">
                    <div class="item-main-dets">
                        <div class="item-name"><i class="fas fa-user"></i><span>${escapeHtml(providerName)}</span></div>
                        <div class="item-title">${escapeHtml(post.Title)}</div>
                        <div class="item-description">
                            ${snippet}
                        </div>
                    </div>
                    <div class="post-actions">
                         ${actionsHTML}
                     </div>
                </div>
                <div class="item-middle">
                    <div><i class="fa-solid fa-tag"></i> Proposed: LKR ${post.Requesting_Price || 0}/= (${post.Price_Type || 'Fixed'})</div>
                </div>
                ${progressHTML}
                ${postTypeHTML}
            </div>
        `;
    }

    function createPendingCard(post, skills) {
        const providerName = post.Provider_Name || 'Unassigned Provider';
        const providerPicture = post.Provider_Picture
            ? `${window.BASE_URL}/file/user-files/${post.Provider_Picture}`
            : null;
        const rating = post.Provider_Rating ? parseFloat(post.Provider_Rating).toFixed(1) : '0.0';
        const category = post.Category_Name || 'N/A';
        const estDate = formatEstDate(post.Est_Date);
        const budget = `Rs. ${Number(post.Requesting_Price || 0).toLocaleString('en-US', {minimumFractionDigits:2})} (${post.Price_Type || 'Fixed'})`;
        const description = post.Description || '';
        const snippet = description.length > 200
            ? escapeHtml(description.substring(0, 200)) + '...'
            : escapeHtml(description);
        const postedDate = formatDate(post.Created_At);
        const postTypeLabel = post.Post_Type === 'direct' ? 'Direct' : 'Bid';

        const avatarHTML = providerPicture
            ? `<img src="${providerPicture}" alt="${escapeHtml(providerName)}" class="request-avatar" onerror="this.style.display='none'">`
            : `<div class="request-avatar" style="width:56px;height:56px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;"><i class="fas fa-user" style="color:#9ca3af;font-size:22px;"></i></div>`;

        return `
            <div class="search-item" data-post-id="${post.Post_ID}">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                <div class="request-header">
                    <div class="request-client-section">
                        ${avatarHTML}
                        <div class="request-client-info">
                            <div class="request-client-name">${escapeHtml(providerName)}</div>
                            <div class="request-client-location">&#11088; ${rating} (0 reviews)</div>
                        </div>
                    </div>
                    <div class="request-actions">
                        <button class="btn-outline" onclick="viewPost(${post.Post_ID})" title="View Request">
                            <i class="fa-solid fa-eye"></i> View
                        </button>
                        <a href="${window.BASE_URL}/messages?new=${post.Provider_ID}" style="text-decoration:none;">
                            <button class="btn-outline" title="Message Provider">
                                <i class="fa-solid fa-comments"></i> Message
                            </button>
                        </a>
                        <button class="btn-primary btn-danger" onclick="cancelRequest(${post.Post_ID})" title="Cancel Request">
                            <i class="fa-solid fa-circle-xmark"></i> Cancel
                        </button>
                    </div>
                </div>
                <div class="request-title">${escapeHtml(post.Title)}</div>
                <div class="request-description">${snippet}</div>
                <div class="request-details">
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                        <span class="request-detail-value">${budget}</span>
                    </div>
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-tag"></i> Category</span>
                        <span class="request-detail-value">${escapeHtml(category)}</span>
                    </div>
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-calendar"></i> Est. Date</span>
                        <span class="request-detail-value">${estDate}</span>
                    </div>
                </div>
                <div class="request-footer">
                    <span class="request-time">${postedDate}</span>
                    <span class="status-chip status-pending">${postTypeLabel} Request</span>
                </div>
            </div>
        `;
    }

    function formatEstDate(estDate) {
        if (!estDate) return 'N/A';
        const end = new Date(estDate);
        const now = new Date();
        now.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);
        const diffDays = Math.ceil((end - now) / (1000 * 60 * 60 * 24));
        if (diffDays < 0) return `${Math.abs(diffDays)} days ago`;
        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return 'Tomorrow';
        return `in ${diffDays} days`;
    }

    function createAcceptedCard(post, skills) {
        const providerName = post.Provider_Name || 'Unassigned Provider';
        const providerPicture = post.Provider_Picture
            ? `${window.BASE_URL}/file/user-files/${post.Provider_Picture}`
            : null;
        const rating = post.Provider_Rating ? parseFloat(post.Provider_Rating).toFixed(1) : '0.0';
        const category = post.Category_Name || 'N/A';
        const estDate = formatEstDate(post.Est_Date);
        const budget = `LKR ${post.Requesting_Price || 0}/= (${post.Price_Type || 'Fixed'})`;
        const description = post.Description || '';
        const snippet = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);
        const postedDate = formatDate(post.Created_At);
        const postTypeLabel = post.Post_Type === 'direct' ? 'Direct' : 'Bid';

        const avatarImg = providerPicture
            ? `<img src="${providerPicture}" alt="${escapeHtml(providerName)}" class="accepted-avatar" onerror="this.remove()">`
            : '';

        return `
            <div class="search-item accepted-card">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                <div class="accepted-header">
                    <div class="accepted-provider-section">
                        <div class="accepted-avatar-wrapper">
                            <i class="fas fa-user"></i>
                            ${avatarImg}
                        </div>
                        <div class="accepted-provider-info">
                            <div class="accepted-provider-name">${escapeHtml(providerName)}</div>
                            <div class="accepted-provider-rating">⭐ ${rating}</div>
                        </div>
                    </div>
                    <div class="accepted-actions">
                        <a href="${window.BASE_URL}/messages?new=${post.Provider_ID}" class="action-btn btn-edit" aria-label="Messages">
                            <i class="fas fa-comments"></i> Messages
                        </a>
                        <button class="action-btn btn-edit" onclick="payPayment(${post.Post_ID})">
                            <i class="fas fa-credit-card"></i> Pay
                        </button>
                        <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="action-btn btn-delete" onclick="cancelRequest(${post.Post_ID})">
                            <i class="fas fa-trash"></i> Cancel
                        </button>
                    </div>
                </div>
                <div class="accepted-title">${escapeHtml(post.Title)}</div>
                <div class="accepted-description">${snippet}</div>
                <div class="accepted-details">
                    <div class="accepted-detail-item">
                        <span class="accepted-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                        <span class="accepted-detail-value">${budget}</span>
                    </div>
                    <div class="accepted-detail-item">
                        <span class="accepted-detail-label"><i class="fa-solid fa-tag"></i> Category</span>
                        <span class="accepted-detail-value">${escapeHtml(category)}</span>
                    </div>
                    <div class="accepted-detail-item">
                        <span class="accepted-detail-label"><i class="fa-solid fa-calendar"></i> Est. Date</span>
                        <span class="accepted-detail-value">${estDate}</span>
                    </div>
                </div>
                <div class="accepted-footer">
                    <span class="accepted-time">${postedDate}</span>
                    <span class="status-chip status-accepted">${postTypeLabel} Request</span>
                </div>
            </div>
        `;
    }

    function createOngoingCard(post, skills) {
        const providerName    = post.Provider_Name || 'Unassigned Provider';
        const providerPicture = post.Provider_Picture
            ? `${window.BASE_URL}/file/user-files/${post.Provider_Picture}`
            : null;
        const rating      = post.Provider_Rating ? parseFloat(post.Provider_Rating).toFixed(1) : '0.0';
        const category    = post.Category_Name || 'N/A';
        const progress    = parseInt(post.Progress) || 0;
        const estDate     = formatEstDate(post.Est_Date);
        const startedDate = post.Started_At ? formatDate(post.Started_At) : formatDate(post.Created_At);
        const budget      = `LKR ${post.Requesting_Price || 0}/= (${post.Price_Type || 'Fixed'})`;
        const description = post.Description || '';
        const snippet     = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);
        const postTypeLabel = post.Post_Type === 'direct' ? 'Direct' : 'Bid';
        const barColor = progress >= 75 ? '#008500' : progress >= 40 ? '#f59e0b' : '#3b82f6';

        const avatarImg = providerPicture
            ? `<img src="${providerPicture}" alt="${escapeHtml(providerName)}" class="ongoing-avatar" onerror="this.remove()">`
            : '';

        return `
            <div class="search-item ongoing-card">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                <div class="ongoing-header">
                    <div class="ongoing-provider-section">
                        <div class="ongoing-avatar-wrapper">
                            <i class="fas fa-user"></i>
                            ${avatarImg}
                        </div>
                        <div class="ongoing-provider-info">
                            <div class="ongoing-provider-name">${escapeHtml(providerName)}</div>
                            <div class="ongoing-provider-rating">&#11088; ${rating}</div>
                        </div>
                    </div>
                    <div class="ongoing-actions">
                        <a href="${window.BASE_URL}/messages?new=${post.Provider_ID}" class="action-btn btn-edit" aria-label="Messages">
                            <i class="fas fa-comments"></i> Messages
                        </a>
                        <button class="action-btn btn-view" onclick="ProjectDetailView.open(${post.Post_ID}, { role: 'client' })">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="action-btn btn-edit" onclick="updateRequest(${post.Post_ID})">
                            <i class="fas fa-file-alt"></i> Requirements
                        </button>
                        <button class="action-btn btn-delete" onclick="cancelOngoingProject(${post.Post_ID})">
                            <i class="fas fa-trash"></i> Cancel
                        </button>
                    </div>
                </div>
                <div class="ongoing-title">${escapeHtml(post.Title)}</div>
                <div class="ongoing-description">${snippet}</div>
                <div class="ongoing-details">
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                        <span class="ongoing-detail-value">${budget}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-tag"></i> Category</span>
                        <span class="ongoing-detail-value">${escapeHtml(category)}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-calendar"></i> Est. Date</span>
                        <span class="ongoing-detail-value">${estDate}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-chart-line"></i> Progress</span>
                        <span class="ongoing-detail-value" style="color:${barColor}; font-weight:700;">${progress}%</span>
                    </div>
                </div>
                <div class="progress-container" style="margin-top:12px;">
                    <div class="progress-track">
                        <div class="progress-fill" style="width:${progress}%; background:${barColor};"></div>
                    </div>
                </div>
                <div class="ongoing-footer">
                    <span class="ongoing-time">${startedDate}</span>
                    <span class="status-chip status-ongoing">${postTypeLabel} &middot; In Progress</span>
                </div>
            </div>
        `;
    }

    function createPendingReviewCard(post, skills) {
        const providerName    = post.Provider_Name || 'Unassigned Provider';
        const providerPicture = post.Provider_Picture
            ? `${window.BASE_URL}/file/user-files/${post.Provider_Picture}`
            : null;
        const rating      = post.Provider_Rating ? parseFloat(post.Provider_Rating).toFixed(1) : '0.0';
        const category    = post.Category_Name || 'N/A';
        const progress    = parseInt(post.Progress) || 0;
        const estDate     = formatEstDate(post.Est_Date);
        const submittedAt = post.Ended_At ? formatDate(post.Ended_At) : formatDate(post.Created_At);
        const budget      = `LKR ${post.Requesting_Price || 0}/= (${post.Price_Type || 'Fixed'})`;
        const description = post.Description || '';
        const snippet     = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);
        const postTypeLabel = post.Post_Type === 'direct' ? 'Direct' : 'Bid';

        const avatarImg = providerPicture
            ? `<img src="${providerPicture}" alt="${escapeHtml(providerName)}" class="ongoing-avatar" onerror="this.remove()">`
            : '';

        return `
            <div class="search-item ongoing-card">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                <div class="ongoing-header">
                    <div class="ongoing-provider-section">
                        <div class="ongoing-avatar-wrapper">
                            <i class="fas fa-user"></i>
                            ${avatarImg}
                        </div>
                        <div class="ongoing-provider-info">
                            <div class="ongoing-provider-name">${escapeHtml(providerName)}</div>
                            <div class="ongoing-provider-rating">&#11088; ${rating}</div>
                        </div>
                    </div>
                    <div class="ongoing-actions">
                        <a href="${window.BASE_URL}/messages?new=${post.Provider_ID}" class="action-btn btn-edit" aria-label="Messages">
                            <i class="fas fa-comments"></i> Messages
                        </a>
                        <button class="action-btn btn-view" onclick="ProjectDetailView.open(${post.Post_ID}, { role: 'client' })">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="action-btn btn-edit" onclick="completePendingReviewProject(${post.Post_ID})">
                            <i class="fas fa-circle-check"></i> Complete
                        </button>
                        <button class="action-btn btn-delete" onclick="openReopenProjectModal(${post.Post_ID})">
                            <i class="fas fa-rotate-left"></i> Request Changes
                        </button>
                    </div>
                </div>
                <div class="ongoing-title">${escapeHtml(post.Title)}</div>
                <div class="ongoing-description">${snippet}</div>
                <div class="ongoing-details">
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                        <span class="ongoing-detail-value">${budget}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-tag"></i> Category</span>
                        <span class="ongoing-detail-value">${escapeHtml(category)}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-calendar"></i> Est. Date</span>
                        <span class="ongoing-detail-value">${estDate}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-chart-line"></i> Progress</span>
                        <span class="ongoing-detail-value" style="color:#f59e0b; font-weight:700;">${progress}%</span>
                    </div>
                </div>
                <div class="progress-container" style="margin-top:12px;">
                    <div class="progress-track">
                        <div class="progress-fill" style="width:${progress}%; background:#f59e0b;"></div>
                    </div>
                </div>
                <div class="ongoing-footer">
                    <span class="ongoing-time">Submitted ${submittedAt}</span>
                    <span class="status-chip" style="background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;">${postTypeLabel} &middot; Pending Review</span>
                </div>
            </div>
        `;
    }

    function createCompletedCard(post, skills) {
        const providerName    = post.Provider_Name || 'Unassigned Provider';
        const providerPicture = post.Provider_Picture
            ? `${window.BASE_URL}/file/user-files/${post.Provider_Picture}`
            : null;
        const rating      = post.Provider_Rating ? parseFloat(post.Provider_Rating).toFixed(1) : '0.0';
        const category    = post.Category_Name || 'N/A';
        const completedAt = post.Ended_At ? formatDate(post.Ended_At) : formatDate(post.Created_At);
        const startedAt   = post.Started_At ? formatDate(post.Started_At) : '—';
        const budget      = `LKR ${post.Requesting_Price || 0}/= (${post.Price_Type || 'Fixed'})`;
        const description = post.Description || '';
        const snippet     = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);
        const postTypeLabel = post.Post_Type === 'direct' ? 'Direct' : 'Bid';

        const avatarImg = providerPicture
            ? `<img src="${providerPicture}" alt="${escapeHtml(providerName)}" class="ongoing-avatar" onerror="this.remove()">`
            : '';

        // Build reviews HTML
        let reviewsHTML = '';
        if (post.reviews && post.reviews.length) {
            const reviewCards = post.reviews.map(r => {
                const starsHTML = renderStarsHTML(r.Rating || 0);
                const reviewer = r.Rated_By === 'Client' ? 'Your Review' : 'Provider\'s Review';
                const rDate = r.Left_At ? new Date(r.Left_At).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';
                const filesHTML = r.files && r.files.length
                    ? `<div class="review-card-files">
                        ${r.files.map(f => {
                            const name = f.split('/').pop();
                            const url  = `${window.BASE_URL}/file/review-files/${encodeURIComponent(f)}`;
                            return `<a href="${url}" target="_blank" class="review-file-chip" title="${escapeHtml(name)}">
                                <i class="fa-solid fa-paperclip"></i> ${escapeHtml(name)}
                            </a>`;
                        }).join('')}
                    </div>`
                    : '';
                return `
                    <div class="completed-review-card ${r.Rated_By === 'Client' ? 'review-own' : 'review-provider'}">
                        <div class="review-card-header">
                            <span class="review-card-label">${escapeHtml(reviewer)}</span>
                            <span class="review-card-date">${escapeHtml(rDate)}</span>
                        </div>
                        <div class="review-card-stars">${starsHTML}</div>
                        ${r.Title ? `<div class="review-card-title">${escapeHtml(r.Title)}</div>` : ''}
                        ${r.Description ? `<div class="review-card-desc">${escapeHtml(r.Description)}</div>` : ''}
                        ${filesHTML}
                    </div>`;
            }).join('');

            reviewsHTML = `
                <div class="completed-reviews-section">
                    <div class="completed-reviews-title"><i class="fa-solid fa-star"></i> Reviews</div>
                    ${reviewCards}
                </div>`;
        }

        return `
            <div class="search-item ongoing-card">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                <div class="ongoing-header">
                    <div class="ongoing-provider-section">
                        <div class="ongoing-avatar-wrapper">
                            <i class="fas fa-user"></i>
                            ${avatarImg}
                        </div>
                        <div class="ongoing-provider-info">
                            <div class="ongoing-provider-name">${escapeHtml(providerName)}</div>
                            <div class="ongoing-provider-rating">&#11088; ${rating}</div>
                        </div>
                    </div>
                    <div class="ongoing-actions">
                        <a href="${window.BASE_URL}/messages?new=${post.Provider_ID}" class="action-btn btn-edit" aria-label="Messages">
                            <i class="fas fa-comments"></i> Messages
                        </a>
                        <button class="action-btn btn-view" onclick="ProjectDetailView.open(${post.Post_ID}, { role: 'client' })">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                </div>
                <div class="ongoing-title">${escapeHtml(post.Title)}</div>
                <div class="ongoing-description">${snippet}</div>
                <div class="ongoing-details">
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                        <span class="ongoing-detail-value">${budget}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-tag"></i> Category</span>
                        <span class="ongoing-detail-value">${escapeHtml(category)}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-calendar"></i> Started</span>
                        <span class="ongoing-detail-value">${startedAt}</span>
                    </div>
                    <div class="ongoing-detail-item">
                        <span class="ongoing-detail-label"><i class="fa-solid fa-circle-check"></i> Completed</span>
                        <span class="ongoing-detail-value">${completedAt}</span>
                    </div>
                </div>
                <div class="progress-container" style="margin-top:12px;">
                    <div class="progress-track">
                        <div class="progress-fill" style="width:100%; background:#16a34a;"></div>
                    </div>
                </div>
                ${reviewsHTML}
                <div class="ongoing-footer">
                    <span class="ongoing-time">Completed ${completedAt}</span>
                    <span class="status-chip" style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;">${postTypeLabel} &middot; Completed</span>
                </div>
            </div>
        `;
    }

    function renderStarsHTML(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            html += i <= rating
                ? '<i class="fa-solid fa-star star-filled"></i>'
                : '<i class="fa-regular fa-star star-empty"></i>';
        }
        return html;
    }

    function updateAsExpired(id) {
        fetch(`<?= BASE_URL ?>/requests/update-expired/${id}`, {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Post marked as expired successfully');
                } else {
                    console.log('Error', data.message || 'Failed to mark post as expired');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';

        const date = new Date(dateString);
        const now = new Date();

        // Reset time parts to compare only dates
        const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const nowOnly = new Date(now.getFullYear(), now.getMonth(), now.getDate());

        // Calculate difference in days
        const diffTime = nowOnly - dateOnly;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

        console.log('Date comparison:', {
            input: dateString,
            parsed: date.toISOString(),
            now: now.toISOString(),
            dateOnly: dateOnly.toISOString(),
            nowOnly: nowOnly.toISOString(),
            diffDays: diffDays
        });

        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return 'Yesterday';
        if (diffDays < 7) return `${diffDays} days ago`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;

        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function calculateDaysLeft(endDate) {
        if (!endDate) return 'N/A';

        const end = new Date(endDate);
        const now = new Date();
        const diffTime = end - now;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays < 0) return 'Expired';
        if (diffDays === 0) return 'Expires today';
        if (diffDays === 1) return '1 day left';
        return `${diffDays} days left`;
    }

    

        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-buttons .buttons');
            const sections = document.querySelectorAll('.requests-section');

            function showSection(status) {
                // Remove active class from all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                const activeBtn = document.querySelector(`.tab-buttons .buttons[data-target="${status}"]`);
                if (activeBtn) activeBtn.classList.add('active');

                // Hide all sections
                sections.forEach(section => section.style.display = 'none');

                // Show selected section
                const activeSection = document.querySelector(`.${status}`);
                if (activeSection) activeSection.style.display = 'block';

                // Load posts
                loadPosts(status);
            }

            // Add click listeners
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const status = this.dataset.target;
                    showSection(status);
                });
            });

            // Initial load
            showSection('pending');
        });

    function viewPost(id) {
        viewDialogBox('view-post-popup');

        // Show loading state
        const formContainer = document.querySelector("#view-post-popup .dialog-body");
        formContainer.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading post details...</p>
            </div>
        `;

        fetch("<?= BASE_URL ?>/requests/view/" + id)
            .then(response => response.json())
            .then(post => {
                // Add delay to make loading animation visible
                return new Promise(resolve => setTimeout(() => resolve(post), 300));
            })
            .then(post => {
                if (post.error) {
                    console.error('Error fetching post:', post.error);
                    formContainer.innerHTML = `
                        <div class="error-state">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Failed to load post details</p>
                            <button onclick="viewPost(${id})" class="retry-btn">Retry</button>
                        </div>
                    `;
                    return;
                }
                const providerName = post.Provider_Name || 'Unassigned provider';
                console.log('Fetched post details:', post);
                // Format date
                const publishDate = post.Published_At ?
                    new Date(post.Published_At).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : 'N/A';

                // Build skills HTML
                const skillsHTML = post.skills && post.skills.length > 0
                    ? post.skills.map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('')
                    : '<span>No skills specified</span>';

                // Replace form content with a div wrapper for proper styling
                formContainer.innerHTML = `
                <div class="post-view">
                    <div class="post-view-title">${post.Title || 'Untitled'}</div>
                    <div class="post-view-meta">
                        <span class="chip"><i class="fa-solid fa-calendar"></i><span>${publishDate}</span></span>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Description</div>
                        <div class="section-body">${post.Description || 'No description provided'}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Provider</div>
                        <div class="post-provider">${escapeHtml(providerName)}</div>
                    </div> 
                    <div class="post-view-section">
                        <div class="section-title">Required Skills</div>
                        <div class="skills-row">${skillsHTML}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Details</div>
                        <div class="kv-grid">
                            <div class="kv-item"><span class="kv-label">Budget:</span><span class="kv-value">LKR ${post.Requesting_Price || '0'}/= (${post.Price_Type || 'N/A'})</span></div>
                            <div class="kv-item"><span class="kv-label">Level:</span><span class="kv-value">${post.Level || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Duration:</span><span class="kv-value">${post.Duration || 'N/A'} ${post.Duration_Type || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Proposals:</span><span class="kv-value">${post.Proposal_Count || '0'}</span></div>
                        </div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Engagement</div>
                        <div class="engagement-row">
                            <span class="chip"><i class="fa-solid fa-eye"></i> ${post.Views || '0'} views</span>
                        </div>
                    </div>
                </div>
            `;
            })
            .catch(error => {
                console.error('Error fetching post:', error);
                formContainer.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Failed to load post details. Please try again.</p>
                        <button onclick="viewPost(${id})" class="retry-btn">Retry</button>
                    </div>
                `;
            });
    }

    function cancelRequest(id) {
        viewDialogBox('confirm-cancel');
        const confirmCancelBtn = document.getElementById('confirmCancelBtn');

        // Remove previous event listeners to avoid multiple triggers
        const newconfirmCancelBtn = confirmCancelBtn.cloneNode(true);
        confirmCancelBtn.parentNode.replaceChild(newconfirmCancelBtn, confirmCancelBtn);

        newconfirmCancelBtn.addEventListener('click', function () {
            fetch("<?= BASE_URL ?>/requests/cancel/" + id, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDialogBox('confirm-cancel');
                        window.showSuccessToast("Success!", "Request cancelled successfully");

                        // Remove from DOM with animation
                        const postElement = document.querySelector(`.search-item input[value="${id}"]`)?.closest('.search-item');
                        if (postElement) {
                            postElement.style.transition = 'all 0.3s ease';
                            postElement.style.opacity = '0';
                            postElement.style.transform = 'translateX(-20px)';

                            setTimeout(() => {
                                postElement.remove();

                                // Check if section is now empty
                                const activeSection = document.querySelector('.requests-section:not([style*="display: none"])');
                                const itemList = activeSection?.querySelector('.item-list');
                                const remainingPosts = itemList?.querySelectorAll('.search-item');

                                if (remainingPosts && remainingPosts.length === 0) {
                                    const status = activeSection.classList.contains('pending') ? 'pending' :
                                        activeSection.classList.contains('accepted') ? 'accepted' : 'ongoing';
                                    showEmptyState(status, itemList);
                                }
                            }, 300);
                        }
                    } else {
                        window.showErrorToast("Error", data.error || 'Failed to cancel post');
                    }
                })
                .catch(error => {
                    console.log('cancelRequest error:', error);
                    console.error('Error:', error);
                    showErrorToast("Error", 'An error occurred while cancelling the request. Please try again.');
                });
        });
    }

    function payPayment(id) {
        viewDialogBox('confirm-payment');

        const formContainer = document.querySelector("#confirm-payment .dialog-body");
        formContainer.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading post details...</p>
            </div>
        `;

        fetch("<?= BASE_URL ?>/requests/view/" + id)
            .then(response => response.json())
            .then(post => {
                // Add delay to make loading animation visible
                return new Promise(resolve => setTimeout(() => resolve(post), 300));
            })
            .then(post => {
                if (post.error) {
                    console.error('Error fetching post:', post.error);
                    formContainer.innerHTML = `
                        <div class="error-state">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Failed to load post details</p>
                            <button onclick="payPayment(${id})" class="retry-btn">Retry</button>
                        </div>
                    `;
                    return;
                }
                const providerName = post.Provider_Name || 'Unassigned provider';
                // Format date
               
                // Build skills HTML
                const skillsHTML = post.skills && post.skills.length > 0
                    ? post.skills.map(skill => `<span class="skill-tag">${skill}</span>`).join('')
                    : '<span>No skills specified</span>';

                // Replace form content with a div wrapper for proper styling
                formContainer.innerHTML = `
                <div class="post-view">
                    <div class="post-view-title">${post.Title || 'Untitled'}</div>
                    <div class="post-view-section">
                        <div class="section-title">Description</div>
                        <div class="section-body">${post.Description || 'No description provided'}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Provider</div>
                        <div class="post-provider">${providerName}</div>
                    </div> 
                    <div class="post-view-section">
                        <div class="section-title">Required Skills</div>
                        <div class="skills-row">${skillsHTML}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Details</div>
                        <div class="kv-grid">
                            <div class="kv-item"><span class="kv-label">Budget:</span><span class="kv-value">LKR ${post.Requesting_Price || '0'}/= (${post.Price_Type || 'N/A'})</span></div>
                            <div class="kv-item"><span class="kv-label">Level:</span><span class="kv-value">${post.Level || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Duration:</span><span class="kv-value">${post.Duration || 'N/A'} ${post.Duration_Type || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Proposals:</span><span class="kv-value">${post.Proposal_Count || '0'}</span></div>
                        </div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Engagement</div>
                        <div class="engagement-row">
                            <span class="chip"><i class="fa-solid fa-eye"></i> ${post.Views || '0'} views</span>
                        </div>
                    </div>
                </div>
            `;
            })
            // document.getElementById('confirmPaymentBtn').addEventListener('click', function () {
                
            //     closeDialogBox('confirm-payment');
            //     window.showSuccessToast("Payment Initiated", "You will be redirected to the payment gateway.");
            //     console.log(`Redirecting to payment for Post ID: ${id}`);
        
            .catch(error => {
                console.error('Error fetching post:', error);
                formContainer.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Failed to load post details. Please try again.</p>
                        <button onclick="viewPost(${id})" class="retry-btn">Retry</button>
                    </div>
                `;
            });

        const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');

        // Remove previous event listeners to avoid multiple triggers
        const newconfirmPaymentBtn = confirmPaymentBtn.cloneNode(true);
        confirmPaymentBtn.parentNode.replaceChild(newconfirmPaymentBtn, confirmPaymentBtn);

        newconfirmPaymentBtn.addEventListener('click', function () {
            fetch("<?= BASE_URL ?>/requests/payment/" + id, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDialogBox('confirm-payment');
                        window.showSuccessToast("Success!", "Payment initiated successfully");

                        // Remove from DOM with animation
                        const postElement = document.querySelector(`.search-item input[value="${id}"]`)?.closest('.search-item');
                        if (postElement) {
                            postElement.style.transition = 'all 0.3s ease';
                            postElement.style.opacity = '0';
                            postElement.style.transform = 'translateX(-20px)';

                            setTimeout(() => {
                                postElement.remove();

                                // Check if section is now empty
                                const activeSection = document.querySelector('.requests-section:not([style*="display: none"])');
                                const itemList = activeSection?.querySelector('.item-list');
                                const remainingPosts = itemList?.querySelectorAll('.search-item');

                                if (remainingPosts && remainingPosts.length === 0) {
                                    const status = activeSection.classList.contains('pending') ? 'pending' :
                                        activeSection.classList.contains('accepted') ? 'accepted' : 'ongoing';
                                    showEmptyState(status, itemList);
                                }
                            }, 300);
                        }
                    } else {
                        window.showErrorToast("Error", data.error || 'Failed to initiate payment');
                    }
                })
                .catch(error => {
                    console.log('cancelRequest error:', error);
                    console.error('Error:', error);
                    showErrorToast("Error", 'An error occurred while cancelling the request. Please try again.');
                });
        });
    }

    function updateRequest(postId) {
        window._reqCurrentProjectId = null;
        window._reqCurrentPostId    = postId;

        // Reset to list tab and show dialog
        reqSwitchTab('req-list-pane');
        viewDialogBox('update-requirements-popup');

        // Reset add-form
        const form = document.getElementById('req-add-form');
        if (form) form.reset();

        // Wire up the submit button (replace listener to avoid duplicates)
        const submitBtn = document.getElementById('btnSubmitReq');
        const newSubmitBtn = submitBtn.cloneNode(true);
        submitBtn.parentNode.replaceChild(newSubmitBtn, submitBtn);
        newSubmitBtn.addEventListener('click', () => submitNewRequirement());

        // Wire tab clicks
        document.querySelectorAll('.req-tab').forEach(btn => {
            const clone = btn.cloneNode(true);
            btn.parentNode.replaceChild(clone, btn);
            clone.addEventListener('click', () => reqSwitchTab(clone.dataset.tab));
        });

        // Load the requirements list
        reqLoadRequirements(postId);
    }

    function reqSwitchTab(tabId) {
        document.querySelectorAll('.req-tab').forEach(t => {
            t.classList.toggle('active', t.dataset.tab === tabId);
        });
        document.querySelectorAll('.req-pane').forEach(p => {
            p.style.display = p.id === tabId ? 'block' : 'none';
        });
    }

    function reqLoadRequirements(postId) {
        const loading = document.getElementById('req-loading');
        const body    = document.getElementById('req-list-body');
        if (loading) loading.style.display = '';
        if (body)    body.style.display    = 'none';

        fetch(`${window.BASE_URL}/project/getrequirements/${postId}`)
            .then(r => r.json())
            .then(json => {
                if (!json.success) throw new Error(json.error || 'Failed to load');

                const { project_id, requirements } = json.data;
                window._reqCurrentProjectId = project_id;

                if (loading) loading.style.display = 'none';
                if (body) {
                    body.innerHTML = reqBuildListHTML(requirements);
                    body.style.display = '';
                }
            })
            .catch(err => {
                if (loading) loading.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Failed to load requirements.</p>
                        <button onclick="reqLoadRequirements(${postId})" class="retry-btn">Retry</button>
                    </div>`;
            });
    }

    function reqBuildListHTML(requirements) {
        if (!requirements || requirements.length === 0) {
            return `<div class="req-empty">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <p>No requirements yet. Use the <strong>Add New</strong> tab to submit one.</p>
                    </div>`;
        }

        return requirements.map(req => {
            const statusMap = {
                pending:  { cls: 'req-status-pending',  icon: 'fa-clock',        label: 'Pending' },
                approved: { cls: 'req-status-approved', icon: 'fa-circle-check', label: 'Approved' },
                rejected: { cls: 'req-status-rejected', icon: 'fa-circle-xmark', label: 'Rejected' },
            };
            const s = statusMap[req.Status] || statusMap['pending'];

            const createdDate = req.Created_At
                ? new Date(req.Created_At).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' })
                : 'N/A';

            let dateLine = `<span class="req-date-item"><i class="fa-solid fa-calendar-plus"></i> Requested: ${createdDate}</span>`;
            if (req.Status === 'approved' && req.Approved_At) {
                const d = new Date(req.Approved_At).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
                dateLine += `<span class="req-date-item req-date-approved"><i class="fa-solid fa-calendar-check"></i> Approved: ${d}</span>`;
            }
            if (req.Status === 'rejected' && req.Rejected_At) {
                const d = new Date(req.Rejected_At).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
                dateLine += `<span class="req-date-item req-date-rejected"><i class="fa-solid fa-calendar-xmark"></i> Rejected: ${d}</span>`;
            }

            const filesHTML = req.files && req.files.length > 0
                ? `<div class="req-files-block">
                       <div class="req-files-label"><i class="fa-solid fa-paperclip"></i> Attachments</div>
                       <div class="req-files">
                           ${req.files.map(f => {
                               const name = f.split('/').pop();
                               const url  = `${window.BASE_URL}/file/project-requirements/${f}`;
                               return `<a href="${url}" target="_blank" class="req-file-chip" title="${escapeHtml(name)}">
                                           <i class="fa-solid fa-file"></i> ${escapeHtml(name)}
                                       </a>`;
                           }).join('')}
                       </div>
                   </div>`
                : '';

            const rejectionReasonHTML = (req.Status === 'rejected' && req.Rejection_Reason)
                ? `<div class="req-rejection-banner">
                       <i class="fa-solid fa-comment-slash"></i>
                       <div><strong>Rejection Reason:</strong> ${escapeHtml(req.Rejection_Reason)}</div>
                   </div>`
                : '';

            return `
                <div class="req-card req-card-${req.Status || 'pending'}">
                    <div class="req-card-stripe"></div>
                    <div class="req-card-body">
                        <div class="req-card-header">
                            <div class="req-card-title">${escapeHtml(req.Requirement_Title || 'Untitled')}</div>
                            <span class="req-status-chip ${s.cls}">
                                <i class="fa-solid ${s.icon}"></i> ${s.label}
                            </span>
                        </div>
                        ${req.Requirement_Description
                            ? `<div class="req-card-desc">${escapeHtml(req.Requirement_Description)}</div>`
                            : ''}
                        ${filesHTML}
                        ${rejectionReasonHTML}
                    </div>
                    <div class="req-card-footer">
                        <div class="req-card-dates">${dateLine}</div>
                    </div>
                </div>`;
        }).join('');
    }

    async function submitNewRequirement() {
        const projectId   = window._reqCurrentProjectId;
        const title       = document.getElementById('reqTitle')?.value.trim()       || '';
        const description = document.getElementById('reqDescription')?.value.trim() || '';
        const filesInput  = document.getElementById('reqFiles');

        if (!projectId) {
            window.showErrorToast('Error', 'Project not loaded. Please reopen the dialog.');
            return;
        }
        if (!title) {
            document.getElementById('reqTitle')?.focus();
            window.showErrorToast('Error', 'Please enter a requirement title.');
            return;
        }

        const btn = document.getElementById('btnSubmitReq');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…'; }

        const fd = new FormData();
        fd.append('project_id',   projectId);
        fd.append('title',        title);
        fd.append('description',  description);
        if (filesInput && filesInput.files.length) {
            Array.from(filesInput.files).forEach(f => fd.append('req_files[]', f));
        }

        try {
            const res  = await fetch(`${window.BASE_URL}/project/add-requirement`, { method: 'POST', body: fd });
            const data = await res.json();

            if (!data.success) {
                window.showErrorToast('Error', data.error || 'Failed to submit requirement.');
                return;
            }

    if (projectId < 0) {
        window.showErrorToast("Error", "No project selected. Please refresh and try again.");
        return;
    }

    fetch(window.BASE_URL + "/project/submit-requirements-update", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            project_id: projectId,
            new_requirement: text
        })
    })
    .then(res => res.text())
    .then(text => {
        console.log("RAW RESPONSE:", text);
        return JSON.parse(text);
    })
    .then(data => {
        if (data.success) {
            window.showSuccessToast("Success!", "Requirement added successfully.");
            document.getElementById("newRequirement").value = "";
            closeDialogBox('update-requirements-popup');
        } else {
            window.showErrorToast("Error", "Failed to add requirement");
        }
    })
    .catch(err => {
        console.error("Error:", err);
        window.showErrorToast("Error", "Error adding requirement: " + err.message);
    });
}


    function cancelOngoingProject(id) {
    viewDialogBox('cancel-ongoing-project');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');

    // Remove previous event listeners
    const newConfirmCancelBtn = confirmCancelBtn.cloneNode(true);
    confirmCancelBtn.parentNode.replaceChild(newConfirmCancelBtn, confirmCancelBtn);

    newConfirmCancelBtn.addEventListener('click', function () {
        fetch("<?= BASE_URL ?>/project/cancel/" + id, {
            method: 'POST'
        })
        /*.then(res => res.text()) // 👈 temporarily change
        .then(data => {
            console.log(data); // 🔥 see actual response
        })*/
        .then(response => response.json())
        .then(data => {
            console.log('cancelProject response:', data);
            if (data.success) {
                closeDialogBox('cancel-ongoing-project');
                window.showSuccessToast("Success!", "Cancellation request sent to provider.");

                // Remove from DOM
                const projectElement = document.querySelector(`.search-item input[value="${id}"]`)?.closest('.search-item');
                if (projectElement) {
                    projectElement.style.transition = 'all 0.3s ease';
                    projectElement.style.opacity = '0';
                    projectElement.style.transform = 'translateX(-20px)';

                    setTimeout(() => {
                        projectElement.remove();
                        const activeSection = document.querySelector('.ongoing');
                        const itemList = activeSection?.querySelector('.item-list');
                        const remainingProjects = itemList?.querySelectorAll('.search-item');

                        if (remainingProjects && remainingProjects.length === 0) {
                            showEmptyState('ongoing', itemList);
                        }
                    }, 300);
                }
            } else {
                window.showErrorToast("Error", data.error || 'Failed to send cancellation request');
            }
        })
        .catch(error => {
            console.log('cancelOngoingProject error:', error);
            window.showErrorToast("Error", 'An error occurred while sending the cancellation request.');
        });
    });
}

function submitReview(id) {
    //window.currentReviewPostId = postId;

    viewDialogBox('review-popup');

    const container = document.querySelector("#review-popup .dialog-body");

    container.innerHTML = `
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading project...</p>
        </div>
    `;

    fetch(window.BASE_URL + "/requests/view/" + id)
        .then(res => {
            console.log('view status:', res.status, res.url);
            return res.text(); // text first, not json
        })
        .then(text => {
            console.log('view raw response:', text); // see what's actually returned
            return JSON.parse(text); // then parse manually
        })
        .then(post => {
            window.currentProjectId = post.Project_ID; // Store project ID for later use
            console.log('currentProjectId set to:', window.currentProjectId);
            const providerName = post.Provider_Name || 'Unassigned provider';
            container.innerHTML = `
                <div class="post-view">
                    <div class="post-view-title">${post.Title || 'Untitled'}</div>
                    <div class="post-view-section">
                        <div class="section-title">Description</div>
                        <div class="section-body">${post.Description || 'No description provided'}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Provider</div>
                        <div class="post-provider">${providerName}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Category</div>
                        <div class="post-category">${post.CategoryName || 'No category specified'}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Rating(1-5)</div>
                        <div class="post-rating"><select id="reviewRating" class="text-field">
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Good</option>
                        <option value="3">3 - Average</option>
                        <option value="2">2 - Poor</option>
                        <option value="1">1 - Very Bad</option>
                    </select></div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Add Comments</div>
                        <textarea class="text-field" id="reviewComments" placeholder="Enter your comments..."></textarea>
                    </div>
                    
                </div>
                
            `;
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = `<p>Error loading project</p>`;
        });
}

document.addEventListener("click", function (e) {
    if (e.target.closest("#saveReviewBtn")) {
        addReviewComments();
    }
});

function addReviewComments() {
    const text = document.getElementById("reviewComments").value;
    const projectId = window.currentProjectId;
    console.log("Adding review comments:", { projectId, text });
    if (!text.trim()) {
        window.showErrorToast("Error", "Please enter review comments");
        return;
    }

    if (projectId < 0) {
        window.showErrorToast("Error", "No project selected. Please refresh and try again.");
        return;
    }

    fetch(window.BASE_URL + "/project/submit-review-comments", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            project_id: projectId,
            review_comments: text
        })
    })
    .then(res => res.text())
    .then(text => {
        console.log("RAW RESPONSE:", text);
        return JSON.parse(text);
    })
    .then(data => {
        console.log("submitReviewComments response:", data);
        if (data.success) {
            window.showSuccessToast("Success!", "Review comments added successfully.");
            document.getElementById("reviewComments").value = "";
            closeDialogBox('review-popup');
        } else {
            window.showErrorToast("Error", "Failed to add review comments");
        }
    })
    .catch(err => {
        console.error("Error:", err);
        window.showErrorToast("Error", "Error adding review comments: " + err.message);
    });
}

    function Complete(id) {
        fetch(window.BASE_URL + "/project/complete/" + id, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: id })
        })
            
        
        .then(response => response.text())
        .then(text => {
            console.log('Complete response:', text);
            return JSON.parse(text); // return raw text for now
        });
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         window.showSuccessToast("Success!", "Project marked as completed.");
        //         // Optionally, you can remove the project from the list or update its status in the UI here
        //     } else {
        //         window.showErrorToast("Error", data.error || 'Failed to complete project');
        //     }
        // })
        // .catch(error => {
        //     console.log('Complete error:', error);
        //     window.showErrorToast("Error", 'An error occurred while completing the project.');
        // });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Setup search input handler with debouncing
        document.getElementById('searchInput').addEventListener('change', handleSearch);
        document.getElementById('searchButton').addEventListener('click', handleSearch);

        function handleSearch() {
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Set new timeout to debounce search (wait 300ms after user stops typing)
            searchTimeout = setTimeout(() => {
                currentSearch = document.getElementById('searchInput').value.trim();

                // Reload posts with search filter
                const activeSection = document.querySelector('.requests-section:not([style*="display: none"])');
                const status = activeSection.classList.contains('pending') ? 'pending' :
                        activeSection.classList.contains('accepted') ? 'accepted' : 
                        activeSection.classList.contains('ongoing') ? 'ongoing' : 
                        activeSection.classList.contains('completed') ? 'completed' : pending-review;
                loadPosts(status);
            }, 300);
        }

        // Setup sort dropdown handler
        const sortOptions = document.getElementById('sortOptions');
        if (sortOptions) {
            sortOptions.addEventListener('click', function (e) {
                const option = e.target.closest('[data-sort]');
                if (option) {
                    const sortValue = option.dataset.sort;
                    const sortText = option.textContent;
                    const providerName = post.Provider_Name || 'Unassigned provider';
                    // Update dropdown display
                    document.getElementById('sortDropdown').value = sortText;

                    // Update global sort variable
                    currentSort = sortValue;                                        

                    // Reload posts with new sort
                    const activeSection = document.querySelector('.requests-section:not([style*="display: none"])');
                    const status = activeSection.classList.contains('pending') ? 'pending' :
                        activeSection.classList.contains('accepted') ? 'accepted' : 'ongoing';
                    loadPosts(status);
                }
            });
        }});

        loadPosts('pending');

    const tabButtons = {
            'pending': document.getElementById('pending'),
            'accepted': document.getElementById('accepted'),
            'ongoing': document.getElementById('ongoing'),
            'pending-review': document.getElementById('pending-review'),
            'completed': document.getElementById('completed')
        };

        const sections = {
            'pending': document.querySelector('.pending'),
            'accepted': document.querySelector('.accepted'),
            'ongoing': document.querySelector('.ongoing'),
            'pending-review': document.querySelector('.pending-review'),
            'completed': document.querySelector('.completed')     
        };

        /*Object.keys(tabButtons).forEach(key => {
            tabButtons[key].addEventListener('click', function () {
                // Remove active class from all buttons
                Object.values(tabButtons).forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Hide all sections
                Object.values(sections).forEach(section => section.style.display = 'none');

                // Show selected section and load posts
                const status = key.replace('-posts', '');
                sections[key].style.display = 'block';
                loadPosts(status);
            });
        });*/

</script>


</html>



