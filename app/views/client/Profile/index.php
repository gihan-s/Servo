<?php if (!empty($_SESSION['flash'])): ?>
    <script>
        window.__FLASH__ = <?php echo json_encode($_SESSION['flash']); ?>;
    </script>
    <?php unset($_SESSION['flash']); endif; ?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>

    <!--stylesheets-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />

    <!--scripts-->
    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/profile.js" defer></script>
</head>

<body>
    <?php include_once __DIR__ . '/../../includes/navbar.php'; ?>
    <main>
        <section class="profile-hero">
            <div class="hero-inner">
                <div class="hero-avatar-wrap">
                    <img class="hero-avatar" id="avatarPublicPreview"
                        src="<?= BASE_URL . htmlspecialchars($user['Profile_Picture']) ?>" alt="Avatar" />
                    <button id="avatarSaveBtn" class="avatar-save-btn" style="display:none;" onclick="saveAvatar()"><i
                            class="fa-regular fa-floppy-disk"></i> Save</button>
                </div>
                <div class="hero-text">
                    <h1 id="publicSummaryName"><?= htmlspecialchars($user['First_Name']) ?>
                        <?= htmlspecialchars($user['Last_Name']) ?>
                    </h1>
                    <div class="muted"><i class="fa-regular fa-envelope"></i> <span
                            id="publicSummaryEmail"><?= htmlspecialchars($user['Email']) ?></span>

                    </div>
                </div>
                <div class="hero-actions">
                    <button class="btn btn-primary" onclick="document.getElementById('avatarPublicInput').click()"><i
                            class="fa-regular fa-camera"></i> Edit Photo</button>
                    <input type="file" id="avatarPublicInput" accept="image/*" style="display:none" />
                </div>
            </div>
        </section>

        <div class="profile-shell">
            <nav class="profile-nav" aria-label="Profile sections">
                <button class="pill" data-target="section-personal" aria-current="true"><i
                        class="fa-regular fa-user"></i> Personal <span class="count">Info</span></button>
                <button class="pill" data-target="section-account" aria-current="false"><i
                        class="fa-regular fa-shield-check"></i> Security <span class="count">Settings</span></button>
            </nav>
            <div class="profile-content">
                <section id="section-personal" class="profile-section active" aria-label="Personal information">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-regular fa-user"></i> Personal Information</h2>
                        <form id="personalForm" action="/update" onsubmit="savePersonal(event)">
                            <div class="input-grid-3">
                                <div class="text-container">
                                    <div class="label text-label label-float">First Name</div>
                                    <input type="text" class="text-field" name="first_name"
                                        value="<?= htmlspecialchars($user['First_Name']) ?>" required>
                                </div>
                                <div class="text-container">
                                    <div class="label text-label label-float">Last Name</div>
                                    <input type="text" class="text-field" name="last_name"
                                        value="<?= htmlspecialchars($user['Last_Name']) ?>" required>
                                </div>
                                <div class="select-container">
                                    <div class="text-container">
                                        <div class="label dropdown-label label-float">Gender</div>
                                        <input type="text" class="text-field-dropdown" readonly name="gender"
                                            value="<?= htmlspecialchars($user['Gender']) ?>" id="genderInput">
                                    </div>


                                    <div class="options">
                                        <div>Male</div>
                                        <div>Female</div>
                                        <div>Prefer not to say</div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-grid-2">
                                <div class="text-container">
                                    <div class="label text-label label-float">Email</div>
                                    <input type="email" class="text-field" name="email"
                                        value="<?= htmlspecialchars($user['Email']) ?>" required readonly>
                                </div>
                                <div class="text-container">
                                    <div class="label text-label label-float">Contact number</div>
                                    <input type="text" class="text-field" name="contact_no" inputmode="numeric"
                                        maxlength="10" pattern="\d{0,10}"
                                        value="<?= htmlspecialchars($user['Contact_No']) ?>" required>
                                </div>
                            </div>

                            <div class="input-grid-1">
                                <div class="text-container">
                                    <div class="label text-label label-float">Website</div>
                                    <input type="text" class="text-field" name="website"
                                        value="<?= htmlspecialchars($user['Social_Link']) ?>">
                                </div>
                            </div>
                            <div class="input-grid-1">
                                <div class="text-container">
                                    <div class="label text-label label-float">Bio</div>
                                    <textarea class="text-field" name="bio"
                                        spellcheck="false"><?= htmlspecialchars($user['Bio']) ?></textarea>
                                </div>
                            </div>
                            <div class="actions">
                                <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i>
                                    Save Personal</button>
                                <button class="btn btn-outline" type="reset" onclick="resetPersonal()"><i
                                        class="fa-regular fa-rotate-left"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                </section>

                <section id="section-account" class="profile-section" aria-label="Account & security">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-regular fa-shield-keyhole"></i> Account & Security</h2>
                        <form id="accountForm" onsubmit="saveAccount(event)">
                            <div class="input-grid-2">
                                <div class="text-container">
                                    <div class="label text-label label-float">Email</div>
                                    <input type="text" class="text-field" name="email"
                                        value="<?= htmlspecialchars($user['Email']) ?>" required readonly>
                                </div>
                                <div class="select-container">
                                    <div class="text-container">
                                        <div class="label dropdown-label label-float">Status</div>
                                        <input type="text" class="text-field-dropdown" readonly name="status"
                                            value="<?= htmlspecialchars($user['Status']) ?>" id="statusInput">
                                    </div>


                                    <div class="options">
                                        <div>Active</div>
                                        <div>Inactive</div>
                                    </div>
                                </div>
                            </div>
                            <div class="password-box">
                                <h4><i class="fa-regular fa-lock"></i> Password & Recovery</h4>
                                <div class="field-row">
                                    <div class="field">
                                        <label for="acc_new_pass">New Password</label>
                                        <input type="password" id="acc_new_pass" name="New_Password"
                                            placeholder="••••••••" />
                                    </div>
                                    <div class="field">
                                        <label for="acc_confirm_pass">Confirm Password</label>
                                        <input type="password" id="acc_confirm_pass" name="Confirm_Password"
                                            placeholder="••••••••" />
                                    </div>
                                    <div class="field">
                                        <label for="reset_code">Reset Code</label>
                                        <input type="text" id="reset_code" name="reset_code"
                                            placeholder="Enter reset code" />
                                    </div>
                                </div>
                                <div class="small">Leave password fields empty if you don't want to change it.</div>
                                <button type="button" class="link-inline" onclick="forgotPassword()"><i
                                        class="fa-regular fa-envelope"></i> Send Reset Password Code</button>
                            </div>
                            <div class="actions">
                                <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i>
                                    Save
                                    Account</button>
                                <button class="btn btn-outline" type="button" onclick="resetAccount()"><i
                                        class="fa-regular fa-rotate-left"></i> Reset</button>
                            </div>
                        </form>
                        <div class="divider" style="margin:22px 0;"></div>
                        <div class="danger-zone">
                            <h4><i class="fa-regular fa-triangle-exclamation"></i> Danger Zone</h4>
                            <p>Deleting your account removes all associated data. This cannot be undone.</p>
                            <button class="danger-btn" onclick="deleteAccount()"><i class="fa-regular fa-trash"></i>
                                Delete Account</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>


    <div class="toast" id="toast"><i class="fa-regular fa-circle-check" style="color:#008500;"></i><span
            id="toastMsg">Saved</span></div>

    <!-- Forgot Password Modal -->
    <div class="modal-overlay" id="fpOverlay" role="dialog" aria-modal="true" aria-labelledby="fpTitle">
        <div class="modal">
            <button class="close-btn" onclick="closeFP()" aria-label="Close"><i
                    class="fa-regular fa-xmark"></i></button>
            <h3 id="fpTitle">Reset Your Password</h3>
            <form  onsubmit="sendFP(event)">
                <input type="email" name="fp_email" id="fp_email" placeholder="you@example.com" value="<?= htmlspecialchars($user['Email']) ?>" required readonly/>
                <div class="actions" style="margin-top:4px;">
                    <button type="submit" class="btn btn-primary"><i class="fa-regular fa-paper-plane"></i> Send
                        Code</button>
                    <button type="button" class="btn btn-ghost" onclick="closeFP()"><i class="fa-regular fa-xmark"></i>
                        Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Add/Edit Modal -->
    <div class="modal-overlay" id="catOverlay" role="dialog" aria-modal="true" aria-labelledby="catTitle">
        <div class="modal">
            <button class="close-btn" onclick="closeCategoryModal()" aria-label="Close"><i
                    class="fa-regular fa-xmark"></i></button>
            <h3 id="catTitle">Add Category</h3>
            <form id="catModalForm" onsubmit="saveCategoryModal(event)">
                <div class="field-row">
                    <div class="field">
                        <label for="m_cat_select">Category</label>
                        <select id="m_cat_select" name="Category_ID"></select>
                    </div>
                    <div class="field">
                        <label for="m_cat_title">Title</label>
                        <input type="text" id="m_cat_title" name="Title" placeholder="Custom title (optional)" />
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label for="m_cat_price">Default Price</label>
                        <input type="number" id="m_cat_price" name="Default_Price" placeholder="e.g. 1000" />
                    </div>
                    <div class="field" style="flex:1 1 100%;">
                        <label for="m_cat_desc">Description</label>
                        <textarea id="m_cat_desc" name="Description"
                            placeholder="Describe what you provide in this category."></textarea>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Locations</label>
                        <div id="m_cat_locations" class="chip-input"></div>
                        <button class="btn btn-ghost" type="button" onclick="pickLocations(true)"><i
                                class="fa-regular fa-location-dot"></i> Add Locations</button>
                    </div>
                    <div class="field">
                        <label>Skills</label>
                        <div id="m_cat_skills" class="chip-input"></div>
                        <button class="btn btn-ghost" type="button" onclick="pickSkills(true)"><i
                                class="fa-regular fa-wand-magic-sparkles"></i> Add Skills</button>
                    </div>
                </div>
                <input type="hidden" id="m_cat_edit_index" value="" />
                <div class="actions">
                    <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i>
                        Save</button>
                    <button class="btn btn-outline" type="button" onclick="closeCategoryModal()"><i
                            class="fa-regular fa-xmark"></i> Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category View Modal -->
    <div class="modal-overlay" id="catViewOverlay" role="dialog" aria-modal="true" aria-labelledby="catViewTitle">
        <div class="modal">
            <button class="close-btn" onclick="closeCategoryView()" aria-label="Close"><i
                    class="fa-regular fa-xmark"></i></button>
            <h3 id="catViewTitle">Category Details</h3>
            <div id="catViewBody" class="small"></div>
        </div>
    </div>
</body>

</html>