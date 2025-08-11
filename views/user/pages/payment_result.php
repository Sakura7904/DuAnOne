<?php
$isSuccess = $isSuccess ?? false;
$orderId   = (int)($orderId ?? 0);
$amountVn  = number_format((int)($amount ?? 0), 0, ',', '.') . 'đ';
?>
<div class="container" style="max-width:720px;margin:40px auto">
    <div style="text-align:center;padding:24px;border:1px solid #eee;border-radius:12px">
        <?php if ($isSuccess): ?>
            <h2 style="color:#16a34a;margin:0 0 8px">Thanh toán thành công</h2>
            <p>Mã đơn: <strong>#<?= htmlspecialchars($orderId) ?></strong></p>
            <p>Số tiền: <strong><?= $amountVn ?></strong></p>
            <p>Thanh toán qua: <strong><?= $payType ?></strong></p>
            <p>Mã giao dịch: <strong><?= htmlspecialchars($transId ?? '') ?></strong></p>
            <p style="color:#555">Trạng thái sẽ được xác nhận qua hệ thống (IPN). Bạn có thể xem chi tiết đơn ngay bây giờ.</p>
        <?php else: ?>
            <h2 style="color:#dc2626;margin:0 0 8px">Thanh toán thất bại</h2>
            <p>Mã đơn: <strong>#<?= htmlspecialchars($orderId) ?></strong></p>
            <p>Mã kết quả: <strong><?= htmlspecialchars((string)($resultCode ?? '')) ?></strong></p>
            <p>Vui lòng thử lại hoặc chọn phương thức khác.</p>
        <?php endif; ?>

        <div style="margin-top:16px">
            <?php if ($orderId): ?>
                <a class="btn" href="index.php?user=order&id=<?= $orderId ?>" style="padding:10px 16px;border:1px solid #333;border-radius:8px;display:inline-block;margin:4px 8px">Xem đơn hàng</a>
            <?php endif; ?>
            <a class="btn" href="index.php?user=home" style="padding:10px 16px;border:1px solid #333;border-radius:8px;display:inline-block;margin:4px 8px">Về trang chủ</a>
        </div>
    </div>
</div>