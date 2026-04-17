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
									data-client="<?= htmlspecialchars($bid['Client_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-title="<?= htmlspecialchars($bid['Title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-category="<?= htmlspecialchars($bid['Category_Name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-amount="<?= htmlspecialchars($bid['Bid_Amount'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-duration="<?= htmlspecialchars($bid['Duration'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-ref="<?= htmlspecialchars($bid['Bid_Ref'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
									data-status="<?= htmlspecialchars($bid['Status'] ?? 'Pending', ENT_QUOTES, 'UTF-8') ?>"
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
									<div class="status-bottom"><span class="status-chip status-<?= htmlspecialchars(strtolower($bid['Status'] ?? 'pending'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($bid['Status'] ?? 'Pending', ENT_QUOTES, 'UTF-8') ?></span></div>
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
