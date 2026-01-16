<?php
$TopBarHeader = "Providers";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Providers</title>

    <?php include 'includes/links.php' ?>

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

            <img src="<?= BASE_URL ?>/assets/img/admin-icon/customers.jpg" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Pending Providers</h3>
                <h1>12</h1>
                <span>Pending for Approval</span>
            </div>

            <img src="<?= BASE_URL ?>/assets/img/admin-icon/softwares.jpg" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Active Providers</h3>
                <h1>90</h1>
                <span>Currently Providing Service</span>
            </div>

            <img src="<?= BASE_URL ?>/assets/img/admin-icon/websites.jpg" alt="">

        </div>


        <div class="container top-card">

            <div>
                <h3>Banned Providers</h3>
                <h1>13</h1>
                <span>Banned by System</span>
            </div>

            <img src="<?= BASE_URL ?>/assets/img/admin-icon/pendingImp.webp" alt="">

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
            <button class="page-btn prev" onclick="previosPagination(this)"><i class="fa-regular fa-chevron-left"></i></button>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <button onclick="window.location=`?page=<?= $i ?>`" class="page-btn <?= $i == $page ? 'active' : '' ?>"><?= $i ?></button>
            <?php endfor; ?>

            <button class="page-btn next" onclick="nextPagination(this)"><i class="fa-regular fa-chevron-right"></i></button>
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

                
            });
    }
</script>