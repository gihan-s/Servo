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
                    <div class="text-container">
                        <div class="label text-label">Price Type</div>
                        <input type="text" class="text-field" name="price_type" id="provider-request-price-type" placeholder="Fixed, Hourly, Daily">
                    </div>
                </div>
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label label-float">Estimated Date</div>
                        <input type="date" class="text-field" name="est_date" id="provider-request-est-date">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="action-btn btn-delete" onclick="closeProviderRequestModal()">Cancel</button>
                    <button type="button" class="action-btn btn-edit" onclick="submitProviderServiceRequest()"><i class="fa-solid fa-paper-plane"></i> Post</button>
                </div>
            </form>
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
                <div class="profile-card">
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
                loadMoreContainer.innerHTML = '<button type="button" class="action-btn btn-view" disabled>Loading...</button>';
            } else {
                providersGrid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px;">Loading providers...</div>';
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
            const modal = document.getElementById('provider-services-modal');
            const modalTitle = document.getElementById('modal-provider-name');
            const modalContent = document.getElementById('modal-services-content');

            if (!service || !modal || !modalTitle || !modalContent) {
                alert('Service details not available');
                return;
            }

            modalTitle.textContent = service.Title || 'Service Details';
            modalContent.innerHTML = renderServiceDetailsCard(service);
            modal.classList.add('dialog-box-2-view');
        }

        function closeProviderServicesModal() {
            const modal = document.getElementById('provider-services-modal');
            if (modal) {
                modal.classList.remove('dialog-box-2-view');
            }
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
                <div style="display:grid; gap:16px; padding:0 20px 10px;">
                    <div>
                        <h3 style="font-size:20px; margin:0 0 10px; color:#1f2937;">${service.Title || 'Untitled Service'}</h3>
                        <p style="margin:0; color:#4b5563; line-height:1.6; white-space:pre-wrap;">${service.Description || 'No description available.'}</p>
                    </div>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        <span class="skill-tag">${service.Category_Name || 'Service'}</span>
                        <span class="skill-tag">${service.price_display || 'Contact for price'}</span>
                        <span class="skill-tag">${service.rate_type_display || 'N/A'}</span>
                    </div>
                    ${skillsHTML ? `<div style="display:flex; flex-wrap:wrap; gap:6px;">${skillsHTML}</div>` : ''}
                    ${locationsHTML ? `<div style="display:flex; flex-wrap:wrap; gap:6px;">${locationsHTML}</div>` : ''}
                    ${linksHTML ? `<div style="display:flex; flex-wrap:wrap; gap:8px;">${linksHTML}</div>` : ''}
                    <div style="display:flex; justify-content:flex-end; gap:8px;">
                        <button type="button" class="action-btn btn-delete" onclick="closeProviderServicesModal()">Close</button>
                        <button type="button" class="action-btn btn-edit" onclick="openServiceRequestModal(${service.Provider_Categories_ID})"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                    </div>
                </div>
            `;
        }

        function openServiceRequestModal(serviceId) {
            const service = providerServiceCache[serviceId];
            const modal = document.getElementById('provider-request-modal');
            if (!service || !modal) {
                alert('Service request form not available');
                return;
            }

            if (service.request_status === 'ongoing') {
                alert('You already have an ongoing request for this service.');
                return;
            }

            document.getElementById('provider-request-service-id').value = service.Provider_Categories_ID || '';
            document.getElementById('provider-request-provider-id').value = service.Provider_ID || '';
            document.getElementById('provider-request-title').value = service.Title || '';
            document.getElementById('provider-request-description').value = service.Description || '';
            document.getElementById('provider-request-price').value = service.Default_Price || '';
            document.getElementById('provider-request-price-type').value = service.rate_type_display && service.rate_type_display !== 'N/A' ? service.rate_type_display : (service.Price_Type || 'Fixed');

            const estDateField = document.getElementById('provider-request-est-date');
            if (estDateField && !estDateField.value) {
                const defaultDate = new Date();
                defaultDate.setDate(defaultDate.getDate() + 7);
                estDateField.value = defaultDate.toISOString().slice(0, 10);
            }

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

                    closeProviderRequestModal();
                    closeProviderServicesModal();
                    alert(data.message || 'Service request sent successfully');
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
    </script>
<?php
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link to external CSS files -->
    <link rel="stylesheet" href="assets/css/cardList.css">
    <link rel="stylesheet" href="assets/css/clientPosts.css">
    <link rel="stylesheet" href="assets/css/searchForProviderStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .profiles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .profile-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
        }

        .profile-cover {
            height: 84px;
            background: linear-gradient(120deg, #0f172a 0%, #1e3a8a 55%, #008500 100%);
        }

        .profile-body {
            padding: 0 16px 16px;
        }

        .profile-head {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -38px;
            margin-bottom: 14px;
            text-align: center;
        }

        .profile-avatar {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            border: 3px solid #ffffff;
            object-fit: cover;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
            margin-bottom: 10px;
        }

        .profile-name {
            font-size: 19px;
            font-weight: 700;
            color: #008500;
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .profile-services {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 14px;
        }

        .stat-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 10px;
            text-align: center;
            font-size: 12px;
            color: #475569;
            font-weight: 600;
        }

        .stat-pill b {
            color: #0f172a;
            font-size: 13px;
        }

        .provider-bio {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 12px;
            padding: 0 12px;
            text-align: left;
            min-height: 0;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            overflow: hidden;
        }

        .provider-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 12px;
        }

        .provider-social {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        .provider-social a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .action-btn i {
            font-size: 14px;
        }

        .btn-view {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .btn-view:hover {
            background-color: #cae8fa;
        }

        .btn-edit {
            background-color: #dcfce7;
            color: #15803d;
        }

        .btn-edit:hover {
            background-color: #bbf7d0;
        }

        .skill-tag {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }
    </style>

    <title>Search for services..</title>
</head>

<body>
    <div class="search-section">
        <!-- Search Header -->
        <!--
        <h1>Service Providers</h1>
        <div class="search-header">
            <div class="search-button">
                <input type="text" placeholder="Search for service providers...">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <button class="filter"><i class="fa-solid fa-filter"></i></button>
        </div>-->
        <div class="search-bottom">

            <!-- Search Filters -->

            <div class="search-filters">
                <div class="main-title"><span>Filters</span>
                    <hr>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Hourly rate</span><i class="fa-solid fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options active radios">
                        <li><input type="radio" name="rate" id="rate" checked>Any hourly rate</li>
                        <li><input type="radio" name="rate" id="rate">Less than $10</li>
                        <li><input type="radio" name="rate" id="rate">$10 - $30</li>
                        <li><input type="radio" name="rate" id="rate">$30 - $60</li>
                        <li><input type="radio" name="rate" id="rate">$60 & above</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Category</span><i class="fa-solid fa-chevron-down"></i></div>
                    <ul class="filter-options checkboxes">
                        <li><input type="checkbox" name="category" id="category" checked>Web Developer</li>
                        <li><input type="checkbox" name="category" id="category">Logo Designer</li>
                        <li><input type="checkbox" name="category" id="category">Tamil</li>
                        <li><input type="checkbox" name="category" id="category">Other</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Project success</span><i class="fa-solid fa-chevron-down"></i></div>
                    <ul class="filter-options radios">
                        <li><input type="radio" name="success" id="success" checked>Any success rate</li>
                        <li><input type="radio" name="success" id="success">90% & up</li>
                        <li><input type="radio" name="success" id="success">80% & up</li>
                        <li><input type="radio" name="success" id="success">70% & up</li>
                        <li><input type="radio" name="success" id="success">Less than 70%</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Total Earnings</span><i class="fa-solid fa-chevron-down"></i></div>
                    <ul class="filter-options radios">
                        <li><input type="radio" name="earnings" id="earnings" checked>Any amount earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$1+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$100+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$1K+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">$10K+ earned</li>
                        <li><input type="radio" name="earnings" id="earnings">No earnings yet</li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Language</span><i class="fa-solid fa-chevron-down"></i></div>
                    <ul class="filter-options checkboxes">
                        <li><input type="checkbox" name="language" id="language" checked>English</li>
                        <li><input type="checkbox" name="language" id="language">Sinhala</li>
                        <li><input type="checkbox" name="language" id="language">Tamil</li>
                        <li><input type="checkbox" name="language" id="language">Other</li>
                    </ul>
                </div>
                
                <div class="button-apply">
                    <button>Apply filters</button>
                </div>
            </div>
            <!-- Search Content -->

            <div class="search-content">
                <!--
                <div class="advance-search">
                    <div class="sort-selection">
                        <div class="selection-input-field">
                            <input type="selection-input" id="selection-input" name="sort" value="Sort By Relevence"
                                disabled><i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="selection-options" id="selection-options">
                            <div class="opt">Sort By Relevence</div>
                            <div class="opt">Sort By Price</div>
                            <div class="opt">Sort By Rating</div>
                        </div>
                    </div>
                </div> -->
                <!-- Search Results -->

                <section class="search-results-section">
                    <div class="profiles-grid" id="providers-grid">
                        <!-- Providers will be loaded here via AJAX -->
                    </div>
                    <div class="load-more-wrap" id="provider-load-more" style="display:flex; justify-content:center; margin-top:35px;"></div>
                </section>
            </div>
        </div>
    </div>

    <!-- Pop-up for Category Selection -->

    <div class="pop-up-section deactive category-pop-up">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Select Category</div>
                <i class="fa-solid fa-xmark" id="category-pop-up"></i>
            </div>
            <hr>
            <div class="pop-search-button">
                <i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search categories....">
            </div>
            <div class="pop-up-content">
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Web Development</div>
                    <div class="pop-up-item-count">(100)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Graphic Design</div>
                    <div class="pop-up-item-count">(50)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Digital Marketing</div>
                    <div class="pop-up-item-count">(30)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Content Writing</div>
                    <div class="pop-up-item-count">(20)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Data Entry</div>
                    <div class="pop-up-item-count">(10)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Web Development</div>
                    <div class="pop-up-item-count">(100)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Graphic Design</div>
                    <div class="pop-up-item-count">(50)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Digital Marketing</div>
                    <div class="pop-up-item-count">(30)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Content Writing</div>
                    <div class="pop-up-item-count">(20)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Data Entry</div>
                    <div class="pop-up-item-count">(10)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Web Development</div>
                    <div class="pop-up-item-count">(100)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Graphic Design</div>
                    <div class="pop-up-item-count">(50)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Digital Marketing</div>
                    <div class="pop-up-item-count">(30)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Content Writing</div>
                    <div class="pop-up-item-count">(20)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Data Entry</div>
                    <div class="pop-up-item-count">(10)</div>
                </div>
                <div class="pop-up-item">
                    <div class="pop-up-item-name">Mobile App Development</div>
                    <div class="pop-up-item-count">(15)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pop-up for Location Selection -->

    <div class="pop-up-section deactive location-pop-up">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Select Location</div>
                <i class="fa-solid fa-xmark" id="location-pop-up"></i>
            </div>
            <hr>
            <div class="pop-search-button">
                <i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search location....">
            </div>
            <div class="pop-up-content">
                <div class="pop-up-item">
                    <div class="pop-up-item-name">All in SriLanka</div>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Colombo</span><i class="fa-solid fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options radios">
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">All in Colombo</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Bambalapitiya</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Kollupitiya</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Gampaha</span><i class="fa-solid fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options radios">
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">All in Gampaha</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Yakkala</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Mirigama</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-item">
                    <div class="filter-title"><span>Kandy</span><i class="fa-solid fa-chevron-down rotated"></i>
                    </div>
                    <ul class="filter-options radios">
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">All in Kandy</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Kadugannawa</div>
                            </div>
                        </li>
                        <li>
                            <div class="pop-up-item">
                                <div class="pop-up-item-name">Gampola</div>
                            </div>
                        </li>
                    </ul>
                </div>


            </div>
        </div>
    </div>

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
                    <div class="text-container">
                        <div class="label text-label">Price Type</div>
                        <input type="text" class="text-field" name="price_type" id="provider-request-price-type" placeholder="Fixed, Hourly, Daily">
                    </div>
                </div>
                <div class="input-grid-1">
                    <div class="text-container">
                        <div class="label text-label label-float">Estimated Date</div>
                        <input type="date" class="text-field" name="est_date" id="provider-request-est-date">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="action-btn btn-delete" onclick="closeProviderRequestModal()">Cancel</button>
                    <button type="button" class="action-btn btn-edit" onclick="submitProviderServiceRequest()"><i class="fa-solid fa-paper-plane"></i> Post</button>
                </div>
            </form>
        </div>
    </div>

</body>
<script src="<?= BASE_URL ?>/../app/views/client/Providers/js/searchForProviderScript.js"></script>
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
            <div class="profile-card">
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
            loadMoreContainer.innerHTML = '<button type="button" class="action-btn btn-view" disabled>Loading...</button>';
        } else {
            providersGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px;">Loading providers...</p>';
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
                console.error('Error:', error);
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

        const skillsHTML = (service.skills || []).map(skill => `<span class="skill-tag">${skill}</span>`).join('');
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

    function renderServiceDetailsCard(service) {
        const skillsHTML = (service.skills || []).map(skill => `<span class="skill-tag">${skill}</span>`).join('');
        const linksHTML = (service.show_links || []).map(link => `
            <a href="${link.url}" target="_blank" rel="noopener noreferrer" class="action-btn btn-view" style="padding: 7px 12px; font-size: 12px; text-decoration: none;">
                <i class="fa-solid fa-link"></i> ${link.label}
            </a>
        `).join('');
        const locationsHTML = (service.locations || []).map(location => `<span class="skill-tag">${location.District}${location.City ? ' - ' + location.City : ''}</span>`).join('');

        return `
            <div style="display:grid; gap:16px; padding:0 20px 10px;">
                <div>
                    <h3 style="font-size:20px; margin:0 0 10px; color:#1f2937;">${service.Title || 'Untitled Service'}</h3>
                    <p style="margin:0; color:#4b5563; line-height:1.6; white-space:pre-wrap;">${service.Description || 'No description available.'}</p>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                    <span class="skill-tag">${service.Category_Name || 'Service'}</span>
                    <span class="skill-tag">${service.price_display || 'Contact for price'}</span>
                    <span class="skill-tag">${service.rate_type_display || 'N/A'}</span>
                </div>
                ${skillsHTML ? `<div style="display:flex; flex-wrap:wrap; gap:6px;">${skillsHTML}</div>` : ''}
                ${locationsHTML ? `<div style="display:flex; flex-wrap:wrap; gap:6px;">${locationsHTML}</div>` : ''}
                ${linksHTML ? `<div style="display:flex; flex-wrap:wrap; gap:8px;">${linksHTML}</div>` : ''}
                <div style="display:flex; justify-content:flex-end; gap:8px;">
                    <button type="button" class="action-btn btn-delete" onclick="closeProviderServicesModal()">Close</button>
                    <button type="button" class="action-btn btn-edit" onclick="openServiceRequestModal(${service.Provider_Categories_ID})"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
                </div>
            </div>
        `;
    }

    function openServiceDetailsModal(serviceId) {
        const service = providerServiceCache[serviceId];
        const modal = document.getElementById('provider-services-modal');
        const modalTitle = document.getElementById('modal-provider-name');
        const modalContent = document.getElementById('modal-services-content');

        if (!service || !modal || !modalTitle || !modalContent) {
            alert('Service details not available');
            return;
        }

        modalTitle.textContent = service.Title || 'Service Details';
        modalContent.innerHTML = renderServiceDetailsCard(service);
        modal.classList.add('dialog-box-2-view');
    }

    function openServiceRequestModal(serviceId) {
        const service = providerServiceCache[serviceId];
        const modal = document.getElementById('provider-request-modal');

        if (!service || !modal) {
            alert('Service request form not available');
            return;
        }

        if (service.request_status === 'ongoing') {
            alert('You already have an ongoing request for this service.');
            return;
        }

        document.getElementById('provider-request-service-id').value = service.Provider_Categories_ID || '';
        document.getElementById('provider-request-provider-id').value = service.Provider_ID || '';
        document.getElementById('provider-request-title').value = service.Title || '';
        document.getElementById('provider-request-description').value = service.Description || '';
        document.getElementById('provider-request-price').value = service.Default_Price || '';
        document.getElementById('provider-request-price-type').value = service.rate_type_display && service.rate_type_display !== 'N/A' ? service.rate_type_display : (service.Price_Type || 'Fixed');

        const estDateField = document.getElementById('provider-request-est-date');
        if (estDateField && !estDateField.value) {
            const defaultDate = new Date();
            defaultDate.setDate(defaultDate.getDate() + 7);
            estDateField.value = defaultDate.toISOString().slice(0, 10);
        }

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

                closeProviderRequestModal();
                closeProviderServicesModal();
                alert(data.message || 'Service request sent successfully');
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
    window.openServiceDetailsModal = openServiceDetailsModal;
    window.openServiceRequestModal = openServiceRequestModal;
    window.closeProviderRequestModal = closeProviderRequestModal;
    window.submitProviderServiceRequest = submitProviderServiceRequest;
</script>