// Main functionality for provider interface
document.addEventListener('DOMContentLoaded', function() {
    // Tab navigation
    const tabButtons = document.querySelectorAll('.container-changer .buttons');
    const tabSections = document.querySelectorAll('.requests-section');
    const searchInput = document.getElementById('projectsSearchInput');
    const searchBtn = document.getElementById('projectsSearchBtn');
    const filterRoot = document.getElementById('projectsFilterRoot');
    const filterModal = document.getElementById('projectsFilterModal');
    const filterButton = document.getElementById('projectsFilterBtn');
    const filterClose = document.getElementById('projectsFilterClose');
    const filterApply = document.getElementById('projectsFilterApply');
    const filterClear = document.getElementById('projectsFilterClear');
    const categoryList = document.getElementById('projectsCategoryList');
    const cards = Array.from(document.querySelectorAll('.request-content .search-item[data-title]'));
    let selectedCategories = new Set();
    let activeSectionKey = 'pending-requests';

    function getSelectedCategoriesFromUI() {
        if (!categoryList) {
            return new Set();
        }
        const selected = new Set();
        categoryList.querySelectorAll('input[type="checkbox"]:checked').forEach((input) => {
            if (input.value) {
                selected.add(input.value.toLowerCase());
            }
        });
        return selected;
    }

    function applyFilters() {
        const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
        cards.forEach((card) => {
            const section = card.closest('.requests-section');
            const sectionKey = section ? section.dataset.section : '';
            const title = (card.dataset.title || '').toLowerCase();
            const category = (card.dataset.category || '').toLowerCase();
            const client = (card.dataset.client || '').toLowerCase();
            const matchesQuery = !query || title.includes(query) || category.includes(query) || client.includes(query);
            const matchesCategory = selectedCategories.size === 0 || selectedCategories.has(category);
            const matchesSection = sectionKey === activeSectionKey;
            card.style.display = matchesQuery && matchesCategory && matchesSection ? '' : 'none';
        });
    }

    function populateCategoryFilters() {
        if (!categoryList) {
            return;
        }
        const uniqueCategories = Array.from(new Set(cards
            .map((card) => (card.dataset.category || '').trim())
            .filter((category) => category.length > 0)))
            .sort((a, b) => a.localeCompare(b));

        categoryList.innerHTML = '';
        uniqueCategories.forEach((category, index) => {
            const listItem = document.createElement('li');
            const id = 'projectsCategory_' + index;
            listItem.innerHTML = '<input type="checkbox" id="' + id + '" value="' + category + '"><label for="' + id + '">' + category + '</label>';
            categoryList.appendChild(listItem);
        });

        categoryList.querySelectorAll('li').forEach((option) => {
            option.addEventListener('click', function(event) {
                if (event.target.tagName === 'INPUT') {
                    return;
                }
                const input = this.querySelector('input');
                if (input) {
                    input.checked = !input.checked;
                }
            });
        });
    }

    function openFilterModal() {
        if (!filterRoot || !filterModal) {
            return;
        }
        filterRoot.classList.remove('deactive');
        filterModal.classList.remove('deactive');
        document.body.style.overflow = 'hidden';
    }

    function closeFilterModal() {
        if (!filterRoot || !filterModal) {
            return;
        }
        filterRoot.classList.add('deactive');
        filterModal.classList.add('deactive');
        document.body.style.overflow = '';
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            activeSectionKey = targetId;

            // Update active tab
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Show corresponding section
            tabSections.forEach(section => {
                section.classList.remove('active');
                if (section.classList.contains(targetId)) {
                    section.classList.add('active');
                }
            });

            applyFilters();
        });
    });

    // Filter popup functionality
    filterButton && filterButton.addEventListener('click', openFilterModal);
    filterClose && filterClose.addEventListener('click', closeFilterModal);
    filterRoot && filterRoot.addEventListener('click', function(e) {
        if (e.target === filterRoot) {
            closeFilterModal();
        }
    });

    filterApply && filterApply.addEventListener('click', function() {
        selectedCategories = getSelectedCategoriesFromUI();
        applyFilters();
        closeFilterModal();
    });

    filterClear && filterClear.addEventListener('click', function() {
        selectedCategories = new Set();
        if (categoryList) {
            categoryList.querySelectorAll('input[type="checkbox"]').forEach((input) => {
                input.checked = false;
            });
        }
        applyFilters();
    });

    searchBtn && searchBtn.addEventListener('click', function() {
        applyFilters();
    });

    searchInput && searchInput.addEventListener('input', function() {
        applyFilters();
    });

    searchInput && searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyFilters();
        }
    });

    searchInput && searchInput.addEventListener('focusin', function() {
        const searchButtonRoot = this.closest('.search-button');
        searchButtonRoot && searchButtonRoot.classList.add('focus');
    });

    searchInput && searchInput.addEventListener('focusout', function() {
        const searchButtonRoot = this.closest('.search-button');
        searchButtonRoot && searchButtonRoot.classList.remove('focus');
    });

    populateCategoryFilters();
    applyFilters();

    // Filter options toggle
    const filterTitles = document.querySelectorAll('.filter-title');

    filterTitles.forEach(title => {
        title.addEventListener('click', function() {
            const options = this.nextElementSibling;
            const icon = this.querySelector('i');

            options.classList.toggle('active');
            icon.classList.toggle('rotated');
        });
    });

    // Sort selection functionality
    const sortInput = document.getElementById('selection-input');
    const sortOptions = document.getElementById('selection-options');

    if (sortInput && sortOptions) {
        sortInput.addEventListener('click', function() {
            sortOptions.style.display = sortOptions.style.display === 'block' ? 'none' : 'block';
        });

        sortOptions.querySelectorAll('.opt').forEach(option => {
            option.addEventListener('click', function() {
                sortInput.value = this.textContent;
                sortOptions.style.display = 'none';
            });
        });

        // Close sort options when clicking outside
        document.addEventListener('click', function(e) {
            if (!sortInput.contains(e.target) && !sortOptions.contains(e.target)) {
                sortOptions.style.display = 'none';
            }
        });
    }

    // Modal functionality
    initializeModals();
});

// Modal management
function initializeModals() {
    // Request Details Modal
    const requestModalRoot = document.getElementById('requestModalRoot');
    const requestModalClose = document.getElementById('requestModalClose');

    if (requestModalRoot && requestModalClose) {
        requestModalClose.addEventListener('click', () => closeModal(requestModalRoot));
        requestModalRoot.addEventListener('click', (e) => {
            if (e.target === requestModalRoot) closeModal(requestModalRoot);
        });
    }

    // Proposal Modal
    const proposalModalRoot = document.getElementById('proposalModalRoot');
    const proposalModalClose = document.getElementById('proposalModalClose');

    if (proposalModalRoot && proposalModalClose) {
        proposalModalClose.addEventListener('click', () => closeModal(proposalModalRoot));
        proposalModalRoot.addEventListener('click', (e) => {
            if (e.target === proposalModalRoot) closeModal(proposalModalRoot);
        });
    }

    // Progress Modal
    const progressModalRoot = document.getElementById('progressModalRoot');
    const progressModalClose = document.getElementById('progressModalClose');

    if (progressModalRoot && progressModalClose) {
        progressModalClose.addEventListener('click', () => closeModal(progressModalRoot));
        progressModalRoot.addEventListener('click', (e) => {
            if (e.target === progressModalRoot) closeModal(progressModalRoot);
        });
    }

    // Submit Modal
    const submitModalRoot = document.getElementById('submitModalRoot');
    const submitModalClose = document.getElementById('submitModalClose');

    if (submitModalRoot && submitModalClose) {
        submitModalClose.addEventListener('click', () => closeModal(submitModalRoot));
        submitModalRoot.addEventListener('click', (e) => {
            if (e.target === submitModalRoot) closeModal(submitModalRoot);
        });
    }

    // Confirm Modal
    const confirmModalRoot = document.getElementById('confirmModalRoot');
    const confirmModalClose = document.getElementById('confirmModalClose');

    if (confirmModalRoot && confirmModalClose) {
        confirmModalClose.addEventListener('click', () => closeModal(confirmModalRoot));
        confirmModalRoot.addEventListener('click', (e) => {
            if (e.target === confirmModalRoot) closeModal(confirmModalRoot);
        });
    }

    // Button event handlers
    setupButtonHandlers();
}

function setupButtonHandlers() {
    // View buttons - open request details modal
    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.search-item');
            openRequestDetailsModal(card);
        });
    });

    // Propose buttons - open proposal modal
    document.querySelectorAll('.btn-propose').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.search-item');
            openProposalModal(card);
        });
    });

    // Update buttons - open progress modal
    document.querySelectorAll('.btn-update').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.search-item');
            openProgressModal(card);
        });
    });

    // Submit buttons - open submit modal
    document.querySelectorAll('.btn-submit').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.search-item');
            openSubmitModal(card);
        });
    });

    // Decline buttons - open confirm modal
    document.querySelectorAll('.btn-decline').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.search-item');
            openConfirmModal(
                'Decline Request',
                'Are you sure you want to decline this request? This action cannot be undone.',
                () => {
                    // Action to perform on confirm
                    card.remove();
                    alert('Request declined successfully.');
                }
            );
        });
    });

    // Withdraw buttons - open confirm modal
    document.querySelectorAll('.btn-withdraw').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.search-item');
            openConfirmModal(
                'Withdraw Proposal',
                'Are you sure you want to withdraw your proposal? This action cannot be undone.',
                () => {
                    // Action to perform on confirm
                    card.remove();
                    alert('Proposal withdrawn successfully.');
                }
            );
        });
    });

    // Modal action buttons
    const btnPropose = document.getElementById('btnPropose');
    if (btnPropose) {
        btnPropose.addEventListener('click', function() {
            closeModal(document.getElementById('requestModalRoot'));
            openProposalModal();
        });
    }

    const btnUpdate = document.getElementById('btnUpdate');
    if (btnUpdate) {
        btnUpdate.addEventListener('click', function() {
            closeModal(document.getElementById('requestModalRoot'));
            openProgressModal();
        });
    }

    const btnSubmit = document.getElementById('btnSubmit');
    if (btnSubmit) {
        btnSubmit.addEventListener('click', function() {
            closeModal(document.getElementById('requestModalRoot'));
            openSubmitModal();
        });
    }

    const btnSendProposal = document.getElementById('btnSendProposal');
    if (btnSendProposal) {
        btnSendProposal.addEventListener('click', function() {
            // Validate form
            const amount = document.getElementById('proposalAmount').value;
            const timeline = document.getElementById('proposalTimeline').value;
            const description = document.getElementById('proposalDescription').value;

            if (!amount || !timeline || !description) {
                alert('Please fill in all required fields.');
                return;
            }

            // Submit proposal (in a real app, this would be an API call)
            alert('Proposal sent successfully!');
            closeModal(document.getElementById('proposalModalRoot'));
        });
    }

    const btnUpdateProgress = document.getElementById('btnUpdateProgress');
    if (btnUpdateProgress) {
        btnUpdateProgress.addEventListener('click', function() {
            // Validate form
            const description = document.getElementById('progressDescription').value;

            if (!description) {
                alert('Please provide a progress update.');
                return;
            }

            // Update progress (in a real app, this would be an API call)
            alert('Progress updated successfully!');
            closeModal(document.getElementById('progressModalRoot'));
        });
    }

    const btnSubmitForReview = document.getElementById('btnSubmitForReview');
    if (btnSubmitForReview) {
        btnSubmitForReview.addEventListener('click', function() {
            // Validate form
            const description = document.getElementById('submitDescription').value;
            const files = document.getElementById('submitFiles').files;

            if (!description || files.length === 0) {
                alert('Please provide submission notes and attach deliverables.');
                return;
            }

            // Submit for review (in a real app, this would be an API call)
            alert('Project submitted for review successfully!');
            closeModal(document.getElementById('submitModalRoot'));
        });
    }

    // Progress percentage slider
    const progressSlider = document.getElementById('progressPercent');
    if (progressSlider) {
        progressSlider.addEventListener('input', function() {
            document.getElementById('progressPercentValue').textContent = this.value + '%';
        });
    }

    // Confirm modal actions
    const btnCancelAction = document.getElementById('btnCancelAction');
    const btnConfirmAction = document.getElementById('btnConfirmAction');

    if (btnCancelAction) {
        btnCancelAction.addEventListener('click', function() {
            closeModal(document.getElementById('confirmModalRoot'));
        });
    }

    // Note: btnConfirmAction action is set dynamically in openConfirmModal
}

function openModal(modal) {
    if (!modal) {
        return;
    }
    modal.classList.remove('deactive');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
    if (!modal) {
        return;
    }
    modal.classList.add('deactive');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function openRequestDetailsModal(card) {
    const modal = document.getElementById('requestModalRoot');
    const data = card.dataset || {};
    const client = data.client || (card.querySelector('.item-name') ? card.querySelector('.item-name').textContent : '');
    const title = data.title || (card.querySelector('.item-title') ? card.querySelector('.item-title').textContent : '');
    const description = data.description || (card.querySelector('.item-description') ? card.querySelector('.item-description').textContent : '');
    const date = data.posted || (card.querySelector('.item-district span') ? card.querySelector('.item-district span').textContent : 'Requested -');

    // Prefer explicit data attributes, then fallback to card content.
    let budget = data.budget || 'Budget: Not specified';
    let timeline = data.timeline || 'Timeline: Not specified';

    if (!data.budget || !data.timeline) {
        const middleItems = card.querySelectorAll('.item-middle div');
        middleItems.forEach(item => {
            const text = item.textContent;
            if (!data.budget && text.includes('Budget:')) budget = text;
            if (!data.timeline && (text.includes('Timeline:') || text.includes('ETA'))) timeline = text;
        });
    }

    // Set modal content
    document.getElementById('reqClient').textContent = client;
    document.getElementById('reqTitle').textContent = title;
    document.getElementById('reqDescription').textContent = description;
    document.getElementById('reqDate').textContent = date;
    document.getElementById('reqBudget').textContent = budget;
    document.getElementById('reqTimeline').textContent = timeline;
    document.getElementById('reqRequirements').textContent = data.requirements || description || '-';

    // Show/hide buttons based on request status
    const status = card.getAttribute('data-status');
    const btnPropose = document.getElementById('btnPropose');
    const btnUpdate = document.getElementById('btnUpdate');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnWithdraw = document.getElementById('btnWithdraw');
    const btnDecline = document.getElementById('btnDecline');

    // Reset all buttons
    [btnPropose, btnUpdate, btnSubmit, btnWithdraw, btnDecline].forEach(btn => {
        if (btn) btn.style.display = 'none';
    });

    // Show appropriate buttons based on status
    switch(status) {
        case 'new':
            if (btnPropose) btnPropose.style.display = '';
            if (btnDecline) btnDecline.style.display = '';
            break;
        case 'pending':
            if (btnWithdraw) btnWithdraw.style.display = '';
            break;
        case 'progress':
            if (btnUpdate) btnUpdate.style.display = '';
            if (btnSubmit) btnSubmit.style.display = '';
            break;
    }

    // Show progress section for in-progress projects
    const progressSection = document.getElementById('modalProgressSection');
    if (progressSection) {
        if (status === 'progress') {
            progressSection.style.display = 'block';
            const progress = data.progress || '65';
            const progressDetail = data.progressDetail || '32h of 50h';
            document.getElementById('modalProgressPercent').textContent = progress + '%';
            document.getElementById('modalProgressDetail').textContent = '(' + progressDetail + ')';
            document.getElementById('modalProgressFill').style.width = progress + '%';
        } else {
            progressSection.style.display = 'none';
        }
    }

    // Render expandable additional details from card data.
    const additionalSection = document.getElementById('reqAdditionalSection');
    const additionalDetails = document.getElementById('reqAdditionalDetails');
    if (additionalSection && additionalDetails) {
        const detailFieldMap = [
            { key: 'category', label: 'Category' },
            { key: 'statusLabel', label: 'Status' },
            { key: 'logged', label: 'Logged Time' },
            { key: 'progressDetail', label: 'Progress Detail' }
        ];

        const detailRows = detailFieldMap
            .map(field => {
                const value = data[field.key];
                if (!value) {
                    return '';
                }
                return '<div style="display:flex; gap:10px; align-items:flex-start;">' +
                    '<div style="min-width:120px; font-size:13px; color:#475569; font-weight:600;">' + field.label + '</div>' +
                    '<div style="font-size:13px; color:#334155;">' + value + '</div>' +
                '</div>';
            })
            .filter(Boolean)
            .join('');

        if (detailRows) {
            additionalDetails.innerHTML = detailRows;
            additionalSection.style.display = 'block';
        } else {
            additionalDetails.innerHTML = '';
            additionalSection.style.display = 'none';
        }
    }

    openModal(modal);
}

function openProposalModal(card) {
    const modal = document.getElementById('proposalModalRoot');

    if (card) {
        const client = card.querySelector('.item-name').textContent;
        const title = card.querySelector('.item-title').textContent;

        document.getElementById('proposalClient').textContent = client;
        document.getElementById('proposalTitle').textContent = title;
    }

    // Reset form
    document.getElementById('proposalAmount').value = '';
    document.getElementById('proposalTimeline').value = '';
    document.getElementById('proposalDescription').value = '';
    document.getElementById('proposalFiles').value = '';

    openModal(modal);
}

function openProgressModal(card) {
    const modal = document.getElementById('progressModalRoot');

    if (card) {
        const client = card.querySelector('.item-name').textContent;
        const title = card.querySelector('.item-title').textContent;

        document.getElementById('progressClient').textContent = client;
        document.getElementById('progressTitle').textContent = title;
    }

    // Reset form
    document.getElementById('progressPercent').value = '65';
    document.getElementById('progressPercentValue').textContent = '65%';
    document.getElementById('hoursWorked').value = '32';
    document.getElementById('progressDescription').value = '';
    document.getElementById('progressFiles').value = '';

    openModal(modal);
}

function openSubmitModal(card) {
    const modal = document.getElementById('submitModalRoot');

    if (card) {
        const client = card.querySelector('.item-name').textContent;
        const title = card.querySelector('.item-title').textContent;

        document.getElementById('submitClient').textContent = client;
        document.getElementById('submitTitle').textContent = title;
    }

    // Reset form
    document.getElementById('submitDescription').value = '';
    document.getElementById('submitFiles').value = '';

    openModal(modal);
}

function openConfirmModal(title, message, confirmAction) {
    const modal = document.getElementById('confirmModalRoot');

    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;

    // Set up confirm action
    const btnConfirmAction = document.getElementById('btnConfirmAction');
    btnConfirmAction.onclick = function() {
        confirmAction();
        closeModal(modal);
    };

    openModal(modal);
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.pop-up-section:not(.deactive)');
        openModals.forEach(modal => {
            closeModal(modal);
        });
    }
});
