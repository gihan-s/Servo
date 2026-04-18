<?php
$TopBarHeader = "Providers";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Providers</title>

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="/assets/js/admin-script.js" defer></script>

</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>


    <div class="card-wrapper" id="TopCardsArea">

        <div class="container top-card">

            <div>
                <h3>Total Providers</h3>
                <h1>115</h1>
                <span>Total Providers in System</span>
            </div>

            <img src="/assets/img/admin-icon/customers.jpg" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Pending Providers</h3>
                <h1>12</h1>
                <span>Pending for Approval</span>
            </div>

            <img src="/assets/img/admin-icon/softwares.jpg" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Active Providers</h3>
                <h1>90</h1>
                <span>Currently Providing Service</span>
            </div>

            <img src="/assets/img/admin-icon/websites.jpg" alt="">

        </div>


        <div class="container top-card">

            <div>
                <h3>Banned Providers</h3>
                <h1>13</h1>
                <span>Banned by System</span>
            </div>

            <img src="/assets/img/admin-icon/pendingImp.webp" alt="">

        </div>

    </div>


    <div class="container">

        <div class="table-scroll" style="overflow: unset;">

            <table>
                <thead>
                    <tr>
                        <th>Provider ID</th>
                        <th>Provider Name</th>
                        <th>Contact No</th>
                        <th>Email</th>
                        <th>NIC No</th>
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
                            case 'pending':
                                $chipType = 'chip-yellow';
                                break;
                            case 'rejected':
                                $chipType = 'chip-orange';
                                break;
                            case 'banned':
                                $chipType = 'chip-red';
                                break;
                            default:
                                $chipType = 'chip-purple';
                        }
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($user['Provider_ID']) ?></td>
                            <td><?= htmlspecialchars($user['First_Name'] . " " . $user['Last_Name']) ?></td>
                            <td><?= htmlspecialchars($user['Contact_No']) ?></td>
                            <td><?= htmlspecialchars($user['Email']) ?></td>
                            <td><?= htmlspecialchars($user['NIC_No']) ?></td>
                            <td><span class="<?= $chipType ?>"><?= htmlspecialchars($user['Status']) ?></span></td>

                            <td>
                                <div class='option-menu'>

                                    <i class='fa-solid fa-ellipsis-vertical option-menu-button'></i>

                                    <div class='option-menu-content'>
                                        <div class='option-menu-item' onclick="viewProvider('<?= htmlspecialchars($user['Provider_ID']) ?>')"><i class='fa-solid fa-eye'></i> View</div>
                                        <div class='option-menu-item'><i class='fa-solid fa-pen-to-square'></i> Edit</div>
                                        <div class='option-menu-item red'><i class="fa-solid fa-ban"></i>Ban</div>
                                        <div class='option-menu-item red'><i class='fa-solid fa-trash'></i>Delete</div>
                                    </div>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        </div>


        <div class="pagination" aria-label="Approved Requests Pagination">
            <button class="page-btn prev" onclick="previosPagination(this)"><i class="fa-solid fa-chevron-left"></i></button>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <button onclick="window.location=`?page=<?= $i ?>`" class="page-btn <?= $i == $page ? 'active' : '' ?>"><?= $i ?></button>
            <?php endfor; ?>

            <button class="page-btn next" onclick="nextPagination(this)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>


    </div>


    <div class="dialog-box-2" id="ViewProviderDialog">
        <div class="dialog-content" style="width: 900px;">
            <div class="dialog-title">
                <div class="title">View Provider</div>

                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2"
                        onclick="closeDialogBox('ViewProviderDialog')"></i>
                </div>
            </div>

            <div id="ViewProviderContent">



            </div>

        </div>

    </div>


</body>

</html>

<script>
    const Sidemenu_Active_ID = 'SM_Providers';


    function viewProvider(Provider_ID) {
        viewDialogBox('ViewProviderDialog');
        const modalBody = document.getElementById("ViewProviderContent");
        showLoadingOn("ViewProviderContent");


        fetch(`./providers/view/${Provider_ID}`)
            .then(res => res.text())
            .then(data => {
                modalBody.innerHTML = data;
                document.getElementsByName("provider_id")[1].value = Provider_ID;
            });
    }
</script>


<div class="dialog-box-2" id="RejectProviderDialog">
    <div class="dialog-content" style="width: 600px; overflow: unset;">
        <div class="dialog-title">
            <div class="title">Reject Service Provider</div>
            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2"
                    onclick="closeDialogBox('RejectProviderDialog')"></i>
            </div>
        </div>

        <form action="./Providers/provider-review" method="post">

            <div class="input-grid-1">

                <div class="search-select-container add-option">

                    <div class="text-container">
                        <div class="label search-dropdown-label">Reason for Rejection</div>
                        <input type="text" class="text-field-search-dropdown" name="reason_for_rejection" autocomplete="off" onkeydown="return false" required>
                    </div>

                    <div class="options">

                        <span class="text-container">
                            <input type="text" class="text-field-search">
                        </span>

                        <div class="option-list">
                            <div> The information provided is incomplete or incorrect </div>
                            <div> Submitted documents are unclear or unreadable </div>
                            <div> The profile picture does not meet our requirements </div>
                            <div> The registration appears suspicious or automated </div>
                            <div> The selected service category is invalid </div>
                        </div>

                    </div>

                </div>

            </div>

            <input type="text" name="provider_id">

            <div style="display: flex; justify-content: end;">
                <button name="reject" class="button" style="background-color: #dc2626;" type="submit">
                    <i class="fa-solid fa-circle-xmark" style="margin-right: 10px;"></i>
                    Reject Provider
                </button>
            </div>

        </form>


    </div>

</div>