<header class="main_nav_header">
    <div class="botHeader hidden-xs hidden-sm">
        <div class="container">
            <div class="logoHeader col-lg-1 col-md-1">
                <a aria-label="logo" href="?user=home">
                    <img width="77" height="48" alt="logo"
                        src="../assets/users/pos.nvncdn.com/4ef0bf-108661/store/20250429_Ya1OrcUS.png">
                </a>
            </div>
            <div class="menuMain col-lg-9 col-md-9">
                <ul class="tp_menu nav-navbar clearfix">
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_32323"
                            href="khOi-nguOn-thanh-lIch-(-web)-pm128511.html" title="SALE CHÀO HÈ">
                            SALE CHÀO HÈ
                            <i class="fal fa-angle-down" aria-hidden="true"></i>
                            <span class="saleTick">Sale</span>
                        </a>
                        <ul class="mainChild levlup_2">
                            <li class="title_lv2">
                                <a aria-label="menu" href="khOi-nguOn-thanh-lIch-(-web)-pm128511.html"
                                    title="Khuyến mại theo từng sản phẩm">Khuyến mại theo từng sản phẩm</a>
                            </li>
                        </ul>
                    </li>
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_32261" href="dam-pc542064.html" title="ĐẦM">
                            ĐẦM
                        </a>
                    </li>
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_32268" href="ao-pc542071.html" title="ÁO">
                            ÁO
                        </a>
                    </li>
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_32269" href="quan-pc542075.html"
                            title="QUẦN">
                            QUẦN
                        </a>
                    </li>
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_32270" href="chan-vay-pc542079.html"
                            title="CHÂN VÁY">
                            CHÂN VÁY
                        </a>
                    </li>
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_32271" href="ao-khoac-pc542082.html"
                            title="ÁO KHOÁC">
                            ÁO KHOÁC
                        </a>
                    </li>
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_2153" href="lookbook-ac2153.html"
                            title="LOOKBOOK">
                            LOOKBOOK
                        </a>
                    </li>
                    <li class="title_lv1">
                        <a aria-label="menu" class="tp_menu_item style_57707"
                            href="campaign/4698/JASMINE-COLLECTION.html" title="BST MỚI">
                            BST MỚI
                        </a>
                    </li>
                </ul>
            </div>
            <div class="iconHeader col-lg-2 col-md-2">
                <div class="wishlistBtn btnIcon">
                    <a aria-label="wishlist" href="wishlist.html">
                        <i class="far fa-heart"></i>
                        <span class="wishlistCount">
                            0 </span>
                    </a>
                </div>
                <div class="cartBtn btnIcon">
                    <a aria-label="cart" class="cartBtnOpen" href="?user=cart">
                        <i class="far fa-shopping-bag"></i>
                        <span class="header__cart-count">0</span>
                    </a>
                    <!-- <a aria-label="cart" class="cartBtnOpen" href="javascript:void(0)">
                        <i class="far fa-shopping-bag"></i>
                        <span class="header__cart-count">0</span>
                    </a> -->
                    <div class="cartHeaderContent" style="display: none">
                        <div class="cartReload"></div>
                    </div>

                </div>

                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Hiển thị khi đã đăng nhập -->
                    <div class="userBtn btnIcon">
                        <a aria-label="user" class="userBtnOpen" href="javascript:void(0)" title="<?= htmlspecialchars($_SESSION['user']['full_name']) ?>">
                            <i class="far fa-user"></i>
                        </a>
                        <div class="userBox">
                            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                <a href="?admin=dashboard">
                                    Xin chào admin <?= htmlspecialchars($_SESSION['user']['full_name']) ?>
                                </a>
                            <?php endif; ?>
                            <a aria-label="profile" href="?user=profile" title="<?= htmlspecialchars($_SESSION['user']['full_name']) ?>">
                                Tài khoản của tôi
                            </a>
                            <a aria-label="logout" href="?user=purchase">
                                Đơn mua
                            </a>
                            <a aria-label="logout" href="?user=logout">
                                Đăng xuất
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Hiển thị khi chưa đăng nhập -->
                    <div class="userBtn btnIcon">
                        <a aria-label="?user=login" href="?user=login">
                            <i class="far fa-user"></i>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <div class="policyBot hidden-xs hidden-sm">
        <div class="container">
            <ul>
                <li>
                    <a aria-label="policy" href="he-thong-cua-hang.html">
                        <img src="../assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220217_DbZC1P3EtEQCzjezvBFKNdfn.png">
                        <span>Hệ thống cửa hàng</span>
                    </a>
                </li>
                <li>
                    <a aria-label="policy" href="chinh-sach-van-chuyen-n93684.html">
                        <img src="../assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220217_zI3LVzaVQ2ecO28wILTulXo0.png">
                        <span>Thông tin vận chuyển</span>
                    </a>
                </li>
                <li>
                    <a aria-label="policy" href="chuong-trinh-tich-diem-n93679.html">
                        <img src="../assets/users/pos.nvncdn.com/4ef0bf-108661/bn/20220217_6vuovyS0B4AEqbNDogOFTiB2.png">
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
                <a aria-label="logo" href="?user=home">
                    <img alt="logo" src="../assets/users/pos.nvncdn.com/4ef0bf-108661/store/20250429_Ya1OrcUS.png">
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
                        <span class="wishlistCount">
                            0 </span>
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
            <a aria-label="logosearch" href="index.html">
                <img alt="logo" src="../assets/users/pos.nvncdn.com/4ef0bf-108661/store/20250429_Ya1OrcUS.png">
            </a>
        </div>
    </div>
</header>