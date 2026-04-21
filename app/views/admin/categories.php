<?php
$TopBarHeader = "Categories";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>

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
        <button class="button" onclick="openAddCategoryDialog()" style="background:#008500;">
            <i class="fa-solid fa-plus" style="margin-right:8px;"></i>Add Category
        </button>
    </div>

    <div class="container">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['Category_ID']) ?></td>
                            <td>
                                <img src="/file/category-icons/<?= urlencode($cat['Icon'] ?? 'default-category.png') ?>"
                                     alt="icon"
                                     style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                            </td>
                            <td><?= htmlspecialchars($cat['Name']) ?></td>
                            <td><?= htmlspecialchars($cat['Description'] ?? '—') ?></td>
                            <td>
                                <div class="option-menu">
                                    <i class="fa-solid fa-ellipsis-vertical option-menu-button"></i>
                                    <div class="option-menu-content">
                                        <div class="option-menu-item"
                                             onclick="openEditCategoryDialog(<?= (int)$cat['Category_ID'] ?>, '<?= htmlspecialchars($cat['Name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($cat['Description'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($cat['Icon'], ENT_QUOTES) ?>')">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </div>
                                        <div class="option-menu-item red"
                                             onclick="openDeleteCategoryDialog(<?= (int)$cat['Category_ID'] ?>, '<?= htmlspecialchars($cat['Name'], ENT_QUOTES) ?>')">
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


    <!-- Add Category Dialog -->
    <div class="dialog-box-2" id="AddCategoryDialog">
        <div class="dialog-content" style="width:480px;">
            <div class="dialog-title">
                <div class="title">Add Category</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('AddCategoryDialog')"></i>
                </div>
            </div>
            <form method="POST" action="/admin/categories/create" enctype="multipart/form-data">
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">Name <span style="color:red;">*</span></label>
                    <input type="text" name="name" required
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">Description</label>
                    <input type="text" name="description"
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">Icon Image</label>
                    <input type="file" name="icon" accept="image/*"
                           style="width:100%;padding:4px 0;">
                    <small style="color:#888;">Leave blank to use default icon.</small>
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="button" style="background:#008500;">
                        <i class="fa-solid fa-plus" style="margin-right:8px;"></i>Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Category Dialog -->
    <div class="dialog-box-2" id="EditCategoryDialog">
        <div class="dialog-content" style="width:480px;">
            <div class="dialog-title">
                <div class="title">Edit Category</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('EditCategoryDialog')"></i>
                </div>
            </div>
            <form method="POST" action="/admin/categories/edit" enctype="multipart/form-data">
                <input type="hidden" name="category_id" id="edit_category_id" class="notreset">

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">Name <span style="color:red;">*</span></label>
                    <input type="text" name="name" id="edit_category_name" required
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">Description</label>
                    <input type="text" name="description" id="edit_category_description"
                           style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">Current Icon</label>
                    <img id="edit_category_icon_preview"
                         src="" alt="Current Icon"
                         style="width:52px;height:52px;object-fit:cover;border-radius:6px;border:1px solid #e0e0e0;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-weight:500;margin-bottom:6px;">Replace Icon Image</label>
                    <input type="file" name="icon" accept="image/*"
                           style="width:100%;padding:4px 0;">
                    <small style="color:#888;">Leave blank to keep current icon.</small>
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="button" style="background:#1a73e8;">
                        <i class="fa-solid fa-floppy-disk" style="margin-right:8px;"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Category Dialog -->
    <div class="dialog-box-2" id="DeleteCategoryDialog">
        <div class="dialog-content" style="width:420px;">
            <div class="dialog-title">
                <div class="title">Delete Category</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox('DeleteCategoryDialog')"></i>
                </div>
            </div>
            <p id="DeleteCategoryName" style="font-weight:600;margin-bottom:10px;"></p>
            <p style="color:#555;font-size:14px;margin-bottom:20px;">
                Are you sure you want to delete this category? This action cannot be undone.
            </p>
            <form method="POST" action="/admin/categories/delete">
                <input type="hidden" name="category_id" id="delete_category_id" class="notreset">
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="button" style="background:#c0392b;color:#fff;">
                        <i class="fa-solid fa-trash" style="margin-right:8px;"></i>Delete
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openAddCategoryDialog() {
            viewDialogBox('AddCategoryDialog');
        }

        function openEditCategoryDialog(id, name, description, icon) {
            viewDialogBox('EditCategoryDialog');
            document.getElementById('edit_category_id').value          = id;
            document.getElementById('edit_category_name').value        = name;
            document.getElementById('edit_category_description').value = description;
            document.getElementById('edit_category_icon_preview').src  = '/file/category-icons/' + encodeURIComponent(icon);
        }

        function openDeleteCategoryDialog(id, name) {
            viewDialogBox('DeleteCategoryDialog');
            document.getElementById('delete_category_id').value          = id;
            document.getElementById('DeleteCategoryName').textContent    = 'Category: ' + name;
        }
    </script>

</body>
</html>

<script>
    const Sidemenu_Active_ID = 'SM_Categories';
</script>
