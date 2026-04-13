<?php
require_once __DIR__ . '/../../../../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Provider Profile</title>

    <!--stylesheets-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!--scripts-->
    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/profile.js" defer></script>

    <style>
        #AddServiceDialog h5 {
            text-align: center;
            font-size: 0.9em;
            margin-top: 35px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../../includes/navbar.php'; ?>
    <main>

        <input type="hidden" id="providerId" value="<?= htmlspecialchars($user['Provider_ID']) ?>" />

        <section class="profile-hero">
            <div class="hero-container">
                <div class="hero-avatar-section">
                    <div class="hero-avatar-wrap">
                        <img class="hero-avatar" id="avatarPublicPreview"
                            src="<?= BASE_URL . '/file/user-files/' . $_SESSION['user_image'] ?>" />
                        <button id="avatarSaveBtn" class="avatar-save-btn" style="display:none;" onclick="saveAvatar()"><i
                                class="fa-solid fa-floppy-disk"></i> Save</button>
                    </div>
                    <div class="hero-actions">
                        <button class="btn btn-primary" onclick="document.getElementById('avatarPublicInput').click()"><i
                                class="fa-solid fa-camera"></i> Edit Photo</button>
                        <input type="file" id="avatarPublicInput" accept="image/*" style="display:none" />
                    </div>
                </div>
                <div class="hero-details">
                    <h1 id="publicSummaryName"><?= htmlspecialchars($user['First_Name']) ?>
                        <?= htmlspecialchars($user['Last_Name']) ?>
                    </h1>
                    <div class="muted"><i class="fa-solid fa-envelope"></i> <span
                            id="publicSummaryEmail"><?= htmlspecialchars($user['Email']) ?></span>
                    </div>

                    <div class="muted"><i class="fa-solid fa-id-card"></i> <span
                            id="publicSummaryEmail"><?= htmlspecialchars($user['NIC_No']) ?></span>
                    </div>

                    <p class="hero-bio">
                        <?= htmlspecialchars($user['Bio']) ?: 'No bio available.' ?>
                    </p>
                </div>
            </div>
        </section>

        <div class="profile-shell">
            <nav class="profile-nav" aria-label="Profile sections">
                <button class="pill" data-target="section-personal" aria-current="true">
                    <i class="fa-solid fa-user"></i> Personal <span class="count">Info</span>
                </button>
                <button class="pill" data-target="section-work" aria-current="false">
                    <i class="fa-solid fa-briefcase"></i> Work <span class="count" id="countCategories">0</span>
                </button>
                <button class="pill" data-target="section-account" aria-current="false"><i
                        class="fa-solid fa-lock"></i> Security <span class="count">Settings</span>
                </button>
                <a href="<?= BASE_URL ?>/logout" class="btn-logout pill">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </nav>
            <div class="profile-content">
                <section id="section-personal" class="profile-section active" aria-label="Personal information">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-solid fa-user"></i> Personal Information</h2>
                        <form id="personalForm" enctype="multipart/form-data" onsubmit="savePersonal(event)">
                            <div class="input-grid-3">
                                <div class="text-container">
                                    <div class="label text-label label-float">First Name</div>
                                    <input type="text" class="text-field" name="first_name" value="<?= htmlspecialchars($user['First_Name']) ?>" required>
                                </div>
                                <div class="text-container">
                                    <div class="label text-label label-float">Last Name</div>
                                    <input type="text" class="text-field" name="last_name" value="<?= htmlspecialchars($user['Last_Name']) ?>" required>
                                </div>
                                <div class="select-container">
                                    <div class="text-container">
                                        <div class="label dropdown-label label-float">Gender</div>
                                        <input type="text" class="text-field-dropdown" readonly name="gender" value="<?= htmlspecialchars($user['Gender']) ?>"
                                            id="genderInput">
                                    </div>


                                    <div class="options">
                                        <div>Male</div>
                                        <div>Female</div>
                                        <div>Prefer not to say</div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-grid-3">
                                <div class="text-container">
                                    <div class="label text-label label-float">Email</div>
                                    <input type="email" class="text-field" name="email" value="<?= htmlspecialchars($user['Email']) ?>" readonly>
                                </div>
                                <div class="text-container">
                                    <div class="label text-label label-float">NIC No</div>
                                    <input type="text" class="text-field" name="nic" value="<?= htmlspecialchars($user['NIC_No']) ?>" readonly>
                                </div>
                                <div class="text-container">
                                    <div class="label text-label label-float">Contact number</div>
                                    <input type="text" class="text-field" name="contact_no" value="<?= htmlspecialchars($user['Contact_No']) ?>" required>
                                </div>
                            </div>

                            <div class="input-grid-1">
                                <div class="text-container">
                                    <div class="label text-label label-float">Website</div>
                                    <input type="text" class="text-field" name="website" value="<?= htmlspecialchars($user['Website']) ?>">
                                </div>
                            </div>
                            <div class="input-grid-1">
                                <div class="text-container">
                                    <div class="label text-label label-float">Bio</div>
                                    <textarea class="text-field" name="bio"
                                        spellcheck="false"><?= htmlspecialchars($user['Bio']) ?></textarea>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field" style="flex:1 1 300px;">
                                    <label>NIC - Front (read only)</label>
                                    <div class="card" style="padding:12px; border-radius:12px;">
                                        <img src="<?= BASE_URL . '/file/user-files/' . $user['NIC_Front'] ?>" alt="NIC Front"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;"
                                            onerror="this.style.display='none'">
                                        <div class="actions" style="margin-top:10px;">
                                            <a class="btn btn-ghost"
                                                href="<?= BASE_URL . '/file/user-files/' . $user['NIC_Front'] ?>" target="_blank"
                                                rel="noopener"><i class="fa-solid fa-eye"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="field" style="flex:1 1 300px;">
                                    <label>NIC - Back (read only)</label>
                                    <div class="card" style="padding:12px; border-radius:12px;">
                                        <img src="<?= BASE_URL . '/file/user-files/' . $user['NIC_Back'] ?>" alt="NIC Back"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;"
                                            onerror="this.style.display='none'">
                                        <div class="actions" style="margin-top:10px;">
                                            <a class="btn btn-ghost"
                                                href="<?= BASE_URL . '/file/user-files/' . $user['NIC_Back'] ?>" target="_blank"
                                                rel="noopener"><i class="fa-solid fa-eye"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-row" style="margin-top: 25px;">
                                <div class="field" style="flex:1 1 300px;">
                                    <label>Resume</label>
                                    <div class="card"
                                        style="padding:12px; border-radius:12px;">

                                        <input type="file" name="Resume" accept="application/pdf" style="display: none;" />
                                        <script>
                                            window.addEventListener('DOMContentLoaded', () => {
                                                const fileInput = document.getElementsByName('Resume')[0];
                                                const viewer = document.getElementsByName('Resume')[0].parentElement.querySelector('iframe');

                                                fileInput.addEventListener('change', function() {
                                                    const file = this.files[0];

                                                    if (file) {
                                                        // Validate PDF type
                                                        if (file.type !== "application/pdf") {
                                                            alert("Only PDF files are allowed.");
                                                            fileInput.value = "";
                                                            return;
                                                        }

                                                        // Create a URL and show in iframe
                                                        const fileURL = URL.createObjectURL(file);
                                                        viewer.src = fileURL;
                                                        viewer.style.display = "block";
                                                    }
                                                });
                                            });
                                        </script>


                                        <?php
                                        if ($user["Resume"] != '') {
                                        ?>

                                            <div style="display:flex; align-items:center; justify-content:end; gap:12px; margin-bottom: 10px;">

                                                <button class="button outline" type="button" onclick="document.getElementsByName('Resume')[0].click()">
                                                    <i class="fa-solid fa-upload" style="margin-right: 10px;"></i>
                                                    Upload
                                                </button>

                                                <a class="btn btn-outline" href="<?= BASE_URL ?>/file/user-files/<?= urlencode($user["Resume"]) ?>"
                                                    target="_blank" rel="noopener"><i class="fa-solid fa-download"></i>
                                                    Download</a>

                                            </div>

                                            <iframe src="<?= BASE_URL ?>/file/user-files/<?= urlencode($user["Resume"]) ?>"
                                                width="100%"
                                                height="600px"
                                                style="border:none;">
                                            </iframe>


                                        <?php
                                        } else {
                                        ?>

                                            <div class="muted" id="NoResumeTag">No resume uploaded.</div>

                                            <iframe
                                                width="100%"
                                                height="600px"
                                                style="border:none; display: none;">
                                            </iframe>


                                            <div style="display:flex; align-items:center; justify-content:end; gap:12px; margin-top: 10px;">

                                                <button class="button outline" type="button" onclick="document.getElementsByName('Resume')[0].click()">
                                                    <i class="fa-solid fa-upload" style="margin-right: 10px;"></i>
                                                    Upload
                                                </button>

                                            </div>

                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <div class="actions">
                                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i>
                                    Save Personal</button>
                                <button class="btn btn-outline" type="button" onclick="resetPersonal()"><i
                                        class="fa-solid fa-rotate-left"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                </section>

                <section id="section-work" class="profile-section" aria-label="Work information">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-solid fa-briefcase"></i> Service Areas</h2>
                        <div class="field-row" style="align-items:center;">
                            <div class="field" style="flex:1 1 auto;">
                                <label for="cat_search">Search</label>
                                <input type="text" id="cat_search" placeholder="Search categories..." />
                            </div>
                            <div class="actions" style="margin-top: 18px;">
                                <button class="btn btn-primary" type="button" onclick="viewDialogBox('AddServiceDialog')"><i
                                        class="fa-solid fa-plus"></i> Add Service</button>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div id="service-card-wrapper"></div>
                    </div>
                </section>

                <section id="section-account" class="profile-section" aria-label="Account & security">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-solid fa-shield-keyhole"></i> Account & Security</h2>
                        <form id="accountForm" onsubmit="saveAccount(event)">

                            <div class="password-box">
                                <h4><i class="fa-solid fa-lock"></i> Password & Recovery</h4>
                                <div class="field-row">
                                    <div class="field">
                                        <label for="acc_old_pass">Old Password</label>
                                        <input type="password" id="acc_old_pass" name="Old_Password"
                                            placeholder="••••••••" />
                                    </div>
                                    <div class="field">
                                        <label for="acc_new_pass">New Password</label>
                                        <input type="password" id="acc_new_pass" name="New_Password"
                                            placeholder="••••••••" />
                                    </div>
                                </div>

                            </div>
                            <div class="actions">
                                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i>
                                    Save
                                    Account</button>
                                <button class="btn btn-outline" type="button" onclick="resetAccount()"><i
                                        class="fa-solid fa-rotate-left"></i> Reset</button>
                            </div>
                            <div class="divider" style="margin:22px 0;"></div>
                        </form>
                        <div class="danger-zone">
                            <h4><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</h4>
                            <p>Deleting your account removes all associated data. This cannot be undone.</p>
                            <button class="danger-btn" onclick="deleteAccount()"><i class="fa-solid fa-trash"></i>
                                Delete Account</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>


    <div class="toast" id="toast"><i class="fa-solid fa-circle-check" style="color:#008500;"></i><span
            id="toastMsg">Saved</span></div>




    <?php include __DIR__ . '/../../register/addServiceDialog.php' ?>

    <script>
        const AddServiceCardTemplate = "provider-profile-template";
    </script>
    <script src="<?= BASE_URL ?>/assets/js/addServiceScript.js" defer></script>



</body>

</html>