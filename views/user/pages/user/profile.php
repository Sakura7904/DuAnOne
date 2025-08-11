<script defer type="text/javascript" src="https://web.nvnstatic.net/tp/T0356/js/user.js?v=2"></script>
<?php
$user = $data['user'] ?? null;
$errors = $data['errors'] ?? [];
$oldInput = $data['oldInput'] ?? [];
?>
<link rel="stylesheet" href="https://web.nvnstatic.net/tp/T0356/css/user.css?v=3" type="text/css">
<div class="full pb-20">
    <div class="container">
        <div class="headCategory hidden-xs hidden-sm" style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(https://pos.nvncdn.com/4ef0bf-108661/bn/20240701_6Xk1iXAr.gif?v=1719919543);">
            <ul class="breadcrumbCate">
                <li><a href="?user=home">Trang chủ</a></li>
                <li><a>Tài khoản</a></li>
            </ul>
        </div>
        <div class="boxUser clearfix">
            <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12 leftBox">
                <div class="block hidden-xs hidden-sm">
                    <h2 class="section-title">
                        Hồ sơ của tôi
                    </h2>
                </div>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['success']); ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Hiển thị lỗi cập nhật -->
                <?php if (isset($errors['update'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($errors['update']) ?>
                    </div>
                <?php endif; ?>

                <form id="formAcountEdit" class="formAcount validate" action="?user=handleUpdateProfile" method="post">
                    <div class="form-group clearfix avata">
                        <img src="https://web.nvnstatic.net/tp/T0356/img/149071.png?v=9">
                        <div class="infoUser">
                            <span><?= htmlspecialchars($user['full_name']) ?></span>
                            <a href="?user=changePassword">Đổi mật khẩu</a>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label>Tên</label>
                        <input type="text" id="fullName" name="fullName"
                            value="<?= htmlspecialchars($oldInput['fullName'] ?? $user['full_name']) ?>"
                            placeholder="Họ tên" class="form-control inputAccount">

                        <?php if (isset($errors['fullName'])): ?>
                            <div class="error-message">
                                <?= htmlspecialchars($errors['fullName']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" id="mobile" name="mobile"
                            value="<?= htmlspecialchars($oldInput['mobile'] ?? $user['phone_number']) ?>"
                            placeholder="Điện thoại" class="form-control inputAccount">
                    </div>
                    <?php if (isset($errors['mobile'])): ?>
                        <div class="error-message text-danger">
                            <?= htmlspecialchars($errors['mobile']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($oldInput['email'] ?? $user['email']) ?>"
                            placeholder="Email" class="form-control inputAccount">
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message text-danger">
                            <?= htmlspecialchars($errors['email']) ?>
                        </div>
                    <?php endif; ?>



                    <div class="form-group clearfix buttonBot">
                        <button type="submit" class="btn btn-green btnUpdate">Lưu</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12 signupContent">
                <div class="block hidden-xs hidden-sm">
                    <h2 class="section-title">
                        Đơn hàng của tôi
                    </h2>
                </div>
                <p>Tra cứu đơn hàng của tôi <a href="?user=purchase" style="color: #D40404">tại đây</a></p>
            </div>
        </div>
    </div>
</div>