
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
                <div id="pending-review" class="buttons" data-target="pending-review">Pending Review</div>
                <div id="completed-jobs" class="buttons" data-target="completed-jobs">Completed</div>
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

             <!-- PENDING REVIEW SECTION -->
             <div class="pending-review requests-section" style="display: none;">
                <div class="item-list">
                </div>
            </div>

             <!-- COMPLETED JOBS SECTION -->
             <div class="completed-jobs requests-section" style="display: none;">
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
        'completed-jobs': { posts: [], visibleCount: 0 }
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
                <h2>No ongoing requests</h2>
                <p>Your ongoing requests will appear here.</p>
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
        const skillsHTML = skills && skills.length > 0
            ? skills.map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('')
            : '<span class="no-skills">---No skills specified---</span>';

        const description = post.Description || '';
        const snippet = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);

        const providerName = post.Provider_Name || 'Unassigned provider';

        const publishedDate = formatDate(post.Published_At || post.Created_At);
        const daysLeft = calculateDaysLeft(post.End_At);
        if (daysLeft == 'Expired') {
            updateAsExpired(post.Post_ID);
        }

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
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-edit" onclick="repostExpired(${post.Post_ID})">
                    <i class="fas fa-redo"></i> Repost
                </button>
                <button class="action-btn btn-delete" onclick="cancelRequest(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Cancel
                </button>
            `;
        }

        // Different date label based on status
        let dateLabel = '';
        if (status === 'draft') {
            dateLabel = `Created ${publishedDate}`;
        } else if (status === 'expired') {
            dateLabel = `Expired ${publishedDate}`;
        } else {
            dateLabel = `Published ${publishedDate}`;
        }

        // Different footer based on status
        let footerHTML = '';
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
        }

        return `
            <div class="search-item">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                <div class="post-header">
                    <div class="post-meta">
                        <div class="post-date">
                            <i class="fas fa-calendar"></i>
                            <span>${dateLabel}</span>
                        </div>
                    </div>
                    <div class="post-actions">
                        ${actionsHTML}
                    </div>
                </div>
                <h3 class="post-title">${escapeHtml(post.Title)}</h3>
                <div class="post-description">${snippet}</div>
                <div class="post-provider">
                    <i class="fas fa-user"></i>
                    <span>${escapeHtml(providerName)}</span>
                </div>

                <div class="post-skills">
                    <span class="skills-label">Required Skills:</span>
                    <div class="skills-tags">${skillsHTML}</div>
                </div>
                ${footerHTML}
                ${engagementHTML}
            </div>
        `;
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
            'completed-jobs': document.getElementById('completed-jobs')
        };

        const sections = {
            'pending': document.querySelector('.pending'),
            'accepted': document.querySelector('.accepted'),
            'ongoing': document.querySelector('.ongoing'),
            'pending-review': document.querySelector('.pending-review'),
            'completed-jobs': document.querySelector('.completed-jobs')     
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



