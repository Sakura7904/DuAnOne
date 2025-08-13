<div>
    <div class="flex items-end justify-between mb-[25px]">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Orders</h2>
            <div class="flex items-center text-xs text-gray-500 gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon"><a class="capitalize" href="?admin=dashboard">home</a></div><img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon"><span class="capitalize text-color-brands">Orders List</span>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-between flex-wrap gap-5 mb-[27px]">
        <div class="flex items-center gap-3">
            <div class="dropdown dropdown-end">
                <label class="cursor-pointer dropdown-label flex items-center justify-between" tabindex="0">
                    <div class="flex items-center justify-between p-4 bg-neutral-bg border border-neutral rounded-lg w-[173px] dark:bg-dark-neutral-bg dark:border-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">Status</p><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-arrow-down.svg" alt="arrow icon">
                    </div>
                </label>
                <ul class="dropdown-content" tabindex="0">
                    <div class="relative menu rounded-box dropdown-shadow w-[173px] bg-neutral-bg pt-[14px] pb-[7px] px-4 border border-neutral-border dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                        <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-transparent right-[18px]"></div>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Sales report</span>
                            </div>
                        </li>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Export report</span>
                            </div>
                        </li>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Profit manage</span>
                            </div>
                        </li>
                        <li class="text-normal mb-[7px]">
                            <div class="flex items-center bg-transparent p-0"><span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Revenue report</span>
                            </div>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg p-[25px] mb-[25px]">
        <div class="flex items-center justify-between pb-4 border-neutral border-b mb-5 dark:border-dark-neutral-border">
            <p class="text-subtitle-semibold font-semibold text-gray-1100 dark:text-gray-dark-1100">Danh sách đơn hàng</p>
            <div class="dropdown dropdown-end ml-auto translate-x-4 z-10">
                <label class="cursor-pointer dropdown-label flex items-center justify-between py-2 px-4" tabindex="0"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-toggle.svg" alt="toggle icon">
                </label>
                <ul class="dropdown-content" tabindex="0">
                    <div class="relative menu rounded-box dropdown-shadow min-w-[126px] bg-neutral-bg mt-[10px] pt-[14px] pb-[7px] px-4 border border-neutral-border  dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                        <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-transparent right-[18px]"></div>
                        <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Sales report</span></a>
                        </li>
                        <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Export report</span></a>
                        </li>
                        <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Profit manage</span></a>
                        </li>
                        <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Revenue report</span></a>
                        </li>
                        <div class="w-full bg-neutral h-[1px] my-[7px] dark:bg-dark-neutral-border"></div>
                        <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#remove"> <span class="text-red text-[11px] leading-4">Remove widget</span></a>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
        <table class="w-full min-w-[900px]">
            <thead>
                <tr class="border-b border-neutral dark:border-dark-neutral-border pb-[15px]">
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Order ID</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Tên người dùng</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Tên sản phẩm</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Phương thức thanh toán</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Ngày mua</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Trạng thái</th>
                    <th class="font-normal text-normal text-gray-400 text-left pb-[15px] dark:text-gray-dark-400">Tổng tiền</th>
                    <th class="font-normal text-normal text-gray-400 text-center pb-[15px] dark:text-gray-dark-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                    <td><span>#25413</span></td>
                    <td class="py-[25px]">
                        <div class="flex items-center gap-2">
                            <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">Bessie Cooper</p>
                        </div>
                    </td>
                    <td>
                        <span>Áo Sơ Mi Lụa Satin Tay Dài - (Trắng - M) × 1</span>
                    </td>
                    <td><span>American Express</span></td>
                    <td><span>17 Oct, 2022</span></td>
                    <td>
                        <div class="flex items-center gap-x-2">
                            <div class="w-2 h-2 rounded-full bg-green"></div>
                            <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">Delivered</p>
                        </div>
                    </td>
                    <td><span>$102.23</span></td>
                    <td>
                        <div class="dropdown dropdown-end w-full">
                            <label class="cursor-pointer dropdown-label flex items-center justify-between p-3" tabindex="0"><img class="mx-auto cursor-pointer" src="./assets/admin/assets/images/icons/icon-more.svg" alt="more icon">
                            </label>
                            <ul class="dropdown-content" tabindex="0">
                                <div class="relative menu rounded-box dropdown-shadow min-w-[126px] bg-neutral-bg mt-[10px] pt-[14px] pb-[7px] px-4 border border-neutral-border dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                                    <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-transparent right-[18px]"></div>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px] show-detail" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">View details</span></a>
                                    </li>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Pending</span></a>
                                    </li>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Completed</span></a>
                                    </li>
                                    <div class="w-full bg-neutral h-[1px] my-[7px] dark:bg-dark-neutral-border"></div>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#remove"> <span class="text-red text-[11px] leading-4">Cancel</span></a>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                    <td class="text-left">
                        <input class="checkbox checkbox-primary rounded border-2 w-[18px] h-[18px] mb-[-6px]" type="checkbox">
                    </td>
                    <td><span>#25413</span></td>
                    <td class="py-[25px]">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full overflow-hidden"><img src="./assets/admin/assets/images/seller-avatar-2.png" alt="user avatar"></div>
                            <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">Gary Simmons</p>
                        </div>
                    </td>
                    <td><span>PayPal</span></td>
                    <td><span>17 Oct, 2022</span></td>
                    <td>
                        <div class="flex items-center gap-x-2">
                            <div class="w-2 h-2 rounded-full bg-orange"></div>
                            <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">Pending</p>
                        </div>
                    </td>
                    <td><span>$206.58</span></td>
                    <td>
                        <div class="dropdown dropdown-end w-full">
                            <label class="cursor-pointer dropdown-label flex items-center justify-between p-3" tabindex="0"><img class="mx-auto cursor-pointer" src="./assets/admin/assets/images/icons/icon-more.svg" alt="more icon">
                            </label>
                            <ul class="dropdown-content" tabindex="0">
                                <div class="relative menu rounded-box dropdown-shadow min-w-[126px] bg-neutral-bg mt-[10px] pt-[14px] pb-[7px] px-4 border border-neutral-border dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                                    <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-transparent right-[18px]"></div>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px] show-detail" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">View details</span></a>
                                    </li>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Pending</span></a>
                                    </li>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Completed</span></a>
                                    </li>
                                    <div class="w-full bg-neutral h-[1px] my-[7px] dark:bg-dark-neutral-border"></div>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#remove"> <span class="text-red text-[11px] leading-4">Cancel</span></a>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr class="border-b text-normal text-gray-1100 border-neutral dark:border-dark-neutral-border dark:text-gray-dark-1100">
                    <td class="text-left">
                        <input class="checkbox checkbox-primary rounded border-2 w-[18px] h-[18px] mb-[-6px]" type="checkbox">
                    </td>
                    <td><span>#25413</span></td>
                    <td class="py-[25px]">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full overflow-hidden"><img src="./assets/admin/assets/images/seller-avatar-3.png" alt="user avatar"></div>
                            <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">Bessie Hank</p>
                        </div>
                    </td>
                    <td><span>Check</span></td>
                    <td><span>17 Oct, 2022</span></td>
                    <td>
                        <div class="flex items-center gap-x-2">
                            <div class="w-2 h-2 rounded-full bg-red"></div>
                            <p class="text-normal text-gray-1100 dark:text-gray-dark-1100">Canceled</p>
                        </div>
                    </td>
                    <td><span>$346.58</span></td>
                    <td>
                        <div class="dropdown dropdown-end w-full">
                            <label class="cursor-pointer dropdown-label flex items-center justify-between p-3" tabindex="0"><img class="mx-auto cursor-pointer" src="./assets/admin/assets/images/icons/icon-more.svg" alt="more icon">
                            </label>
                            <ul class="dropdown-content" tabindex="0">
                                <div class="relative menu rounded-box dropdown-shadow min-w-[126px] bg-neutral-bg mt-[10px] pt-[14px] pb-[7px] px-4 border border-neutral-border dark:text-gray-dark-500 dark:border-dark-neutral-border dark:bg-dark-neutral-bg">
                                    <div class="border-solid border-b-8 border-x-transparent border-x-8 border-t-0 absolute w-[14px] top-[-7px] border-b-transparent right-[18px]"></div>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px] show-detail" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">View details</span></a>
                                    </li>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Pending</span></a>
                                    </li>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#"> <span class="text-gray-500 text-[11px] leading-4 hover:text-gray-700">Completed</span></a>
                                    </li>
                                    <div class="w-full bg-neutral h-[1px] my-[7px] dark:bg-dark-neutral-border"></div>
                                    <li class="text-normal mb-[7px]"><a class="flex items-center bg-transparent p-0 gap-[7px]" href="#remove"> <span class="text-red text-[11px] leading-4">Cancel</span></a>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex items-center gap-x-10">
        <div>
            <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-color-brands font-semibold py-[11px] px-[18px] hover:bg-color-brands">1</button>
            <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">2</button>
            <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">3</button>
            <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">4</button>
            <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">5</button>
        </div><a class="items-center justify-center border rounded-lg border-neutral hidden gap-x-[10px] px-[18px] py-[11px] dark:border-dark-neutral-border sm:flex" href="#"> <span class="text-gray-400 text-xs font-semibold leading-[18px] dark:text-gray-dark-400">Next</span><img src="./assets/admin/assets/images/icons/icon-arrow-right-long.svg" alt="arrow right icon"></a>
    </div>
</div>