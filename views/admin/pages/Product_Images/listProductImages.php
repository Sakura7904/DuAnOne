<div class="">
    <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Photo gallery</h2>
    <div class="flex items-center text-xs text-gray-500 gap-x-[11px] mb-[37px]">
        <div class="flex items-center gap-x-1">
            <img src="./assets/admin/assets/images/icons/icon-home-2.svg" alt="home icon">
            <a class="capitalize" href="index.php?act=dashboard">home</a>
        </div>
        <img src="./assets/admin/assets/images/icons/icon-arrow-right.svg" alt="arrow right icon">
        <span class="capitalize text-color-brands">Photo gallery</span>
    </div>

    <section>
        <div class="flex justify-between gap-6 flex-col xl:flex-row">
            <div class="flex flex-col gap-[10px] lg:gap-[27px] xl:w-[25%] md:flex-row xl:flex-col">
                <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl pb-5 flex-1 px-[28px] pt-[35px]">

                    <!-- Form thêm ảnh -->
                    <form action="?act=product_images&action=store" method="POST" enctype="multipart/form-data" id="addImageForm">

                        <!-- Chọn sản phẩm -->
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Thêm ảnh cho sản phẩm</p>
                        <select name="product_id" id="product_id"
                            class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400 mb-4"
                            onchange="loadVariants(this.value)" required>
                            <option value="">Chọn sản phẩm</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>">
                                    <?= htmlspecialchars($product['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Chọn variant -->
                        <select name="variant_id" id="variant_id"
                            class="select w-full border rounded-lg font-normal text-sm leading-4 text-gray-400 py-4 h-fit min-h-fit border-[#E8EDF2] dark:border-[#313442] focus:outline-none pl-[13px] min-w-[252px] dark:text-gray-dark-400 mb-12"
                            required disabled>
                            <option value="">Vui lòng chọn sản phẩm trước</option>
                        </select>

                        <!-- Upload ảnh -->
                        <div class="flex flex-col">
                            <div>
                                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">product gallery</p>

                                <!-- Drop zone -->
                                <div id="drop-zone"
                                    class="border-dashed border-2 text-center mb-4 border-neutral py-[26px] dark:border-dark-neutral-border cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                    onclick="document.getElementById('images').click()">
                                    <img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                                    <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Drop your image here, or browse</p>
                                    <p class="leading-6 text-gray-400 text-[13px]">JPG, PNG, GIF và WEBP files are allowed</p>
                                </div>

                                <!-- Input file ẩn -->
                                <input type="file"
                                    id="images"
                                    name="images[]"
                                    multiple
                                    accept="image/jpeg,image/png,image/gif,image/webp"
                                    class="hidden"
                                    onchange="handleFileSelect(event)">

                                <!-- Preview ảnh đã chọn -->
                                <div id="preview-container" class="flex flex-col mb-12 gap-y-[10px]">
                                    <!-- Ảnh preview sẽ hiển thị ở đây -->
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center gap-x-4 flex-wrap gap-y-4">
                                    <button type="submit"
                                        class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">
                                        Thêm ảnh
                                    </button>
                                    <button type="button"
                                        onclick="clearForm()"
                                        class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 bg-[#E8EDF2] text-[#B8B1E4] hover:!bg-[#bdbec0] hover:text-white dark:bg-[#313442] dark:hover:!bg-[#424242] py-[14px]">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="rounded-2xl border border-neutral bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg overflow-x-scroll scrollbar-hide flex-1 px-[28px] pt-[33px] pb-[23px]">
                <div class="flex items-center justify-between flex-col gap-4 mb-[23px] lg:flex-row">
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Thư viện ảnh</p>
                </div>
                <div class="w-full bg-neutral h-[1px] mb-[10px] dark:bg-dark-neutral-border"></div>
                <table class="w-full min-w-[800px] lg:min-w-fit">
                    <thead>
                        <tr class="text-gray-1100 dark:text-gray-dark-1100">
                            <td></td>
                            <td>Tên sản phẩm</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($images as $image) : ?>
                            <tr>
                                <td>
                                    <input class="checkbox checkbox-primary rounded border-2 w-[18px] h-[18px] translate-y-[3px]" type="checkbox">
                                </td>
                                <td class="py-[10px]">
                                    <div class="flex items-center gap-[13px]"><img class="mr-2" src="<?= htmlspecialchars($image['image_url']) ?>" alt="pdf icon" width="120">
                                        <div class="flex flex-col gap-y-[5px]">
                                            <h4 class="font-semibold leading-4 text-gray-1100 text-[14px] dark:text-gray-dark-1100"><?= $image['product_name'] ?></h4>
                                            <time class="text-xs text-gray-400 dark:text-gray-dark-400">
                                                on <?= date('d/m/Y \\a\\t g:i a', strtotime($image['created_at'])) ?>
                                            </time>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg px-6 dark:border-dark-neutral-bg py-[14px]">Sửa</button>
                                    <button class="btn normal-case h-fit min-h-fit transition-all duration-300 px-6 border-0 text-white bg-[#E23738] hover:!bg-[#ef6364] hover:text-white py-[14px]">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="flex items-center gap-x-10 mt-6">
                    <div>
                        <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-color-brands font-semibold py-[11px] px-[18px] hover:bg-color-brands">1</button>
                        <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">2</button>
                        <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">3</button>
                        <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">4</button>
                        <button class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 bg-transparent font-semibold text-gray-1100 py-[11px] px-[18px] hover:text-white hover:bg-color-brands dark:text-gray-dark-1100">5</button>
                    </div><a class="items-center justify-center border rounded-lg border-neutral hidden gap-x-[10px] px-[18px] py-[11px] dark:border-dark-neutral-border sm:flex" href="#"> <span class="text-gray-400 text-xs font-semibold leading-[18px] dark:text-gray-dark-400">Next</span><img src="./assets/admin/assets/images/icons/icon-arrow-right-long.svg" alt="arrow right icon"></a>
                </div>
            </div>
        </div>
    </section>
    <label class="btn modal-button absolute left-[-1000px]" for="details-modal">details</label>
    <input class="modal-toggle" type="checkbox" id="details-modal">
    <div class="modal">
        <div class="modal-box relative bg-neutral-bg scrollbar-hide dark:bg-dark-neutral-bg">
            <label class="absolute right-2 top-2 cursor-pointer" for="details-modal"><img src="./assets/admin/assets/images/icons/icon-close-modal.svg" alt="close modal button"></label>
            <h6 class="text-header-6 font-semibold text-gray-500 dark:text-gray-dark-500 mb-[49px]">Transaction Details</h6>
            <div class="flex items-center gap-6 mb-10">
                <div> <img src="./assets/admin/assets/images/nasa.png" alt="Nasa logo"></div>
                <div>
                    <p class="text-header-7 font-bold text-gray-1100 mb-2 dark:text-gray-dark-1100">Transfer $68.25 to Nasa.,JSC</p>
                    <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500 mb-[21px]">On 22 January 2022 At 15:39 GMT</p>
                    <div class="border border-neutral bg-transparent flex items-center gap-3 px-5 w-fit rounded-[72px] py-[12px]">
                        <div class="rounded-full w-[10px] h-[10px] bg-green"></div>
                        <p class="font-medium text-xs text-green">Active</p>
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-between mb-[76px]">
                <div class="flex items-start gap-x-[10px]">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#E8EDF2] dark:bg-[#313442]"><img class="dark:invert" src="./assets/admin/assets/images/icons/icon-user.svg" alt="user icon"></div>
                    <div class="flex flex-col gap-y-2">
                        <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">Send to</p>
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">Jane Cooper</p>
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">E-mail: <a href="https://wp.alithemes.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="592d303477333c373730373e2a193c21383429353c773a3634">[email&#160;protected]</a></p>
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">Phone: +099 856 245</p>
                    </div>
                </div>
                <div class="capitalize text-xs text-color-brands py-2 rounded-lg px-[13.5px] bg-[#E8EDF2] dark:bg-[#313442] max-w-[114px]">$ 68,125.25</div>
            </div>
            <div class="flex items-end justify-between mb-2">
                <div class="flex items-start gap-x-[10px]">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#E8EDF2] dark:bg-[#313442]"><img class="dark:invert" src="./assets/admin/assets/images/icons/icon-home-hashtag.svg" alt="home icon"></div>
                    <div class="flex flex-col gap-y-2">
                        <p class="text-sm leading-4 text-gray-1100 font-semibold dark:text-gray-dark-1100">Bank Details</p>
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">967-400 026789758</p>
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">Sparkasse credit</p>
                        <p class="text-sm leading-4 text-gray-500 dark:text-gray-dark-500">Invoice ID: #12546872</p>
                    </div>
                </div>
                <div class="capitalize text-xs text-color-brands py-2 rounded-lg px-[13.5px] bg-[#E8EDF2] dark:bg-[#313442] max-w-[114px]">$250</div>
            </div>
            <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border mb-[67px]"></div>
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-[15px]">
                    <button class="border-0 capitalize font-semibold py-4 px-8 text-gray-500 bg-neutral flex items-center gap-2 text-[12px] leading-[21px] dark:text-gray-dark-500 dark:bg-dark-neutral-border hover:opacity-80 rounded-[10px]">
                        <p>Print</p><i> <img src="./assets/admin/assets/images/icons/icon-printer.svg" alt="print icon"></i>
                    </button>
                    <button class="bg-transparent font-semibold flex items-center text-[12px] leading-[21px] dark:text-gray-dark-500 hover:opacity-80 rounded-[10px] gap-[10px]">
                        <p>Download Pdf</p><i> <img src="./assets/admin/assets/images/icons/icon-down.svg" alt="down icon"></i>
                    </button>
                </div>
                <button class="border-0 capitalize font-semibold py-4 px-8 text-gray-500 bg-neutral flex items-center gap-2 text-[12px] leading-[21px] dark:text-gray-dark-500 dark:bg-dark-neutral-border hover:opacity-80 rounded-[10px]">
                    <p>Issue Refund</p><i> <img src="./assets/admin/assets/images/icons/icon-refunds.svg" alt="print icon"></i>
                </button>
            </div>
        </div>
    </div>
    <label class="btn modal-button absolute left-[-1000px]" for="project-modal">project</label>
    <input class="modal-toggle" type="checkbox" id="project-modal">
    <div class="modal">
        <div class="modal-box relative bg-neutral-bg scrollbar-hide dark:bg-dark-neutral-bg pt-[53px]">
            <label class="absolute right-2 top-2 cursor-pointer" for="project-modal"><img src="./assets/admin/assets/images/icons/icon-close-modal.svg" alt="close modal button"></label>
            <div class="flex items-center justify-center flex-col">
                <h6 class="text-header-6 font-semibold text-gray-500 text-center dark:text-gray-dark-500 mb-[38px]">Create a New Project</h6>
                <div class="cursor-pointer show-add-project-2"><img class="hover:opacity-80 mb-[29px] dark:hidden" src="./assets/admin/assets/images/icons/add-project.svg" width="92" height="92" alt="add project icon"><img class="hidden hover:opacity-80 mb-[29px] dark:block" src="./assets/admin/assets/images/icons/add-project-dark.svg" width="92" height="92" alt="add project icon"></div>
                <p class="text-sm leading-4 text-gray-1100 dark:text-gray-dark-1100 mb-[6px]">Blank project</p>
                <p class="text-desc text-gray-400 dark:text-gray-dark-400 mb-[61px]">Start from scratch</p>
                <div class="flex items-center gap-[6px]">
                    <button class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg w-fit dark:border-dark-neutral-bg py-[7px] px-[16.5px]">Templates</button>
                    <button class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-gray-500 hover:bg-white hover:text-black hover:border-gray-300 dark:hover:border-gray-dark-300 border-neutral-bg w-fit dark:border-dark-neutral-bg py-[7px] px-[19px]">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <label class="btn modal-button absolute left-[-1000px]" for="add-modal">add</label>
    <input class="modal-toggle" type="checkbox" id="add-modal">
    <div class="modal">
        <div class="modal-box relative bg-neutral-bg scrollbar-hide w-full dark:bg-dark-neutral-bg pt-[53px] max-w-[794px]">
            <label class="absolute right-2 top-2 cursor-pointer" for="add-modal"><img src="./assets/admin/assets/images/icons/icon-close-modal.svg" alt="close modal button"></label>
            <div class="flex items-center justify-center flex-col">
                <h6 class="text-header-6 font-semibold text-gray-500 text-center dark:text-gray-dark-500 mb-[50px]">Create a New Project</h6>
                <div class="w-full flex flex-col max-w-[531px] gap-[30px] mb-[60px] lg:mb-[166px]">
                    <div class="border-dashed border-2 text-center border-neutral mx-auto cursor-pointer py-[26px] dark:border-dark-neutral-border w-full max-w-[724px]"><img class="mx-auto inline-block mb-[15px]" src="./assets/admin/assets/images/icons/icon-image.svg" alt="image icon">
                        <p class="text-sm leading-6 text-gray-500 font-normal mb-[5px]">Drop your image here, or browse</p>
                        <p class="leading-6 text-gray-400 text-[13px]">JPG,PNG and GIF files are allowed</p>
                    </div>
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Project Name</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                            <input class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit" type="text" placeholder="Type name here">
                        </div>
                    </div>
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Description</p>
                        <div class="rounded-lg border border-neutral flex flex-col dark:border-dark-neutral-border p-[13px] h-[218px]">
                            <div class="flex items-center gap-y-4 flex-col gap-x-[22px] mb-[31px] xl:flex-row xl:gap-y-0">
                                <div class="flex items-center gap-x-[14px]"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-bold.svg" alt="bold icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-italicized.svg" alt="italicized icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-underlined.svg" alt="underlined icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-strikethrough.svg" alt="strikethrough icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-textcolor.svg" alt="textcolor icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-backgroundcolor.svg" alt="backgroundcolor icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-smile.svg" alt="smile icon"></div>
                                <div class="flex items-center gap-x-[14px]">
                                    <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-paragraphformat.svg" alt="paragraphformat icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                                    <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-align-left.svg" alt="align left icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                                    <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-ordered-list.svg" alt="ordered list icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                                    <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-unordered-list.svg" alt="unordered list icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-indent.svg" alt="indent icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-indent.svg" alt="outdent icon">
                                </div>
                                <div class="flex items-center gap-x-[14px]"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-image.svg" alt="insert image icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-link.svg" alt="insert link icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-file.svg" alt="insert-file icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-video.svg" alt="insert video icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-undo.svg" alt="undo icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-redo.svg" alt="redo icon"></div>
                            </div>
                            <textarea class="textarea w-full p-0 text-gray-400 resize-none rounded-none bg-transparent flex-1 focus:outline-none dark:text-gray-dark-400 placeholder:text-inherit" placeholder="Type description here"></textarea>
                        </div>
                    </div>
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Category</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                            <input class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit" type="text" placeholder="Design system">
                        </div>
                    </div>
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Priority</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                            <input class="input bg-transparent text-sm leading-4 text-gray-400 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-gray-dark-400 placeholder:text-inherit" type="text" placeholder="Urgent">
                        </div>
                    </div>
                    <div class="w-full">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Tag</p>
                        <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-lg p-[15px] mt-[10px] min-h-[107px]">
                            <div class="flex flex-wrap gap-[10px]">
                                <div class="flex items-center py-1 px-2 gap-x-[5px] mb-[10px] bg-[#E8EDF2] dark:bg-[#313442] rounded-[5px]"><span class="text-xs text-gray-400">smartwatch</span><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-close.svg" alt="close icon"></div>
                                <div class="flex items-center py-1 px-2 gap-x-[5px] mb-[10px] bg-[#E8EDF2] dark:bg-[#313442] rounded-[5px]"><span class="text-xs text-gray-400">Apple</span><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-close.svg" alt="close icon"></div>
                                <div class="flex items-center py-1 px-2 gap-x-[5px] mb-[10px] bg-[#E8EDF2] dark:bg-[#313442] rounded-[5px]"><span class="text-xs text-gray-400">Watch</span><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-close.svg" alt="close icon"></div>
                                <div class="flex items-center py-1 px-2 gap-x-[5px] mb-[10px] bg-[#E8EDF2] dark:bg-[#313442] rounded-[5px]"><span class="text-xs text-gray-400">smartphone</span><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-close.svg" alt="close icon"></div>
                                <div class="flex items-center py-1 px-2 gap-x-[5px] mb-[10px] bg-[#E8EDF2] dark:bg-[#313442] rounded-[5px]"><span class="text-xs text-gray-400">iPhone14 max</span><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-close.svg" alt="close icon"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <label class="btn modal-button absolute left-[-1000px]" for="share-modal">share</label>
    <input class="modal-toggle" type="checkbox" id="share-modal">
    <div class="modal">
        <div class="modal-box relative bg-neutral-bg scrollbar-hide w-full dark:bg-dark-neutral-bg pt-[53px] max-w-[738px] pr-[31px]">
            <label class="absolute right-2 top-2 cursor-pointer" for="share-modal"><img src="./assets/admin/assets/images/icons/icon-close-modal.svg" alt="close modal button"></label>
            <div class="flex items-center justify-center flex-col">
                <h6 class="text-header-6 font-semibold text-gray-500 text-center dark:text-gray-dark-500 mb-[53px]">Share Duplicate of Creative requests</h6>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border mb-10"></div>
                <div class="w-full mb-[65px]">
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Invite link</p>
                    <div class="flex items-center justify-between rounded-md bg-neutral py-[11px] px-[10px] dark:bg-dark-neutral-border">
                        <p class="text-sm leading-4 text-gray-400 dark:text-gray-dark-400">foxy.com/ZmRzYSBmZHMgc2RmIHNkYWZzZ</p>
                        <div class="flex items-center text-blue gap-[6px]"><img src="./assets/admin/assets/images/icons/Icon-link.svg" alt="link icon">
                            <p class="text-desc">Copy link</p>
                        </div>
                    </div>
                </div>
                <div class="w-full mb-[24px]">
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Invite with email</p>
                    <div class="flex items-center gap-5">
                        <input class="bg-transparent text-sm leading-4 text-gray-400 border border-neutral flex-1 rounded-md focus:outline-none p-[10px] dark:text-gray-dark-400 placeholder:text-inherit dark:border-dark-neutral-border" type="text" placeholder="Add project members by name or email">
                        <button class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px]">Invite</button>
                    </div>
                </div>
                <div class="w-full flex items-center justify-between mb-[30px]">
                    <div class="flex items-center gap-3"> <a class="block rounded-full border-neutral overflow-hidden border-[1.4px] dark:border-gray-dark-100 w-9 h-9 border-none" href="seller-details.html"><img class="w-full h-full object-cover" src="./assets/admin/assets/images/avatar-layouts-1.png" alt="user avatar"></a>
                        <p class="text-sm leading-4 text-gray-400 dark:text-gray-dark-400">Theresa Webb</p>
                    </div>
                    <p class="text-sm leading-4 text-gray-400 dark:text-gray-dark-400">Owner</p>
                </div>
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border mb-[35px]"></div>
                <div class="w-full mb-[42px]">
                    <p class="text-subtitle font-medium text-gray-1100 mb-8 dark:text-gray-dark-1100">Members</p>
                    <div class="flex flex-col items-center gap-6 w-full">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-3"><a class="block rounded-full border-neutral overflow-hidden border-[1.4px] dark:border-gray-dark-100 w-9 h-9 border-none" href="seller-details.html"><img class="w-full h-full object-cover" src="./assets/admin/assets/images/avatar-layouts-2.png" alt="user avatar"></a>
                                <div>
                                    <p class="text-normal text-gray-1100 mb-[2px] dark:text-gray-dark-1100">Bessie Cooper</p>
                                    <p class="text-desc text-gray-400 dark:text-gray-dark-400"><a href="https://wp.alithemes.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="fa989394929b94ccc8c2ba9d979b9396d4999597">[email&#160;protected]</a></p>
                                </div>
                            </div>
                            <select class="select text-gray-500 pl-1 font-normal h-fit min-h-fit dark:text-gray-dark-500 focus:outline-0 select-caret">
                                <option>Can Edit</option>
                                <option>Can View</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-3"><a class="block rounded-full border-neutral overflow-hidden border-[1.4px] dark:border-gray-dark-100 w-9 h-9 border-none" href="seller-details.html"><img class="w-full h-full object-cover" src="./assets/admin/assets/images/avatar-layouts-3.png" alt="user avatar"></a>
                                <div>
                                    <p class="text-normal text-gray-1100 mb-[2px] dark:text-gray-dark-1100">Cameron Williamson</p>
                                    <p class="text-desc text-gray-400 dark:text-gray-dark-400"><a href="https://wp.alithemes.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="295d5b5c474e42404c475a59425d474d694e4844484045074a4644">[email&#160;protected]</a></p>
                                </div>
                            </div>
                            <select class="select text-gray-500 pl-1 font-normal h-fit min-h-fit dark:text-gray-dark-500 focus:outline-0 select-caret">
                                <option>Can Edit</option>
                                <option>Can View</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-3"><a class="block rounded-full border-neutral overflow-hidden border-[1.4px] dark:border-gray-dark-100 w-9 h-9 border-none" href="seller-details.html"><img class="w-full h-full object-cover" src="./assets/admin/assets/images/avatar-layouts-4.png" alt="user avatar"></a>
                                <div>
                                    <p class="text-normal text-gray-1100 mb-[2px] dark:text-gray-dark-1100">Jacob Jones</p>
                                    <p class="text-desc text-gray-400 dark:text-gray-dark-400"><a href="https://wp.alithemes.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="335e525d5b5b52505b5847030b73545e525a5f1d505c5e">[email&#160;protected]</a></p>
                                </div>
                            </div>
                            <select class="select text-gray-500 pl-1 font-normal h-fit min-h-fit dark:text-gray-dark-500 focus:outline-0 select-caret">
                                <option>Can Edit</option>
                                <option>Can View</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-3"><a class="block rounded-full border-neutral overflow-hidden border-[1.4px] dark:border-gray-dark-100 w-9 h-9 border-none" href="seller-details.html"><img class="w-full h-full object-cover" src="./assets/admin/assets/images/avatar-layouts-5.png" alt="user avatar"></a>
                                <div>
                                    <p class="text-normal text-gray-1100 mb-[2px] dark:text-gray-dark-1100">Arlene McCoy</p>
                                    <p class="text-desc text-gray-400 dark:text-gray-dark-400"><a href="https://wp.alithemes.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="017568646f6d607172716a756f6541666c60686d2f626e6c">[email&#160;protected]</a></p>
                                </div>
                            </div>
                            <select class="select text-gray-500 pl-1 font-normal h-fit min-h-fit dark:text-gray-dark-500 focus:outline-0 select-caret">
                                <option>Can Edit</option>
                                <option>Can View</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-3"><a class="block rounded-full border-neutral overflow-hidden border-[1.4px] dark:border-gray-dark-100 w-9 h-9 border-none" href="seller-details.html"><img class="w-full h-full object-cover" src="./assets/admin/assets/images/avatar-layouts-1.png" alt="user avatar"></a>
                                <div>
                                    <p class="text-normal text-gray-1100 mb-[2px] dark:text-gray-dark-1100">Brooklyn Simmons</p>
                                    <p class="text-desc text-gray-400 dark:text-gray-dark-400"><a href="https://wp.alithemes.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="a0d4d2c1ced4c8d5d98eced5d4c5e0c7cdc1c9cc8ec3cfcd">[email&#160;protected]</a></p>
                                </div>
                            </div>
                            <select class="select text-gray-500 pl-1 font-normal h-fit min-h-fit dark:text-gray-dark-500 focus:outline-0 select-caret">
                                <option>Can Edit</option>
                                <option>Can View</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <label class="btn modal-button absolute left-[-1000px]" for="mail-modal">mail</label>
    <input class="modal-toggle" type="checkbox" id="mail-modal">
    <div class="modal">
        <div class="modal-box relative bg-neutral-bg scrollbar-hide w-full dark:bg-dark-neutral-bg pt-[38px] max-w-[650px] pl-[45px]">
            <label class="absolute right-2 top-2 cursor-pointer" for="mail-modal"><img src="./assets/admin/assets/images/icons/icon-close-modal.svg" alt="close modal button"></label>
            <div class="flex items-center justify-center flex-col">
                <h6 class="w-full text-header-6 font-semibold text-gray-500 dark:text-gray-dark-500 mb-[39px]">New Mesage</h6>
                <div class="flex items-center gap-4 flex-col w-full">
                    <div class="flex items-center rounded-lg border border-neutral justify-between w-full flex-wrap gap-3 py-[13px] px-[10px] dark:border-dark-neutral-border">
                        <div class="flex items-center">
                            <p class="text-sm leading-4 text-gray-400 dark:text-gray-dark-400 pr-5">To:</p>
                            <div class="flex items-center flex-wrap gap-[5px]">
                                <div class="flex items-center rounded gap-[5px] py-[6px] pl-[10px] pr-[5px] bg-neutral dark:bg-dark-neutral-border"><img class="rounded-full w-4 h-4" src="./assets/admin/assets/images/seller-avatar-2.png" alt="user avatar">
                                    <p class="font-medium text-gray-400 text-[10px] leading-[15px] dark:text-gray-dark-400">Steven Job</p>
                                    <svg class="fill-gray-400 cursor-pointer dark:fill-gray-dark-400" width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.20003 8.00001L4.80003 10.4L5.60003 11.2L8.00002 8.80001L10.4 11.2L11.2 10.4L8.80002 8.00001L11.2 5.60001L10.4 4.80001L8.00002 7.20001L5.6 4.79999L4.8 5.59999L7.20003 8.00001Z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center rounded gap-[5px] py-[6px] pl-[10px] pr-[5px] bg-neutral dark:bg-dark-neutral-border"><img class="rounded-full w-4 h-4" src="./assets/admin/assets/images/seller-avatar-3.png" alt="user avatar">
                                    <p class="font-medium text-gray-400 text-[10px] leading-[15px] dark:text-gray-dark-400">Hailen</p>
                                    <svg class="fill-gray-400 cursor-pointer dark:fill-gray-dark-400" width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.20003 8.00001L4.80003 10.4L5.60003 11.2L8.00002 8.80001L10.4 11.2L11.2 10.4L8.80002 8.00001L11.2 5.60001L10.4 4.80001L8.00002 7.20001L5.6 4.79999L4.8 5.59999L7.20003 8.00001Z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center rounded gap-[5px] py-[6px] pl-[10px] pr-[5px] bg-neutral dark:bg-dark-neutral-border"><img class="rounded-full w-4 h-4" src="./assets/admin/assets/images/seller-avatar-4.png" alt="user avatar">
                                    <p class="font-medium text-gray-400 text-[10px] leading-[15px] dark:text-gray-dark-400">Azumi Rose</p>
                                    <svg class="fill-gray-400 cursor-pointer dark:fill-gray-dark-400" width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.20003 8.00001L4.80003 10.4L5.60003 11.2L8.00002 8.80001L10.4 11.2L11.2 10.4L8.80002 8.00001L11.2 5.60001L10.4 4.80001L8.00002 7.20001L5.6 4.79999L4.8 5.59999L7.20003 8.00001Z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <p class="font-medium text-gray-400 cursor-pointer text-[10px] leading-[15px] dark:text-gray-dark-400 hover:text-color-brands dark:hover:text-color-brands">Cc</p>
                            <p class="font-medium text-gray-400 cursor-pointer text-[10px] leading-[15px] dark:text-gray-dark-400 hover:text-color-brands dark:hover:text-color-brands">Bcc</p>
                        </div>
                    </div>
                    <div class="rounded-lg border border-neutral justify-between w-full py-[16px] px-[13px] dark:border-dark-neutral-border">
                        <input class="input bg-transparent text-sm leading-4 text-gray-400 p-0 w-full h-4 rounded-none focus:outline-none dark:text-gray-dark-400 placeholder:text-inherit" type="text" placeholder="Subject">
                    </div>
                    <div class="rounded-lg border border-neutral flex flex-col dark:border-dark-neutral-border p-[13px] h-[262px] w-full">
                        <div class="flex items-center gap-y-4 flex-col gap-x-[22px] mb-[31px] xl:flex-row xl:gap-y-0">
                            <div class="flex items-center gap-x-[14px]"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-bold.svg" alt="bold icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-italicized.svg" alt="italicized icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-underlined.svg" alt="underlined icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-strikethrough.svg" alt="strikethrough icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-textcolor.svg" alt="textcolor icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-backgroundcolor.svg" alt="backgroundcolor icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-smile.svg" alt="smile icon"></div>
                            <div class="flex items-center gap-x-[14px]">
                                <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-paragraphformat.svg" alt="paragraphformat icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                                <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-align-left.svg" alt="align left icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                                <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-ordered-list.svg" alt="ordered list icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div>
                                <div class="flex items-center cursor-pointer gap-x-[1.5px]"><img src="./assets/admin/assets/images/icons/icon-unordered-list.svg" alt="unordered list icon"><img src="./assets/admin/assets/images/icons/icon-arrow-down-triangle.svg" alt="arrow down triangle icon"></div><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-indent.svg" alt="indent icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-indent.svg" alt="outdent icon">
                            </div>
                            <div class="flex items-center gap-x-[14px]"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-image.svg" alt="insert image icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-link.svg" alt="insert link icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-file.svg" alt="insert-file icon"><img class="cursor-pointer" src="./assets/admin/assets/images/icons/icon-insert-video.svg" alt="insert video icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-undo.svg" alt="undo icon"><img class="cursor-pointer opacity-40" src="./assets/admin/assets/images/icons/icon-redo.svg" alt="redo icon"></div>
                        </div>
                        <textarea class="textarea w-full p-0 text-gray-400 resize-none rounded-none bg-transparent flex-1 focus:outline-none dark:text-gray-dark-400 placeholder:text-inherit" placeholder="Content here"></textarea>
                    </div>
                    <div class="flex items-center w-full gap-[15px]">
                        <button class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg font-medium dark:border-dark-neutral-bg py-[7px] px-[24px] text-[12px] leading-[18px]">Send</button>
                        <button class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 border-neutral-bg bg-gray-200 font-medium text-gray-500 dark:border-dark-neutral-bg py-[7px] px-[17px] dark:bg-gray-dark-200 text-[12px] leading-[18px] dark:text-gray-dark-500 hover:bg-gray-200 dark:hover:bg-gray-dark-200 hover:border-gray-300 dark:hover:border-gray-dark-300">Save Darft</button>
                        <p class="text-desc text-gray-400 dark:text-gray-dark-400">Schedule</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript  -->
<script>
    // Load variants khi chọn sản phẩm
    function loadVariants(productId) {
        const variantSelect = document.getElementById('variant_id');

        if (!productId) {
            variantSelect.innerHTML = '<option value="">Vui lòng chọn sản phẩm trước</option>';
            variantSelect.disabled = true;
            return;
        }

        // Disable select và hiển thị loading
        variantSelect.disabled = true;
        variantSelect.innerHTML = '<option value="">Đang tải...</option>';

        // Gửi AJAX request
        fetch('?act=product_images&action=getVariantsByProduct', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + encodeURIComponent(productId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let options = '<option value="">-- Chọn phiên bản --</option>';
                    data.variants.forEach(variant => {
                        const price = variant.sale_price ?
                            `${Number(variant.sale_price).toLocaleString()}đ (Sale)` :
                            `${Number(variant.price).toLocaleString()}đ`;
                        const attributes = variant.attributes ? ` - ${variant.attributes}` : '';
                        options += `<option value="${variant.id}">ID: ${variant.id} - ${price}${attributes}</option>`;
                    });
                    variantSelect.innerHTML = options;
                    variantSelect.disabled = false;
                } else {
                    variantSelect.innerHTML = '<option value="">Không có phiên bản nào</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                variantSelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
            });
    }

    // Xử lý drag & drop
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('images');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');

        const files = e.dataTransfer.files;
        fileInput.files = files;
        handleFileSelect({
            target: {
                files: files
            }
        });
    });

    // Xử lý khi chọn file
    function handleFileSelect(event) {
        const files = event.target.files;
        const container = document.getElementById('preview-container');
        container.innerHTML = '';

        if (files.length > 0) {
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'flex items-center justify-between py-3 border pl-3 pr-3 transition-all duration-300 border-[#E8EDF2] dark:border-[#313442] rounded-[5px] gap-x-[10px] hover:shadow-xl sm:pr-8 lg:pr-3 xl:pr-8';
                        div.innerHTML = `
                        <div class="flex items-center gap-x-3">
                            <img class="w-12 h-12 object-cover rounded" src="${e.target.result}" alt="preview">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" style="max-width: 150px;">${file.name}</p>
                                <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                        </div>
                        <button type="button" 
                                class="delete-btn hover:scale-110 transition-transform duration-200" 
                                onclick="removePreview(this, ${index})">
                            <img src="./assets/admin/assets/images/icons/icon-close-circle.svg" 
                                 alt="close circle icon" 
                                 class="w-6 h-6 hover:opacity-70">
                        </button>
                    `;
                        container.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Xóa preview ảnh
    function removePreview(button, index) {
        button.closest('.flex.items-center').remove();

        // Cập nhật file input (loại bỏ file đã xóa)
        const dt = new DataTransfer();
        const files = fileInput.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }

        fileInput.files = dt.files;
    }

    // Clear form
    function clearForm() {
        document.getElementById('addImageForm').reset();
        document.getElementById('variant_id').disabled = true;
        document.getElementById('variant_id').innerHTML = '<option value="">Vui lòng chọn sản phẩm trước</option>';
        document.getElementById('preview-container').innerHTML = '';
    }

    // Hiển thị thông báo
    <?php if (isset($_SESSION['success'])): ?>
        alert('<?= htmlspecialchars($_SESSION['success']) ?>');
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        alert('Lỗi: <?= htmlspecialchars($_SESSION['error']) ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</script>