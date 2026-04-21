<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servo | Provider Bids</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/serviceProjects.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css" />
	<style>
		html,
		body {
			height: 100%;
		}

		body.bids-page {
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		body.bids-page .main-content {
			flex: 1 0 auto;
			width: 100%;
		}

		body.bids-page footer {
			margin-top: auto;
		}
	</style>
</head>

<body class="bids-page">
	<?php 
	require_once __DIR__ . '/../../includes/navbar.php';
	require_once __DIR__ . '/../../components/SearchHeader.php';
	require_once __DIR__ . '/../../components/FilterModal.php';
	?>

	<div class="main-content">
		<section class="service-requests" style="padding-top: 12px; padding-bottom: 28px;">
			<?php
			$searchHeader = new SearchHeader([
				'inputId' => 'bidsSearchInput',
				'placeholder' => 'Search by title, category, or client...',
				'filterBtnId' => 'bidsFilterBtn',
				'searchBtnId' => 'bidsSearchBtn',
				'title' => 'Placed Bids',
				'note' => 'Your placed bids are organized here. View details, message clients, or edit/withdraw active bids while you wait for client decisions.',
				'showTabs' => true,
				'tabs' => [
					['id' => 'active', 'label' => 'Active Bids', 'target' => 'active', 'active' => true],
					['id' => 'accepted', 'label' => 'Accepted', 'target' => 'accepted'],
					['id' => 'closed', 'label' => 'Closed', 'target' => 'closed']
				]
			]);
			$searchHeader->render();
			?>

			<div class="request-content">
				<?php
				$sections = [
					'active' => ['label' => 'Pending bids waiting for client acceptance or rejection.', 'items' => $activeBids],
					'accepted' => ['label' => 'Bids accepted by clients.', 'items' => $acceptedBids],
					'closed' => ['label' => 'Bids that are no longer viable.', 'items' => $closedBids],
				];
				?>

				<?php foreach ($sections as $key => $section): ?>
					<div class="requests-section <?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?> <?= $key === 'active' ? 'active' : '' ?>" data-section="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" style="display: <?= $key === 'active' ? 'block' : 'none' ?>;">
						<p class="section-note"><?= htmlspecialchars($section['label'], ENT_QUOTES, 'UTF-8') ?></p>
						<div class="item-list">
							<?php if (empty($section['items'])): ?>
								<article class="search-item">
									<div class="item-head">
										<div class="item-main-dets">
											<div class="item-title">No bids in this section yet.</div>
										</div>
									</div>
								</article>
							<?php endif; ?>

							<?php foreach ($section['items'] as $bid): ?>
								<article class="search-item"
									data-bid-id="<?= htmlspecialchars((string) ($bid['Bid_ID'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
									data-client="<?= htmlspecialchars($bid['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-title="<?= htmlspecialchars($bid['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-category="<?= htmlspecialchars($bid['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-amount="<?= htmlspecialchars($bid['Bid_Amount'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-amount-raw="<?= htmlspecialchars((string) ($bid['Amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
									data-duration="<?= htmlspecialchars($bid['Duration'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-duration-hours="<?= htmlspecialchars((string) ($bid['Duration_Hours'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
									data-duration-unit-value="<?= htmlspecialchars((string) ($bid['Duration_Unit_Value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
									data-duration-unit="<?= htmlspecialchars((string) ($bid['Duration_Unit'] ?? 'd'), ENT_QUOTES, 'UTF-8') ?>"
									data-ref="<?= htmlspecialchars($bid['Bid_Ref'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-status="<?= htmlspecialchars($bid['Status'] ?? 'Closed', ENT_QUOTES, 'UTF-8') ?>"
									data-status-key="<?= htmlspecialchars($bid['Status_Key'] ?? strtolower($bid['Status'] ?? 'closed'), ENT_QUOTES, 'UTF-8') ?>"
									data-description="<?= htmlspecialchars($bid['Comment'] ?? $bid['Post_Description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
									<div class="item-head">
										<div class="item-main-dets">
											<div class="item-name"><?= htmlspecialchars($bid['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
											<div class="item-title"><?= htmlspecialchars($bid['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
											<div class="item-district">
												<span><i class="fa-solid fa-clock"></i> Bid placed <?= htmlspecialchars($bid['Bid_Date'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
												<span><i class="fa-solid fa-tag"></i> Your bid: <?= htmlspecialchars($bid['Bid_Amount'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
											</div>
										</div>
										<div class="button">
											<button class="btn-outline btn-view" type="button" title="View"><i class="fa-regular fa-eye"></i> View</button>
											<button class="btn-outline btn-message" type="button" title="Message"><i class="fa-regular fa-message"></i> Message</button>
											<?php if ($key === 'active'): ?>
												<button class="btn-outline btn-edit" type="button" title="Edit Bid"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
												<button class="btn-danger btn-withdraw" type="button" title="Withdraw"><i class="fa-solid fa-trash"></i> Withdraw</button>
											<?php endif; ?>
										</div>
									</div>
									<div class="item-middle">
										<div><i class="fa-solid fa-calendar-days"></i> Duration: <?= htmlspecialchars($bid['Duration'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
										<div><i class="fa-solid fa-layer-group"></i> Category: <?= htmlspecialchars($bid['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
									</div>
									<div class="item-description"><?= htmlspecialchars($bid['Comment'] ?? $bid['Post_Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
									<div class="status-bottom"><span class="status-chip status-<?= htmlspecialchars($bid['Status_Key'] ?? strtolower($bid['Status'] ?? 'closed'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($bid['Status'] ?? 'Closed', ENT_QUOTES, 'UTF-8') ?></span></div>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	</div>

	<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

	<div class="pop-up-section request-modal deactive" id="bidDetailsModalRoot">
		<div class="pop-up" style="max-width: 680px; border-radius: 16px;">
			<div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
				<div class="pop-up-title" id="bidDetailTitle">Bid Details</div>
				<i class="fa-solid fa-xmark" id="bidDetailsModalClose" style="cursor:pointer;"></i>
			</div>
			<hr>
			<div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
				<div style="font-size:15px; color:#64748b;">Client: <strong id="bidDetailClient">-</strong></div>
				<div id="bidDetailDetails" style="display:grid; gap:10px;"></div>
			</div>
		</div>
	</div>

	<div class="pop-up-section request-modal deactive" id="bidEditModalRoot">
		<div class="pop-up" id="bidEditModal" style="max-width:680px; border-radius:16px;">
			<div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
				<div class="pop-up-title">Edit Bid</div>
				<i class="fa-solid fa-xmark" id="bidEditModalClose" style="cursor:pointer;"></i>
			</div>
			<hr>
			<form id="bidEditForm" class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;" method="POST" action="<?= BASE_URL ?>/bids/edit">
				<input type="hidden" id="editBidId" name="bid_id" value="">
				<div style="font-size:14px; color:#334155;">You are editing: <strong id="editBidProjectTitle">Project</strong></div>
				<div style="font-size:13px; color:#64748b;">Client: <span id="editBidClientName">Client Name</span></div>

				<label for="editBidAmount" style="font-weight:700; color:#111827;">Bid Amount</label>
				<input id="editBidAmount" name="bid_amount" type="number" min="1" step="1" placeholder="Enter your amount" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>

				<label for="editBidDuration" style="font-weight:700; color:#111827;">Duration</label>
				<div style="display:grid; grid-template-columns:1fr 170px; gap:10px;">
					<input id="editBidDuration" name="bid_duration" type="number" min="1" step="1" placeholder="e.g. 7" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>
					<select id="editBidDurationUnit" name="bid_duration_unit" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>
						<option value="d" selected>Days</option>
						<option value="w">Weeks</option>
						<option value="m">Months</option>
					</select>
				</div>

				<label for="editBidMessage" style="font-weight:700; color:#111827;">Proposal Note</label>
				<textarea id="editBidMessage" name="bid_message" rows="5" placeholder="Write a short proposal..." style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px; resize:vertical;" required></textarea>

				<div class="modal-actions" style="margin-top:6px;">
					<button class="btn-primary" id="editBidSubmitBtn" type="submit"><i class="fa-solid fa-paper-plane"></i> Submit Edit</button>
				</div>
			</form>
		</div>
	</div>

	<div class="pop-up-section request-modal deactive" id="bidWithdrawModalRoot">
		<div class="pop-up" id="bidWithdrawModal" style="max-width:560px; border-radius:16px;">
			<div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
				<div class="pop-up-title">Withdraw Bid</div>
				<i class="fa-solid fa-xmark" id="bidWithdrawModalClose" style="cursor:pointer;"></i>
			</div>
			<hr>
			<form id="bidWithdrawForm" class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;" method="POST" action="<?= BASE_URL ?>/bids/withdraw">
				<input type="hidden" id="withdrawBidId" name="bid_id" value="">
				<div style="font-size:14px; color:#334155;">Are you sure you want to withdraw this bid?</div>
				<div style="font-size:13px; color:#64748b;">Withdrawing this bid is permanent. If you change your mind, you'll need to submit a new bid.</div>
				<div class="modal-actions" style="margin-top:6px;">
					<button class="btn-outline" id="bidWithdrawCancelBtn" type="button">Cancel</button>
					<button class="btn-danger" id="bidWithdrawConfirmBtn" type="submit"><i class="fa-solid fa-trash"></i> Withdraw</button>
				</div>
			</form>
		</div>
	</div>

	<?php
	$filterModal = new FilterModal([
		'modalRootId' => 'bidsFilterRoot',
		'modalId' => 'bidsFilterModal',
		'closeId' => 'bidsFilterClose',
		'applyId' => 'bidsFilterApply',
		'clearId' => 'bidsFilterClear',
		'filters' => [
			[
				'type' => 'checkbox',
				'label' => 'Category',
				'id' => 'bidsCategoryList'
			]
		]
	]);
	$filterModal->render();
	?>

	<script type="module" src="<?= BASE_URL ?>/assets/js/providerBids.js"></script>
</body>

</html>
