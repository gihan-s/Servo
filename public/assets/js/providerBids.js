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
const editModalRoot = document.getElementById('bidEditModalRoot');
const editModal = document.getElementById('bidEditModal');
const editModalClose = document.getElementById('bidEditModalClose');
const editForm = document.getElementById('bidEditForm');
const withdrawModalRoot = document.getElementById('bidWithdrawModalRoot');
const withdrawModal = document.getElementById('bidWithdrawModal');
const withdrawModalClose = document.getElementById('bidWithdrawModalClose');
const withdrawForm = document.getElementById('bidWithdrawForm');
const withdrawBidId = document.getElementById('withdrawBidId');
const withdrawCancelBtn = document.getElementById('bidWithdrawCancelBtn');
const withdrawConfirmBtn = document.getElementById('bidWithdrawConfirmBtn');
const editBidId = document.getElementById('editBidId');
const editBidProjectTitle = document.getElementById('editBidProjectTitle');
const editBidClientName = document.getElementById('editBidClientName');
const editBidAmount = document.getElementById('editBidAmount');
const editBidDuration = document.getElementById('editBidDuration');
const editBidDurationUnit = document.getElementById('editBidDurationUnit');
const editBidMessage = document.getElementById('editBidMessage');
const editBidSubmitBtn = document.getElementById('editBidSubmitBtn');
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
let activeEditCard = null;
let activeWithdrawCard = null;

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

function parsePositiveInt(value, fallback = 1) {
	const parsed = Number.parseInt(String(value || '').trim(), 10);
	return Number.isFinite(parsed) && parsed > 0 ? parsed : fallback;
}

function parseAmount(rawAmount, formattedAmount) {
	const parsedRaw = Number.parseFloat(String(rawAmount || '').replace(/[^\d.]/g, ''));
	if (Number.isFinite(parsedRaw) && parsedRaw > 0) {
		return parsedRaw;
	}

	const parsedFormatted = Number.parseFloat(String(formattedAmount || '').replace(/[^\d.]/g, ''));
	return Number.isFinite(parsedFormatted) && parsedFormatted > 0 ? parsedFormatted : 1;
}

function durationHoursToEditableValue(durationHours) {
	const hours = parsePositiveInt(durationHours, 24);
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

function updateCardAfterEdit(card, bidData) {
	if (!card || !bidData) return;

	const amountFormatted = bidData.amountFormatted || card.dataset.amount || '-';
	const amountRaw = String(bidData.amount ?? card.dataset.amountRaw ?? '').trim();
	const durationLabel = bidData.durationLabel || card.dataset.duration || '-';
	const durationHours = String(bidData.durationHours ?? card.dataset.durationHours ?? '').trim();
	const durationState = durationHoursToEditableValue(durationHours);
	const comment = bidData.comment || card.dataset.description || '';
	const status = bidData.status || card.dataset.status || 'Active';
	const statusKey = bidData.statusKey || card.dataset.statusKey || 'active';
	const bidDateLabel = bidData.bidDateLabel || card.dataset.bidDateLabel || '';

	card.dataset.amount = amountFormatted;
	card.dataset.amountRaw = amountRaw;
	card.dataset.duration = durationLabel;
	card.dataset.durationHours = durationHours;
	card.dataset.durationUnitValue = durationState.value;
	card.dataset.durationUnit = durationState.unit;
	card.dataset.description = comment;
	card.dataset.status = status;
	card.dataset.statusKey = statusKey;
	if (bidDateLabel) {
		card.dataset.bidDateLabel = bidDateLabel;
	}

	const districtSpans = card.querySelectorAll('.item-district span');
	if (districtSpans[0] && bidDateLabel) {
		districtSpans[0].innerHTML = '<i class="fa-solid fa-clock"></i> Bid placed ' + bidDateLabel;
	}
	if (districtSpans[1]) {
		districtSpans[1].innerHTML = '<i class="fa-solid fa-tag"></i> Your bid: ' + amountFormatted;
	}

	const middleDivs = card.querySelectorAll('.item-middle div');
	if (middleDivs[0]) {
		middleDivs[0].innerHTML = '<i class="fa-solid fa-calendar-days"></i> Duration: ' + durationLabel;
	}

	const desc = card.querySelector('.item-description');
	if (desc) {
		desc.textContent = comment;
	}

	const statusChip = card.querySelector('.status-bottom .status-chip');
	if (statusChip) {
		statusChip.textContent = status;
		statusChip.className = 'status-chip status-' + statusKey;
	}
}

function openEditModal(card) {
	if (!editForm || !editModalRoot || !editBidId || !editBidAmount || !editBidDuration || !editBidDurationUnit || !editBidMessage) {
		return;
	}

	activeEditCard = card;

	const bidId = card.dataset.bidId || '';
	const title = card.dataset.title || 'Project';
	const client = card.dataset.client || 'Client';
	const amount = parseAmount(card.dataset.amountRaw, card.dataset.amount);
	const datasetDurationValue = parsePositiveInt(card.dataset.durationUnitValue, 0);
	const datasetDurationUnit = (card.dataset.durationUnit || '').trim().toLowerCase();
	const durationState = datasetDurationValue > 0 && ['d', 'w', 'm'].includes(datasetDurationUnit)
		? { value: String(datasetDurationValue), unit: datasetDurationUnit }
		: durationHoursToEditableValue(card.dataset.durationHours);
	const comment = card.dataset.description || '';

	editForm.reset();
	editBidId.value = bidId;
	editBidProjectTitle.textContent = title;
	editBidClientName.textContent = client;
	editBidAmount.value = String(Math.max(1, Math.round(amount)));
	editBidDuration.value = durationState.value;
	editBidDurationUnit.value = durationState.unit;
	editBidMessage.value = comment;

	openModal(editModalRoot, editModal);
	editBidAmount.focus();
}

function closeEditModal() {
	closeModal(editModalRoot, editModal);
	activeEditCard = null;
}

function openWithdrawModal(card) {
	if (!withdrawForm || !withdrawModalRoot || !withdrawModal || !withdrawBidId) {
		return;
	}

	activeWithdrawCard = card;
	withdrawForm.reset();
	withdrawBidId.value = card.dataset.bidId || '';
	openModal(withdrawModalRoot, withdrawModal);
}

function closeWithdrawModal() {
	closeModal(withdrawModalRoot, withdrawModal);
	activeWithdrawCard = null;
	if (withdrawBidId) {
		withdrawBidId.value = '';
	}
}

async function submitEditForm(event) {
	event.preventDefault();
	if (!editForm || !activeEditCard) return;

	const previousLabel = editBidSubmitBtn ? editBidSubmitBtn.innerHTML : '';
	if (editBidSubmitBtn) {
		editBidSubmitBtn.disabled = true;
		editBidSubmitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
	}

	try {
		const response = await fetch(editForm.action, {
			method: 'POST',
			body: new FormData(editForm),
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});

		let payload = null;
		try {
			payload = await response.json();
		} catch (_error) {
			payload = null;
		}

		if (!response.ok || !payload || payload.success !== true) {
			const message = payload && payload.message ? payload.message : 'Unable to update bid.';
			alert(message);
			return;
		}

		updateCardAfterEdit(activeEditCard, payload.bid || {});
		applyFiltersWrapper();
		closeEditModal();
		alert(payload.message || 'Bid updated successfully.');
	} catch (_error) {
		alert('Unable to update bid right now. Please try again.');
	} finally {
		if (editBidSubmitBtn) {
			editBidSubmitBtn.disabled = false;
			editBidSubmitBtn.innerHTML = previousLabel;
		}
	}
}

async function submitWithdrawForm(event) {
	event.preventDefault();
	if (!withdrawForm || !activeWithdrawCard) return;

	const previousLabel = withdrawConfirmBtn ? withdrawConfirmBtn.innerHTML : '';
	if (withdrawConfirmBtn) {
		withdrawConfirmBtn.disabled = true;
		withdrawConfirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Withdrawing...';
	}

	try {
		const response = await fetch(withdrawForm.action, {
			method: 'POST',
			body: new FormData(withdrawForm),
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});

		let payload = null;
		try {
			payload = await response.json();
		} catch (_error) {
			payload = null;
		}

		if (!response.ok || !payload || payload.success !== true) {
			const message = payload && payload.message ? payload.message : 'Unable to withdraw bid.';
			alert(message);
			return;
		}

		activeWithdrawCard.remove();
		applyFiltersWrapper();
		closeWithdrawModal();
		alert(payload.message || 'Bid withdrawn successfully.');
	} catch (_error) {
		alert('Unable to withdraw bid right now. Please try again.');
	} finally {
		if (withdrawConfirmBtn) {
			withdrawConfirmBtn.disabled = false;
			withdrawConfirmBtn.innerHTML = previousLabel;
		}
	}
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
		if (card) {
			openWithdrawModal(card);
		}
	});
});

document.querySelectorAll('.search-item .btn-edit').forEach((btn) => {
	btn.addEventListener('click', function () {
		const card = this.closest('.search-item');
		if (card) {
			openEditModal(card);
		}
	});
});

// Details modal handlers
detailsModalClose && detailsModalClose.addEventListener('click', closeDetails);
detailsModalRoot && detailsModalRoot.addEventListener('click', (event) => {
	if (event.target === detailsModalRoot) closeDetails();
});

editModalClose && editModalClose.addEventListener('click', closeEditModal);
editModalRoot && editModalRoot.addEventListener('click', (event) => {
	if (event.target === editModalRoot) closeEditModal();
});
editForm && editForm.addEventListener('submit', submitEditForm);

withdrawModalClose && withdrawModalClose.addEventListener('click', closeWithdrawModal);
withdrawCancelBtn && withdrawCancelBtn.addEventListener('click', closeWithdrawModal);
withdrawModalRoot && withdrawModalRoot.addEventListener('click', (event) => {
	if (event.target === withdrawModalRoot) closeWithdrawModal();
});
withdrawForm && withdrawForm.addEventListener('submit', submitWithdrawForm);

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
	{ root: editModalRoot, element: editModal },
	{ root: withdrawModalRoot, element: withdrawModal },
	{ root: filterRoot, element: filterModal }
]);

// Initialize
populateCategoryFilters(categoryList, cards, 'bidsCategory');
activeSectionKey = 'active';
applyFiltersWrapper();
