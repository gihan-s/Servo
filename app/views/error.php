<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sorry something unexpected happened</title>
    <?php $publicBase = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\'); ?>
    <link rel="stylesheet" href="<?= ($publicBase === '' ? '' : $publicBase) ?>/assets/css/notfound.css">
</head>

<body>
    <section class="page_404">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="col-sm-10 col-sm-offset-1 text-center">
                        <div class="four_zero_four_bg">
                            <h1 class="text-center">500</h1>
                        </div>

                        <div class="contant_box_404">
                            <h3 class="h2">Looks like something went wrong</h3>
                            <p>Please try again later.</p>
                            <a href="<?= '/' ?>" class="link_404">Go to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- partial -->

</body>

</html>