<script defer type="text/javascript" src="https://web.nvnstatic.net/tp/T0356/js/user.js?v=2"></script>
<link rel="stylesheet" href="https://web.nvnstatic.net/tp/T0356/css/user.css?v=3" type="text/css">

<?php
// Lấy biến từ data
$user = $data['user'] ?? null;
$errors = $data['errors'] ?? [];
$oldInput = $data['oldInput'] ?? [];
?>

<div class="full pb-20 changePass">
    <div class="container">
        <div class="headCategory hidden-xs hidden-sm" style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(https://pos.nvncdn.com/4ef0bf-108661/bn/20240701_6Xk1iXAr.gif?v=1719919543);">
            <ul class="breadcrumbCate">
                <li><a href="?user=home">Trang chủ</a></li>
                <li><a class="129976">Đổi mật khẩu</a></li>
            </ul>
        </div>

        <!-- Hiển thị thông báo thành công -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']); ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="section-content block">
            <div class="block">
                <h1 class="section-title mt-4 text-center">
                    Đổi mật khẩu
                </h1>
                <p>Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
            </div>
            <form id="formAcount" class="formAcount validate" method="post" action="?user=handleChangePassword">
                <div class="form-group clearfix">
                    <label>Mật khẩu cũ</label>
                    <input type="password" id="oldpassword" name="oldpassword" placeholder="Mật khẩu cũ"
                        class="form-control inputAccount">
                </div>
                <?php if (isset($errors['oldpassword'])): ?>
                    <div class="error-message">
                        <div class="text-danger">
                            <?= htmlspecialchars($errors['oldpassword']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group clearfix">
                    <label>Mật khẩu mới</label>
                    <input type="password" id="newpassword" name="newpassword" placeholder="Mật khẩu mới"
                        class="form-control inputAccount">
                </div>
                <?php if (isset($errors['newpassword'])): ?>
                    <div class="error-message">
                        <div class="text-danger">
                            <?= htmlspecialchars($errors['newpassword']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group clearfix">
                    <label>Xác nhận Mật khẩu</label>
                    <input type="password" id="repassword" name="repassword" placeholder="Xác nhận Mật khẩu"
                        class="form-control inputAccount">
                </div>
                <?php if (isset($errors['repassword'])): ?>
                    <div class="error-message">
                        <div class="text-danger">
                            <?= htmlspecialchars($errors['repassword']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group clearfix">
                    <button type="submit" class="btn btn-green btnAcess"> Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>