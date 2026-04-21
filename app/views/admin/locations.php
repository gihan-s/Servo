<?php
$TopBarHeader = "Locations";

// Build unique district list from existing locations + districts table for the dropdown
$districtNames = array_unique(array_column($districts, 'District'));
sort($districtNames);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/elementStyles.css">
    <link rel="stylesheet" href="/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="/assets/css/admin-main.css">
    <link rel="stylesheet" href="/assets/css/admin-sidebar.css">

    <script src="/assets/js/elementScript.js" defer></script>
    <script src="/assets/js/admin-script.js" defer></script>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
        <button class="button" onclick="openAddLocationDialog()" style="background:#008500;">
            <i class="fa-solid fa-plus" style="margin-right:8px;"></i>Add Location
        </button>
    </div>

    <div class="container">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>District</th>
                        <th>City</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $loc): ?>
                        <tr>
                            <td><?= htmlspecialchars($loc['Location_ID']) ?></td>
                            <td><?= htmlspecialchars($loc['District']) ?></td>
                            <td><?= htmlspecialchars($loc['City']) ?></td>
                            <td>
                                <div class="option-menu">
                                    <i class="fa-solid fa-ellipsis-vertical option-menu-button"></i>
                                    <div class="option-menu-content">
                                        <div class="option-menu-item"
                                             onclick="openEditLocationDialog(<?= (int)$loc['Location_ID'] ?>, '<?= htmlspecialchars($loc['District'], ENT_QUOTES) ?>', '<?= htmlspecialchars($loc['City'], ENT_QUOTES) ?>')">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </div>
                                        <div class="option-menu-item red"
                                             onclick="openDeleteLocationDialog(<?= (int)$loc['Location_ID'] ?>, '<?= htmlspecialchars($loc['District'] . ' — ' . $loc['City'], ENT_QUOTES) ?>')">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Add Location Dialog -->
    <div class="dialog-box-2" id="AddLocationDialog">
        <div class="dialog-content" style="width:460px;">
            <div class="dialog-title">
                <div class="title">Add Location</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('AddLocationDialog')"></i>
                </div>
            </div>
            <form method="POST" action="/admin/locations/create">
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">District <span style="color:red;">*</span></label>
                    <input list="district-list-add" name="district" id="add_district" required
                           autocomplete="off"
                           placeholder="Select or type a district..."
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                    <datalist id="district-list-add">
                        <?php foreach ($districtNames as $d): ?>
                            <option value="<?= htmlspecialchars($d) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">City <span style="color:red;">*</span></label>
                    <input type="text" name="city" required
                           placeholder="e.g. Colombo 01"
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="button" style="background:#008500;">
                        <i class="fa-solid fa-plus" style="margin-right:8px;"></i>Add Location
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Location Dialog -->
    <div class="dialog-box-2" id="EditLocationDialog">
        <div class="dialog-content" style="width:460px;">
            <div class="dialog-title">
                <div class="title">Edit Location</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('EditLocationDialog')"></i>
                </div>
            </div>
            <form method="POST" action="/admin/locations/edit">
                <input type="hidden" name="location_id" id="edit_location_id" class="notreset">

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">District <span style="color:red;">*</span></label>
                    <input list="district-list-edit" name="district" id="edit_district" required
                           autocomplete="off"
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                    <datalist id="district-list-edit">
                        <?php foreach ($districtNames as $d): ?>
                            <option value="<?= htmlspecialchars($d) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">City <span style="color:red;">*</span></label>
                    <input type="text" name="city" id="edit_city" required
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="button" style="background:#1a73e8;">
                        <i class="fa-solid fa-floppy-disk" style="margin-right:8px;"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Location Dialog -->
    <div class="dialog-box-2" id="DeleteLocationDialog">
        <div class="dialog-content" style="width:420px;">
            <div class="dialog-title">
                <div class="title">Delete Location</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('DeleteLocationDialog')"></i>
                </div>
            </div>
            <p id="DeleteLocationName" style="font-weight:600;margin-bottom:10px;"></p>
            <p style="color:#555;font-size:14px;margin-bottom:20px;">
                Are you sure you want to delete this location? This action cannot be undone.
            </p>
            <form method="POST" action="/admin/locations/delete">
                <input type="hidden" name="location_id" id="delete_location_id" class="notreset">
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="button" style="background:#c0392b;color:#fff;">
                        <i class="fa-solid fa-trash" style="margin-right:8px;"></i>Delete
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openAddLocationDialog() {
            viewDialogBox('AddLocationDialog');
        }

        function openEditLocationDialog(id, district, city) {
            viewDialogBox('EditLocationDialog');
            document.getElementById('edit_location_id').value = id;
            document.getElementById('edit_district').value    = district;
            document.getElementById('edit_city').value        = city;
        }

        function openDeleteLocationDialog(id, label) {
            viewDialogBox('DeleteLocationDialog');
            document.getElementById('delete_location_id').value        = id;
            document.getElementById('DeleteLocationName').textContent  = 'Location: ' + label;
        }
    </script>

</body>
</html>

<script>
    const Sidemenu_Active_ID = 'SM_Locations';
</script>
