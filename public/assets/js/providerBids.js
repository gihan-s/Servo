import {
	applyFilters,
	populateCategoryFilters,
	getSelectedCategoriesFromUI,
	openModal,
	closeModal,
	setupSearchHandlers,
	setupFilterHandlers,
	setupEscapeKeyHandler,
	setupTabNavigation
} from './providerCommon.js';

const tabContainer = document.querySelector('.container-changer');
const tabs = document.querySelectorAll('.container-changer .buttons');
const sections = document.querySelectorAll('.request-content .requests-section');
const detailsModalRoot = document.getElementById('bidDetailsModalRoot');
const detailsModalClose = document.getElementById('bidDetailsModalClose');
const searchInput = document.getElementById('bidsSearchInput');
const searchBtn = document.getElementById('bidsSearchBtn');
const filterRoot = document.getElementById('bidsFilterRoot');
const filterModal = document.getElementById('bidsFilterModal');
const filterBtn = document.getElementById('bidsFilterBtn');
const filterClose = document.getElementById('bidsFilterClose');
const filterApply = document.getElementById('bidsFilterApply');
const filterClear = document.getElementById('bidsFilterClear');
const categoryList = document.getElementById('bidsCategoryList');
const bidDetailTitle = document.getElementById('bidDetailTitle');
const bidDetailClient = document.getElementById('bidDetailClient');
const bidDetailDetails = document.getElementById('bidDetailDetails');
const cards = Array.from(document.querySelectorAll('.request-content .search-item[data-title]'));
let selectedCategories = new Set();
let activeSectionKey = 'active';

const bidDetailFieldMap = [
	{ key: 'amount', label: 'Bid Amount' },
	{ key: 'duration', label: 'Duration' },
	{ key: 'category', label: 'Category' },
	{ key: 'status', label: 'Status' },
	{ key: 'description', label: 'Description' }
];

if (!tabContainer || !tabs.length || !sections.length) {
	throw new Error('Required tab elements not found');
}

function applyFiltersWrapper() {
	applyFilters(cards, searchInput, selectedCategories, activeSectionKey);
}

function openDetails(card) {
	if (!bidDetailTitle || !bidDetailClient || !bidDetailDetails) return;

	bidDetailTitle.textContent = card.dataset.title || 'Bid Details';
	bidDetailClient.textContent = card.dataset.client || '-';
	bidDetailDetails.innerHTML = '';

	bidDetailFieldMap.forEach((field) => {
		const value = card.dataset[field.key] || '-';
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
		bidDetailDetails.appendChild(row);
	});

	openModal(detailsModalRoot);
}

function closeDetails() {
	closeModal(detailsModalRoot);
}

// Bid-specific button handlers
document.querySelectorAll('.search-item .btn-view').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		if (card) openDetails(card);
	});
});

document.querySelectorAll('.search-item .btn-message').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		const client = card ? card.dataset.client || 'client' : 'client';
		alert('Open message thread with ' + client + ' (placeholder)');
	});
});

document.querySelectorAll('.search-item .btn-withdraw').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		const title = card ? card.dataset.title || 'this bid' : 'this bid';
		if (confirm('Withdraw bid for "' + title + '"?')) {
			if (card) card.remove();
			alert('Bid withdrawn. Withdrawn bids are removed from your list.');
		}
	});
});

document.querySelectorAll('.search-item .btn-edit').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		const title = card ? card.dataset.title || 'this bid' : 'this bid';
		alert(
			'Edit for "' + title + '" will be processed as withdraw + new bid in backend. ' +
			'This complexity stays hidden from you (placeholder).'
		);
	});
});

// Details modal handlers
detailsModalClose && detailsModalClose.addEventListener('click', closeDetails);
detailsModalRoot && detailsModalRoot.addEventListener('click', (event) => {
	if (event.target === detailsModalRoot) closeDetails();
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

// Setup tab navigation using common module
setupTabNavigation(tabContainer, tabs, sections, (targetId) => {
	activeSectionKey = targetId;
	applyFiltersWrapper();
});

// Setup escape key handler for all modals
setupEscapeKeyHandler([
	{ root: detailsModalRoot },
	{ root: filterRoot, element: filterModal }
]);

// Initialize
populateCategoryFilters(categoryList, cards, 'bidsCategory');
activeSectionKey = 'active';
applyFiltersWrapper();
