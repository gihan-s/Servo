<div class="sidebar-new">

    <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="">

    <div style="width: 100%;">

        <div class="option" onclick="window.location = './Dashboard'" id="SM_Dashboard">
            <i class="fa-solid fa-layer-group"></i>Dashboard
        </div>

        <div class="option" onclick="window.location = './Providers'" id="SM_Providers">
            <i class="fa-solid fa-user-tie"></i>Providers
        </div>

        <div class="option" onclick="window.location = './Clients'" id="SM_Clients">
            <i class="fa-solid fa-users"></i>Clients
        </div>

        <div class="option" onclick="window.location = './Posts'" id="SM_Posts">
            <i class="fa-solid fa-address-card"></i></i>Posts
        </div>

        <div class="option" onclick="window.location = './Payments'" id="SM_Payments">
            <i class="fa-solid fa-coins"></i>Payments
        </div>

        <div class="option" onclick="window.location = './Complaints'" id="SM_Complaints">
            <i class="fa-solid fa-triangle-exclamation"></i> Complaints
        </div>

        <div class="option" onclick="window.location = './Analytics'" id="SM_Analytics">
            <i class="fa-solid fa-chart-simple"></i> Analytics
        </div>

        <div class="option" onclick="window.location = './Categories'" id="SM_Categories">
            <i class="fa-solid fa-list"></i> Categories
        </div>


    </div>

    <div class="option" onclick="window.location = './logout'">
        <i class="fa-solid fa-right-from-bracket"></i>Logout
    </div>

</div>







<script>
    function showAlert(alertMessage, Title = 'Error', Icon = 'fa-solid fa-circle-exclamation', Color = "Black") {
        document.getElementById("AlertMessage").innerHTML = alertMessage;
        viewDialogBox2("AlertDialog");

        document.querySelector("#AlertDialog .title").innerHTML = Title;
        document.querySelector("#AlertMessage").previousElementSibling.className = Icon;

        document.querySelector("#AlertDialog .title").style.color = Color;
        document.querySelector("#AlertMessage").previousElementSibling.style.color = Color;
    }
</script>



<div class="dialog-box-2" id="AlertDialog" style="z-index: 9999999;">

    <div class="container" style="padding: 20px; width: 350px;">

        <div style="display: flex; border-bottom:1px solid var(--tableBorderColor); margin-bottom:30px;">

            <div style="flex: 1;">
                <div class="title">Error</div>
            </div>

            <div style="flex: 1; display: flex; justify-content: end; color: red;">
                <i class="fa-solid fa-xmark dialog-close-button-2" onclick="closeDialogBox2('AlertDialog')"></i>
            </div>
        </div>

        <div style="font-size: 15px; gap:20px; display: flex; align-items: center; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 32px;"></i>
            <span id="AlertMessage"></span>
        </div>

        <div style="display: flex; justify-content: end; gap: 15px;">

            <button type="button" onclick="closeDialogBox2('AlertDialog')">OK</button>

        </div>


    </div>

</div>