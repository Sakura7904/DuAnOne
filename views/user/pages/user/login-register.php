    <link rel="stylesheet" href="../../assets/users/web.nvnstatic.net/css/validationEngine.jqueryae52.css?v=5" type="text/css">
    <link rel="stylesheet" href="../../assets/users/web.nvnstatic.net/css/appLib.css" type="text/css">
    <link rel="stylesheet" href="../../assets/users/web.nvnstatic.net/tp/T0356/css/user30f4.css?v=3" type="text/css">
    <script defer type="text/javascript" src="../../assets/users/web.nvnstatic.net/tp/T0356/js/user5e1f.js?v=2"></script>
    <?php
    // Lấy biến từ data (thêm vào đầu file)
    $errors = $data['errors'] ?? [];
    $oldInput = $data['oldInput'] ?? [];

    ?>
    <div class="full pb-20">
        <div class="container">
            <div class="headCategory hidden-xs hidden-sm"
                style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(../../assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20240701_6Xk1iXAr.jpg);">
                <ul class="breadcrumbCate">
                    <li><a href="?user=home">Trang chủ</a></li>
                    <li><a>Tài khoản</a></li>
                </ul>
            </div>

            <div class="boxUser clearfix">

                <div class="section-content col-md-6 col-lg-6 col-xs-12 col-sm-12 signinContent open">
                    <div class="block hidden-xs hidden-sm">
                        <h2 class="section-title">
                            Đăng nhập </h2>
                    </div>
                    <!-- Hiển thị thông báo success -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success']); ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Hiển thị lỗi đăng nhập -->
                    <?php if (isset($errors['login'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($errors['login']) ?>
                        </div>
                    <?php endif; ?>
                    <form id="formAcount" class="formAcount validate" method="post" action="?user=handleLogin">
                        <div class="form-group">
                            <label>Nhập SĐT hoặc Địa chỉ email:</label>
                            <input type="text" name="username" placeholder="Email hoặc số điện thoại"
                                class=" form-control inputAccount" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            <!-- Hiển thị lỗi username -->
                            <?php if (isset($errors['username'])): ?>
                                <div class="error-message">
                                    <div class="text-danger"><?= htmlspecialchars($errors['username']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Mật Khẩu:</label>
                            <input type="password" name="password" placeholder="Password"
                                class=" form-control inputAccount">
                            <!-- Hiển thị lỗi password -->
                            <?php if (isset($errors['password'])): ?>
                                <div class="error-message">
                                    <div class="text-danger"><?= htmlspecialchars($errors['password']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group clearfix">
                            <div class="wrapperBtnSignin">
                                <button id="btnSignIn" type="submit" class="btn w100 btn-green">Đăng nhập</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="section-content col-md-6 col-lg-6 col-xs-12 col-sm-12 signupContent">
                    <div class="block hidden-xs hidden-sm">
                        <h2 class="section-title">
                            Đăng ký </h2>
                    </div>
                    <?php if (isset($errors['register'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($errors['register']) ?>
                        </div>
                    <?php endif; ?>

                    <form id="formAcountSignup" action="?user=handleRegister" class="formAcount validate" method="post">
                        <div class="form-group">
                            <label>Số điện thoại:</label>
                            <input type="text" class="form-control inputAccount"
                                name="mobile" placeholder="Điện thoại" value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>">
                            <?php if (isset($errors['mobile'])): ?>
                                <div class="error-message">
                                    <div class="text-danger"> <?= htmlspecialchars($errors['mobile']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Địa Chỉ Email:</label>
                            <input type="text" name="email" placeholder="Email hoặc tên đăng nhập"
                                class=" form-control inputAccount" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            <?php if (isset($errors['email'])): ?>
                                <div class="error-message">
                                    <div class="text-danger"><?= htmlspecialchars($errors['email']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Họ và tên:</label>
                            <input type="text" name="fullName" placeholder="Họ và tên của bạn"
                                class="form-control inputAccount"
                                value="<?= htmlspecialchars($_POST['fullName'] ?? '') ?>">
                            <?php if (isset($errors['fullName'])): ?>
                                <div class="error-message">
                                    <div class="text-danger"> <?= htmlspecialchars($errors['fullName']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Mật Khẩu:</label>
                            <input type="password" name="password" placeholder="Password"
                                class=" form-control inputAccount">
                            <?php if (isset($errors['password_register'])): ?>
                                <div class="error-message">
                                    <div class="text-danger"> <?= htmlspecialchars($errors['password_register']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Nhập Lại Mật Khẩu:</label>
                            <input type="password" name="confirmPassword" placeholder="Password"
                                class=" form-control inputAccount">
                            <?php if (isset($errors['confirmPassword'])): ?>
                                <div class="error-message">
                                    <?= htmlspecialchars($errors['confirmPassword']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group clearfix">
                            <div class="wrapperBtnSignin">
                                <button id="btnSingup" type="submit" class="btn w100 btn-green">Tạo tài khoản</button>
                                <p style="margin: 19px 0;">Đăng ký ngay để <span style="color: #D40404">nhận nhiều ưu đãi</span></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <section class="bannerPromotion hidden-xs hidden-sm">
                <div class="container">
                    <div class="row">
                        <div class="bannerPromotionItem col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a href="../campaign/3450/XU-HUONG.html">
                                <img alt="Banner 9"
                                    src="../../assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220222_8qQSVdNcr1Kw0ULWA2a4f5Oo.jpg">
                            </a>
                        </div>
                        <div class="bannerPromotionItem col-lg-6 col-md-6 col-xs-12 col-sm-12">
                            <a href="../campaign/3505/MOMENT-AT-TIFFANY-S.html">
                                <img alt="Banner 8"
                                    src="../../assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220222_sYVkyTE2jNelRV3M2P43yfsd.png">
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- JavaScript xử lý AJAX -->
    <script>
        $(document).ready(function() {
            // Xử lý đăng nhập
            $('#btnSignIn').click(function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.error-message').remove();
                $('.alert').remove();
                $('.form-control').removeClass('error');

                var formData = $('#formAcount').serialize();

                $.ajax({
                    url: '?user=handleLogin',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Hiển thị thông báo thành công
                            $('.signinContent').prepend('<div class="alert alert-success">' + response.message + '</div>');

                            // Redirect sau 1 giây
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1000);
                        } else {
                            // Hiển thị lỗi
                            $('.signinContent').prepend('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('.signinContent').prepend('<div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại!</div>');
                    }
                });
            });

            // Xử lý đăng ký
            $('#btnSingup').click(function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.error-message').remove();
                $('.alert').remove();
                $('.form-control').removeClass('error');

                var formData = $('#formAcountSignup').serialize();

                $.ajax({
                    url: '?user=handleRegister',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Hiển thị thông báo thành công
                            $('.signupContent').prepend('<div class="alert alert-success">' + response.message + '</div>');

                            // Clear form và chuyển về tab đăng nhập
                            $('#formAcountSignup')[0].reset();

                            setTimeout(function() {
                                $('.signinContent').addClass('open');
                                $('.signupContent').removeClass('open');
                            }, 1500);
                        } else {
                            // Hiển thị lỗi
                            $('.signupContent').prepend('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('.signupContent').prepend('<div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại!</div>');
                    }
                });
            });
        });
    </script>

    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?= $_SESSION['alert']['type'] ?>',
                    title: '<?= $_SESSION['alert']['type'] == 'success' ? 'Thành công!' : ($_SESSION['alert']['type'] == 'error' ? 'Lỗi!' : 'Thông báo!') ?>',
                    text: '<?= htmlspecialchars($_SESSION['alert']['message']) ?>',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php
        unset($_SESSION['alert']);
    endif;
    ?>