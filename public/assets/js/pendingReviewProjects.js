/**
 * Pending Review Projects Manager
 * Fetches and renders projects awaiting client approval for the provider.
 * The provider has already submitted these — they are read-only (waiting for client).
 */
class PendingReviewProjectsManager {
    constructor(config = {}) {
        this.apiEndpoint      = config.apiEndpoint      || `${BASE_URL || ''}/provider/pending-review-projects`;
        this.itemListSelector = config.itemListSelector || '#section-review .item-list';
        this.paginationSelector = config.paginationSelector || '#section-review .pagination';
        this.currentPage  = 1;
        this.totalPages   = 1;
        this.itemsPerPage = config.itemsPerPage || 10;
        this.isLoading    = false;
        this.currentData  = [];
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
                console.error('PendingReviewProjectsManager API error:', data.message);
                return null;
            }
            return data;
        } catch (err) {
            console.error('PendingReviewProjectsManager fetch error:', err);
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
                    <i class="fas fa-hourglass-half"></i>
                    <h2>No pending reviews right now</h2>
                    <p>Projects you've submitted for review will appear here while awaiting client approval.</p>
                </section>`;
            return;
        }

        container.innerHTML = projects.map(p => this.createCardHTML(p)).join('');
    }

    createCardHTML(project) {
        const progress      = Math.min(100, Math.max(0, project.progress || 0));
        const postTypeLabel = project.post_type === 'Direct' ? 'Direct Request' : 'Bid Request';

        return `
        <div class="search-item" data-status="review" data-post-id="${project.Post_ID}" data-project-id="${project.Project_ID}">
            <!-- Header row -->
            <div class="request-header">
                <div class="request-client-section">
                    <img src="${this.escHtml(project.client_avatar)}"
                         alt="${this.escHtml(project.client_name)}"
                         class="request-avatar"
                         onerror="this.style.display='none'">
                    <div class="request-client-info">
                        <div class="request-client-name">${this.escHtml(project.client_name)}</div>
                        <div class="request-client-location">
                            <i class="fa-solid fa-paper-plane" style="color:#f59e0b;"></i>
                            Submitted ${this.escHtml(project.submitted_date)}
                        </div>
                    </div>
                </div>
                <div class="request-actions">
                    <button class="btn-outline btn-view-review"
                            data-post-id="${project.Post_ID}"
                            title="View Details">
                        <i class="fa-solid fa-eye"></i> View
                    </button>
                    <a href="${BASE_URL || ''}/messages?new=${project.Client_ID}" class="message-link">
                        <button class="btn-outline" title="Message Client">
                            <i class="fa-solid fa-comments"></i> Message
                        </button>
                    </a>
                    <button class="btn-outline btn-req-review"
                            data-post-id="${project.Post_ID}"
                            title="View Requirements">
                        <i class="fa-solid fa-list-check"></i> Requirements
                    </button>
                </div>
            </div>

            <!-- Title -->
            <div class="request-title">${this.escHtml(project.title)}</div>

            <!-- Description -->
            <div class="request-description">${this.escHtml(project.description)}</div>

            <!-- Progress bar (amber — awaiting review) -->
            <div class="ongoing-progress-wrap">
                <div class="ongoing-progress-label">
                    <span>Progress</span>
                    <span class="ongoing-progress-pct" style="color:#f59e0b; font-weight:700;">${progress}%</span>
                </div>
                <div class="ongoing-progress-track">
                    <div class="ongoing-progress-fill"
                         style="width:${progress}%; background:#f59e0b;"></div>
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
                <span class="request-time">Submitted ${this.escHtml(project.submitted_date)}</span>
                <span class="status-chip status-review-chip">
                    <i class="fa-solid fa-hourglass-half"></i> Awaiting Client Review
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

        // View button — opens ProjectDetailView if available
        container.querySelectorAll('.btn-view-review').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId = parseInt(btn.dataset.postId, 10);
                if (typeof ProjectDetailView !== 'undefined' && ProjectDetailView.open) {
                    ProjectDetailView.open(postId);
                }
            });
        });

        // Requirements button
        container.querySelectorAll('.btn-req-review').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId = parseInt(btn.dataset.postId, 10);
                if (typeof openProviderRequirementsModal === 'function') {
                    openProviderRequirementsModal(postId);
                }
            });
        });
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
                    <button class="retry-btn" onclick="window.pendingReviewProjectsManager && window.pendingReviewProjectsManager.loadProjects(1)">Retry</button>
                </div>`;
        }
    }

    escHtml(text) {
        const d = document.createElement('div');
        d.textContent = String(text ?? '');
        return d.innerHTML;
    }

    init() {
        return this.loadProjects(1);
    }
}

/* ------------------------------------------------------------------ */
/*  Bootstrap on DOM ready                                              */
/* ------------------------------------------------------------------ */
document.addEventListener('DOMContentLoaded', function () {
    const manager = new PendingReviewProjectsManager();
    manager.init().catch(err => console.error('PendingReviewProjectsManager init error:', err));
    window.pendingReviewProjectsManager = manager;
});
