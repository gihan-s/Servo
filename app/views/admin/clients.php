<?php
$TopBarHeader = "Clients";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- css -->
    <link rel="stylesheet" href="/assets/css/elementStyles.css">
    <link rel="stylesheet" href="/assets/css/gridTemplates.css">

    <link rel="stylesheet" href="/assets/css/admin-main.css">
    <link rel="stylesheet" href="/assets/css/admin-sidebar.css">

    <!-- Javascript -->
    <script src="/assets/js/elementScript.js" defer></script>

    <script src="/assets/js/admin-script.js" defer></script>

</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>


    <div class="card-wrapper" id="TopCardsArea">

        <div class="container top-card">
            <div>
                <h3>Total Clients</h3>
                <h1><?= number_format($totalClients) ?></h1>
                <span>Total Clients in System</span>
            </div>
            <img src="/assets/img/admin-icon/customers.jpg" alt="">
        </div>

        <div class="container top-card">
            <div>
                <h3>Active Clients</h3>
                <h1><?= number_format($activeClients) ?></h1>
                <span>Currently Using the Platform</span>
            </div>
            <img src="/assets/img/admin-icon/websites.jpg" alt="">
        </div>

        <div class="container top-card">
            <div>
                <h3>Banned Clients</h3>
                <h1><?= number_format($bannedClients) ?></h1>
                <span>Banned by System</span>
            </div>
            <img src="/assets/img/admin-icon/pendingImp.webp" alt="">
        </div>

        <div class="container top-card">
            <div>
                <h3>New Clients This Month</h3>
                <h1><?= number_format($newClientsThisMonth) ?></h1>
                <span>Joined in <?= date('F Y') ?></span>
            </div>
            <img src="/assets/img/admin-icon/customers.jpg" alt="">
        </div>

    </div>


    <div class="container">

        <div class="table-scroll" style="overflow: unset;">

            <table>
                <thead>
                    <tr>
                        <th>Client ID</th>
                        <th>Client Name</th>
                        <th>Contact No</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($users as $user): ?>

                        <?php
                        $chipType = '';
                        switch (strtolower($user['Status'])) {
                            case 'active':
                                $chipType = 'chip-green';
                                break;
                            case 'banned':
                                $chipType = 'chip-red';
                                break;
                            default:
                                $chipType = 'chip-purple';
                        }
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($user['Client_ID']) ?></td>
                            <td><?= htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name']) ?></td>
                            <td><?= htmlspecialchars($user['Contact_No'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($user['Email']) ?></td>
                            <td><?= htmlspecialchars(date('M d, Y', strtotime($user['Created_At']))) ?></td>
                            <td><span class="<?= $chipType ?>"><?= htmlspecialchars($user['Status']) ?></span></td>

                            <td>
                                <div class='option-menu'>

                                    <i class='fa-solid fa-ellipsis-vertical option-menu-button'></i>

                                    <div class='option-menu-content'>
                                        <div class='option-menu-item' onclick="openClientViewDialog(<?= (int)$user['Client_ID'] ?>)"><i class='fa-solid fa-eye'></i> View</div>
                                        <?php if (strtolower($user['Status']) !== 'banned'): ?>
                                        <div class='option-menu-item red' onclick="openBanDialog(<?= (int)$user['Client_ID'] ?>, '<?= htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name'], ENT_QUOTES) ?>')"><i class="fa-solid fa-ban"></i> Ban</div>
                                        <?php else: ?>
                                        <div class='option-menu-item' onclick="openUnbanDialog(<?= (int)$user['Client_ID'] ?>, '<?= htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name'], ENT_QUOTES) ?>')"><i class="fa-solid fa-circle-check"></i> Unban</div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        </div>


        <div class="pagination" aria-label="Clients Pagination">
            <button class="page-btn prev" onclick="previosPagination(this)"><i class="fa-solid fa-chevron-left"></i></button>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <button onclick="window.location=`?page=<?= $i ?>`" class="page-btn <?= $i == $page ? 'active' : '' ?>"><?= $i ?></button>
            <?php endfor; ?>

            <button class="page-btn next" onclick="nextPagination(this)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>


    </div>


    <!-- Ban Client Dialog -->
    <div class="dialog-box-2" id="BanClientDialog">
        <div class="dialog-content" style="width: 500px;">
            <div class="dialog-title">
                <div class="title">Ban Client</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2"
                        onclick="closeDialogBox('BanClientDialog')"></i>
                </div>
            </div>
            <div>
                <p id="BanClientName" style="font-weight:600; margin-bottom:16px;"></p>

                <form method="POST" action="/admin/clients/ban">
                    <input type="hidden" name="client_id" id="ban_client_id" class="notreset">

                    <div class="form-group" style="margin-bottom:16px;">
                        <label for="ban_reason" style="display:block; margin-bottom:6px; font-weight:500;">Reason for Ban <span style="color:red;">*</span></label>
                        <textarea name="ban_reason" id="ban_reason" rows="4"
                            placeholder="Enter the reason for banning this client..."
                            style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; resize:vertical; box-sizing:border-box;"
                            required></textarea>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:10px;">
                        <button type="submit" class="button button-red" style="background:#c0392b; color:#fff;">
                            <i class="fa-solid fa-ban" style="margin-right:8px;"></i>Confirm Ban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Unban Client Dialog -->
    <div class="dialog-box-2" id="UnbanClientDialog">
        <div class="dialog-content" style="width: 460px;">
            <div class="dialog-title">
                <div class="title">Unban Client</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2"
                        onclick="closeDialogBox('UnbanClientDialog')"></i>
                </div>
            </div>
            <div>
                <p id="UnbanClientName" style="font-weight:600; margin-bottom:12px;"></p>
                <p style="color:#555; font-size:14px; margin-bottom:20px;">Are you sure you want to reinstate this client? Their account will be set back to Active and they will be notified by email.</p>

                <form method="POST" action="/admin/clients/unban">
                    <input type="hidden" name="client_id" id="unban_client_id" class="notreset">
                    <div style="display:flex; justify-content:flex-end; gap:10px;">
                        <button type="submit" class="button" style="background:#27ae60; color:#fff;">
                            <i class="fa-solid fa-circle-check" style="margin-right:8px;"></i>Confirm Unban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function openBanDialog(clientId, clientName) {
            viewDialogBox('BanClientDialog');
            document.getElementById('ban_client_id').value = clientId;
            document.getElementById('BanClientName').textContent = 'Client: ' + clientName;
        }

        function openUnbanDialog(clientId, clientName) {
            viewDialogBox('UnbanClientDialog');
            document.getElementById('unban_client_id').value = clientId;
            document.getElementById('UnbanClientName').textContent = 'Client: ' + clientName;
        }

        function openClientViewDialog(clientId) {
            const body   = document.getElementById('ClientViewBody');
            const dialog = document.getElementById('ClientViewDialog');

            body.innerHTML = '<div style="text-align:center;padding:40px;"><i class="fa-solid fa-spinner fa-spin" style="font-size:28px;color:#888;"></i></div>';
            viewDialogBox('ClientViewDialog');

            fetch('/admin/clients/api/' + clientId)
                .then(function(res) {
                    if (!res.ok) throw new Error('Failed to load client data');
                    return res.json();
                })
                .then(function(data) {
                    body.innerHTML = buildClientViewHTML(data);
                })
                .catch(function(err) {
                    body.innerHTML = '<p style="color:red;padding:20px;">Error loading client data.</p>';
                });
        }

        function esc(val) {
            if (val === null || val === undefined) return '—';
            return String(val)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function statusChip(status) {
            if (!status) return '—';
            const s = status.toLowerCase();
            const cls = s === 'active' ? 'chip-green' : s === 'banned' ? 'chip-red' : 'chip-purple';
            return '<span class="' + cls + '">' + esc(status) + '</span>';
        }

        function postStatusChip(status) {
            if (!status) return '—';
            const s = status.toLowerCase();
            const cls = s === 'active' ? 'chip-green' : s === 'closed' ? 'chip-red' : 'chip-purple';
            return '<span class="' + cls + '">' + esc(status) + '</span>';
        }

        function fmtDate(dt) {
            if (!dt) return '—';
            const d = new Date(dt);
            return d.toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
        }

        function fmtDateTime(dt) {
            if (!dt) return '—';
            const d = new Date(dt);
            return d.toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' })
                 + ' ' + d.toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
        }

        function fmtMoney(val) {
            const n = parseFloat(val) || 0;
            return 'Rs. ' + n.toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 });
        }

        function buildClientViewHTML(data) {
            const u = data.user;
            const s = data.stats;
            const posts = data.posts || [];

            const socialLink = u.Social_Link
                ? '<a href="' + esc(u.Social_Link) + '" target="_blank" rel="noopener noreferrer">' + esc(u.Social_Link) + '</a>'
                : '—';

            const profilePic = u.Profile_Picture
                ? '<img src="/file/user-files/' + encodeURIComponent(u.Profile_Picture) + '" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:2px solid #e0e0e0;" alt="Profile">'
                : '<div style="width:90px;height:90px;border-radius:50%;background:#eee;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="font-size:36px;color:#bbb;"></i></div>';

            let postsRows = '';
            if (posts.length === 0) {
                postsRows = '<tr><td colspan="6" style="text-align:center;color:#999;padding:16px;">No posts found.</td></tr>';
            } else {
                posts.forEach(function(p) {
                    postsRows += '<tr>'
                        + '<td>' + esc(p.Post_ID) + '</td>'
                        + '<td>' + esc(p.Title) + '</td>'
                        + '<td>' + esc(p.Category_Name) + '</td>'
                        + '<td>' + fmtMoney(p.Requesting_Price) + ' / ' + esc(p.Price_Type) + '</td>'
                        + '<td>' + postStatusChip(p.Post_Status) + '</td>'
                        + '<td>' + fmtDate(p.Created_At) + '</td>'
                        + '</tr>';
                });
            }

            return `
            <div style="display:flex;gap:16px;align-items:center;margin-bottom:24px;">
                <div>${profilePic}</div>
                <div>
                    <div style="font-size:20px;font-weight:700;">${esc(u.First_Name)} ${esc(u.Last_Name)}</div>
                    <div style="color:#777;font-size:13px;margin-top:4px;">${esc(u.Email)}</div>
                    <div style="margin-top:6px;">${statusChip(u.Status)}</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">
                <div style="background:#f5f5f5;border-radius:8px;padding:14px 18px;">
                    <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:0.5px;">Total Posts</div>
                    <div style="font-size:22px;font-weight:700;margin-top:4px;">${parseInt(s.total_posts)||0}</div>
                </div>
                <div style="background:#f5f5f5;border-radius:8px;padding:14px 18px;">
                    <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:0.5px;">Total Projects</div>
                    <div style="font-size:22px;font-weight:700;margin-top:4px;">${parseInt(s.total_projects)||0}</div>
                </div>
                <div style="background:#f5f5f5;border-radius:8px;padding:14px 18px;">
                    <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:0.5px;">Total Spent</div>
                    <div style="font-size:22px;font-weight:700;margin-top:4px;">${fmtMoney(s.total_spent)}</div>
                </div>
            </div>

            <table style="width:100%;border-collapse:collapse;margin-bottom:24px;font-size:13px;">
                <tbody>
                    <tr><td style="padding:7px 0;color:#888;width:130px;">Client ID</td><td style="font-weight:500;">${esc(u.Client_ID)}</td></tr>
                    <tr><td style="padding:7px 0;color:#888;">Gender</td><td style="font-weight:500;">${esc(u.Gender)||'—'}</td></tr>
                    <tr><td style="padding:7px 0;color:#888;">Contact No</td><td style="font-weight:500;">${esc(u.Contact_No)||'—'}</td></tr>
                    <tr><td style="padding:7px 0;color:#888;">Social Link</td><td style="font-weight:500;">${socialLink}</td></tr>
                    <tr><td style="padding:7px 0;color:#888;">Bio</td><td style="font-weight:500;">${esc(u.Bio)||'—'}</td></tr>
                    <tr><td style="padding:7px 0;color:#888;">Online</td><td>${u.Is_Online ? '<span class="chip-green">Online</span>' : '<span style="color:#999;">Offline</span>'}</td></tr>
                    <tr><td style="padding:7px 0;color:#888;">Last Seen</td><td style="font-weight:500;">${fmtDateTime(u.Last_Seen)}</td></tr>
                    <tr><td style="padding:7px 0;color:#888;">Registered At</td><td style="font-weight:500;">${fmtDateTime(u.Created_At)}</td></tr>
                </tbody>
            </table>

            <div style="font-size:14px;font-weight:700;margin-bottom:10px;">Recent Posts</div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:12px;">
                    <thead>
                        <tr style="background:#f5f5f5;">
                            <th style="padding:8px 10px;text-align:left;">ID</th>
                            <th style="padding:8px 10px;text-align:left;">Title</th>
                            <th style="padding:8px 10px;text-align:left;">Category</th>
                            <th style="padding:8px 10px;text-align:left;">Price</th>
                            <th style="padding:8px 10px;text-align:left;">Status</th>
                            <th style="padding:8px 10px;text-align:left;">Created</th>
                        </tr>
                    </thead>
                    <tbody>${postsRows}</tbody>
                </table>
            </div>`;
        }
    </script>

    <!-- Client View Dialog -->
    <div class="dialog-box-2" id="ClientViewDialog">
        <div class="dialog-content" style="width:780px; max-width:95vw; max-height:85vh; display:flex; flex-direction:column;">
            <div class="dialog-title">
                <div class="title">Client Details</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2"
                        onclick="closeDialogBox('ClientViewDialog')"></i>
                </div>
            </div>
            <div id="ClientViewBody" style="overflow-y:auto; padding:4px 2px 8px;">
            </div>
        </div>
    </div>


</body>

</html>

<script>
    const Sidemenu_Active_ID = 'SM_Clients';
</script>
