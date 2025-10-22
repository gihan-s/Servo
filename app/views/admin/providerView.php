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

        <img class="profile-picture" src="/file/user-files/<?= urlencode($user["Profile_Picture"]) ?>" alt="">

    </div>

    <div class="container">

        <h4 class="provider-subtitle">NIC Front</h4>

        <img class="profile-picture" src="/file/user-files/<?= urlencode($user["NIC_Front"]) ?>" alt="">

    </div>

    <div class="container">

        <h4 class="provider-subtitle">NIC Back</h4>

        <img class="profile-picture" src="/file/user-files/<?= urlencode($user["NIC_Back"]) ?>" alt="">

    </div>

</div>

<?php
if ($user["Resume"] != '') {
?>

    <div class="container" style="margin-bottom: 25px;">
        <h4 class="provider-subtitle">Resume</h4>

        <iframe src="/file/user-files/<?= urlencode($user["Resume"]) ?>"
            width="100%"
            height="600px"
            style="border:none;">
        </iframe>

    </div>

<?php
}
?>


<h3 class="provider-title">Service Areas</h3>

<div id="service-card-wrapper">

    <div class="search-item">
        <div class="status-badge status-active">Graphic Design</div>

        <div class="post-header">
            <div class="post-meta">

            </div>
            <div class="post-actions">

            </div>
        </div>

        <h3 class="post-title">Graphic Design</h3>

        <div class="post-description">
            Skilled in creating visually engaging designs for digital and print media using tools like Adobe Photoshop, Illustrator, and Canva. Experienced in developing creative visuals and layouts that enhance brand identity and user engagement.
        </div>

        <div class="post-skills">
            <span class="skills-label">Skills:</span>
            <div class="skills-tags">
                <span class="skill-tag">Photoshop</span>
                <span class="skill-tag">Ilustrator</span>
                <span class="skill-tag">Canva</span>
            </div>
        </div>

        <div class="post-footer">
            <div class="post-details">
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="fa-solid fa-circle-dollar"></i>
                        Rs. 1200.00 / hr
                    </span>
                </div>
            </div>
        </div>

        <div class="engagement-stats">
            <div class="stat-item">
                <i class="fas fa-location-pin"></i>
                All Districts
                <?php
                // join(", ", json_decode($_SESSION["register"]["locations"][$key])) 
                ?>
            </div>
        </div>

    </div>


</div>

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


<style>
    .provider-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .provider-subtitle {
        text-align: center;
        margin-bottom: 15px;
    }

    .provider-wrapper .table-layout {
        display: grid;
        grid-template-columns: 100px 10px auto;
        font-size: 0.9em;
        row-gap: 3px;
    }

    .provider-wrapper .table-layout .key {
        font-weight: bold;
        opacity: 0.8;
    }

    .provider-wrapper .profile-picture {
        height: 200px;
        width: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .provider-title {
        text-align: center;
        margin-bottom: 15px;
        margin-top: 40px;
    }

    .search-item {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;

        text-align: left;

        margin-bottom: 25px;
    }

    .search-item::before {
        content: none;
    }

    .search-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #008500;
    }

    /* Status Indicators */
    .status-badge {
        position: absolute;
        bottom: 16px;
        /* moved to bottom */
        right: 16px;
        top: auto;
        /* override previous top positioning */
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-active {
        background: #dcfce7;
        color: #008500;
        border: 1px solid #bbf7d0;
    }

    /* Post Header Section */
    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }


    .post-actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: none;
    }

    .btn-delete {
        background: white;
        color: #dc2626;
        border: 2px solid #dc2626;
    }

    .btn-delete i {
        color: #dc2626;
    }


    .btn-delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-1px);
    }

    .btn-delete:hover i {
        color: white;
    }

    /* Post Content Section */
    .post-title {
        font-size: 22px;
        font-weight: 700;
        color: #008500;
        margin-bottom: 16px;
        line-height: 1.4;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .post-description {
        color: #4b5563;
        line-height: 1.7;
        margin-bottom: 20px;
        font-size: 15px;
    }

    .post-skills {
        margin-bottom: 24px;
    }

    .skills-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        display: block;
    }

    .skills-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .skill-tag {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .skill-tag:hover {
        background: #008500;
        color: white;
        border-color: #008500;
        transform: translateY(-1px);
    }


    /* Post Footer Section */
    .post-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #f3f4f6;
    }

    .post-details {
        display: flex;
        gap: 32px;
    }

    .detail-item {
        text-align: center;
    }

    .detail-label {
        display: block;
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }


    .engagement-stats {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }
</style>