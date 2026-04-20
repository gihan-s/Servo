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

// ── DOM refs ──────────────────────────────────────────────────────────────────
const bidModalRoot    = document.getElementById('bidModalRoot');
const bidModalTitle   = document.getElementById('bidModalTitle');
const bidModalClose   = document.getElementById('bidModalClose');
const bidForm         = document.getElementById('bidForm');
const bidFormAction   = document.getElementById('bidFormAction');
const bidPostId       = document.getElementById('bidPostId');
const bidIdField      = document.getElementById('bidIdField');
const bidProjectTitle = document.getElementById('bidProjectTitle');
const bidClientName   = document.getElementById('bidClientName');
const bidAmount       = document.getElementById('bidAmount');
const bidDuration     = document.getElementById('bidDuration');
const bidDurationUnit = document.getElementById('bidDurationUnit');
const bidMessage      = document.getElementById('bidMessage');

const searchInput  = document.getElementById('feedSearchInput');
const searchBtn    = document.getElementById('feedSearchBtn');
const filterRoot   = document.getElementById('feedFilterRoot');
const filterModal  = document.getElementById('feedFilterModal');
const filterBtn    = document.getElementById('feedFilterBtn');
const filterClose  = document.getElementById('feedFilterClose');
const filterApply  = document.getElementById('feedFilterApply');
const filterClear  = document.getElementById('feedFilterClear');
const categoryList = document.getElementById('feedCategoryList');

const viewRoot     = document.getElementById('feedViewRoot');
const viewClose    = document.getElementById('feedViewClose');
const viewTitle    = document.getElementById('feedViewTitle');
const viewClient   = document.getElementById('feedViewClient');
const viewDetails  = document.getElementById('feedViewDetails');
const viewBids     = document.getElementById('feedViewBids');
const viewBidsList = document.getElementById('feedViewBidsList');

const cancelBidForm = document.getElementById('cancelBidForm');
const cancelBidId   = document.getElementById('cancelBidId');

const cards = Array.from(document.querySelectorAll('.feed-card'));
let selectedCategories = new Set();

// ── Bid Modal ─────────────────────────────────────────────────────────────────
function openNewBidModal(card) {
	if (!bidModalRoot) return;
	bidModalTitle.textContent   = 'Submit Bid';
	if (bidFormAction) bidFormAction.value = 'submit';
	bidPostId.value             = card.dataset.postid || '';
	if (bidIdField) bidIdField.value = '';
	bidProjectTitle.textContent = card.dataset.title  || 'Project';
	bidClientName.textContent   = card.dataset.client || 'Client';
	bidAmount.value  = '';
	bidDuration.value = '';
	if (bidDurationUnit) bidDurationUnit.value = 'd';
	if (bidMessage) bidMessage.value = '';
	bidForm.action = (window.BASE_URL || '') + '/feed/submit-bid';
	openModal(bidModalRoot);
	bidAmount.focus();
}

function openEditBidModal(card) {
	if (!bidModalRoot) return;
	bidModalTitle.textContent   = 'Edit Your Bid';
	if (bidFormAction) bidFormAction.value = 'edit';
	bidPostId.value             = card.dataset.postid || '';
	if (bidIdField) bidIdField.value = card.dataset.mybidid || '';
	bidProjectTitle.textContent = card.dataset.title  || 'Project';
	bidClientName.textContent   = card.dataset.client || 'Client';
	bidAmount.value = card.dataset.mybidamount  || '';
	if (bidMessage) bidMessage.value = card.dataset.mybidcomment || '';

	const durationState = durationHoursToEditableValue(card.dataset.mybidduration);
	bidDuration.value = durationState.value;
	if (bidDurationUnit) bidDurationUnit.value = durationState.unit;

	bidForm.action = (window.BASE_URL || '') + '/feed/edit-bid';
	openModal(bidModalRoot);
	bidAmount.focus();
}

function closeBidModal() {
	closeModal(bidModalRoot);
}

// ── View Modal ────────────────────────────────────────────────────────────────
function openViewModal(card) {
	if (!viewRoot || !viewDetails) return;

	viewTitle.textContent  = card.dataset.title  || '-';
	viewClient.textContent = card.dataset.client || '-';

	const hasBid   = !!card.dataset.mybidid;
	const duration = card.dataset.mybidduration ? formatDurationFromHours(parseInt(card.dataset.mybidduration, 10)) : '-';

	const rows = [
		{ label: 'Category',    value: card.dataset.category    || '-' },
		{ label: 'Budget',      value: card.dataset.budget       || '-' },
		{ label: 'Price Type',  value: card.dataset.pricetype    || '-' },
		{ label: 'Level',       value: card.dataset.level        || '-' },
		{ label: 'Deadline',    value: calculateDeadline(card.dataset.deadlineraw) },
		{ label: 'Posted',      value: card.dataset.posted        || '-' },
		{ label: 'Description', value: card.dataset.description  || '-', wide: true },
	];

	if (hasBid) {
		rows.push(
			{ label: 'Your Bid Amount', value: 'Rs. ' + (card.dataset.mybidamount || '-'), highlight: true },
			{ label: 'Your Duration',   value: duration, highlight: true },
			{ label: 'Your Proposal',   value: card.dataset.mybidcomment || '-', wide: true, highlight: true },
		);
	}

	viewDetails.innerHTML = '';
	rows.forEach(({ label, value, wide, highlight }) => {
		const row = document.createElement('div');
		row.style.cssText = `display:grid; grid-template-columns:${wide ? '1fr' : '150px 1fr'}; gap:6px; align-items:start;`;
		if (highlight) {
			row.style.background   = '#f0fdf4';
			row.style.padding      = '6px 8px';
			row.style.borderRadius = '8px';
		}

		const lbl = document.createElement('div');
		lbl.style.cssText = 'font-weight:700; color:#111827; font-size:13px;';
		lbl.textContent   = label;

		const val = document.createElement('div');
		val.style.cssText = `color:#334155; font-size:13px; white-space:${wide ? 'pre-wrap' : 'normal'};`;
		val.textContent   = value;

		if (wide) row.style.gridTemplateColumns = '1fr';
		row.appendChild(lbl);
		row.appendChild(val);
		viewDetails.appendChild(row);
	});

	// Populate bids list
	if (viewBids && viewBidsList) {
		let bids = [];
		try { bids = JSON.parse(card.dataset.bids || '[]'); } catch (e) { bids = []; }
		if (bids.length > 0) {
			viewBidsList.innerHTML = '';
			bids.forEach(b => {
				const row = document.createElement('div');
				row.className = 'view-bid-row' + (b.is_mine ? ' is-mine' : '');
				row.innerHTML = `
					<span class="view-bid-name">${b.name}${b.is_mine ? ' <em style="font-size:11px;color:#16a34a;">(You)</em>' : ''}</span>
					<span class="view-bid-amount">Rs. ${Number(b.amount).toLocaleString('en-US', {minimumFractionDigits:2})}</span>
					<span class="view-bid-duration">${formatDurationFromHours(b.duration)}</span>
				`;
				viewBidsList.appendChild(row);
			});
			viewBids.style.display = '';
		} else {
			viewBids.style.display = 'none';
		}
	}

	openModal(viewRoot);
}

function closeViewModal() {
	if (!viewRoot) return;
	closeModal(viewRoot);
}

// ── Utility ───────────────────────────────────────────────────────────────────
function durationHoursToEditableValue(durationHours) {
	const hours = Number.parseInt(String(durationHours || '').trim(), 10);
	if (!Number.isFinite(hours) || hours <= 0) {
		return { value: '1', unit: 'd' };
	}

	if (hours % 720 === 0) {
		return { value: String(hours / 720), unit: 'm' };
	}

	if (hours % 168 === 0) {
		return { value: String(hours / 168), unit: 'w' };
	}

	if (hours % 24 === 0) {
		return { value: String(hours / 24), unit: 'd' };
	}

	return { value: String(Math.max(1, Math.ceil(hours / 24))), unit: 'd' };
}

function formatDurationFromHours(durationHours) {
	const hours = Number.parseInt(String(durationHours || '').trim(), 10);
	if (!Number.isFinite(hours) || hours <= 0) return '-';

	const days = Math.max(1, Math.ceil(hours / 24));
	if (days % 30 === 0) return `${days / 30} month${days / 30 > 1 ? 's' : ''}`;
	if (days % 7  === 0) return `${days / 7} week${days / 7 > 1 ? 's' : ''}`;
	return `${days} day${days > 1 ? 's' : ''}`;
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
	const diffMs = new Date(dateStr) - new Date();
	if (diffMs <= 0) return 'Deadline has passed';
	return formatDuration(diffMs);
}

function applyFiltersWrapper() {
	applyFilters(cards, searchInput, selectedCategories);
}

// ── Card button handlers ──────────────────────────────────────────────────────
document.querySelectorAll('.feed-card .btn-bid').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.feed-card');
		if (card) openNewBidModal(card);
	});
});

document.querySelectorAll('.feed-card .btn-edit-bid').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.feed-card');
		if (card) openEditBidModal(card);
	});
});

document.querySelectorAll('.feed-card .btn-cancel-bid').forEach((btn) => {
	btn.addEventListener('click', function () {
		const bidId = this.dataset.bidid || '';
		if (!bidId) return;
		if (!confirm('Are you sure you want to cancel your bid?')) return;
		if (cancelBidId) cancelBidId.value = bidId;
		if (cancelBidForm) cancelBidForm.submit();
	});
});

document.querySelectorAll('.feed-card .btn-message').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card  = this.closest('.feed-card');
		const cid   = card ? (card.dataset.clientid || '') : '';
		if (cid) window.location.href = (window.BASE_URL || '') + '/messages?new=' + cid;
	});
});

document.querySelectorAll('.feed-card .btn-view').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.feed-card');
		if (card) openViewModal(card);
	});
});

// ── Bid modal handlers ────────────────────────────────────────────────────────
bidModalClose && bidModalClose.addEventListener('click', closeBidModal);
bidModalRoot  && bidModalRoot.addEventListener('click', (e) => {
	if (e.target === bidModalRoot) closeBidModal();
});

bidForm && bidForm.addEventListener('submit', function (e) {
	if (!bidPostId || !bidPostId.value) {
		e.preventDefault();
		alert('Missing project ID for this bid.');
	}
});

// ── View modal handlers ───────────────────────────────────────────────────────
viewClose && viewClose.addEventListener('click', closeViewModal);
viewRoot  && viewRoot.addEventListener('click', (e) => {
	if (e.target === viewRoot) closeViewModal();
});

// ── Search / Filter ───────────────────────────────────────────────────────────
setupSearchHandlers(searchInput, searchBtn, applyFiltersWrapper);

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

setupEscapeKeyHandler([
	{ root: bidModalRoot },
	{ root: viewRoot },
	{ root: filterRoot, element: filterModal }
]);

// ── Init ──────────────────────────────────────────────────────────────────────
populateCategoryFilters(categoryList, cards, 'feedCategory');
applyFiltersWrapper();
