import {
	applyFilters,
	populateCategoryFilters,
	getSelectedCategoriesFromUI,
	openModal,
	closeModal,
	setupSearchHandlers,
	setupFilterHandlers,
	setupEscapeKeyHandler
} from './providerCommon.js';

const bidModalRoot = document.getElementById('bidModalRoot');
const bidModalClose = document.getElementById('bidModalClose');
const bidForm = document.getElementById('bidForm');
const bidPostId = document.getElementById('bidPostId');
const bidProjectTitle = document.getElementById('bidProjectTitle');
const bidClientName = document.getElementById('bidClientName');
const bidAmount = document.getElementById('bidAmount');
const bidDuration = document.getElementById('bidDuration');
const bidDurationUnit = document.getElementById('bidDurationUnit');
const searchInput = document.getElementById('feedSearchInput');
const searchBtn = document.getElementById('feedSearchBtn');
const filterRoot = document.getElementById('feedFilterRoot');
const filterModal = document.getElementById('feedFilterModal');
const filterBtn = document.getElementById('feedFilterBtn');
const filterClose = document.getElementById('feedFilterClose');
const filterApply = document.getElementById('feedFilterApply');
const filterClear = document.getElementById('feedFilterClear');
const categoryList = document.getElementById('feedCategoryList');
const viewRoot = document.getElementById('feedViewRoot');
const viewClose = document.getElementById('feedViewClose');
const viewTitle = document.getElementById('feedViewTitle');
const viewClient = document.getElementById('feedViewClient');
const viewDetails = document.getElementById('feedViewDetails');
const cards = Array.from(document.querySelectorAll('.search-item'));
let selectedCategories = new Set();

const viewFieldMap = [
	{ key: 'category', label: 'Category' },
	{ key: 'budget', label: 'Budget' },
	{ key: 'deadline', label: 'Deadline' },
	{ key: 'posted', label: 'Posted' },
	{ key: 'status', label: 'Status' },
	{ key: 'description', label: 'Description' }
];

function openBidModal(card) {
	const postId = card.dataset.postid || '';
	bidProjectTitle.textContent = card.dataset.title || 'Project';
	bidClientName.textContent = card.dataset.client || 'Client';
	if (bidPostId) bidPostId.value = postId;
	bidAmount.value = '';
	bidDuration.value = '';
	if (bidDurationUnit) bidDurationUnit.value = 'd';
	openModal(bidModalRoot);
	bidAmount.focus();
}

function closeBidModal() {
	closeModal(bidModalRoot);
}

function openViewModal(card) {
	if (!viewRoot || !viewDetails) return;

	viewTitle.textContent = card.dataset.title || '-';
	viewClient.textContent = card.dataset.client || '-';
	viewDetails.innerHTML = '';

	viewFieldMap.forEach((field) => {
		let value = card.dataset[field.key] || '-';

		// Format deadline as calculated time remaining
		if (field.key === 'deadline') {
			value = calculateDeadline(card.dataset.deadline);
		}

		const row = document.createElement('div');
		row.style.display = 'grid';
		row.style.gridTemplateColumns = '140px 1fr';
		row.style.gap = '10px';
		row.style.alignItems = 'start';

		const label = document.createElement('div');
		label.style.fontWeight = '700';
		label.style.color = '#111827';
		label.textContent = field.label;

		const content = document.createElement('div');
		content.style.color = '#334155';
		content.style.whiteSpace = field.key === 'description' ? 'pre-wrap' : 'normal';
		content.textContent = value;

		row.appendChild(label);
		row.appendChild(content);
		viewDetails.appendChild(row);
	});

	openModal(viewRoot);
}

function closeViewModal() {
	if (!viewRoot) return;
	closeModal(viewRoot);
}

function applyFiltersWrapper() {
	applyFilters(cards, searchInput, selectedCategories);
}

function formatDuration(diffMs) {
	const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));

	if (days < 1) return 'deadline has passed';
	if (days < 7) return `in ${days} day${days > 1 ? 's' : ''}`;

	const weeks = Math.floor(days / 7);
	if (weeks < 4) return `in ${weeks} week${weeks > 1 ? 's' : ''}`;

	const months = Math.floor(days / 30);
	return `in ${months} month${months > 1 ? 's' : ''}`;
}

function calculateDeadline(dateStr) {
	if (!dateStr) return 'N/A';
	const now = new Date();
	const deadline = new Date(dateStr);
	const diffMs = deadline - now;
	if (diffMs <= 0) return 'deadline has passed';
	return formatDuration(diffMs);
}

// Bid-specific button handlers
document.querySelectorAll('.search-item .btn-bid').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		if (card) openBidModal(card);
	});
});

document.querySelectorAll('.search-item .btn-message').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		const client = card ? (card.dataset.client || 'client') : 'client';
		alert('Open message thread with ' + client + ' (placeholder)');
	});
});

document.querySelectorAll('.search-item .btn-view').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		if (card) openViewModal(card);
	});
});

// Bid modal handlers
bidModalClose && bidModalClose.addEventListener('click', closeBidModal);
bidModalRoot && bidModalRoot.addEventListener('click', (e) => {
	if (e.target === bidModalRoot) closeBidModal();
});

bidForm && bidForm.addEventListener('submit', function (e) {
	if (!bidPostId || !bidPostId.value) {
		e.preventDefault();
		alert('Missing project ID for this bid.');
		return;
	}
});

// View modal handlers
viewClose && viewClose.addEventListener('click', closeViewModal);
viewRoot && viewRoot.addEventListener('click', (e) => {
	if (e.target === viewRoot) closeViewModal();
});

// Setup search handlers using common module
setupSearchHandlers(searchInput, searchBtn, applyFiltersWrapper);

// Setup filter handlers using common module
setupFilterHandlers({
	filterBtn,
	filterRoot,
	filterModal,
	filterClose,
	filterApply,
	filterClear,
	categoryList,
	applyCallback: () => {
		selectedCategories = getSelectedCategoriesFromUI(categoryList);
		applyFiltersWrapper();
	},
	clearCallback: () => {
		selectedCategories = new Set();
		applyFiltersWrapper();
	}
});

// Setup escape key handler for all modals
setupEscapeKeyHandler([
	{ root: bidModalRoot },
	{ root: viewRoot },
	{ root: filterRoot, element: filterModal }
]);

// Initialize
populateCategoryFilters(categoryList, cards, 'feedCategory');
applyFiltersWrapper();

// Update deadline displays with calculated time remaining
cards.forEach(card => {
	const deadline = card.dataset.deadline;
	if (deadline) {
		const deadlineElements = card.querySelectorAll('div');
		deadlineElements.forEach(el => {
			if (el.textContent.startsWith('Deadline:')) {
				el.innerHTML = `<i class="fa-solid fa-calendar-days"></i> Deadline: ${calculateDeadline(deadline)}`;
			}
		});
	}
});
