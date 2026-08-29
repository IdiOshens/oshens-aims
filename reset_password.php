<?php
require_once "DB_operations.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changed_password'])) {
    $result = resetUserPassword(
        $_POST['email'],
        $_POST['current_password'],
        $_POST['new_password'],
        $_POST['confirm_password']
    );

    if ($result === true) {
        $success = "Password updated successfully!";
        // Clear form fields on success
        $_POST = array();
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <title>
        oshens Attendance IMS
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="./assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
    <link href="./assets/css/error_message.css" rel="stylesheet" />
</head>

<body class="">
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <!-- <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../pages/dashboard.html">
                            OSHENS ATTENDANCE MANAGEMENT SYSTEM
                        </a>

                    </div>
                </nav> -->
                <!-- End Navbar -->
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-75">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-bolder text-info text-gradient">OSHENS AIMS</h3>
                                    <p class="mb-0">Update your password from here</p>
                                </div>
                                <div class="card-body">
                                    <?php if ($error): ?>
                                        <div class="message error"><?= htmlspecialchars($error) ?></div>
                                    <?php endif; ?>

                                    <?php if ($success): ?>
                                        <div class="message success"><?= htmlspecialchars($success) ?></div>
                                    <?php endif; ?>

                                    <form role="form" action="reset_password.php" method="post">
                                        <label>Email</label>
                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon" required
                                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                        </div>
                                        <label>Current Password</label>
                                        <div class="mb-3">
                                            <input type="password" name="current_password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
                                        </div>

                                        <label>New Password</label>
                                        <div class="mb-3">
                                            <input type="password" name="new_password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
                                        </div>

                                        <label>Re_enter New Password</label>
                                        <div class="mb-3">
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" name="changed_password" class="btn bg-gradient-info w-100 mt-4 mb-0">Confirm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('./assets/img/curved-images/curved6.jpg')"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <p class="mb-0 text-secondary">
                        Copyright © <script>
                            document.write(new Date().getFullYear())
                        </script> Idi Mbuga
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>
    <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="./assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>