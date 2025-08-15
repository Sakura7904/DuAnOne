<?php
$item       = $item ?? null;          // record đang sửa
$attributes = $attributes ?? [];
$old        = $old ?? [];
$errors     = $errors ?? [];
$id         = (int)($item['id'] ?? ($old['id'] ?? 0));
$valCurrent = $old['value'] ?? $item['value'] ?? '';
$ccCurrent  = $old['color_code'] ?? $item['color_code'] ?? '';
$aidCurrent = (int)($old['attribute_id'] ?? $item['attribute_id'] ?? 0);
?>
<script>
    <?php if (!empty($errors['form'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: '<?= htmlspecialchars($errors['form']) ?>'
        });
    <?php endif; ?>
</script>

<div class="max-w-3xl">
    <h2 class="capitalize text-gray-1100 font-bold text-[24px] leading-[32px] dark:text-gray-dark-1100 mb-4">Sửa giá trị thuộc tính</h2>

    <form action="?admin=attribute_value_update" method="post" class="border p-6 bg-neutral-bg rounded-2xl border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral space-y-5" onsubmit="return validateFormAV(this);">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div>
            <label class="block text-sm mb-2 text-gray-600 dark:text-gray-dark-500">Thuộc tính <span class="text-danger">*</span></label>
            <select name="attribute_id" class="w-full border rounded-2xl p-3 bg-white dark:bg-[#1e1f29] border-neutral dark:border-dark-neutral-border" required>
                <?php foreach ($attributes as $a): ?>
                    <option value="<?= (int)$a['id'] ?>" <?= $aidCurrent === (int)$a['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm mb-2 text-gray-600 dark:text-gray-dark-500">Giá trị (value) <span class="text-danger">*</span></label>
            <input type="text" name="value" maxlength="100" value="<?= htmlspecialchars($valCurrent) ?>" class="w-full border rounded-2xl p-3 bg-white dark:bg-[#1e1f29] border-neutral dark:border-dark-neutral-border" required>
        </div>

        <div>
            <label class="block text-sm mb-2 text-gray-600 dark:text-gray-dark-500">Mã màu (color_code)</label>
            <div class="flex items-center gap-3">
                <input type="text" name="color_code" value="<?= htmlspecialchars($ccCurrent) ?>" class="flex-1 border rounded-2xl p-3 bg-white dark:bg-[#1e1f29] border-neutral dark:border-dark-neutral-border" placeholder="#FFFFFF">
                <input type="color" id="picker" class="w-12 h-12 rounded border" value="<?= htmlspecialchars($ccCurrent ?: '#ffffff') ?>" oninput="document.querySelector('[name=color_code]').value=this.value;">
            </div>
            <small class="text-xs text-gray-500">Định dạng #RRGGBB</small>
        </div>

        <div class="flex gap-3">
            <button class="btn bg-color-brands text-white rounded-2xl px-6">Cập nhật</button>
            <a href="?admin=attribute_values" class="btn rounded-2xl px-6 border-0 bg-[#f3f3f3] dark:bg-[#313442]">Quay lại</a>
        </div>
    </form>
</div>

<script>
    function validateFormAV(f) {
        const aid = +f.attribute_id.value || 0;
        const val = (f.value.value || '').trim();
        const cc = (f.color_code.value || '').trim();
        if (!aid) {
            Swal.fire({
                icon: 'error',
                title: 'Thiếu dữ liệu',
                text: 'Vui lòng chọn thuộc tính'
            });
            return false;
        }
        if (!val) {
            Swal.fire({
                icon: 'error',
                title: 'Thiếu dữ liệu',
                text: 'Vui lòng nhập value'
            });
            return false;
        }
        if (cc && !/^#[0-9A-Fa-f]{6}$/.test(cc)) {
            Swal.fire({
                icon: 'error',
                title: 'Sai định dạng',
                text: 'color_code phải dạng #RRGGBB'
            });
            return false;
        }
        return true;
    }
</script>