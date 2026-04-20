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
    <script src="<?= BASE_URL ?>/assets/js/clientPosts.js" defer></script>
    <title>My Service Requests</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <div class="header-top" style="display:flex; justify-content: space-between; align-items: center;">
                <h1>My Service Requests</h1>

                <button type="button" class="post-job-btn" onclick="openCreateForm('create-post-popup')"><i
                        class="fa-solid fa-plus"></i>
                    Create Request</button>
            </div>

        </div>
        <div class="container-changer">
            <div class="tab-buttons">
                <div id="active-posts" class="buttons active">Active Requests</div>
                <div id="draft-posts" class="buttons">Draft Requests</div>
                <div id="expired-posts" class="buttons">Expired Requests</div>
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
            <!-- ACTIVE REQUESTS SECTION -->
            <div class="active-posts active requests-section">
                <div class="item-list">
                </div>
            </div>

            <!-- DRAFT REQUESTS SECTION -->
            <div class="draft-posts requests-section draft-section" style="display: none;">
                <div class="item-list">
                </div>
            </div>

            <!-- EXPIRED REQUESTS SECTION -->
            <div class="expired-posts requests-section expired-section" style="display: none;">
                <div class="item-list">
                </div>
            </div>
        </div>
    </section>

</body>


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
                <div class="text-container">
                    <div class="label text-label">Title</div>
                    <input type="text" class="text-field" name="title" id="">
                </div>
            </div>
            <div class="input-grid-1">
                <div class="text-container">
                    <div class="label text-label">Description</div>
                    <textarea class="text-field" spellcheck="false" name="description"></textarea>
                </div>
            </div>
            <div class="input-grid-1">
                <div class="search-select-container" data-idinput="Category_ID">
                    <div class="text-container">
                        <div class="label search-dropdown-label">Service Category</div>
                        <input type="text" class="text-field-search-dropdown" name="category" id="Category"
                            autocomplete="off" onkeydown="return false">
                    </div>
                    <div class="options">
                        <span class="text-container">
                            <input type="text" class="text-field-search">
                        </span>
                        <div class="option-list" onclick="selectCategory(event)">
                            <?php foreach ($categories as $Category): ?>
                                <div data-id="<?= $Category['Category_ID'] ?>">
                                    <?= htmlspecialchars($Category['Name']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="Category_ID" name="categoryid">
            </div>
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
            <div class="input-grid-1">
                <div class="text-container">
                    <div class="label text-label">Requesting Price</div>
                    <input type="text" class="text-field" name="price" id="">
                </div>
            </div>

            <div class="input-grid-1">
                <div class="search-select-container">
                    <div class="text-container">
                        <div class="label search-dropdown-label">Level</div>
                        <input type="text" class="text-field-search-dropdown" autocomplete="off"
                            onkeydown="return false" name="level" id="">
                    </div>
                    <div class="options">
                        <div class="option-list">
                            <div>Beginner</div>
                            <div>Intermediate</div>
                            <div>Advanced</div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="input-grid-2">
                <div class="text-container">
                    <div class="label text-label label-float">Estimated Date</div>
                    <input type="date" class="text-field" name="estdate" id="">
                </div>
                <div class="text-container">
                    <div class="label text-label label-float"> Post Expired Date</div>
                    <input type="date" class="text-field" name="endat" id="">
                </div>
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

<div class="dialog-box-2" id="confirm-publish">
    <div class="dialog-content" style="width: 400px;">
        <div class="dialog-title">
            <div class="title">Confirm Publish</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('confirm-publish')"></i>
            </div>
        </div>
        <div class="pop-up-content">
            Are you sure you want to publish this request? It will become visible for providers to bid.
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-delete" id="confirmPublishKeep"
                onclick="closeDialogBox('confirm-publish')">Cancel</button>
            <button class="action-btn btn-edit" id="confirmPublishBtn" onclick="submitPost('publish')"><i
                    class="fa-solid fa-rocket"></i>
                Publish
            </button>
        </div>

    </div>

</div>

<div class="dialog-box-2" id="confirm-delete">
    <div class="dialog-content" style="width: 400px;">
        <div class="dialog-title">
            <div class="title">Confirm Delete</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('confirm-delete')"></i>
            </div>
        </div>
        <div class="pop-up-content">
            Are you sure you want to delete this post? This action cannot be undone.
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-view" id="confirmKeep"
                onclick="closeDialogBox('confirm-delete')">Keep</button>
            <button class="action-btn btn-delete" id="confirmDeleteBtn"><i class="fa-solid fa-circle-xmark"></i> Yes,
                Delete</button>
        </div>

    </div>

</div>

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

<script>
    // Global variable to track current sort
    let currentSort = 'date_desc';
    let currentSearch = '';
    let searchTimeout = null;
    const PAGE_SIZE = 5;
    const postsState = {
        active: { posts: [], visibleCount: 0 },
        draft: { posts: [], visibleCount: 0 },
        expired: { posts: [], visibleCount: 0 }
    };

    function getListContainer(status) {
        return document.querySelector(`.${status}-posts .item-list`);
    }

    function clearLoadMoreButton(status) {
        const section = document.querySelector(`.${status}-posts`);
        if (!section) return;
        const existing = section.querySelector('.load-more-wrap');
        if (existing) existing.remove();
    }

    function renderLoadMoreButton(status) {
        const section = document.querySelector(`.${status}-posts`);
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

    /**
     * Load posts via AJAX
     */
    function loadPosts(status = 'active') {
        const container = getListContainer(status);
        if (!container) return;

        container.innerHTML = `
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading posts...</p>
        </div>
    `;
        clearLoadMoreButton(status);

        console.log('Loading posts for status:', status, 'with sort:', currentSort, 'search:', currentSearch);

        // CHANGE THIS LINE - add /list to the URL
        fetch(`<?= BASE_URL ?>/requests/list?status=${status}&sort=${currentSort}&search=${encodeURIComponent(currentSearch)}`)
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

    /**
     * Create post card HTML
     */
    function createPostCard(post, skills, status = 'active') {
        const skillsHTML = skills && skills.length > 0
            ? skills.map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('')
            : '<span class="no-skills">---No skills specified---</span>';

        const description = post.Description || '';
        const snippet = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);

        const publishedDate = formatDate(post.Published_At || post.Created_At);
        const daysLeft = calculateDaysLeft(post.End_At);
        if (daysLeft == 'Expired') {
            updateAsExpired(post.Post_ID);
        }

        // Different buttons based on status
        let actionsHTML = '';
        if (status === 'active') {
            actionsHTML = `
                <button class="action-btn btn-edit" onclick="editPost(${post.Post_ID})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-delete" onclick="deletePost(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            `;
        } else if (status === 'draft') {
            actionsHTML = `
                <button class="action-btn btn-edit" onclick="editPost(${post.Post_ID})">
                    <i class="fas fa-edit"></i> Continue
                </button>
                <button class="action-btn btn-view" onclick="publishDraft(${post.Post_ID})">
                    <i class="fas fa-rocket"></i> Publish
                </button>
                <button class="action-btn btn-delete" onclick="deletePost(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            `;
        } else if (status === 'expired') {
            actionsHTML = `
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID})">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-edit" onclick="repostExpired(${post.Post_ID})">
                    <i class="fas fa-redo"></i> Repost
                </button>
                <button class="action-btn btn-delete" onclick="deletePost(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Delete
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
                            <span class="detail-value budget-amount">LKR ${post.Requesting_Price || 0}/=</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Final Proposals</span>
                            <span class="detail-value proposals-count">${post.Proposal_Count || post.ProposalsCount || 0}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estimated Date</span>
                            <span class="detail-value project-duration">${post.Est_Date || 'N/A'}</span>
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
                            <span class="detail-value budget-amount">LKR ${post.Requesting_Price || 0}/=</span>
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
                            <span class="detail-label">Estimated Date</span>
                            <span class="detail-value project-duration">${post.Est_Date || 'N/A'}</span>
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
                <div class="post-skills">
                    <span class="skills-label">Required Skills:</span>
                    <div class="skills-tags">${skillsHTML}</div>
                </div>
                ${footerHTML}
                ${engagementHTML}
            </div>
        `;
    }

    /**
     * Show empty state
     */
    function renderNoResultsState() {
        const searchTerm = (currentSearch || '').trim();
        const safeSearchTerm = escapeHtml(searchTerm);

        return `
            <section class="empty-state search-no-results">
                <i class="fa-solid fa-magnifying-glass"></i>
                <h3>No Search Item Found</h3>
                <p>Try a different keyword or clear the search.</p>
                ${safeSearchTerm ? `<div class="search-term-hint">Search: "${safeSearchTerm}"</div>` : ''}
            </section>
        `;
    }

    function showEmptyState(status, container) {
        if ((currentSearch || '').trim() !== '') {
            container.innerHTML = renderNoResultsState();
            return;
        }

        let message = '';
        let icon = 'fa-inbox';

        if (status === 'active') {
            message = `
                <h2>No active requests right now</h2>
                <p>Click "Create New Request" to post your first service request.</p>
            `;
        } else if (status === 'draft') {
            message = `
                <h2>No draft requests right now</h2>
                <p>Create drafts to save your job requests and publish them later.</p>
            `;
        } else if (status === 'expired') {
            message = `
                <h2>No expired requests</h2>
                <p>Your expired requests will appear here.</p>
            `;
        }

        container.innerHTML = `
            <section class="empty-state">
                <i class="fas ${icon}"></i>
                ${message}
            </section>
        `;
    }

    /**
     * Helper function to escape HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    /**
     * Helper function to format date
     */
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

    /**
     * Helper function to calculate days left
     */
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

    function selectCategory(e, skipReset = false) {
        // same behavior as registration, but robustly read the clicked option
        const opt = e?.target?.closest('[data-id]');
        if (opt) {
            document.getElementById("Category_ID").value = opt.dataset.id;
        }
        const CategoryID = document.getElementById("Category_ID").value;
        // reset skills dropdown UI
        if (!skipReset) {
            const currentChips = document.getElementById('SkillsChips').querySelectorAll(".chip");
            for (let j = 0; j < currentChips.length; j++) {
                removeChip(currentChips[j]);
            }
        }
        document.getElementById("SkillAddInput").value = "";
        document.getElementById("SkillsOptionList").innerHTML = "";
        document.getElementById("SkillAddInput").parentElement
            .querySelector(".label").classList.remove("label-float");

        // absolute URL (like reg page but for requests endpoint)
        fetch("<?= BASE_URL ?>/requests/get-skills", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "category_id=" + encodeURIComponent(CategoryID)
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {
                    data.result.forEach(element => {
                        addItemToDropdown("SkillAddInput", element.Skill, false, element.Skill_ID);
                    });
                } else {
                    console.log(data);
                }
            })
            .catch(err => console.error(err));
    }

    function addSkill() {
        if (addChip('SkillsChips', document.getElementById("SkillAddInput").value, document.getElementById("SkillAddInput").dataset.id)) {
            document.getElementById("SkillAddInput").value = "";
            document.getElementById("SkillAddInput").parentElement
                .querySelector(".label").classList.remove("label-float");
        } else {
            document.getElementById("SkillAddInput").focus();
        }
    }
    // expose to inline onclick


    function daysConvert(days) {

        if (days >= 365) {
            const years = Math.floor(days / 365);
            return years + (years === 1 ? " year" : " years");
        }
        else if (days >= 30) {
            const months = Math.floor(days / 30);
            return months + (months === 1 ? " month" : " months");
        }
        else if (days >= 7) {
            const weeks = Math.floor(days / 7);
            return weeks + (weeks === 1 ? " week" : " weeks");
        } else {
            return days + (days === 1 ? " day" : " days");
        }
    }

    function validateEmpty(inputField) {
        if (!inputField || inputField.value.trim() === '') {
            showValidationTooltip(inputField, "This field is required");
            return false;
        }
        return true;
    }


    function submitPost(action) {

        // Validate required fields
        const title = document.querySelector("input[name='title']");
        const description = document.querySelector("textarea[name='description']");
        const categoryId = document.getElementById("Category_ID");
        const skillsString = document.getElementById("Skills").value;
        const skillIds = skillsString.match(/\d+/g)?.map(id => parseInt(id, 10)) || [];
        const skillIdsString = skillIds.join(','); // "1,5,8,12"

        // console.log("Skills IDs String:", skillIdsString);
        const price = document.querySelector("input[name='price']");
        const est_date = document.querySelector("input[name='estdate']");
        const level = document.querySelector("input[name='level']");
        const end_at = document.querySelector("input[name='endat']");

        // Check if any field is empty

        const form = document.getElementById('create-post-form');
        const inputs = form.querySelectorAll('input[type="text"], textarea, input[type="date"]');
        let isEmpty = false;
        for (let i = 0; i < inputs.length; i++) {
            const element = inputs[i];
            if (element.value.trim() === '' && element.id !== 'SkillAddInput' && element.classList.contains('text-field-search') === false && element.name !== 'skills') {
                showValidationTooltip(element, "This field is required");
                console.log("Empty field:", element);
                isEmpty = true;
            }
        }

        if (isEmpty) {
            closeDialogBox('confirm-publish');
            console.log("Form has empty fields");
            return;
        }

        console.log("All required fields are filled.");

        if (price.value.trim() !== '' && (isNaN(price.value.trim()) || price.value.trim() < 0)) {
            showValidationTooltip(price, "Please enter a valid number");
            console.log("Invalid price:", price.value);
            closeDialogBox('confirm-publish');
            return;
        }

        if (isDateBeforeToday(est_date.value)) {
            showValidationTooltip(est_date, "Estimated date must be today or a future date");
            closeDialogBox('confirm-publish');
            return;
        }

        if (isDateBeforeToday(end_at.value)) {
            showValidationTooltip(end_at, "Post expired date must be today or a future date");
            closeDialogBox('confirm-publish');
            return;
        }

        // Collect all form data manually
        const postData = {
            title: title.value.trim() || '',
            description: description.value.trim() || '',
            category_id: categoryId.value,
            skills: skillIdsString,
            price: price.value.trim() || '',
            est_date: est_date.value || '',
            level: level.value.trim() || '',
            end_at: end_at.value || '',
            status: action // 'draft' or 'publish'
        };

        // Convert to URL-encoded format
        const urlEncodedData = Object.keys(postData)
            .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(postData[key]))
            .join('&');

        const url = "<?= BASE_URL ?>/requests/create";
        console.log("Calling URL:", url);
        console.log("POST data:", postData);
        // Send AJAX request
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: urlEncodedData
        })
            .then(response => {
                console.log("Response status:", response.status);
                console.log("Response headers:", response.headers.get('content-type'));

                // Get the raw text first to see what's being returned
                return response.text().then(text => {
                    console.log("Raw response:", text);

                    // Try to parse as JSON
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error("Failed to parse JSON:", e);
                        throw new Error("Server returned invalid JSON: " + text.substring(0, 200));
                    }
                });
            })
            .then(data => {
                console.log("Parsed data:", data);
                if (data.success) {
                    closeDialogBox('confirm-publish');
                    closeDialogBox('create-post-popup');

                    window.showSuccessToast('Post Saved', action === 'draft' ? 'Draft saved successfully!' : 'Post published successfully!');

                    // Reload the appropriate section
                    const status = action === 'draft' ? 'draft' : 'active';
                    setTimeout(() => {
                        loadPosts(status);

                        // Switch to the correct tab if not already there
                        const targetButton = document.getElementById(`${status}-posts`);
                        if (targetButton && !targetButton.classList.contains('active')) {
                            targetButton.click();
                        }
                    }, 500);

                    // Reset form
                    document.getElementById('create-post-form').reset();
                    inputReset('create-post-form');
                } else {
                    window.showErrorToast("Error", data.error || 'An unknown error occurred while saving the post.');
                    closeDialogBox('confirm-publish');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast("Error", 'An error occurred while saving the post. Please try again.');
                closeDialogBox('confirm-publish');
            });
    }

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

                const bids = Array.isArray(post.bids) ? post.bids : [];
                const proposalCount = bids.length > 0
                    ? bids.length
                    : Number(post.Proposal_Count || post.ProposalsCount || 0);
                const providerBidsHTML = bids.length > 0
                    ? bids.map((bid) => {
                        const fullName = `${bid.First_Name || ''} ${bid.Last_Name || ''}`.trim() || 'Unknown Provider';
                        const initials = fullName.split(' ').map((n) => n.charAt(0)).join('').substring(0, 2).toUpperCase();
                        const ratingSource = bid.Provider_Star_Rating ?? bid.Provider_Rating ?? bid.Provider_Score ?? 0;
                        const ratingRaw = Number(ratingSource);
                        const normalizedRating = Number.isNaN(ratingRaw)
                            ? 0
                            : (ratingRaw > 5 ? ratingRaw / 20 : ratingRaw);
                        const rating = Math.max(0, Math.min(5, normalizedRating)).toFixed(1);
                        const imagePath = bid.Profile_Picture ? `<?= BASE_URL ?>/file/user-files/${bid.Profile_Picture}` : '';
                        const safeComment = bid.Comment ? bid.Comment : 'No comment provided';
                        const bidAmount = bid.Amount ? Number(bid.Amount).toLocaleString() : '0';
                        const rawDuration = bid.Duration;
                        let bidDuration = 'N/A';
                        let bidEstimatedDate = 'N/A';
                        if (rawDuration !== null && rawDuration !== undefined && rawDuration !== '') {
                            const durationNum = Number(rawDuration);
                            if (!Number.isNaN(durationNum)) {
                                const totalHours = Math.max(0, Math.floor(durationNum));
                                const hoursPerDay = 24;
                                const daysPerWeek = 7;
                                const daysPerMonth = 30;
                                const hoursPerWeek = hoursPerDay * daysPerWeek;
                                const hoursPerMonth = hoursPerDay * daysPerMonth;

                                // Compare duration against current time and compute estimated completion date.
                                const now = new Date();
                                const etaDate = new Date(now.getTime() + (totalHours * 60 * 60 * 1000));
                                bidEstimatedDate = etaDate.toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                });

                                if (totalHours > hoursPerMonth) {
                                    const months = Math.floor(totalHours / hoursPerMonth);
                                    const remainingHours = totalHours % hoursPerMonth;
                                    const remainingDays = Math.floor(remainingHours / hoursPerDay);
                                    bidDuration = `${months} month${months === 1 ? '' : 's'}`;
                                    if (remainingDays > 0) {
                                        bidDuration += ` ${remainingDays} day${remainingDays === 1 ? '' : 's'}`;
                                    }
                                } else if (totalHours > hoursPerWeek) {
                                    const totalDays = Math.floor(totalHours / hoursPerDay);
                                    const weeks = Math.floor(totalDays / daysPerWeek);
                                    const remainingDays = totalDays % daysPerWeek;
                                    bidDuration = `${weeks} week${weeks === 1 ? '' : 's'}`;
                                    if (remainingDays > 0) {
                                        bidDuration += ` ${remainingDays} day${remainingDays === 1 ? '' : 's'}`;
                                    }
                                } else if (totalHours > hoursPerDay) {
                                    const days = Math.floor(totalHours / hoursPerDay);
                                    const remainingHours = totalHours % hoursPerDay;
                                    bidDuration = `${days} day${days === 1 ? '' : 's'}`;
                                    if (remainingHours > 0) {
                                        bidDuration += ` ${remainingHours} hr${remainingHours === 1 ? '' : 's'}`;
                                    }
                                } else {
                                    bidDuration = `${totalHours} hr${totalHours === 1 ? '' : 's'}`;
                                }
                            } else if (/[a-zA-Z]/.test(String(rawDuration))) {
                                bidDuration = String(rawDuration);
                            }
                        }

                        return `
                            <article class="provider-bid-card">
                                <div class="provider-bid-top">
                                    ${imagePath
                                        ? `<img class="provider-avatar-img" src="${imagePath}" alt="">`
                                        : `<div class="provider-avatar">${initials}</div>`
                                    }
                                    <div class="provider-meta">
                                        <h4>${fullName}</h4>
                                        <p><i class="fa-solid fa-star"></i> ${rating}</p>
                                    </div>
                                    <span class="provider-bid-price">LKR ${bidAmount}</span>
                                </div>
                                <div class="provider-bid-bottom">
                                    <span><i class="fa-solid fa-calendar-days"></i> Est: ${bidEstimatedDate}</span>
                                    <span><i class="fa-solid fa-clock"></i> Duration: ${bidDuration}</span>
                                    <span><i class="fa-solid fa-message"></i> ${safeComment}</span>
                                </div>
                                <div class="provider-bid-actions">
                                    <button class="action-btn btn-edit provider-request-btn" data-provider-id="${bid.Provider_ID}" onclick="sendRequestToProvider(${post.Post_ID}, ${bid.Provider_ID}, this)">Send Request</button>
                                </div>
                            </article>
                        `;
                    }).join('')
                    : '<div class="provider-bid-empty">No bids received for this post yet.</div>';

                // Replace form content with a richer interactive layout that keeps the current theme.
                formContainer.innerHTML = `
                <div class="post-view">
                    <div class="post-view-title">${post.Title || 'Untitled'}</div>
                    <div class="post-view-meta">
                        <span class="chip"><i class="fa-solid fa-calendar"></i><span>${publishDate}</span></span>
                        <span class="chip"><i class="fa-solid fa-list-check"></i> ${proposalCount} proposals</span>
                        <span class="chip"><i class="fa-solid fa-eye"></i> ${post.Views || '0'} views</span>
                    </div>

                    <div class="post-quick-stats">
                        <div class="post-quick-stat"><span class="label">Budget</span><span class="value">LKR ${post.Requesting_Price || '0'}/=</span></div>
                        <div class="post-quick-stat"><span class="label">Level</span><span class="value">${post.Level || 'N/A'}</span></div>
                        <div class="post-quick-stat"><span class="label">Estimated Date</span><span class="value">${post.Est_Date || 'N/A'}</span></div>
                    </div>

                    <div class="post-view-tabs">
                        <button type="button" class="post-view-tab active" data-pane="overview"><i class="fa-solid fa-circle-info"></i> Overview</button>
                        <button type="button" class="post-view-tab" data-pane="bids"><i class="fa-solid fa-user-group"></i> Bids</button>
                        <button type="button" class="post-view-tab" data-pane="engagement"><i class="fa-solid fa-chart-line"></i> Engagement</button>
                    </div>

                    <div class="post-view-pane active" data-pane="overview">
                        <div class="post-view-section">
                            <div class="section-title">Description</div>
                            <div class="section-body">${post.Description || 'No description provided'}</div>
                        </div>
                        <div class="post-view-section">
                            <div class="section-title">Required Skills</div>
                            <div class="skills-row">${skillsHTML}</div>
                        </div>
                        <div class="post-view-section">
                            <div class="section-title">Details</div>
                            <div class="kv-grid">
                                <div class="kv-item"><span class="kv-label">Budget:</span><span class="kv-value">LKR ${post.Requesting_Price || '0'}/=</span></div>
                                <div class="kv-item"><span class="kv-label">Level:</span><span class="kv-value">${post.Level || 'N/A'}</span></div>
                                <div class="kv-item"><span class="kv-label">Estimated Date:</span><span class="kv-value">${post.Est_Date || 'N/A'}</span></div>
                                <div class="kv-item"><span class="kv-label">Proposals:</span><span class="kv-value">${proposalCount}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="post-view-pane" data-pane="bids">
                        <div class="post-view-section">
                            <div class="section-title">Bidded Providers</div>
                            <div class="request-status-note" id="requestStatusNote"></div>
                            <div class="bidded-providers-grid">
                                ${providerBidsHTML}
                            </div>
                        </div>
                    </div>

                    <div class="post-view-pane" data-pane="engagement">
                        <div class="post-view-section">
                            <div class="section-title">Engagement</div>
                            <div class="engagement-row">
                                <span class="chip"><i class="fa-solid fa-eye"></i> ${post.Views || '0'} views</span>
                                <span class="chip"><i class="fa-solid fa-user-group"></i> ${proposalCount} proposals</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                const tabButtons = formContainer.querySelectorAll('.post-view-tab');
                const panes = formContainer.querySelectorAll('.post-view-pane');
                tabButtons.forEach((tabButton) => {
                    tabButton.addEventListener('click', function () {
                        const targetPane = tabButton.dataset.pane;
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        panes.forEach(pane => pane.classList.remove('active'));
                        tabButton.classList.add('active');
                        const target = formContainer.querySelector(`.post-view-pane[data-pane="${targetPane}"]`);
                        if (target) {
                            target.classList.add('active');
                        }
                    });
                });

                const requestStatus = (post.Request_Status || '').toLowerCase();
                const postStatus = (post.Post_Status || '').toLowerCase();
                const canSendRequest = postStatus === 'active' && (requestStatus === '' || requestStatus === 'declined');
                const statusNote = document.getElementById('requestStatusNote');
                const requestButtons = formContainer.querySelectorAll('.provider-request-btn');

                if (statusNote) {
                    if (canSendRequest) {
                        statusNote.textContent = 'You can send a request to one provider.';
                    } else if (requestStatus === 'ongoing' || requestStatus === 'accepted') {
                        statusNote.textContent = `Requests are locked because current status is ${requestStatus}.`;
                    } else {
                        statusNote.textContent = 'Requests are available only for active posts.';
                    }
                }

                requestButtons.forEach((btn) => {
                    if (!canSendRequest) {
                        btn.dataset.locked = '1';
                        btn.setAttribute('aria-disabled', 'true');
                        btn.style.opacity = '0.65';
                        btn.style.cursor = 'not-allowed';
                        btn.classList.remove('btn-edit');
                        btn.classList.add('btn-view');
                    }

                    if ((requestStatus === 'ongoing' || requestStatus === 'accepted') && post.Provider_ID && Number(btn.dataset.providerId) === Number(post.Provider_ID)) {
                        btn.dataset.locked = '1';
                        btn.setAttribute('aria-disabled', 'true');
                        btn.style.opacity = '0.65';
                        btn.style.cursor = 'not-allowed';
                        btn.classList.remove('btn-edit');
                        btn.classList.add('btn-view');
                    }
                });
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

    function sendRequestToProvider(postId, providerId, button) {
        if (!postId || !providerId) {
            window.showErrorToast('Error', 'Invalid post/provider data');
            return;
        }

        if (button && button.dataset && button.dataset.locked === '1') {
            window.showWarningToast('Already Selected', 'Already selected a bid for this post');
            return;
        }

        button.disabled = true;
        const originalText = button.textContent;
        button.textContent = 'Sending...';

        fetch(`<?= BASE_URL ?>/requests/send-request/${postId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `provider_id=${encodeURIComponent(providerId)}`
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    button.disabled = false;
                    button.textContent = originalText;
                    window.showErrorToast('Request Not Sent', data.message || 'Unable to send request');
                    return;
                }

                window.showSuccessToast('Request Sent', 'Provider request status is now ongoing.');

                const allButtons = document.querySelectorAll('.provider-request-btn');
                allButtons.forEach((btn) => {
                    btn.dataset.locked = '1';
                    btn.setAttribute('aria-disabled', 'true');
                    btn.style.opacity = '0.65';
                    btn.style.cursor = 'not-allowed';
                    btn.classList.remove('btn-edit');
                    btn.classList.add('btn-view');
                });

                const statusNote = document.getElementById('requestStatusNote');
                if (statusNote) {
                    statusNote.textContent = 'Request status is ongoing. You cannot send another request right now.';
                }
            })
            .catch((error) => {
                console.error('sendRequestToProvider error:', error);
                button.disabled = false;
                button.textContent = originalText;
                window.showErrorToast('Error', 'Failed to send request');
            });
    }

    // Add this function to handle save post button
    function saveEditedPost() {
        const root = document.getElementById('create-post-popup');
        const postId = root.querySelector('[data-role="save-post"]').dataset.postId;

        if (!postId) {
            window.showErrorToast("Error", "Post ID not found");
            return;
        }

        const title = root.querySelector("input[name='title']");
        const description = root.querySelector("textarea[name='description']");
        const categoryId = document.getElementById("Category_ID");
        const skills = document.getElementById("Skills");

        const price = root.querySelector("input[name='price']");
        const est_date = root.querySelector("input[name='estdate']");
        const level = root.querySelector("input[name='level']");
        const end_at = root.querySelector("input[name='endat']");

        endDateValue = end_at.value;

        // If empty or invalid, set to 30 days from now
        if (!endDateValue || endDateValue === '0000-00-00') {
            const futureDate = new Date();
            futureDate.setDate(futureDate.getDate() + 30);
            const year = futureDate.getFullYear();
            const month = String(futureDate.getMonth() + 1).padStart(2, '0');
            const day = String(futureDate.getDate()).padStart(2, '0');
            endDateValue = `${year}-${month}-${day}`;
            console.log("Setting default end date:", endDateValue);
        }

        // Validate date format (YYYY-MM-DD)
        const datePattern = /^\d{4}-\d{2}-\d{2}$/;
        if (!datePattern.test(endDateValue)) {
            window.showWarningToast("Validation Error", "Invalid end date format");
            return;
        }

        // Validation
        if (!title.value.trim() || !description.value.trim() || !categoryId.value) {
            window.showWarningToast("Validation Error", "Please fill all required fields");
            return;
        }

        if (isDateBeforeToday(est_date.value)) {
            showValidationTooltip(est_date, "Estimated date must be today or a future date");
            return;
        }

        if (isDateBeforeToday(endDateValue)) {
            showValidationTooltip(end_at, "Post expired date must be today or a future date");
            return;
        }

        // Get the skill IDs - NEED TO CHECK THE FORMAT
        const skillIdsString = skills.value || '';

        console.log("skillIdsString:", skillIdsString);

        // If it's JSON array like '[{"value":"Graphic Card","id":"3"}]', parse it
        let cleanedSkills = skillIdsString;
        try {
            // Check if it's JSON
            if (skillIdsString.startsWith('[')) {
                const skillsArray = JSON.parse(skillIdsString);
                cleanedSkills = skillsArray.map(s => s.id).join(',');
                console.log("Parsed from JSON, cleaned skills:", cleanedSkills);
            }
        } catch (e) {
            console.log("Not JSON, using as-is");
        }

        const postData = {
            title: title.value.trim(),
            description: description.value.trim(),
            category_id: categoryId.value,
            skills: cleanedSkills,  // Use cleaned skills
            price: price.value.trim() || '0',
            est_date: est_date.value || '',
            level: level.value.trim() || 'Beginner',
            end_at: endDateValue || ''
        };

        console.log('Final post data:', postData);

        const urlEncodedData = Object.keys(postData)
            .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(postData[key]))
            .join('&');

        fetch(`<?= BASE_URL ?>/requests/update/${postId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: urlEncodedData
        })
            .then(response => response.text().then(text => {
                console.log('Raw response:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Server returned invalid JSON');
                }
            }))
            .then(data => {
                console.log('Parsed response:', data);

                if (data.success) {
                    closeDialogBox('create-post-popup');
                    window.showSuccessToast('Success!', 'Post updated successfully!');

                    setTimeout(() => {
                        const activeSection = document.querySelector('.requests-section:not([style*="display: none"])');
                        const status = activeSection.classList.contains('active-posts') ? 'active' :
                            activeSection.classList.contains('draft-posts') ? 'draft' : 'expired';
                        loadPosts(status);
                    }, 500);

                    document.getElementById('create-post-form').reset();
                    inputReset('create-post-form');
                } else {
                    window.showErrorToast("Error", data.message || 'Failed to update post');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showErrorToast("Error", error.message || 'An error occurred');
            });
    }

    function getTodayDateString() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function applyMinDateConstraints(root = document) {
        const today = getTodayDateString();
        const estDateInput = root.querySelector("input[name='estdate']");
        const endAtInput = root.querySelector("input[name='endat']");

        if (estDateInput) {
            estDateInput.setAttribute('min', today);
        }
        if (endAtInput) {
            endAtInput.setAttribute('min', today);
        }
    }

    function isDateBeforeToday(dateValue) {
        if (!dateValue) return false;
        return dateValue < getTodayDateString();
    }

    function openPublishConfirmWithAction(onConfirm) {
        viewDialogBox('confirm-publish');

        const confirmPublishBtn = document.getElementById('confirmPublishBtn');
        const newConfirmPublishBtn = confirmPublishBtn.cloneNode(true);
        confirmPublishBtn.parentNode.replaceChild(newConfirmPublishBtn, confirmPublishBtn);
        newConfirmPublishBtn.onclick = onConfirm;
    }

    // Update the editPost function to attach save handler
    function editPost(id, mode = 'edit') {
        viewDialogBox('create-post-popup');

        const root = document.getElementById("create-post-popup");
        applyMinDateConstraints(root);

        fetch("<?= BASE_URL ?>/requests/view/" + id)
            .then(response => response.json())
            .then(post => {
                if (post.error) {
                    console.error('Error fetching post:', post.error);
                    return;
                }

                root.querySelector(".title").innerText = "Edit Post";
                root.querySelectorAll(".label").forEach(label => {
                    label.classList.add("label-float");
                });
                root.querySelector("#field-skill-label").classList.remove("label-float");

                root.querySelector("input[name='title']").value = post.Title || '';
                root.querySelector("textarea[name='description']").value = post.Description || '';
                root.querySelector("input[name='category']").value = post.Category_Name || '';
                document.getElementById("Category_ID").value = post.Category_ID || '';

                root.querySelector("input[name='price']").value = post.Requesting_Price || '';
                root.querySelector("input[name='estdate']").value = post.Est_Date || '';
                root.querySelector("input[name='level']").value = post.Level || '';

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

    function deletePost(id) {
        viewDialogBox('confirm-delete');

        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        // Remove previous event listeners to avoid multiple triggers
        const newConfirmDeleteBtn = confirmDeleteBtn.cloneNode(true);
        confirmDeleteBtn.parentNode.replaceChild(newConfirmDeleteBtn, confirmDeleteBtn);

        newConfirmDeleteBtn.addEventListener('click', function () {
            fetch("<?= BASE_URL ?>/requests/delete/" + id, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDialogBox('confirm-delete');
                        window.showSuccessToast("Success!", "Post deleted successfully");

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
                                    const status = activeSection.classList.contains('active-posts') ? 'active' :
                                        activeSection.classList.contains('draft-posts') ? 'draft' : 'expired';
                                    showEmptyState(status, itemList);
                                }
                            }, 300);
                        }
                    } else {
                        window.showErrorToast("Error", data.error || 'Failed to delete post');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast("Error", 'An error occurred while deleting the post. Please try again.');
                });
        });
    }

    function publishDraft(id) {
        viewDialogBox('confirm-publish');

        const confirmPublishBtn = document.getElementById('confirmPublishBtn');
        const newConfirmPublishBtn = confirmPublishBtn.cloneNode(true);
        confirmPublishBtn.parentNode.replaceChild(newConfirmPublishBtn, confirmPublishBtn);

        newConfirmPublishBtn.onclick = function () {
            fetch(`<?= BASE_URL ?>/requests/publish/${id}`, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDialogBox('confirm-publish');
                        window.showSuccessToast('Success!', 'Draft published successfully!');

                        // Reload draft section and switch to active
                        loadPosts('draft');
                        setTimeout(() => {
                            document.getElementById('active-posts').click();
                        }, 500);
                    } else {
                        window.showErrorToast('Error', data.message || 'Failed to publish draft');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showErrorToast('Error', 'An error occurred while publishing');
                });
        };
    }

    function repostExpired(id) {
        // Load the post as repost mode (special button + today date + publish confirmation).
        editPost(id, 'repost');
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

    function openCreateForm(id) {
        const root = document.getElementById(id);
        root.querySelector(".title").innerText = "Create A New Service Request";
        applyMinDateConstraints(root);
        // Change buttons: hide draft/publish, show save button
        const saveDraftBtn = root.querySelector('[data-role="save-draft"]');
        const publishBtn = root.querySelector('[data-role="publish"]');
        const savePostBtn = root.querySelector('[data-role="save-post"]');

        if (savePostBtn) savePostBtn.style.display = 'none';
        if (saveDraftBtn && publishBtn) {
            saveDraftBtn.style.display = '';
            publishBtn.style.display = '';
        }
        viewDialogBox(id);
    }

    /**
     * Initialize on page load
     */
    document.addEventListener('DOMContentLoaded', function () {
        applyMinDateConstraints(document.getElementById('create-post-popup'));

        // Setup search input handler with debouncing
        document.getElementById('searchInput').addEventListener('input', handleSearch);
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
                const status = activeSection.classList.contains('active-posts') ? 'active' :
                    activeSection.classList.contains('draft-posts') ? 'draft' : 'expired';
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
                    currentSort = sortValue;

                    // Reload posts with new sort
                    const activeSection = document.querySelector('.requests-section:not([style*="display: none"])');
                    const status = activeSection.classList.contains('active-posts') ? 'active' :
                        activeSection.classList.contains('draft-posts') ? 'draft' : 'expired';
                    loadPosts(status);
                }
            });
        }

        // Load active posts on initial page load
        loadPosts('active');

        // Handle tab switching
        const tabButtons = {
            'active-posts': document.getElementById('active-posts'),
            'draft-posts': document.getElementById('draft-posts'),
            'expired-posts': document.getElementById('expired-posts')
        };

        const sections = {
            'active-posts': document.querySelector('.active-posts'),
            'draft-posts': document.querySelector('.draft-posts'),
            'expired-posts': document.querySelector('.expired-posts')
        };

        Object.keys(tabButtons).forEach(key => {
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
        });
    });

</script>

</html>