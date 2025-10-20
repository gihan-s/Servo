<?php
require_once __DIR__ . '/../../../../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Provider Profile</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />


    <script src="<?= BASE_URL ?>/assets/js/elementScript.js" defer></script>
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
                            id="publicSummaryEmail"><?= htmlspecialchars($user['Email']) ?></span> · <i
                            class="fa-regular fa-id-card"></i>
                        <span id="publicSummaryNIC"><?= htmlspecialchars($user['NIC_No']) ?></span> · <span
                            class="status-badge"
                            id="publicStatus"><?= htmlspecialchars($user['Approvel_Status']) ?></span>
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
                <button class="pill" data-target="section-work" aria-current="false"><i
                        class="fa-regular fa-briefcase"></i> Work <span class="count"
                        id="countCategories">0</span></button>
                <button class="pill" data-target="section-account" aria-current="false"><i
                        class="fa-regular fa-shield-check"></i> Security <span class="count">Settings</span></button>
            </nav>
            <div class="profile-content">
                <section id="section-personal" class="profile-section active" aria-label="Personal information">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-regular fa-user"></i> Personal Information</h2>
                        <form id="personalForm" onsubmit="savePersonal(event)">
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
                                    <input type="email" class="text-field" name="email" value="<?= htmlspecialchars($user['Email']) ?>" required>
                                </div>
                                <div class="text-container">
                                    <div class="label text-label label-float">NIC No</div>
                                    <input type="text" class="text-field" name="nic" value="<?= htmlspecialchars($user['NIC_No']) ?>" required>
                                </div>
                                <div class="text-container">
                                    <div class="label text-label label-float">Contact number</div>
                                    <input type="text" class="text-field" name="contact_number" value="<?= htmlspecialchars($user['Contact_No']) ?>" required>
                                </div>
                            </div>

                            <div class="input-grid-1">
                                <div class="text-container">
                                    <div class="label text-label label-float">Website</div>
                                    <input type="text" class="text-field" name="website" value="<?= htmlspecialchars($user['Website']) ?>" required>
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
                                        <img src="<?= BASE_URL ?>/uploads/providers/0/nic_front.jpg" alt="NIC Front"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;"
                                            onerror="this.style.display='none'">
                                        <div class="actions" style="margin-top:10px;">
                                            <a class="btn btn-ghost"
                                                href="<?= BASE_URL ?>/uploads/providers/0/nic_front.jpg" target="_blank"
                                                rel="noopener"><i class="fa-regular fa-eye"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="field" style="flex:1 1 300px;">
                                    <label>NIC - Back (read only)</label>
                                    <div class="card" style="padding:12px; border-radius:12px;">
                                        <img src="<?= BASE_URL ?>/uploads/providers/0/nic_back.jpg" alt="NIC Back"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;"
                                            onerror="this.style.display='none'">
                                        <div class="actions" style="margin-top:10px;">
                                            <a class="btn btn-ghost"
                                                href="<?= BASE_URL ?>/uploads/providers/0/nic_back.jpg" target="_blank"
                                                rel="noopener"><i class="fa-regular fa-eye"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field" style="flex:1 1 300px;">
                                    <label>Resume (read only)</label>
                                    <div class="card"
                                        style="padding:12px; border-radius:12px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                                        <div class="small" style="flex:1;">Current resume on file.</div>
                                        <a class="btn btn-outline" href="<?= BASE_URL ?>/uploads/providers/0/resume.pdf"
                                            target="_blank" rel="noopener"><i class="fa-regular fa-download"></i>
                                            Download</a>
                                    </div>
                                </div>
                            </div>
                            <div class="actions">
                                <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i>
                                    Save Personal</button>
                                <button class="btn btn-outline" type="button" onclick="resetPersonal()"><i
                                        class="fa-regular fa-rotate-left"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                </section>

                <section id="section-work" class="profile-section" aria-label="Work information">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-regular fa-briefcase"></i> Categories</h2>
                        <div class="field-row" style="align-items:center;">
                            <div class="field" style="flex:1 1 auto;">
                                <label for="cat_search">Search</label>
                                <input type="text" id="cat_search" placeholder="Search categories..." />
                            </div>
                            <div class="actions" style="margin-top: 18px;">
                                <button class="btn btn-primary" type="button" onclick="openCategoryModal('add')"><i
                                        class="fa-regular fa-plus"></i> Add Category</button>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div id="categoryList"></div>
                    </div>
                </section>

                <section id="section-account" class="profile-section" aria-label="Account & security">
                    <div class="card">
                        <h2 class="section-title"><i class="fa-regular fa-shield-keyhole"></i> Account & Security</h2>
                        <form id="accountForm" onsubmit="saveAccount(event)">
                            <div class="field-row">
                                <div class="field">
                                    <label for="acc_email">Email</label>
                                    <input type="email" id="acc_email" name="Email" value="john@example.com" readonly />
                                </div>
                                <div class="field">
                                    <label for="acc_contact">Contact No</label>
                                    <input type="text" id="acc_contact" name="Contact_No" value="+1 555 123 987" />
                                </div>
                                <div class="field">
                                    <label for="acc_status">Status</label>
                                    <select id="acc_status" name="Status">
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
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
                                </div>
                                <div class="small">Leave password fields empty if you don't want to change it.</div>
                                <button type="button" class="link-inline" onclick="forgotPassword()"><i
                                        class="fa-regular fa-envelope"></i> Send forgot password email</button>
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
            <p>Enter your account email below and we'll send a password reset link if it exists in our system.</p>
            <form onsubmit="sendFP(event)">
                <input type="email" name="fp_email" id="fp_email" placeholder="you@example.com" required />
                <div class="actions" style="margin-top:4px;">
                    <button type="submit" class="btn btn-primary"><i class="fa-regular fa-paper-plane"></i> Send
                        Link</button>
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

    <script>
        // Side navigation logic
        const pills = document.querySelectorAll('.profile-nav .pill');
        const sections = document.querySelectorAll('.profile-section');
        pills.forEach(p => p.addEventListener('click', () => {
            pills.forEach(x => x.setAttribute('aria-current', 'false'));
            sections.forEach(s => s.classList.remove('active'));
            p.setAttribute('aria-current', 'true');
            document.getElementById(p.dataset.target).classList.add('active');
        }));

        // Toast helper
        const toast = document.getElementById('toast');
        function showToast(msg) { document.getElementById('toastMsg').textContent = msg; toast.classList.add('show'); setTimeout(() => toast.classList.remove('show'), 3200); }

        // Personal form logic (frontend only)
        const personalForm = document.getElementById('personalForm');
        let personalOriginal = new FormData(personalForm);
        function resetPersonal() { personalForm.reset(); personalOriginal.forEach((v, k) => { if (personalForm.elements[k]) personalForm.elements[k].value = v; }); showToast('Personal reset'); }
        function savePersonal(e) { e.preventDefault(); const fd = new FormData(personalForm); fetch('#', { method: 'POST', body: fd }).then(() => { personalOriginal = fd; document.getElementById('publicSummaryName').textContent = fd.get('First_Name') + ' ' + fd.get('Last_Name'); document.getElementById('publicSummaryEmail').textContent = fd.get('Email') || '—'; document.getElementById('publicSummaryNIC').textContent = fd.get('NIC') || '—'; showToast('Personal saved'); }); }

        // Account form logic
        const accountForm = document.getElementById('accountForm');
        let accountOriginal = new FormData(accountForm);
        function resetAccount() { accountForm.reset(); accountOriginal.forEach((v, k) => { if (accountForm.elements[k]) accountForm.elements[k].value = v; }); showToast('Account reset'); }
        function saveAccount(e) { e.preventDefault(); const fd = new FormData(accountForm); if (fd.get('New_Password') !== fd.get('Confirm_Password')) { showToast('Passwords do not match'); return; } fetch('#', { method: 'POST', body: fd }).then(() => { accountOriginal = fd; document.getElementById('accountSummaryContact').textContent = fd.get('Contact_No') || '—'; document.getElementById('accountSummaryStatus').textContent = fd.get('Status'); document.getElementById('accountStatus').textContent = fd.get('Status'); showToast('Account saved'); }); }

        // Avatar preview (both)
        let pendingAvatarFile = null;
        function bindAvatar(idInput, idImg) {
            const inputEl = document.getElementById(idInput);
            if (!inputEl) return;
            inputEl.addEventListener('change', (e) => {
                const f = e.target.files[0];
                if (!f) return;
                const r = new FileReader();
                r.onload = ev => {
                    document.getElementById(idImg).src = ev.target.result;
                    if (idInput === 'avatarPublicInput') { pendingAvatarFile = f; document.getElementById('avatarSaveBtn').style.display = 'inline-flex'; }
                    showToast('Photo updated (not saved)');
                };
                r.readAsDataURL(f);
            });
        }

        function saveAvatar() {
            const input = document.getElementById('avatarPublicInput');
            const file = input.files && input.files[0] ? input.files[0] : pendingAvatarFile;
            if (!file) { showToast('No new photo selected'); return; }
            const fd = new FormData();
            fd.append('avatar', file);
            // TODO: replace '#' with backend endpoint, e.g., `${BASE_URL}/provider/profile/avatar`
            fetch('#', { method: 'POST', body: fd })
                .then(() => {
                    document.getElementById('avatarSaveBtn').style.display = 'none';
                    pendingAvatarFile = null;
                    // Optionally clear input to reset state
                    try { input.value = ''; } catch { }
                    showToast('Photo saved');
                })
                .catch(() => { showToast('Failed to save photo'); });
        }
        bindAvatar('avatarPublicInput', 'avatarPublicPreview');
        bindAvatar('avatarAccountInput', 'avatarAccountPreview');

        // --- Work Information (frontend only) ---
        const mockCategories = [
            { id: 1, name: 'Graphic Design' },
            { id: 2, name: 'Web Development' },
            { id: 3, name: 'Mobile Apps' },
            { id: 4, name: 'Content Writing' },
            { id: 5, name: 'Photography' }
        ];
        const mockLocations = [
            { id: 1, name: 'Colombo' }, { id: 2, name: 'Kandy' }, { id: 3, name: 'Galle' }, { id: 4, name: 'Jaffna' }
        ];
        const mockSkills = [
            { id: 1, name: 'Photoshop' }, { id: 2, name: 'Illustrator' }, { id: 3, name: 'React' }, { id: 4, name: 'Node.js' }, { id: 5, name: 'SEO' }
        ];

        // populate selects (modal)
        const mCatSelect = document.getElementById('m_cat_select');
        function refreshCategoryOptions() {
            if (!mCatSelect) return;
            mCatSelect.innerHTML = '';
            mockCategories.forEach(c => {
                const opt = document.createElement('option');
                opt.value = String(c.id); opt.textContent = c.name; mCatSelect.appendChild(opt);
            });
        }
        refreshCategoryOptions();

        let providerCats = [
            {
                category_id: 2,
                title: 'Website Development',
                description: 'Modern, responsive websites with basic SEO and performance best practices.',
                default_price: '150000',
                locations: [mockLocations[0], mockLocations[1]],
                skills: [mockSkills[2], mockSkills[4]]
            },
            {
                category_id: 1,
                title: 'Logo & Brand Kit',
                description: 'Professional logo, color palette, and typography starter kit.',
                default_price: '45000',
                locations: [mockLocations[0]],
                skills: [mockSkills[0], mockSkills[1]]
            },
            {
                category_id: 5,
                title: 'Event Photography',
                description: 'Candid and portrait coverage for corporate and private events.',
                default_price: '80000',
                locations: [mockLocations[2], mockLocations[3]],
                skills: [mockSkills[0]]
            },
            {
                category_id: 4,
                title: 'Blog & SEO Articles',
                description: 'Well‑researched, SEO‑friendly long‑form articles and blog posts.',
                default_price: '12000',
                locations: [mockLocations[0], mockLocations[2]],
                skills: [mockSkills[4]]
            }
        ];

        const categoryList = document.getElementById('categoryList');
        const catSearch = document.getElementById('cat_search');
        const catOverlay = document.getElementById('catOverlay');
        const catViewOverlay = document.getElementById('catViewOverlay');
        // modal fields
        const mCatTitle = document.getElementById('m_cat_title');
        const mCatDesc = document.getElementById('m_cat_desc');
        const mCatPrice = document.getElementById('m_cat_price');
        const mCatEditIndex = document.getElementById('m_cat_edit_index');
        const mCatLocations = document.getElementById('m_cat_locations');
        const mCatSkills = document.getElementById('m_cat_skills');

        function renderChips(container, items) {
            if (!container) return;
            container.innerHTML = '';
            items.forEach(it => {
                const chip = document.createElement('span');
                chip.className = 'inline-badge';
                chip.style.marginRight = '6px';
                chip.textContent = it.name;
                container.appendChild(chip);
            });
        }

        let catFilter = '';
        function renderCategoryList() {
            if (!categoryList) return;
            const filtered = providerCats.filter(pc => {
                if (!catFilter) return true;
                const cat = mockCategories.find(c => c.id === pc.category_id);
                const hay = [pc.title || '', pc.description || '', (cat ? cat.name : '')].join(' ').toLowerCase();
                return hay.includes(catFilter);
            });
            categoryList.innerHTML = filtered.length ? '' : '<div class="small">No categories found.</div>';
            filtered.forEach((pc) => {
                const card = document.createElement('div');
                card.className = 'req-card';
                const cat = mockCategories.find(c => c.id === pc.category_id);
                const title = pc.title || (cat ? cat.name : 'Category');
                const name = cat ? cat.name : 'Category';
                const locations = (pc.locations || []).map(l => l.name).join(', ') || '—';
                const skills = (pc.skills || []);
                card.innerHTML = `
                    <div class="req-head">
                        <img class="req-avatar" src="<?= BASE_URL ?>/public/assets/img/default-category.png" alt="" onerror="this.style.visibility='hidden'"/>
                        <div class="req-main">
                            <span class="req-name">${name}</span>
                            <span class="req-title">${title}</span>
                            <span class="req-time">${locations}</span>
                        </div>
                        <div class="req-actions">
                            <button class="btn-outline-blue" data-action="view"><i class="fa-regular fa-eye"></i> View</button>
                            <button class="btn-outline-blue" data-action="edit"><i class="fa-regular fa-pen"></i> Edit</button>
                            <button class="btn-outline-rose" data-action="remove"><i class="fa-regular fa-xmark"></i> Delete</button>
                        </div>
                    </div>
                    <div class="req-tags">
                        <span class="tag"><i class="fa-regular fa-tag"></i> Default: <strong>${pc.default_price || '—'}</strong></span>
                        ${skills.map(s => `<span class=\"tag\">${s.name}</span>`).join('')}
                    </div>
                    <div class="req-desc">${pc.description || '—'}</div>
                `;
                const originalIndex = providerCats.indexOf(pc);
                card.querySelector('[data-action="view"]').addEventListener('click', () => openCategoryView(pc));
                card.querySelector('[data-action="edit"]').addEventListener('click', () => openCategoryModal('edit', originalIndex));
                card.querySelector('[data-action="remove"]').addEventListener('click', () => removeCategory(originalIndex));
                categoryList.appendChild(card);
            });
        }

        // search
        if (catSearch) {
            catSearch.addEventListener('input', (e) => { catFilter = String(e.target.value || '').trim().toLowerCase(); renderCategoryList(); });
        }

        function removeCategory(idx) {
            if (!confirm('Remove this category?')) return;
            providerCats.splice(idx, 1);
            renderCategoryList();
            updateCounts();
            showToast('Category removed');
        }

        function openCategoryModal(mode = 'add', idx = -1) {
            // populate
            document.getElementById('catTitle').textContent = mode === 'edit' ? 'Edit Category' : 'Add Category';
            if (mCatSelect && mCatSelect.options.length) mCatSelect.selectedIndex = 0;
            mCatTitle.value = '';
            mCatDesc.value = '';
            mCatPrice.value = '';
            mCatLocations.innerHTML = '';
            mCatSkills.innerHTML = '';
            mCatEditIndex.value = '';
            if (mode === 'edit' && providerCats[idx]) {
                const pc = providerCats[idx];
                mCatSelect.value = String(pc.category_id);
                mCatTitle.value = pc.title || '';
                mCatDesc.value = pc.description || '';
                mCatPrice.value = pc.default_price || '';
                renderChips(mCatLocations, pc.locations || []);
                renderChips(mCatSkills, pc.skills || []);
                mCatEditIndex.value = String(idx);
            }
            catOverlay.style.display = 'flex';
        }
        function closeCategoryModal() { catOverlay.style.display = 'none'; }

        function saveCategoryModal(e) {
            e.preventDefault();
            const idx = mCatEditIndex.value !== '' ? parseInt(mCatEditIndex.value, 10) : -1;
            const category_id = parseInt((mCatSelect || { value: '0' }).value, 10) || 0;
            const title = mCatTitle.value.trim();
            const description = mCatDesc.value.trim();
            const default_price = mCatPrice.value.trim();
            const locations = Array.from((mCatLocations || { querySelectorAll: () => [] }).querySelectorAll('.inline-badge')).map(el => ({ id: 0, name: el.textContent }));
            const skills = Array.from((mCatSkills || { querySelectorAll: () => [] }).querySelectorAll('.inline-badge')).map(el => ({ id: 0, name: el.textContent }));
            const entry = { category_id, title, description, default_price, locations, skills };
            if (idx >= 0) providerCats[idx] = entry; else providerCats.push(entry);
            renderCategoryList();
            updateCounts();
            closeCategoryModal();
            showToast('Category saved');
        }

        function openCategoryView(pc) {
            const cat = mockCategories.find(c => c.id === pc.category_id);
            const body = document.getElementById('catViewBody');
            const skills = (pc.skills || []);
            const locations = (pc.locations || []);
            body.innerHTML = `
                <div class="view-meta">
                    <span class="pill"><i class="fa-regular fa-layer-group"></i> ${cat ? cat.name : '—'}</span>
                    <span class="pill price"><i class="fa-regular fa-tag"></i> Default: <strong>${pc.default_price || '—'}</strong></span>
                    <span class="pill"><i class="fa-regular fa-location-dot"></i> ${locations.length ? locations.map(l => l.name).join(', ') : '—'}</span>
                </div>
                <div class="kv">
                    <div class="row"><div class="k">Title</div><div class="v">${pc.title || '—'}</div></div>
                    <div class="row"><div class="k">Description</div><div class="v">${pc.description || '—'}</div></div>
                    <div class="row"><div class="k">Skills</div><div class="v"><div class="chips">${skills.length ? skills.map(s => `<span class=\"chip\">${s.name}</span>`).join('') : '—'}</div></div></div>
                </div>
            `;
            catViewOverlay.style.display = 'flex';
        }
        function closeCategoryView() { catViewOverlay.style.display = 'none'; }

        function pickLocations(isModal = false) {
            const names = prompt('Add locations (comma separated):', 'Colombo, Kandy');
            if (!names) return;
            const items = names.split(',').map(s => s.trim()).filter(Boolean).map(n => ({ id: 0, name: n }));
            renderChips(isModal ? mCatLocations : document.getElementById('cat_locations'), items);
        }

        function pickSkills(isModal = false) {
            const names = prompt('Add skills (comma separated):', 'Photoshop, SEO');
            if (!names) return;
            const items = names.split(',').map(s => s.trim()).filter(Boolean).map(n => ({ id: 0, name: n }));
            renderChips(isModal ? mCatSkills : document.getElementById('cat_skills'), items);
        }

        // initial render
        renderCategoryList();
        function updateCounts() { const el = document.getElementById('countCategories'); if (el) el.textContent = String(providerCats.length); }
        updateCounts();

        // Forgot password modal
        const fpOverlay = document.getElementById('fpOverlay');
        function forgotPassword() { fpOverlay.style.display = 'flex'; document.getElementById('fp_email').value = document.getElementById('acc_email').value; setTimeout(() => document.getElementById('fp_email').focus(), 50); }
        function closeFP() { fpOverlay.style.display = 'none'; }
        function sendFP(e) {
            e.preventDefault(); const email = document.getElementById('fp_email').value; // simulate
            fetch('#', { method: 'POST', body: new URLSearchParams({ email }) }).then(() => { closeFP(); showToast('Reset link sent'); });
        }

        // Delete account
        function deleteAccount() { if (confirm('Delete account permanently? This cannot be undone.')) { showToast('Deletion requested'); } }
    </script>
</body>

</html>