<?php
// Fallback-safe text snipping helper for servers without mbstring
if (!function_exists('str_snippet')) {
    function str_snippet($text, $limit = 160, $suffix = '…')
    {
        $text = (string) $text;
        // Prefer multibyte-aware trim when available
        if (function_exists('mb_strimwidth')) {
            return mb_strimwidth($text, 0, (int) $limit, (string) $suffix, 'UTF-8');
        }
        // Basic fallback (byte-based)
        if (strlen($text) <= $limit)
            return $text;
        return rtrim(substr($text, 0, $limit)) . $suffix;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clientPosts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/clientPosts.js" defer></script>
    <title>My Service Requests</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <div class="header-top" style="display:flex; justify-content: space-between; align-items: center;">
                <h1>My Service Requests</h1>

                <button type="button" class="post-job-btn" onclick="viewDialogBox('create-post-popup')"><i
                        class="fa-regular fa-plus"></i>
                    Create Request</button>
            </div>

        </div>
        <div class="container-changer">
            <div class="tab-buttons">
                <div id="active-posts" class="buttons active">Active Requests</div>
                <div id="draft-posts" class="buttons">Draft Requests</div>
                <div id="expired-posts" class="buttons">Expired Requests</div>
            </div>
        </div>
        <div class="request-content">
            <div class="search-header">

                <div class="search-button">
                    <input type="text" placeholder="Search my service requests...">
                    <button><i class="fa-light fa-magnifying-glass"></i></button>
                </div>
                <button class="filter" id="filter-pop-up"><i
                        class="fa-light fa-filter-list"></i><span>filter</span></button>

                <div class="advance-search">
                    <span>Sort By: </span>
                    <div class="select-container" style="width: 100px;">

                        <div class="text-container">
                            <div class="label dropdown-label" style="visibility: hidden;"></div>
                            <input type="text" id="date" class="text-field-dropdown"
                                style="padding: 10px; background-color: var(--containerColor);" value="Date" readonly>
                        </div>

                        <div class="options">
                            <div>Date</div>
                            <div>Price</div>
                            <div>Views</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ACTIVE REQUESTS SECTION -->
            <div class="active-posts active requests-section">
                <div class="item-list">
                    <?php if (empty($data['activePosts'])): ?>
                        <section class="empty-state" aria-label="No posts">
                            <!-- ...illustration... -->
                            <h2>No active requests right now</h2>
                            <p>Check back soon or refresh to see new requests.</p>
                            <div class="empty-actions">
                                <a class="btn primary" href="<?= BASE_URL ?>/feeds"
                                    onclick="location.reload(); return false;">
                                    <i class="fa-regular fa-rotate" style="font-size:25px"></i> Refresh
                                </a>
                            </div>
                        </section>
                    <?php else: ?>
                        <?php foreach ($data['activePosts'] as $row): ?>
                            <?php
                            if (!is_array($row) || !isset($row['post']) || !is_array($row['post'])) {
                                continue;
                            }
                            $p = $row['post'];
                            $Published_At = $p['Published_At'] ?? null;
                            $daysPassed = 0;
                            if ($Published_At) {
                                try {
                                    $tz = new DateTimeZone('Asia/Colombo');
                                    $created = new DateTime($Published_At, $tz);
                                    $now = new DateTime('now', $tz);
                                    $daysPassed = $now->diff($created)->days;
                                } catch (Throwable $e) {
                                    $daysPassed = 0;
                                }
                            }
                            ?>
                            <div class="search-item">
                                <input type="hidden" class="post-id" value="<?= htmlspecialchars($p['Post_ID'] ?? '') ?>">
                                <div class="post-header">
                                    <div class="post-meta">
                                        <div class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <span>
                                                <?php if ($daysPassed >= 365) {
                                                    $years = floor($daysPassed / 365);
                                                    echo "Published " . $years . ($years === 1 ? " year" : " years") . " ago.";
                                                } else if ($daysPassed >= 30) {
                                                    $months = floor($daysPassed / 30);
                                                    echo "Published " . $months . ($months === 1 ? " month" : " months") . " ago.";
                                                } else if ($daysPassed >= 7) {
                                                    $weeks = floor($daysPassed / 7);
                                                    echo "Published " . $weeks . ($weeks === 1 ? " week" : " weeks") . " ago.";
                                                } else if ($daysPassed > 1) {
                                                    echo "Published " . $daysPassed . " days ago.";
                                                } else if ($daysPassed == 0) {
                                                    echo "Published Today";
                                                } else {
                                                    echo "Published Yesterday";
                                                } ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-actions">
                                        <button class="action-btn btn-edit" onclick="editPost(<?= (int) $p['Post_ID'] ?>)"><i
                                                class="fas fa-edit"></i>
                                            Edit</button>
                                        <button class="action-btn btn-view" onclick="viewPost(<?= (int) $p['Post_ID'] ?>)"><i
                                                class="fas fa-eye"></i>
                                            View</button>
                                        <button class="action-btn btn-delete"><i class="fas fa-trash"
                                                onclick="window.location='<?= BASE_URL ?>/requests/delete/<?= (int) $p['Post_ID'] ?>'"></i>
                                            Delete</button>
                                    </div>
                                </div>

                                <h3 class="post-title"><?= htmlspecialchars($p['Title'] ?? '') ?></h3>

                                <div class="post-description">
                                    <?php
                                    $desc = (string) ($p['Description'] ?? '');
                                    $plain = trim(preg_replace('/\s+/', ' ', strip_tags($desc)));
                                    $snippet = str_snippet($plain, 300, '…'); // limit to ~160 chars
                                    ?>
                                    <?= htmlspecialchars($snippet, ENT_QUOTES, 'UTF-8') ?>
                                </div>

                                <div class="post-skills">
                                    <span class="skills-label">Required Skills:</span>
                                    <div class="skills-tags">
                                        <?php
                                        if (!empty($row['skills'])) {
                                            foreach ($row['skills'] as $skill): ?>
                                                <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
                                            <?php endforeach;
                                        } else { ?>
                                            <span class="">---No skills specified---</span>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="post-footer">
                                    <div class="post-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Budget</span>
                                            <span class="detail-value budget-amount">LKR
                                                <?= htmlspecialchars($p['Requesting_Price'] ?? '') ?>/=
                                                (<?= htmlspecialchars($p['Price_Type'] ?? '') ?>)</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Proposals Received</span>
                                            <span
                                                class="detail-value proposals-count"><?= htmlspecialchars($p['Proposal_Count'] ?? '0') ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Level</span>
                                            <span
                                                class="detail-value project-level"><?= htmlspecialchars($p['Level'] ?? '') ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Duration</span>
                                            <span
                                                class="detail-value project-duration"><?= htmlspecialchars($p['Duration'] ?? '') ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="engagement-stats">
                                    <div class="stat-item"><i class="fas fa-eye"></i><span>156 views</span></div>
                                    <div class="stat-item"><i class="fas fa-clock"></i>
                                        <span>
                                            <?php
                                            if (!is_array($row) || !isset($row['post']) || !is_array($row['post'])) {
                                                continue;
                                            }
                                            $p = $row['post'];
                                            $End_At = $p['End_At'] ?? null;
                                            $daysLeft = 0;
                                            if ($End_At) {
                                                try {
                                                    $tz = new DateTimeZone('Asia/Colombo');
                                                    $created = new DateTime($End_At, $tz);
                                                    $now = new DateTime('now', $tz);
                                                    $daysLeft = $created->diff($now)->days;
                                                } catch (Throwable $e) {
                                                    $daysLeft = 0;
                                                }
                                            }
                                            ?>
                                            <?= $daysLeft ?> days left</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Pagination Active -->
                        <div class="pagination" aria-label="Pagination Active Requests">
                            <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                            <button class="page-btn active">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn">3</button>
                            <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- DRAFT REQUESTS SECTION -->
            <div class="draft-posts requests-section draft-section" style="display: none;">
                <div class="item-list">
                    <?php if (empty($data['draftPosts'])): ?>
                        <section class="empty-state" aria-label="No draft requests">
                            <!-- ...illustration... -->
                            <h2>No draft requests right now</h2>
                            <p>Create drafts to save your job requests and publish them later.</p>
                            <div class="empty-actions">
                                <a class="btn primary" href="<?= BASE_URL ?>/requests/create">
                                    <i class="fa-regular fa-plus" style="font-size:25px"></i> Create Draft
                                </a>
                            </div>
                        </section>
                    <?php else: ?>
                        <?php foreach ($data['draftPosts'] as $row): ?>
                            <?php
                            if (!is_array($row) || !isset($row['post']) || !is_array($row['post'])) {
                                continue;
                            }
                            $p = $row['post'];
                            $Created_At = $p['Created_At'] ?? null;
                            $daysPassed = 0;
                            if ($Created_At) {
                                try {
                                    $tz = new DateTimeZone('Asia/Colombo');
                                    $created = new DateTime($Created_At, $tz);
                                    $now = new DateTime('now', $tz);
                                    $daysPassed = $now->diff($created)->days;
                                } catch (Throwable $e) {
                                    $daysPassed = 0;
                                }
                            }
                            ?>

                            <div class="search-item">
                                <input type="hidden" class="post-id" value="<?= htmlspecialchars($p['Post_ID'] ?? '') ?>">
                                <div class="post-header">
                                    <div class="post-meta">
                                        <div class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <span>
                                                <?php if ($daysPassed > 1) {
                                                    echo "Created " . $daysPassed . " days ago.";
                                                } else if ($daysPassed == 0) {
                                                    echo "Created Today";
                                                } else {
                                                    echo "Created Yesterday";
                                                } ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-actions">
                                        <button class="action-btn btn-edit">
                                            <i class="fas fa-edit"></i>
                                            Continue
                                        </button>
                                        <button class="action-btn btn-view">
                                            <i class="fas fa-rocket"></i>
                                            Publish
                                        </button>
                                        <button class="action-btn btn-delete">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                <h3 class="post-title"><?= htmlspecialchars($p['Title'] ?? '') ?></h3>

                                <div class="post-description">
                                    <?php
                                    $desc = (string) ($p['Description'] ?? '');
                                    $plain = trim(preg_replace('/\s+/', ' ', strip_tags($desc)));
                                    $snippet = str_snippet($plain, 160, '…'); // limit to ~160 chars
                                    ?>
                                    <?= htmlspecialchars($snippet, ENT_QUOTES, 'UTF-8') ?>
                                </div>

                                <div class="post-skills">
                                    <span class="skills-label">Required Skills:</span>
                                    <div class="skills-tags">
                                        <?php
                                        if (!empty($row['skills'])) {
                                            foreach ($row['skills'] as $skill): ?>
                                                <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
                                            <?php endforeach;
                                        } else { ?>
                                            <span class="">---No skills specified---</span>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="post-footer">
                                    <div class="post-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Budget</span>
                                            <span class="detail-value budget-amount">LKR
                                                <?= htmlspecialchars($p['Requesting_Price'] ?? '') ?>/=
                                                (<?= htmlspecialchars($p['Price_Type'] ?? '') ?>)</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Proposals Received</span>
                                            <span
                                                class="detail-value proposals-count"><?= htmlspecialchars($p['Proposal_Count'] ?? '0') ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Level</span>
                                            <span
                                                class="detail-value project-level"><?= htmlspecialchars($p['Level'] ?? '') ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Duration</span>
                                            <span
                                                class="detail-value project-duration"><?= htmlspecialchars($p['Duration'] ?? '') ?></span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        <?php endforeach; ?>

                        <!-- Pagination Draft -->
                        <div class="pagination" aria-label="Pagination Draft Requests">
                            <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                            <button class="page-btn active">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn">3</button>
                            <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- EXPIRED REQUESTS SECTION -->
            <div class="expired-posts requests-section expired-section" style="display: none;">
                <div class="item-list">
                    <?php if (empty($data['expiredPosts'])): ?>
                        <section class="empty-state" aria-label="No expired requests">
                            <!-- ...illustration... -->
                            <h2>No expired requests right now</h2>
                            <p>Your expired requests will appear here. You can repost them anytime.</p>
                        </section>
                    <?php else: ?>
                        <?php foreach ($data['expiredPosts'] as $row): ?>
                            <?php
                            if (!is_array($row) || !isset($row['post']) || !is_array($row['post'])) {
                                continue;
                            }
                            $p = $row['post'];
                            $Expired_At = $p['End_At'] ?? null;
                            $daysPassed = 0;
                            if ($Expired_At) {
                                try {
                                    $tz = new DateTimeZone('Asia/Colombo');
                                    $expired = new DateTime($Expired_At, $tz);
                                    $now = new DateTime('now', $tz);
                                    $daysPassed = $now->diff($expired)->days;
                                } catch (Throwable $e) {
                                    $daysPassed = 0;
                                }
                            }
                            ?>
                            <div class="search-item">
                                <input type="hidden" class="post-id" value="<?= htmlspecialchars($p['Post_ID'] ?? '') ?>">
                                <div class="post-header">
                                    <div class="post-meta">
                                        <div class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <span>
                                                <?php if ($daysPassed > 1) {
                                                    echo "Expired " . $daysPassed . " days ago.";
                                                } else if ($daysPassed == 0) {
                                                    echo "Expired Today";
                                                } else {
                                                    echo "Expired Yesterday";
                                                } ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-actions">
                                        <button class="action-btn btn-view">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </button>
                                        <button class="action-btn btn-edit">
                                            <i class="fas fa-redo"></i>
                                            Repost
                                        </button>
                                        <button class="action-btn btn-delete">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                <h3 class="post-title"><?= htmlspecialchars($p['Title'] ?? '') ?></h3>

                                <div class="post-description">
                                    <?php
                                    $desc = (string) ($p['Description'] ?? '');
                                    $plain = trim(preg_replace('/\s+/', ' ', strip_tags($desc)));
                                    $snippet = str_snippet($plain, 160, '…'); // limit to ~160 chars
                                    ?>
                                    <?= htmlspecialchars($snippet, ENT_QUOTES, 'UTF-8') ?>
                                </div>

                                <div class="post-skills">
                                    <span class="skills-label">Required Skills:</span>
                                    <div class="skills-tags">
                                        <?php
                                        if (!empty($row['skills'])) {
                                            foreach ($row['skills'] as $skill): ?>
                                                <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
                                            <?php endforeach;
                                        } else { ?>
                                            <span class="">---No skills specified---</span>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="post-footer">
                                    <div class="post-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Budget</span>
                                            <span class="detail-value budget-amount">LKR
                                                <?= htmlspecialchars($p['Requesting_Price'] ?? '') ?>/=
                                                (<?= htmlspecialchars($p['Price_Type'] ?? '') ?>)</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Final Proposals</span>
                                            <span
                                                class="detail-value proposals-count"><?= htmlspecialchars($p['ProposalsCount'] ?? 0) ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Duration</span>
                                            <span
                                                class="detail-value project-duration"><?= htmlspecialchars($p['Duration'] ?? '') . ' ' . htmlspecialchars($p['Duration_Type'] ?? '') ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="engagement-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-eye"></i>
                                        <span>198 views</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>Expired</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Pagination Expired -->
                        <div class="pagination" aria-label="Pagination Expired Requests">
                            <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                            <button class="page-btn active">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn">3</button>
                            <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

</body>


<div class="dialog-box-2" id="create-post-popup">
    <div class="dialog-content" style="width: 500px;">
        <div class="dialog-title">
            <div class="title">Create A New Service Request</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('create-post-popup')"></i>
            </div>
        </div>
        <form id="create-post-form" class="create-post-form" onsubmit="return false;">
            <div class="input-grid-1">
                <div class="text-container">
                    <div class="label text-label">Title</div>
                    <input type="text" class="text-field" name="title" id="">
                </div>
            </div>
            <div class="input-grid-1">
                <div class="text-container">
                    <div class="label text-label">Description</div>
                    <textarea class="text-field" spellcheck="false" name="description"></textarea>
                </div>
            </div>
            <div class="input-grid-1">
                <div class="search-select-container" data-idinput="Category_ID">
                    <div class="text-container">
                        <div class="label search-dropdown-label">Service Category</div>
                        <input type="text" class="text-field-search-dropdown" name="category" id="Category"
                            autocomplete="off" onkeydown="return false">
                    </div>
                    <div class="options">
                        <span class="text-container">
                            <input type="text" class="text-field-search">
                        </span>
                        <div class="option-list" onclick="selectCategory(event)">
                            <?php foreach ($categories as $Category): ?>
                                <div data-id="<?= $Category['Category_ID'] ?>">
                                    <?= htmlspecialchars($Category['Name']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="Category_ID" name="categoryid">
            </div>
            <div class="input-grid-1">
                <div style="display: flex; gap: 15px; margin-bottom: 5px;">

                    <div class="search-select-container add-option" style="width: 100%;">

                        <div class="text-container">
                            <div class="label search-dropdown-label" id="field-skill-label">Skill</div>
                            <input type="text" class="text-field-search-dropdown" id="SkillAddInput" autocomplete="off"
                                onkeydown="return false">
                        </div>

                        <div class="options">

                            <span class="text-container">
                                <input type="text" class="text-field-search" placeholder="Enter new skill to add">
                            </span>

                            <div class="option-list" id="SkillsOptionList">

                            </div>
                        </div>
                    </div>

                    <button class="button" style="white-space: nowrap;" onclick="addSkill();">
                        <i class="fa-solid fa-plus" style="margin-right: 10px;"></i>Add
                    </button>

                </div>
            </div>
            <div class="chip-wrapper" id="SkillsChips" style="margin-bottom:20px">
                <input type="hidden" id="Skills" name="skills">

                <p>No skill selected</p>
            </div>
            <div class="input-grid-2">
                <div class="text-container">
                    <div class="label text-label">Requesting Price</div>
                    <input type="text" class="text-field" name="price" id="">
                </div>
                <div class="search-select-container">
                    <div class="text-container">
                        <div class="label search-dropdown-label">Price Type</div>
                        <input type="text" class="text-field-search-dropdown" autocomplete="off"
                            onkeydown="return false" name="pricetype" id="">
                    </div>
                    <div class="options">
                        <div class="option-list">
                            <div>Fixed</div>
                            <div>Hourly</div>
                            <div>Daily</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="input-grid-2">
                <div class="text-container">
                    <div class="label text-label">Duration</div>
                    <input type="text" class="text-field" name="duration" id="">
                </div>
                <div class="search-select-container">
                    <div class="text-container">
                        <div class="label search-dropdown-label">Duration Type</div>
                        <input type="text" class="text-field-search-dropdown" autocomplete="off"
                            onkeydown="return false" name="durationtype" id="">
                    </div>
                    <div class="options">
                        <div class="option-list">
                            <div>Days</div>
                            <div>Weeks</div>
                            <div>Months</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="input-grid-2">
                <div class="search-select-container">
                    <div class="text-container">
                        <div class="label search-dropdown-label">Level</div>
                        <input type="text" class="text-field-search-dropdown" autocomplete="off"
                            onkeydown="return false" name="level" id="">
                    </div>
                    <div class="options">
                        <div class="option-list">
                            <div>Beginner</div>
                            <div>Intermediate</div>
                            <div>Advanced</div>
                        </div>
                    </div>
                </div>

                <div class="text-container">
                    <div class="label text-label label-float">Expired Date</div>
                    <input type="date" class="text-field" name="endat" id="">
                </div>
            </div>

            <div class="modal-actions">
                <button type="reset" class="action-btn btn-delete" id="create-post-pop-up"
                    data-role="cancel">Reset</button>
                <button type="button" class="action-btn btn-view" onclick="window.submitCreatePost('draft')"
                    id="create-post-pop-up" data-role="save-draft">
                    <i class="fa-regular fa-floppy-disk"></i>
                    Save Draft
                </button>
                <button type="button" class="action-btn btn-edit" onclick="submitPost('publish')"
                    id="create-post-pop-up" data-role="publish">
                    <i class="fa-regular fa-rocket"></i>
                    Publish Request
                </button>
                <button type="button" class="action-btn btn-edit" data-role="save-post" style="display:none;">
                    <i class="fa-regular fa-floppy-disk"></i>
                    Save Request
                </button>
            </div>
        </form>
    </div>

</div>

<div class="dialog-box-2" id="edit-post-popup">
    <div class="dialog-content" style="width: 500px;">
        <div class="dialog-title">
            <div class="title">Create A New Service Request</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('edit-post-popup')"></i>
            </div>
        </div>
        <div class="dialog-body"></div>
    </div>
</div>

<!-- Create Post Modal (matches project pop-up pattern) 
<div class="pop-up-section create-post-pop-up deactive">
    <div class="pop-up deactive">
        <div class="pop-up-header">
            <div class="pop-up-title">Create A New Service Request</div>
            <i class="fa-light fa-xmark" id="create-post-pop-up"></i>
        </div>
        <hr>
        <div class="pop-up-content">
            
        </div>
    </div>
</div>-->
<script>

    // (Removed previous capture guard). We'll override togglePopUp safely after external scripts load.
</script>
<!-- Post Details Modal -->
<div class="pop-up-section request-modal deactive" id="postDetailsRoot">
    <div class="pop-up deactive" id="postDetailsModal" style="max-width:720px; border-radius:16px;">
        <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div class="pop-up-title">Post Details</div>
            <i class="fa-light fa-xmark" id="postDetailsClose" style="cursor:pointer;"></i>
        </div>
        <hr>
        <div class="pop-up-content post-view" id="postContent" style="display:flex; flex-direction:column; gap:12px;">

        </div>
        <div class="modal-actions" style="justify-content:flex-end;">
            <button class="action-btn btn-delete" id="modalDeleteBtn"><i class="fa-regular fa-circle-xmark"></i>
                Delete</button>
        </div>
    </div>
</div>


<!-- Confirm Delete Modal -->
<div class="pop-up-section confirm-modal deactive" id="confirmDeleteRoot">
    <div class="pop-up deactive" id="confirmDelete">
        <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div class="pop-up-title">Confirm Delete</div>
            <i class="fa-light fa-xmark" id="confirmDeleteClose" style="cursor:pointer;"></i>
        </div>
        <hr>
        <div class="pop-up-content">
            Are you sure you want to delete this post? This action cannot be undone.
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-view" id="confirmKeep">Keep</button>
            <button class="action-btn btn-delete" id="confirmDeleteBtn"><i class="fa-regular fa-circle-xmark"></i> Yes,
                Delete</button>
        </div>
    </div>
</div>

<!-- Confirm Publish Modal -->
<div class="pop-up-section confirm-modal deactive" id="confirmPublishRoot">
    <div class="pop-up deactive" id="confirmPublish">
        <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div class="pop-up-title">Publish Request</div>
            <i class="fa-light fa-xmark" id="confirmPublishClose" style="cursor:pointer;"></i>
        </div>
        <hr>
        <div class="pop-up-content">
            Are you sure you want to publish this draft? It will become visible for providers to bid.
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-view" id="confirmPublishKeep">Cancel</button>
            <button class="action-btn btn-edit" id="confirmPublishBtn"><i class="fa-regular fa-rocket"></i>
                Publish
            </button>
        </div>
    </div>
</div>
<script>
    function selectCategory(e) {
        // same behavior as registration, but robustly read the clicked option
        const opt = e?.target?.closest('[data-id]');
        if (opt) {
            document.getElementById("Category_ID").value = opt.dataset.id;
        }
        const CategoryID = document.getElementById("Category_ID").value;
        // reset skills dropdown UI
        document.getElementById("SkillAddInput").value = "";
        document.getElementById("SkillsOptionList").innerHTML = "";
        document.getElementById("SkillAddInput").parentElement
            .querySelector(".label").classList.remove("label-float");

        // absolute URL (like reg page but for requests endpoint)
        fetch("<?= BASE_URL ?>/requests/get-skills", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "category_id=" + encodeURIComponent(CategoryID)
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {
                    data.result.forEach(element => {
                        addItemToDropdown("SkillAddInput", element.Skill, false, element.Skill_ID);
                    });
                } else {
                    console.log(data);
                }
            })
            .catch(err => console.error(err));
    }

    function addSkill() {
        if (addChip('SkillsChips', document.getElementById("SkillAddInput").value, document.getElementById("SkillAddInput").dataset.id)) {
            document.getElementById("SkillAddInput").value = "";
            document.getElementById("SkillAddInput").parentElement
                .querySelector(".label").classList.remove("label-float");
        } else {
            document.getElementById("SkillAddInput").focus();
        }
    }
    // expose to inline onclick


    function daysConvert(days) {

        if (days >= 365) {
            const years = Math.floor(days / 365);
            return years + (years === 1 ? " year" : " years");
        }
        else if (days >= 30) {
            const months = Math.floor(days / 30);
            return months + (months === 1 ? " month" : " months");
        }
        else if (days >= 7) {
            const weeks = Math.floor(days / 7);
            return weeks + (weeks === 1 ? " week" : " weeks");
        } else {
            return days + (days === 1 ? " day" : " days");
        }
    }


    function submitPost(action) {

        // Validate required fields
        const title = document.querySelector("input[name='title']").value.trim();
        const description = document.querySelector("textarea[name='description']").value.trim();
        const categoryId = document.getElementById("Category_ID").value;
        const skillsString = document.getElementById("Skills").value;
        const skillIds = skillsString.match(/\d+/g)?.map(id => parseInt(id, 10)) || [];
        const skillIdsString = skillIds.join(','); // "1,5,8,12"
        console.log("Skills IDs String:", skillIdsString);


        // if (!title) {
        //     alert('Please enter a title');
        //     document.querySelector("input[name='title']").focus();
        //     return;
        // }

        // if (!description) {
        //     alert('Please enter a description');
        //     document.querySelector("textarea[name='description']").focus();
        //     return;
        // }

        // if (!categoryId) {
        //     alert('Please select a category');
        //     return;
        // }

        // Collect all form data manually
        const postData = {
            title: title,
            description: description,
            category_id: categoryId,
            skills: skillIdsString,
            price: document.querySelector("input[name='price']").value.trim() || '',
            price_type: document.querySelector("input[name='pricetype']").value.trim() || '',
            duration: document.querySelector("input[name='duration']").value.trim() || '',
            duration_type: document.querySelector("input[name='durationtype']").value.trim() || '',
            level: document.querySelector("input[name='level']").value.trim() || '',
            end_at: document.querySelector("input[name='endat']").value || '',
            status: action // 'draft' or 'publish'
        };

        // Convert to URL-encoded format
        const urlEncodedData = Object.keys(postData)
            .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(postData[key]))
            .join('&');

        const url = "<?= BASE_URL ?>/requests/create";
        console.log("Calling URL:", url);
        console.log("POST data:", postData);
        // Send AJAX request
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: urlEncodedData
        })
            .then(response => {
                console.log("Response status:", response.status);
                console.log("Response headers:", response.headers.get('content-type'));

                // Get the raw text first to see what's being returned
                return response.text().then(text => {
                    console.log("Raw response:", text);

                    // Try to parse as JSON
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error("Failed to parse JSON:", e);
                        throw new Error("Server returned invalid JSON: " + text.substring(0, 200));
                    }
                });
            })
            .then(data => {
                console.log("Parsed data:", data);
                if (data.success) {
                    alert(action === 'draft' ? 'Draft saved successfully!' : 'Post published successfully!');
                    closeDialogBox('create-post-popup');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create post'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            });
    }

    function viewPost(id) {
        viewDialogBox('edit-post-popup');

        fetch("<?= BASE_URL ?>/requests/view/" + id)
            .then(response => response.json())
            .then(post => {
                if (post.error) {
                    console.error('Error fetching post:', post.error);
                    return;
                }

                // Format date
                const publishDate = post.Published_At ?
                    new Date(post.Published_At).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : 'N/A';

                // Build skills HTML
                const skillsHTML = post.skills && post.skills.length > 0
                    ? post.skills.map(skill => `<span class="skill-tag">${skill}</span>`).join('')
                    : '<span>No skills specified</span>';

                // Update title
                document.querySelector("#edit-post-popup .dialog-title .title").innerText = "Post Details";

                // Replace form content with a div wrapper for proper styling
                const formContainer = document.querySelector("#edit-post-popup .dialog-body");
                formContainer.innerHTML = `
                <div class="post-view">
                    <div class="post-view-title">${post.Title || 'Untitled'}</div>
                    <div class="post-view-meta">
                        <span class="chip"><i class="fa-regular fa-calendar"></i><span>${publishDate}</span></span>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Description</div>
                        <div class="section-body">${post.Description || 'No description provided'}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Required Skills</div>
                        <div class="skills-row">${skillsHTML}</div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Details</div>
                        <div class="kv-grid">
                            <div class="kv-item"><span class="kv-label">Budget:</span><span class="kv-value">LKR ${post.Requesting_Price || '0'}/= (${post.Price_Type || 'N/A'})</span></div>
                            <div class="kv-item"><span class="kv-label">Level:</span><span class="kv-value">${post.Level || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Duration:</span><span class="kv-value">${post.Duration || 'N/A'} ${post.Duration_Type || 'N/A'}</span></div>
                            <div class="kv-item"><span class="kv-label">Proposals:</span><span class="kv-value">${post.Proposal_Count || '0'}</span></div>
                        </div>
                    </div>
                    <div class="post-view-section">
                        <div class="section-title">Engagement</div>
                        <div class="engagement-row">
                            <span class="chip"><i class="fa-regular fa-eye"></i> ${post.Views || '0'} views</span>
                        </div>
                    </div>
                </div>
            `;
            })
            .catch(error => {
                console.error('Error fetching post:', error);
                alert('Failed to load post details. Please try again.');
            });
    }

    function editPost(id) {
        const root = document.querySelector('.create-post-pop-up');
        const modal = root.querySelector('.pop-up');
        if (root && modal) {
            root.classList.remove('deactive');
            modal.classList.remove('deactive');
        }

        fetch("<?= BASE_URL ?>/requests/view/" + id)
            .then(response => response.json())
            .then(post => {
                if (post.error) {
                    console.error('Error fetching post:', post.error);
                    return;
                }

                // Format date
                const publishDate = post.Published_At ?
                    new Date(post.Published_At).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : 'N/A';

                root.querySelector(".pop-up-title").innerText = "Edit Post";
                root.querySelectorAll(".label").forEach(label => {
                    label.classList.add("label-float");
                });
                root.querySelector("#field-skill-label").classList.remove("label-float");
                root.querySelector("input[name='title']").value = post.Title || '';
                root.querySelector("textarea[name='description']").value = post.Description || '';

                root.querySelector("input[name='category']").value = post.Category_Name || '';
                document.getElementById("Category_ID").value = post.Category_ID || '';

                // Clear existing skills first
                const skillsChips = root.querySelector("#SkillsChips");
                skillsChips.innerHTML = '<input type="hidden" id="Skills" name="skills"><p>No skill selected</p>';

                // Add each skill using the existing addChip function
                if (post.skills && post.skills.length > 0) {
                    post.skills.forEach(skill => {
                        addChip('SkillsChips', skill);
                    });
                }

                root.querySelector("input[name='price']").value = post.Requesting_Price || '';
                root.querySelector("input[name='pricetype']").value = post.Price_Type || '';
                root.querySelector("input[name='duration']").value = post.Duration || '';
                root.querySelector("input[name='durationtype']").value = post.Duration_Type || '';
                root.querySelector("input[name='level']").value = post.Level || '';


                const endAtInput = root.querySelector("input[name='endat']");
                if (post.End_At) {
                    // Extract date part from datetime string (format: YYYY-MM-DD HH:MM:SS)
                    const datePart = post.End_At.split(' ')[0];
                    endAtInput.value = datePart;
                } else {
                    endAtInput.value = '';
                }

                // Change buttons: hide draft/publish, show save button
                const saveDraftBtn = root.querySelector('[data-role="save-draft"]');
                const publishBtn = root.querySelector('[data-role="publish"]');
                const savePostBtn = root.querySelector('[data-role="save-post"]');

                if (saveDraftBtn) saveDraftBtn.style.display = 'none';
                if (publishBtn) publishBtn.style.display = 'none';
                if (savePostBtn) {
                    savePostBtn.style.display = '';
                    // Set the post ID for update
                    savePostBtn.setAttribute('data-post-id', post.Post_ID);
                }

                // Load skills for the selected category
                if (post.Category_ID) {
                    fetch("<?= BASE_URL ?>/posts/get-skills", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "category_id=" + encodeURIComponent(post.Category_ID)
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'ok') {
                                const skillsOptionList = document.getElementById("SkillsOptionList");
                                skillsOptionList.innerHTML = '';
                                data.result.forEach(element => {
                                    addItemToDropdown("SkillAddInput", element.Skill, false);
                                });
                            }
                        })
                        .catch(err => console.error('Error loading skills:', err));
                }

            })
            .catch(error => {
                console.error('Error fetching post:', error);
                alert('Failed to load post details. Please try again.');
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Get all elements with id 'create-post-popup' (buttons to open modal)
        const createPostBtns = document.querySelectorAll('[id="create-post-popup"]');

        createPostBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                const root = document.querySelector('#create-post-popup');

                // Check if we're opening the modal (has deactive class)
                if (root && root.classList.contains('deactive')) {
                    // Reset the entire form
                    const form = document.getElementById('create-post-form');
                    if (form) {
                        form.reset();
                    }

                    // Clear skills chips
                    const skillsChips = document.querySelector("#SkillsChips");
                    if (skillsChips) {
                        skillsChips.innerHTML = '<input type="hidden" id="Skills" name="skills"><p>No skill selected</p>';
                    }

                    // Clear category ID
                    const categoryId = document.getElementById("Category_ID");
                    if (categoryId) {
                        categoryId.value = "";
                    }

                    // Remove label-float from all labels
                    root.querySelectorAll(".label").forEach(label => {
                        label.classList.remove("label-float");
                    });

                    // Clear skills dropdown
                    const skillAddInput = document.getElementById("SkillAddInput");
                    if (skillAddInput) {
                        skillAddInput.value = "";
                    }
                    const skillsOptionList = document.getElementById("SkillsOptionList");
                    if (skillsOptionList) {
                        skillsOptionList.innerHTML = "";
                    }

                    // Set title to "Create a New Post"
                    const title = root.querySelector(".pop-up-title");
                    if (title) {
                        title.innerText = "Create a New Post";
                    }

                    // Show the draft/publish buttons, hide save button
                    const saveDraftBtn = root.querySelector('[data-role="save-draft"]');
                    const publishBtn = root.querySelector('[data-role="publish"]');
                    const savePostBtn = root.querySelector('[data-role="save-post"]');

                    if (saveDraftBtn) saveDraftBtn.style.display = '';
                    if (publishBtn) publishBtn.style.display = '';
                    if (savePostBtn) savePostBtn.style.display = 'none';
                }
            });
        });
    });



</script>

</html>