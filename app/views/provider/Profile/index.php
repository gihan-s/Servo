<?php
require_once __DIR__ . '/../../../../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Provider Profile</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
    <style>
        :root {
            --brand: #008500;
            --brand-2: #0a7a0a;
            --ink: #0f172a;
            --muted: #64748b;
            --card: #ffffff;
            --border: #e2e8f0;
        }

        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #111827;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 26px 90px;
        }

        .card::before {
            content: none;
        }

        /* Hero */
        .profile-hero {
            position: relative;
            border-radius: 22px;
            overflow: hidden;
            padding: 38px 28px 28px;
            margin-bottom: 24px;
            background: radial-gradient(1200px 200px at -10% -20%, #12b98133, transparent 60%),
                radial-gradient(1200px 240px at 120% -10%, #22d3ee2e, transparent 60%),
                linear-gradient(135deg, #0ea5e9 0%, #16a34a 60%, #14532d 100%);
            color: #ecfeff;
        }

        .hero-inner {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .hero-avatar-wrap {
            position: relative;
            display: inline-block;
        }

        .hero-avatar {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, .45);
        }

        .avatar-save-btn {
            position: absolute;
            bottom: -8px;
            right: -8px;
            background: #008500;
            color: #fff;
            border: 1px solid #046d04;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 10px;
            box-shadow: 0 10px 22px -10px rgba(0, 0, 0, .35);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .avatar-save-btn:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .hero-text h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .hero-text .muted {
            opacity: .9;
            font-size: 13px;
        }

        .hero-actions {
            margin-left: auto;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .hero-actions .btn {
            padding: 10px 14px;
            border-radius: 10px;
        }

        /* Shell */
        .profile-shell {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 22px;
            align-items: start;
        }

        @media (max-width: 1000px) {
            .profile-shell {
                grid-template-columns: 1fr;
            }
        }

        .profile-nav {
            position: sticky;
            top: 18px;
            align-self: start;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .pill {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid var(--border);
            background: #fff;
            padding: 12px 14px;
            border-radius: 14px;
            font-weight: 700;
            color: #0f172a;
            cursor: pointer;
            transition: all .25s ease;
        }

        .pill:hover {
            border-color: var(--brand);
            box-shadow: 0 10px 20px -10px rgba(0, 133, 0, .35);
            transform: translateY(-1px);
        }

        .pill[aria-current="true"] {
            background: linear-gradient(135deg, var(--brand), #046d04);
            color: #fff;
            border-color: var(--brand);
        }

        .pill .count {
            margin-left: auto;
            background: #f1f5f9;
            color: #0f172a;
            border-radius: 999px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 800;
        }

        .pill[aria-current="true"] .count {
            background: #064e3b;
            color: #ecfeff;
        }

        .profile-section {
            display: none;
            animation: fade .35s ease;
        }

        .profile-section.active {
            display: block;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

        .avatar-btn {
            background: #008500;
            color: #fff;
            border: 1px solid #008500;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 700;
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

        /* Chips and list */
        .chip-input {
            min-height: 44px;
            border: 1px dashed #e2e8f0;
            border-radius: 10px;
            padding: 8px;
        }

        .cat-card-actions .btn {
            padding: 8px 12px;
        }

        /* Work list uses global cardList.css .search-item style */
        #categoryList {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* Project-like request card for categories */
        .req-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px 18px 14px;
            box-shadow: 0 4px 14px -6px rgba(0, 0, 0, .08);
        }

        .req-head {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .req-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 6px 14px -8px rgba(0, 0, 0, .35);
        }

        .req-main {
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
        }

        .req-name {
            color: #0f766e;
            font-weight: 800;
            font-size: 14px;
        }

        .req-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .req-time {
            font-size: 12px;
            color: #64748b;
        }

        .req-actions {
            display: flex;
            gap: 8px;
        }

        .btn-outline-blue {
            background: #fff;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 8px 12px;
            font-weight: 700;
            font-size: 13px;
        }

        .btn-outline-blue:hover {
            border-color: #94a3b8;
        }

        .btn-outline-rose {
            background: #fff;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 8px 12px;
            font-weight: 700;
            font-size: 13px;
        }

        .btn-outline-rose:hover {
            border-color: #fca5a5;
        }

        .req-tags {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 10px 0 6px;
        }

        .req-tags .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #475569;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 6px 10px;
        }

        .req-desc {
            font-size: 14px;
            color: #111827;
            margin-top: 8px;
        }

        .req-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 12px;
        }

        .req-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 800;
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 999px;
            padding: 6px 10px;
        }

        /* View modal beautify */
        #catViewOverlay .modal {
            max-width: 560px;
        }

        .view-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 6px 0 12px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .pill.price {
            background: #ecfeff;
            border-color: #bae6fd;
            color: #0c4a6e;
        }

        .kv {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .kv .row {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 12px;
            align-items: start;
        }

        .kv .k {
            font-size: 12px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .kv .v {
            font-size: 14px;
            color: #0f172a;
        }

        .chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../navbar.php'; ?>
    <main>
        <section class="profile-hero">
            <div class="hero-inner">
                <div class="hero-avatar-wrap">
                    <img class="hero-avatar" id="avatarPublicPreview"
                        src="<?= BASE_URL ?>/uploads/providers/0/default.png" alt="Avatar" />
                    <button id="avatarSaveBtn" class="avatar-save-btn" style="display:none;" onclick="saveAvatar()"><i
                            class="fa-regular fa-floppy-disk"></i> Save</button>
                </div>
                <div class="hero-text">
                    <h1 id="publicSummaryName">John Doe</h1>
                    <div class="muted"><i class="fa-regular fa-envelope"></i> <span
                            id="publicSummaryEmail">john@example.com</span> · <i class="fa-regular fa-id-card"></i>
                        <span id="publicSummaryNIC">000000000V</span> · <span class="status-badge"
                            id="publicStatus">Active</span></div>
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
                            <div class="field-row">
                                <div class="field">
                                    <label for="per_first">First Name</label>
                                    <input type="text" id="per_first" name="First_Name" value="John" required />
                                </div>
                                <div class="field">
                                    <label for="per_last">Last Name</label>
                                    <input type="text" id="per_last" name="Last_Name" value="Doe" required />
                                </div>
                                <div class="field">
                                    <label for="per_gender">Gender</label>
                                    <select id="per_gender" name="Gender">
                                        <option value="Male" selected>Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="field">

                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field">
                                    <label for="per_email">Email</label>
                                    <input type="email" id="per_email" name="Email" value="john@example.com" readonly />
                                </div>
                                <div class="field">
                                    <label for="per_nic">NIC</label>
                                    <input type="text" id="per_nic" name="NIC" value="000000000V" readonly />
                                </div>
                                <div class="field">
                                    <label for="per_contact">Contact No</label>
                                    <input type="text" id="per_contact" name="Contact_No" value="+1 555 123 987" />
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field" style="flex:1 1 100%;">
                                    <label for="per_social">Social Link</label>
                                    <input type="url" id="per_social" name="Social_Link"
                                        value="https://linkedin.com/in/john-doe" placeholder="https://" />
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field" style="flex:1 1 100%;">
                                    <label for="per_bio">Bio</label>
                                    <textarea id="per_bio" name="Bio"
                                        placeholder="Share a concise overview about your expertise and experience.">Tech founder focused on marketplace and payment integration excellence.</textarea>
                                </div>
                            </div>
                            <div class="divider"></div>
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