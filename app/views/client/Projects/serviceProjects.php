
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clientPosts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
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
                <div id="completed" class="buttons" data-target="completed">Completed Projects</div>
                <div id="pending-review" class="buttons" data-target="pending-review">Pending Review</div>
            </div>
        </div>
        <div class="request-content">
            <div class="search-header">

                <div class="search-button">
                    <input type="text" id="searchInput" placeholder="Search my service requests...">
                    <button id="searchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <button class="filter" id="filter-pop-up"><i class="fa-solid fa-filter"
                        onclick="window.showSuccessToast('Test','Test Message')"></i><span>filter</span></button>

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

    function loadPosts(status = 'pending') {
        const container = getListContainer(status);
        if (!container) return;

        container.innerHTML = `
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading posts...</p>
        </div>
    `;
        clearLoadMoreButton(status);

        console.log('Loading posts for status:', status);

        // CHANGE THIS LINE - add /list to the URL
        fetch(`<?= BASE_URL ?>/projects/list?status=${status}&sort=${currentSort}&search=${encodeURIComponent(currentSearch)}`)
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
                    postsState[status].posts = data.posts;
                    postsState[status].visibleCount = Math.min(PAGE_SIZE, data.posts.length);
                    renderPosts(status);
                    return;
                }

                postsState[status].posts = [];
                postsState[status].visibleCount = 0;
                showEmptyState(status, container);
            })
            .catch(error => {
                console.error('Error loading posts:', error);
                container.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Failed to load posts. Please try again.</p>
                    <p style="font-size: 12px; color: #999;">${error.message}</p>
                    <button onclick="loadPosts('${status}')" class="retry-btn">Retry</button>
                </div>
            `;
                clearLoadMoreButton(status);
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
            const postHTML = createPostCard(item.post, item.skills, status);
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
        const postTypeLabel = post.post_type === 'direct' ? 'Direct Request' : 'Bid Request';
        const postTypeHTML = `
            <div class="post-type-badge">
                <span class="badge-label ${post.post_type === 'direct' ? 'direct' : 'bid'}">${postTypeLabel}</span>
            </div>
            
        `;

        let progressHTML = '';
        if (status === 'ongoing') {
            const progress = post.Progress || 0;
            const hoursWorked = Math.round((progress / 100) * 80);
            progressHTML = `<div class="progress-container" aria-label="Project progress">
                <div class="progress-label">Progress: <span class="progress-percent">${progress}%</span> <span class="progress-detail" style="color:#64748b;">(${hoursWorked}h of 80h)</span></div>
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
                // Format date
                const publishDate = post.Published_At ?
                    new Date(post.Published_At).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : 'N/A';

                // Build skills HTML
                const skillsHTML = post.skills && post.skills.length > 0
                    ? post.skills.map(skill => `<span class="skill-tag">${skill}</span>`).join('')
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
            document.getElementById('confirmPaymentBtn').addEventListener('click', function () {
                
                closeDialogBox('confirm-payment');
                window.showSuccessToast("Payment Initiated", "You will be redirected to the payment gateway.");
                console.log(`Redirecting to payment for Post ID: ${id}`);
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

    function updateRequest(id, mode = 'edit') {
        viewDialogBox('create-post-popup');

        fetch("<?= BASE_URL ?>/requests/view/" + id)
            .then(response => response.json())
            .then(post => {
                if (post.error) {
                    console.error('Error fetching post:', post.error);
                    return;
                }

                const root = document.getElementById("create-post-popup");
                root.querySelector(".title").innerText = "Update Request";
                root.querySelectorAll(".label").forEach(label => {
                    label.classList.add("label-float");
                });
                root.querySelector("#field-skill-label").classList.remove("label-float");

                //root.querySelector("input[name='title']").value = post.Title || '';
                //root.querySelector("textarea[name='description']").value = post.Description || '';
                //root.querySelector("input[name='category']").value = post.Category_Name || '';
                //document.getElementById("Category_ID").value = post.Category_ID || '';

                //root.querySelector("input[name='price']").value = post.Requesting_Price || '';
                //root.querySelector("input[name='pricetype']").value = post.Price_Type || '';
                //root.querySelector("input[name='duration']").value = post.Duration || '';
                //root.querySelector("input[name='durationtype']").value = post.Duration_Type || '';
                //root.querySelector("input[name='level']").value = post.Level || '';

                const endAtInput = root.querySelector("input[name='endat']");
                if (post.End_At) {
                    // End_At might be "2025-12-31 00:00:00" or "2025-12-31"
                    let dateValue = post.End_At.split(' ')[0]; // Get just the date part "2025-12-31"

                    // Verify it's a valid date and format is correct
                    const dateObj = new Date(dateValue);
                    if (!isNaN(dateObj.getTime())) {
                        // Format as YYYY-MM-DD
                        const year = dateObj.getFullYear();
                        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const day = String(dateObj.getDate()).padStart(2, '0');
                        dateValue = `${year}-${month}-${day}`;

                        console.log("Setting end date to:", dateValue);
                        endAtInput.value = dateValue;
                    } else {
                        console.error("Invalid date:", post.End_At);
                        endAtInput.value = '';
                    }
                } else {
                    endAtInput.value = '';
                }

                // Use selectCategory to load skills for the category
                if (post.Category_ID) {
                    const categoryOption = document.querySelector(`.search-select-container [data-id="${post.Category_ID}"]`);
                    if (categoryOption) {
                        // Call selectCategory with skipReset = true to not clear existing chips yet
                        selectCategory({ target: categoryOption }, true);
                    }
                }

                // Add skills after loading category skills
                setTimeout(() => {
                    const skillsChips = root.querySelector("#SkillsChips");
                    skillsChips.innerHTML = '<input type="hidden" id="Skills" name="skills"><p>No skill selected</p>';

                    if (post.skills && post.skills.length > 0) {
                        post.skills.forEach(skill => {
                            const skillOptions = document.getElementById("SkillsOptionList").querySelectorAll('[data-id]');
                            const matchingOption = Array.from(skillOptions).find(opt => opt.textContent.trim() === skill);

                            if (matchingOption) {
                                addChip('SkillsChips', skill, matchingOption.dataset.id);
                            }
                        });
                    }
                }, 500);

                // Change buttons
                const saveDraftBtn = root.querySelector('[data-role="save-draft"]');
                const publishBtn = root.querySelector('[data-role="publish"]');
                const savePostBtn = root.querySelector('[data-role="save-post"]');

                if (mode === 'repost') {
                    // Repost flow: force date to today and use confirmation before publishing.
                    root.querySelector("input[name='endat']").value = getTodayDateString();

                    if (saveDraftBtn) saveDraftBtn.style.display = 'none';
                    if (publishBtn) publishBtn.style.display = 'none';
                    if (savePostBtn) {
                        savePostBtn.style.display = '';
                        savePostBtn.removeAttribute('data-post-id');
                        savePostBtn.innerHTML = '<i class="fa-solid fa-rocket"></i> Repost Request';

                        const newSaveBtn = savePostBtn.cloneNode(true);
                        savePostBtn.parentNode.replaceChild(newSaveBtn, savePostBtn);
                        newSaveBtn.onclick = function () {
                            openPublishConfirmWithAction(function () {
                                submitPost('publish');
                            });
                        };
                    }
                } else {
                    if (saveDraftBtn) saveDraftBtn.style.display = 'none';
                    if (publishBtn) publishBtn.style.display = 'none';
                    if (savePostBtn) {
                        savePostBtn.style.display = '';
                        savePostBtn.setAttribute('data-post-id', post.Post_ID);
                        savePostBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Request';

                        // Remove old event listeners and add new one
                        const newSaveBtn = savePostBtn.cloneNode(true);
                        savePostBtn.parentNode.replaceChild(newSaveBtn, savePostBtn);
                        newSaveBtn.onclick = saveEditedPost;
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching post:', error);
                alert('Failed to load post details. Please try again.');
            });
    }

    function cancelOngoingProject(id) {
    viewDialogBox('cancel-ongoing-project');
    const confirmCancelBtn = document.getElementById('cancel-ongoing-project');

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

    /*document.addEventListener('DOMContentLoaded', function () {
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
                        activeSection.classList.contains('accepted') ? 'accepted' : 'ongoing';
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

                    // Update dropdown display
                    document.getElementById('sortDropdown').value = sortText;

                    // Update global sort variable
                    currentSort = sortValue;                    const providerName = post.Provider_Name || 'Unassigned provider';                    const providerName = post.Provider_Name || 'Unassigned provider';

                    // Reload posts with new sort
                    const activeSection = document.querySelector('.requests-section:not([style*="display: none"])');
                    const status = activeSection.classList.contains('pending') ? 'pending' :
                        activeSection.classList.contains('accepted') ? 'accepted' : 'ongoing';
                    loadPosts(status);
                }
            });
        }*/

</script>


</html>



