document.addEventListener('DOMContentLoaded', function () {
    const postDetailsRoot = document.getElementById('postDetailsRoot');
    const postDetailsClose = document.getElementById('postDetailsClose');
    const modalPostTitle = document.getElementById('modalPostTitle');
    const modalPostDescription = document.getElementById('modalPostDescription');
    const modalPostSkills = document.getElementById('modalPostSkills');
    const modalPostKV = document.getElementById('modalPostKV');
    const modalPostDate = document.getElementById('modalPostDate');
    const modalEngagementSection = document.getElementById('modalEngagementSection');
    const modalPostEngagement = document.getElementById('modalPostEngagement');
    const modalDeleteBtn = document.getElementById('modalDeleteBtn');

    const confirmRoot = document.getElementById('confirmDeleteRoot');
    const confirmClose = document.getElementById('confirmDeleteClose');
    const confirmKeep = document.getElementById('confirmKeep');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    // publish confirm
    const publishRoot = document.getElementById('confirmPublishRoot');
    const publishClose = document.getElementById('confirmPublishClose');
    const publishKeep = document.getElementById('confirmPublishKeep');
    const publishBtn = document.getElementById('confirmPublishBtn');

    let activeCard = null; // the card currently viewed

    // Helpers to lock/unlock body scroll without layout shift
    function lockBody() {
        const sbw = window.innerWidth - document.documentElement.clientWidth; // scrollbar width
        if (sbw > 0) {
            document.body.style.paddingRight = sbw + 'px';
        }
        document.body.style.overflow = 'hidden';
    }
    function unlockBody() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    function openPostModal() { postDetailsRoot.classList.remove('deactive'); document.getElementById('postDetailsModal').classList.remove('deactive'); postDetailsRoot.classList.add('active'); lockBody(); }
    function closePostModal() { document.getElementById('postDetailsModal').classList.add('deactive'); postDetailsRoot.classList.remove('active'); postDetailsRoot.classList.add('deactive'); unlockBody(); }
    function openConfirm() { confirmRoot.classList.remove('deactive'); document.getElementById('confirmDelete').classList.remove('deactive'); confirmRoot.classList.add('active'); lockBody(); }
    function closeConfirm() { document.getElementById('confirmDelete').classList.add('deactive'); confirmRoot.classList.remove('active'); confirmRoot.classList.add('deactive'); unlockBody(); }
    function openPublishConfirm() { publishRoot.classList.remove('deactive'); document.getElementById('confirmPublish').classList.remove('deactive'); publishRoot.classList.add('active'); lockBody(); }
    function closePublishConfirm() { document.getElementById('confirmPublish').classList.add('deactive'); publishRoot.classList.remove('active'); publishRoot.classList.add('deactive'); unlockBody(); }

    // Populate modal from a card element
    function populateFromCard(card) {
        activeCard = card;
        const title = card.querySelector('.post-title') ? card.querySelector('.post-title').textContent.trim() : '';
        const desc = card.querySelector('.post-description') ? card.querySelector('.post-description').textContent.trim() : '';
        modalPostTitle.textContent = title;
        modalPostDescription.textContent = desc;
        // meta date
        const dateText = card.querySelector('.post-date span') ? card.querySelector('.post-date span').textContent.trim() : '—';
        if (modalPostDate) modalPostDate.textContent = dateText;
        // skills
        modalPostSkills.innerHTML = '';
        const skills = card.querySelectorAll('.skill-tag');
        skills.forEach(s => { const span = document.createElement('span'); span.className = 'skill-tag'; span.textContent = s.textContent.trim(); modalPostSkills.appendChild(span); });
        // details (budget, proposals, level, duration) in key-value cards
        modalPostKV.innerHTML = '';
        const details = card.querySelectorAll('.post-footer .detail-item');
        details.forEach(d => {
            const label = d.querySelector('.detail-label') ? d.querySelector('.detail-label').textContent.trim() : '';
            const value = d.querySelector('.detail-value') ? d.querySelector('.detail-value').textContent.trim() : '';
            if (label) {
                const el = document.createElement('div');
                el.className = 'kv';
                el.innerHTML = `<div class="kv-label">${label}</div><div class="kv-value">${value}</div>`;
                modalPostKV.appendChild(el);
            }
        });
        // engagement stats
        modalPostEngagement.innerHTML = '';
        const engagement = card.querySelectorAll('.engagement-stats .stat-item');
        if (engagement.length > 0) {
            modalEngagementSection && (modalEngagementSection.style.display = '');
            engagement.forEach(e => { const el = document.createElement('div'); el.style.display = 'flex'; el.style.alignItems = 'center'; el.style.gap = '6px'; el.innerHTML = e.innerHTML; modalPostEngagement.appendChild(el); });
        } else {
            modalEngagementSection && (modalEngagementSection.style.display = 'none');
        }
    }

    // helper to attach handlers to one card (used after publish transform)
    function bindCardActions(card) {
        const viewBtn = Array.from(card.querySelectorAll('.btn-view')).find(b => b.textContent.trim().toLowerCase().includes('view'));
        const editBtn = card.querySelector('.btn-edit');
        const deleteBtn = card.querySelector('.btn-delete');
        if (viewBtn) { viewBtn.addEventListener('click', (e) => { e.preventDefault(); populateFromCard(card); modalDeleteBtn && (modalDeleteBtn.style.display = ''); openPostModal(); }); }
        if (editBtn) {
            editBtn.addEventListener('click', (e) => {
                e.preventDefault(); const form = document.getElementById('create-post-form'); if (form) { form.querySelector('#post-title').value = card.querySelector('.post-title') ? card.querySelector('.post-title').textContent.trim() : ''; form.querySelector('#post-description').value = card.querySelector('.post-description') ? card.querySelector('.post-description').textContent.trim() : ''; const skillsInput = form.querySelector('#post-skills'); const skills = Array.from(card.querySelectorAll('.skill-tag')).map(s => s.textContent.trim()).join(', '); if (skillsInput) skillsInput.value = skills; const priceEl = Array.from(card.querySelectorAll('.post-footer .detail-item')).find(d => /Budget/i.test(d.textContent)); if (priceEl) { const val = priceEl.querySelector('.detail-value') ? priceEl.querySelector('.detail-value').textContent.trim().replace(/[^0-9\.]/g, '') : ''; const priceField = form.querySelector('#requesting-price'); if (priceField) priceField.value = val; } }
                // toggle action buttons for EDIT mode: show only Save Post + Cancel
                const root = document.getElementsByClassName('create-post-pop-up')[0];
                if (root) {
                    const titleEl = root.querySelector('.pop-up-title'); if (titleEl) titleEl.textContent = 'Edit Post';
                    const btnSaveDraft = root.querySelector('[data-role="save-draft"]');
                    const btnPublish = root.querySelector('[data-role="publish"]');
                    const btnSavePost = root.querySelector('[data-role="save-post"]');
                    if (btnSaveDraft) btnSaveDraft.style.display = 'none';
                    if (btnPublish) btnPublish.style.display = 'none';
                    if (btnSavePost) {
                        btnSavePost.style.display = '';
                        const isRepost = (editBtn.textContent || '').toLowerCase().includes('repost');
                        // Change label to 'Repost' when action is Repost, otherwise ensure 'Save Post'
                        if (isRepost) {
                            btnSavePost.innerHTML = btnSavePost.innerHTML.replace(/Save\s*Post/i, 'Repost');
                        } else {
                            btnSavePost.innerHTML = btnSavePost.innerHTML.replace(/Repost/i, 'Save Post');
                        }
                    }
                    // open modal
                    root.classList.remove('deactive');
                    root.querySelector('.pop-up').classList.remove('deactive');
                    lockBody();
                }
            });
        }
        if (deleteBtn) { deleteBtn.addEventListener('click', (e) => { e.preventDefault(); populateFromCard(card); openConfirm(); }); }
    }

    // Attach view/edit/delete handlers for all sections
    function wireSection(sectionSelector) {
        document.querySelectorAll(sectionSelector + ' .search-item').forEach(card => {
            // View/Edit/Delete for non-draft or view-labeled buttons
            bindCardActions(card);
            // Draft Publish button handling
            if (sectionSelector.indexOf('draft-posts') !== -1) {
                const publishBtnCand = Array.from(card.querySelectorAll('.btn-view')).find(b => b.textContent.trim().toLowerCase().includes('publish'));
                if (publishBtnCand) {
                    publishBtnCand.addEventListener('click', (e) => { e.preventDefault(); activeCard = card; openPublishConfirm(); });
                }
            }
        });
    }

    // Wire all three sections
    ['.active-posts', '.draft-posts', '.expired-posts'].forEach(s => wireSection(s));

    // Create button -> toggle to CREATE mode: show Save Draft + Publish, hide Save Post
    const createBtn = document.querySelector('.search-header .post-job-btn');
    if (createBtn) {
        createBtn.addEventListener('click', function () {
            const root = document.getElementsByClassName('create-post-pop-up')[0];
            if (root) {
                const titleEl = root.querySelector('.pop-up-title'); if (titleEl) titleEl.textContent = 'Create a New Post';
                const btnSaveDraft = root.querySelector('[data-role="save-draft"]');
                const btnPublish = root.querySelector('[data-role="publish"]');
                const btnSavePost = root.querySelector('[data-role="save-post"]');
                if (btnSaveDraft) btnSaveDraft.style.display = '';
                if (btnPublish) btnPublish.style.display = '';
                if (btnSavePost) btnSavePost.style.display = 'none';
                // Optionally clear form for new post
                const form = document.getElementById('create-post-form');
                if (form) { form.reset(); }
            }
        });
    }

    // Save Post button closes modal (demo; hook backend here if needed)
    const cpRoot = document.getElementsByClassName('create-post-pop-up')[0];
    if (cpRoot) {
        const btnSavePost = cpRoot.querySelector('[data-role="save-post"]');
        btnSavePost && btnSavePost.addEventListener('click', function () {
            cpRoot.classList.add('deactive');
            const pop = cpRoot.querySelector('.pop-up');
            pop && pop.classList.add('deactive');
            unlockBody();
        });
    }

    // Ensure body lock state stays in sync when the create-post modal is toggled
    // by generic handlers in other scripts (which don't manage scroll locking).
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('#create-post-pop-up');
        if (!trigger) return;
        // Let the other script toggle classes first, then correct the body state.
        setTimeout(() => {
            const container = document.getElementsByClassName('create-post-pop-up')[0];
            if (!container) return;
            if (container.classList.contains('deactive')) {
                // Modal just closed
                unlockBody();
            } else {
                // Modal just opened
                lockBody();
            }
        }, 0);
    });

    // Confirm modal actions
    confirmClose && confirmClose.addEventListener('click', closeConfirm);
    confirmRoot && confirmRoot.addEventListener('click', (e) => { if (e.target === confirmRoot) closeConfirm(); });
    confirmKeep && confirmKeep.addEventListener('click', closeConfirm);
    confirmDeleteBtn && confirmDeleteBtn.addEventListener('click', () => {
        if (activeCard && activeCard.parentNode) {
            activeCard.parentNode.removeChild(activeCard);
        }
        closeConfirm();
        closePostModal();
    });

    // Post Details modal Delete button -> open confirm
    modalDeleteBtn && modalDeleteBtn.addEventListener('click', (e) => { e.preventDefault(); openConfirm(); });

    // Publish confirm actions
    publishClose && publishClose.addEventListener('click', closePublishConfirm);
    publishRoot && publishRoot.addEventListener('click', (e) => { if (e.target === publishRoot) closePublishConfirm(); });
    publishKeep && publishKeep.addEventListener('click', closePublishConfirm);
    publishBtn && publishBtn.addEventListener('click', () => {
        if (!activeCard) { closePublishConfirm(); return; }
        // Update header date
        const dateSpan = activeCard.querySelector('.post-date span');
        if (dateSpan) dateSpan.textContent = 'Posted just now';
        // Update buttons: change Publish -> View, Continue -> Edit
        const actions = activeCard.querySelector('.post-actions');
        if (actions) {
            const edit = actions.querySelector('.btn-edit');
            if (edit) { edit.innerHTML = '<i class="fas fa-edit"></i> Edit'; }
            const pub = Array.from(actions.querySelectorAll('.btn-view')).find(b => b.textContent.trim().toLowerCase().includes('publish'));
            if (pub) { pub.innerHTML = '<i class="fas fa-eye"></i> View'; }
        }
        // Move card to Active Posts list
        const activeList = document.querySelector('.active-posts .item-list');
        if (activeList && activeCard.parentNode) { activeList.appendChild(activeCard); }
        // Rebind actions for this card as an active card
        bindCardActions(activeCard);
        closePublishConfirm();
    });

    // Post details modal close handlers
    postDetailsClose && postDetailsClose.addEventListener('click', closePostModal);
    postDetailsRoot && postDetailsRoot.addEventListener('click', (e) => { if (e.target === postDetailsRoot) closePostModal(); });
    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closePostModal(); closeConfirm(); } });
});
// Override global togglePopUp to avoid errors when clicking buttons without matching popups
document.addEventListener('DOMContentLoaded', function () {
    window.togglePopUp = function (popUpId) {
        if (!popUpId) return; // ignore empty ids
        const container = document.getElementsByClassName(popUpId)[0];
        if (!container) return; // no matching popup on this page
        const pop = container.querySelector('.pop-up');
        const goingActive = container.classList.contains('deactive');
        container.classList.toggle('deactive');
        if (pop) pop.classList.toggle('deactive');
        // lock body scroll when opening, unlock when closing
        if (goingActive) {
            const sbw = window.innerWidth - document.documentElement.clientWidth;
            if (sbw > 0) { document.body.style.paddingRight = sbw + 'px'; }
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    };
});

// Tab switching functionality for client-side job management
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.buttons');
    const sections = document.querySelectorAll('.requests-section');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove active class from all tabs and sections
            tabs.forEach(t => t.classList.remove('active'));
            sections.forEach(s => {
                s.classList.remove('active');
                s.style.display = 'none';
            });

            // Add active class to clicked tab
            this.classList.add('active');

            // Show corresponding section based on tab ID
            let sectionClass = '';
            if (this.id === 'active-posts') {
                sectionClass = 'active-posts';
            } else if (this.id === 'draft-posts') {
                sectionClass = 'draft-posts';
            } else if (this.id === 'expired-posts') {
                sectionClass = 'expired-posts';
            }

            const section = document.querySelector('.' + sectionClass);
            if (section) {
                section.classList.add('active');
                section.style.display = 'block';
            }
        });
    });
});