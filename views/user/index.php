<!-- T0356 -->
<!DOCTYPE html>
<html lang="en" dir="ltr" data-jm.com.vn-template="T0356">

<!-- Mirrored from jm.com.vn/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 22 Jun 2025 07:47:55 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<link rel="stylesheet" href="path/to/your/product-styles.css">
<script src="path/to/your/product-scripts.js"></script>

<?php include 'views/user/layouts/head.php'; ?>

<body data-accent="blue" class="tp_background tp_text_color">
    <input type="hidden" id="checkIsUser" value="">
    <input type="hidden" id="psStoreId" value="108661">

    <section id="mobile_menu" class="visible-sm visible-xs">
        <?php include 'views/user/layouts/nav.php' ?>
    </section>

    <?php require_once 'views/user/layouts/header.php' ?>

    <?php include $content ?>

    <style>
        .botHeaderMobile .bar-tool.btnIcon {
            display: block;
        }

        .botHeaderMobile .backHistory.btnIcon {
            display: none;
        }

        .menuBot ul li.indexPage svg path {
            fill: #303030;
        }

        .menuBot ul li.indexPage a span {
            color: #303030;
            font-weight: 700;
        }
    </style>
    <div id="dialogMessage"></div>
    <div id="quick-view-product" class="modal"></div>
    <div class="overlay_chir menu"></div>
    <div id="modalShow" class="modal fade" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content"></div>
        </div>
    </div>
    <input type="hidden" class="checkCookies" value=''>

    <footer class="footer tp_footer">
        <?= include 'views/user/layouts/footer.php' ?>
    </footer>

    <div class="menuBot noneMobile visible-xs visible-sm">
        <ul>
            <li class="indexPage">
                <a aria-label="shop" href="index.html" rel="nofollow">
                    <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.66667 16.6673H13.3333C13.7753 16.6673 14.1993 16.4917 14.5118 16.1792C14.8244 15.8666 15 15.4427 15 15.0006V7.50065C15.0006 7.39098 14.9796 7.28226 14.9381 7.18073C14.8967 7.0792 14.8356 6.98685 14.7583 6.90899L8.09166 0.242327C7.93552 0.087118 7.72431 0 7.50416 0C7.28401 0 7.0728 0.087118 6.91666 0.242327L0.25 6.90899C0.17126 6.98617 0.108618 7.0782 0.0657043 7.17977C0.0227905 7.28133 0.000457708 7.3904 0 7.50065V15.0006C0 15.4427 0.175594 15.8666 0.488155 16.1792C0.800715 16.4917 1.22464 16.6673 1.66667 16.6673ZM5.83333 15.0006V10.834H9.16666V15.0006H5.83333ZM1.66667 7.84232L7.49999 2.00899L13.3333 7.84232V15.0006H10.8333V10.834C10.8333 10.392 10.6577 9.96803 10.3452 9.65547C10.0326 9.34291 9.60868 9.16732 9.16666 9.16732H5.83333C5.3913 9.16732 4.96738 9.34291 4.65482 9.65547C4.34226 9.96803 4.16666 10.392 4.16666 10.834V15.0006H1.66667V7.84232Z"
                            fill="#656565" />
                    </svg>
                    <span>Mua sắm</span>
                </a>
            </li>
            <li class="productPage">
                <a aria-label="product" href="product.html" rel="nofollow">
                    <svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.975 1.25001L3.25625 1.80626L3.6 2.50001H6.24999V5.62501H1.25V1.25001H2.975ZM3.3625 1.514e-05H0.624999C0.459239 1.514e-05 0.300268 0.0658632 0.183058 0.183073C0.0658479 0.300284 0 0.459254 0 0.625015V6.25001C0 6.41577 0.0658479 6.57474 0.183058 6.69195C0.300268 6.80916 0.459239 6.87501 0.624999 6.87501H6.87499C7.04075 6.87501 7.19973 6.80916 7.31694 6.69195C7.43415 6.57474 7.49999 6.41577 7.49999 6.25001V1.87501C7.49999 1.70925 7.43415 1.55028 7.31694 1.43307C7.19973 1.31586 7.04075 1.25001 6.87499 1.25001H4.375L3.925 0.343765C3.87258 0.239743 3.79214 0.152438 3.69275 0.0916989C3.59336 0.0309601 3.47898 -0.000795478 3.3625 1.514e-05Z"
                            fill="#656565" />
                        <path
                            d="M12.975 1.25001L13.2562 1.80626L13.6 2.50001H16.25V5.62501H11.25V1.25001H12.975ZM13.3625 1.514e-05H10.625C10.4592 1.514e-05 10.3003 0.0658632 10.1831 0.183073C10.0658 0.300284 10 0.459254 10 0.625015V6.25001C10 6.41577 10.0658 6.57474 10.1831 6.69195C10.3003 6.80916 10.4592 6.87501 10.625 6.87501H16.875C17.0408 6.87501 17.1997 6.80916 17.3169 6.69195C17.4341 6.57474 17.5 6.41577 17.5 6.25001V1.87501C17.5 1.70925 17.4341 1.55028 17.3169 1.43307C17.1997 1.31586 17.0408 1.25001 16.875 1.25001H14.375L13.925 0.343765C13.8726 0.239743 13.7921 0.152438 13.6928 0.0916989C13.5934 0.0309601 13.479 -0.000795478 13.3625 1.514e-05Z"
                            fill="#656565" />
                        <path
                            d="M2.975 9.37501L3.25625 9.93126L3.6 10.625H6.24999V13.75H1.25V9.37501H2.975ZM3.3625 8.12502H0.624999C0.459239 8.12502 0.300268 8.19086 0.183058 8.30807C0.0658479 8.42528 0 8.58425 0 8.75001V14.375C0 14.5408 0.0658479 14.6997 0.183058 14.817C0.300268 14.9342 0.459239 15 0.624999 15H6.87499C7.04075 15 7.19973 14.9342 7.31694 14.817C7.43415 14.6997 7.49999 14.5408 7.49999 14.375V10C7.49999 9.83425 7.43415 9.67528 7.31694 9.55807C7.19973 9.44086 7.04075 9.37501 6.87499 9.37501H4.375L3.925 8.46876C3.87258 8.36474 3.79214 8.27744 3.69275 8.2167C3.59336 8.15596 3.47898 8.1242 3.3625 8.12502Z"
                            fill="#656565" />
                        <path
                            d="M12.975 9.37501L13.2562 9.93126L13.6 10.625H16.25V13.75H11.25V9.37501H12.975ZM13.3625 8.12502H10.625C10.4592 8.12502 10.3003 8.19086 10.1831 8.30807C10.0658 8.42528 10 8.58425 10 8.75001V14.375C10 14.5408 10.0658 14.6997 10.1831 14.817C10.3003 14.9342 10.4592 15 10.625 15H16.875C17.0408 15 17.1997 14.9342 17.3169 14.817C17.4341 14.6997 17.5 14.5408 17.5 14.375V10C17.5 9.83425 17.4341 9.67528 17.3169 9.55807C17.1997 9.44086 17.0408 9.37501 16.875 9.37501H14.375L13.925 8.46876C13.8726 8.36474 13.7921 8.27744 13.6928 8.2167C13.5934 8.15596 13.479 8.1242 13.3625 8.12502Z"
                            fill="#656565" />
                    </svg>
                    <span>Danh mục</span>
                </a>
            </li>
            <li class="newPage">
                <a aria-label="product" href="product.html" rel="nofollow">
                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.59702 4.85085L3.92537 1.50757H12.2836L10.6119 4.85085V7.35831L15 15.7165H1L5.59702 7.35831V4.85085Z"
                            stroke="#656565" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Mới</span>
                </a>
            </li>
            <li class="cartPage">
                <a aria-label="cart" href="cart.html" rel="nofollow">
                    <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.3636 3.97727H10.6591V3.65909C10.6591 1.63864 9.02045 0 7 0C4.97955 0 3.34091 1.63864 3.34091 3.65909V3.97727H0.636364C0.284375 3.97727 0 4.26165 0 4.61364V15.2727C0 15.6247 0.284375 15.9091 0.636364 15.9091H13.3636C13.7156 15.9091 14 15.6247 14 15.2727V4.61364C14 4.26165 13.7156 3.97727 13.3636 3.97727ZM4.77273 3.65909C4.77273 2.42812 5.76903 1.43182 7 1.43182C8.23097 1.43182 9.22727 2.42812 9.22727 3.65909V3.97727H4.77273V3.65909ZM12.5682 14.4773H1.43182V5.40909H3.34091V7.15909C3.34091 7.24659 3.4125 7.31818 3.5 7.31818H4.61364C4.70114 7.31818 4.77273 7.24659 4.77273 7.15909V5.40909H9.22727V7.15909C9.22727 7.24659 9.29886 7.31818 9.38636 7.31818H10.5C10.5875 7.31818 10.6591 7.24659 10.6591 7.15909V5.40909H12.5682V14.4773Z"
                            fill="#656565" />
                    </svg>
                    <span>Giỏ hàng</span>
                </a>
            </li>
            <li class="userPage">
                <a aria-label="signin" href="user/signin.html" class="chat_animation">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.866 13.8593H13.866H13.866ZM13.866 13.8593L13.8647 13.8588L13.866 13.8593ZM12.9943 11.6291L12.9945 11.6297C13.2629 12.2281 13.4254 12.8553 13.4804 13.5H13.0644C12.9413 12.1378 12.3202 10.8758 11.2939 9.90614C10.1426 8.81855 8.61683 8.22362 7 8.22362C5.38317 8.22362 3.85735 8.81855 2.70613 9.90614C1.67978 10.8758 1.05868 12.1378 0.935635 13.5H0.519263C0.573961 12.8581 0.737647 12.2273 1.00529 11.6319L1.00556 11.6313C1.32995 10.9071 1.80055 10.2472 2.39275 9.68894L2.39366 9.68808C2.98915 9.12387 3.68345 8.68135 4.4596 8.37201L4.4626 8.37081L4.4704 8.36791C4.48012 8.36418 4.49409 8.35854 4.51038 8.35084L5.29895 7.97835L4.57908 7.48603C3.54316 6.77755 2.88301 5.63682 2.88301 4.36181C2.88301 2.25481 4.69846 0.5 7 0.5C9.30154 0.5 11.117 2.25481 11.117 4.36181C11.117 5.6369 10.4568 6.7774 9.42134 7.48398L8.70009 7.97616L9.48963 8.34909C9.50592 8.35678 9.51989 8.36242 9.5296 8.36615L9.53751 8.36909L9.5399 8.37005C10.3139 8.6795 11.0155 9.12695 11.6065 9.68646L11.6076 9.68747C12.2001 10.2451 12.6706 10.9049 12.9943 11.6291ZM13.0849 13.8508L13.0849 13.8491L13.0849 13.8508ZM0.49988 13.869L0.499942 13.866L0.49988 13.869ZM9.54737 8.37268C9.54856 8.3731 9.54795 8.37288 9.54708 8.37257L9.54737 8.37268ZM4.39282 6.86396C5.09344 7.52585 6.02162 7.88693 7 7.88693C7.97838 7.88693 8.90656 7.52585 9.60718 6.86396C10.3088 6.20116 10.7021 5.31132 10.7021 4.36181C10.7021 3.4123 10.3088 2.52245 9.60718 1.85966C8.90656 1.19777 7.97838 0.836683 7 0.836683C6.02162 0.836683 5.09344 1.19777 4.39282 1.85966C3.69125 2.52245 3.29789 3.4123 3.29789 4.36181C3.29789 5.31132 3.69125 6.20116 4.39282 6.86396Z"
                            fill="#656565" stroke="#656565" />
                    </svg>
                    <span>Tài khoản</span>
                </a>
            </li>
        </ul>
    </div><input type="hidden" class="fanpageId" value="">
    <style>
        .mini-cart__line-item-list {
            overflow-y: scroll;
            max-height: 375px;
            overflow-x: hidden;
            padding-right: 15px;
        }

        .mini-cart__line-item-list::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: #F5F5F5;
        }

        .mini-cart__line-item-list::-webkit-scrollbar {
            width: 4px;
            background-color: #F5F5F5;
        }

        .mini-cart__line-item-list::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: #d40404;
        }

        .news-item .news-intro {
            height: 72px;
        }

        #blockRate .starbap-rev__content .starbap-rev__body {
            word-break: unset;
        }

        .newIndex .news-info:first-child .news-item .infoNew .title-news {
            height: auto;
        }

        .productInfo .productName {
            height: 20px;
        }

        .newIndex .news-info:first-child .news-item .infoNew .title-news {
            height: auto;
        }

        @media (max-width: 768px) {
            .newIndex .news-info:first-child .news-item .infoNew .date {
                margin: 16px 0 10px;
            }

            .topHeader {
                background: none;
            }

            .topHeader a img {
                display: block;
            }

            .newIndex .news-info:first-child .news-item .infoNew .date {
                margin: 16px 0 10px;
            }
        }
    </style>
    <style>
        @media (max-width:768px) {

            .content p,
            .content p span {
                text-align: justify !important;
            }
        }
    </style>
    <style>
        @media (max-width:768px) {

            .content p,
            .content p span {
                text-align: justify !important;
                white-space: normal !important;
                vertical-align: unset !important;
            }
        }
    </style><!-- Meta Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () {
                n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
            n.queue = []; t = b.createElement(e); t.async = !0;
            t.src = v; s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '232976956040692');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=232976956040692&amp;ev=PageView&amp;noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
    <meta name="facebook-domain-verification" content="4wm3hmbjd4whfc50jv36b3nmif24w1" />
    <meta name="facebook-domain-verification" content="z2hk01srmpluvar43eenpvee6pprpi" />
    <!-- AMIS aiMarketing Embed Code -->
    <script type="text/javascript" env="production" id="ldz-popup-loader" async defer
        src="https://amismisa.misacdn.net/apps/aimarketing/customer/form-editor/static/js/popup-embed.js?companyid=b38a86e1-8926-43cc-b11f-47038df4ed3e"></script>
    <!-- end AMIS aiMarketing Embed Code--><!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "816215858425450");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
        window.fbAsyncInit = function () {
            FB.init({
                xfbml: true,
                version: 'v18.0'
            });
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script><input type="hidden" id="bussinessId" value="108661"><input type="hidden"
        value="K2AIAdflEfa4CuHWtErcQoWevVf2tGqbscFsro7tFm7jAUTbGLyKcKpfrHFGpco8CnpNXbbychULHQOSkDbicXV3bytrxI5UJS5MOqK0maaaJpH2uLfwcelNIwGlggBjCpSIrphDUkHo7bGF"
        id="uctk" name="uctk" /><input type="hidden" id="clienIp" value="1.55.131.196">
    <script id='gg_productView'>
        setTimeout(function () {
            if (typeof dataLayer !== 'undefined') {
                dataLayer.push({
                    'event': 'productDetail',
                    'productId': 34890180,
                    'productName': 'Áo khoác Dạ tay giác lăng cổ đức BE-L'
                });
                dataLayer.push({
                    'event': 'productClick',
                    'ecommerce': {
                        'click': {
                            'actionField': { 'list': 'Search Results' },      // Optional list property.
                            'products': [
                                {
                                    'name': 'Áo khoác Dạ tay giác lăng cổ đức BE-L',
                                    'id': 34890180,
                                    'price': 2455000,
                                    'brand': '',
                                    'category': 542086,
                                    'position': 1,
                                    'variant': '',
                                }
                            ]
                        }
                    },
                    'eventCallback': function () {
                        // document.location = '/ao-khoac-da-tay-giac-lang-co-duc-bel-p34890180.html'
                    }
                });
            }
        }, 1000);
    </script>
    <script id='gg_rmkProductView'>
        gtag('event', 'view_item', {
            'value': 2455000,
            'items': [
                {
                    'id': 34890180,
                    'google_business_vertical': 'retail'
                }
            ]
        });
    </script>
</body>

<!-- Mirrored from jm.com.vn/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 22 Jun 2025 07:47:58 GMT -->

</html>