
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

    <!-- <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script> -->
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
        <div class="modal-actions">
            <button class="action-btn btn-delete" onclick="closeDialogBox('view-post-popup')">Close</button>
        </div>
    </div>
</div>

<div class="dialog-box-2" id="provider-profile-modal">
    <div class="dialog-content" style="width: 980px; max-width: calc(100vw - 32px); overflow: hidden;">
        <div class="dialog-title" style="margin-bottom: 0;">
            <div class="title">Provider Profile</div>
            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeProjectProviderProfileModal()"></i>
            </div>
        </div>
        <div id="provider-profile-content" style="padding: 20px;"></div>
    </div>
</div>

<div class="dialog-box-2" id="provider-services-modal">
    <div class="dialog-content" style="width: 700px;">
        <div class="dialog-title">
            <div class="title">Services by <span id="modal-provider-name"></span></div>
            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeProjectProviderServicesModal()"></i>
            </div>
        </div>
        <div id="modal-services-content" style="padding: 20px 0;"></div>
        <div class="modal-actions" style="padding: 0 20px 20px; justify-content: flex-end;">
            <button type="button" class="action-btn btn-delete" onclick="closeProjectProviderServicesModal()">Close</button>
        </div>
    </div>
</div>

<div class="dialog-box-2" id="confirm-payment">
    <div class="dialog-content" style="width:580px; max-width:96vw;">
        <div class="dialog-title" style="display:flex; align-items:center; justify-content:space-between; padding-bottom:12px; border-bottom:1px solid #e5e7eb;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:38px;height:38px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-credit-card" style="color:#008500; font-size:16px;"></i>
                </div>
                <div class="title" style="font-size:17px; font-weight:700; color:#111827;">Confirm Payment</div>
            </div>
            <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('confirm-payment')" style="cursor:pointer; color:#6b7280; font-size:18px;"></i>
        </div>
        <div class="dialog-body"></div>
        <div id="payhere-form-container"></div>
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
            <button class="action-btn btn-delete" onclick="closeDialogBox('reopen-project-popup')">Cancel</button>
            <button class="action-btn btn-edit" id="btnConfirmReopenProject">
                <i class="fa-solid fa-rotate-left"></i> Move to Ongoing
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
    const providerProfileCache = {};
    const providerServiceCache = {};

    function normalizeProviderProfile(providerId, providerName, providerPicture, providerRating) {
        const normalizedId = Number(providerId) || 0;
        const normalizedName = providerName || 'Provider';
        const normalizedPicture = providerPicture || '';
        const ratingValue = Number(providerRating || 0);

        return {
            Provider_ID: normalizedId,
            First_Name: normalizedName,
            Last_Name: '',
            avatar: normalizedPicture,
            rating: Math.max(0, Math.min(5, ratingValue > 5 ? ratingValue / 20 : ratingValue)).toFixed(1),
            skills: [],
            categories: [],
            social_links: [],
            Bio: 'View the provider services below.',
            formatted_location: ''
        };
    }

    function getProviderProfileImage(provider) {
        const raw = provider?.avatar || provider?.Profile_Picture || '';
        if (!raw) return '';
        if (/^(https?:)?\/\//.test(raw) || raw.startsWith('/')) return raw;
        return `${window.BASE_URL}/file/user-files/${raw}`;
    }

    function renderProjectProviderNameLink(providerId, providerName, className = '') {
        const numericId = Number(providerId) || 0;
        return `<a href="#" class="${className}" style="background:none; border:none; padding:0; font:inherit; color:inherit; font-weight:700; text-decoration:none; text-align:left; cursor:pointer;" onclick="openProjectProviderProfileModal(${numericId}); return false;">${escapeHtml(providerName || 'Provider')}</a>`;
    }

    function renderProjectProviderProfileContent(provider, fallbackName = 'Provider') {
        const numericId = Number(provider?.Provider_ID) || 0;
        const displayName = `${provider?.First_Name || ''} ${provider?.Last_Name || ''}`.trim() || fallbackName || 'Provider';
        const imageUrl = getProviderProfileImage(provider || {});
        const categories = Array.isArray(provider?.categories) ? provider.categories : [];
        const skills = Array.isArray(provider?.skills) ? provider.skills : [];
        const socialLinks = Array.isArray(provider?.social_links)
            ? provider.social_links.filter(social => String(social?.link || '').trim())
            : [];
        const categoriesHTML = categories.length ? categories.map(item => `<span class="skill-tag">${escapeHtml(item)}</span>`).join('') : '<span class="skill-tag">No categories listed</span>';
        const skillsHTML = skills.length ? skills.map(item => `<span class="skill-tag">${escapeHtml(item)}</span>`).join('') : '<span class="skill-tag">No skills listed</span>';
        const socialHTML = socialLinks.length ? socialLinks.map(social => {
            const iconPrefix = (social.icon_class === 'fa-envelope' || social.icon_class === 'fa-link') ? 'fa-solid' : 'fa-brands';
            return `<a href="${escapeHtml(social.link || '#')}" title="${escapeHtml(social.name || 'Link')}" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:50%; color:#fff; text-decoration:none; background-color:${escapeHtml(social.color || '#008500')};"><i class="${iconPrefix} ${escapeHtml(social.icon_class || 'fa-link')}"></i></a>`;
        }).join('') : '<span style="font-size: 13px; color: #94a3b8;">No social links</span>';

        return `
            <div style="display:grid; gap:18px; padding:0 20px 16px;">
                <div style="position:relative; overflow:hidden; border-radius:22px; border:1px solid #e5e7eb; background:linear-gradient(135deg, #0f172a 0%, #0f3d2e 48%, #008500 100%); color:#fff; box-shadow:0 18px 40px rgba(15,23,42,0.18);">
                    <div style="position:absolute; inset:0; background: radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 28%), radial-gradient(circle at left bottom, rgba(255,255,255,0.12), transparent 22%);"></div>
                    <div style="position:relative; padding:28px; display:grid; grid-template-columns: 132px minmax(0,1fr) auto; gap:22px; align-items:center;">
                        <div style="width:132px; height:132px; border-radius:28px; padding:5px; background:rgba(255,255,255,0.16); box-shadow:0 16px 30px rgba(0,0,0,0.18);">
                            ${imageUrl ? `<img src="${imageUrl}" alt="${escapeHtml(displayName)}" style="width:100%; height:100%; object-fit:cover; border-radius:23px; background:#fff;">` : '<div style="width:100%; height:100%; border-radius:23px; background:#fff; display:flex; align-items:center; justify-content:center; color:#15803d;"><i class="fa-solid fa-user" style="font-size:30px;"></i></div>'}
                        </div>
                        <div style="min-width:0;">
                            <div style="font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; opacity:0.85; margin-bottom:8px;">Provider Profile</div>
                            <h2 style="margin:0; font-size:30px; line-height:1.2; font-weight:800;">${escapeHtml(displayName)}</h2>
                            <div style="margin-top:10px; display:flex; flex-wrap:wrap; gap:10px; align-items:center; color:rgba(255,255,255,0.92); font-size:14px;">
                                <span style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:rgba(255,255,255,0.14);"><i class="fa-solid fa-star" style="color:#fbbf24;"></i>${escapeHtml(provider?.rating || '0.0')}</span>
                            </div>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:10px; justify-self:end;">
                            <button type="button" class="action-btn btn-view" style="border-color: rgba(255,255,255,0.3); color:#fff; background:rgba(255,255,255,0.08);" onclick="openProjectProviderServicesModal(${numericId}, '${escapeHtml(displayName).replace(/'/g, "\\'")}')"><i class="fa-solid fa-briefcase"></i> Services</button>
                        </div>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns: minmax(0, 1.35fr) minmax(320px, 0.85fr); gap:18px; align-items:start;">
                    <div style="display:grid; gap:18px;">
                        <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">About</div><p style="margin:0; color:#334155; line-height:1.8; font-size:14px; white-space:pre-wrap;">${escapeHtml(provider?.Bio || 'Experienced professional ready to help with your project.')}</p></div>
                        <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Categories</div><div style="display:flex; flex-wrap:wrap; gap:8px;">${categoriesHTML}</div></div>
                        <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Skills</div><div style="display:flex; flex-wrap:wrap; gap:8px;">${skillsHTML}</div></div>
                    </div>
                    <div style="display:grid; gap:18px;">
                        <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
                            <div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Stats</div>
                            <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:10px;">
                                <div class="stat-pill" style="background:#eff6ff; border-color:#bfdbfe;"><b>${escapeHtml(provider?.rating || 0)}</b><br>Rating</div>
                                <div class="stat-pill" style="background:#f8fafc; border-color:#e2e8f0;"><b>${categories.length}</b><br>Categories</div>
                                <div class="stat-pill" style="background:#fdf4ff; border-color:#f5d0fe;"><b>${socialLinks.length}</b><br>Links</div>
                            </div>
                        </div>
                        <div style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:18px; background:#fff;"><div style="font-size:13px; font-weight:800; color:#008500; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">Social Links</div><div class="provider-social" style="justify-content:flex-start; gap:10px; padding-top:0; border-top:none;">${socialHTML}</div></div>
                    </div>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:2px;"><button type="button" class="action-btn btn-delete" onclick="closeProjectProviderProfileModal()">Close</button></div>
            </div>
        `;
    }

    function openProjectProviderProfileModal(providerId, providerName = '', providerPicture = '', providerRating = '') {
        const numericId = Number(providerId) || 0;
        if (!numericId) return;

        if (!providerProfileCache[numericId]) {
            providerProfileCache[numericId] = normalizeProviderProfile(numericId, providerName, providerPicture, providerRating);
        }
        const modal = document.getElementById('provider-profile-modal');
        const content = document.getElementById('provider-profile-content');
        if (!modal || !content) return;

        viewDialogBox('provider-profile-modal');

        content.innerHTML = '<div style="text-align: center; padding: 60px 20px; color: #6b7280;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 20px; color: #008500; opacity: 0.8;"></i><p style="font-size: 16px; font-weight: 500; color: #1f2937;">Loading provider profile...</p></div>';

        const fallbackProvider = providerProfileCache[numericId];
        fetch(`${window.BASE_URL}/providers/profile?provider_id=${numericId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const dbProvider = data && data.success && data.provider ? data.provider : fallbackProvider;
                if (!dbProvider) {
                    throw new Error('Provider profile could not be loaded.');
                }

                providerProfileCache[numericId] = Object.assign({}, fallbackProvider || {}, dbProvider);
                content.innerHTML = renderProjectProviderProfileContent(providerProfileCache[numericId], providerName || 'Provider');
            })
            .catch(error => {
                console.error('Error loading provider profile:', error);
                content.innerHTML = renderProjectProviderProfileContent(fallbackProvider, providerName || 'Provider');
            });
    }

    function closeProjectProviderProfileModal() {
        closeDialogBox('provider-profile-modal');
    }

    function openProjectProviderServicesModal(providerId, providerName = 'Provider') {
        const numericId = Number(providerId) || 0;
        if (!numericId) return;

        const modal = document.getElementById('provider-services-modal');
        const modalTitle = document.getElementById('modal-provider-name');
        if (!modal || !modalTitle) return;

        modalTitle.textContent = providerName || 'Provider';
        viewDialogBox('provider-services-modal');
        loadProjectProviderServices(numericId);
    }

    function closeProjectProviderServicesModal() {
        closeDialogBox('provider-services-modal');
    }

    function renderProjectProviderServiceCard(service) {
        const serviceId = Number(service.Provider_Categories_ID) || 0;
        if (serviceId) {
            providerServiceCache[serviceId] = service;
        }

        const skillsHTML = (service.skills || []).map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('');
        const linksHTML = (service.show_links || []).map(link => {
            const label = escapeHtml(link.label || 'Link');
            const url = escapeHtml(link.url || '#');
            return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="action-btn btn-view" style="padding: 7px 12px; font-size: 12px; text-decoration: none;"><i class="fa-solid fa-link"></i> ${label}</a>`;
        }).join('');
        const successiveRate = service.success_rate_display || `${Math.max(0, Math.min(100, Math.round(Number(service.Rating || 0))))}%`;

        return `
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background: #f9fafb; transition: all 0.2s ease;">
                <div style="margin-bottom: 12px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1f2937; margin: 0 0 8px 0; line-height: 1.4;">${escapeHtml(service.Title || 'Untitled Service')}</h3>
                    <p style="font-size: 14px; color: #6b7280; margin: 0 0 12px 0; line-height: 1.5;">${escapeHtml(service.Description ? service.Description.substring(0, 150) + (service.Description.length > 150 ? '...' : '') : 'No description')}</p>
                </div>
                ${skillsHTML ? `<div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 6px;">${skillsHTML}</div>` : ''}
                ${linksHTML ? `<div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 8px;">${linksHTML}</div>` : ''}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                    <div style="font-size: 14px; color: #4b5563;">
                        <span style="font-weight: 600; color: #008500;">${escapeHtml(service.price_display || 'Contact for price')}</span>
                        <span style="color: #9ca3af; font-size: 13px;"> • ${escapeHtml(service.rate_type_display || service.Price_Type || 'N/A')}</span>
                    </div>
                    <span style="font-size: 12px; color: #9ca3af;">${escapeHtml(service.Category_Name || service.CategoryName || 'Service')}</span>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <span class="skill-tag" style="background:#fff7ed; color:#9a3412; border-color:#fed7aa;">Successive Rate: ${escapeHtml(successiveRate)}</span>
                </div>
            </div>
        `;
    }

    function loadProjectProviderServices(providerId) {
        const modalContent = document.getElementById('modal-services-content');
        if (!modalContent) return;

        modalContent.innerHTML = '<div style="text-align: center; padding: 60px 20px; color: #6b7280;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 20px; color: #008500; opacity: 0.8;"></i><p style="font-size: 16px; font-weight: 500; color: #1f2937;">Loading services...</p></div>';

        fetch(`<?= BASE_URL ?>/providers/services?provider_id=${providerId}&limit=10`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => JSON.parse(data))
            .then(data => {
                if (!data.success) {
                    modalContent.innerHTML = `<p style="text-align: center; padding: 40px; color: #ef4444;">${escapeHtml(data.message || 'Failed to load services')}</p>`;
                    return;
                }

                if (!data.services || data.services.length === 0) {
                    modalContent.innerHTML = '<p style="text-align: center; padding: 40px; color: #6b7280;">No services found for this provider.</p>';
                    return;
                }

                const servicesHTML = data.services.map(service => renderProjectProviderServiceCard(service)).join('');
                modalContent.innerHTML = `<div style="display: grid; grid-template-columns: 1fr; gap: 16px; padding: 0 20px;">${servicesHTML}</div>`;
            })
            .catch(error => {
                console.error('Error loading services:', error);
                modalContent.innerHTML = `<p style="text-align: center; padding: 40px; color: #ef4444;">Error loading services: ${escapeHtml(error.message)}</p>`;
            });
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
            const statusMap = { 'pending': 'pending', 'accepted': 'accepted', 'completed': 'completed' };
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
        const normalizedSkills = Array.isArray(skills)
            ? skills.map(skill => {
                if (typeof skill === 'string') return skill;
                return skill?.Skill || skill?.name || skill?.Name || '';
            }).filter(Boolean)
            : [];

        const skillsHTML = normalizedSkills.length > 0
            ? normalizedSkills.map(skill => `<span class="skill-tag">${escapeHtml(skill)}</span>`).join('')
            : '<span class="skill-tag">No skills specified</span>';

        const description = post.Description || '';
        const snippet = description.length > 300
            ? escapeHtml(description.substring(0, 300)) + '...'
            : escapeHtml(description);

        const providerName = post.Provider_Name || 'Unassigned provider';
        const providerPicture = post.Provider_Picture
            ? `${window.BASE_URL}/file/user-files/${post.Provider_Picture}`
            : null;
        const providerRatingRaw = Number(post.Provider_Star_Rating ?? post.Provider_Rating ?? post.Provider_Score ?? 0);
        const providerRating = Math.max(0, Math.min(5, providerRatingRaw > 5 ? providerRatingRaw / 20 : providerRatingRaw)).toFixed(1);
        const messageHref = post.Provider_ID
            ? `${window.BASE_URL}/messages?new=${post.Provider_ID}`
            : `${window.BASE_URL}/messages`;
        const providerProfileNameAttr = escapeHtml(providerName).replace(/'/g, "\\'");
        providerProfileCache[Number(post.Provider_ID) || 0] = normalizeProviderProfile(post.Provider_ID, providerName, providerPicture, providerRating);

        //const publishedDate = formatDate(post.Published_At || post.Created_At);
        //const daysLeft = calculateDaysLeft(post.End_At);
        //if (daysLeft == 'Expired') {
        //    updateAsExpired(post.Post_ID);
        //}

        // Different buttons based on status
        let actionsHTML = '';
        if (status === 'pending') {
            actionsHTML = `
                <a href="${messageHref}" class="action-btn btn-edit"
                aria-label="Messages">
                    <i class="fas fa-comments"></i>Messages
                </a>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID}, 'pending')">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-delete" onclick="cancelRequest(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Cancel
                </button>
            `;
        } else if (status === 'accepted') {
            actionsHTML = `
                <a href="${messageHref}" class="action-btn btn-edit"
                aria-label="Messages">
                    <i class="fas fa-comments"></i>Messages
                </a>
                <button class="action-btn btn-edit" onclick="payPayment(${post.Post_ID})">
                    <i class="fas fa-credit-card"></i> Pay
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID}, 'accepted')">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-delete" onclick="cancelRequest(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Cancel
                </button>
            `;
        } else if (status === 'ongoing') {
            actionsHTML = `
                <button class="action-btn btn-edit" onclick="updateRequest(${post.Post_ID}, ${Number(post.Project_ID || 0)})">
                    <i class="fas fa-redo"></i> Update
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID}, 'ongoing')">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-delete" onclick="cancelOngoingProject(${post.Post_ID})">
                    <i class="fas fa-trash"></i> Cancel
                </button>
            `;
        }
        else if (status === 'pending-review') {
            actionsHTML = `
                <button class="action-btn btn-edit" onclick="completePendingReviewProject(${post.Post_ID})">
                    <i class="fas fa-star"></i> Submit Review
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID}, 'pending-review')">
                    <i class="fas fa-eye"></i> View
                </button>
            `;
        }
        else if (status === 'completed') {
            actionsHTML = `
                <a href="${messageHref}" class="action-btn btn-edit"
                aria-label="Messages">
                    <i class="fas fa-comments"></i>Messages
                </a>
                <button class="action-btn btn-edit" onclick="updateRequest(${post.Post_ID}, ${Number(post.Project_ID || 0)})">
                    <i class="fas fa-redo"></i> Change Requirements
                </button>
                <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID}, 'completed')">
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
                            <span class="detail-value budget-amount">LKR ${post.Requesting_Price || 0}/=</span>
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

        const postTypeLabel = post.Post_Type === 'direct' ? 'Direct Request' : 'Bid Request';
        const postedDate = formatDate(post.Published_At || post.Created_At);
        const category = post.Category_Name || 'N/A';
        const budget = `LKR ${post.Requesting_Price || 0}/=`;
        const estDate = formatEstDate(post.Est_Date);
        const progress = Math.max(0, Math.min(100, Number(post.Progress || 0)));

        let progressHTML = '';
        if (status === 'ongoing' || status === 'pending-review' || status === 'completed') {
            progressHTML = `<div class="progress-container" aria-label="Project progress">
                <div class="progress-label">Progress: <span class="progress-percent">${progress}%</span> </div>
                <div class="progress-track"><div class="progress-fill" style="width: ${progress}%;"></div></div>
            </div>`;
        }

        return `
            <div class="search-item">
                <input type="hidden" class="post-id" value="${post.Post_ID}">
                
                        <div class="post-date" style='margin:5px'><i class="fas fa-calendar"></i><span>${postedDate}</span></div>
                <div class="post-header">
                    <div class="post-meta">
                        <div style="display:flex; align-items:center; gap:10px; margin-top:6px;">
                            ${providerPicture
                                ? `<img src="${providerPicture}" alt="${escapeHtml(providerName)}" style="width:42px; height:42px; border-radius:50%; object-fit:cover; border:2px solid #22c55e;" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">`
                                : ''}
                            <span style="width:42px; height:42px; border-radius:50%; background:#e8f5eb; border:2px solid #22c55e; align-items:center; justify-content:center; color:#15803d; flex:0 0 42px; display:${providerPicture ? 'none' : 'inline-flex'};"><i class="fas fa-user"></i></span>
                            <span style="display:flex; flex-direction:column; line-height:1.25;">
                                <span style="font-size:16px; font-weight:800; color:#0f172a;">${escapeHtml(providerName)}</span>
                                <span style="font-size:13px; font-weight:700; color:#f59e0b;"><i class="fas fa-star" style="margin-right:4px;"></i>${providerRating}</span>
                            </span>
                        </div>
                    </div>
                    <div class="post-actions">
                        ${actionsHTML}
                    </div>
                </div>
                
                <h3 class="post-title">${escapeHtml(post.Title)}</h3>
                <div class="post-description">${snippet}</div>
                <div class="post-footer">
                    <div class="post-details">
                        <div class="detail-item">
                            <span class="detail-label">Budget</span>
                            <span class="detail-value budget-amount">${budget}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Category</span>
                            <span class="detail-value">${escapeHtml(category)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estimated Date</span>
                            <span class="detail-value">${estDate}</span>
                        </div>
                        ${(status === 'ongoing' || status === 'pending-review' || status === 'completed') ? `
                        <div class="detail-item">
                            <span class="detail-label">Progress</span>
                            <span class="detail-value">${progress}%</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
                ${progressHTML}
                <div style="display:flex; justify-content:flex-end; margin-top:10px;">
                    <span class="skill-tag" style="background:#f1f5f9; color:#0f172a; border:1px solid #cbd5e1;">${postTypeLabel}</span>
                </div>
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
        const budget = `Rs. ${Number(post.Requesting_Price || 0).toLocaleString('en-US', {minimumFractionDigits:2})}`;
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
                        <button class="btn-outline" onclick="viewPost(${post.Post_ID}, 'pending')" title="View Request">
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
        const budget = `LKR ${post.Requesting_Price || 0}/=`;
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
                            ${renderProjectProviderNameLink(post.Provider_ID, providerName, 'accepted-provider-name')}
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
                        <button class="action-btn btn-view" onclick="viewPost(${post.Post_ID}, 'accepted')">
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
        const budget      = `LKR ${post.Requesting_Price || 0}/=`;
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
                            ${renderProjectProviderNameLink(post.Provider_ID, providerName, 'ongoing-provider-name')}
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
                        <button class="action-btn btn-edit" onclick="updateRequest(${post.Post_ID}, ${Number(post.Project_ID || 0)})">
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
        const budget      = `LKR ${post.Requesting_Price || 0}/=`;
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
                            ${renderProjectProviderNameLink(post.Provider_ID, providerName, 'ongoing-provider-name')}
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
        const budget      = `LKR ${post.Requesting_Price || 0}/=`;
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
                            ${renderProjectProviderNameLink(post.Provider_ID, providerName, 'ongoing-provider-name')}
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

    let viewPostUpdatesChart = null;

    function getViewPostProgressColor(pct) {
        if (pct >= 75) return '#22c55e';
        if (pct >= 40) return '#f59e0b';
        return '#3b82f6';
    }

    function renderViewDialogUpdateTimeline(events) {
        if (!Array.isArray(events) || events.length === 0) {
            return `<div class="pd-timeline-empty">
                <i class="fa-solid fa-timeline"></i>
                <p>No activity recorded yet.</p>
            </div>`;
        }

        return `<div class="pd-timeline">${events.map(ev => {
            const dotClass = 'pd-timeline-dot-' + (ev.color || 'gray');
            const title = escapeHtml(ev.title || 'Update');
            const desc = ev.description ? `<div class="pd-timeline-desc">${escapeHtml(ev.description)}</div>` : '';
            const date = ev.date
                ? new Date(ev.date).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
                : 'N/A';

            let meta = '';
            if (ev.type === 'progress_update') {
                const parts = [];
                if ((Number(ev.progress_completed) || 0) > 0) {
                    parts.push(`<span><i class="fa-solid fa-chart-simple"></i> Progress: ${Number(ev.progress_completed)}%</span>`);
                }
                if ((Number(ev.worked_hours) || 0) > 0) {
                    parts.push(`<span><i class="fa-solid fa-clock"></i> ${Number(ev.worked_hours)}h worked</span>`);
                }
                if (parts.length) {
                    meta = `<div class="pd-timeline-meta">${parts.join('')}</div>`;
                }
            }

            const files = Array.isArray(ev.files) ? ev.files : [];
            const filesHtml = files.length
                ? `<div class="pd-timeline-files">${files.map(f => {
                    const name = escapeHtml(String(f).split('/').pop() || 'Attachment');
                    const url = `${window.BASE_URL}/file/project-updates/${f}`;
                    return `<a href="${url}" target="_blank" class="pd-timeline-file" title="${name}"><i class="fa-solid fa-file"></i> ${name}</a>`;
                }).join('')}</div>`
                : '';

            return `
                <div class="pd-timeline-item">
                    <div class="pd-timeline-dot ${dotClass}">
                        <i class="fa-solid ${escapeHtml(ev.icon || 'fa-circle')}"></i>
                    </div>
                    <div class="pd-timeline-content">
                        <div class="pd-timeline-head">
                            <div class="pd-timeline-title">${title}</div>
                            <div class="pd-timeline-date">${escapeHtml(date)}</div>
                        </div>
                        ${desc}
                        ${meta}
                        ${filesHtml}
                    </div>
                </div>`;
        }).join('')}</div>`;
    }

    function initViewDialogUpdatesChart(points) {
        const canvas = document.getElementById('viewPostProgressChart');
        if (!canvas || typeof Chart === 'undefined') return;

        if (viewPostUpdatesChart) {
            viewPostUpdatesChart.destroy();
            viewPostUpdatesChart = null;
        }

        if (!Array.isArray(points) || points.length < 2) {
            const wrap = document.getElementById('view-post-updates-chart-wrap');
            if (wrap) {
                wrap.innerHTML = `<div class="pd-chart-empty"><i class="fa-solid fa-chart-line"></i><p>Not enough data to display chart yet.</p></div>`;
            }
            return;
        }

        const labels = points.map(p => new Date(p.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const data = points.map(p => Number(p.progress) || 0);

        viewPostUpdatesChart = new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data,
                    borderColor: '#008500',
                    backgroundColor: 'rgba(0, 133, 0, 0.08)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#008500',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: { stepSize: 25, callback: v => `${v}%`, color: '#9ca3af', font: { size: 11 } },
                        grid: { color: '#f3f4f6' },
                        border: { display: false },
                    },
                    x: {
                        ticks: { color: '#9ca3af', font: { size: 11 }, maxRotation: 45 },
                        grid: { display: false },
                        border: { display: false },
                    }
                },
                interaction: { intersect: false, mode: 'index' },
            }
        });
    }

    function renderViewDialogProjectUpdates(details) {
        const progress = Math.max(0, Math.min(100, Number(details?.progress || 0)));
        const progressColor = getViewPostProgressColor(progress);
        const timeline = Array.isArray(details?.timeline) ? details.timeline : [];

        return `
            <div class="pd-progress-section" style="margin-bottom:14px;">
                <div class="pd-section-title"><i class="fa-solid fa-chart-simple"></i> Current Progress</div>
                <div class="pd-progress-bar-wrap">
                    <div class="pd-progress-header">
                        <span class="pd-progress-label">Completion</span>
                        <span class="pd-progress-pct" style="color:${progressColor}">${progress}%</span>
                    </div>
                    <div class="pd-progress-track">
                        <div class="pd-progress-fill" style="width:${progress}%; background:${progressColor};"></div>
                    </div>
                </div>
            </div>
            <div class="pd-chart-section" style="margin-bottom:14px;">
                <div class="pd-section-title"><i class="fa-solid fa-chart-line"></i> Progress Over Time</div>
                <div class="pd-chart-container" id="view-post-updates-chart-wrap" style="height:220px;">
                    <canvas id="viewPostProgressChart"></canvas>
                </div>
            </div>
            <div class="pd-timeline-section">
                <div class="pd-section-title"><i class="fa-solid fa-timeline"></i> Project Milestones</div>
                ${renderViewDialogUpdateTimeline(timeline)}
            </div>`;
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

    function viewPost(id, status = '') {
        const activeStatus = document.querySelector('.tab-buttons .buttons.active')?.dataset?.target || '';
        const normalizedStatus = (status || window._viewPostStatus || activeStatus || '').toLowerCase();
        window._viewPostStatus = normalizedStatus;
        const shouldShowProjectUpdates = !['pending', 'accepted'].includes(normalizedStatus);

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
                            <button onclick="viewPost(${id}, window._viewPostStatus)" class="retry-btn">Retry</button>
                        </div>
                    `;
                    return;
                }
                const providerName = post.Provider_Name || post.provider_name || post.Provider?.Name || 'Unassigned provider';
                const providerPictureRaw = post.Provider_Picture || post.provider_picture || post.Provider_Image || post.provider_image || post.Provider_Avatar || post.provider_avatar || '';
                const providerPicture = providerPictureRaw
                    ? (/^(https?:)?\/\//.test(providerPictureRaw) || providerPictureRaw.startsWith('/')
                        ? providerPictureRaw
                        : `${window.BASE_URL}/file/user-files/${providerPictureRaw}`)
                    : null;
                const providerRatingRaw = Number(
                    post.Provider_Star_Rating
                    ?? post.Provider_Rating
                    ?? post.Provider_Score
                    ?? post.provider_rating
                    ?? post.provider_score
                    ?? post.Provider?.Rating
                    ?? 0
                );
                const providerRating = Math.max(0, Math.min(5, providerRatingRaw > 5 ? providerRatingRaw / 20 : providerRatingRaw)).toFixed(1);
                providerProfileCache[Number(post.Provider_ID) || 0] = normalizeProviderProfile(post.Provider_ID, providerName, providerPicture, providerRating);
                console.log('Fetched post details:', post);
                // Format date
                const publishDate = post.Published_At ?
                    new Date(post.Published_At).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : 'N/A';

                const durationValue = (post.Duration ?? '').toString().trim();
                const durationType = (post.Duration_Type ?? '').toString().trim();
                const durationText = durationValue
                    ? `${durationValue}${durationType ? ' ' + durationType : ''}`
                    : (post.Est_Date ? formatEstDate(post.Est_Date) : 'N/A');
                const projectUpdatesSectionHTML = shouldShowProjectUpdates
                    ? `<div class="post-view-section">
                        <div class="section-title">Project Updates</div>
                        <div id="view-post-updates" class="section-body" style="padding:0; color:#6b7280;">Loading project updates...</div>
                    </div>`
                    : '';

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
                        <div class="post-provider" style="display:flex; align-items:center; gap:12px;">
                            ${providerPicture
                                ? `<img src="${providerPicture}" alt="${escapeHtml(providerName)}" style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #22c55e;" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">`
                                : ''}
                            <span style="width:48px; height:48px; border-radius:50%; background:#e8f5eb; border:2px solid #22c55e; align-items:center; justify-content:center; color:#15803d; display:${providerPicture ? 'none' : 'inline-flex'};"><i class="fas fa-user"></i></span>
                            <span style="display:flex; flex-direction:column; line-height:1.25;">
                                ${renderProjectProviderNameLink(post.Provider_ID, providerName)}
                                <span style="font-size:13px; font-weight:700; color:#f59e0b;"><i class="fas fa-star" style="margin-right:4px;"></i>${providerRating}</span>
                            </span>
                        </div>
                    </div> 
                    <div class="post-view-section">
                        <div class="section-title">Details</div>
                        <div class="kv-grid">
                            <div class="kv-item"><span class="kv-label">Budget:</span><span class="kv-value">LKR ${post.Requesting_Price || '0'}/=</span></div>
                            <div class="kv-item"><span class="kv-label">Level:</span><span class="kv-value">${post.Level || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Est Date:</span><span class="kv-value">${durationText}</span></div>
                        </div>
                    </div>
                    ${projectUpdatesSectionHTML}
                </div>
            `;

                if (shouldShowProjectUpdates) {
                    const updatesContainer = document.getElementById('view-post-updates');

                    fetch(`${window.BASE_URL}/project/details/${id}`)
                        .then(response => response.json())
                        .then(json => {
                            if (!updatesContainer) return;
                            if (!json.success) throw new Error(json.error || 'Failed to load updates');
                            updatesContainer.innerHTML = renderViewDialogProjectUpdates(json?.data || {});
                            initViewDialogUpdatesChart(json?.data?.progress_history || []);
                        })
                        .catch(() => {
                            if (updatesContainer) {
                                updatesContainer.innerHTML = '<div class="section-body" style="color:#ef4444;">Unable to load project updates.</div>';
                            }
                        });
                }
            })
            .catch(error => {
                console.error('Error fetching post:', error);
                formContainer.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Failed to load post details. Please try again.</p>
                        <button onclick="viewPost(${id}, window._viewPostStatus)" class="retry-btn">Retry</button>
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
        const payhereFormContainer = document.getElementById('payhere-form-container');
        if (payhereFormContainer) payhereFormContainer.innerHTML = '';

        formContainer.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading payment details...</p>
            </div>
        `;

        fetch("<?= BASE_URL ?>/requests/view/" + id)
            .then(response => response.json())
            .then(post => new Promise(resolve => setTimeout(() => resolve(post), 300)))
            .then(post => {
                if (post.error) {
                    formContainer.innerHTML = `
                        <div class="error-state">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Failed to load post details</p>
                            <button onclick="payPayment(${id})" class="retry-btn">Retry</button>
                        </div>`;
                    return;
                }

                const providerName = escapeHtml(post.Provider_Name || 'Assigned Provider');
                const amount       = parseFloat(post.Requesting_Price || 0);
                const priceType    = post.Price_Type || 'Fixed';

                formContainer.innerHTML = `
                <div style="padding:4px 0 16px;">

                    <!-- Project info summary -->
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:14px 16px; margin-bottom:16px;">
                        <div style="font-size:15px; font-weight:700; color:#111827; margin-bottom:6px;">${escapeHtml(post.Title || 'Untitled Project')}</div>
                        <div style="display:flex; gap:16px; flex-wrap:wrap; font-size:13px; color:#6b7280;">
                            <span><i class="fa-solid fa-user" style="color:#008500;"></i> ${providerName}</span>
                            <span><i class="fa-solid fa-layer-group" style="color:#008500;"></i> ${escapeHtml(post.Category_Name || 'N/A')}</span>
                            <span><i class="fa-solid fa-tag" style="color:#008500;"></i> ${priceType}</span>
                        </div>
                        ${post.Description ? `<div style="font-size:13px; color:#374151; margin-top:8px; line-height:1.5;">${escapeHtml(post.Description.substring(0, 160))}${post.Description.length > 160 ? '…' : ''}</div>` : ''}
                    </div>

                    <!-- Amount highlighted -->
                    <div style="background:linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border:2px solid #86efac; border-radius:14px; padding:18px 20px; margin-bottom:20px; text-align:center;">
                        <div style="font-size:12px; font-weight:600; color:#166534; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:6px;">Amount to Pay</div>
                        <div style="font-size:32px; font-weight:800; color:#15803d; line-height:1.1;">LKR ${amount.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Details</div>
                        <div class="kv-grid">
                            <div class="kv-item"><span class="kv-label">Budget:</span><span class="kv-value">LKR ${post.Requesting_Price || '0'}/=</span></div>
                            <div class="kv-item"><span class="kv-label">Level:</span><span class="kv-value">${post.Level || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Est Date:</span><span class="kv-value">${post.Duration || 'N/A'} ${post.Duration_Type || 'N/A'}</span></div>
                        </div>

                    <!-- PayHere branding -->
                    <div style="display:flex; align-items:center; justify-content:center; gap:8px; font-size:12px; color:#6b7280; margin-bottom:16px;">
                        <i class="fa-solid fa-shield-halved" style="color:#008500;"></i>
                        <span>Secured by <strong style="color:#111827;">PayHere</strong> — Sri Lanka's trusted payment gateway</span>
                        ${<?= PAYHERE_SANDBOX ? 'true' : 'false' ?> ? '<span style="background:#fef3c7; color:#92400e; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; border:1px solid #fde68a;">SANDBOX</span>' : ''}
                    </div>

                    <!-- Actions -->
                    <div style="display:flex; gap:10px; justify-content:flex-end;">
                        <button class="action-btn btn-delete" type="button" onclick="closeDialogBox('confirm-payment')">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </button>
                        <button class="action-btn btn-edit" id="confirmPaymentBtn" type="button" data-post-id="${id}">
                            <i class="fa-solid fa-credit-card"></i> Proceed to PayHere
                        </button>
                    </div>
                </div>`;

                // Wire up the proceed button — submit a form POST to payhereRedirect
                document.getElementById('confirmPaymentBtn').addEventListener('click', function () {
                    this.disabled = true;
                    this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Redirecting…';
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= BASE_URL ?>/payments/payhere/' + id;
                    document.body.appendChild(form);
                    form.submit();
                });
            })
            .catch(() => {
                formContainer.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Failed to load payment details. Please try again.</p>
                        <button onclick="payPayment(${id})" class="retry-btn">Retry</button>
                    </div>`;
            });
    }

    function updateRequest(postId, projectId = 0) {
        window._reqCurrentProjectId = Number(projectId || 0) || null;
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
        const normalizedPostId = Number(postId || window._reqCurrentPostId || 0);
        if (normalizedPostId > 0) {
            window._reqCurrentPostId = normalizedPostId;
        }

        const loading = document.getElementById('req-loading');
        const body    = document.getElementById('req-list-body');
        if (loading) loading.style.display = '';
        if (body)    body.style.display    = 'none';

        if (normalizedPostId <= 0) {
            if (loading) {
                loading.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Invalid project reference. Please reopen the dialog.</p>
                    </div>`;
            }
            return;
        }

        fetch(`${window.BASE_URL}/project/getrequirements/${normalizedPostId}`)
            .then(async (r) => {
                const raw = await r.text();
                let json;
                try {
                    json = JSON.parse(raw);
                } catch (parseErr) {
                    const preview = String(raw || '').replace(/\s+/g, ' ').trim().slice(0, 180);
                    throw new Error(preview || 'Invalid JSON response from server');
                }
                if (!r.ok) {
                    throw new Error(json?.error || json?.message || `HTTP ${r.status}`);
                }
                return json;
            })
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
                const message = err?.message || 'Failed to load requirements.';
                if (loading) loading.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>${escapeHtml(message)}</p>
                        <button onclick="reqLoadRequirements(${normalizedPostId})" class="retry-btn">Retry</button>
                    </div>`;
            });
    }

    async function ensureRequirementProjectLoaded(postId) {
        if (window._reqCurrentProjectId) {
            return window._reqCurrentProjectId;
        }

        if (!postId) {
            return 0;
        }

        try {
            const response = await fetch(`${window.BASE_URL}/project/getrequirements/${postId}`);
            const raw = await response.text();
            const json = JSON.parse(raw);
            if (json?.success && Number(json?.data?.project_id) > 0) {
                window._reqCurrentProjectId = Number(json.data.project_id);
                return window._reqCurrentProjectId;
            }
        } catch (err) {
            console.error('ensureRequirementProjectLoaded error:', err);
        }

        // Fallback: resolve from project detail endpoint when requirements are empty/not yet available.
        try {
            const detailsResponse = await fetch(`${window.BASE_URL}/project/details/${postId}`);
            const detailsJson = await detailsResponse.json();
            if (detailsJson?.success && Number(detailsJson?.data?.project_id) > 0) {
                window._reqCurrentProjectId = Number(detailsJson.data.project_id);
                return window._reqCurrentProjectId;
            }
        } catch (err) {
            console.error('ensureRequirementProjectLoaded details fallback error:', err);
        }

        return 0;
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
        let projectId   = Number(window._reqCurrentProjectId || 0);
        const postId    = Number(window._reqCurrentPostId || 0);
        const title       = document.getElementById('reqTitle')?.value.trim()       || '';
        const description = document.getElementById('reqDescription')?.value.trim() || '';
        const filesInput  = document.getElementById('reqFiles');

        if (!projectId) {
            projectId = await ensureRequirementProjectLoaded(postId);
        }

        if (!title) {
            document.getElementById('reqTitle')?.focus();
            window.showErrorToast('Error', 'Please enter a requirement title.');
            return;
        }

        const btn = document.getElementById('btnSubmitReq');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…'; }

        const fd = new FormData();
        if (projectId > 0) {
            fd.append('project_id', projectId);
        }
        if (postId > 0) {
            fd.append('post_id', postId);
        }
        fd.append('title',        title);
        fd.append('description',  description);
        if (filesInput && filesInput.files.length) {
            Array.from(filesInput.files).forEach(f => fd.append('req_files[]', f));
        }

        try {
            const res  = await fetch(`${window.BASE_URL}/project/add-requirement`, { method: 'POST', body: fd });
            const data = await res.json();

            if (!data.success) {
                window.showErrorToast('Error', data.error || `Failed to submit requirement (HTTP ${res.status}).`);
                return;
            }

            window.showSuccessToast('Success!', 'Requirement submitted successfully.');
            document.getElementById('req-add-form')?.reset();

            // Switch back to list tab and refresh
            reqSwitchTab('req-list-pane');
            reqLoadRequirements(postId || window._reqCurrentPostId);
        } catch (err) {
            window.showErrorToast('Error', 'An error occurred: ' + err.message);
        } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Requirement'; }
        }
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

function completePendingReviewProject(postId) {
    window._completeProjectPostId = postId;

    // Reset form
    document.getElementById('reviewRating').value = '';
    const titleEl = document.getElementById('reviewTitle');
    const descEl  = document.getElementById('reviewDescription');
    const filesEl = document.getElementById('reviewFiles');
    if (titleEl)  titleEl.value = '';
    if (descEl)   descEl.value  = '';
    if (filesEl)  filesEl.value = '';
    document.getElementById('ratingError').style.display = 'none';

    // Reset star display
    document.querySelectorAll('#starRatingInput i').forEach(s => {
        s.className   = 'fa-regular fa-star';
        s.style.color = '';
    });

    // Wire confirm button (clone to prevent duplicate listeners)
    const btn    = document.getElementById('btnConfirmCompleteProject');
    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);
    newBtn.addEventListener('click', submitCompleteProject);

    viewDialogBox('complete-project-popup');
}

function submitCompleteProject() {
    const postId = window._completeProjectPostId;
    const rating = document.getElementById('reviewRating').value;

    if (!rating) {
        document.getElementById('ratingError').style.display = '';
        return;
    }
    document.getElementById('ratingError').style.display = 'none';

    const btn = document.getElementById('btnConfirmCompleteProject');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...'; }

    const fd = new FormData();
    fd.append('rating',      rating);
    fd.append('title',       document.getElementById('reviewTitle')?.value.trim()       || '');
    fd.append('description', document.getElementById('reviewDescription')?.value.trim() || '');
    Array.from(document.getElementById('reviewFiles')?.files || []).forEach(f => fd.append('review_files[]', f));

    fetch(`${window.BASE_URL}/project/complete/${postId}`, {
        method: 'POST',
        body: fd,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeDialogBox('complete-project-popup');
                window.showSuccessToast('Success!', 'Project completed, payment released, and review submitted.');
                loadPosts('pending-review');
                loadPosts('completed');
            } else {
                window.showErrorToast('Error', data.error || 'Failed to complete project.');
            }
        })
        .catch(error => {
            console.error('submitCompleteProject error:', error);
            window.showErrorToast('Error', 'An error occurred while completing the project.');
        })
        .finally(() => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Complete &amp; Submit Review'; }
        });
}

function openReopenProjectModal(postId) {
    window._reopenProjectPostId = postId;
    const reasonInput = document.getElementById('reopenReason');
    const filesInput  = document.getElementById('reopenFiles');
    if (reasonInput) reasonInput.value = '';
    if (filesInput) filesInput.value = '';

    const confirmBtn = document.getElementById('btnConfirmReopenProject');
    if (confirmBtn) {
        const newBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
        newBtn.addEventListener('click', submitReopenProject);
    }

    viewDialogBox('reopen-project-popup');
}

function submitReopenProject() {
    const postId = window._reopenProjectPostId;
    const reason = document.getElementById('reopenReason')?.value.trim() || '';
    const files  = document.getElementById('reopenFiles')?.files || [];

    if (!postId) {
        window.showErrorToast('Error', 'Project not selected.');
        return;
    }

    if (!reason) {
        window.showErrorToast('Error', 'Please provide a reason before moving back to ongoing.');
        document.getElementById('reopenReason')?.focus();
        return;
    }

    const btn = document.getElementById('btnConfirmReopenProject');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    }

    const fd = new FormData();
    fd.append('reason', reason);
    Array.from(files).forEach(file => fd.append('reopen_files[]', file));

    fetch(`${window.BASE_URL}/project/reopen/${postId}`, {
        method: 'POST',
        body: fd,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeDialogBox('reopen-project-popup');
                window.showSuccessToast('Success!', 'Project moved back to ongoing.');
                loadPosts('pending-review');
                loadPosts('ongoing');
            } else {
                window.showErrorToast('Error', data.error || 'Failed to move project back to ongoing.');
            }
        })
        .catch(error => {
            console.error('submitReopenProject error:', error);
            window.showErrorToast('Error', 'An error occurred while saving your request.');
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-rotate-left"></i> Move to Ongoing';
            }
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

// Star rating interaction for complete-project modal
document.addEventListener('DOMContentLoaded', function () {
    const stars     = document.querySelectorAll('#starRatingInput i');
    const ratingIn  = document.getElementById('reviewRating');

    function toHalfStep(value) {
        return Math.max(0.5, Math.min(5, Math.round(value * 2) / 2));
    }

    function getStarValue(star, event) {
        const baseValue = Number(star.dataset.value) || 0;
        if (!event) return baseValue;

        const rect = star.getBoundingClientRect();
        const isLeftHalf = (event.clientX - rect.left) <= rect.width / 2;
        return isLeftHalf ? baseValue - 0.5 : baseValue;
    }

    function renderStars(val) {
        const selected = Number(val) || 0;
        stars.forEach((star, index) => {
            const starValue = index + 1;
            if (selected >= starValue) {
                star.className = 'fa-solid fa-star';
                star.style.color = '#f59e0b';
            } else if (selected >= starValue - 0.5) {
                star.className = 'fa-solid fa-star-half-stroke';
                star.style.color = '#f59e0b';
            } else {
                star.className = 'fa-regular fa-star';
                star.style.color = '#d1d5db';
            }
        });
    }

    stars.forEach(star => {
        star.addEventListener('click', function (event) {
            const val = toHalfStep(getStarValue(this, event));
            if (ratingIn) ratingIn.value = val.toString();
            renderStars(val);
        });

        star.addEventListener('mousemove', function (event) {
            const val = toHalfStep(getStarValue(this, event));
            renderStars(val);
        });

        star.addEventListener('mouseleave', function () {
            const selected = Number(ratingIn?.value) || 0;
            renderStars(selected);
        });
    });

    renderStars(Number(ratingIn?.value) || 0);
});

</script>


</html>



