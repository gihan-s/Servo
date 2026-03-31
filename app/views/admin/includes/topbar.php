<div class="topbar-new" style="justify-content: space-between;">

    <h3>
        <?php 
            if (isset($TopBarHeader)) {
                echo $TopBarHeader;
            }
        ?>
    </h3>

    <div class="user-wrapper">
        <div class="user-image">
            <img src="<?= BASE_URL ?>/assets/img/user.jpeg" alt="">
        </div>

        <div>
            <h4><?php echo $_SESSION["name"] ?></h4>
            <h5><?php echo $_SESSION["access_level"] ?></h5>
        </div>
    </div>
</div>