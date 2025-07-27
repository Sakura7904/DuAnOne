<!DOCTYPE html>
<html class="scroll-smooth overflow-x-hidden" lang="en">

<head>
    <?php include "views/admin/layout/head.php"; ?>
</head>

<body class="w-screen relative overflow-x-hidden min-h-screen bg-gray-100 scrollbar-hide ecommerce-dashboard-page dark:bg-[#000]">
    <div class="wrapper mx-auto text-gray-900 font-normal grid scrollbar-hide grid-cols-[257px,1fr] grid-rows-[auto,1fr]" id="layout">
        <aside class="bg-white row-span-2 border-r border-neutral relative flex flex-col justify-between p-[25px] dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
            <?php include 'views/admin/layout/sidebar.php'; ?>
        </aside>
        <header class="flex items-center justify-between flex-wrap bg-neutral-bg p-5 gap-5 md:py-6 md:pl-[25px] md:pr-[38px] lg:flex-nowrap dark:bg-dark-neutral-bg lg:gap-0"><a class="hidden logo" href="index.html"><img class="md:mr-[100px] lg:mr-[133px]" src="./assets/admin/assets/images/icons/icon-logo.svg" alt="Frox logo"></a>
            <?php include 'views/admin/layout/header.php'; ?>
        </header>
        <main class="overflow-x-scroll scrollbar-hide flex flex-col justify-between pt-[42px] px-[23px] pb-[28px]">
            <!-- CONTENT -->
            <?php
            $content = 'views/admin/pages/dashboard.php'; // Mặc định
            if (isset($_GET['page'])) {
                $content = 'views/admin/pages/' . $_GET['page'] . '.php';
            }
            ?>
            <div class="">
                <?php include $content; ?>
            </div>
            <footer class="mt-[37px]">
                <?php include 'views/admin/layout/footer.php'; ?>
            </footer>
        </main>
    </div>
    <?php include 'views/admin/layout/script.php'; ?>
</body>

<!-- Mirrored from wp.alithemes.com/html/frox/demos/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 22 Jun 2025 06:45:52 GMT -->

</html>