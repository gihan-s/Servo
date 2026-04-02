(function () {
	const tabContainer = document.querySelector('.container-changer');
	const tabs = document.querySelectorAll('.container-changer .buttons');
	const sections = document.querySelectorAll('.request-content .requests-section');
	const detailsModalRoot = document.getElementById('bidDetailsModalRoot');
	const detailsModalClose = document.getElementById('bidDetailsModalClose');

	if (!tabContainer || !tabs.length || !sections.length) {
		return;
	}

	function activateSection(target) {
		tabs.forEach((tab) => {
			tab.classList.toggle('active', tab.dataset.target === target);
		});

		sections.forEach((section) => {
			section.classList.toggle('active', section.dataset.section === target);
			section.style.display = section.dataset.section === target ? 'block' : 'none';
		});
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

	window.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && detailsModalRoot && detailsModalRoot.classList.contains('active')) {
			closeDetails();
		}
	});

	activateSection('active');
})();
