/**
 * Completed Projects Manager
 * Fetches and renders completed projects for the provider.
 * Provider can leave a review for the client; after reviewing, both reviews are visible.
 */
class CompletedProjectsManager {
    constructor(config = {}) {
        this.apiEndpoint      = config.apiEndpoint      || `${BASE_URL || ''}/provider/completed-projects`;
        this.reviewEndpoint   = config.reviewEndpoint   || `${BASE_URL || ''}/project/provider-review`;
        this.itemListSelector = config.itemListSelector || '#section-completed .item-list';
        this.paginationSelector = config.paginationSelector || '#section-completed .pagination';
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
                console.error('CompletedProjectsManager API error:', data.message);
                return null;
            }
            return data;
        } catch (err) {
            console.error('CompletedProjectsManager fetch error:', err);
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
                    <i class="fas fa-check-circle"></i>
                    <h2>No completed projects yet</h2>
                    <p>Projects that have been fully completed will appear here.</p>
                </section>`;
            return;
        }

        container.innerHTML = projects.map(p => this.createCardHTML(p)).join('');
    }

    createCardHTML(project) {
        const progress      = 100;
        const postTypeLabel = project.post_type === 'Direct' ? 'Direct Request' : 'Bid Request';
        const reviewed      = project.provider_reviewed;

        // Reviews section — only shown if provider has reviewed
        let reviewsHTML = '';
        if (reviewed && project.reviews && project.reviews.length) {
            reviewsHTML = `<div class="completed-reviews-section">
                <div class="completed-reviews-title"><i class="fa-solid fa-star"></i> Reviews</div>
                ${project.reviews.map(r => this.createReviewHTML(r)).join('')}
            </div>`;
        }

        // Action: Leave Review button if not yet reviewed
        const reviewBtnHTML = !reviewed
            ? `<button class="btn-outline btn-leave-review" data-post-id="${project.Post_ID}" title="Leave Review">
                   <i class="fa-solid fa-star"></i> Leave Review
               </button>`
            : '';

        return `
        <div class="search-item" data-status="complete" data-post-id="${project.Post_ID}" data-project-id="${project.Project_ID}">
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
                            <i class="fa-solid fa-circle-check" style="color:#16a34a;"></i>
                            Completed ${this.escHtml(project.completed_date)}
                        </div>
                    </div>
                </div>
                <div class="request-actions">
                    <button class="btn-outline btn-view-completed"
                            data-post-id="${project.Post_ID}"
                            title="View Details">
                        <i class="fa-solid fa-eye"></i> View
                    </button>
                    <a href="${BASE_URL || ''}/messages?new=${project.Client_ID}" class="message-link">
                        <button class="btn-outline" title="Message Client">
                            <i class="fa-solid fa-comments"></i> Message
                        </button>
                    </a>
                    ${reviewBtnHTML}
                </div>
            </div>

            <!-- Title -->
            <div class="request-title">${this.escHtml(project.title)}</div>

            <!-- Description -->
            <div class="request-description">${this.escHtml(project.description)}</div>

            <!-- Progress bar (green — completed) -->
            <div class="ongoing-progress-wrap">
                <div class="ongoing-progress-label">
                    <span>Progress</span>
                    <span class="ongoing-progress-pct" style="color:#16a34a; font-weight:700;">${progress}%</span>
                </div>
                <div class="ongoing-progress-track">
                    <div class="ongoing-progress-fill"
                         style="width:${progress}%; background:#16a34a;"></div>
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
                    <span class="request-detail-label"><i class="fa-solid fa-calendar"></i> Started</span>
                    <span class="request-detail-value">${this.escHtml(project.started_date)}</span>
                </div>
                <div class="request-detail-item">
                    <span class="request-detail-label"><i class="fa-solid fa-layer-group"></i> Level</span>
                    <span class="request-detail-value">${this.escHtml(project.level)}</span>
                </div>
            </div>

            ${reviewsHTML}

            <!-- Footer -->
            <div class="request-footer">
                <span class="request-time">Completed ${this.escHtml(project.completed_date)}</span>
                <span class="status-chip status-complete-chip">
                    <i class="fa-solid fa-circle-check"></i> Completed
                </span>
            </div>
        </div>`;
    }

    createReviewHTML(review) {
        const stars = this.renderStars(review.Rating || 0);
        const reviewer = review.Rated_By === 'Client' ? 'Client\'s Review' : 'Your Review';
        const date = review.Left_At ? new Date(review.Left_At).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';

        const filesHTML = review.files && review.files.length
            ? `<div class="review-card-files">
                ${review.files.map(f => {
                    const name = f.split('/').pop();
                    const url  = `${window.BASE_URL}/file/review-files/${encodeURIComponent(f)}`;
                    return `<a href="${url}" target="_blank" class="review-file-chip" title="${this.escHtml(name)}">
                        <i class="fa-solid fa-paperclip"></i> ${this.escHtml(name)}
                    </a>`;
                }).join('')}
               </div>`
            : '';

        return `
        <div class="completed-review-card ${review.Rated_By === 'Provider' ? 'review-own' : 'review-client'}">
            <div class="review-card-header">
                <span class="review-card-label">${this.escHtml(reviewer)}</span>
                <span class="review-card-date">${this.escHtml(date)}</span>
            </div>
            <div class="review-card-stars">${stars}</div>
            ${review.Title ? `<div class="review-card-title">${this.escHtml(review.Title)}</div>` : ''}
            ${review.Description ? `<div class="review-card-desc">${this.escHtml(review.Description)}</div>` : ''}
            ${filesHTML}
        </div>`;
    }

    renderStars(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                html += '<i class="fa-solid fa-star star-filled"></i>';
            } else {
                html += '<i class="fa-regular fa-star star-empty"></i>';
            }
        }
        return html;
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
        container.querySelectorAll('.btn-view-completed').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId = parseInt(btn.dataset.postId, 10);
                if (typeof ProjectDetailView !== 'undefined' && ProjectDetailView.open) {
                    ProjectDetailView.open(postId);
                }
            });
        });

        // Leave Review button — opens the provider review modal
        container.querySelectorAll('.btn-leave-review').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const postId = parseInt(btn.dataset.postId, 10);
                this.openReviewModal(postId);
            });
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Provider Review Modal                                               */
    /* ------------------------------------------------------------------ */

    openReviewModal(postId) {
        const modal   = document.querySelector('.provider-review-popup');
        const overlay = modal;
        if (!modal) return;

        // Reset form
        const form = modal.querySelector('#provider-review-form');
        if (form) form.reset();
        modal.querySelectorAll('.star-rating-input .fa-star').forEach(s => {
            s.classList.remove('fa-solid', 'active');
            s.classList.add('fa-regular');
        });
        modal.querySelector('#provider-review-post-id').value = postId;
        modal.querySelector('#provider-review-rating').value = '';

        modal.classList.remove('deactive');
        modal.querySelector('.pop-up').classList.remove('deactive');
    }

    closeReviewModal() {
        const modal = document.querySelector('.provider-review-popup');
        if (!modal) return;
        modal.classList.add('deactive');
        modal.querySelector('.pop-up').classList.add('deactive');
    }

    async submitReview() {
        const modal  = document.querySelector('.provider-review-popup');
        if (!modal) return;

        const postId = modal.querySelector('#provider-review-post-id').value;
        const rating = modal.querySelector('#provider-review-rating').value;
        const title  = modal.querySelector('#provider-review-title').value.trim();
        const desc   = modal.querySelector('#provider-review-description').value.trim();

        if (!rating || parseInt(rating) < 1) {
            alert('Please select a rating.');
            return;
        }

        const submitBtn = modal.querySelector('.btn-submit-review');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        }

        try {
            const formData = new FormData();
            formData.append('rating', rating);
            formData.append('title', title);
            formData.append('description', desc);

            const res = await fetch(`${this.reviewEndpoint}/${postId}`, {
                method: 'POST',
                body: formData,
            });
            const data = await res.json();

            if (data.success) {
                this.closeReviewModal();
                this.loadProjects(this.currentPage);
            } else {
                alert(data.message || 'Failed to submit review.');
            }
        } catch (err) {
            console.error('Review submit error:', err);
            alert('Something went wrong. Please try again.');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Review';
            }
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
                    <button class="retry-btn" onclick="window.completedProjectsManager && window.completedProjectsManager.loadProjects(1)">Retry</button>
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
    const manager = new CompletedProjectsManager();
    manager.init().catch(err => console.error('CompletedProjectsManager init error:', err));
    window.completedProjectsManager = manager;

    // Star rating interaction for provider review modal
    const starsContainer = document.querySelector('.provider-review-popup .star-rating-input');
    const ratingInput    = document.querySelector('#provider-review-rating');
    if (starsContainer && ratingInput) {
        const stars = starsContainer.querySelectorAll('.fa-star');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const val = parseInt(star.dataset.value);
                ratingInput.value = val;
                stars.forEach(s => {
                    if (parseInt(s.dataset.value) <= val) {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid', 'active');
                    } else {
                        s.classList.remove('fa-solid', 'active');
                        s.classList.add('fa-regular');
                    }
                });
            });

            star.addEventListener('mouseenter', () => {
                const val = parseInt(star.dataset.value);
                stars.forEach(s => {
                    if (parseInt(s.dataset.value) <= val) {
                        s.classList.add('hover');
                    } else {
                        s.classList.remove('hover');
                    }
                });
            });
        });

        starsContainer.addEventListener('mouseleave', () => {
            stars.forEach(s => s.classList.remove('hover'));
        });
    }

    // Close & submit wiring
    const closeBtn = document.querySelector('#provider-review-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => window.completedProjectsManager?.closeReviewModal());
    }
    const submitBtn = document.querySelector('.btn-submit-review');
    if (submitBtn) {
        submitBtn.addEventListener('click', () => window.completedProjectsManager?.submitReview());
    }
    const overlay = document.querySelector('.provider-review-popup');
    if (overlay) {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) window.completedProjectsManager?.closeReviewModal();
        });
    }
});
