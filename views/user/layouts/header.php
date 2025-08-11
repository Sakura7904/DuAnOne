<?php
// Guard để không văng lỗi khi $categories thiếu / sai định dạng
if (!isset($categories) || !is_array($categories)) {
    $categories = [];
}
function e($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<header class="main_nav_header">
    <div class="botHeader hidden-xs hidden-sm">
        <div class="container">
            
            <div class="logoHeader col-lg-1 col-md-1">
                <a aria-label="logo" href="index.php?user=home">
                    <img width="77" height="48" alt="logo"
                        src="./assets/users/pos.nvncdn.com/4ef0bf-108661/store/20250429_Ya1OrcUS.png">
                </a>
            </div>
            <div class="menuMain col-lg-9 col-md-9">
                <ul class="tp_menu nav-navbar clearfix">

                    <!-- Hiển thị menu cha (Trang phục nữ) và các danh mục con bên trong dropdown -->
                    <?php foreach ($categories as $cat): ?>
                        <?php
                        $catId    = (int)($cat['id'] ?? 0);
                        $catName  = $cat['name'] ?? '';
                        $parentId = $cat['parent_id'] ?? null;
                        ?>
                        <?php if ($parentId === null): ?>
                            <li class="title_lv1">
                                <a aria-label="menu" class="tp_menu_item"
                                    href="index.php?user=productsByCategory&category_id=<?= $catId ?>"
                                    title="<?= e($catName) ?>">
                                    <?= e(mb_strtoupper((string)$catName, 'UTF-8')) ?>
                                    <i class="fal fa-angle-down" aria-hidden="true"></i>
                                </a>
                                <ul class="mainChild levlup_2">
                                    <?php foreach ($categories as $sub): ?>
                                        <?php
                                        $subId     = (int)($sub['id'] ?? 0);
                                        $subName   = $sub['name'] ?? '';
                                        $subParent = $sub['parent_id'] ?? null;
                                        ?>
                                        <?php if ($subParent == $catId): ?>
                                            <li class="title_lv2">
                                                <a aria-label="menu"
                                                    href="index.php?user=productsByCategory&category_id=<?= $subId ?>"
                                                    title="<?= e($subName) ?>">
                                                    <?= e($subName) ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Vẫn hiển thị tất cả các danh mục con như menu độc lập (GIỮ NGUYÊN LOGIC) -->
                    <?php foreach ($categories as $cat): ?>
                        <?php
                        $catId    = (int)($cat['id'] ?? 0);
                        $catName  = $cat['name'] ?? '';
                        $parentId = $cat['parent_id'] ?? null;
                        ?>
                        <?php if ($parentId != null): ?>
                            <li class="title_lv1">
                                <a aria-label="menu" class="tp_menu_item"
                                    href="index.php?user=productsByCategory&category_id=<?= $catId ?>"
                                    title="<?= e($catName) ?>">
                                    <?= e(mb_strtoupper((string)$catName, 'UTF-8')) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </ul>
            </div>

            <div class="iconHeader col-lg-2 col-md-2">
                <div class="wishlistBtn btnIcon">
                    <a aria-label="wishlist" href="wishlist.html">
                        <i class="far fa-heart"></i>
                        <span class="wishlistCount">0</span>
                    </a>
                </div>
                <div class="cartBtn btnIcon">
                    <a aria-label="cart" class="cartBtnOpen" href="javascript:void(0)">
                        <i class="far fa-shopping-bag"></i>
                        <span class="header__cart-count">0</span>
                    </a>
                    <div class="cartHeaderContent" style="display: none">
                        <div class="cartReload"></div>
                    </div>
                </div>
                <div class="userBtn btnIcon">
                    <a aria-label="signin" href="user/signin.html">
                        <i class="far fa-user"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="policyBot hidden-xs hidden-sm">
        <div class="container">
            <ul>
                <li>
                    <a aria-label="policy" href="he-thong-cua-hang.html">
                        <img src="./assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220217_DbZC1P3EtEQCzjezvBFKNdfn.png">
                        <span>Hệ thống cửa hàng</span>
                    </a>
                </li>
                <li>
                    <a aria-label="policy" href="chinh-sach-van-chuyen-n93684.html">
                        <img src="./assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220217_zI3LVzaVQ2ecO28wILTulXo0.png">
                        <span>Thông tin vận chuyển</span>
                    </a>
                </li>
                <li>
                    <a aria-label="policy" href="chuong-trinh-tich-diem-n93679.html">
                        <img src="./assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220217_6vuovyS0B4AEqbNDogOFTiB2.png">
                        <span>Chính sách tích điểm</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="botHeaderMobile clearfix hidden-lg hidden-md">
        <div class="container">
            <div class="iconLeft col-xs-4 col-sm-4">
                <div class="bar-tool btnIcon">
                    <a aria-label="menu" href="javascript:void(0)">
                        <i class="far fa-bars" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="btnIcon backHistory">
                    <button aria-label="history" onclick="history.back()"><i class="far fa-angle-left"></i></button>
                </div>
            </div>
            <div class="logoHeader col-xs-4 col-sm-4">
                <a aria-label="logo" href="index.php?user=home">
                    <img alt="logo" src="./assets/users/pos.nvncdn.com/4ef0bf-108661/store/20250429_Ya1OrcUS.png">
                </a>
            </div>
           
            <div class="iconRight col-xs-4 col-sm-4">
                <div class="searchBtn btnIcon">
                    <a aria-label="searchmobile" class="openSearchMobile" href="javascript:void(0)"><i
                            class="fal fa-search" aria-hidden="true"></i></a>
                </div>
                <div class="wishlistBtn btnIcon">
                    <a aria-label="wishlist" href="wishlist.html">
                        <i class="far fa-heart"></i>
                        <span class="wishlistCount">0</span>
                    </a>
                </div>
                <div class="cartBtn btnIcon">
                    <a aria-label="cart" href="cart.html">
                        <i class="far fa-shopping-bag"></i>
                        <span class="header__cart-count">0</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="boxSearchHeader hidden-lg hidden-md">
        <div class="wrapBoxSearch">
            <a aria-label="close-search" class="closeSearch" href="javascript:void(0)"><i
                    class="fal fa-times"></i></a>
            <h3>Tìm kiếm</h3>
            <form class="bigSearchBar" action="https://jm.com.vn/search" method="get">
                <input type="text" class="search-box" aria-label="Search" name="q"
                    placeholder="Bạn muốn tìm sản phẩm gì ?">
                <button aria-label="search" type="submit" class="search__btn">
                    <i class="fal fa-search" aria-hidden="true"></i>
                </button>
            </form>
        </div>
        <div class="logoSearchBox">
            <a aria-label="logosearch" href="index.php?user=home">
                <img alt="logo" src="./assets/users/pos.nvncdn.com/4ef0bf-108661/store/20250429_Ya1OrcUS.png">
            </a>
        </div>
    </div>
</header>