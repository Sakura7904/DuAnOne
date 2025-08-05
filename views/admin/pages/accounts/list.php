<h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Accounts</h2>
<div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
    <div class="flex items-center gap-x-1">
        <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
        <a class="capitalize" href="index.php?admin=dashboard">home</a>
    </div>
    <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
    <span class="capitalize text-color-brands">Accounts</span>
</div>

<div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg overflow-scroll scrollbar-hide p-6 mb-8">
    <div class="flex items-center justify-between pb-4 border-b border-neutral dark:border-dark-neutral-border mb-5">
        <p class="text-xl font-semibold text-gray-1100 dark:text-gray-dark-1100">Danh sách người dùng</p>
    </div>
    <table class="w-full min-w-[1100px] table-auto text-sm">
        <thead>
            <tr class="border-b border-neutral dark:border-dark-neutral-border">
                <th class="text-left text-gray-500 px-4 py-3">ID</th>
                <th class="text-left text-gray-500 px-4 py-3">Tên người dùng</th>
                <th class="text-left text-gray-500 px-4 py-3">Email</th>
                <th class="text-left text-gray-500 px-4 py-3">Số điện thoại</th>
                <th class="text-left text-gray-500 px-4 py-3">Địa chỉ</th>
                <th class="text-left text-gray-500 px-4 py-3">Vai trò</th>
                <th class="text-left text-gray-500 px-4 py-3">Trạng thái</th>
                <th class="text-center text-gray-500 px-4 py-3">Chỉnh trạng thái</th>
                <th class="text-center text-gray-500 px-4 py-3">Chuyển vai trò</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $user): ?>
                <tr class="border-b text-gray-800 dark:text-gray-300 border-neutral dark:border-dark-neutral-border">
                    <td class="px-4 py-3"><?= htmlspecialchars($user['id']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['full_name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['phone_number']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['address']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($user['role']) ?></td>

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <?php if ($user['status'] == 'active'): ?>
                                <span class="inline-block w-3 h-3 rounded-full" style="background-color: #34d399;"></span>
                                <span class="text-xs leading-4 font-medium" style="color: #059669;">Hoạt động</span>
                            <?php else: ?>
                                <span class="inline-block w-3 h-3 rounded-full" style="background-color: #f87171;"></span>
                                <span class="text-xs leading-4 font-medium" style="color: #dc2626;">Không hoạt động</span>
                            <?php endif; ?>
                        </div>
                    </td>


                    <!-- Form chỉnh trạng thái -->
                    <td class="px-4 py-3 text-center">
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="text-gray-400 italic">------</span>
                        <?php else: ?>
                            <form method="post" action="index.php?admin=change_status_accounts">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                <select name="status" class="border px-2 py-1 rounded bg-white" onchange="this.form.submit()">
                                    <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </form>
                        <?php endif; ?>
                    </td>

                    <!-- Form chuyển vai trò -->
                    <td class="px-4 py-3 text-center">
                        <?php if ($user['role'] === 'admin' || $user['status'] !== 'active'): ?>
                            <span class="text-gray-400 italic">------</span>
                        <?php else: ?>
                            <form method="post" action="index.php?admin=promote_accounts_role">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="text-blue-600 hover:underline hover:text-blue-800 text-sm">Trao quyền Admin</button>
                            </form>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>