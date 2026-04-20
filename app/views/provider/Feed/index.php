<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servo | Provider Feed</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/serviceProjects.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/incomingRequests.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css" />
	<style>
		html, body { height: 100%; }
		body.feed-page { min-height: 100vh; display: flex; flex-direction: column; }
		body.feed-page .main-content { flex: 1 0 auto; width: 100%; }
		body.feed-page footer { margin-top: auto; }

		/* ── Feed card ─────────────────────────────────────────── */
		.feed-card {
			background: #ffffff;
			border: 1px solid #e5e7eb;
			border-radius: 14px;
			padding: 20px;
			margin-bottom: 16px;
			box-shadow: 0 1px 4px rgba(0,0,0,.07);
			transition: box-shadow .2s ease, border-color .2s ease;
		}
		.feed-card:hover {
			border-color: #008500;
			box-shadow: 0 4px 16px rgba(0,133,0,.10);
		}

		/* header row */
		.feed-card-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			gap: 12px;
			margin-bottom: 14px;
		}
		.feed-card-client {
			display: flex;
			gap: 12px;
			align-items: flex-start;
			flex: 1;
			min-width: 0;
		}
		.feed-card-avatar {
			width: 52px; height: 52px;
			border-radius: 50%;
			object-fit: cover;
			flex-shrink: 0;
			border: 2px solid #dcfce7;
			background: #f3f4f6;
		}
		.feed-card-client-info { flex: 1; min-width: 0; }
		.feed-card-client-name { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: 3px; }
		.feed-card-meta { font-size: 12px; color: #6b7280; }

		/* action buttons — inherit .btn-primary / .btn-outline from serviceProjects.css */
		.feed-card-actions {
			display: flex; gap: 8px; flex-shrink: 0; flex-wrap: wrap; justify-content: flex-end;
		}
		.feed-card-actions button {
			padding: 7px 12px;
			font-size: 13px;
			border-radius: 8px;
			cursor: pointer;
			display: inline-flex; align-items: center; gap: 6px;
			white-space: nowrap;
			font-weight: 600;
		}
		.feed-card-actions .btn-bid-placed {
			background: #f3f4f6 !important; color: #6b7280 !important;
			border: 1px solid #e5e7eb !important; cursor: default;
		}
		.feed-card-actions .btn-cancel-bid {
			background: #fee2e2 !important; color: #b91c1c !important;
			border: 1px solid #fecaca !important;
		}
		.feed-card-actions .btn-cancel-bid:hover {
			background: #fecaca !important;
		}

		/* title */
		.feed-card-title {
			font-size: 15px; font-weight: 700; color: #1e293b;
			margin-bottom: 8px;
		}

		/* description */
		.feed-card-description {
			font-size: 13px; color: #64748b; line-height: 1.6;
			margin-bottom: 12px;
			display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
			overflow: hidden;
		}

		/* details row */
		.feed-card-details {
			display: flex; flex-wrap: wrap; gap: 10px 20px;
			margin-bottom: 12px;
		}
		.feed-card-detail {
			display: flex; align-items: center; gap: 6px;
			font-size: 13px; color: #475569;
		}
		.feed-card-detail i { color: #008500; width: 14px; text-align: center; }

		/* my bid banner */
		.feed-bid-banner {
			display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
			background: #f0fdf4;
			border: 1px solid #bbf7d0;
			border-radius: 9px;
			padding: 10px 14px;
			margin-bottom: 12px;
			font-size: 13px;
		}
		.feed-bid-banner-left { display: flex; gap: 20px; flex-wrap: wrap; }
		.feed-bid-banner-item { display: flex; flex-direction: column; }
		.feed-bid-banner-label { font-size: 11px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
		.feed-bid-banner-value { font-size: 14px; color: #166534; font-weight: 700; }

		/* other bids list */
		.feed-bids-list {
			margin-bottom: 12px;
		}
		.feed-bids-list-title {
			font-size: 12px; font-weight: 700; color: #6b7280;
			text-transform: uppercase; letter-spacing: .5px;
			margin-bottom: 8px;
		}
		.feed-bid-row {
			display: flex; align-items: center; justify-content: space-between;
			background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 8px;
			padding: 8px 12px; margin-bottom: 6px;
			font-size: 13px;
		}
		.feed-bid-row-name { font-weight: 600; color: #111827; }
		.feed-bid-row-amount { color: #008500; font-weight: 700; }
		.feed-bid-row-duration { color: #6b7280; font-size: 12px; }
		.feed-bid-row-you {
			background: #f0fdf4; border-color: #bbf7d0;
		}

		/* footer */
		.feed-card-footer {
			display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
		}
		.feed-card-footer-left { display: flex; align-items: center; gap: 10px; }
		.feed-time { font-size: 12px; color: #9ca3af; }
		.bid-count-chip {
			background: #f0fdf4; color: #166534;
			border: 1px solid #bbf7d0;
			border-radius: 20px; padding: 3px 10px;
			font-size: 12px; font-weight: 600;
		}

		/* bids in view modal */
		.view-bids-section { margin-top: 6px; }
		.view-bids-title {
			font-size: 13px; font-weight: 700; color: #374151;
			margin-bottom: 8px; padding-bottom: 4px;
			border-bottom: 1px solid #e5e7eb;
		}
		.view-bid-row {
			display: grid; grid-template-columns: 1fr auto auto;
			gap: 8px; align-items: center;
			padding: 8px 12px; border-radius: 8px;
			background: #f9fafb; margin-bottom: 6px;
			font-size: 13px;
		}
		.view-bid-row.is-mine {
			background: #f0fdf4; border: 1px solid #bbf7d0;
		}
		.view-bid-name { font-weight: 600; color: #111827; }
		.view-bid-amount { color: #008500; font-weight: 700; text-align: right; }
		.view-bid-duration { color: #6b7280; font-size: 12px; text-align: right; white-space: nowrap; }
	</style>
</head>

<body class="feed-page">
	<?php 
	require_once __DIR__ . '/../../includes/navbar.php';
	require_once __DIR__ . '/../../components/SearchHeader.php';
	require_once __DIR__ . '/../../components/FilterModal.php';
	?>

	<div class="main-content">
		<section class="service-requests" style="padding-top: 12px; padding-bottom: 28px;">
			<?php
			$searchHeader = new SearchHeader([
				'inputId' => 'feedSearchInput',
				'placeholder' => 'Search by title, category, or client...',
				'filterBtnId' => 'feedFilterBtn',
				'searchBtnId' => 'feedSearchBtn',
				'title' => 'Feed',
				'note' => 'Browse the latest project posts and place your bids. Use the filters to find projects that match your skills.',
			]);
			$searchHeader->render();
			?>

			<div class="request-content">
				<div class="requests-section active" style="display:block;">
					<div class="item-list">
						<?php foreach ($feedItems as $item):
							$myBidId      = $item['My_Bid_ID']      ?? null;
							$myBidAmount  = $item['My_Bid_Amount']   ?? null;
							$myBidHours   = (int) ($item['My_Bid_Duration'] ?? 0);
							$myBidComment = $item['My_Bid_Comment']  ?? '';
							$totalBids    = (int) ($item['Total_Bids'] ?? 0);
							$postBids     = $allBids[$item['Post_ID']] ?? [];
							$avatarSrc    = !empty($item['Client_Avatar'])
								? BASE_URL . '/file/user-files/' . htmlspecialchars($item['Client_Avatar'], ENT_QUOTES, 'UTF-8')
								: BASE_URL . '/assets/img/default-avatar.png';

							// Build anonymised bids JSON for JS
							$bidsJson = json_encode(array_map(function($b) use ($myBidId) {
								return [
									'bid_id'    => (int) $b['Bid_ID'],
									'name'      => $b['First_Name'] . ' ' . substr($b['Last_Name'], 0, 1) . '.',
									'amount'    => (float) $b['Amount'],
									'duration'  => (int) $b['Duration'],
									'is_mine'   => ((int)$b['Bid_ID'] === (int)$myBidId),
								];
							}, $postBids), JSON_HEX_QUOT | JSON_HEX_TAG);
						?>
						<article class="feed-card"
							data-postid="<?= htmlspecialchars($item['Post_ID'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-client="<?= htmlspecialchars($item['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-clientid="<?= htmlspecialchars($item['Client_ID'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-title="<?= htmlspecialchars($item['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-category="<?= htmlspecialchars($item['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-budget="<?= htmlspecialchars($item['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-pricetype="<?= htmlspecialchars($item['Price_Type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-level="<?= htmlspecialchars($item['Level'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-deadlineraw="<?= htmlspecialchars($item['Est_Date'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-deadline="<?= htmlspecialchars($item['Deadline'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-posted="<?= htmlspecialchars($item['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-description="<?= htmlspecialchars($item['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
							data-totalbids="<?= $totalBids ?>"
							data-bids="<?= htmlspecialchars($bidsJson, ENT_QUOTES, 'UTF-8') ?>"
							<?php if ($myBidId): ?>
							data-mybidid="<?= htmlspecialchars($myBidId, ENT_QUOTES, 'UTF-8') ?>"
							data-mybidamount="<?= htmlspecialchars($myBidAmount, ENT_QUOTES, 'UTF-8') ?>"
							data-mybidduration="<?= htmlspecialchars($myBidHours, ENT_QUOTES, 'UTF-8') ?>"
							data-mybidcomment="<?= htmlspecialchars($myBidComment, ENT_QUOTES, 'UTF-8') ?>"
							<?php endif; ?>>

							<!-- Header -->
							<div class="feed-card-header">
								<div class="feed-card-client">
									<img src="<?= $avatarSrc ?>" alt="<?= htmlspecialchars($item['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="feed-card-avatar">
									<div class="feed-card-client-info">
										<div class="feed-card-client-name"><?= htmlspecialchars($item['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
										<div class="feed-card-meta"><i class="fa-solid fa-clock"></i> <?= htmlspecialchars($item['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
									</div>
								</div>
								<div class="feed-card-actions">
									<?php if ($myBidId): ?>
										<button class="btn-bid-placed" type="button" disabled title="You already placed a bid"><i class="fa-solid fa-gavel"></i> Bid Placed</button>
										<button class="btn-outline btn-edit-bid" type="button" title="Edit Your Bid"><i class="fa-solid fa-pen"></i> Edit</button>
										<button class="btn-cancel-bid" type="button" title="Cancel Your Bid" data-bidid="<?= (int)$myBidId ?>"><i class="fa-solid fa-xmark"></i> Cancel Bid</button>
									<?php else: ?>
										<button class="btn-primary btn-bid" type="button" title="Place Bid"><i class="fa-solid fa-gavel"></i> Bid</button>
									<?php endif; ?>
									<button class="btn-outline btn-message" type="button" title="Message Client"><i class="fa-regular fa-message"></i> Message</button>
									<button class="btn-outline btn-view" type="button" title="View Details"><i class="fa-regular fa-eye"></i> View</button>
								</div>
							</div>

							<!-- Title & Description -->
							<div class="feed-card-title"><?= htmlspecialchars($item['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
							<div class="feed-card-description"><?= htmlspecialchars($item['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>

							<!-- Details -->
							<div class="feed-card-details">
								<div class="feed-card-detail"><i class="fa-solid fa-layer-group"></i> <?= htmlspecialchars($item['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
								<div class="feed-card-detail"><i class="fa-solid fa-coins"></i> <?= htmlspecialchars($item['Budget'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
								<div class="feed-card-detail"><i class="fa-solid fa-calendar-days"></i> Deadline: <?= htmlspecialchars($item['Deadline'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
								<div class="feed-card-detail"><i class="fa-solid fa-signal"></i> <?= htmlspecialchars(ucfirst($item['Level'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
							</div>

							<?php if ($myBidId): ?>
							<!-- My Bid Banner -->
							<div class="feed-bid-banner">
								<div class="feed-bid-banner-left">
									<div class="feed-bid-banner-item">
										<span class="feed-bid-banner-label">Your Bid</span>
										<span class="feed-bid-banner-value">Rs. <?= htmlspecialchars(number_format((float)$myBidAmount, 2), ENT_QUOTES, 'UTF-8') ?></span>
									</div>
									<div class="feed-bid-banner-item">
										<span class="feed-bid-banner-label">Duration</span>
										<span class="feed-bid-banner-value">
											<?php
											$myBidDays = $myBidHours > 0 ? (int) ceil($myBidHours / 24) : 0;
											if ($myBidDays % 30 === 0 && $myBidDays >= 30) echo ($myBidDays / 30) . ' month' . ($myBidDays / 30 > 1 ? 's' : '');
											elseif ($myBidDays % 7 === 0 && $myBidDays >= 7)  echo ($myBidDays / 7)  . ' week'  . ($myBidDays / 7  > 1 ? 's' : '');
											else echo max(1, $myBidDays) . ' day' . (max(1, $myBidDays) > 1 ? 's' : '');
											?>
										</span>
									</div>
								</div>
								<span style="font-size:12px; color:#008500; font-weight:600;"><i class="fa-solid fa-circle-check"></i> Bid Submitted</span>
							</div>
							<?php endif; ?>

							<!-- Other Bids List -->
							<?php if (!empty($postBids)): ?>
							<div class="feed-bids-list">
								<div class="feed-bids-list-title"><i class="fa-solid fa-gavel"></i> Current Bids</div>
								<?php foreach ($postBids as $bid):
									$isMe    = ($myBidId && (int)$bid['Bid_ID'] === (int)$myBidId);
									$bidName = $bid['First_Name'] . ' ' . substr($bid['Last_Name'], 0, 1) . '.';
									$bidHours = (int) $bid['Duration'];
									$bidDays = $bidHours > 0 ? (int) ceil($bidHours / 24) : 0;
									if ($bidDays % 30 === 0 && $bidDays >= 30) $durStr = ($bidDays/30) . ' mo';
									elseif ($bidDays % 7 === 0 && $bidDays >= 7) $durStr = ($bidDays/7) . ' wk';
									else $durStr = max(1, $bidDays) . 'd';
								?>
								<div class="feed-bid-row<?= $isMe ? ' feed-bid-row-you' : '' ?>">
									<span class="feed-bid-row-name"><?= htmlspecialchars($bidName, ENT_QUOTES, 'UTF-8') ?><?= $isMe ? ' <em style="font-size:11px;color:#16a34a;">(You)</em>' : '' ?></span>
									<span class="feed-bid-row-amount">Rs. <?= htmlspecialchars(number_format((float)$bid['Amount'], 2), ENT_QUOTES, 'UTF-8') ?></span>
									<span class="feed-bid-row-duration"><?= htmlspecialchars($durStr, ENT_QUOTES, 'UTF-8') ?></span>
								</div>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>

							<!-- Footer -->
							<div class="feed-card-footer">
								<div class="feed-card-footer-left">
									<span class="feed-time"><i class="fa-solid fa-clock"></i> <?= htmlspecialchars($item['Posted'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
									<span class="bid-count-chip"><i class="fa-solid fa-gavel"></i> <?= $totalBids ?> bid<?= $totalBids !== 1 ? 's' : '' ?></span>
								</div>
								<span class="status-chip status-<?= htmlspecialchars(strtolower($item['Post_Status'] ?? 'active'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['Post_Status'] ?? 'Active', ENT_QUOTES, 'UTF-8') ?></span>
							</div>
						</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	</div>

	<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

	<!-- Bid / Edit Bid Modal -->
	<div class="pop-up-section request-modal deactive" id="bidModalRoot">
		<div class="pop-up" id="bidModal" style="max-width:680px; border-radius:16px;">
			<div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
				<div class="pop-up-title" id="bidModalTitle">Submit Bid</div>
				<i class="fa-solid fa-xmark" id="bidModalClose" style="cursor:pointer;"></i>
			</div>
			<hr>
			<form id="bidForm" class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;" method="POST" action="<?= BASE_URL ?>/feed/submit-bid">
				<input type="hidden" id="bidFormAction" name="_action" value="submit">
				<input type="hidden" id="bidPostId"   name="post_id" value="">
				<input type="hidden" id="bidIdField"  name="bid_id"  value="">
				<div style="font-size:14px; color:#334155;">Bidding on: <strong id="bidProjectTitle">Project</strong></div>
				<div style="font-size:13px; color:#64748b;">Client: <span id="bidClientName">Client Name</span></div>

				<label for="bidAmount" style="font-weight:700; color:#111827;">Bid Amount (Rs.)</label>
				<input id="bidAmount" name="bid_amount" type="number" min="1" step="1" placeholder="Enter your amount"
					style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>

				<label for="bidDuration" style="font-weight:700; color:#111827;">Duration</label>
				<div style="display:grid; grid-template-columns:1fr 170px; gap:10px;">
					<input id="bidDuration" name="bid_duration" type="number" min="1" step="1" placeholder="e.g. 7"
						style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>
					<select id="bidDurationUnit" name="bid_duration_unit"
						style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px;" required>
						<option value="d" selected>Days</option>
						<option value="w">Weeks</option>
						<option value="m">Months</option>
					</select>
				</div>

				<label for="bidMessage" style="font-weight:700; color:#111827;">Proposal Note</label>
				<textarea id="bidMessage" name="bid_message" rows="5" placeholder="Write a short proposal..."
					style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:14px; resize:vertical;" required></textarea>

				<div class="modal-actions" style="margin-top:6px;">
					<button class="btn-primary" id="submitBidBtn" type="submit"><i class="fa-solid fa-paper-plane"></i> Submit</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Filter Modal -->
	<?php
	$filterModal = new FilterModal([
		'modalRootId' => 'feedFilterRoot',
		'modalId'     => 'feedFilterModal',
		'closeId'     => 'feedFilterClose',
		'applyId'     => 'feedFilterApply',
		'clearId'     => 'feedFilterClear',
		'filters' => [
			[
				'type'  => 'checkbox',
				'label' => 'Category',
				'id'    => 'feedCategoryList'
			]
		]
	]);
	$filterModal->render();
	?>

	<!-- View Details Modal -->
	<div class="pop-up-section request-modal deactive" id="feedViewRoot">
		<div class="pop-up" id="feedViewModal" style="max-width:680px; border-radius:16px;">
			<div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
				<div class="pop-up-title" id="feedViewTitle">Project</div>
				<i class="fa-solid fa-xmark" id="feedViewClose" style="cursor:pointer;"></i>
			</div>
			<hr>
			<div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
				<div style="font-size:15px; color:#64748b;">Client: <strong id="feedViewClient">-</strong></div>
				<div id="feedViewDetails" style="display:grid; gap:10px;"></div>
				<div class="view-bids-section" id="feedViewBids" style="display:none;">
					<div class="view-bids-title"><i class="fa-solid fa-gavel"></i> Current Bids</div>
					<div id="feedViewBidsList"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Hidden cancel bid form -->
	<form id="cancelBidForm" method="POST" action="<?= BASE_URL ?>/feed/cancel-bid" style="display:none;">
		<input type="hidden" id="cancelBidId" name="bid_id" value="">
	</form>

	<script type="module" src="<?= BASE_URL ?>/assets/js/providerFeed.js"></script>
</body>

</html>
