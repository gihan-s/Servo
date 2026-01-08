<?php
$TopBarHeader = "Dashboard";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <!-- css -->
    <link rel="stylesheet" href="/assets/css/elementStyles.css">
    <link rel="stylesheet" href="/assets/css/gridTemplates.css">

    <link rel="stylesheet" href="/assets/css/admin-main.css">
    <link rel="stylesheet" href="/assets/css/admin-sidebar.css">

    <!-- Javascript -->
    <script src="/assets/js/elementScript.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="/assets/js/admin-script.js" defer></script>
    <script src="/assets/js/admin-dashboardCharts.js" defer></script>

</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>





    <div class="card-wrapper">

        <div class="container top-card">

            <div>
                <h3>Active Clients</h3>
                <h1>458</h1>
                <span>Currently Using the System</span>
            </div>

            <img src="/assets/img/admin-icon/softwares.jpg" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Active Providers</h3>
                <h1>56</h1>
                <span>Currently Delivering Services</span>
            </div>

            <img src="/assets/img/admin-icon/invoices.webp" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Total Active Posts</h3>
                <h1>4,526</h1>
                <span>Currently Published</span>
            </div>

            <img src="/assets/img/admin-icon/sales.webp" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Payment Received</h3>
                <h1>835,000.00</h1>
                <span>No of Customers Added</span>
            </div>

            <img src="/assets/img/admin-icon/accounting.png" alt="">

        </div>

    </div>



<div class="card-wrapper-2">

    <div>

        <div class="card-wrapper-3">

            <div class="container">
                <canvas id="NoOfSoftwaresChart"></canvas>
            </div>

            <div class="container">
                <div class="dougnut-chart-wrapper">
                    <canvas id="VisitsSummery"></canvas>

                    <h3>Projects Summary</h3>
                </div>

                <table class="visit-table">
                    <tr>
                        <td>Pending Projects</td>
                        <td>1250</td>
                    </tr>

                    <tr>
                        <td>Ongoing Projects</td>
                        <td>520</td>
                    </tr>

                    <tr>
                        <td>Completed Projects</td>
                        <td>1260</td>
                    </tr>

                    <tr>
                        <td>Rejected Projects</td>
                        <td>80</td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <th>3050</th>
                    </tr>

                </table>
            </div>

        </div>

        <div class="card-wrapper-4">

            <div class="container">

                <h3>Most Engaging Providers</h3>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/75.jpg" alt="">
                    <div>
                        <h4>Chethiya Bandara</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="80"></div>
                        <span>Bids : 1245</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/74.jpg" alt="">
                    <div>
                        <h4>Himath Adithya</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="70"></div>
                        <span>Bids : 760</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/73.jpg" alt="">
                    <div>
                        <h4>Akila Prabhashwara</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="55"></div>
                        <span>Bids : 600</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/72.jpg" alt="">
                    <div>
                        <h4>Bhashitha Sandeepa</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="40"></div>
                        <span>Bids : 452</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/71.jpg" alt="">
                    <div>
                        <h4>Pasindu Gihan</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="25"></div>
                        <span>Bids : 325</span>
                    </div>
                </div>

            </div>

            <div class="container">

                <h3>Most Earned Providers</h3>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/74.jpg" alt="">
                    <div>
                        <h4>Himath Adithya</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="80"></div>
                        <span>Amount : Rs. 3,261,124.005</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/73.jpg" alt="">
                    <div>
                        <h4>Akila Prabhashwara</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="50"></div>
                        <span>Amount : Rs. 2,756,510.00</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/75.jpg" alt="">
                    <div>
                        <h4>Chethiya Bandara</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="35"></div>
                        <span>Amount : Rs. 1,261,600.00</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/72.jpg" alt="">
                    <div>
                        <h4>Bhashitha Sandeepa</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="15"></div>
                        <span>Amount : Rs. 561,452.00</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/71.jpg" alt="">
                    <div>
                        <h4>Pasindu Gihan</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="8"></div>
                        <span>Amount : Rs. 361,325.00</span>
                    </div>
                </div>




            </div>

            <div class="container">


                <h3>Most Spending Clients</h3>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/65.jpg" alt="">
                    <div>
                        <h4>Hirusha Randika</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="80"></div>
                        <span>Amount : Rs. 325,600.00</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/64.jpg" alt="">
                    <div>
                        <h4>Pasan Dhananjaya</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="70"></div>
                        <span>Amount : Rs. 250,000.00</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/63.jpg" alt="">
                    <div>
                        <h4>Charith Shehan</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="55"></div>
                        <span>Amount : Rs. 160,000.00</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/62.jpg" alt="">
                    <div>
                        <h4>Chinthaka Prasad</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="40"></div>
                        <span>Amount : Rs. 88,500.00</span>
                    </div>
                </div>

                <div class="customer-progress-card">
                    <img src="https://randomuser.me/api/portraits/med/men/61.jpg" alt="">
                    <div>
                        <h4>Kalindu Dilshan</h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="25"></div>
                        <span>Amount : Rs. 65,325.00</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
    
</div>

</body>

</html>

<script>
    const Sidemenu_Active_ID = 'SM_Dashboard';
</script>