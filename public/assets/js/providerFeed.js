(function () {
	const bidModalRoot = document.getElementById('bidModalRoot');
	const bidModalClose = document.getElementById('bidModalClose');
	const bidForm = document.getElementById('bidForm');
	const bidProjectTitle = document.getElementById('bidProjectTitle');
	const bidClientName = document.getElementById('bidClientName');
	const bidAmount = document.getElementById('bidAmount');
	const bidTimeline = document.getElementById('bidTimeline');

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

	document.querySelectorAll('.search-item .btn-bid').forEach((btn) => {
		btn.addEventListener('click', function () {
			const card = this.closest('.search-item');
			if (!card) {
				return;
			}
			openBidModal(card);
		});
	});

	document.querySelectorAll('.search-item .btn-like').forEach((btn) => {
		btn.addEventListener('click', function () {
			this.classList.toggle('liked');
			const icon = this.querySelector('i');
			if (!icon) {
				return;
			}
			if (this.classList.contains('liked')) {
				icon.classList.remove('fa-regular');
				icon.classList.add('fa-solid');
				this.setAttribute('aria-pressed', 'true');
			} else {
				icon.classList.remove('fa-solid');
				icon.classList.add('fa-regular');
				this.setAttribute('aria-pressed', 'false');
			}
		});
	});

	document.querySelectorAll('.search-item .btn-message').forEach((btn) => {
		btn.addEventListener('click', function () {
			const card = this.closest('.search-item');
			const client = card ? (card.dataset.client || 'client') : 'client';
			alert('Open message thread with ' + client + ' (placeholder)');
		});
	});

	bidModalClose && bidModalClose.addEventListener('click', closeBidModal);
	bidModalRoot && bidModalRoot.addEventListener('click', (e) => {
		if (e.target === bidModalRoot) {
			closeBidModal();
		}
	});

	window.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && bidModalRoot.classList.contains('active')) {
			closeBidModal();
		}
	});

	bidForm && bidForm.addEventListener('submit', function (e) {
		e.preventDefault();
		alert('Bid submitted (placeholder)');
		closeBidModal();
	});
})();
