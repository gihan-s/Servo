(function () {
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
	const cards = Array.from(document.querySelectorAll('.request-content .search-item[data-title]'));
	let selectedCategories = new Set();
	let activeSectionKey = 'active';

	if (!tabContainer || !tabs.length || !sections.length) {
		return;
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
			card.style.display = matchesSection && matchesQuery && matchesCategory ? '' : 'none';
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
			const id = 'bidsCategory_' + index;
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

	function activateSection(target) {
		activeSectionKey = target;
		tabs.forEach((tab) => {
			tab.classList.toggle('active', tab.dataset.target === target);
		});

		sections.forEach((section) => {
			section.classList.toggle('active', section.dataset.section === target);
			section.style.display = section.dataset.section === target ? 'block' : 'none';
		});

		applyFilters();
	}

	tabContainer.addEventListener('click', (event) => {
		const tab = event.target.closest('.buttons');
		if (!tab || !tabContainer.contains(tab)) {
			return;
		}

		activateSection(tab.dataset.target || tab.id || 'active');
	});

	function openDetails(card) {
		document.getElementById('bidDetailTitle').textContent = card.dataset.title || 'Project';
		document.getElementById('bidDetailClient').textContent = card.dataset.client || 'Client';
		document.getElementById('bidDetailAmount').textContent = card.dataset.amount || '$0';
		document.getElementById('bidDetailTimeline').textContent = card.dataset.timeline || '-';
		document.getElementById('bidDetailRef').textContent = card.dataset.ref || '-';
		document.getElementById('bidDetailDescription').textContent = card.dataset.description || '';

		detailsModalRoot.classList.remove('deactive');
		detailsModalRoot.classList.add('active');
		document.body.style.overflow = 'hidden';
	}

	function closeDetails() {
		detailsModalRoot.classList.remove('active');
		detailsModalRoot.classList.add('deactive');
		document.body.style.overflow = '';
	}

	document.querySelectorAll('.search-item .btn-view').forEach((btn) => {
		btn.addEventListener('click', function () {
			const card = this.closest('.search-item');
			if (!card) {
				return;
			}
			openDetails(card);
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
				// Placeholder behavior until backend endpoint is wired.
				if (card) {
					card.remove();
				}
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

	detailsModalClose && detailsModalClose.addEventListener('click', closeDetails);
	detailsModalRoot && detailsModalRoot.addEventListener('click', (event) => {
		if (event.target === detailsModalRoot) {
			closeDetails();
		}
	});

	filterBtn && filterBtn.addEventListener('click', openFilterModal);
	filterClose && filterClose.addEventListener('click', closeFilterModal);
	filterRoot && filterRoot.addEventListener('click', (event) => {
		if (event.target === filterRoot) {
			closeFilterModal();
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

	searchInput && searchInput.addEventListener('keydown', function (event) {
		if (event.key === 'Enter') {
			event.preventDefault();
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

	window.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && detailsModalRoot && detailsModalRoot.classList.contains('active')) {
			closeDetails();
			return;
		}
		if (event.key === 'Escape' && filterRoot && !filterRoot.classList.contains('deactive')) {
			closeFilterModal();
		}
	});

	populateCategoryFilters();
	activateSection('active');
})();
