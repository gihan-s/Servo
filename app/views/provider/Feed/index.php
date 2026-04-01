<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servo | Provider Feed</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/serviceProjects.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css" />
</head>

<body>
	<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

	<div class="main-content">
		<section class="service-requests" style="padding-top: 12px; padding-bottom: 28px;">
			<div class="header-requests">
				<h1>Provider Feed</h1>
				<p class="section-note">Discover matching opportunities and place bids directly from each card.</p>
			</div>

			<div class="request-content">
				<div class="requests-section active" style="display:block;">
					<div class="item-list">
						<?php foreach ($feedItems as $item): ?>
							<article class="search-item" data-client="<?= htmlspecialchars($item['client'], ENT_QUOTES, 'UTF-8') ?>" data-title="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>" data-budget="<?= htmlspecialchars($item['budget'], ENT_QUOTES, 'UTF-8') ?>" data-timeline="<?= htmlspecialchars($item['timeline'], ENT_QUOTES, 'UTF-8') ?>">
								<div class="item-head">
									<div class="item-main-dets">
										<div class="item-name"><?= htmlspecialchars($item['client'], ENT_QUOTES, 'UTF-8') ?></div>
										<div class="item-title"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></div>
										<div class="item-district">
											<span><i class="fa-solid fa-clock"></i> Posted <?= htmlspecialchars($item['posted'], ENT_QUOTES, 'UTF-8') ?></span>
											<span><i class="fa-solid fa-tag"></i> Budget: <?= htmlspecialchars($item['budget'], ENT_QUOTES, 'UTF-8') ?></span>
										</div>
									</div>
									<div class="button">
										<button class="btn-outline btn-like" type="button" title="Like"><i class="fa-regular fa-heart"></i> Like</button>
										<button class="btn-primary btn-bid" type="button" title="Place Bid"><i class="fa-solid fa-gavel"></i> Bid</button>
										<button class="btn-outline btn-message" type="button" title="Message"><i class="fa-regular fa-message"></i> Message</button>
										<button class="btn-outline" type="button" title="View"><i class="fa-regular fa-eye"></i> View</button>
									</div>
								</div>
								<div class="item-middle">
									<div><i class="fa-solid fa-calendar-days"></i> Timeline: <?= htmlspecialchars($item['timeline'], ENT_QUOTES, 'UTF-8') ?></div>
									<div><i class="fa-solid fa-layer-group"></i> Category: <?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></div>
								</div>
								<div class="item-description"><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></div>
								<div class="status-bottom"><span class="status-chip <?= htmlspecialchars($item['statusClass'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['statusLabel'], ENT_QUOTES, 'UTF-8') ?></span></div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	</div>

	<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

	<div class="pop-up-section request-modal deactive" id="bidModalRoot">
		<div class="pop-up" id="bidModal" style="max-width:680px; border-radius:16px;">
			<div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
				<div class="pop-up-title">Submit Bid</div>
				<i class="fa-solid fa-xmark" id="bidModalClose" style="cursor:pointer;"></i>
			</div>
			<hr>
			<form id="bidForm" class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
				<div style="font-size:14px; color:#334155;">You are bidding on: <strong id="bidProjectTitle">Project</strong></div>
				<div style="font-size:13px; color:#64748b;">Client: <span id="bidClientName">Client Name</span></div>

				<label for="bidAmount" style="font-weight:700; color:#111827;">Bid Amount</label>
				<input id="bidAmount" name="bidAmount" type="number" min="1" step="1" placeholder="Enter your amount" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>

				<label for="bidTimeline" style="font-weight:700; color:#111827;">Delivery Timeline</label>
				<input id="bidTimeline" name="bidTimeline" type="text" placeholder="e.g. 7 days" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>

				<label for="bidCover" style="font-weight:700; color:#111827;">Proposal Note</label>
				<textarea id="bidCover" name="bidCover" rows="5" placeholder="Write a short proposal..." style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px; resize:vertical;" required></textarea>

				<div class="modal-actions" style="margin-top:6px;">
					<button class="btn-primary" id="submitBidBtn" type="submit"><i class="fa-solid fa-paper-plane"></i> Submit Bid</button>
				</div>
			</form>
		</div>
	</div>

	<script src="<?= BASE_URL ?>/assets/js/providerFeed.js"></script>
</body>

</html>
