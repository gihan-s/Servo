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
	<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

	<div class="main-content">
		<section class="service-requests" style="padding-top: 12px; padding-bottom: 28px;">
			<div class="header-requests">
				<h1>Placed Bids</h1>
				<p class="section-note">Posts from the feed that you have already bid on are shown here.</p>
				<div class="container-changer">
					<div class="buttons active" id="active" data-target="active">Active Bids</div>
					<div class="buttons" id="accepted" data-target="accepted">Accepted</div>
					<div class="buttons" id="rejected" data-target="rejected">Rejected</div>
				</div>
			</div>

			<div class="request-content">
				<?php
				$sections = [
					'active' => ['label' => 'Pending bids waiting for client acceptance or rejection.', 'items' => $activeBids],
					'accepted' => ['label' => 'Bids accepted by clients.', 'items' => $acceptedBids],
					'rejected' => ['label' => 'Bids rejected by clients.', 'items' => $rejectedBids],
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
									data-client="<?= htmlspecialchars($bid['client'], ENT_QUOTES, 'UTF-8') ?>"
									data-title="<?= htmlspecialchars($bid['title'], ENT_QUOTES, 'UTF-8') ?>"
									data-amount="<?= htmlspecialchars($bid['bidAmount'], ENT_QUOTES, 'UTF-8') ?>"
									data-timeline="<?= htmlspecialchars($bid['timeline'], ENT_QUOTES, 'UTF-8') ?>"
									data-ref="<?= htmlspecialchars($bid['projectRef'], ENT_QUOTES, 'UTF-8') ?>"
									data-description="<?= htmlspecialchars($bid['description'], ENT_QUOTES, 'UTF-8') ?>">
									<div class="item-head">
										<div class="item-main-dets">
											<div class="item-name"><?= htmlspecialchars($bid['client'], ENT_QUOTES, 'UTF-8') ?></div>
											<div class="item-title"><?= htmlspecialchars($bid['title'], ENT_QUOTES, 'UTF-8') ?></div>
											<div class="item-district">
												<span><i class="fa-solid fa-clock"></i> Bid placed <?= htmlspecialchars($bid['bidDate'], ENT_QUOTES, 'UTF-8') ?></span>
												<span><i class="fa-solid fa-tag"></i> Your bid: <?= htmlspecialchars($bid['bidAmount'], ENT_QUOTES, 'UTF-8') ?></span>
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
										<div><i class="fa-solid fa-calendar-days"></i> Timeline: <?= htmlspecialchars($bid['timeline'], ENT_QUOTES, 'UTF-8') ?></div>
										<div><i class="fa-solid fa-layer-group"></i> Category: <?= htmlspecialchars($bid['category'], ENT_QUOTES, 'UTF-8') ?></div>
									</div>
									<div class="item-description"><?= htmlspecialchars($bid['description'], ENT_QUOTES, 'UTF-8') ?></div>
									<div class="status-bottom"><span class="status-chip <?= htmlspecialchars($bid['statusClass'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($bid['statusLabel'], ENT_QUOTES, 'UTF-8') ?></span></div>
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
				<div class="pop-up-title">Bid Details</div>
				<i class="fa-solid fa-xmark" id="bidDetailsModalClose" style="cursor:pointer;"></i>
			</div>
			<hr>
			<div class="pop-up-content" style="display:flex; flex-direction:column; gap:12px;">
				<div style="display:flex; gap:12px; align-items:center;">
					<div style="font-weight:700; color:#111827;" id="bidDetailClient">Client</div>
					<span style="font-size:12px; color:#64748b;">•</span>
					<div style="font-size:13px; color:#475569;">Ref: <span id="bidDetailRef">-</span></div>
				</div>
				<div style="font-size:16px; font-weight:700; color:#111827;" id="bidDetailTitle">Bid Title</div>
				<div style="font-size:14px; color:#475569; line-height:1.6;" id="bidDetailDescription">Description</div>
				<div style="display:flex; gap:10px; align-items:center;">
					<span class="status-chip" style="background:#ecfdf5; color:#008500; border-color:#bbf7d0;">
						<i class="fa-solid fa-tag"></i>
						<span>Bid Amount: <span id="bidDetailAmount">$0</span></span>
					</span>
					<span class="status-chip" style="background:#f1f5f9; color:#0f172a; border-color:#cbd5e1;">
						<i class="fa-solid fa-clock"></i>
						<span>Timeline: <span id="bidDetailTimeline">-</span></span>
					</span>
				</div>
			</div>
		</div>
	</div>

	<script src="<?= BASE_URL ?>/assets/js/providerBids.js"></script>
</body>

</html>
