/**
 * Provider Common Utilities - ES6 Module
 * Shared functionality for provider views (Feed, Bids, Projects)
 */

/**
 * Apply search and filter logic to cards
 * @param {Array<HTMLElement>} cards - Array of card elements to filter
 * @param {HTMLInputElement} searchInput - Search input element
 * @param {Set<string>} selectedCategories - Set of selected category filters
 * @param {string} activeSectionKey - Current active section (optional, for multi-section views)
 */
export function applyFilters(cards, searchInput, selectedCategories, activeSectionKey = null) {
	const query = searchInput ? searchInput.value.trim().toLowerCase() : '';

	cards.forEach((card) => {
		const section = card.closest('.requests-section');
		const sectionKey = section ? section.dataset.section : '';
		const title = (card.dataset.title || '').toLowerCase();
		const category = (card.dataset.category || '').toLowerCase();
		const client = (card.dataset.client || '').toLowerCase();
		
		const matchesQuery = !query || title.includes(query) || category.includes(query) || client.includes(query);
		const matchesCategory = selectedCategories.size === 0 || selectedCategories.has(category);
		const matchesSection = !activeSectionKey || sectionKey === activeSectionKey;
		
		card.style.display = matchesQuery && matchesCategory && matchesSection ? '' : 'none';
	});
}

/**
 * Populate category filter checkboxes from cards data
 * @param {HTMLElement} categoryList - Container element for category checkboxes
 * @param {Array<HTMLElement>} cards - Array of card elements
 * @param {string} idPrefix - Prefix for checkbox IDs (e.g., 'feedCategory', 'bidsCategory')
 */
export function populateCategoryFilters(categoryList, cards, idPrefix) {
	if (!categoryList) return;

	const uniqueCategories = Array.from(
		new Set(
			cards
				.map((card) => (card.dataset.category || '').trim())
				.filter((category) => category.length > 0)
		)
	).sort((a, b) => a.localeCompare(b));

	categoryList.innerHTML = '';
	
	uniqueCategories.forEach((category, index) => {
		const listItem = document.createElement('li');
		const id = `${idPrefix}_${index}`;
		const checkbox = document.createElement('input');
		checkbox.type = 'checkbox';
		checkbox.id = id;
		checkbox.value = category;
		
		const label = document.createElement('label');
		label.htmlFor = id;
		label.textContent = category;
		
		listItem.appendChild(checkbox);
		listItem.appendChild(label);
		categoryList.appendChild(listItem);
	});
}

/**
 * Get selected categories from UI checkboxes
 * @param {HTMLElement} categoryList - Container element with category checkboxes
 * @returns {Set<string>} Set of selected category values (lowercase)
 */
export function getSelectedCategoriesFromUI(categoryList) {
	if (!categoryList) return new Set();

	const selected = new Set();
	categoryList.querySelectorAll('input[type="checkbox"]:checked').forEach((input) => {
		if (input.value) selected.add(input.value.toLowerCase());
	});
	
	return selected;
}

/**
 * Open a modal/popup
 * @param {HTMLElement} modalRoot - Modal root container
 * @param {HTMLElement} modalElement - Modal popup element (optional, for dual-class modals)
 */
export function openModal(modalRoot, modalElement = null) {
	if (!modalRoot) return;
	
	// Remove deactive and add active to show modal
	modalRoot.classList.remove('deactive');
	modalRoot.classList.add('active');
	
	if (modalElement) {
		modalElement.classList.remove('deactive');
		modalElement.classList.add('active');
	}
	
	// Prevent body scroll
	document.body.style.overflow = 'hidden';
}

/**
 * Close a modal/popup
 * @param {HTMLElement} modalRoot - Modal root container
 * @param {HTMLElement} modalElement - Modal popup element (optional, for dual-class modals)
 */
export function closeModal(modalRoot, modalElement = null) {
	if (!modalRoot) return;
	
	// Remove active first
	modalRoot.classList.remove('active');
	
	if (modalElement) {
		modalElement.classList.remove('active');
	}
	
	// Wait for animation to complete before hiding
	setTimeout(() => {
		modalRoot.classList.add('deactive');
		if (modalElement) {
			modalElement.classList.add('deactive');
		}
	}, 200); // Match animation duration
	
	// Restore body scroll
	document.body.style.overflow = '';
}

/**
 * Setup search input event handlers
 * @param {HTMLInputElement} searchInput - Search input element
 * @param {HTMLButtonElement} searchBtn - Search button element
 * @param {Function} filterCallback - Callback function to apply filters
 */
export function setupSearchHandlers(searchInput, searchBtn, filterCallback) {
	if (!searchInput) return;

	// Search button click
	if (searchBtn) {
		searchBtn.addEventListener('click', filterCallback);
	}

	// Input event (real-time search)
	searchInput.addEventListener('input', filterCallback);

	// Enter key
	searchInput.addEventListener('keydown', (e) => {
		if (e.key === 'Enter') {
			e.preventDefault();
			filterCallback();
		}
	});
}

/**
 * Setup filter modal event handlers
 * @param {Object} config - Configuration object
 * @param {HTMLElement} config.filterBtn - Filter button to open modal
 * @param {HTMLElement} config.filterRoot - Filter modal root
 * @param {HTMLElement} config.filterModal - Filter modal popup (optional)
 * @param {HTMLElement} config.filterClose - Close button
 * @param {HTMLElement} config.filterApply - Apply button
 * @param {HTMLElement} config.filterClear - Clear button
 * @param {HTMLElement} config.categoryList - Category list container
 * @param {Function} config.applyCallback - Callback to apply filters
 * @param {Function} config.clearCallback - Callback to clear filters
 */
export function setupFilterHandlers(config) {
	const {
		filterBtn,
		filterRoot,
		filterModal,
		filterClose,
		filterApply,
		filterClear,
		categoryList,
		applyCallback,
		clearCallback
	} = config;

	if (!filterRoot) return;

	// Open filter modal
	if (filterBtn) {
		filterBtn.addEventListener('click', () => openModal(filterRoot, filterModal));
	}

	// Close filter modal
	if (filterClose) {
		filterClose.addEventListener('click', () => closeModal(filterRoot, filterModal));
	}

	// Click outside to close
	filterRoot.addEventListener('click', (e) => {
		if (e.target === filterRoot) closeModal(filterRoot, filterModal);
	});

	// Apply filters
	if (filterApply && applyCallback) {
		filterApply.addEventListener('click', () => {
			applyCallback();
			closeModal(filterRoot, filterModal);
		});
	}

	// Clear filters
	if (filterClear && categoryList && clearCallback) {
		filterClear.addEventListener('click', () => {
			categoryList.querySelectorAll('input[type="checkbox"]').forEach((input) => {
				input.checked = false;
			});
			clearCallback();
		});
	}
}

/**
 * Setup escape key handler for modals
 * @param {Array<Object>} modals - Array of modal configurations: [{root, element}, ...]
 */
export function setupEscapeKeyHandler(modals) {
	window.addEventListener('keydown', (e) => {
		if (e.key !== 'Escape') return;

		for (const modal of modals) {
			const isActive = modal.root && (
				modal.root.classList.contains('active') ||
				!modal.root.classList.contains('deactive')
			);
			
			if (isActive) {
				closeModal(modal.root, modal.element);
				return;
			}
		}
	});
}

/**
 * Setup tab navigation (for views with multiple sections)
 * @param {HTMLElement} tabContainer - Container with tab buttons
 * @param {NodeList} tabs - Tab button elements
 * @param {NodeList} sections - Section elements to show/hide
 * @param {Function} onTabChange - Callback when tab changes (receives targetId)
 */
export function setupTabNavigation(tabContainer, tabs, sections, onTabChange = null) {
	if (!tabContainer || !tabs.length || !sections.length) return;

	tabContainer.addEventListener('click', (event) => {
		const tab = event.target.closest('.buttons');
		if (!tab || !tabContainer.contains(tab)) return;

		const targetId = tab.dataset.target || tab.id;

		// Update active tab
		tabs.forEach((t) => t.classList.toggle('active', t.dataset.target === targetId));

		// Show corresponding section
		sections.forEach((section) => {
			const sectionKey = section.dataset.section || section.classList[0];
			const isActive = sectionKey === targetId;
			section.classList.toggle('active', isActive);
			section.style.display = isActive ? 'block' : 'none';
		});

		// Trigger callback
		if (onTabChange) onTabChange(targetId);
	});
}
