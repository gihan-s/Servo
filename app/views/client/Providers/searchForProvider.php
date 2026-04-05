<?php
if (!empty($cardsOnly)) {
?>
    <div class="profiles-grid" id="providers-grid">
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #475569;">
            Loading providers...
        </div>
    </div>
    <div class="load-more-wrap" id="provider-load-more" style="display:flex; justify-content:center; margin-top:35px;"></div>
    <div class="dialog-box-2" id="provider-services-modal">
        <div class="dialog-content" style="width: 700px;">
            <div class="dialog-title">
                <div class="title">Services by <span id="modal-provider-name"></span></div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeProviderServicesModal()"></i>
                </div>
            </div>
            <div id="modal-services-content" style="padding: 20px 0;">
                <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
                    <i class="fa-solid fa-spinner" style="font-size: 48px; margin-bottom: 20px; color: #008500; opacity: 0.8; animation: spin 1s linear infinite;"></i>
                    <p style="font-size: 16px; font-weight: 500; color: #1f2937;">Loading services...</p>
                </div>
            </div>
        </div>
    </div>
    <div class="dialog-box-2" id="provider-request-modal">
        <div class="dialog-content" style="width: 720px;">
            <div class="dialog-title">
                <div class="title">Send Request</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeProviderRequestModal()"></i>
                </div>
            </div>
            <form id="provider-request-form" onsubmit="return false;">
                <input type="hidden" id="provider-request-service-id" name="provider_categories_id">
                <input type="hidden" id="provider-request-provider-id" name="provider_id">
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label">Title</div>
                        <input type="text" class="text-field" name="title" id="provider-request-title">
                    </div>
                </div>
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label">Description</div>
                        <textarea class="text-field" spellcheck="false" name="description" id="provider-request-description"></textarea>
                    </div>
                </div>
                <div class="input-grid-2">
                    <div class="text-container">
                        <div class="label text-label">Requesting Price</div>
                        <input type="text" class="text-field" name="requesting_price" id="provider-request-price">
                    </div>
                    <div class="search-select-container">
                        <div class="text-container">
                            <div class="label search-dropdown-label">Price Type</div>
                            <input type="text" class="text-field-search-dropdown" autocomplete="off" onkeydown="return false" name="price_type" id="provider-request-price-type">
                        </div>
                        <div class="options">
                            <span class="text-container">
                                <input type="text" class="text-field-search" placeholder="Search price type">
                            </span>
                            <div class="option-list">
                                <div>Fixed</div>
                                <div>Hourly</div>
                                <div>Daily</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label">Estimated Date</div>
                        <input type="date" class="text-field" name="est_date" id="provider-request-est-date">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="action-btn btn-delete" onclick="closeProviderRequestModal()">Cancel</button>
                    <button type="button" class="action-btn btn-edit" onclick="submitProviderServiceRequest()"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                </div>
            </form>
        </div>
    </div>
        <div class="dialog-box-2" id="provider-service-details-modal">
            <div class="dialog-content" style="width: 700px;">
                <div class="dialog-title">
                    <div class="title" id="service-details-title">Service Details</div>
                    <div>
                        <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeServiceDetailsModal()"></i>
                    </div>
                </div>
                <div id="service-details-content" style="padding: 20px 0;"></div>
            </div>
        </div>
    <script>
        let providerSearchCurrentPage = 1;
        let providerSearchTotalPages = 1;
        let providerSearchIsLoading = false;
        const providerServiceCache = {};

        function renderProviderCard(provider) {
            const skillsHTML = (provider.skills || []).map(skill =>
                `<span class="skill-tag">${skill}</span>`
            ).join('');

            const socialHTML = (provider.social_links || []).map(social => {
                const iconPrefix = (social.icon_class === 'fa-envelope' || social.icon_class === 'fa-link') ? 'fa-solid' : 'fa-brands';
                return `
                    <a href="${social.link}" title="${social.name}" target="_blank" style="background-color: ${social.color}">
                        <i class="${iconPrefix} ${social.icon_class}"></i>
                    </a>
                `;
            }).join('');

            return `
                <div class="profile-card search-item">
                    <div class="profile-cover"></div>
                    <div class="profile-body">
                        <div class="profile-head">
                            <img class="profile-avatar" src="${provider.avatar}" alt="Profile Image" style="object-fit: cover;">
                            <h3 class="profile-name">${provider.First_Name} ${provider.Last_Name}</h3>
                            <div class="profile-services">
                                ${skillsHTML || '<span class="skill-tag">View Services</span>'}
                            </div>
                        </div>

                        <div class="profile-stats">
                            <div class="stat-pill">Rating: <b><i class="fa-solid fa-star" style="color:#f59e0b;"></i> ${provider.rating}</b></div>
                            <div class="stat-pill">Earnings: <b>${provider.total_earning_formatted}</b></div>
                        </div>

                        <div class="provider-bio" style="padding: 0 18px; box-sizing: border-box;">${provider.Bio || 'Experienced professional ready to help with your project.'}</div>

                        <div class="provider-actions">
                            <button class="action-btn btn-view" type="button"><i class="fa-solid fa-messages"></i> Message</button>
                            <button class="action-btn btn-view" type="button"><i class="fa-solid fa-user"></i> View Profile</button>
                            <button class="action-btn btn-edit provider-hire-btn" type="button" data-provider-id="${provider.Provider_ID}" data-provider-name="${provider.First_Name} ${provider.Last_Name}" onclick="openProviderServicesModal(this)"><i class="fa-solid fa-briefcase"></i> Hire</button>
                        </div>

                        <div class="provider-social">
                            ${socialHTML || '<span style="font-size: 12px; color: #999;">No social links</span>'}
                        </div>
                    </div>
                </div>
            `;
        }

        function renderLoadMoreButton() {
            const loadMoreContainer = document.getElementById('provider-load-more');
            if (!loadMoreContainer) {
                return;
            }

            if (providerSearchCurrentPage >= providerSearchTotalPages) {
                loadMoreContainer.innerHTML = '';
                return;
            }

            loadMoreContainer.innerHTML = '<button type="button" class="action-btn btn-view" id="load-more-providers-btn">Load More</button>';
            const button = document.getElementById('load-more-providers-btn');
            if (button) {
                button.addEventListener('click', function () {
                    loadProviders(providerSearchCurrentPage + 1, true);
                });
            }
        }

        function loadProviders(page = 1, append = false) {
            const providersGrid = document.getElementById('providers-grid');
            const loadMoreContainer = document.getElementById('provider-load-more');

            if (!providersGrid || !loadMoreContainer || providerSearchIsLoading) {
                return;
            }

            providerSearchIsLoading = true;

            if (append) {
                loadMoreContainer.innerHTML = '<div class="loading-state" style="padding: 10px 0 0; width: 100%;"><i class="fas fa-spinner"></i><p>Loading providers...</p></div>';
            } else {
                providersGrid.innerHTML = '<div class="loading-state" style="grid-column: 1 / -1;"><i class="fas fa-spinner"></i><p>Loading providers...</p></div>';
                loadMoreContainer.innerHTML = '';
            }

            fetch(`<?= BASE_URL ?>/providers/search?page=${page}&limit=12`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    try {
                        return JSON.parse(data);
                    } catch (error) {
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(data => {
                    if (!data.success) {
                        providersGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: red;">Error loading providers</p>';
                        loadMoreContainer.innerHTML = '';
                        providerSearchIsLoading = false;
                        return;
                    }

                    const cardsHTML = (data.providers || []).map(renderProviderCard).join('');

                    if (append) {
                        if (cardsHTML) {
                            providersGrid.insertAdjacentHTML('beforeend', cardsHTML);
                        }
                    } else {
                        providersGrid.innerHTML = cardsHTML || '<p style="grid-column: 1/-1; text-align: center;">No providers found</p>';
                    }

                    providerSearchCurrentPage = data.pagination?.current_page || page;
                    providerSearchTotalPages = data.pagination?.total_pages || page;
                    renderLoadMoreButton();
                    providerSearchIsLoading = false;
                })
                .catch(error => {
                    console.error('Error loading providers:', error);
                    providersGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: red;">Error loading providers</p>';
                    loadMoreContainer.innerHTML = '';
                    providerSearchIsLoading = false;
                });
        }

        function openProviderServicesModal(button) {
            const providerId = button.getAttribute('data-provider-id');
            const providerName = button.getAttribute('data-provider-name');

            if (!providerId) {
                alert('Provider ID not found');
                return;
            }

            const modal = document.getElementById('provider-services-modal');
            const modalTitle = document.getElementById('modal-provider-name');

            if (!modal || !modalTitle) {
                alert('Modal element not found');
                return;
            }

            modalTitle.textContent = providerName || 'Provider';
            modal.classList.add('dialog-box-2-view');
            loadProviderServices(providerId);
        }

        function closeProviderServicesModal() {
            const modal = document.getElementById('provider-services-modal');
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }
            closeServiceDetailsModal();
        }

        function loadProviderServices(providerId) {
            const modalContent = document.getElementById('modal-services-content');
            if (!modalContent) {
                return;
            }

            modalContent.innerHTML = `
                <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
                    <i class="fa-solid fa-spinner" style="font-size: 48px; margin-bottom: 20px; color: #008500; opacity: 0.8; animation: spin 1s linear infinite;"></i>
                    <p style="font-size: 16px; font-weight: 500; color: #1f2937;">Loading services...</p>
                </div>
            `;

            fetch(`<?= BASE_URL ?>/providers/services?provider_id=${providerId}&limit=10`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    try {
                        return JSON.parse(data);
                    } catch (error) {
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(data => {
                    if (!data.success) {
                        modalContent.innerHTML = `<p style="text-align: center; padding: 40px; color: #ef4444;">${data.message || 'Failed to load services'}</p>`;
                        return;
                    }

                    if (!data.services || data.services.length === 0) {
                        modalContent.innerHTML = `<p style="text-align: center; padding: 40px; color: #6b7280;">No services found for this provider.</p>`;
                        return;
                    }

                    const servicesHTML = data.services.map(service => renderServiceCard(service)).join('');
                    modalContent.innerHTML = `
                        <div style="display: grid; grid-template-columns: 1fr; gap: 16px; padding: 0 20px;">
                            ${servicesHTML}
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error loading services:', error);
                    modalContent.innerHTML = `<p style="text-align: center; padding: 40px; color: #ef4444;">Error loading services: ${error.message}</p>`;
                });
        }

        function renderServiceCard(service) {
            providerServiceCache[service.Provider_Categories_ID] = service;

            const skillsHTML = (service.skills || []).map(skill =>
                `<span class="skill-tag">${skill}</span>`
            ).join('');

            const linksHTML = (service.show_links || []).map(link => `
                <a href="${link.url}" target="_blank" rel="noopener noreferrer" class="action-btn btn-view" style="padding: 7px 12px; font-size: 12px; text-decoration: none;">
                    <i class="fa-solid fa-link"></i> ${link.label}
                </a>
            `).join('');

            return `
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background: #f9fafb; transition: all 0.2s ease;">
                    <div style="margin-bottom: 12px;">
                        <h3 style="font-size: 16px; font-weight: 600; color: #1f2937; margin: 0 0 8px 0; line-height: 1.4;">
                            ${service.Title || 'Untitled Service'}
                        </h3>
                        <p style="font-size: 14px; color: #6b7280; margin: 0 0 12px 0; line-height: 1.5;">
                            ${service.Description ? service.Description.substring(0, 150) + (service.Description.length > 150 ? '...' : '') : 'No description'}
                        </p>
                    </div>

                    ${skillsHTML ? `
                        <div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 6px;">
                            ${skillsHTML}
                        </div>
                    ` : ''}

                    ${linksHTML ? `
                        <div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 8px;">
                            ${linksHTML}
                        </div>
                    ` : ''}

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                        <div style="font-size: 14px; color: #4b5563;">
                            <span style="font-weight: 600; color: #008500;">${service.price_display || 'Contact for price'}</span>
                            <span style="color: #9ca3af; font-size: 13px;"> • ${service.rate_type_display || service.Price_Type || 'N/A'}</span>
                        </div>
                        <span style="font-size: 12px; color: #9ca3af;">${service.Category_Name || service.CategoryName || 'Service'}</span>
                    </div>

                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <button class="action-btn btn-view" type="button" style="padding: 7px 12px; font-size: 12px;" onclick="openServiceRequestModal(${service.Provider_Categories_ID})" ${service.request_status === 'ongoing' ? 'disabled' : ''}>
                            <i class="fa-solid fa-paper-plane"></i> ${service.request_status === 'ongoing' ? 'Requested' : 'Send Request'}
                        </button>
                        <button class="action-btn btn-edit" type="button" style="padding: 7px 12px; font-size: 12px;" onclick="openServiceDetailsModal(${service.Provider_Categories_ID})">
                            <i class="fa-solid fa-eye"></i> View Service
                        </button>
                    </div>
                </div>
            `;
        }

        function openServiceDetailsModal(serviceId) {
            const service = providerServiceCache[serviceId];
                const detailsModal = document.getElementById('provider-service-details-modal');
                const detailsTitle = document.getElementById('service-details-title');
                const detailsContent = document.getElementById('service-details-content');

                if (!service || !detailsModal || !detailsTitle || !detailsContent) {
                alert('Service details not available');
                return;
            }

                detailsTitle.textContent = service.Title || 'Service Details';
                detailsContent.innerHTML = renderServiceDetailsCard(service);
                detailsModal.classList.add('dialog-box-2-view');
        }

            function closeServiceDetailsModal() {
                const detailsModal = document.getElementById('provider-service-details-modal');
                if (detailsModal) {
                    detailsModal.classList.remove('dialog-box-2-view');
                }
            }

        function closeProviderServicesModal() {
            const modal = document.getElementById('provider-services-modal');
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }
            closeServiceDetailsModal();
        }

        function renderServiceDetailsCard(service) {
            const skillsHTML = (service.skills || []).map(skill => `<span class="skill-tag">${skill}</span>`).join('');
            const linksHTML = (service.show_links || []).map(link => `
                <a href="${link.url}" target="_blank" rel="noopener noreferrer" class="action-btn btn-view" style="padding: 7px 12px; font-size: 12px; text-decoration: none;">
                    <i class="fa-solid fa-link"></i> ${link.label}
                </a>
            `).join('');
            const locationsHTML = (service.locations || []).map(location => `<span class="skill-tag">${location.District}${location.City ? ' - ' + location.City : ''}</span>`).join('');

            return `
                <div style="display:grid; gap:14px; padding:0 20px 10px;">
                    <div style="padding:16px; border:1px solid #e5e7eb; border-radius:14px; background:linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
                        <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap; margin-bottom:12px;">
                            <div style="min-width:0; flex:1;">
                                <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#008500; margin-bottom:8px;">Service Overview</div>
                                <h3 style="font-size:20px; margin:0; color:#111827; line-height:1.35;">${service.Title || 'Untitled Service'}</h3>
                            </div>
                            <div style="display:flex; flex-direction:column; gap:8px; align-items:flex-end; min-width:160px;">
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#ecfdf5; color:#166534; font-size:13px; font-weight:700;">
                                    <i class="fa-solid fa-layer-group"></i>
                                    ${service.Category_Name || 'Service'}
                                </div>
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#eff6ff; color:#1d4ed8; font-size:13px; font-weight:700;">
                                    <i class="fa-solid fa-tag"></i>
                                    ${service.price_display || 'Contact for price'}
                                </div>
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#f5f3ff; color:#6d28d9; font-size:13px; font-weight:700;">
                                    <i class="fa-regular fa-clock"></i>
                                    ${service.rate_type_display || 'N/A'}
                                </div>
                            </div>
                        </div>
                        <div style="padding-top:12px; border-top:1px solid #e5e7eb;">
                            <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Description</div>
                            <p style="margin:0; color:#4b5563; line-height:1.7; white-space:pre-wrap; font-size:14px;">${service.Description || 'No description available.'}</p>
                        </div>
                    </div>

                    ${skillsHTML ? `
                        <div style="padding:14px 16px; border:1px solid #e5e7eb; border-radius:14px; background:#fff;">
                            <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:10px;">Skills</div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">${skillsHTML}</div>
                        </div>
                    ` : ''}

                    ${locationsHTML ? `
                        <div style="padding:14px 16px; border:1px solid #e5e7eb; border-radius:14px; background:#fff;">
                            <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:10px;">Locations</div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">${locationsHTML}</div>
                        </div>
                    ` : ''}

                    ${linksHTML ? `
                        <div style="padding:14px 16px; border:1px solid #e5e7eb; border-radius:14px; background:#fff;">
                            <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:10px;">Links</div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">${linksHTML}</div>
                        </div>
                    ` : ''}

                    <div style="display:flex; justify-content:flex-end; gap:8px; padding-top:4px;">
                        <button type="button" class="action-btn btn-delete" onclick="closeServiceDetailsModal()">Close</button>
                        <button type="button" class="action-btn btn-edit" onclick="openServiceRequestModal(${service.Provider_Categories_ID})"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                    </div>
                </div>
            `;
        }

        function openServiceRequestModal(serviceId) {
            const service = providerServiceCache[serviceId];
            const modal = document.getElementById('provider-request-modal');
            const form = document.getElementById('provider-request-form');
            if (!service || !modal) {
                alert('Service request form not available');
                return;
            }

            closeServiceDetailsModal();

            if (service.request_status === 'ongoing') {
                alert('You already have an ongoing request for this service.');
                return;
            }

            if (form) {
                form.reset();
            }

            document.getElementById('provider-request-service-id').value = service.Provider_Categories_ID || '';
            document.getElementById('provider-request-provider-id').value = service.Provider_ID || '';

            modal.classList.add('dialog-box-2-view');
        }

        function closeProviderRequestModal() {
            const modal = document.getElementById('provider-request-modal');
            const form = document.getElementById('provider-request-form');
            if (form) {
                form.reset();
            }
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }
                closeServiceDetailsModal();
        }

        function submitProviderServiceRequest() {
            const serviceId = document.getElementById('provider-request-service-id').value;
            const providerId = document.getElementById('provider-request-provider-id').value;
            const title = document.getElementById('provider-request-title').value.trim();
            const description = document.getElementById('provider-request-description').value.trim();
            const requestingPrice = document.getElementById('provider-request-price').value.trim();
            const priceType = document.getElementById('provider-request-price-type').value.trim();
            const estDate = document.getElementById('provider-request-est-date').value;

            if (!serviceId || !providerId || !title || !description || !estDate) {
                alert('Please fill in all required fields.');
                return;
            }

            const payload = new URLSearchParams({
                provider_categories_id: serviceId,
                provider_id: providerId,
                title,
                description,
                requesting_price: requestingPrice || '0',
                price_type: priceType || 'Fixed',
                est_date: estDate,
                level: 'Beginner',
                end_at: ''
            });

            fetch(`<?= BASE_URL ?>/requests/direct-request`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: payload.toString()
            })
                .then(response => response.text())
                .then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (error) {
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Failed to send request');
                        return;
                    }

                    const service = providerServiceCache[parseInt(serviceId, 10)];
                    if (service) {
                        service.request_status = 'ongoing';
                    }

                        closeServiceDetailsModal();
                    closeProviderRequestModal();
                    closeProviderServicesModal();
                    if (typeof window.showSuccessToast === 'function') {
                        window.showSuccessToast('Request Sent', data.message || 'Service request sent successfully');
                    } else {
                        alert(data.message || 'Service request sent successfully');
                    }
                    loadProviderServices(providerId);
                })
                .catch(error => {
                    console.error('Error sending service request:', error);
                    alert('Failed to send service request');
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadProviders(1);
        });

        window.loadProviders = loadProviders;
        window.openProviderServicesModal = openProviderServicesModal;
        window.closeProviderServicesModal = closeProviderServicesModal;
        window.loadProviderServices = loadProviderServices;
            window.closeServiceDetailsModal = closeServiceDetailsModal;
    </script>
<?php
    return;
}
?>
