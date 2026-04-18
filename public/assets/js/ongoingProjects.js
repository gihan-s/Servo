/**
 * Ongoing Projects Manager
 * Fetches and renders ongoing projects for the provider with pagination,
 * progress updates, and a detail modal.
 */
class OngoingProjectsManager {
    constructor(config = {}) {
        this.apiEndpoint      = config.apiEndpoint      || `${BASE_URL || ''}/provider/ongoing-projects`;
        this.updateEndpoint   = config.updateEndpoint   || `${BASE_URL || ''}/project/update-progress`;
        this.itemListSelector = config.itemListSelector || '#section-progress .item-list';
        this.paginationSelector = config.paginationSelector || '#section-progress .pagination';
        this.currentPage  = 1;
        this.totalPages   = 1;
        this.itemsPerPage = config.itemsPerPage || 10;
        this.isLoading    = false;
        this.currentProject = null;
        this.currentData    = [];
    }

    /* ------------------------------------------------------------------ */
    /*  Data fetching                                                       */
    /* ------------------------------------------------------------------ */

    async fetchProjects(page = 1) {
        if (this.isLoading) return null;
        this.isLoading = true;

        try {
            const url = new URL(this.apiEndpoint, window.location.origin);
            url.searchParams.set('page',  page);
            url.searchParams.set('limit', this.itemsPerPage);

            const response = await fetch(url.toString());
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            if (!data.success) {
                console.error('API error:', data.message);
                return null;
            }
            return data;
        } catch (err) {
            console.error('OngoingProjectsManager fetch error:', err);
            return null;
        } finally {
            this.isLoading = false;
        }
    }

    async loadProjects(page = 1) {
        this.showLoading();
        const data = await this.fetchProjects(page);

        if (!data) {
            this.showError('Failed to load projects. Please try again.');
            return;
        }

        await new Promise(r => setTimeout(r, 300));

        this.currentPage = data.pagination.current_page;
        this.totalPages  = data.pagination.total_pages;
        this.currentData = data.data;

        this.renderProjects(data.data);
        this.renderPagination(this.currentPage, this.totalPages);
        this.attachCardListeners(data.data);
    }

    /* ------------------------------------------------------------------ */
    /*  Rendering                                                           */
    /* ------------------------------------------------------------------ */

    renderProjects(projects) {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;

        if (!projects.length) {
            container.innerHTML = `
                <section class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <h2>No ongoing projects right now</h2>
                    <p>Projects will appear here once a request has been accepted and started.</p>
                </section>`;
            return;
        }

        container.innerHTML = projects.map(p => this.createCardHTML(p)).join('');
    }

    createCardHTML(project) {
        const progress   = Math.min(100, Math.max(0, project.progress || 0));
        const barColor   = progress >= 75 ? '#22c55e' : progress >= 40 ? '#f59e0b' : '#3b82f6';
        const postTypeLabel = project.post_type === 'Direct' ? 'Direct Request' : 'Bid Request';
        const submitReviewBtn = progress === 100
            ? `<button class="btn-primary btn-submit-review-ongoing"
                            data-post-id="${project.Post_ID}"
                            title="Submit for Review">
                        <i class="fa-solid fa-paper-plane"></i> Submit Review
                    </button>`
            : '';

        return `
        <div class="search-item" data-status="progress" data-post-id="${project.Post_ID}" data-project-id="${project.Project_ID}">
            <!-- Header row -->
            <div class="request-header">
                <div class="request-client-section">
                    <img src="/file/user-files/${this.escHtml(project.client_avatar)}"
                         alt="${this.escHtml(project.client_name)}"
                         class="request-avatar"
                         >
                    <div class="request-client-info">
                        <div class="request-client-name">${this.escHtml(project.client_name)}</div>
                        <div class="request-client-location">
                            <i class="fa-solid fa-calendar-days" style="color:#9ca3af;"></i>
                            Started ${this.escHtml(project.started_date)}
                        </div>
                    </div>
                </div>
                <div class="request-actions">
                    <button class="btn-outline btn-view-ongoing"
                            data-post-id="${project.Post_ID}"
                            title="View Details">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <a href="${BASE_URL || ''}/messages?new=${project.Client_ID}" class="message-link">
                        <button class="btn-outline" title="Message Client">
                            <i class="fa-solid fa-comments"></i>
                        </button>
                    </a>
                    <button class="btn-outline btn-req-ongoing"
                            data-post-id="${project.Post_ID}"
                            title="View Requirements">
                        <i class="fa-solid fa-list-check"></i> Requirements
                    </button>
                    <button class="btn-primary btn-update-ongoing"
                            data-post-id="${project.Post_ID}"
                            data-project-id="${project.Project_ID}"
                            data-progress="${progress}"
                            title="Update Progress">
                        <i class="fa-solid fa-arrow-up-right-dots"></i> Update
                    </button>
                    ${submitReviewBtn}
                </div>
            </div>

            <!-- Title -->
            <div class="request-title">${this.escHtml(project.title)}</div>

            <!-- Description -->
            <div class="request-description">${this.escHtml(project.description)}</div>

            <!-- Progress bar -->
            <div class="ongoing-progress-wrap">
                <div class="ongoing-progress-label">
                    <span>Progress</span>
                    <span class="ongoing-progress-pct" style="color:${barColor}; font-weight:700;">${progress}%</span>
                </div>
                <div class="ongoing-progress-track">
                    <div class="ongoing-progress-fill"
                         style="width:${progress}%; background:${barColor};"></div>
                </div>
            </div>

            <!-- Details grid -->
            <div class="request-details">
                <div class="request-detail-item">
                    <span class="request-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                    <span class="request-detail-value">${this.escHtml(project.budget_display)}</span>
                </div>
                <div class="request-detail-item">
                    <span class="request-detail-label"><i class="fa-solid fa-tag"></i> Category</span>
                    <span class="request-detail-value">${this.escHtml(project.category)}</span>
                </div>
                <div class="request-detail-item">
                    <span class="request-detail-label"><i class="fa-solid fa-calendar"></i> Est. Date</span>
                    <span class="request-detail-value">${this.escHtml(project.timeline)}</span>
                </div>
                <div class="request-detail-item">
                    <span class="request-detail-label"><i class="fa-solid fa-layer-group"></i> Level</span>
                    <span class="request-detail-value">${this.escHtml(project.level)}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="request-footer">
                <span class="request-time">Started ${this.escHtml(project.started_date)}</span>
                <span class="status-chip status-progress">
                    <i class="fa-solid fa-spinner"></i> ${this.escHtml(postTypeLabel)}
                </span>
            </div>
        </div>`;
    }

    /* ------------------------------------------------------------------ */
    /*  Pagination                                                          */
    /* ------------------------------------------------------------------ */

    renderPagination(currentPage, totalPages) {
        const container = document.querySelector(this.paginationSelector);
        if (!container) return;

        if (totalPages <= 1) { container.innerHTML = ''; return; }

        const maxShow  = 5;
        let startPage  = Math.max(1, currentPage - Math.floor(maxShow / 2));
        let endPage    = Math.min(totalPages, startPage + maxShow - 1);
        if (endPage - startPage + 1 < maxShow) startPage = Math.max(1, endPage - maxShow + 1);

        let html = `<button class="page-btn prev" ${currentPage === 1 ? 'disabled' : ''}><i class="fa-solid fa-chevron-left"></i></button>`;

        if (startPage > 1) {
            html += `<button class="page-btn" data-page="1">1</button>`;
            if (startPage > 2) html += `<span class="ellipsis">...</span>`;
        }

        for (let i = startPage; i <= endPage; i++) {
            html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += `<span class="ellipsis">...</span>`;
            html += `<button class="page-btn" data-page="${totalPages}">${totalPages}</button>`;
        }

        html += `<button class="page-btn next" ${currentPage === totalPages ? 'disabled' : ''}><i class="fa-solid fa-chevron-right"></i></button>`;
        container.innerHTML = html;

        container.querySelectorAll('.page-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                this.loadProjects(parseInt(btn.dataset.page, 10));
            });
        });

        const prevBtn = container.querySelector('.page-btn.prev');
        if (prevBtn && currentPage > 1) {
            prevBtn.addEventListener('click', e => { e.preventDefault(); this.loadProjects(currentPage - 1); });
        }

        const nextBtn = container.querySelector('.page-btn.next');
        if (nextBtn && currentPage < totalPages) {
            nextBtn.addEventListener('click', e => { e.preventDefault(); this.loadProjects(currentPage + 1); });
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Card event listeners                                                */
    /* ------------------------------------------------------------------ */

    attachCardListeners(projects) {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;

        // View button
        container.querySelectorAll('.btn-view-ongoing').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId  = parseInt(btn.dataset.postId, 10);
                const project = projects.find(p => p.Post_ID === postId);
                if (project) this.openDetailModal(project);
            });
        });

        // Update Progress button
        container.querySelectorAll('.btn-update-ongoing').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId  = parseInt(btn.dataset.postId, 10);
                const project = projects.find(p => p.Post_ID === postId);
                if (project) {
                    this.currentProject = project;
                    this.openProgressModal(project);
                }
            });
        });

        // Requirements button
        container.querySelectorAll('.btn-req-ongoing').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId = parseInt(btn.dataset.postId, 10);
                if (typeof openProviderRequirementsModal === 'function') {
                    openProviderRequirementsModal(postId);
                }
            });
        });

        // Submit for Review button (shown only at 100%)
        container.querySelectorAll('.btn-submit-review-ongoing').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId  = parseInt(btn.dataset.postId, 10);
                const project = projects.find(p => p.Post_ID === postId);
                if (project) {
                    this.currentProject = project;
                    this.openSubmitForReviewModal(postId);
                }
            });
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Detail Modal                                                        */
    /* ------------------------------------------------------------------ */

    openDetailModal(project) {
        this.currentProject = project;
        if (typeof ProjectDetailView !== 'undefined') {
            ProjectDetailView.open(project.Post_ID, { role: 'provider' });
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Progress Update Modal                                               */
    /* ------------------------------------------------------------------ */

    openProgressModal(project) {
        const modal = document.getElementById('progressModalRoot');
        if (!modal) return;

        const clientEl = document.getElementById('progressClient');
        const titleEl  = document.getElementById('progressTitle');
        const slider   = document.getElementById('progressPercent');
        const sliderLbl = document.getElementById('progressPercentValue');

        if (clientEl) clientEl.textContent = project.client_name;
        if (titleEl)  titleEl.textContent  = project.title;
        if (slider) {
            slider.value = project.progress;
            if (sliderLbl) sliderLbl.textContent = `${project.progress}%`;
        }

        // Reset all fields
        const updateTitle = document.getElementById('progressUpdateTitle');
        if (updateTitle) updateTitle.value = '';
        document.getElementById('hoursWorked').value         = '';
        document.getElementById('progressDescription').value = '';
        const filesInput = document.getElementById('progressFiles');
        if (filesInput) filesInput.value = '';

        // Wire submit
        const btnSubmit = document.getElementById('btnUpdateProgress');
        if (btnSubmit) {
            btnSubmit.onclick = () => this.submitProgressUpdate(project.Post_ID, project.Project_ID);
        }

        this.openModal(modal);
    }

    async submitProgressUpdate(postId) {
        const titleInput = document.getElementById('progressUpdateTitle');
        const title      = titleInput ? titleInput.value.trim() : '';

        if (!title) {
            titleInput && titleInput.focus();
            if (typeof showToast === 'function') {
                showToast('error', 'Please enter an update title');
            } else {
                alert('Please enter an update title');
            }
            return;
        }

        const slider      = document.getElementById('progressPercent');
        const descEl      = document.getElementById('progressDescription');
        const hoursEl     = document.getElementById('hoursWorked');
        const filesInput  = document.getElementById('progressFiles');

        const fd = new FormData();
        fd.append('title',       title);
        fd.append('progress',    slider ? slider.value : '0');
        fd.append('description', descEl  ? descEl.value.trim() : '');
        fd.append('worked_hours', hoursEl ? hoursEl.value : '0');

        if (filesInput && filesInput.files.length) {
            Array.from(filesInput.files).forEach(f => fd.append('update_files[]', f));
        }

        const btn = document.getElementById('btnUpdateProgress');
        if (btn) { btn.disabled = true; btn.textContent = 'Saving…'; }

        try {
            const response = await fetch(`${this.updateEndpoint}/${postId}`, {
                method: 'POST',
                body: fd,   // multipart/form-data – no Content-Type header needed
            });

            const data = await response.json();

            if (!data.success) {
                if (typeof showToast === 'function') {
                    showToast('error', data.error || 'Failed to save update');
                } else {
                    alert('Error: ' + (data.error || 'Failed to save update'));
                }
                return;
            }

            this.closeModal(document.getElementById('progressModalRoot'));

            if (typeof showToast === 'function') {
                showToast('success', 'Progress updated successfully');
            }

            // If progress hit 100%, prompt to submit for review
            const progressValue = parseInt(slider ? slider.value : '0', 10);
            if (progressValue >= 100) {
                this.openSubmitForReviewModal(postId);
            }

            await this.loadProjects(this.currentPage);
        } catch (err) {
            console.error('Progress update error:', err);
            alert('An error occurred while updating progress.');
        } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-arrow-up-right-dots"></i> Save Update'; }
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Submit for Review Modal                                             */
    /* ------------------------------------------------------------------ */

    openSubmitForReviewModal(postId) {
        const modal = document.getElementById('submitModalRoot');
        if (!modal) return;

        this._reviewPostId = postId;

        // Pre-fill project info
        const project = this.currentProject || (this.currentData || []).find(p => p.Post_ID === postId);
        if (project) {
            const clientEl = document.getElementById('submitClient');
            const titleEl  = document.getElementById('submitTitle');
            if (clientEl) clientEl.textContent = project.client_name;
            if (titleEl)  titleEl.textContent  = project.title;
        }

        // Reset fields
        const descEl  = document.getElementById('submitDescription');
        const filesEl = document.getElementById('submitFiles');
        if (descEl)  descEl.value = '';
        if (filesEl) filesEl.value = '';

        this.openModal(modal);
    }

    async submitForReview() {
        const postId = this._reviewPostId;
        if (!postId) return;

        const descEl  = document.getElementById('submitDescription');
        const filesEl = document.getElementById('submitFiles');

        const fd = new FormData();
        fd.append('description', descEl ? descEl.value.trim() : '');

        if (filesEl && filesEl.files.length) {
            Array.from(filesEl.files).forEach(f => fd.append('deliverable_files[]', f));
        }

        const btn = document.getElementById('btnSubmitForReview');
        if (btn) { btn.disabled = true; btn.textContent = 'Submitting…'; }

        try {
            const response = await fetch(`${BASE_URL || ''}/project/submit-review/${postId}`, {
                method: 'POST',
                body: fd,
            });

            const data = await response.json();

            if (!data.success) {
                if (typeof showToast === 'function') {
                    showToast('error', data.error || 'Failed to submit for review');
                } else {
                    alert('Error: ' + (data.error || 'Failed to submit for review'));
                }
                return;
            }

            this.closeModal(document.getElementById('submitModalRoot'));

            if (typeof showToast === 'function') {
                showToast('success', 'Project submitted for review!');
            }

            await this.loadProjects(this.currentPage);
        } catch (err) {
            console.error('Submit for review error:', err);
            alert('An error occurred while submitting for review.');
        } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit for Review'; }
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    showLoading() {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;
        container.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading projects...</p>
            </div>`;
    }

    showError(message) {
        const container = document.querySelector(this.itemListSelector);
        if (container) {
            container.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>${this.escHtml(message)}</p>
                    <button class="retry-btn" onclick="window.ongoingProjectsManager && window.ongoingProjectsManager.loadProjects(1)">Retry</button>
                </div>`;
        }
    }

    openModal(modal) {
        if (modal) { modal.classList.remove('deactive'); document.body.style.overflow = 'hidden'; }
    }

    closeModal(modal) {
        if (modal) { modal.classList.add('deactive'); document.body.style.overflow = ''; }
    }

    escHtml(text) {
        const d = document.createElement('div');
        d.textContent = String(text ?? '');
        return d.innerHTML;
    }

    /* ------------------------------------------------------------------ */
    /*  Init                                                                */
    /* ------------------------------------------------------------------ */

    init() {
        this.setupModalListeners();
        return this.loadProjects(1);
    }

    setupModalListeners() {
        // Detail modal
        const detailRoot  = document.getElementById('ongoingDetailModalRoot');
        const detailClose = document.getElementById('ongoingDetailModalClose');
        if (detailRoot && detailClose) {
            detailClose.addEventListener('click', () => this.closeModal(detailRoot));
            detailRoot.addEventListener('click', e => { if (e.target === detailRoot) this.closeModal(detailRoot); });
        }

        // Progress modal close (existing modal in view)
        const progressModalClose = document.getElementById('progressModalClose');
        const progressModalRoot  = document.getElementById('progressModalRoot');
        if (progressModalRoot && progressModalClose) {
            progressModalClose.addEventListener('click', () => this.closeModal(progressModalRoot));
            progressModalRoot.addEventListener('click', e => { if (e.target === progressModalRoot) this.closeModal(progressModalRoot); });
        }

        // Slider live label
        const slider  = document.getElementById('progressPercent');
        const sliderLbl = document.getElementById('progressPercentValue');
        if (slider && sliderLbl) {
            slider.addEventListener('input', () => { sliderLbl.textContent = `${slider.value}%`; });
        }

        // Submit for Review modal
        const submitModalRoot  = document.getElementById('submitModalRoot');
        const submitModalClose = document.getElementById('submitModalClose');
        if (submitModalRoot) {
            if (submitModalClose) {
                submitModalClose.addEventListener('click', () => this.closeModal(submitModalRoot));
            }
            submitModalRoot.addEventListener('click', e => { if (e.target === submitModalRoot) this.closeModal(submitModalRoot); });
        }
        const btnSubmitForReview = document.getElementById('btnSubmitForReview');
        if (btnSubmitForReview) {
            btnSubmitForReview.addEventListener('click', () => this.submitForReview());
        }

        // Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                if (detailRoot) this.closeModal(detailRoot);
                if (progressModalRoot) this.closeModal(progressModalRoot);
                if (submitModalRoot) this.closeModal(submitModalRoot);
            }
        });
    }
}

/* ------------------------------------------------------------------ */
/*  Bootstrap on DOM ready                                              */
/* ------------------------------------------------------------------ */
document.addEventListener('DOMContentLoaded', function () {
    const manager = new OngoingProjectsManager();
    manager.init().catch(err => console.error('OngoingProjectsManager init error:', err));
    window.ongoingProjectsManager = manager;
});
