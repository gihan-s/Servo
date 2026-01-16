<?php include_once '../helpers/locations.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/providerView.css">


<div class="provider-wrapper">

    <div class="container">

        <h4 class="provider-subtitle">Personal Details</h4>

        <div class="table-layout">

         <span class="key">Provider ID</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["Provider_ID"]) ?></span>

            <span class="key">First Name</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["First_Name"]) ?></span>

            <span class="key">Last Name</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["Last_Name"]) ?></span>

            <span class="key">Gender</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["Gender"]) ?></span>

            <span class="key">Email</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["Email"]) ?></span>

            <span class="key">Contact No</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["Contact_No"]) ?></span>

            <span class="key">Registered At</span>
            <span>:</span>
            <span class="value"><?= date("Y-m-d", strtotime($user["Created_At"])) ?> &nbsp; <?= date("h:i A", strtotime($user["Created_At"])) ?></span>

            <span class="key">Website</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["Website"]) ?></span>

            <span class="key">Bio</span>
            <span>:</span>
            <span class="value"><?= htmlspecialchars($user["Bio"]) ?></span>
        </div>
    </div>


    <div class="container">

        <h4 class="provider-subtitle">Profile Picture</h4>

        <img class="profile-picture" src="<?= BASE_URL ?>/file/user-files/<?= urlencode($user["Profile_Picture"]) ?>" alt="">

    </div>

    <div class="container">

        <h4 class="provider-subtitle">NIC Front</h4>

        <img class="profile-picture" src="<?= BASE_URL ?>/file/user-files/<?= urlencode($user["NIC_Front"]) ?>" alt="">

    </div>

    <div class="container">

        <h4 class="provider-subtitle">NIC Back</h4>

        <img class="profile-picture" src="<?= BASE_URL ?>/file/user-files/<?= urlencode($user["NIC_Back"]) ?>" alt="">

    </div>

</div>

<?php
if ($user["Resume"] != '') {
?>

    <div class="container" style="margin-bottom: 25px;">
        <h4 class="provider-subtitle">Resume</h4>

        <iframe src="<?= BASE_URL ?>/file/user-files/<?= rawurlencode($user["Resume"]) ?>"
            width="100%"
            height="600px"
            style="border:none;">
        </iframe>

    </div>

<?php
}
?>


<h3 class="provider-title">Service Areas</h3>

<?php foreach ($user['Categories'] as $Category): ?>

<div id="service-card-wrapper">

    <div class="search-item">
        <div class="status-badge status-active"><?= htmlspecialchars($Category['Category_Type']) ?></div>

        <div class="post-header">
            <div class="post-meta">

            </div>
            <div class="post-actions">

            </div>
        </div>

        <h3 class="post-title"><?= htmlspecialchars($Category['Title']) ?></h3>

        <div class="post-description">
            <?= htmlspecialchars($Category['Description']) ?>
        </div>

        <div class="post-skills">
            <span class="skills-label">Skills:</span>
            <div class="skills-tags">
                <?php foreach ($Category['Skills'] as $Skill): ?>
                <span class="skill-tag"><?= $Skill ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="post-footer">
            <div class="post-details">
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="fa-solid fa-circle-dollar"></i>
                        Rs. <?= number_format($Category['Default_Price'], 2) ?> / hr
                    </span>
                </div>
            </div>
        </div>

        <div class="engagement-stats">
            <div class="stat-item">
                <i class="fas fa-location-pin"></i>
                <?= formatLocations($Category['Locations']) ?>
            </div>
        </div>

    </div>

</div>

<?php endforeach; ?>


<form action="./Providers/provider-review" method="post">

<input type="hidden" name="provider_id" value="<?= htmlspecialchars($user["Provider_ID"]) ?>">

    <div style="display: flex; justify-content: space-between;">

        <button name="reject" class="button" style="background-color: #dc2626;">
            <i class="fa-solid fa-circle-xmark" style="margin-right: 10px;"></i>
            Reject Provider
        </button>

        <button name="accept" class="button" style="background-color: #008500;">
            <i class="fa-solid fa-circle-check" style="margin-right: 10px;"></i>
            Accept Provider
        </button>

    </div>

</form>


