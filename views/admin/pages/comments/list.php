<?php
// Data từ controller/model
$comments    = $comments ?? [];
$result      = $result   ?? ['page' => 1, 'perPage' => 20, 'total' => 0, 'totalPages' => 1];

// Trang hiện tại & thông số phân trang
$currentPage = (int)($result['page'] ?? 1);
$perPage     = (int)($result['perPage'] ?? 20);
$total       = (int)($result['total'] ?? 0);
$totalPages  = (int)($result['totalPages'] ?? 1);

// Tính khoảng mục đang hiển thị
$hasItems  = $total > 0;
$startItem = $hasItems ? (($currentPage - 1) * $perPage + 1) : 0;
$endItem   = $hasItems ? min($currentPage * $perPage, $total) : 0;

// Tính dải trang hiển thị (tối đa 5 số)
$start = max(1, $currentPage - 2);
$end   = min($totalPages, $start + 4);
if ($end - $start < 4) {
    $start = max(1, $end - 4);
}

// Giữ lại các tham số filter (nếu có)
$keepKeys = ['keyword', 'product_id', 'user_id'];
$extra    = [];
foreach ($keepKeys as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') $extra[$k] = $_GET[$k];
}
$qs = $extra ? '&' . http_build_query($extra) : '';

// Flash message
if (isset($_SESSION['msg'])) {
    $msg  = $_SESSION['msg'];
    $type = $_SESSION['msg_type'] ?? 'info';
    unset($_SESSION['msg'], $_SESSION['msg_type']);

    $bgClass = match ($type) {
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error'   => 'bg-red-100 border-red-400 text-red-700',
        default   => 'bg-gray-100 border-gray-400 text-gray-700',
    }; ?>
    <div class="px-4 py-3 mb-4 border rounded <?= $bgClass ?>">
        <?= htmlspecialchars($msg) ?>
    </div>
<?php } ?>

<h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Bình luận</h2>
<div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
    <div class="flex items-center gap-x-1">
        <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
        <a class="capitalize" href="index.php?admin=dashboard">home</a>
    </div>
    <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
    <span class="capitalize text-color-brands">Bình luận</span>
</div>

<div class="p-6 bg-neutral-bg rounded-2xl border border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
    <div class="flex justify-between items-center mb-6">
        <h2 class="capitalize text-gray-1100 font-bold text-[20px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">
            Danh sách bình luận
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px]">
            <thead>
                <tr class="border-b border-neutral dark:border-dark-neutral-border pb-[15px]">
                    <th class="text-left text-gray-500 px-4 py-3">ID</th>
                    <th class="text-left text-gray-500 px-4 py-3">Sản phẩm</th>
                    <th class="text-left text-gray-500 px-4 py-3">Người dùng</th>
                    <th class="text-left text-gray-500 px-4 py-3">Nội dung</th>
                    <th class="text-left text-gray-500 px-4 py-3">Ngày bình luận</th>
                    <th class="text-left text-gray-500 px-4 py-3">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $c): ?>
                    <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                        <td class="px-4 py-3"><?= (int)$c['id'] ?></td>
                        <td class="px-4 py-3 pl-3">
                            <?= (int)$c['product_id'] ?> — <?= htmlspecialchars($c['product_name'] ?? '') ?>
                        </td>
                        <td class="px-4 py-3">
                            <?= (int)$c['user_id'] ?> — <?= htmlspecialchars($c['user_name'] ?? '') ?><br>
                            <small class="text-gray-400"><?= htmlspecialchars($c['email'] ?? '') ?></small>
                        </td>
                        <td class="px-4 py-3">
                            <?= nl2br(htmlspecialchars(mb_strimwidth($c['content'] ?? '', 0, 100, '…', 'UTF-8'))) ?>
                        </td>
                        <td class="px-4 py-3">
                            <?= !empty($c['created_at']) ? date('d/m/Y H:i:s', strtotime($c['created_at'])) : '-' ?>
                        </td>
                        <td class="px-4 py-3">
                            <a href="index.php?admin=delete_comment&id=<?= (int)$c['id'] ?><?= $qs ?>"
                                 class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 text-white bg-[#E23738] hover:!bg-[#ef6364] hover:text-white py-[9px]"
                                onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')"> Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">Chưa có bình luận nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
  <div class="mt-5 flex items-center gap-x-4 justify-center">
      <?php if ($currentPage > 1): ?>
        <a href="?admin=list_comments&p=<?= $currentPage - 1 ?><?= $qs ?>" class="btn">Prev</a>
      <?php else: ?>
        <span class="btn opacity-50 cursor-not-allowed">Prev</span>
      <?php endif; ?>

      <?php for ($i = $start; $i <= $end; $i++): ?>
        <?php if ($i == $currentPage): ?>
          <span class="btn bg-color-brands text-white"><?= $i ?></span>
        <?php else: ?>
          <a href="?admin=list_comments&p=<?= $i ?><?= $qs ?>" class="btn"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($currentPage < $totalPages): ?>
        <a href="?admin=list_comments&p=<?= $currentPage + 1 ?><?= $qs ?>" class="btn">Next</a>
      <?php else: ?>
        <span class="btn opacity-50 cursor-not-allowed">Next</span>
      <?php endif; ?>
  </div>
<?php endif; ?>
