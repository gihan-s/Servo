(function () {
	const bidModalRoot = document.getElementById('bidModalRoot');
	const bidModalClose = document.getElementById('bidModalClose');
	const bidForm = document.getElementById('bidForm');
	const bidProjectTitle = document.getElementById('bidProjectTitle');
	const bidClientName = document.getElementById('bidClientName');
	const bidAmount = document.getElementById('bidAmount');
	const bidTimeline = document.getElementById('bidTimeline');
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
		{ key: 'timeline', label: 'Timeline' },
		{ key: 'posted', label: 'Posted' },
		{ key: 'statusLabel', label: 'Status' },
		{ key: 'description', label: 'Description' }
	];

	function openBidModal(card) {
		bidProjectTitle.textContent = card.dataset.title || 'Project';
		bidClientName.textContent = card.dataset.client || 'Client';
		bidAmount.value = '';
		bidTimeline.value = card.dataset.timeline || '';
		bidModalRoot.classList.remove('deactive');
		bidModalRoot.classList.add('active');
		document.body.style.overflow = 'hidden';
		bidAmount.focus();
	}

	function closeBidModal() {
		bidModalRoot.classList.remove('active');
		bidModalRoot.classList.add('deactive');
		document.body.style.overflow = '';
	}

	function openViewModal(card) {
		if (!viewRoot || !viewDetails) {
			return;
		}

		viewTitle.textContent = card.dataset.title || '-';
		viewClient.textContent = card.dataset.client || '-';
		viewDetails.innerHTML = '';

		viewFieldMap.forEach((field) => {
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
			viewDetails.appendChild(row);
		});

		viewRoot.classList.remove('deactive');
		viewRoot.classList.add('active');
		document.body.style.overflow = 'hidden';
	}

	function closeViewModal() {
		if (!viewRoot) {
			return;
		}
		viewRoot.classList.remove('active');
		viewRoot.classList.add('deactive');
		document.body.style.overflow = '';
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
			const title = (card.dataset.title || '').toLowerCase();
			const category = (card.dataset.category || '').toLowerCase();
			const client = (card.dataset.client || '').toLowerCase();
			const matchesQuery = !query || title.includes(query) || category.includes(query) || client.includes(query);
			const matchesCategory = selectedCategories.size === 0 || selectedCategories.has(category);
			card.style.display = matchesQuery && matchesCategory ? '' : 'none';
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
			const id = 'feedCategory_' + index;
			listItem.innerHTML = '<input type="checkbox" id="' + id + '" value="' + category + '"><label for="' + id + '">' + category + '</label>';
			categoryList.appendChild(listItem);
		});

		categoryList.querySelectorAll('li').forEach((option) => {
			option.addEventListener('click', function (event) {
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

	document.querySelectorAll('.search-item .btn-bid').forEach((btn) => {
		btn.addEventListener('click', function () {
			const card = this.closest('.search-item');
			if (!card) {
				return;
			}
			openBidModal(card);
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
			if (!card) {
				return;
			}
			openViewModal(card);
		});
	});

	bidModalClose && bidModalClose.addEventListener('click', closeBidModal);
	bidModalRoot && bidModalRoot.addEventListener('click', (e) => {
		if (e.target === bidModalRoot) {
			closeBidModal();
		}
	});

	filterBtn && filterBtn.addEventListener('click', openFilterModal);
	filterClose && filterClose.addEventListener('click', closeFilterModal);
	filterRoot && filterRoot.addEventListener('click', (e) => {
		if (e.target === filterRoot) {
			closeFilterModal();
		}
	});

	viewClose && viewClose.addEventListener('click', closeViewModal);
	viewRoot && viewRoot.addEventListener('click', (e) => {
		if (e.target === viewRoot) {
			closeViewModal();
		}
	});

	filterApply && filterApply.addEventListener('click', function () {
		selectedCategories = getSelectedCategoriesFromUI();
		applyFilters();
		closeFilterModal();
	});

	filterClear && filterClear.addEventListener('click', function () {
		selectedCategories = new Set();
		if (categoryList) {
			categoryList.querySelectorAll('input[type="checkbox"]').forEach((input) => {
				input.checked = false;
			});
		}
		applyFilters();
	});

	searchBtn && searchBtn.addEventListener('click', function () {
		applyFilters();
	});

	searchInput && searchInput.addEventListener('input', function () {
		applyFilters();
	});

	searchInput && searchInput.addEventListener('keydown', function (e) {
		if (e.key === 'Enter') {
			e.preventDefault();
			applyFilters();
		}
	});

	searchInput && searchInput.addEventListener('focusin', function () {
		const searchButtonRoot = this.closest('.search-button');
		searchButtonRoot && searchButtonRoot.classList.add('focus');
	});

	searchInput && searchInput.addEventListener('focusout', function () {
		const searchButtonRoot = this.closest('.search-button');
		searchButtonRoot && searchButtonRoot.classList.remove('focus');
	});

	window.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && bidModalRoot.classList.contains('active')) {
			closeBidModal();
			return;
		}
		if (e.key === 'Escape' && viewRoot && viewRoot.classList.contains('active')) {
			closeViewModal();
			return;
		}
		if (e.key === 'Escape' && filterRoot && !filterRoot.classList.contains('deactive')) {
			closeFilterModal();
		}
	});

	bidForm && bidForm.addEventListener('submit', function (e) {
		e.preventDefault();
		alert('Bid submitted (placeholder)');
		closeBidModal();
	});

	populateCategoryFilters();
	applyFilters();
})();
