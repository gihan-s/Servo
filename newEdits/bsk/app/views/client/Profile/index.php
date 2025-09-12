<?php
require_once __DIR__ . '/../../../../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile (Tabbed)</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
    <style>
        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #111827;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 26px 90px;}
        .card::before { content:none; }

        .top-header {
            padding: 1rem;
            padding-top: 0;
            margin-bottom: 1rem;
            box-shadow: none;
            position: relative;
            z-index: -1;
        }
        
        h1 {
            margin: 0 0 6px;
            font-size: 30px;
            font-weight: 800;
        }

        .subtitle {
            color: #475569;
        }

        .tabs-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .tab-btn {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 14px;
            cursor: pointer;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            color: #374151;
            transition: all .25s ease;
            position: relative;
        }

        .tab-btn:hover {
            border-color: #008500;
            color: #008500;
        }

        .tab-btn[aria-selected="true"] {
            background: linear-gradient(135deg, #008500, #006600);
            color: #fff;
            border-color: #008500;
            box-shadow: 0 6px 18px -4px rgba(0, 133, 0, .45);
        }

        .tab-panel {
            display: none;
            animation: fade .45s ease;
        }

        .tab-panel.active {
            display: block;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 32px;
            align-items: start;
        }

        @media (max-width:1000px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .05), 0 2px 4px -1px rgba(0, 0, 0, .04);
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #008500, #3b82f6);
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: #008500;
            box-shadow: 0 18px 28px -8px rgba(0, 0, 0, .12), 0 10px 14px -8px rgba(0, 0, 0, .08);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 22px;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .avatar-wrap {
            text-align: center;
        }

        .avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 0 0 4px #00850033, 0 6px 16px -6px rgba(0, 0, 0, .25);
        }

        .avatar-btn {
            margin-top: 14px;
            background: #008500;
            color: #fff;
            border: 1px solid #008500;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            gap: 6px;
            align-items: center;
            transition: all .3s ease;
        }

        .avatar-btn:hover {
            box-shadow: 0 8px 22px -6px rgba(0, 133, 0, .5);
            transform: translateY(-2px);
        }

        .status-badge {
            margin-top: 18px;
            display: inline-block;
            background: #dcfce7;
            color: #008500;
            border: 1px solid #bbf7d0;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0, #fff);
            margin: 18px 0;
        }

        .summary-pairs {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 14px;
        }

        .inline-badge {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            padding: 4px 10px;
            font-weight: 600;
            border-radius: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        .field-row {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }

        .field {
            flex: 1 1 240px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .5px;
            color: #475569;
            text-transform: uppercase;
        }

        input[type=text],
        input[type=email],
        input[type=url],
        select,
        textarea,
        input[type=password] {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 46px;
            transition: border-color .25s ease, box-shadow .25s ease;
        }

        textarea {
            min-height: 120px;
            line-height: 1.5;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #008500;
            box-shadow: 0 0 0 3px #00850033;
        }

        .actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            padding: 12px 20px;
            border-radius: 12px;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            transition: all .3s ease;
            letter-spacing: .4px;
        }

        .btn-primary {
            background: #008500;
            color: #fff;
            box-shadow: 0 4px 12px -4px rgba(0, 133, 0, .55);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 26px -8px rgba(0, 133, 0, .55);
        }

        .btn-outline {
            background: #fff;
            border: 2px solid #008500;
            color: #008500;
        }

        .btn-outline:hover {
            background: #008500;
            color: #fff;
        }

        .btn-ghost {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #374151;
        }

        .btn-ghost:hover {
            border-color: #008500;
            color: #008500;
        }

        .danger-zone {
            border: 1px solid #fecaca;
            background: #fff5f5;
            padding: 22px 24px 26px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .danger-zone h4 {
            margin: 0;
            font-size: 15px;
            color: #b91c1c;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .danger-zone p {
            margin: 0;
            font-size: 13px;
            color: #881337;
            line-height: 1.5;
        }

        .danger-btn {
            background: #fff;
            color: #b91c1c;
            border: 2px solid #b91c1c;
            padding: 10px 16px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            gap: 6px;
            align-items: center;
            transition: all .3s ease;
        }

        .danger-btn:hover {
            background: #b91c1c;
            color: #fff;
        }

        .password-box {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 16px 18px 20px;
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .password-box h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            gap: 8px;
            align-items: center;
            color: #0f172a;
        }

        .small {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
        }

        .link-inline {
            background: none;
            border: none;
            color: #008500;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            text-decoration: underline;
        }

        .toast {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: #111827;
            color: #fff;
            padding: 14px 20px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 8px 22px -6px rgba(0, 0, 0, .4);
            display: flex;
            gap: 10px;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: all .4s ease;
            z-index: 60;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal {
            background: #fff;
            width: 100%;
            max-width: 460px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 30px 30px 34px;
            position: relative;
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, .25);
        }

        .modal h3 {
            margin: 0 0 12px;
            font-size: 20px;
            font-weight: 700;
        }

        .modal p {
            margin: 0 0 18px;
            font-size: 14px;
            line-height: 1.55;
            color: #475569;
        }

        .close-btn {
            position: absolute;
            top: 14px;
            right: 14px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            cursor: pointer;
            color: #475569;
        }

        .close-btn:hover {
            border-color: #008500;
            color: #008500;
        }

        .modal form {
            gap: 18px;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../navbar.php'; ?>
    <main>
        <header class="top-header">
            <h1>My Profile</h1>
            <div class="subtitle">Public info vs account & security settings.</div>
        </header>

        <div class="tabs-bar" role="tablist">
            <button class="tab-btn" role="tab" id="tab-public" aria-selected="true" aria-controls="panel-public"><i
                    class="fa-regular fa-id-card"></i> Public Profile</button>
            <button class="tab-btn" role="tab" id="tab-account" aria-selected="false" aria-controls="panel-account"><i
                    class="fa-regular fa-shield-check"></i> Account & Security</button>
        </div>

        <!-- PUBLIC PROFILE TAB -->
        <section class="tab-panel active" role="tabpanel" id="panel-public" aria-labelledby="tab-public">
            <div class="layout">
                <aside class="card" aria-label="Profile summary">
                    <div class="avatar-wrap">
                        <img src="<?= BASE_URL ?>/uploads/clients/0/default.png" alt="Profile Picture" class="avatar"
                            id="avatarPublicPreview">
                        <button class="avatar-btn" onclick="document.getElementById('avatarPublicInput').click()"><i
                                class="fa-regular fa-camera"></i> Change Photo</button>
                        <input type="file" id="avatarPublicInput" accept="image/*" style="display:none" />
                        <div class="status-badge" id="publicStatus">Active</div>
                    </div>
                    <div class="divider"></div>
                    <div class="summary-pairs">
                        <div><strong>Name:</strong> <span id="publicSummaryName">John Doe</span></div>
                        <div><strong>Social:</strong> <span id="publicSummarySocial">linkedin.com/in/john-doe</span>
                        </div>
                        <div><strong>Gender:</strong> <span id="publicSummaryGender">Male</span></div>
                        <div style="margin-top:6px;"><span class="inline-badge">Public Profile</span></div>
                    </div>
                </aside>
                <section class="card" aria-label="Public profile form">
                    <h2 class="section-title"><i class="fa-regular fa-user"></i> Public Information</h2>
                    <form id="publicForm" onsubmit="savePublic(event)">
                        <div class="field-row">
                            <div class="field">
                                <label for="pub_first">First Name</label>
                                <input type="text" id="pub_first" name="First_Name" value="John" required />
                            </div>
                            <div class="field">
                                <label for="pub_last">Last Name</label>
                                <input type="text" id="pub_last" name="Last_Name" value="Doe" required />
                            </div>
                            <div class="field">
                                <label for="pub_gender">Gender</label>
                                <select id="pub_gender" name="Gender">
                                    <option value="Male" selected>Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="field">
                                <label for="pub_social">Social Link</label>
                                <input type="url" id="pub_social" name="Social_Link"
                                    value="https://linkedin.com/in/john-doe" placeholder="https://" />
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field" style="flex:1 1 100%;">
                                <label for="pub_bio">Bio</label>
                                <textarea id="pub_bio" name="Bio"
                                    placeholder="Share a concise overview">Tech founder focused on marketplace and payment integration excellence.</textarea>
                            </div>
                        </div>
                        <div class="actions">
                            <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i> Save
                                Public</button>
                            <button class="btn btn-outline" type="button" onclick="resetPublic()"><i
                                    class="fa-regular fa-rotate-left"></i> Reset</button>
                        </div>
                    </form>
                </section>
            </div>
        </section>

        <!-- ACCOUNT & SECURITY TAB -->
        <section class="tab-panel" role="tabpanel" id="panel-account" aria-labelledby="tab-account">
            <div class="layout">
                <aside class="card" aria-label="Account summary">
                    <div class="avatar-wrap">
                        <img src="<?= BASE_URL ?>/uploads/clients/0/default.png" alt="Profile Picture" class="avatar"
                            id="avatarAccountPreview">
                        <button class="avatar-btn" onclick="document.getElementById('avatarAccountInput').click()"><i
                                class="fa-regular fa-camera"></i> Change Photo</button>
                        <input type="file" id="avatarAccountInput" accept="image/*" style="display:none" />
                        <div class="status-badge" id="accountStatus">Active</div>
                    </div>
                    <div class="divider"></div>
                    <div class="summary-pairs">
                        <div><strong>Email:</strong> <span id="accountSummaryEmail">john@example.com</span></div>
                        <div><strong>Contact:</strong> <span id="accountSummaryContact">+1 555 123 987</span></div>
                        <div><strong>Joined:</strong> <span id="accountSummaryCreated">2025-08-12</span></div>
                        <div><strong>Status:</strong> <span id="accountSummaryStatus">Active</span></div>
                        <div style="margin-top:6px;"><span class="inline-badge">Account</span></div>
                    </div>
                    <div class="divider"></div>
                    <div class="danger-zone">
                        <h4><i class="fa-regular fa-triangle-exclamation"></i> Danger Zone</h4>
                        <p>Deleting your account removes all associated data. This cannot be undone.</p>
                        <button class="danger-btn" onclick="deleteAccount()"><i class="fa-regular fa-trash"></i> Delete
                            Account</button>
                    </div>
                </aside>
                <section class="card" aria-label="Account & security form">
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
                            <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i> Save
                                Account</button>
                            <button class="btn btn-outline" type="button" onclick="resetAccount()"><i
                                    class="fa-regular fa-rotate-left"></i> Reset</button>
                        </div>
                    </form>
                </section>
            </div>
        </section>
    </main>
    <?php include_once __DIR__ . '/../footer.php'; ?>
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

    <script>
        // Tab logic
        const tabs = document.querySelectorAll('.tab-btn');
        const panels = document.querySelectorAll('.tab-panel');
        tabs.forEach(t => t.addEventListener('click', () => {
            tabs.forEach(b => b.setAttribute('aria-selected', 'false'));
            panels.forEach(p => p.classList.remove('active'));
            t.setAttribute('aria-selected', 'true');
            document.getElementById(t.getAttribute('aria-controls')).classList.add('active');
        }));

        // Toast helper
        const toast = document.getElementById('toast');
        function showToast(msg) { document.getElementById('toastMsg').textContent = msg; toast.classList.add('show'); setTimeout(() => toast.classList.remove('show'), 3200); }

        // Public form logic
        const publicForm = document.getElementById('publicForm');
        let publicOriginal = new FormData(publicForm);
        function resetPublic() { publicForm.reset(); publicOriginal.forEach((v, k) => { if (publicForm.elements[k]) publicForm.elements[k].value = v; }); showToast('Public reset'); }
        function savePublic(e) { e.preventDefault(); const fd = new FormData(publicForm); fetch('#', { method: 'POST', body: fd }).then(() => { publicOriginal = fd; document.getElementById('publicSummaryName').textContent = fd.get('First_Name') + ' ' + fd.get('Last_Name'); document.getElementById('publicSummarySocial').textContent = fd.get('Social_Link') || '—'; document.getElementById('publicSummaryGender').textContent = fd.get('Gender') || '—'; showToast('Public saved'); }); }

        // Account form logic
        const accountForm = document.getElementById('accountForm');
        let accountOriginal = new FormData(accountForm);
        function resetAccount() { accountForm.reset(); accountOriginal.forEach((v, k) => { if (accountForm.elements[k]) accountForm.elements[k].value = v; }); showToast('Account reset'); }
        function saveAccount(e) { e.preventDefault(); const fd = new FormData(accountForm); if (fd.get('New_Password') !== fd.get('Confirm_Password')) { showToast('Passwords do not match'); return; } fetch('#', { method: 'POST', body: fd }).then(() => { accountOriginal = fd; document.getElementById('accountSummaryContact').textContent = fd.get('Contact_No') || '—'; document.getElementById('accountSummaryStatus').textContent = fd.get('Status'); document.getElementById('accountStatus').textContent = fd.get('Status'); showToast('Account saved'); }); }

        // Avatar preview (both)
        function bindAvatar(idInput, idImg) { document.getElementById(idInput).addEventListener('change', (e) => { const f = e.target.files[0]; if (!f) return; const r = new FileReader(); r.onload = ev => { document.getElementById(idImg).src = ev.target.result; showToast('Photo updated (not saved)'); }; r.readAsDataURL(f); }); }
        bindAvatar('avatarPublicInput', 'avatarPublicPreview');
        bindAvatar('avatarAccountInput', 'avatarAccountPreview');

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