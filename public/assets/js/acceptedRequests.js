/**
 * Accepted Requests Manager
 * Handles fetching and displaying accepted requests (awaiting client payment) for the provider.
 */
class AcceptedRequestsManager {
    constructor(config = {}) {
        this.apiEndpoint       = config.apiEndpoint       || '/provider/accepted-requests';
        this.itemListSelector  = config.itemListSelector  || '#section-accepted .item-list';
        this.paginationSelector = config.paginationSelector || '#section-accepted .pagination';
        this.currentPage  = 1;
        this.totalPages   = 1;
        this.itemsPerPage = config.itemsPerPage || 10;
        this.isLoading    = false;
        this.currentData  = [];
    }

    async fetchRequests(page = 1) {
        if (this.isLoading) return null;
        this.isLoading = true;

        try {
            const url = new URL(this.apiEndpoint, window.location.origin);
            url.searchParams.append('page', page);
            url.searchParams.append('limit', this.itemsPerPage);

            const response = await fetch(url.toString());
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

            const data = await response.json();
            if (!data.success) {
                console.error('API Error:', data.message);
                return null;
            }
            return data;
        } catch (error) {
            console.error('AcceptedRequestsManager fetch error:', error);
            return null;
        } finally {
            this.isLoading = false;
        }
    }

    renderRequests(requests) {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;

        if (requests.length === 0) {
            container.innerHTML = `
                <section class="empty-state">
                    <i class="fas fa-clock"></i>
                    <h2>No accepted requests right now</h2>
                    <p>Requests you accept will appear here while awaiting client payment.</p>
                </section>`;
            return;
        }

        container.innerHTML = requests.map(r => this.createCardHTML(r)).join('');
    }

    createCardHTML(request) {
        return `
            <div class="search-item" data-status="accepted" data-post-id="${request.Post_ID}">
                <div class="request-header">
                    <div class="request-client-section">
                        <img src="${request.client_avatar}" alt="${this.esc(request.client_name)}" class="request-avatar"
                             onerror="this.style.display='none'">
                        <div class="request-client-info">
                            <div class="request-client-name">${this.esc(request.client_name)}</div>
                            <div class="request-client-location">&#11088; 0.0 (0 reviews)</div>
                        </div>
                    </div>
                    <div class="request-actions">
                        <button class="btn-outline btn-view-accepted" title="View Request" data-post-id="${request.Post_ID}">
                            <i class="fa-solid fa-eye"></i> View
                        </button>
                        <a href="/messages?new=${request.Client_ID}" style="text-decoration:none;">
                            <button class="btn-outline" title="Message Client">
                                <i class="fa-solid fa-comments"></i> Message
                            </button>
                        </a>
                    </div>
                </div>
                <div class="request-title">${this.esc(request.title)}</div>
                <div class="request-description">${this.esc(request.description)}</div>
                <div class="request-details">
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                        <span class="request-detail-value">${request.budget_display}</span>
                    </div>
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-tag"></i> Category</span>
                        <span class="request-detail-value">${this.esc(request.category)}</span>
                    </div>
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-calendar"></i> Est. Date</span>
                        <span class="request-detail-value">${this.esc(request.timeline)}</span>
                    </div>
                </div>
                <div class="request-footer">
                    <span class="request-time">${request.posted_date_relative}</span>
                    <span class="status-chip status-accepted-chip">${this.esc(request.request_type)} Request &middot; Awaiting Payment</span>
                </div>
            </div>
        `;
    }

    renderPagination(currentPage, totalPages) {
        const container = document.querySelector(this.paginationSelector);
        if (!container) return;

        if (totalPages <= 1) { container.innerHTML = ''; return; }

        const maxShow = 5;
        let start = Math.max(1, currentPage - Math.floor(maxShow / 2));
        let end   = Math.min(totalPages, start + maxShow - 1);
        if (end - start + 1 < maxShow) start = Math.max(1, end - maxShow + 1);

        let html = `<button class="page-btn prev" ${currentPage === 1 ? 'disabled' : ''}><i class="fa-solid fa-chevron-left"></i></button>`;
        if (start > 1) { html += `<button class="page-btn" data-page="1">1</button>`; if (start > 2) html += `<span class="ellipsis">...</span>`; }
        for (let i = start; i <= end; i++) html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        if (end < totalPages) { if (end < totalPages - 1) html += `<span class="ellipsis">...</span>`; html += `<button class="page-btn" data-page="${totalPages}">${totalPages}</button>`; }
        html += `<button class="page-btn next" ${currentPage === totalPages ? 'disabled' : ''}><i class="fa-solid fa-chevron-right"></i></button>`;

        container.innerHTML = html;
        this.setupPaginationListeners();
    }

    setupPaginationListeners() {
        const container = document.querySelector(this.paginationSelector);
        if (!container) return;
        container.querySelectorAll('.page-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', () => this.loadRequests(parseInt(btn.dataset.page, 10)));
        });
        const prev = container.querySelector('.page-btn.prev');
        if (prev && this.currentPage > 1) prev.addEventListener('click', () => this.loadRequests(this.currentPage - 1));
        const next = container.querySelector('.page-btn.next');
        if (next && this.currentPage < this.totalPages) next.addEventListener('click', () => this.loadRequests(this.currentPage + 1));
    }

    async loadRequests(page = 1) {
        this.showLoading();
        const data = await this.fetchRequests(page);
        if (!data) { this.showError(); return; }

        await new Promise(r => setTimeout(r, 300));

        this.currentPage  = data.pagination.current_page;
        this.totalPages   = data.pagination.total_pages;
        this.currentData  = data.data;

        this.renderRequests(data.data);
        this.renderPagination(this.currentPage, this.totalPages);
        this.setupCardListeners(data.data);
    }

    setupCardListeners(requests) {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;

        container.querySelectorAll('.btn-view-accepted').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const postId  = parseInt(btn.dataset.postId, 10);
                const request = requests.find(r => r.Post_ID === postId);
                if (request) this.openViewModal(request);
            });
        });
    }

    openViewModal(request) {
        const modal = document.getElementById('requestModalRoot');
        if (!modal) return;

        document.getElementById('reqClient').textContent     = this.esc(request.client_name);
        document.getElementById('reqTitle').textContent       = this.esc(request.title);
        document.getElementById('reqDescription').textContent = this.esc(request.full_description);
        document.getElementById('reqDate').textContent        = request.posted_date;
        document.getElementById('reqBudget').innerHTML        = `Budget: ${request.budget_display}`;
        document.getElementById('reqTimeline').innerHTML      = `Estimated: ${this.esc(request.timeline)}`;

        // Hide action buttons that don't apply
        const btnAccept  = document.getElementById('btnAccept');
        const btnDecline = document.getElementById('btnDecline');
        if (btnAccept)  btnAccept.style.display  = 'none';
        if (btnDecline) btnDecline.style.display = 'none';

        modal.classList.remove('deactive');
        document.body.style.overflow = 'hidden';
    }

    showLoading() {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;
        container.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading requests...</p>
            </div>`;
    }

    showError() {
        const container = document.querySelector(this.itemListSelector);
        if (container) {
            container.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Failed to load requests. Please try again.</p>
                    <button class="retry-btn" onclick="window.acceptedRequestsManager && window.acceptedRequestsManager.loadRequests(1)">Retry</button>
                </div>`;
        }
    }

    esc(text) {
        const d = document.createElement('div');
        d.textContent = text || '';
        return d.innerHTML;
    }

    async init() {
        await this.loadRequests(1);
    }
}

/* Auto-init */
document.addEventListener('DOMContentLoaded', () => {
    const mgr = new AcceptedRequestsManager({
        apiEndpoint: `${BASE_URL || ''}/provider/accepted-requests`,
    });
    mgr.init();
    window.acceptedRequestsManager = mgr;
});
