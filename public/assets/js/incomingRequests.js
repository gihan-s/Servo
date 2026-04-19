/**
 * Incoming Requests Manager
 * Handles fetching incoming service requests from the server with pagination support
 */

class IncomingRequestsManager {
    constructor(config = {}) {
        this.apiEndpoint = config.apiEndpoint || '/provider/incoming-requests';
        this.itemListSelector = config.itemListSelector || '.item-list';
        this.paginationSelector = config.paginationSelector || '.pagination';
        this.currentPage = 1;
        this.totalPages = 1;
        this.itemsPerPage = config.itemsPerPage || 10;
        this.isLoading = false;
        this.currentRequest = null; // Store current request for modal actions
    }

    /**
     * Fetch incoming requests from the server
     * @param {number} page - Page number to fetch
     * @returns {Promise<Object>} Response data
     */
    async fetchRequests(page = 1) {
        if (this.isLoading) return null;

        this.isLoading = true;
        try {
            const url = new URL(this.apiEndpoint, window.location.origin);
            url.searchParams.append('page', page);
            url.searchParams.append('limit', this.itemsPerPage);

            const response = await fetch(url.toString());

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                console.error('API Error:', data.message);
                return null;
            }

            return data;
        } catch (error) {
            console.error('Fetch Error:', error);
            return null;
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Render requests data to the DOM
     * @param {Array} requests - Array of request objects
     */
    renderRequests(requests) {
        const container = document.querySelector(this.itemListSelector);
        if (!container) {
            console.warn('Item list container not found');
            return;
        }

        if (requests.length === 0) {
            container.innerHTML = `
                <section class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h2>No incoming requests right now</h2>
                    <p>New service requests from clients will appear here.</p>
                </section>`;
            return;
        }

        container.innerHTML = requests.map(request => this.createRequestHTML(request)).join('');
    }

    /**
     * Create HTML for a single request
     * @param {Object} request - Request object
     * @returns {string} HTML string
     */
    createRequestHTML(request) {
        return `
            <div class="search-item" data-status="pending" data-post-id="${request.Post_ID}">
                <!-- Header: Avatar, Client Info, and Action Buttons -->
                <div class="request-header">
                    <div class="request-client-section">
                        <img src="/file/user-files/${request.client_avatar}" alt="${this.escapeHtml(request.client_name)}" class="request-avatar">
                        <div class="request-client-info">
                            <div class="request-client-name">${this.escapeHtml(request.client_name)}</div>
                            <div class="request-client-location">⭐ 0.0 (0 reviews)</div>
                        </div>
                    </div>
                    <div class="request-actions">
                        <button class="btn-outline btn-view" title="View Request" data-post-id="${request.Post_ID}">
                            <i class="fa-solid fa-eye"></i> View
                        </button>
                        <a href="/messages?new=${request.Client_ID}" class="message-link">
                            <button class="btn-outline btn-message" title="Message Client">
                                <i class="fa-solid fa-comments"></i> Message
                            </button>
                        </a>
                        
                        <button class="btn-primary btn-reject btn-danger" title="Reject Request" data-post-id="${request.Post_ID}">
                            <i class="fa-solid fa-circle-xmark"></i> Reject
                        </button>
                        
                        <button class="btn-primary btn-accept" title="Accept Request" data-post-id="${request.Post_ID}">
                            <i class="fa-solid fa-circle-check"></i> Accept
                        </button>
                    </div>
                </div>

                <!-- Task Title -->
                <div class="request-title">${this.escapeHtml(request.title)}</div>

                <!-- Task Description -->
                <div class="request-description">${this.escapeHtml(request.description)}</div>

                <!-- Details Section -->
                <div class="request-details">
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-coins"></i> Budget</span>
                        <span class="request-detail-value">${request.budget_display}</span>
                    </div>
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-tag request-detail-icon"></i>Category</span>
                        <span class="request-detail-value">${this.escapeHtml(request.category)}</span>
                    </div>
                    <div class="request-detail-item">
                        <span class="request-detail-label"><i class="fa-solid fa-calendar request-detail-icon"></i>Est. Date</span>
                        <span class="request-detail-value">${this.escapeHtml(request.timeline)}</span>
                    </div>
                </div>

                <!-- Footer: Time and Status -->
                <div class="request-footer">
                    <span class="request-time">${request.posted_date_relative}</span>
                    <span class="status-chip status-pending">${this.escapeHtml(request.request_type)} Request</span>
                </div>
            </div>
        `;
    }

    /**
     * Render pagination controls
     * @param {number} currentPage - Current page number
     * @param {number} totalPages - Total number of pages
     */
    renderPagination(currentPage, totalPages) {
        const container = document.querySelector(this.paginationSelector);
        if (!container) {
            console.warn('Pagination container not found');
            return;
        }

        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = `
            <button class="page-btn prev" ${currentPage === 1 ? 'disabled' : ''}>
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        `;

        // Calculate page range to display
        const maxPagesShow = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxPagesShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesShow - 1);

        if (endPage - startPage + 1 < maxPagesShow) {
            startPage = Math.max(1, endPage - maxPagesShow + 1);
        }

        // First page and ellipsis
        if (startPage > 1) {
            html += `<button class="page-btn" data-page="1">1</button>`;
            if (startPage > 2) {
                html += `<span class="ellipsis">...</span>`;
            }
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }

        // Last page and ellipsis
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="ellipsis">...</span>`;
            }
            html += `<button class="page-btn" data-page="${totalPages}">${totalPages}</button>`;
        }

        html += `
            <button class="page-btn next" ${currentPage === totalPages ? 'disabled' : ''}>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        `;

        container.innerHTML = html;

        // Add event listeners for pagination buttons
        this.setupPaginationListeners();
    }

    /**
     * Setup event listeners for pagination buttons
     */
    setupPaginationListeners() {
        const container = document.querySelector(this.paginationSelector);
        if (!container) return;

        // Page number buttons
        container.querySelectorAll('.page-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(btn.getAttribute('data-page'), 10);
                this.loadRequests(page, true);
            });
        });

        // Previous button
        const prevBtn = container.querySelector('.page-btn.prev');
        if (prevBtn && this.currentPage > 1) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.loadRequests(this.currentPage - 1, true);
            });
        }

        // Next button
        const nextBtn = container.querySelector('.page-btn.next');
        if (nextBtn && this.currentPage < this.totalPages) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.loadRequests(this.currentPage + 1, true);
            });
        }
    }

    /**
     * Load requests for a specific page
     * @param {number} page - Page number to load
     */
    async loadRequests(page = 1, scrollToTop = false) {
        this.showLoading();
        const data = await this.fetchRequests(page);

        if (!data) {
            this.showError('Failed to load requests. Please try again.');
            return;
        }

        // Small delay so the spinner is visible (matches client side feel)
        await new Promise(r => setTimeout(r, 300));

        this.currentPage = data.pagination.current_page;
        this.totalPages = data.pagination.total_pages;
        this.currentData = data.data; // Store data for modal access

        this.renderRequests(data.data);
        this.renderPagination(this.currentPage, this.totalPages);
        this.setupRequestListeners(data.data);

        // Scroll to top of requests (only on pagination navigation, not initial load)
        if (scrollToTop) {
            const container = document.querySelector(this.itemListSelector);
            if (container) {
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    }

    /**
     * Setup event listeners for request items
     */
    setupRequestListeners(requests) {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;

        // View buttons
        container.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const postId = parseInt(btn.getAttribute('data-post-id'), 10);
                const request = requests.find(r => r.Post_ID === postId);
                if (request) {
                    console.log("Openning View Dialog");
                    
                    this.openRequestModal(request);
                }
            });
        });

        // Accept buttons on cards
        container.querySelectorAll('.btn-accept').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const postId = parseInt(btn.getAttribute('data-post-id'), 10);
                const request = requests.find(r => r.Post_ID === postId);
                if (request) {
                    this.currentRequest = request;
                    this.showAcceptConfirmDialog();
                }
            });
        });

        // Reject buttons on cards
        container.querySelectorAll('.btn-reject').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const postId = parseInt(btn.getAttribute('data-post-id'), 10);
                const request = requests.find(r => r.Post_ID === postId);
                if (request) {
                    this.currentRequest = request;
                    this.showRejectReasonDialog();
                }
            });
        });
    }

    /**
     * Open request details modal
     */
    openRequestModal(request) {
        const modal = document.getElementById('requestModalRoot');
        console.log("Modal element:", modal);
        if (!modal) return;

        // Store current request for button actions
        this.currentRequest = request;

        // Populate modal fields
        document.getElementById('reqClient').textContent = this.escapeHtml(request.client_name);
        document.getElementById('reqTitle').textContent = this.escapeHtml(request.title);
        document.getElementById('reqDescription').textContent = this.escapeHtml(request.full_description);
        document.getElementById('reqDate').textContent = request.posted_date;
        document.getElementById('reqBudget').innerHTML = `Budget: ${request.budget_display}`;
        document.getElementById('reqTimeline').innerHTML = `Estimated: ${this.escapeHtml(request.timeline)}`;
        
        // Show modal
        modal.classList.remove('deactive');
        document.body.style.overflow = 'hidden';

        // Setup modal button listeners
        this.setupModalButtonListeners();
    }

    /**
     * Setup modal button event listeners
     */
    setupModalButtonListeners() {
        const btnMessage = document.getElementById('btnMessage');
        const btnAccept = document.getElementById('btnAccept');
        const btnDecline = document.getElementById('btnDecline');

        // Message button
        if (btnMessage) {
            btnMessage.onclick = () => {
                if (this.currentRequest) {
                    window.location.href = `/messages?new=${this.currentRequest.Client_ID}`;
                }
            };
        }

        // Accept button
        if (btnAccept) {
            btnAccept.onclick = () => {
                this.showAcceptConfirmDialog();
            };
        }

        // Decline button
        if (btnDecline) {
            btnDecline.onclick = () => {
                this.showRejectReasonDialog();
            };
        }
    }

    /**
     * Show rejection reason modal
     */
    showRejectReasonDialog() {
        const modal = document.getElementById('rejectionModalRoot');
        if (!modal) {
            alert('Rejection modal not found');
            return;
        }

        // Clear previous reason
        const textarea = document.getElementById('rejectionReason');
        if (textarea) {
            textarea.value = '';
            textarea.focus();
        }

        // Show modal
        modal.classList.remove('deactive');
        document.body.style.overflow = 'hidden';
    }

    /**
     * Reject a request
     */
    async rejectRequest(postId, reason) {
        try {
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('reason', reason);

            const response = await fetch(`${BASE_URL || ''}/provider/reject-request`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                alert('Error rejecting request: ' + (data.message || 'Unknown error'));
                return;
            }

            // alert('Request rejected successfully');
            showToast('success', 'Request rejected successfully');
            
            // Close both modals
            const requestModal = document.getElementById('requestModalRoot');
            const rejectionModal = document.getElementById('rejectionModalRoot');
            this.closeModal(requestModal);
            this.closeModal(rejectionModal);

            // Reload requests
            await this.loadRequests(this.currentPage);
        } catch (error) {
            console.error('Error:', error);
            alert('Error rejecting request');
        }
    }

    /**
     * Show accept confirmation dialog
     */
    showAcceptConfirmDialog() {
        const modal = document.getElementById('acceptModalRoot');
        if (!modal) {
            alert('Accept confirmation modal not found');
            return;
        }

        // Show modal
        modal.classList.remove('deactive');
        document.body.style.overflow = 'hidden';
    }

    /**
     * Handle accept form submission
     */
    handleAcceptSubmission() {
        if (this.currentRequest) {
            this.acceptRequest(this.currentRequest.Post_ID);
        }
    }

    /**
     * Accept a request
     */
    async acceptRequest(postId) {
        try {
            const formData = new FormData();
            formData.append('post_id', postId);

            const response = await fetch(`${BASE_URL || ''}/provider/accept-request`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                alert('Error accepting request: ' + (data.message || 'Unknown error'));
                return;
            }

            showToast('success', 'Request accepted successfully');
            
            // Close both modals
            const requestModal = document.getElementById('requestModalRoot');
            const acceptModal = document.getElementById('acceptModalRoot');
            this.closeModal(requestModal);
            this.closeModal(acceptModal);

            // Reload requests
            await this.loadRequests(this.currentPage);
        } catch (error) {
            console.error('Error:', error);
            alert('Error accepting request');
        }
    }
    closeModal(modal) {
        if (modal) {
            modal.classList.add('deactive');
            document.body.style.overflow = '';
        }
    }

    /**
     * Initialize the manager and load the first page
     */
    async init() {
        this.setupModalListeners();
        await this.loadRequests(1);
    }

    /**
     * Setup modal event listeners
     */
    setupModalListeners() {
        const requestModalRoot = document.getElementById('requestModalRoot');
        const requestModalClose = document.getElementById('requestModalClose');

        if (requestModalRoot && requestModalClose) {
            requestModalClose.addEventListener('click', () => this.closeModal(requestModalRoot));
            requestModalRoot.addEventListener('click', (e) => {
                if (e.target === requestModalRoot) this.closeModal(requestModalRoot);
            });
        }

        // Rejection modal listeners
        const rejectionModalRoot = document.getElementById('rejectionModalRoot');
        const rejectionModalClose = document.getElementById('rejectionModalClose');
        const btnCancelRejection = document.getElementById('btnCancelRejection');
        const btnConfirmRejection = document.getElementById('btnConfirmRejection');

        if (rejectionModalRoot && rejectionModalClose) {
            rejectionModalClose.addEventListener('click', () => this.closeModal(rejectionModalRoot));
            rejectionModalRoot.addEventListener('click', (e) => {
                if (e.target === rejectionModalRoot) this.closeModal(rejectionModalRoot);
            });
        }

        if (btnCancelRejection) {
            btnCancelRejection.addEventListener('click', () => {
                this.closeModal(rejectionModalRoot);
            });
        }

        if (btnConfirmRejection) {
            btnConfirmRejection.addEventListener('click', () => {
                this.handleRejectSubmission();
            });
        }

        // Accept confirmation modal listeners
        const acceptModalRoot = document.getElementById('acceptModalRoot');
        const acceptModalClose = document.getElementById('acceptModalClose');
        const btnCancelAccept = document.getElementById('btnCancelAccept');
        const btnConfirmAccept = document.getElementById('btnConfirmAccept');

        if (acceptModalRoot && acceptModalClose) {
            acceptModalClose.addEventListener('click', () => this.closeModal(acceptModalRoot));
            acceptModalRoot.addEventListener('click', (e) => {
                if (e.target === acceptModalRoot) this.closeModal(acceptModalRoot);
            });
        }

        if (btnCancelAccept) {
            btnCancelAccept.addEventListener('click', () => {
                this.closeModal(acceptModalRoot);
            });
        }

        if (btnConfirmAccept) {
            btnConfirmAccept.addEventListener('click', () => {
                this.handleAcceptSubmission();
            });
        }

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('requestModalRoot');
                const rejectionModal = document.getElementById('rejectionModalRoot');
                const acceptModal = document.getElementById('acceptModalRoot');
                if (modal) this.closeModal(modal);
                if (rejectionModal) this.closeModal(rejectionModal);
                if (acceptModal) this.closeModal(acceptModal);
            }
        });
    }

    /**
     * Handle rejection form submission
     */
    handleRejectSubmission() {
        const textarea = document.getElementById('rejectionReason');
        const reason = textarea ? textarea.value.trim() : '';

        if (reason === '') {
            alert('Please provide a reason for rejection');
            return;
        }

        if (this.currentRequest) {
            this.rejectRequest(this.currentRequest.Post_ID, reason);
        }
    }

    /**
     * Show error message to user
     * @param {string} message - Error message
     */
    showLoading() {
        const container = document.querySelector(this.itemListSelector);
        if (!container) return;
        container.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading requests...</p>
            </div>`;
    }

    showError(message) {
        const container = document.querySelector(this.itemListSelector);
        if (container) {
            container.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>${this.escapeHtml(message)}</p>
                    <button class="retry-btn" onclick="window.requestsManager && window.requestsManager.loadRequests(1)">Retry</button>
                </div>`;
        }
    }

    /**
     * Escape HTML special characters
     * @param {string} text - Text to escape
     * @returns {string} Escaped text
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

/**
 * Initialize on DOM ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Create instance with custom configuration
    const requestsManager = new IncomingRequestsManager({
        apiEndpoint: `${BASE_URL || ''}/provider/incoming-requests`,
        itemListSelector: '.item-list',
        paginationSelector: '.pagination',
        itemsPerPage: 10
    });

    // Initialize and load requests
    requestsManager.init().catch(error => {
        console.error('Initialization Error:', error);
    });

    // Store instance globally if needed for debugging
    window.requestsManager = requestsManager;
});
