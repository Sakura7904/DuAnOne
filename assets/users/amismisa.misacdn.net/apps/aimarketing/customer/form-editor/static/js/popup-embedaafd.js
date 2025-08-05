//#region Các hàm extension của aimarketing
if (typeof globalThis === 'undefined') {
    var globalThis = Function('return this')();
    globalThis = Function('return this')();
}
if (!Element.prototype.aimMatches) {
    Element.prototype.aimMatches =
        Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.aimClosest) {
    Element.prototype.aimClosest = function (s) {
        var el = this;

        do {
            if (Element.prototype.aimMatches.call(el, s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

/**
 * Hàm thực hiện validate khi key down của input dạng số
 * @param {any} textbox element input dạng số
 * @param {any} inputFilter filter cần validate
 * @author PTBinh (21/12/2021)
 */
function setInputFilter(textbox, inputFilter) {
    ['input', 'keydown', 'keyup', 'mousedown', 'mouseup', 'select', 'contextmenu', 'drop'].forEach(
        function (event) {
            for (let index = 0; index < textbox.length; index++) {
                if (textbox[index]) {
                    textbox[index].addEventListener(event, function () {
                        if (inputFilter(this.value)) {
                            this.oldValue = this.value;
                            this.oldSelectionStart = this.selectionStart;
                            this.oldSelectionEnd = this.selectionEnd;
                        } else if (this.hasOwnProperty('oldValue')) {
                            this.value = this.oldValue;
                            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                        } else {
                            this.value = '';
                        }
                    });
                }
            }
        }
    );
}

/**
 * Thực hiện format lại định dạng số trước khi submit
 * @param {string} value Gía trị cần conver
 *@param {string} isPercentType Có phải dữ liệu dạng % không
 * @author TDBA (23/12/2021)
 */
function convertTextNumber(value, isPercentType) {
    try {
        let valueNumber = value.toString();
        const fix = 2; // Số chữ số thập phân được làm tròn

        // Nếu có dấu , trong chuỗi thì thay , thành .
        if (valueNumber.includes(',')) {
            valueNumber = valueNumber.replaceAll(',', '.');
        }

        // Nếu tồn tại dấu . ở đầu thì => định dạng sẽ là 0.xxx
        if (valueNumber.indexOf('.') === 0) {
            valueNumber = '0' + valueNumber;
        } else if (valueNumber.indexOf('.') === valueNumber.length - 1) {
            // Nếu tồn tại dấu . ở cuối thì => định dạng sẽ là xxx.0
            valueNumber = valueNumber + '0';
        }

        valueNumber = Number(parseFloat(valueNumber).toFixed(fix));

        if (isPercentType) return valueNumber / 100;

        return valueNumber;
    } catch (err) {
        return '';
    }
}

/**
 * Hàm render checkbox required
 * @author ptbinh (01/01/2021)
 */
function renderCheckboxRequired() {
    //Loại bỏ required khi đã chọn 1 checkbox
    var listRequiredGroup = document.querySelectorAll('.required-group');

    listRequiredGroup.forEach(function (item) {
        var checkbox = item.querySelectorAll('input[type = "checkbox"]');
        var required = true;
        var lengthCheck = checkbox.length;
        for (var i = 0; i < lengthCheck; i++) {
            if (checkbox[i].hasAttribute('checked')) {
                required = false;
                break;
            }
        }
        if (!required) {
            checkbox.forEach(function (x) {
                x.removeAttribute('required');
            });
        }
    });

    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    if (checkboxes == null) return;
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const elWrapChecks = this.aimClosest('.required-group');
            if (elWrapChecks == null) return;

            let elInputChecked = elWrapChecks.querySelector('input:checked');
            if (elInputChecked !== null) {
                let elements = this.aimClosest('.required-group').querySelectorAll('input');
                if (elements !== null)
                    Array.prototype.forEach.call(elements, function (el, i) {
                        el.removeAttribute('required');
                    });
            }
            else {
                let elRequired = this.aimClosest('.required-group').querySelectorAll('input');
                if (elRequired != null)
                    Array.prototype.forEach.call(elRequired, function (el, i) {
                        el.setAttribute('required', true);
                    });
            }
        });
    });
}

//#endregion


if (!globalThis.lzpr) {

    //Bổ sung landingPageId
    var ldpageId = '';
    var landingPageUcap = document.getElementsByClassName("lpage-ucap");
    if (landingPageUcap.length > 0) {
        ldpageId = landingPageUcap[0].getAttribute("lz-id");
    }

    /**
     * Kiểu Popup
     * */
    const aimktTypePopup = {
        FormPopup: 0,
        Banner: 1
    }

    /**
     * Const dùng trong Form Popup
     * */
    const aimktFormPopupConst = {
        //Config
        Config: {
            TimeAnimationShowPopup: 300,
        },
        //Tên các class name global được sử dụng
        ClassName: {
            DomFormPopup: "form-popup-preview-iframe"
        },
    }

    /**
     * Const dùng trong Banner
     * */
    const aimktBannerConst = {
        //Config
        Config: {
            TimeAnimationShowPopup: 300,
        },
        //Tên các class name global được sử dụng
        ClassName: {
            DomBanner: "banner-preview-iframe"
        },
        //Loại banner
        BannerType: {
            PopupBox: 0, //chính giữa
            DropdownBanner: 1, // Ở trên
            SlideinBoxLeft: 2, // Ở bên trái
            SlideinBoxRight: 3, // Ở bên phải
        },

        //LocalStorage
        LocalStorageName: {
            ListBannerDataStorage: 'aim-banners',
        },

        //Thời gian hiển thị lại Banner
        TimeReDisplayBanner: {
            Day1: 0, //1 Ngày
            Day3: 1, //3 Ngày
            Week1: 2, //1 Tuần
            Week2: 3, //2 Tuần
            Month1: 4 //1 Tháng
        },
        //Thời gian hiển thị lại Banner
        TimeReDisplayBannerMilliseconds: {
            //Day1: 86400, //1 Ngày
            Day1: 86400, //1 Ngày
            Day3: 259200, //3 Ngày
            Week1: 604800, //1 Tuần
            Week2: 1209600, //2 Tuần
            Month1: 2592000 //1 Tháng
        },
        //Loại trigger hiện hiển thị banner
        aimktTriggerDisplay: {
            PageScroll: 0,
            ExitIntent: 1,
            AfterElapsedTime: 2,
        },
    }

    /**
     * Các hằng số liên quan đến Form
     * @author dvhung1 (04/08/2021)
     * */
    const aimktFormConstant = {
        //hiển thị vui lòng chọn trong dropdown
        Dropdown: {
            Message: 'Vui lòng chọn'
        },
        //Các giá trị config
        Config: {
            TimePopupThanks: 10000, //10s hiển thị popup thành công
        },
        //Các loại key của bàn phím
        KeyboardCode: {
            Esc: 27,
        },
        //Loại form
        FormType: {
            RegularForm: 0, // Form thường
            PopupForm: 1, // Form popup
        },
        //Trạng thái của Form
        FormStatus: {
            Draft: 0, //Nháp
            Publish: 1, //Xuất bản
            Scheduled: 2,
        },
        //Loại form popup
        PopupFormType: {
            PopupBox: 0, // Ở giữa
            DropdownBanner: 1, // Ở trên
            SlideinBoxLeft: 2, // Ở bên trái
            SlideinBoxRight: 3, // Ở bên phải
        },
        //Thời gian hiển thị lại Form
        TimeReDisplayForm: {
            Day1: 0, //1 Ngày
            Day3: 1, //3 Ngày
            Week1: 2, //1 Tuần
            Week2: 3, //2 Tuần
            Month1: 4 //1 Tháng
        },
        //Thời gian hiển thị lại Form
        TimeReDisplayFormMilliseconds: {
            //Day1: 86400, //1 Ngày
            Day1: 86400, //1 Ngày
            Day3: 259200, //3 Ngày
            Week1: 604800, //1 Tuần
            Week2: 1209600, //2 Tuần
            Month1: 2592000 //1 Tháng
        },
        //Các bước trong FormPopup
        FormPopupStep: {
            Callout: 1,
            LayoutForm: 2,
            Thanks: 3
        },
        //Các loại Input type
        InputType: {
            Text: 'text',
            Date: 'date',
            Checkbox: 'checkbox',
            Radio: 'radio',
            Textarea: 'textarea',
            File: 'file',
            Number: 'number',
            Email: 'email',
            Submit: 'submit',
        },
        //Các loại feild type
        FieldType: {
            SingleCheckbox: 'scb',
        },
        //Loại cảm ơn sau khi submit thành công
        AfterSubmissionType: {
            ThankYouMsg: 'thankYouMsg', // Cảm ơn bằng văn bản text
            CtnPopup: 'ctnPopup', // Mở popup cảm ơn
            Redirect: 'redirect', // Cảm ơn bằng văn bản text
        },
        //Các hằng attribute của Element
        Attribute: {
            Name: 'name',
            Type: 'type',
            TenantID: 'scope',
            LzID: 'lz-id',
            FieldType: 'field-type',
            Identity: 'identity', //ID của contact property
        },
        //Các hằng utm
        PropertyName: {
            PhoneNumber: 'phoneNumber',
            Email: 'email',
            UtmSource: 'utm_source',
            UtmTerm: 'utm_term',
            UtmMedium: 'utm_medium',
            UtmContent: 'utm_content',
            UtmCampaign: 'utm_campaign',
            Gclid: 'gclid',
            Fbclid: 'fbclid',
            EmailId: 'emailid',
            WorkflowId: 'workflowid',
            ContactId: 'workflowcontactid',
            PhoneNumberCompany: 'so_dien_thoai_co_quan',
            PhoneNumberMobile: 'so_dien_thoai_di_dong',
            Zalo: 'zalo',
            UtmMisaEmployee: "utm_misa_employee",
            UtmMisaSource: "utm_misa_source",
            UtmMisaShareId: "utm_misa_share_id"
        },
        //Cookie
        CookieName: {
            UcapProfileID: 'ucap_profile_id',
            UcapSessionID: 'ucap_session_id',
        },
        //LocalStorage
        LocalStorageName: {
            ListFormDataStorage: 'aim-form-popups',
        },
        //Message
        Message: {
            ErrorEnterPhoneNumber:
                'Vui lòng nhập số điện thoại có ít nhất 10 ký tự chỉ chứa chữ số, + () - . và khoảng trắng',
            ValidateRequired:
                'Vui lòng điền vào trường này',
            ValidateEmail:
                'Định dạng email không hợp lệ',
            ValidatePhoneNumber:
                'Số điện thoại không quá 50 ký tự',
            Watermark:
                'Nền tảng Inbound Marketing số 1 Việt Nam'
        },
        // Định dạng số
        FormatTypeNumber: {
            Number: "number",
            Currency: "currency",
            Percent: "percent"
        },
        //Loại trigger hiện hiển thị form
        aimktTriggerDisplay: {
            PageScroll: 0,
            ExitIntent: 1,
            AfterElapsedTime: 2,
        },
        //license
        License: {
            Trial: 0,
            CheckLicense: 1,
            Unlimited: 2,
        },

        //action callout
        ActionCallOut: {
            GoToSubmitForm: 0,
            CloseCallout: 1
        },

        //template của callout
        TemplateCallout: {
            ImageTop: 1, //Form có ảnh ở trên
            ImageNone: 2, //Form không có ảnh
            ImageOnly: 3, //Form chỉ có ảnh
        }

    };

    /**
     * Các hàm sử dụng chung trong Form
     * @author dvhung1 (04/08/2021)
     * */
    const aimktFormCommonFunc = {
        /**
         * Tạo mới element từ 1 chuỗi nội dung
         * @param {any} htmlString Nội dung
         * @author dvhung1 (04/08/2021)
         */
        createElementFromHTML: function (htmlString) {
            var div = document.createElement('div');
            div.innerHTML = htmlString.trim();

            return div.firstChild;
        },

        /**
         * Thêm style vào ngay canh element
         * @param {any} styles Nội dung style
         * @param {any} element Element muốn thêm vào cạnh
         * @author dvhung1 (04/08/2021)
         */
        createStyleHtml: function (styles, element) {
            var css = document.createElement('style');
            css.type = 'text/css';

            if (css.styleSheet) css.styleSheet.cssText = styles;
            else css.appendChild(document.createTextNode(styles));
            element.appendChild(css);
        },

        /**
         * Thêm Link font google vào ngay element
         * @param {any} fontFamily Tên font
         * @param {any} element Element muốn thêm vào
         * @author dvhung1 (04/08/2021)
         */
        createLinkFontCssGoogle: function (fontFamily, element) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = `https://fonts.googleapis.com/css?family=${fontFamily}`;
            element.appendChild(link);
        },

        /**
         * Tạo dom script vào trong Element
         * @param {any} links Danh sách link
         * @param {any} element Element muốn thêm vào
         * @author dvhung1 (04/08/2021)
         */
        createLinkScript: function (links = [], element) {
            links.forEach((linkUrl) => {
                var link = document.createElement('script');
                link.src = linkUrl;
                element.appendChild(link);
            });
        },

        /**
         * Tạo mới DOM script có nội dung script
         * @param {string} scriptContent Nội dung script
         * @author dvhhung1 (05/08/2021)
         */
        createScript: function (scriptContent) {
            var link = document.createElement('script');
            link.textContent = scriptContent;
            return link;
        },

        /**
         * Hàm empty cho element
         * @param {any} el Element
         * @author dvhung1 (04/08/2021)
         * */
        elementEmpty: function (el) {
            while (el.firstChild) el.removeChild(el.firstChild);
        },

        /**
         * Hàm đợi document load xong hết thì chạy hàm
         * @param {any} fn Hàm cần chạy
         * @author dvhung1 (04/08/2021)
         */
        documentReady: function (fn) {
            if (document.readyState != 'loading') {
                fn();
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        },

        /**
         * Lấy dom của capcha ui
         * @param {any} form Đối tượng Form
         * @author dvhung1 (04/08/2021)
         */
        createDomCapchaUI: function (form) {
            let domParent = this.createElementFromHTML(`<div></div>`);
            let dom = this
                .createElementFromHTML(`<div class='utilities-wrapper' style=" margin-bottom: 16px; ">
                            <div class="g-recaptcha" data-sitekey="6Lc_RjIaAAAAAGS7_RFbfOl3gtD0GSZGjYGaXUWT" data-callback="handleGResponse${form.formID.replaceAll('-', '')}"></div>
                        </div>`);

            let linkScript = document.createElement('script');
            linkScript.src = 'https://www.google.com/recaptcha/api.js';
            linkScript.async = true;
            linkScript.defer = true;

            let linkScriptHandle = document.createElement('script');
            linkScriptHandle.type = 'text/javascript';
            linkScriptHandle.text = `
        function handleGResponse${form.formID.replaceAll('-', '')}(res) {
            if (res && typeof res === 'string') {
                document.querySelector('form[lz-id="${form.formID}"].private-form .submit-wrapper .btn-form-submit:disabled').removeAttribute('disabled')
            }
        }`;

            domParent.appendChild(dom);
            domParent.appendChild(linkScript);
            domParent.appendChild(linkScriptHandle);

            return domParent.children;
        },

        /**
         * Hàm thực hiện convert thêm important cho giá trị của Style
         * @param {any} value giá trị style
         * @author PTBinh (30/07/2021)
         */
        convertImportantStyleValue(value) {
            if (value.toString().includes('important') == true) {
                return value;
            } else {
                return value + ' !important';
            }
        },

        /**
         * Convert Object chứa thông tin style của Form thành nội dung css
         * @param {any} value giá trị style
         * @author dvhung1 (04/08/2021)
         * */
        convertObjectStyleToCss(form) {
            let formStyleStr = '';
            let formSubmitStyleStr = '';
            let formFieldStyleStr = '';
            let formSubmitWrapperStyleStr = '';
            let formLineHeight = '';
            let formRowStyleStr = '';
            let helpTextStyleStr = '';
            let placeholderStyleStr = '';
            let isLabelInInput;
            let labelInInputStyleStr = '';
            let labelStyleStr = '';
            let dimensionInput = '';
            let contentMultiCheck = '';

            //Form
            if (form.style) {
                for (let prop in form.style) {
                    //dvhung1? todo.
                    if (prop == 'line-height') {
                        let styleValue = this.convertImportantStyleValue(form.style[prop]);
                        formLineHeight += `${prop}: ${styleValue};`;
                    }

                    let styleValue = this.convertImportantStyleValue(form.style[prop]);
                    formStyleStr += `${prop}: ${styleValue};`;
                }
            }

            //Feild
            if (form.childrenStyles.formFields) {
                for (let prop in form.childrenStyles.formFields) {
                    let styleValue = this.convertImportantStyleValue(
                        form.childrenStyles.formFields[prop]
                    );
                    formFieldStyleStr += `${prop}: ${styleValue};`;


                    if (prop == 'font-size') {
                        let childrenStyles = form.childrenStyles;
                        if (childrenStyles) {
                            let isPropFormFields = childrenStyles.hasOwnProperty("formFields");
                            let dimensionCss = isPropFormFields ? childrenStyles.formFields[prop] : "14px";
                            dimensionInput =
                                'min-width: ' +
                                dimensionCss +
                                ';' +
                                'min-height: ' +
                                dimensionCss +
                                ';';
                        }
                    }
                    if (prop == 'font-size' || prop == 'color') {
                        let isPropFormFields = form.childrenStyles.hasOwnProperty("formFields");
                        let valueFormFields = isPropFormFields ? form.childrenStyles.formFields[prop] : "";

                        contentMultiCheck +=
                            prop + ': ' + valueFormFields + ';';
                    }
                }
            }

            //Button
            if (form.childrenStyles.submitBtn) {
                for (let prop in form.childrenStyles.submitBtn) {
                    let isPropSubmitBtn = form.childrenStyles.hasOwnProperty("submitBtn");
                    let valueSubmitBtn = isPropSubmitBtn ? form.childrenStyles.submitBtn[prop] : "";

                    let styleValue = this.convertImportantStyleValue(valueSubmitBtn);
                    if (prop === 'width') {
                        if (
                            styleValue.toString().includes('%') ||
                            styleValue.toString().includes('px')
                        ) {
                            formSubmitStyleStr += `${prop}: ${styleValue};`;
                        } else {
                            formSubmitStyleStr += 'width: 100% !important;';
                        }
                    } else {
                        if (prop.indexOf('margin') >= 0 || prop === 'justify-content') {
                            formSubmitWrapperStyleStr += `${prop}: ${styleValue};`;
                        } else {
                            formSubmitStyleStr += `${prop}: ${styleValue};`;
                        }
                    }
                }
            }

            //formRow
            if (form.childrenStyles.formRow) {
                for (let prop in form.childrenStyles.formRow) {
                    let styleValue = this.convertImportantStyleValue(form.childrenStyles.formRow[prop]);
                    formRowStyleStr += `${prop}: ${styleValue};`;
                }
            }

            //help text
            if (form.childrenStyles.helpText) {
                for (let prop in form.childrenStyles.helpText) {
                    let styleValue = this.convertImportantStyleValue(
                        form.childrenStyles.helpText[prop]
                    );
                    helpTextStyleStr += `${prop}: ${styleValue};`;
                }
            }
            else {
                helpTextStyleStr += 'font-size: 13px !important; color: #454545 !important;';
            }

            //placeholder
            if (form.childrenStyles.placeholder) {
                for (let prop in form.childrenStyles.placeholder) {
                    let styleValue = this.convertImportantStyleValue(
                        form.childrenStyles.placeholder[prop]
                    );
                    placeholderStyleStr += `${prop}: ${styleValue};`;
                }
            }
            else {
                placeholderStyleStr += 'font-size: 12px !important; color: #bbbbbb !important;';
            }

            //label
            if (form.childrenStyles.label) {
                for (let prop in form.childrenStyles.label) {
                    labelStyleStr +=
                        prop +
                        ': ' +
                        this.convertImportantStyleValue(form.childrenStyles.label[prop]) +
                        ';';
                }
            }

            //default style
            if (form['DefaultStyles'] == undefined) {
                isLabelInInput = false;
                labelInInputStyleStr = 'display: block !important;';
            } else {
                let defaultValue = JSON.parse(form.DefaultStyles);
                if (defaultValue['LabelInInput'] && defaultValue['LabelInInput'] == true) {
                    isLabelInInput = true;
                    labelInInputStyleStr = 'display: none !important;';
                } else {
                    isLabelInInput = false;
                    labelInInputStyleStr = 'display: block !important;';
                }
            }

            return {
                formStyleStr,
                formSubmitStyleStr,
                formFieldStyleStr,
                formSubmitWrapperStyleStr,
                formLineHeight,
                formRowStyleStr,
                helpTextStyleStr,
                placeholderStyleStr,
                isLabelInInput,
                labelInInputStyleStr,
                labelStyleStr,
                dimensionInput,
                contentMultiCheck
            };
        },

        /**
         * Lấy nội dung style được trong thẻ style
         * @param {any} styleObj Đối tượng chứa tất cả thông tin render ra style
         * @author dvhung1 (04/08/2021)
         */
        getStyleCssContent: function (
            styleObj = {
                form,
                formStyleStr,
                formSubmitWrapperStyleStr,
                formLineHeight,
                formFieldStyleStr,
                formSubmitStyleStr,
                formRowStyleStr,
                helpTextStyleStr,
                placeholderStyleStr,
                isLabelInInput,
                labelInInputStyleStr,
                labelStyleStr,
                dimensionInput,
                contentMultiCheck
            }
        ) {
            let {
                form,
                formStyleStr,
                formSubmitWrapperStyleStr,
                formLineHeight,
                formFieldStyleStr,
                formSubmitStyleStr,
                formRowStyleStr,
                helpTextStyleStr,
                placeholderStyleStr,
                isLabelInInput,
                labelInInputStyleStr,
                labelStyleStr,
                dimensionInput,
                contentMultiCheck
            } = styleObj;

            let style = `
            /* Chỉ chạy riêng với màn hình Editor preview (thay cho media)*/
            @media only screen and (max-width: 767px) {
                .private-form .form-row{
                    flex-direction: column;
                }
            }
           .form-wrapper {
                width: 100%;
                border: none !important;
                height: 100%; 
                overflow: auto !important;
                box-sizing: border-box;
                font-family: Roboto !important;
            }
        
             form[lz-id='${form.formID}'].private-form {
                box-sizing: border-box !important;
                padding: 4px 24px !important;
                width: 100%;
                border: none !important;
                height: 100%;
                overflow: visible !important;
                display: flex !important;
                align-items: flex-start !important;
                flex-direction: column !important;
                margin-bottom: 0px;
                ${formStyleStr};
            }

            form[lz-id='${form.formID}'].private-form .aimkt-layout-form_static .aimkt-layout-form_title{
                ${formRowStyleStr}
            }

            form[lz-id='${form.formID}'].private-form .aimkt-layout-form_static .aimkt-layout-form_content{
                ${formRowStyleStr}
            }
                 form[lz-id='${form.formID}'].private-form .submit-wrapper {
                    border: 1px solid transparent !important;
                    cursor: pointer !important;
                    box-sizing: border-box !important;
                    display: flex !important;
                    line-height: normal;
                    width: 100% !important;
                    ${formSubmitWrapperStyleStr};
                }
        
                     form[lz-id='${form.formID}'].private-form .submit-wrapper .btn-form-submit {
                       /* pointer-events: none;*/
                        padding: 0 16px !important;
                        white-space: normal;
                        work-wrap: break-word;
                    }
                     form[lz-id='${form.formID}'].private-form .submit-wrapper .btn-form-submit:disabled {
                          cursor: not-allowed !important;
                          opacity: .5 !important;
                    }			
        
                 form[lz-id='${form.formID}'].private-form .form-row {
                    display: flex !important;
                    width: 100% !important;
                    align-items: flex-end !important;
                    flex-wrap: nowrap !important;
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    box-sizing: border-box;
                    ${formRowStyleStr};
                }
        
                     form[lz-id='${form.formID}'].private-form .form-row .field-wrapper {
                        display: flex;
                        width: 100% !important;
                        align-items: center !important;
                        flex-wrap: nowrap !important;
                        position: relative !important;
                        border: 1px solid transparent !important;
                        margin-bottom: 0;
                    }
        
                         form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner {
                            display: flex !important;
                            flex: 1 !important;
                            flex-direction: column !important;
                            flex-wrap: nowrap !important;
                        }

                        form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner label{
                            ${labelStyleStr};
                        }
        
                             form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner > * {
                                width: 100%;
                            }
        
                             form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner * {
                               /*pointer-events: none;*/
                               ${formLineHeight};
                            }
        
                             form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner .help-text {
                                padding-top: 8px; !important;
                                color: #9EB3C9 !important;
                                margin-bottom: 8px !important;
                                ${helpTextStyleStr};
                            }
        
                             form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner .lz-input:not([type="radio"]):not([type="checkbox"]) {
                                height: 42px ;
                                padding-left: 8px !important;
                                padding-right: 8px !important;
                                width: 100% ;
                                border: 1px solid #CBD6E2 !important;
                                border-radius: 6px !important;
                                font-family: Roboto !important;
                                ${formFieldStyleStr};
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="scb"] .input span , 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="mcb"] .input span , 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="radio"] .input span {
                                line-height: normal;
                                padding-left: 8px !important;
                                ${contentMultiCheck};
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="scb"] .input input, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="mcb"] .input input, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="radio"] .input input {
                                margin-top: 0px !important;
                                margin: 0px 3px 0px 5px;
                                ${dimensionInput};
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="scb"] .input div, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="mcb"] .input div, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="radio"] .input div {
                                padding-bottom: 12px !important;
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="scb"] .input div:last-child, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="mcb"] .input div:last-child, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="radio"] .input div:last-child {
                                padding-bottom: 0px !important;
                            }


                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="slt"] input {
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner textarea {
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner .lz-input[type="date"] {
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner .lz-input[type="number"] {
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="dropdown"] select {
                                width: 100% !important;
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner .require-mark{
                                color: #eb3333;
                            }


                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="slt"] label, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="mlt"] label, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="number"] label, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="dropdown"] label {
                                ${labelInInputStyleStr}
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="slt"] .help-text, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="mlt"] .help-text, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="number"] .help-text, 
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="dropdown"] .help-text {
                                ${labelInInputStyleStr}
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner[field-type="dropdown"] .input .lz-input {
                                background-position-x: calc(100% - 12px);
                               background-position-y: 12px;
                               -webkit-appearance: none;
                               background-image: url('${globalThis.lzpr.publishedUrl}/content/images/IconDown.svg');
                               background-repeat: no-repeat;
                            }

                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner .lz-input::-webkit-input-placeholder {
                                ${placeholderStyleStr}
                            }
                            
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper [field-type="mcb"] {
                                line-height: normal;
                            }
                            
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper [field-type="radio"] {
                                line-height: normal;
                            }
        
                             form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner textarea {
                                min-height: 80px;
                                padding: 8px !important;
                            }                     
                            form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner input[type="date"] {
                                align-items: center !important;
                                display: -webkit-inline-flex !important;
                                font-family: monospace !important;
                                padding-inline-start: 1px !important;
                                cursor: default !important;
                                overflow: hidden !important;
                                font-size: 13.3333px !important;
                            }
        
                             form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner label {
                                font-weight: normal !important;
                                line-height: 1.2 ;
                                margin-bottom: 0px !important;
                            }
        
                             form[lz-id='${form.formID}'].private-form .form-row .field-wrapper .field-inner .hidden-mark {
                                border: 1px solid #6A78D1 !important;
                                margin: 0 8px !important;
                                padding: 0 4px !important;
                                background-color: #F0F1FA !important;
                            }
        form[lz-id='${form.formID}'] .btn-orange {
            height: 40px !important;
            min-height: 40px !important;
            width: 96px;
            background-color: #FF5637 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 6px !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            font-family: Roboto !important;
            ${formSubmitStyleStr};
        }
       
        `;
            return style;
        },

        /**
         * Hàm để đảm bảo parse json không bị chết chương trình
         * @param {any} jsonString string json
         * @author dvhung1 (04/08/2021)
         */
        safeJsonPare: function (jsonString) {
            try {
                return JSON.parse(jsonString);
            } catch (error) {
                console.error('aimarketing_error parse json - ', error);
                return undefined;
            }
        },
    };

    const lzpr = {
        //develop
        //url: 'https://localhost:44303',
        //STATISTIC: 'https://ladizone.misa.local',

        //local
        publishedUrl: 'https://ladizone.misa.local',
        trackingUrl: 'https://ladizone.misa.local',
        urlCdn: 'https://cdnapps.amispdc.misa.local/apps/aimarketing',

        //testonline
        // url: 'https://test.ladizone.com',
        // STATISTIC: 'https://test.ladizone.com',

        //production
        // url: 'https://ladizone.com',
        // STATISTIC: 'https://ladizone.com',

        timeCallApi: 0, //Thời gian gọi API

        eventTypeBannerClick: 1101,
        eventTypeBannerView: 1100,
        scopeBanner: 11,

        //#region Hàm sử dụng chung của đổi tượng ( Cả form thường và Popup )

        /**
         * ICON thành công mặc định
         * @param {any} fill Màu sắc
         * @author dvhung1 (04/08/2021)
         */
        iconSuccess: function (fill) {
            return `<svg xmlns="http://www.w3.org/2000/svg" width="84" height="84" viewBox="0 0 84 84">
  <g id="Group_44727" data-name="Group 44727" transform="translate(-645 -262)">
    <g id="Ellipse_118" data-name="Ellipse 118" transform="translate(645 262)" fill="none" stroke="#5bd267" stroke-width="4">
      <circle cx="42" cy="42" r="42" stroke="none"/>
      <circle cx="42" cy="42" r="40" fill="none"/>
    </g>
    <g id="check" transform="translate(667.24 285.79)">
      <g id="Group_44726" data-name="Group 44726" transform="translate(4 6)">
        <path id="Path_1" data-name="Path 1" d="M39.519,6,15.1,30.419,4,19.32" transform="translate(-4 -6)" fill="none" stroke="#5bd267" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"/>
      </g>
    </g>
  </g>
</svg>`;
        },

        /**
         * ICON mũi tên đi xuống
         * @param {any} fill Màu sắc
         * @author dvhung1 (04/08/2021)
         */
        arrowDown: function (fill) {
            return `<svg fill=${fill} class="arrow-down" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="306px" height="306px" viewBox="0 0 306 306" style="enable-background:new 0 0 306 306;" xml:space="preserve">
<g>
	<g id="keyboard-arrow-down">
		<polygon points="35.7,58.65 153,175.951 270.3,58.65 306,94.351 153,247.35 0,94.351 		"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>`;
        },

        /**
         * ICON mũi tên đi lên
         * @param {any} fill Màu sắc
         * @author dvhung1 (04/08/2021)
         */
        arrowUp: function (fill) {
            return `<svg fill=${fill} class="arrow-up" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="306px" height="306px" viewBox="0 0 306 306" style="enable-background:new 0 0 306 306;" xml:space="preserve">
<g>
	<g id="keyboard-arrow-down">
		<polygon points="35.7,58.65 153,175.951 270.3,58.65 306,94.351 153,247.35 0,94.351 		"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>`;
        },

        /**
         * Lấy giá trị của cookie theo tên key
         * @param {string} cname Tên key của cookie
         */
        getCookie(cname) {
            var name = cname + '=';
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return '';
        },

        /**
         * Lấy giá trị của param trong url
         * @param {string} name Tên của param muốn lấy
         */
        getUrlParameter(name) {
            var pairs = window.location.search.substring(1).split('&'),
                obj = {},
                pair,
                i;
            let lengthPairs = pairs.length;
            for (var i = 0; i < lengthPairs; i++) {
                if (pairs[i] === '') continue;

                pair = pairs[i].split('=');
                obj[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }

            var newObject = {};
            for (const [key, value] of Object.entries(obj)) {
                newObject[key.toLowerCase()] = value;
            }

            var str = [];
            for (var p in newObject) {
                if (newObject.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + '=' + encodeURIComponent(newObject[p]));
                }
            }
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec('?' + str.join('&'));
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        },

        /**
         * Lấy type của input theo loại field truyền vào
         * @param {int} inputType Loại enum của field
         * @returns Giá trị type của input
         */
        caculateInputType(inputType) {
            switch (inputType) {
                case 0:
                    return 'text';
                case 1:
                    return 'date';
                case 2:
                    return 'checkbox';
                case 3:
                    return 'radio';
                case 4:
                    return 'textarea';
                case 5:
                    return 'file';
                case 6:
                    //dạng number
                    return 'text';
                case 7:
                    return 'email';
            }
        },

        /**
         * Lấy loại màu tối hoặc sáng của màu sắc truyền vào.
         * @param {string} color Giá trị của màu sắc
         */
        lightOrDark(color) {
            // Check the format of the color, HEX or RGB?
            if (color.match(/^rgb/)) {
                // If HEX --> store the red, green, blue values in separate variables
                color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);

                r = color[1];
                g = color[2];
                b = color[3];
            } else {
                // If RGB --> Convert it to HEX: http://gist.github.com/983661
                color = +('0x' + color.slice(1).replace(color.length < 5 && /./g, '$&$&'));

                r = color >> 16;
                g = (color >> 8) & 255;
                b = color & 255;
            }

            // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
            hsp = Math.sqrt(0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b));

            // Using the HSP value, determine whether the color is light or dark
            if (hsp > 160) {
                return 'light';
            } else {
                return 'dark';
            }
        },

        /**
         * Hàm thực hiện update lại isCheck
         * @param options danh sách các option
         * @author PTBinh (07/09/2021)
         * */
        updateIsCheck(options) {
            if (options) {
                let lengthOptions = options.length;
                for (i = 0; i < lengthOptions; i++) {
                    if (options[i]['isCheck'] == undefined) {
                        options[i]['isCheck'] = true;
                    }
                }
            }
            return options;
        },

        /**
        * Render HTML của Form dạng DOM (dvhung1-jquery: ok)
        * @param {object} model Đối tượng Form muốn render
        * @param {boolean} isLandingPage Có phải render vào Landing page không
        */
        renderDomFormByModel(model) {
            let labelInInput;
            if (model['DefaultStyles'] == undefined) {
                labelInInput = false;
            } else {
                var defaultValue = JSON.parse(model.DefaultStyles);
                if (defaultValue['LabelInInput'] == undefined) {
                    labelInInput = false;
                } else {
                    if (defaultValue['LabelInInput'] && defaultValue['LabelInInput'] == true) {
                        labelInInput = true;
                    } else {
                        labelInInput = false;
                    }
                }
            }
            let formWrapper = aimktFormCommonFunc.createElementFromHTML(
                `<div class="form-wrapper"></div>`
            );
            let form = aimktFormCommonFunc.createElementFromHTML(
                `<form class="private-form form-ucap" scope="${model.tenantID}" id="${model.formID}" lz-id="${model.formID}" nameform="${model.FormName}"></form>`
            );
            for (let row of model.rows) {
                if (row.fields[0]['inActive']) continue;
                let formRowUI = aimktFormCommonFunc.createElementFromHTML(
                    '<div class="form-row" style="display: flex; align-items: flex-end;"></div>'
                );
                for (let field of row.fields) {
                    if (field['inputId']) {
                        field.inputName = field.inputId;
                    }
                    let fieldWrapperUI = null;
                    let showLabel;
                    if (field.hideLabel) {
                        showLabel = false;
                    } else {
                        showLabel = true;
                    }
                    if (
                        field.fieldType === 'slt' ||
                        field.fieldType === 'file' ||
                        field.fieldType === 'number'
                    ) {
                        let patternEmail = `^\\s*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}\\s*$`;
                        let fieldUI = `<div class='field-inner' identity='${field.identity
                            }' field-type="${field.fieldType}">
                              ${showLabel
                                ? `<label>
                                  <span class='label-text' style='white-space: normal'>${field.label
                                }</span>
                                  ${field.required ? "<span class='require-mark'>&ast;</span>" : ''}
                              </label>`
                                : ``
                            }
                              <div class='help-text' style="${(field['isEnableDescription'] && field.helpText) ? 'padding-top: 8px' : 'padding-top: 0px'}">${field['isEnableDescription'] ? (field.helpText ? field.helpText : '') : ''}</div>
                              <input data-gjs-type='default' step="any" value='${field.value ? field.value : ''
                            }' ${(field.inputName === 'phoneNumber' ||
                                field.inputName === aimktFormConstant.PropertyName.PhoneNumberMobile ||
                                field.inputName === aimktFormConstant.PropertyName.PhoneNumberCompany ||
                                field.inputName === aimktFormConstant.PropertyName.Zalo)
                                ? 'pattern="^[+]*{0,1}[0-9]{1,4}{0,1}[-\\s\\./0-9]{4,16}[0-9]$" maxlength="50" '
                            : field.inputName === 'email' ? `pattern="` + patternEmail + `"`
                            : ''
                            //: ''
                            }
                                type='${this.caculateInputType(field.inputType)}' placeholder="${field.placeholder ? field.placeholder : ''
                            }"
                                class='lz-input ${field.fieldType === 'number' ? 'aim-text-number' : ''} '  ${field.hidden ? 'hidden="true"' : ''}
                                ${field.inputName === 'email' ||
                                field.inputName === 'fullName' ||
                                field.inputName === 'firstName' ||
                                field.inputName === 'lastName'
                                ? ' maxlength="100" '
                                : ''
                            }
                                 name="${field.inputName}" ${field.required ? "required='true'" : ''
                            }>
                          </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    } else if (field.fieldType === 'mlt') {
                        let fieldUI = `<div class='field-inner' identity='${field.identity
                            }' field-type="${field.fieldType}">
                              ${showLabel
                                ? `<label>
                                  <span class='label-text' style='white-space: normal'>${field.label
                                }</span>
                                 ${field.required ? "<span class='require-mark'>&ast;</span>" : ''}
                              </label>`
                                : ``
                            }
                               <div class='help-text' style="${(field['isEnableDescription'] && field.helpText) ? 'padding-top: 8px' : 'padding-top: 0px'}">${field['isEnableDescription'] ? (field.helpText ? field.helpText : '') : ''}</div>
                              <textarea data-gjs-type='default' placeholder="${field.placeholder ? field.placeholder : ''
                            }"
                                type='${this.caculateInputType(
                                field.inputType
                            )}' class='lz-input'  ${field.hidden ? 'hidden="true"' : ''}
                                name="${field.inputName}" ${field.required ? "required='true'" : ''
                            } >${field.value ? field.value : ''}</textarea>
                          </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    } else if (field.fieldType === 'scb') {
                        field.options = this.updateIsCheck(field.options);
                        let fieldUI = `<div class='field-inner' identity='${field.identity
                            }' field-type="${field.fieldType}">
                          ${showLabel
                                ? `<label>
                              <span class='label-text' style='white-space: normal'>${field.label
                                }</span>
                          </label>`
                                : ``
                            }
                           <div class='help-text' ${field.helpText ? `` : `hidden="true"`} style="${(field['isEnableDescription'] && field.helpText) ? 'padding-top: 8px' : 'padding-top: 0px'}">${field['isEnableDescription'] ? (field.helpText ? field.helpText : '') : ''}</div>
                            <div class='input '>
                                    ${field.options && field.options.length
                                ? field.options
                                    .map(
                                        (item) => item.isCheck ? `
                                       <div style="display: flex;">
                                        <input type='${this.caculateInputType(field.inputType)}' 
                                        ${item.select ? 'checked' : ''} name="${field.inputName
                                            }"  ${field.hidden ? 'hidden="true"' : ''}
                                         value='${item.value}' class='lz-input' ${field.required ? "required='true'" : ''
                                            } data-gjs-type='default' />
                                        <span>${item.label} ${field.required ? `<span class='require-mark' style="color: #eb3333">&ast;</span>` : ''}</span>
                                   </div>` : ``
                                    )
                                    .reduce((acc, next) => acc + next)
                                : ''
                            }
                            </div>
                    </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    } else if (field.fieldType === 'mcb') {
                        field.options = this.updateIsCheck(field.options);
                        let fieldUI = `<div class='field-inner' identity='${field.identity
                            }' field-type="${field.fieldType}">
                          ${showLabel
                                ? `<label>
                              <span class='label-text' style='white-space: normal'>${field.label
                                }</span>
                              ${field.required ? "<span class='require-mark'>&ast;</span>" : ''}
                          </label>`
                                : ``
                            }
                           <div class='help-text' style="${(field['isEnableDescription'] && field.helpText) ? 'padding-top: 8px' : 'padding-top: 0px'}">${field['isEnableDescription'] ? (field.helpText ? field.helpText : '') : ''}</div>
                            <div class='input ${field.required ? 'required-group' : ''}'>
                                    ${field.options && field.options.length
                                ? field.options
                                    .map(
                                        (item) => item.isCheck ? `
                                       <div style="display: flex;">
                                        <input type='${this.caculateInputType(field.inputType)}' 
                                        ${item.select ? 'checked' : ''} name="${field.inputName
                                            }"  ${field.hidden ? 'hidden="true"' : ''}
                                         value='${item.value}' class='lz-input' ${field.required ? "required='true'" : ''
                                            } data-gjs-type='default' />
                                        <span>${item.label}</span>
                                   </div>` : ``
                                    )
                                    .reduce((acc, next) => acc + next)
                                : ''
                            }
                            </div>
                    </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    } else if (field.fieldType === 'radio') {
                        field.options = this.updateIsCheck(field.options);
                        let fieldUI = `<div class='field-inner' identity='${field.identity
                            }' field-type="${field.fieldType}">
                          ${showLabel
                                ? `<label>
                              <span class='label-text' style='white-space: normal'>${field.label
                                }</span>
                             ${field.required ? "<span class='require-mark'>&ast;</span>" : ''}
                          </label>`
                                : ``
                            }
                            <div class='help-text' style="${(field['isEnableDescription'] && field.helpText) ? 'padding-top: 8px' : 'padding-top: 0px'}">${field['isEnableDescription'] ? (field.helpText ? field.helpText : '') : ''}</div>
                            <div class='input'>
                                    ${field.options && field.options.length
                                ? field.options
                                    .map(
                                        (item) => item.isCheck ? `
                                    <div style="display: flex;">
                                        <input type='${this.caculateInputType(field.inputType)}'  
                                        name='${field.inputName}' data-gjs-type='default'
                                         ${item.select ? 'checked' : ''}  ${field.hidden ? 'hidden="true"' : ''
                                            }
                                         value='${item.value}' class='lz-input' ${field.required ? "required='true'" : ''
                                            }/>
                                        <span>${item.label}</span>
                                    </div>` : ``
                                    )
                                    .reduce((acc, next) => acc + next)
                                : ''
                            }
                            </div>
                    </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    } else if (field.fieldType === 'dropdown') {
                        field.options = this.updateIsCheck(field.options);
                        var labelCurrent = field.label;
                        field.options.unshift({
                            label: labelInInput ? labelCurrent : aimktFormConstant.Dropdown.Message,
                            disable: true,
                            selected: true,
                            hidden: true,
                            isCheck: true
                        });

                        let fieldUI = `<div class='field-inner'  identity='${field.identity
                            }' field-type="${field.fieldType}">
                          ${showLabel
                                ? `<label>
                              <span class='label-text' style='white-space: normal'>${field.label
                                }</span>
                              ${field.required ? "<span class='require-mark'>&ast;</span>" : ''}
                          </label>`
                                : ``
                            }
                            <div class='help-text' style="${(field['isEnableDescription'] && field.helpText) ? 'padding-top: 8px' : 'padding-top: 0px'}">${field['isEnableDescription'] ? (field.helpText ? field.helpText : '') : ''}</div>
                            <div class='input'>
                                <select value='${field.value ? field.value : ''}'
                                    type='${this.caculateInputType(field.inputType)}' 
                                    name='${field.inputName}'
                                    data-gjs-type='default' ${field.hidden ? 'hidden="true"' : ''}
                                    class='lz-input' ${field.required ? "required='true'" : ''}>
                                    ${field.options && field.options.length
                                ? field.options
                                    .map((item) => item.isCheck ? (
                                        item.hidden
                                            ? `<option ${item.select ? 'selected' : ''
                                            } hidden value>${item.label
                                            } ${field.required && item.label != aimktFormConstant.Dropdown.Message ? ` *` : ``}</option>`
                                            : `<option ${item.select ? 'selected' : ''
                                            } value='${item.value}'>${item.label
                                            }</option>`) : ``
                                    )
                                    .reduce((acc, next) => acc + next)
                                : ''
                            }
                                </select>
                            </div>
                    </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    } else if (field.fieldType === 'datepicker') {
                        let fieldUI = `<div class='field-inner'  identity='${field.identity
                            }' field-type="${field.fieldType}">
                            ${showLabel
                                ? `<label>
                                <span class='label-text' style='white-space: normal'>${field.label
                                }</span>
                             ${field.required ? "<span class='require-mark'>&ast;</span>" : ''}
                            </label>`
                                : ``
                            }
                           <div class='help-text' style="${(field['isEnableDescription'] && field.helpText) ? 'padding-top: 8px' : 'padding-top: 0px'}">${field['isEnableDescription'] ? (field.helpText ? field.helpText : '') : ''}</div>
                            <input type='date' value='${field.value ? field.value : ''
                            }' placeholder="${field.placeholder ? field.placeholder : ''}"
                            class='lz-input datepicker' ${field.hidden ? 'hidden="true"' : ''
                            } data-gjs-type='default'
                            name="${field.inputName}" ${field.required ? "required='true'" : ''}>
                    </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    } else if (field.fieldType === 'text') {
                        let fieldUI = `<div class='field-inner field-static' identity='${field.identity}' field-type="${field.fieldType}">
                              ${field.content}
                          </div>`;
                        fieldWrapperUI = aimktFormCommonFunc.createElementFromHTML(
                            `<div style="${field.hidden ? 'display: none' : ''
                            }" class='field-wrapper'>${fieldUI}</div>`
                        );
                    }
                    formRowUI.appendChild(fieldWrapperUI);
                }
                form.appendChild(formRowUI);
            }
            const submitWrapper = aimktFormCommonFunc.createElementFromHTML(
                `<div class='submit-wrapper'></div>`
            );
            const submitBtn = aimktFormCommonFunc.createElementFromHTML(`<input type="submit" name="submitButton" value="${model.submitBtn.value}"
                class="btn-orange btn-form-submit">`);

            //let elFormPreviewIframe = document.querySelector('#form-preview-iframe');
            //elFormPreviewIframe.insertBefore(submitBtn, elFormPreviewIframe.firstChild);

            //Cập nhật width cho button
            submitWrapper.style.width = getComputedStyle(submitBtn)['width'];
            submitWrapper.append(submitBtn);
            form.appendChild(submitWrapper);

            if (model.IsEnableCaptcha) {
                let captchaUIs = aimktFormCommonFunc.createDomCapchaUI(model);
                let lengthUI = captchaUIs.length;
                for (let indexCapcha = 0; indexCapcha < lengthUI; indexCapcha++) {
                    form.insertBefore(captchaUIs[0], submitWrapper);
                }
                submitBtn.disabled = true;
            }
            formWrapper.appendChild(form);
            return formWrapper;
        },

        /**
         * Hàm submit form (dvhung1-jquery: ok)
         * @param submission submission
         * @author PTBinh (21/06/2021)
         * */
        getSubmitForm(submission, formObj) {
            let formData = {};
            const PROPERTY_NAME = aimktFormConstant.PropertyName;
            const COOKIE_NAME = aimktFormConstant.CookieName;
            const ATTRIBUTE = aimktFormConstant.Attribute;

            for (var submissionProp of submission.SubmissionProperties) {
                if (submissionProp.InputName) {
                    formData[submissionProp.InputName] = submissionProp.InputName == PROPERTY_NAME.PhoneNumber ? submissionProp.ContactPropertyValue.replace(/[^0-9a-zA-Z ]/g, "").split(" ").join("") : submissionProp.ContactPropertyValue; //0-9a-zA-Z0-9a-zA-Z
                }
            }

            //Bổ sung thông tin utm khi submit form
            let valParamUtmSource = this.getUrlParameter(PROPERTY_NAME.UtmSource);
            if (valParamUtmSource) formData[PROPERTY_NAME.UtmSource] = valParamUtmSource;

            let valParamUtmTerm = this.getUrlParameter(PROPERTY_NAME.UtmTerm);
            if (valParamUtmTerm) formData[PROPERTY_NAME.UtmTerm] = valParamUtmTerm;

            //Format số điện thoại
            let valParamPhoneNumber = this.getUrlParameter(PROPERTY_NAME.PhoneNumber);
            if (valParamPhoneNumber) formData[PROPERTY_NAME.PhoneNumber] = valParamPhoneNumber.replace(/[^0-9a-zA-Z ]/g, "").split(" ").join("");

            let valParamUtmMedium = this.getUrlParameter(PROPERTY_NAME.UtmMedium);
            if (valParamUtmMedium) formData[PROPERTY_NAME.UtmMedium] = valParamUtmMedium;

            let valParamUtmContent = this.getUrlParameter(PROPERTY_NAME.UtmContent);
            if (valParamUtmContent) formData[PROPERTY_NAME.UtmContent] = valParamUtmContent;

            let valParamUtmCampaign = this.getUrlParameter(PROPERTY_NAME.UtmCampaign);
            if (valParamUtmCampaign) formData[PROPERTY_NAME.UtmCampaign] = valParamUtmCampaign;

            let valParamUtmMisaEmployee = this.getUrlParameter(PROPERTY_NAME.UtmMisaEmployee);
            if (valParamUtmMisaEmployee) formData[PROPERTY_NAME.UtmMisaEmployee] = valParamUtmMisaEmployee;

            let valParamUtmMisaSource = this.getUrlParameter(PROPERTY_NAME.UtmMisaSource);
            if (valParamUtmMisaSource) formData[PROPERTY_NAME.UtmMisaSource] = valParamUtmMisaSource;

            let valParamUtmMisaShareId = this.getUrlParameter(PROPERTY_NAME.UtmMisaShareId);
            if (valParamUtmMisaShareId) formData[PROPERTY_NAME.UtmMisaShareId] = valParamUtmMisaShareId;

            let valParamGclid = this.getUrlParameter(PROPERTY_NAME.Gclid);
            if (valParamGclid) formData[PROPERTY_NAME.Gclid] = valParamGclid;

            let valParamFbclid = this.getUrlParameter(PROPERTY_NAME.Fbclid);
            if (valParamFbclid) formData[PROPERTY_NAME.Fbclid] = valParamFbclid;

            let valParamEmailId = this.getUrlParameter(PROPERTY_NAME.EmailId);
            if (valParamEmailId) formData[PROPERTY_NAME.EmailId] = valParamEmailId;

            let valParamWorflowId = this.getUrlParameter(PROPERTY_NAME.WorkflowId);
            if (valParamWorflowId) formData[PROPERTY_NAME.WorkflowId] = valParamWorflowId;

            let valParamContactId = this.getUrlParameter(PROPERTY_NAME.ContactId);
            if (valParamContactId) formData[PROPERTY_NAME.ContactId] = valParamContactId;


            formData = {
                ...formData,
                //ProfileID: cxs['profileId'],
                ProfileID: this.getCookie(COOKIE_NAME.UcapProfileID),
                TenantID: formObj.companyID,
            };

            if (formData[PROPERTY_NAME.Email]) {
                formData[PROPERTY_NAME.Email] = formData[PROPERTY_NAME.Email]?.trim();
            }

            //Thu thập thêm utm

            var contextSessionURL = this.trackingUrl + '/api/ucap/context.json?sessionId=';
            var eventCollectorUrl = this.trackingUrl + '/api/ucap/eventcollector';

            var landingPageKeyFillIdInForm = 'form_lpageid_ucap_key';
            var tenantFillIdInForm = 'form_tenant_ucap_key';
            var scopeForm = 2;
            var eventTypeFormView = 201;
            var eventTypeFormClick = 202;

            //form submit to unomi
            try {
                fetch(eventCollectorUrl, {
                    method: 'POST',
                    headers: {
                        'Content-type': 'text/plain; charset=utf-8',
                        'Access-Control-Allow-Origin': '*',
                    },
                    body: JSON.stringify({
                        events: [
                            {
                                eventType: eventTypeFormClick,
                                scope: scopeForm,
                                source: {
                                    itemType: 'landingPageId',
                                    properties: getPageInfoAimkt(),
                                    itemId: getPageInfoAimkt()['lpageId'],
                                },
                                target: {
                                    itemType: 'formId',
                                    itemId: formObj['formID'],
                                    properties: {
                                        TargetName: formObj['name'],
                                        OwnerID: formObj["OwnerID"],
                                        FormKey: formObj["FormKey"],
                                        FormDataConfig: checkConditionCreateLead(formObj, submission.SubmissionProperties)
                                    },
                                },
                                properties: formData,
                            },
                        ],
                        //sessionId: cxs['sessionId'],
                        sessionId: this.getCookie(COOKIE_NAME.UcapSessionID),
                    }),
                });
            } catch (error) {
                console.error('aimarketing_error GET FORM - ', error);
            }

            /**
             * Hàm thực hiện render ra object thiết lập dữ liệu
             * @param {any} formObj thông tin form
             * @author PTBinh (02/06/2022)
             */
            function getObjectDataConfigLead(formObj) {
                let _formDataConfig = JSON.parse(formObj['FormDataConfig']);
                let _objectDataConfigLead = {
                    IsCreateLead: true,
                    OwnerID: _formDataConfig['OwnerID'],
                    OwnerName: _formDataConfig['OwnerName'],
                    ContactProduct: _formDataConfig['ContactProduct'],
                    IsCreateLead: _formDataConfig['IsCreateLead']
                }

                if (_formDataConfig['IsTransferCRM'] == true) {
                    _objectDataConfigLead['IsTransferCRM'] = true;
                    _objectDataConfigLead['TransferCRMNote'] = _formDataConfig['TransferCRMNote'];
                }
                else {
                    _objectDataConfigLead['IsTransferCRM'] = false;
                }
                return _objectDataConfigLead;
            }

            /**
             * Hàm kiểm tra có thỏa mãn điều kiện tạo lead không
             * @param {any} formObj thông tin form
             * @param {any} submitInfo thông tin submitForm
             * @author PTBinh (03/06/2022)
             */
            function checkConditionCreateLead(formObj, submitInfo) {
                if (!formObj['FormDataConfig'] || formObj['FormDataConfig'] == 'null') {
                    return null;
                }
                else {
                    let _formDataConfig = JSON.parse(formObj['FormDataConfig']);
                    let _formRow = formObj['FormRows'];
                    if (_formDataConfig['IsCreateLead'] == false) {
                        return null;
                    }
                    else {
                        if (_formDataConfig['IsConditionAdvanced'] == false) {
                            return getObjectDataConfigLead(formObj);
                        }
                        else {
                            //danh sách các điều kiện đã set
                            let _conditions = _formDataConfig['ConditionAdvancedDetail'];
                            if (_conditions[0].FieldID == '00000000-0000-0000-0000-000000000000') {
                                return getObjectDataConfigLead(formObj);
                            }

                            //danh sách cách giá trị của các field đã được submit trong form
                            for (var i = 0; i < submitInfo.length; i++) {
                                if (isCreateLeadFromCondition(_conditions, submitInfo[i]) == true) {
                                    return getObjectDataConfigLead(formObj);
                                }
                            }
                            return null;
                        }
                    }
                }
            }

            /**
             * Hàm thực hiện check điều kiện cho từng field của trường được submit
             * @param {any} conditions danh sách cách điều kiện
             * @param {any} propertyDetail thông tin của field được submit
             * @author PTBinh (03/06/2022)
             */
            function isCreateLeadFromCondition(conditions, propertyDetail) {
                const OPERATOR_CONDITION_LEAD = {
                    ANY: 0,
                    CONTAIN: 1,
                    EXACTLY: 2,
                    WITHIN: 3,
                    BEFORE: 4,
                    AFTER: 5,
                }
                if (conditions) {
                    let lengthCondition = conditions.length;
                    let GUID_NULL = '00000000-0000-0000-0000-000000000000';
                    for (var i = 0; i < lengthCondition; i++) {
                        if (conditions[i].FieldID == propertyDetail.ContactPropertyID) {
                            if (conditions[i].FieldType == 'number' || conditions[i].FieldType == 'slt' || conditions[i].FieldType == 'mlt') {
                                let _contactPropertyValue;
                                if (conditions[i].FieldType == 'number') {
                                    let _propertyValue = JSON.parse(propertyDetail.ContactPropertyValue);
                                    if (_propertyValue['formatType'] == aimktFormConstant.FormatTypeNumber.Percent) {
                                        _contactPropertyValue = _propertyValue['value'] * 100;
                                        _contactPropertyValue = _contactPropertyValue.toString();
                                    }
                                    else {
                                        _contactPropertyValue = _propertyValue['value'].toString();
                                    }
                                }
                                else {
                                    _contactPropertyValue = propertyDetail.ContactPropertyValue;
                                }
                                switch (conditions[i].Operator) {
                                    case OPERATOR_CONDITION_LEAD.CONTAIN:
                                        if (_contactPropertyValue.includes(conditions[i].ValueData) == true) {
                                            return true;
                                        }
                                        break;
                                    case OPERATOR_CONDITION_LEAD.EXACTLY:
                                        if (_contactPropertyValue == conditions[i].ValueData) {
                                            return true;
                                        }
                                        break;
                                    case OPERATOR_CONDITION_LEAD.ANY:
                                        return true;
                                }
                            }
                            else if (conditions[i].FieldType == 'dropdown' || conditions[i].FieldType == 'mcb' || conditions[i].FieldType == 'radio') {
                                if (conditions[i].FieldType == 'mcb') {
                                    let lengthContactPropertyValue = propertyDetail.ContactPropertyValue.length;
                                    for (var item = 0; item < lengthContactPropertyValue; item++) {
                                        if (propertyDetail.ContactPropertyValue[item] == conditions[i].ValueData.value) {
                                            return true;
                                        }
                                    }
                                }
                                else {
                                    if (propertyDetail.ContactPropertyValue == conditions[i].ValueData.value) {
                                        return true;
                                    }
                                    else {
                                        if (conditions[i].ValueData.value == GUID_NULL) {
                                            return true;
                                        }
                                    }
                                }
                            }
                            else if (conditions[i].FieldType == 'scb') {
                                if (propertyDetail.ContactPropertyValue == conditions[i].ValueData.value) {
                                    return true;
                                }
                                else {
                                    if (conditions[i].ValueData.value == GUID_NULL) {
                                        return true;
                                    }
                                }
                            }
                            else if (conditions[i].FieldType == 'datepicker') {
                                let timeSubmit;
                                let timeCondition;
                                let endDateCondition;
                                let startDateCondition;
                                switch (conditions[i].Operator) {
                                    case OPERATOR_CONDITION_LEAD.EXACTLY:
                                        timeSubmit = new Date(propertyDetail.ContactPropertyValue).toDateString();
                                        timeCondition = new Date(conditions[i].ValueData.date).toDateString();
                                        if (new Date(timeSubmit).getTime() == new Date(timeCondition).getTime()) {
                                            return true;
                                        }
                                        break;
                                    case OPERATOR_CONDITION_LEAD.WITHIN:
                                        endDateCondition = new Date(conditions[i].ValueData.endDate).toDateString();
                                        startDateCondition = new Date(conditions[i].ValueData.startDate).toDateString();
                                        timeSubmit = new Date(propertyDetail.ContactPropertyValue).toDateString();
                                        if (new Date(endDateCondition).getTime() >= new Date(timeSubmit).getTime() && new Date(startDateCondition).getTime() <= new Date(timeSubmit).getTime()) {
                                            return true;
                                        }
                                        break;
                                    case OPERATOR_CONDITION_LEAD.BEFORE:
                                        timeCondition = new Date(conditions[i].ValueData.date).toDateString();
                                        timeSubmit = new Date(propertyDetail.ContactPropertyValue).toDateString();
                                        if (new Date(timeCondition).getTime() > new Date(timeSubmit).getTime()) {
                                            return true;
                                        }
                                        break;
                                    case OPERATOR_CONDITION_LEAD.AFTER:
                                        timeCondition = new Date(conditions[i].ValueData.date).toDateString();
                                        timeSubmit = new Date(propertyDetail.ContactPropertyValue).toDateString();
                                        if (new Date(timeCondition).getTime() < new Date(timeSubmit).getTime()) {
                                            return true;
                                        }
                                        break;
                                    case OPERATOR_CONDITION_LEAD.ANY:
                                        return true;
                                        break;

                                }
                            }
                        }
                    }
                    return false;
                }
                else {
                    return true;
                }

            }

            /**
             * Hàm lấy thông tin đối tượng Page cơ bản
             * @returns Đối tượng chứa thông tin cơ bản của page
             */
            function getPageInfoAimkt() {
                let elLdpageUcap = document.querySelector('.lpage-ucap[lz-id]');
                let landingPageID = elLdpageUcap === null ? "" : elLdpageUcap.getAttribute(ATTRIBUTE.LzID);

                if (!landingPageID) landingPageID = '';

                return {
                    destinationURL: window.parent.location.href,
                    pagePath: window.parent.location.pathname,
                    pageOrigin: window.parent.location.origin,
                    pageName: window.parent.document.title,
                    referringURL: window.parent.document.referrer,
                    lpageId: landingPageID,
                };
            }
        },

        /**
         * Hàm submit form thường. (dvhung1-jquery: ok)  //Có 1 chút thay đổi. với form thường
         * @param {any} event Sự kiện
         * @param {any} form Đối tượng form submit
         * @param {any} elForm Element form
         * @author dvhung1 (04/08/2021)
         */
        async handleSubmitFormNomal(event, form, elForm) {
            let me = this;
            event.preventDefault();
            const ATTIBUTE = aimktFormConstant.Attribute;
            const COOKIE_NAME = aimktFormConstant.CookieName;
            const INPUT_TYPE = aimktFormConstant.InputType;
            const FORMAT_NUMBER_TYPE = aimktFormConstant.FormatTypeNumber;
            const FEILD_TYPE = aimktFormConstant.FieldType;
            const PROPERTY_NAME = aimktFormConstant.PropertyName;
            const MESSAGE = aimktFormConstant.Message;
            try {

                //không cho user dùng dấu cách để lách qua trường required
                let inputs = elForm.querySelectorAll('div[field-type=slt] > input[required=true]');
                for (let i = 0; i < inputs.length; i++) {
                    let el = inputs[i];
                    if (el?.value?.trim() == '') {
                        return;
                    }
                }

                const submission = {
                    CompanyID: elForm.getAttribute(ATTIBUTE.TenantID),
                    SessionID: me.getCookie(COOKIE_NAME.UcapSessionID),
                    PageName: document.title,
                    ProfileID: me.getCookie(COOKIE_NAME.UcapProfileID),
                    FormID: elForm.getAttribute(ATTIBUTE.LzID),
                    LandingPageID: document.querySelector('body').getAttribute(ATTIBUTE.LzID),
                    PageUrl: window.location.href,
                    SubmissionProperties: [],
                };

                const formatTypeNumber = [];
                let rows = form.FormRows;
                for (let row of rows) {
                    for (let item of row.fields) {
                        if (item.fieldType === INPUT_TYPE.Number) {
                            for (let type of item.options) {
                                if (type.formatType) {
                                    let formatType = type.formatType;
                                    formatTypeNumber.push({ contactPropertyID: item.identity, formatType: formatType });
                                }
                            }
                        }

                    }
                }

                var checkBoxes = {};
                for (let el of elForm.elements) {
                    let typeInput = el.getAttribute(ATTIBUTE.Type);

                    let elFeildType = el.aimClosest('.field-inner');
                    if (typeInput === INPUT_TYPE.Submit || !elFeildType) continue;

                    let feildType = elFeildType.getAttribute(ATTIBUTE.FieldType);

                    let labelContactProp = elFeildType.querySelector('.label-text').textContent;
                    let idContactProp = elFeildType.getAttribute(ATTIBUTE.Identity);
                    let nameInput = el.getAttribute(ATTIBUTE.Name);
                    let inputVal = el["value"];

                    let submitValueObj = {
                        InputName: nameInput,
                        ContactPropertyLabel: labelContactProp,
                        ContactPropertyID: idContactProp,
                    };

                    switch (typeInput) {
                        case INPUT_TYPE.Submit:
                            break;
                        case INPUT_TYPE.Checkbox:
                            if (feildType === FEILD_TYPE.SingleCheckbox) {
                                submission.SubmissionProperties.push({
                                    ContactPropertyValue: el.checked ? true : false,
                                    ...submitValueObj,
                                });
                            } else {
                                if (el.checked) {
                                    //Cho lại required nếu submit
                                    let elRequiredGroup = el.aimClosest('.required-group');
                                    let elRequiredInput = elRequiredGroup === null ? [] : elRequiredGroup.querySelectorAll('input');

                                    if (elRequiredInput.length > 0) {
                                        elRequiredInput.forEach(function (input) {
                                            input.setAttribute('required', true);
                                        });
                                    }

                                    if (!checkBoxes[nameInput]) {
                                        checkBoxes[nameInput] = {
                                            ContactPropertyValue: [inputVal],
                                            ...submitValueObj,
                                        };
                                    } else {
                                        checkBoxes[nameInput].ContactPropertyValue.push(inputVal);
                                    }
                                }
                            }
                            break;

                        case INPUT_TYPE.Date:
                            if (inputVal) {
                                submission.SubmissionProperties.push({
                                    ContactPropertyValue: new Date(inputVal).getTime(),
                                    ...submitValueObj,
                                });
                            }
                            break;
                        case INPUT_TYPE.Radio:
                            if (el.checked) {
                                submission.SubmissionProperties.push({
                                    ContactPropertyValue: inputVal,
                                    ...submitValueObj,
                                });
                            }
                            break;
                        case INPUT_TYPE.File:
                            try {
                                const file = el.files[0];
                                if (file) {
                                    const formData = new FormData();
                                    formData.append('file', file);
                                    let res = await fetch(`${me.url}/api/publications/form-files`, {
                                        method: 'post',
                                        body: formData,
                                    });

                                    let fileUrl = await res.text();
                                    submission.SubmissionProperties.push({
                                        ContactPropertyValue: fileUrl,
                                        ...submitValueObj,
                                    });
                                }
                            } catch (err) {
                                console.log(err);
                            }
                            break;
                        case INPUT_TYPE.Number:
                            if (el.value !== "") {
                                let value = el.value;
                                let format = "";
                                for (let item of formatTypeNumber) {
                                    if (item.contactPropertyID === submitValueObj.ContactPropertyID) {
                                        format = item.formatType;
                                        if (format === FORMAT_NUMBER_TYPE.Percent) {
                                            if (value !== "") {
                                                value = el.value / 100;
                                            }
                                        }
                                    }
                                }

                                submission.SubmissionProperties.push({
                                    ContactPropertyValue: JSON.stringify({
                                        formatType: format,
                                        value: value
                                    }),
                                    ...submitValueObj,
                                });
                            }
                            break;
                        default:
                            if (inputVal) {
                                if (feildType != 'number') {
                                    if (
                                        nameInput === PROPERTY_NAME.PhoneNumber &&
                                        inputVal.length < 10
                                    ) {
                                        el.setCustomValidity(MESSAGE.ErrorEnterPhoneNumber);
                                        return;
                                    }
                                    submission.SubmissionProperties.push({
                                        ContactPropertyValue: el.value,
                                        ...submitValueObj,
                                    });
                                }
                                //trường hợp là dạng number
                                else {
                                    if (el.value !== '') {
                                        let value = el.value;
                                        let format = '';
                                        let fix = 2;
                                        for (let item of formatTypeNumber) {
                                            if (
                                                item.contactPropertyID ===
                                                submitValueObj.ContactPropertyID
                                            ) {
                                                format = item.formatType;
                                            }
                                        }

                                        if (value == '.' || value == ',' || value == '-') {
                                            submission.SubmissionProperties.push({
                                                ContactPropertyValue: '',
                                                ...submitValueObj,
                                            });
                                        } else {
                                            submission.SubmissionProperties.push({
                                                ContactPropertyValue: convertTextNumber(
                                                    value
                                                ),
                                                ...submitValueObj,
                                            });
                                        }
                                    }
                                }

                            }
                            break;
                    }
                }
                for (let item in checkBoxes) {
                    submission.SubmissionProperties.push(Object.assign({}, checkBoxes[item]));
                }

                const formObj = {
                    ...form,
                    name: form.FormName,
                    formType: form.FormType,
                    popupFormType: form.PopupFormType,
                    style: form.Style,
                    childrenStyles: form.ChildrenStyles,
                    submitBtn: form.SubmitBtn,
                    rows: form.FormRows,
                    options: form.FormOptions,
                    modifiedDate: form.ModifiedDate,
                    createdDate: form.CreatedDate,
                    createdBy: form.CreatedBy,
                    formID: form.FormID,
                    companyID: form.TenantID,
                    submissionrate: form.Submissionrate,
                    submissions: form.Submissions,
                    views: form.Views,
                    appeaarson: form.Appeaarson,
                    isEnableCaptcha: form.IsEnableCaptcha,
                };

                me.getSubmitForm(submission, formObj); //dvhung1: todo
                me.handleSubmitFormNomalSussces(event, form); //dvhung1: todo
            } catch (err) {
                console.log(err);
                me.sendNotificationMessage("Error popup-embed.js handleSubmitFormNomal", err);
            }
        },

        /**
         * Hàm convert toàn bộ form data thành Object có thể đọc được. ( Hàm vừa được tạo mới chưa có trong form thường)
         * @param {any} formData
         * @author dvhung1 (25/08/2021)
         */
        convertFormDataParseString(formData) {
            let formObj = Object.assign({}, formData, {
                FormID: formData.FormID,
                FormName: formData.FormName,
                TenantID: formData.TenantID,
                FormType: formData.FormType,
                PopupFormType: formData.PopupFormType,
                Style: typeof (formData.Style) === "string" ? JSON.parse(formData.Style) : formData.Style,
                FormRows: typeof (formData.FormRows) === "string" ? JSON.parse(formData.FormRows) : formData.FormRows,
                ChildrenStyles: typeof (formData.ChildrenStyles) === "string" ? JSON.parse(formData.ChildrenStyles) : formData.ChildrenStyles,
                FormOptions: typeof (formData.FormOptions) === "string" ? JSON.parse(formData.FormOptions) : formData.FormOptions,
                IsEnableCaptcha: typeof (formData.IsEnableCaptcha) === "string" ? JSON.parse(formData.IsEnableCaptcha) : formData.IsEnableCaptcha,
                SubmitBtn: typeof (formData.SubmitBtn) === "string" ? JSON.parse(formData.SubmitBtn) : formData.SubmitBtn,
            });

            return formObj;
        },

        /**
         * Hàm convert toàn bộ banner data thành Object có thể đọc được.
         * @param {any} bannerData object thông tin của banner
         * @author PTBinh (04/03/2022)
         */
        convertBannerDataParseString(bannerData) {
            let bannerObj = Object.assign({}, bannerData, {
                BannerID: bannerData.BannerID,
                BannerName: bannerData.BannerName,
                TenantID: bannerData.TenantID,
                BannerOptions: typeof (bannerData.BannerOptions) === "string" ? JSON.parse(bannerData.BannerOptions) : bannerData.BannerOptions,
                UrlRedirect: bannerData.URLRedirect,
                BannerType: bannerData.BannerType
            });

            return bannerObj;
        },

        /**
         * Hàm thực hiện validate cho email
         * @param {any} elEmail element của email
         * @author PTBinh (25/11/2021)
         */
        validateEmail(elEmail) {
            elEmail.oninvalid = function (e) {
                e.target.setCustomValidity('');
                if (
                    e.target.value.toLowerCase().trim().match(
                        /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/g
                    )
                ) {
                    e.target.setCustomValidity('');
                } else if (e.target.value.length == 0) {
                    e.target.setCustomValidity(aimktFormConstant.Message.ValidateRequired);
                }
                else {
                    e.target.setCustomValidity(aimktFormConstant.Message.ValidateEmail);
                }
            };
            elEmail.oninput = function (e) {
                let required = e.currentTarget.attributes["required"];
                let isRequired = false;
                if (required) {
                    isRequired = required.value;
                }

                if (
                    e.target.value.toLowerCase().trim().match(
                        /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/g
                    )
                ) {
                    e.target.setCustomValidity('');
                } else {
                    if (isRequired == true || isRequired == "true") {
                        e.target.setCustomValidity(aimktFormConstant.Message.ValidateEmail);
                    }
                }
            };
        },

        /**
         * Hàm thực hiện validate số điện thoại
         * @param {any} elIpPhoneNumber element số điện thoại
         * @author PTBinh (25/11/2021)
         */
        validatePhoneNumber(elIpPhoneNumber) {
            elIpPhoneNumber.oninvalid = function (e) {
                e.target.setCustomValidity('');
                if (e.target.value.length == 0 && elIpPhoneNumber.required) {
                    e.target.setCustomValidity(
                        aimktFormConstant.Message.ValidateRequired
                    );
                }
                else if (e.target.value.length > 50) {
                    e.target.setCustomValidity(aimktFormConstant.Message.ValidatePhoneNumber);
                } else if ((e.target.value.length < 10 && e.target.value.length > 0) || !e.target.validity.valid) {
                    e.target.setCustomValidity(
                        aimktFormConstant.Message.ErrorEnterPhoneNumber
                    );
                }
            };
            elIpPhoneNumber.oninput = function (e) {
                elIpPhoneNumber.oninput = function (e) {
                    if ((e.target.value.length == 0 && !elIpPhoneNumber.required)) {
                        e.target.setCustomValidity(aimktFormConstant.Message.ErrorEnterPhoneNumber);
                    }
                    else {
                        e.target.setCustomValidity('');
                    }
                };
            };
        },

        /**
         * Xủ lý logic cha con trong chọn địa điểm
         * @param {any} elForm dom form popup
         * @author DVHAI (15/07/2022)
         */
        handleSelectLocation(elForm) {
            let me = this;

            let elProvinceCity = elForm.querySelector('select[name="provinceCity"]');
            let elDistrict = elForm.querySelector('select[name="district"]');
            let elWard = elForm.querySelector('select[name="phuongxa"]');

            if (elProvinceCity !== null) {
                elProvinceCity.addEventListener('change', function (e) {
                    let name = this.value;
                    me.renderOptionDropdown(elDistrict);
                    me.renderOptionDropdown(elWard);
                    if (elDistrict) {
                        fetch(`${me.publishedUrl}/api/v1/Locations/address-info?kind=2&parentLocationName=${encodeURIComponent(name)}&sort=sortOrder:desc,locationName:asc`, {
                            method: "get",
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        }).then(response => response.json())
                            .then(data => {
                                me.renderOptionDropdown(elDistrict, data);
                            });
                    }
                });
            }

            if (elDistrict !== null) {
                elDistrict.addEventListener('change', function (e) {
                    let name = this.value;
                    me.renderOptionDropdown(elWard);
                    if (elWard) {
                        fetch(`${me.publishedUrl}/api/v1/Locations/address-info?kind=3&parentLocationName=${encodeURIComponent(name)}&sort=sortOrder:desc,locationName:asc&grandLocationName=${encodeURIComponent(elProvinceCity.value)}`, {
                            method: "get",
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        }).then(response => response.json())
                            .then(data => {
                                me.renderOptionDropdown(elWard, data);
                            });
                    }
                });
            }
        },

        /**
       * Hàm onReady khởi tạo form form. (dvhung1-jquery: ok) //Có 1 chút thay đổi. với form thường - Bổ sung param
       * @author dvhung1 (04/08/2021)
       * */
        onReadyFormNomal(form, elForm, formConfig = { isLabelInInput }) {
            const me = this;
            const { isLabelInInput } = formConfig;

            //validate required
            var elements = elForm.getElementsByTagName("INPUT");
            var elementsSelect = elForm.getElementsByTagName("SELECT");
            for (var i = 0; i < elements.length; i++) {
                elements[i].oninvalid = function (e) {
                    e.target.setCustomValidity("");
                    if (!e.target.validity.valid) {
                        e.target.setCustomValidity(aimktFormConstant.Message.ValidateRequired);
                    }
                };
                elements[i].oninput = function (e) {
                    e.target.setCustomValidity('');
                };
            }
            for (var i = 0; i < elementsSelect.length; i++) {
                elementsSelect[i].oninvalid = function (e) {
                    e.target.setCustomValidity("");
                    if (!e.target.validity.valid) {
                        e.target.setCustomValidity(aimktFormConstant.Message.ValidateRequired);
                    }
                };
                elementsSelect[i].oninput = function (e) {
                    e.target.setCustomValidity("");
                };
            }

            //validate email
            if (elForm.querySelector('[name="email"]') !== null || elForm.querySelector('[name="email_co_quan"]') !== null) {
                let elEmail = elForm.querySelector('[name="email"]');
                me.validateEmail(elEmail);
            }

            //validate email cơ quan
            if (elForm.querySelector('[name="email_co_quan"]') !== null) {
                let elEmail = elForm.querySelector('[name="email_co_quan"]');
                me.validateEmail(elEmail);
            }

            //Validate số điện thoại
            if (elForm.querySelector('[name="phoneNumber"]') !== null) {
                let elIpPhoneNumber = elForm.querySelector('[name="phoneNumber"]');
                me.validatePhoneNumber(elIpPhoneNumber);
            }

            //validate zalo
            if (elForm.querySelector('[name="zalo"]') !== null) {
                let elIpPhoneNumber = elForm.querySelector('[name="zalo"]');
                me.validatePhoneNumber(elIpPhoneNumber);
            }

            //validate số điện thoại cơ quan
            if (elForm.querySelector('[name="so_dien_thoai_co_quan"]') !== null) {
                let elIpPhoneNumber = elForm.querySelector('[name="so_dien_thoai_co_quan"]');
                me.validatePhoneNumber(elIpPhoneNumber);
            }

            //validate số điện thoại di động
            if (elForm.querySelector('[name="so_dien_thoai_di_dong"]') !== null) {
                let elIpPhoneNumber = elForm.querySelector('[name="so_dien_thoai_di_dong"]');
                me.validatePhoneNumber(elIpPhoneNumber);
            }

            //set Label vào trong placeholder
            if (isLabelInInput) {
                let formRow = form.FormRows;
                let lengthFormRow = formRow.length;

                let elFormRows = elForm.querySelectorAll('.field-wrapper .field-inner');
                elFormRows.forEach(function (elFormRow) {
                    if (
                        elFormRow.getAttribute('field-type') == 'slt' ||
                        elFormRow.getAttribute('field-type') == 'mlt' ||
                        elFormRow.getAttribute('field-type') == 'number'
                    ) {
                        let placeholder = '';
                        for (let i = 0; i < lengthFormRow; i++) {
                            if (formRow[i].fields) {
                                if (formRow[i].fields.length) {
                                    if (
                                        formRow[i].fields[0].inputName ==
                                        elFormRow.getElementsByClassName('lz-input')[0].getAttribute('name')
                                    ) {
                                        placeholder = formRow[i].fields[0].placeholder;
                                        break;
                                    }
                                }
                            }
                        }
                        let required = elFormRow.getElementsByClassName('lz-input')[0].getAttribute('required');
                        let labelName = elFormRow
                            .getElementsByTagName('label')[0]
                            .getElementsByClassName('label-text')[0].innerText;
                        elFormRow.getElementsByClassName('lz-input')[0].placeholder = isLabelInInput
                            ? required ? labelName + ' *' : labelName
                            : placeholder;
                    }
                });
            }

            //Loại bỏ required khi đã chọn 1 checkbox
            renderCheckboxRequired();

            elForm.addEventListener('submit', async function (event) {
                me.handleSubmitFormNomal(event, form, elForm);
            });

            //validate khi nhập vào input dạng số
            setInputFilter(document.getElementsByClassName('aim-text-number'), function (value) {
                return /^-?\d*[.,]?\d*$/.test(value);
            });

            //Thêm hành động xử lý logic chọn cha con trong location
            me.handleSelectLocation(elForm);
        },

        //#endregion

        //#region CSS resource

        /**
         * CSS với hiệu ứng animation
         * @author dvhung1 (22/10/2021)
         * */
        resourceCSSAnimation() {
            const css = `.animated {
            -webkit-animation-duration: 500ms;
            animation-duration: 500ms;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            opacity: 0;
        }

        /* fadeInDown */
        @-webkit-keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -100%, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -100%, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .fadeInDown {
            -webkit-animation-name: fadeInDown;
            animation-name: fadeInDown;
        }


        /* fadeInLeft */
        @-webkit-keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translate3d(-100%, 0, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translate3d(-100%, 0, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .fadeInLeft {
            -webkit-animation-name: fadeInLeft;
            animation-name: fadeInLeft;
        }

        /* fadeInRight  */
        @-webkit-keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translate3d(100%, 0, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translate3d(100%, 0, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .fadeInRight {
            -webkit-animation-name: fadeInRight;
            animation-name: fadeInRight;
        }
         @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fadeIn {
            animation-name: fadeIn;
        }
`;
            return css;
        },

        /**
         * CSS chung của form popup
         * @author dvhung1 (22/10/2021)
         * */
        resourceCSSMountExternalPopupForm() {
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            const css = `
                .lz-iframe-ctn{
                    overflow: auto;
                    z-index: 16000009;
                }

                .lz-iframe-ctn *{
                    box-sizing: border-box;
                }
            
                .lz-iframe-ctn .lz-iframe-ctn__overlay{
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;

                    background-color: rgba(0,0,0,0.5);
                    z-index: -1;
                }
    
                #${CLASS_NAME_FORM_POPUP}{
                    font-family: 'Google Sans';
                }

                .lz-iframe-ctn .lz-iframe-ctn__overlay.hidden,
                .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}.hidden,
                .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP} .hidden{
                    display: none !important;
                }

                .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP} *{
                    box-sizing: border-box;
                }

                @media (max-width: 600px){
                    .lz-iframe-ctn.lz-iframe-ctn{
                        height: unset !important;
                        overflow-y: auto;
                        bottom: 0;
                        top: 60px !important;
                        left: 0;
                        right: 0;
                        margin-left: auto;
                        margin-right: auto;
                        width: fit-content;
                    }
                 }
                `;
            return css;
        },

        /**
         * CSS chung của banner
         * @author PTBinh (04/03/2022)
         * */
        resourceCSSMountExternalBanner() {
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            const css = `
                .lz-iframe-ctn{
                    overflow: auto;
                    z-index: 16000009;
                }

                .lz-iframe-ctn *{
                    box-sizing: border-box;
                }
            
                .lz-iframe-ctn .lz-iframe-ctn__overlay{
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;

                    background-color: rgba(0,0,0,0.5);
                    z-index: -1;
                }
    
                .lz-iframe-ctn .lz-iframe-ctn__overlay.hidden,
                .lz-iframe-ctn #${CLASS_NAME_BANNER}.hidden,
                .lz-iframe-ctn #${CLASS_NAME_BANNER} .hidden{
                    display: none !important;
                }

                .lz-iframe-ctn #${CLASS_NAME_BANNER} *{
                    box-sizing: border-box;
                }

                .lz-iframe-ctn #${CLASS_NAME_BANNER} *{
                    border-radius: 8px;
                }

                @media (max-width: 600px){
                    .lz-iframe-ctn.lz-iframe-ctn{
                        height: unset !important;
                        overflow-y: auto;
                        bottom: 0;
                        //top: 60px !important;
                        left: 0;
                        right: 0;
                        margin-left: auto;
                        margin-right: auto;
                        width: 100%;
                    }
                 }
                `;
            return css;
        },
        //#endregion

        /**
         * Convert form data thành dạng Obj
         * Data từ dạng string JSON thành json
         * Data vói tên trường API thay đối về Propety cũ theo method cũ
         * @param {any} formData Dữ liệu raw lấy từ API
         * @author dvhung1 (13/08/2021)
         */
        convertFormDataToObj(formData) {
            let formObj = Object.assign({}, formData, {
                formID: formData.FormID,
                formName: formData.FormName,
                tenantID: formData.TenantID,
                companyID: formData.TenantID,
                formType: formData.FormType,
                popupFormType: formData.PopupFormType,
                style: formData.Style,
                rows: formData.FormRows,
                childrenStyles: formData.ChildrenStyles,
                options: formData.FormOptions,
                submitBtn: formData.SubmitBtn,
                isEnableCaptcha: formData.IsEnableCaptcha,
                isEnableDescription: formData['isEnableDescription']
            });

            return formObj;
        },

        /**
         * Hàm Khởi tạo sự kiện TRIGGER hiển thị Form Popup
         * @param {any} forms Đối tượng Form muốn hiển thị
         * @param {any} publishedUrl Đường dẫn của published page
         * @param {any} trackingUrl Đường dẫn của tracking ucap
         * @param {boolean} isPreview Có phải form preview hay không
         * @author dvhung1 (13/08/2021)
         */
        async renderExternalPopupForm(forms, publishedUrl, trackingUrl, isPreview = false) {
            const that = this;
            try {
                if (!(forms && forms.length > 0)) return;

                that.publishedUrl = publishedUrl;
                that.trackingUrl = trackingUrl;

                forms.forEach((formItem) => {
                    const formEntity = formItem;
                    const form = that.convertFormDataParseString(formEntity);
                    const formOptions = form.FormOptions;

                    //Kiểm tra điều kiện hiển thị với màn hình nhỏ:
                    let isDisableWithSmallScreen = formOptions["option"] ? formOptions["option"]['disableWithSmallScreen'] : "";
                    if (isDisableWithSmallScreen) {
                        const widthWindow = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
                        if (widthWindow < 1024) return;
                    }

                    if (isPreview) {
                        //Hiển thị ngay không cần triggerConditions cho trang Xem trước
                        that.mountExternalPopupForm(form);
                    } else {
                        //Khỏi tạo các điều kiện trigger để hiện thị Form Popup
                        if (formOptions.triggerConditions && isAutoShow) {
                            const {
                                pageScroll,
                                exitIntent,
                                afterElapsedTime,
                            } = formOptions.triggerConditions;
                            if (pageScroll) {
                                if (pageScroll.active) {
                                    document.body.addEventListener('scroll', function (event) {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                                        if (!isCheckTimeShowForm) return;

                                        that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.PageScroll);

                                        const scrollHeight = document.querySelector('body').scrollHeight;
                                        if ((document.body.scrollTop / (scrollHeight - document.querySelector('body').offsetHeight)) * 100 >= pageScroll.value) {
                                            that.mountExternalPopupForm(form);
                                        }
                                    });
                                    window.addEventListener('scroll', function (event) {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                                        if (!isCheckTimeShowForm) return;

                                        that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.PageScroll);
                                        const scrollHeight = document.querySelector('body')
                                            .scrollHeight;
                                        if ((window.scrollY / (scrollHeight - window.outerHeight)) * 100 >= pageScroll.value) {
                                            that.mountExternalPopupForm(form);
                                        }
                                    });
                                }
                            }
                            if (exitIntent) {
                                if (exitIntent.active) {
                                    document.addEventListener('mouseleave', function (event) {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                                        if (!isCheckTimeShowForm) return;

                                        that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.ExitIntent);
                                        that.mountExternalPopupForm(form);
                                        event.stopPropagation && event.stopPropagation();
                                    });
                                }
                            }

                            if (afterElapsedTime) {
                                if (afterElapsedTime.active) {
                                    let timeOut = afterElapsedTime.value * 1000 - that.timeCallApi;
                                    timeOut = timeOut > 0 ? timeOut : 0;
                                    setTimeout(function () {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                                        if (!isCheckTimeShowForm) return;

                                        that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.AfterElapsedTime);
                                        that.mountExternalPopupForm(form);
                                    }, timeOut);
                                }
                            }
                        } else {
                            that.mountExternalPopupForm(form);
                        }

                        //thực hiện check click vào Cta để hiển thị form Popup
                        this.showPopupWithCta(form);
                    }
                });

            } catch (err) {
                console.log(err);
            }
        },

        /**
         * Hàm thực hiện kiểm tra xem click vào cta có hiện form popup không
         * @param {any} form thông tin form
         * @author PTBinh (12/09/2022)
         */
        showPopupWithCta(form) {
            const that = this;
            let formOptions = form.FormOptions;
            let infoClickCta = formOptions.triggerConditions['clickCta'];
            if (infoClickCta && infoClickCta['active']) {
                let values = infoClickCta['value'];

                if (values && values.length > 0) {
                    values.forEach((item) => {
                        let override = item['override'];

                        //trường hợp element là id
                        if (item.type.toLowerCase() == 'id') {
                            let listIds = document.querySelectorAll(`[id^="${item.value}"]`);
                            if (listIds && listIds.length > 0) {
                                listIds.forEach((item) => {
                                    //trường hợp có đè event của element
                                    if (override) {
                                        item.removeAttribute('href');
                                        item.removeAttribute('ai-event-type');
                                        item.addEventListener("click", function () { that.mountExternalPopupForm(form); });
                                    }
                                    //trường hợp không đè event của element
                                    else {
                                        item.addEventListener("click", function () { that.mountExternalPopupForm(form); });
                                    }
                                })

                            }
                        }

                        //trường hợp element là class
                        else if (item.type.toLowerCase() == 'class') {
                            let listClass = document.getElementsByClassName(item.value);
                            if (listClass && listClass.length > 0) {
                                let lengthClass = listClass.length;
                                for (var i = 0; i < lengthClass; i++) {
                                    //trường hợp có đè event của element
                                    if (override) {
                                        listClass[i].removeAttribute('href');
                                        listClass[i].removeAttribute('ai-event-type');
                                        listClass[i].addEventListener("click", function () { that.mountExternalPopupForm(form); });
                                    }
                                    //trường hợp không đè event của element
                                    else {
                                        listClass[i].addEventListener("click", function () { that.mountExternalPopupForm(form); });
                                    }
                                }
                            }
                        }
                    })
                }
            }
        },

        /**
         * Hàm Khởi tạo sự kiện TRIGGER hiển thị Banner
         * @param {any} banners Đối tượng Form muốn hiển thị
         * @param {any} publishedUrl Đường dẫn của published page
         * @param {any} trackingUrl Đường dẫn của tracking ucap
         * @param {boolean} isPreview Có phải banner preview hay không
         * @author PTBinh (04/03/2022)
         */
        async renderExternalBanner(banners, publishedUrl, trackingUrl, isPreview = false, callbackClickCloseBanner, callbackClickBanner) {
            try {
                const that = this;
                if (!(banners && banners.length > 0)) return;

                that.publishedUrl = publishedUrl;
                that.trackingUrl = trackingUrl;

                banners.forEach((bannerItem) => {
                    const bannerEntity = bannerItem;
                    const banner = that.convertBannerDataParseString(bannerEntity);
                    const bannerOptions = banner.BannerOptions;
                    let noDisplayWithScreenSmall = bannerOptions.option.noDisplayWithScreenSmall != undefined ? bannerOptions.option.noDisplayWithScreenSmall : false;
                    if (noDisplayWithScreenSmall) {
                        const widthWindow = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
                        if (widthWindow < 1024) return;
                    }

                    if (isPreview) {
                        //Hiển thị ngay không cần triggerConditions cho trang Xem trước
                        that.mountExternalBanner(banner, callbackClickCloseBanner, callbackClickBanner);
                    } else {
                        //Nếu là isAutoShow thì hiển thị luôn banner
                        //Khỏi tạo các điều kiện trigger để hiện thị Banner
                        if (bannerOptions.triggerConditions && isAutoShow) {
                            const {
                                pageScroll,
                                exitIntent,
                                afterElapsedTime,
                            } = bannerOptions.triggerConditions;
                            if (pageScroll) {
                                if (pageScroll.active) {
                                    document.body.addEventListener('scroll', function (event) {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                                        if (!isCheckTimeShowBanner) return;

                                        that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.PageScroll);

                                        const scrollHeight = document.querySelector('body').scrollHeight;
                                        if ((document.body.scrollTop / (scrollHeight - document.querySelector('body').offsetHeight)) * 100 >= pageScroll.value) {
                                            that.mountExternalBanner(banner);
                                        }
                                    });
                                    window.addEventListener('scroll', function (event) {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                                        if (!isCheckTimeShowBanner) return;

                                        that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.PageScroll);
                                        const scrollHeight = document.querySelector('body')
                                            .scrollHeight;
                                        if ((window.scrollY / (scrollHeight - window.outerHeight)) * 100 >= pageScroll.value) {
                                            that.mountExternalBanner(banner);
                                        }
                                    });
                                }
                            }
                            if (exitIntent) {
                                if (exitIntent.active) {
                                    document.addEventListener('mouseleave', function (event) {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                                        if (!isCheckTimeShowBanner) return;

                                        that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.ExitIntent);
                                        that.mountExternalBanner(banner);
                                        event.stopPropagation && event.stopPropagation();
                                    });
                                }
                            }

                            if (afterElapsedTime) {
                                if (afterElapsedTime.active) {
                                    let timeOut = afterElapsedTime.value * 1000 - that.timeCallApi;
                                    timeOut = timeOut > 0 ? timeOut : 0;
                                    setTimeout(function () {
                                        //Kiểm tra điều kiện thời gian hiển thị
                                        let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                                        if (!isCheckTimeShowBanner) return;

                                        that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.AfterElapsedTime);
                                        that.mountExternalBanner(banner);
                                    }, timeOut);
                                }
                            }
                        } else {
                            that.mountExternalBanner(banner);
                        }
                    }
                });


            } catch (err) {
                console.log(err);
            }
        },

        /**
         * Hàm thực hiện hiển thị survey banner
         * @author PTBinh (02/08/2023)
         * */
        async renderBannerSurvey(tenantID, bannerID, publishedUrl, trackingUrl, callbackClickCloseBanner, callbackClickBanner) {
            const result = await fetch(publishedUrl + `/api/v3/banners?bannerID=${bannerID}`, {
                headers: {
                    'x-tenant-id': tenantID,
                    'Access-Control-Allow-Origin': '*',
                },
            });
            const banner = await result.json();
            const banners = [banner];
            if (!banners || banners.length === 0) return;
            //render banner
            globalThis.lzpr.renderExternalBanner(banners, publishedUrl, trackingUrl, isPreview = true, callbackClickCloseBanner, callbackClickBanner);
        },

        /**
         * Hàm Khởi tạo sự kiện TRIGGER hiển thị cả banner và form
         * @param {any} arr mảng chứa cả banner và form
         * @param {any} publishedUrl Đường dẫn của published page
         * @param {any} trackingUrl Đường dẫn của tracking ucap
         * @author PTBinh (08/03/2022)
         */
        async renderExternalMerge(forms, banners, publishedUrl, trackingUrl) {
            try {
                const that = this;
                if (!(forms && forms.length > 0) && !(banners && banners.length > 0)) return;

                that.publishedUrl = publishedUrl;
                that.trackingUrl = trackingUrl;

                let arrPageScroll = [];
                let arrExitIntent = [];
                let arrAfterElapsedTime = [];

                //Đưa form vào danh sách các điều kiện hiển thị
                forms.forEach((formItem) => {
                    const formEntity = formItem;
                    const form = that.convertFormDataParseString(formEntity);
                    const formOptions = form.FormOptions;

                    //Nếu là isAutoShow thì hiển thị banner luôn
                    if (formOptions.triggerConditions && isAutoShow) {
                        const {
                            pageScroll,
                            exitIntent,
                            afterElapsedTime,
                        } = formOptions.triggerConditions;
                        if (pageScroll) {
                            if (pageScroll.active) {
                                arrPageScroll.push(formItem);
                            }
                        }
                        if (exitIntent) {
                            if (exitIntent.active) {
                                arrExitIntent.push(formItem);
                            }
                        }
                        if (afterElapsedTime) {
                            if (afterElapsedTime.active) {
                                arrAfterElapsedTime.push(formItem);
                            }
                        }
                    }
                    else {
                        that.mountExternalPopupForm(form);
                    }

                    //thực hiện check click vào Cta để hiển thị form Popup
                    this.showPopupWithCta(form);
                });

                //Đưa banner vào danh sách các điều kiện hiển thị
                banners.forEach((bannerItem) => {
                    const bannerEntity = bannerItem;
                    const banner = that.convertBannerDataParseString(bannerEntity);
                    const bannerOptions = banner.BannerOptions;
                    let noDisplayWithScreenSmall = bannerOptions.option.noDisplayWithScreenSmall != undefined ? bannerOptions.option.noDisplayWithScreenSmall : false;
                    if (noDisplayWithScreenSmall) {
                        const widthWindow = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
                        if (widthWindow < 1024) return;
                    }
                    if (bannerOptions.triggerConditions && isAutoShow) {
                        const {
                            pageScroll,
                            exitIntent,
                            afterElapsedTime,
                        } = bannerOptions.triggerConditions;
                        if (pageScroll) {
                            if (pageScroll.active) {
                                arrPageScroll.push(bannerItem);
                            }
                        }
                        if (exitIntent) {
                            if (exitIntent.active) {
                                arrExitIntent.push(bannerItem);
                            }
                        }
                        if (afterElapsedTime) {
                            if (afterElapsedTime.active) {
                                arrAfterElapsedTime.push(bannerItem);
                            }
                        }
                    }
                    else {
                        that.mountExternalBanner(banner);
                    }
                });

                let maxPageScroll;
                let maxExitIntent;
                let maxAfterElapsedTime;

                //lấy ra thời gian xuất bản sau cùng của event cuộn trang
                if (arrPageScroll.length > 0) {
                    maxPageScroll = arrPageScroll[0];
                    arrPageScroll.forEach(item => {
                        if (item.ModifiedDate > maxPageScroll.ModifiedDate) {
                            maxPageScroll = item;
                        }
                    })
                }

                //lấy ra thời gian xuất bản sau cùng của event thoát trang
                if (arrExitIntent.length > 0) {
                    maxExitIntent = arrExitIntent[0];
                    arrExitIntent.forEach(item => {
                        if (item.ModifiedDate > maxExitIntent.ModifiedDate) {
                            maxExitIntent = item;
                        }
                    })
                }

                //lấy ra thời gian xuất bản sau cùng của event sau bao lâu thì hiển thị
                if (arrAfterElapsedTime.length > 0) {
                    maxAfterElapsedTime = arrAfterElapsedTime[0];
                    arrAfterElapsedTime.forEach(item => {
                        if (item.ModifiedDate > maxAfterElapsedTime.ModifiedDate) {
                            maxAfterElapsedTime = item;
                        }
                    })
                }

                //set sự kiện hiển thị khi cuộn trang cho Form/Banner
                if (maxPageScroll) {
                    if (maxPageScroll["FormID"]) {
                        const form = that.convertFormDataParseString(maxPageScroll);
                        const formOptions = form.FormOptions;
                        let pageScroll = formOptions.triggerConditions.pageScroll;
                        document.body.addEventListener('scroll', function (event) {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                            if (!isCheckTimeShowForm) return;

                            that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.PageScroll);

                            const scrollHeight = document.querySelector('body').scrollHeight;
                            if ((document.body.scrollTop / (scrollHeight - document.querySelector('body').offsetHeight)) * 100 >= pageScroll.value) {
                                that.mountExternalPopupForm(form);
                            }
                        });
                        window.addEventListener('scroll', function (event) {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                            if (!isCheckTimeShowForm) return;

                            that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.PageScroll);
                            const scrollHeight = document.querySelector('body')
                                .scrollHeight;
                            if ((window.scrollY / (scrollHeight - window.outerHeight)) * 100 >= pageScroll.value) {
                                that.mountExternalPopupForm(form);
                            }
                        });
                    }

                    if (maxPageScroll["BannerID"]) {
                        const banner = that.convertBannerDataParseString(maxPageScroll);
                        const bannerOptions = banner.BannerOptions;
                        let pageScroll = bannerOptions.triggerConditions.pageScroll;
                        document.body.addEventListener('scroll', function (event) {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                            if (!isCheckTimeShowBanner) return;

                            that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.PageScroll);

                            const scrollHeight = document.querySelector('body').scrollHeight;
                            if ((document.body.scrollTop / (scrollHeight - document.querySelector('body').offsetHeight)) * 100 >= pageScroll.value) {
                                that.mountExternalBanner(banner);
                            }
                        });
                        window.addEventListener('scroll', function (event) {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                            if (!isCheckTimeShowBanner) return;

                            that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.PageScroll);
                            const scrollHeight = document.querySelector('body')
                                .scrollHeight;
                            if ((window.scrollY / (scrollHeight - window.outerHeight)) * 100 >= pageScroll.value) {
                                that.mountExternalBanner(banner);
                            }
                        });
                    }
                }

                //set sự kiện hiển thị khi chuẩn bị thoát trang cho Form/Banner
                if (maxExitIntent) {
                    if (maxExitIntent["FormID"]) {
                        const form = that.convertFormDataParseString(maxExitIntent);
                        document.addEventListener('mouseleave', function (event) {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                            if (!isCheckTimeShowForm) return;

                            that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.ExitIntent);
                            that.mountExternalPopupForm(form);
                            event.stopPropagation && event.stopPropagation();
                        });
                    }
                    if (maxExitIntent["BannerID"]) {
                        const banner = that.convertBannerDataParseString(maxExitIntent);
                        document.addEventListener('mouseleave', function (event) {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                            if (!isCheckTimeShowBanner) return;

                            that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.ExitIntent);
                            that.mountExternalBanner(banner);
                            event.stopPropagation && event.stopPropagation();
                        });
                    }
                }

                //set sự kiện hiển thị sau bao lâu cho Form/Banner
                if (maxAfterElapsedTime) {
                    if (maxAfterElapsedTime["FormID"]) {
                        const form = that.convertFormDataParseString(maxAfterElapsedTime);
                        const formOptions = form.FormOptions;
                        let timeOut = formOptions.triggerConditions.afterElapsedTime.value * 1000 - that.timeCallApi;
                        timeOut = timeOut > 0 ? timeOut : 0;
                        setTimeout(function () {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowForm = that.isCheckShowFormPopup(form);
                            if (!isCheckTimeShowForm) return;

                            that.setFirstTriggerShowFormType(aimktFormConstant.aimktTriggerDisplay.AfterElapsedTime);
                            that.mountExternalPopupForm(form);
                        }, timeOut);
                    }
                    if (maxAfterElapsedTime["BannerID"]) {
                        const banner = that.convertBannerDataParseString(maxAfterElapsedTime);
                        const bannerOptions = banner.BannerOptions;
                        let timeOut = bannerOptions.triggerConditions.afterElapsedTime.value * 1000 - that.timeCallApi;
                        timeOut = timeOut > 0 ? timeOut : 0;
                        setTimeout(function () {
                            //Kiểm tra điều kiện thời gian hiển thị
                            let isCheckTimeShowBanner = that.isCheckShowBanner(banner);
                            if (!isCheckTimeShowBanner) return;

                            that.setFirstTriggerShowBannerType(aimktBannerConst.aimktTriggerDisplay.AfterElapsedTime);
                            that.mountExternalBanner(banner);
                        }, timeOut);
                    }
                }
            } catch (err) {
                console.log(err);
                that.sendNotificationMessage("Error popup-embed.js renderExternalPopupForm", err);
            }
        },

        /**
         * Kiểm tra điều kiện hiển thị Form
         * @param {object} form Đối tượng Form muốn hiển thị
         * @param {any} aimktTriggerShowType Loại form hiển thị
         * @returns 
         * TRUE: Có cho phép hiển thị
         * FALSE: Không cho phép hiển thị
         * @author dvhung1 (06/09/2021)
         * */
        isCheckShowFormPopup(form, aimktTriggerShowType = aimktFormConstant.aimktTriggerDisplay.ExitIntent) {
            try {
                let elFormPopup = document.querySelector(`.lz-iframe-ctn`);
                if (elFormPopup !== null) return false;

                const formID = form.FormID;
                const formOptions = form.FormOptions;

                if (globalThis.aimktTriggerShowForm) {
                    switch (aimktTriggerShowType) {
                        case aimktFormConstant.aimktTriggerDisplay.AfterElapsedTime:
                            if (globalThis.aimktTriggerDisplayed["AfterElapsedTime"] === true) return false;
                            break;
                        case aimktFormConstant.aimktTriggerDisplay.ExitIntent:
                            if (globalThis.aimktTriggerDisplayed["ExitIntent"] === true) return false;
                            break;
                        case aimktFormConstant.aimktTriggerDisplay.PageScroll:
                            if (globalThis.aimktTriggerDisplayed["PageScroll"] === true) return false;
                            break;
                        default:
                            break;
                    }
                }

                const formOptionTimeShow = formOptions.option.timeReDisplayForm;
                const TIME_RE_DISPLAY_FORM = aimktFormConstant.TimeReDisplayForm;
                const TIME_RE_DISPLAY_FORM_S = aimktFormConstant.TimeReDisplayFormMilliseconds;

                let timeShow = 0;
                switch (formOptionTimeShow) {
                    case TIME_RE_DISPLAY_FORM.Day1:
                        timeShow = TIME_RE_DISPLAY_FORM_S.Day1;
                        break;
                    case TIME_RE_DISPLAY_FORM.Day3:
                        timeShow = TIME_RE_DISPLAY_FORM_S.Day3;
                        break;
                    case TIME_RE_DISPLAY_FORM.Week1:
                        timeShow = TIME_RE_DISPLAY_FORM_S.Week1;
                        break;
                    case TIME_RE_DISPLAY_FORM.Week2:
                        timeShow = TIME_RE_DISPLAY_FORM_S.Week2;
                        break;
                    case TIME_RE_DISPLAY_FORM.Month1:
                        timeShow = TIME_RE_DISPLAY_FORM_S.Month1;
                        break;
                    default:
                        break;
                }


                const aimktStorageFormPopup = localStorage.getItem(aimktFormConstant.LocalStorageName.ListFormDataStorage);
                if (aimktStorageFormPopup === null || aimktStorageFormPopup === "") return true;

                const arrFormTime = JSON.parse(aimktStorageFormPopup);
                let formNow = arrFormTime.find(form => form.FormID == formID);
                if (formNow === undefined) return true;

                if (formNow['IsSubmitForm'] === true) return false;

                const lastShowForm = formNow.LastShowForm;
                const timeNow = new Date().getTime();
                const isDisplay = ((timeNow - lastShowForm) / 1000) > timeShow;
                return isDisplay;
            } catch (e) {
                localStorage.removeItem(aimktFormConstant.LocalStorageName.ListFormDataStorage);
                return true;
            }
        },

        /**
         * Kiểm tra điều kiện hiển thị Banner
         * @param {object} banner Đối tượng Banner muốn hiển thị
         * @param {any} aimktTriggerShowType Loại banner hiển thị
         * @returns
         * TRUE: Có cho phép hiển thị
         * FALSE: Không cho phép hiển thị
         * @author PTBinh (07/03/2022)
         * */
        isCheckShowBanner(banner, aimktTriggerShowType = aimktBannerConst.aimktTriggerDisplay.ExitIntent) {
            try {
                let elBanner = document.querySelector(`.lz-iframe-ctn`);
                if (elBanner !== null) return false;

                const bannerID = banner.BannerID;
                const bannerOptions = banner.BannerOptions;

                if (globalThis.aimktTriggerShowForm) {
                    switch (aimktTriggerShowType) {
                        case aimktBannerConst.aimktTriggerDisplay.AfterElapsedTime:
                            if (globalThis.aimktTriggerDisplayed["AfterElapsedTime"] === true) return false;
                            break;
                        case aimktBannerConst.aimktTriggerDisplay.ExitIntent:
                            if (globalThis.aimktTriggerDisplayed["ExitIntent"] === true) return false;
                            break;
                        case aimktBannerConst.aimktTriggerDisplay.PageScroll:
                            if (globalThis.aimktTriggerDisplayed["PageScroll"] === true) return false;
                            break;
                        default:
                            break;
                    }
                }

                const bannerOptionTimeShow = bannerOptions.option.timeReDisplayBanner;
                const TIME_RE_DISPLAY_BANNER = aimktBannerConst.TimeReDisplayBanner;
                const TIME_RE_DISPLAY_BANNER_S = aimktBannerConst.TimeReDisplayBannerMilliseconds;

                let timeShow = 0;
                switch (bannerOptionTimeShow) {
                    case TIME_RE_DISPLAY_BANNER.Day1:
                        timeShow = TIME_RE_DISPLAY_BANNER_S.Day1;
                        break;
                    case TIME_RE_DISPLAY_BANNER.Day3:
                        timeShow = TIME_RE_DISPLAY_BANNER_S.Day3;
                        break;
                    case TIME_RE_DISPLAY_BANNER.Week1:
                        timeShow = TIME_RE_DISPLAY_BANNER_S.Week1;
                        break;
                    case TIME_RE_DISPLAY_BANNER.Week2:
                        timeShow = TIME_RE_DISPLAY_BANNER_S.Week2;
                        break;
                    case TIME_RE_DISPLAY_BANNER.Month1:
                        timeShow = TIME_RE_DISPLAY_BANNER_S.Month1;
                        break;
                    default:
                        break;
                }


                const aimktStorageBanner = localStorage.getItem(aimktBannerConst.LocalStorageName.ListBannerDataStorage);
                if (aimktStorageBanner === null || aimktStorageBanner === "") return true;

                const arrBannerTime = JSON.parse(aimktStorageBanner);
                let bannerNow = arrBannerTime.find(banner => banner.BannerID == bannerID);
                if (bannerNow === undefined) return true;

                //if (bannerNow['IsSubmitForm'] === true) return false;

                const lastShowBanner = bannerNow.LastShowBanner;
                const timeNow = new Date().getTime();
                const isDisplay = ((timeNow - lastShowBanner) / 1000) > timeShow;
                return isDisplay;
            } catch (e) {
                localStorage.removeItem(aimktBannerConst.LocalStorageName.ListBannerDataStorage);
                return true;
            }
        },

        /**
         * Save thời gian hiển thị form lần cuối cùng
         * @param {object} form Đối tượng Form muốn hiển thị
         * @param {Boolean} isSubmitForm Có phải submit form hay không
         * @author dvhung1 (06/09/2021)
         * */
        setLastTimeShowFormPopup(form, isSubmitForm = false) {
            let formID = form.FormID;
            let formTime = {
                FormID: formID,
                LastShowForm: new Date().getTime(),
                IsSubmitForm: isSubmitForm
            }
            let formDataStorage = [];

            try {
                let aimktStorageFormPopup = localStorage.getItem(aimktFormConstant.LocalStorageName.ListFormDataStorage);
                if (aimktStorageFormPopup === null || aimktStorageFormPopup === "") {
                    formDataStorage = [formTime];
                } else {
                    let arrFormTime = JSON.parse(aimktStorageFormPopup);
                    let formNow = arrFormTime.find(form => form.FormID == formID);
                    if (formNow === undefined) {
                        arrFormTime.push(formTime);
                    } else {
                        formNow.LastShowForm = formTime.LastShowForm;
                        formNow.IsSubmitForm = formTime.IsSubmitForm;
                    }
                    formDataStorage = arrFormTime;
                }

                localStorage.setItem(aimktFormConstant.LocalStorageName.ListFormDataStorage, JSON.stringify(formDataStorage));
            } catch (e) {
                localStorage.setItem(aimktFormConstant.LocalStorageName.ListFormDataStorage, JSON.stringify([formTime]));
                return true;
            }

        },

        /**
         * Thời gian hiển thị banner lần cuối cùng
         * @param {object} banner Đối tượng Banner muốn hiển thị
         * @author PTBinh (04/03/2022)
         * */
        setLastTimeShowBanner(banner) {
            let bannerID = banner.BannerID;
            let bannerTime = {
                BannerID: bannerID,
                LastShowBanner: new Date().getTime(),
            }
            let bannerDataStorage = [];

            try {
                let aimktStorageBanner = localStorage.getItem(aimktBannerConst.LocalStorageName.ListBannerDataStorage);
                if (aimktStorageBanner === null || aimktStorageBanner === "") {
                    bannerDataStorage = [bannerTime];
                } else {
                    let arrBannerTime = JSON.parse(aimktStorageBanner);
                    let bannerNow = arrBannerTime.find(banner => banner.BannerID == bannerID);
                    if (bannerNow === undefined) {
                        arrBannerTime.push(bannerTime);
                    } else {
                        bannerNow.LastShowBanner = bannerTime.LastShowBanner;
                        //bannerNow.IsSubmitForm = formTime.IsSubmitForm;
                    }
                    bannerDataStorage = arrBannerTime;
                }

                localStorage.setItem(aimktBannerConst.LocalStorageName.ListBannerDataStorage, JSON.stringify(bannerDataStorage));
            } catch (e) {
                localStorage.setItem(aimktBannerConst.LocalStorageName.ListBannerDataStorage, JSON.stringify([bannerTime]));
                return true;
            }
        },

        /**
         * set điều kiện trigger hiển thị đầu tiên
         * @param {any} aimktTriggerShowType Loại điều kiện trigger hiển thị
         * @dvhung1 (15/09/2021)
         */
        setFirstTriggerShowFormType(aimktTriggerShowType = aimktFormConstant.aimktTriggerDisplay.ExitIntent) {
            if (!globalThis.aimktTriggerDisplayed) globalThis.aimktTriggerDisplayed = {};

            switch (aimktTriggerShowType) {
                case aimktFormConstant.aimktTriggerDisplay.AfterElapsedTime:
                    globalThis.aimktTriggerDisplayed["AfterElapsedTime"] = true;
                    break;
                case aimktFormConstant.aimktTriggerDisplay.ExitIntent:
                    globalThis.aimktTriggerDisplayed["ExitIntent"] = true;
                    break;
                case aimktFormConstant.aimktTriggerDisplay.PageScroll:
                    globalThis.aimktTriggerDisplayed["PageScroll"] = true;
                    break;
                default:
                    break;
            }
        },

        /**
         * set điều kiện trigger hiển thị đầu tiên
         * @param {any} aimktTriggerShowType Loại điều kiện trigger hiển thị
         * @PTBinh (07/03/2022)
         */
        setFirstTriggerShowBannerType(aimktTriggerShowType = aimktBannerConst.aimktTriggerDisplay.ExitIntent) {
            if (!globalThis.aimktTriggerDisplayed) globalThis.aimktTriggerDisplayed = {};

            switch (aimktTriggerShowType) {
                case aimktBannerConst.aimktTriggerDisplay.AfterElapsedTime:
                    globalThis.aimktTriggerDisplayed["AfterElapsedTime"] = true;
                    break;
                case aimktBannerConst.aimktTriggerDisplay.ExitIntent:
                    globalThis.aimktTriggerDisplayed["ExitIntent"] = true;
                    break;
                case aimktBannerConst.aimktTriggerDisplay.PageScroll:
                    globalThis.aimktTriggerDisplayed["PageScroll"] = true;
                    break;
                default:
                    break;
            }
        },

        /**
         * @param {any} form Đối tượng Form muốn hiển thị 
         * @author dvhung1 (13/08/2021)
         */
        mountExternalPopupForm(form) {
            const that = this;
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            const POPUP_FORM_TYPE = aimktFormConstant.PopupFormType;
            const formOptions = form.FormOptions;
            let fontFamily = form.Style['font-family'] == undefined ? 'Roboto' : form.Style['font-family'];

            let elFormPopup = document.querySelector(`#${CLASS_NAME_FORM_POPUP}[for="popup"]`);
            if (elFormPopup === null) {
                const domFormWrap = aimktFormCommonFunc.createElementFromHTML(`<div class="lz-iframe-ctn"><div class="lz-iframe-ctn__overlay"></div></div>`);
                const domForm = aimktFormCommonFunc.createElementFromHTML(`<div id="${CLASS_NAME_FORM_POPUP}" for="popup" scrolling="no" frameborder="0"></div>`);
                const elContent = aimktFormCommonFunc.createElementFromHTML(`<div class="lz-main-ctn">  <div class="lz-inner-ctn"></div>  </div>`);

                let cssMountExternalPopupForm = that.resourceCSSMountExternalPopupForm() + that.resourceCSSAnimation();
                aimktFormCommonFunc.createStyleHtml(cssMountExternalPopupForm, domForm);
                aimktFormCommonFunc.createLinkFontCssGoogle('Google Sans', domForm);
                aimktFormCommonFunc.createLinkFontCssGoogle(fontFamily, domForm);

                //Khởi tạo DOM có để hiện thị được các Step của FormPopup


                domForm.appendChild(elContent);
                domFormWrap.appendChild(domForm);
                document.body.appendChild(domFormWrap);

                switch (form.PopupFormType) {
                    case POPUP_FORM_TYPE.PopupBox:
                        that.renderCallOutPopupBox(form, domFormWrap, domForm);
                        break;
                    case POPUP_FORM_TYPE.DropdownBanner:
                        that.renderCallOutDropdownBanner(form, domFormWrap, domForm);
                        break;
                    case POPUP_FORM_TYPE.SlideinBoxLeft:
                    case POPUP_FORM_TYPE.SlideinBoxRight:
                        that.renderCallOutSlideinBoxLeftRight(form, domFormWrap, domForm)
                        break;
                }

                //Render tab layout
                that.renderLayoutForm(form, domForm);

                if (!formOptions.callout.isCallOut) {
                    that.setShowUIStep(aimktFormConstant.FormPopupStep.LayoutForm, form);
                }

                //Khởi tạo sự kiện cho Form Popup
                that.initEventFormPopup(domForm, form);
            }
        },

        /**
         * Get thông tin page
         * @author PTBinh (08/03/2022)
         * */
        getPageInfo() {
            let elLdpageUcap = document.querySelector('.lpage-ucap[lz-id]');
            let landingPageID = elLdpageUcap === null ? "" : elLdpageUcap.getAttribute('lz-id');
            return {
                destinationURL: window.parent.location.href,
                pagePath: window.parent.location.pathname,
                pageOrigin: window.parent.location.origin,
                pageName: window.parent.document.title,
                referringURL: window.parent.document.referrer,
                lpageId: landingPageID,
            };
        },

        /**
         * @param {any} form Đối tượng Banner muốn hiển thị
         * @author PTBinh (04/03/2022)
         */
        mountExternalBanner(banner, callbackClickCloseBanner, callbackClickBanner) {
            const that = this;
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            const BANNER_TYPE = aimktBannerConst.BannerType;
            const bannerOptions = banner.BannerOptions;
            //let fontFamily = form.Style['font-family'] == undefined ? 'Roboto' : form.Style['font-family'];

            let elBanner = document.querySelector(`#${CLASS_NAME_BANNER}`);
            if (elBanner === null) {
                const domBannerWrap = aimktFormCommonFunc.createElementFromHTML(`<div class="lz-iframe-ctn"><div class="lz-iframe-ctn__overlay"></div></div>`);
                const domBanner = aimktFormCommonFunc.createElementFromHTML(`<div id="${CLASS_NAME_BANNER}" scrolling="no" frameborder="0"></div>`);
                const elContent = aimktFormCommonFunc.createElementFromHTML(`<div class="lz-main-ctn">  <div class="lz-inner-ctn"></div>  </div>`);

                let cssMountExternalBanner = that.resourceCSSMountExternalBanner() + that.resourceCSSAnimation();
                aimktFormCommonFunc.createStyleHtml(cssMountExternalBanner, domBanner);
                //aimktFormCommonFunc.createLinkFontCssGoogle('Google Sans', domForm);
                //aimktFormCommonFunc.createLinkFontCssGoogle(fontFamily, domForm);

                //Khởi tạo DOM có để hiện thị được các Step của FormPopup
                domBanner.appendChild(elContent);
                domBannerWrap.appendChild(domBanner);
                document.body.appendChild(domBannerWrap);
                switch (banner.BannerType) {
                    case BANNER_TYPE.PopupBox:
                        that.renderBannerPopupBox(banner, domBannerWrap, domBanner);
                        break;
                    case BANNER_TYPE.DropdownBanner:
                        that.renderDropdownBanner(banner, domBannerWrap, domBanner);
                        break;
                    case BANNER_TYPE.SlideinBoxLeft:
                    case BANNER_TYPE.SlideinBoxRight:
                        that.renderBannerSlideinBoxLeftRight(banner, domBannerWrap, domBanner, banner.BannerType)
                        break;
                }
                ////Render tab layout
                //that.renderLayoutForm(form, domForm);

                //if (!formOptions.callout.isCallOut) {
                //    that.setShowUIStep(aimktFormConstant.FormPopupStep.LayoutForm, form);
                //}

                // View Banner - call api tracking

                //tracking utm
                let formData = {};
                formData = this.trackingUtm(banner);

                try {
                    fetch(this.trackingUrl + '/api/ucap/eventcollector', {
                        method: 'POST',
                        headers: {
                            'Content-type': 'text/plain; charset=utf-8',
                        },
                        body: JSON.stringify({
                            events: [
                                {
                                    eventType: globalThis.lzpr.eventTypeBannerView,
                                    scope: globalThis.lzpr.scopeBanner,
                                    source: {
                                        itemType: 'LandingPageID',
                                        properties: this.getPageInfo(),
                                        itemId: this.getPageInfo()['lpageId'],
                                    },
                                    target: {
                                        itemType: 'BannerID',
                                        itemId: banner.BannerID,
                                        properties: {
                                            TargetName: banner.BannerName,
                                        },
                                    },
                                    properties: formData,
                                },
                            ],
                        }),
                    });

                } catch (error) {
                    console.error('aimarketing_error GET FORM - ', error);
                }

                //Khởi tạo sự kiện cho Form Popup
                that.initEventBanner(domBanner, banner, callbackClickCloseBanner, callbackClickBanner);
            }
        },

        //#region render ra banner
        /**
         * Hảm render ra DOM cho Form popup dạng: PopupBox
         * @param {any} banner Đối tượng Banner
         * @param {any} domBannerWrap Dom element Dom chứa Iframe
         * @param {any} domBanner Dom element Dom Iframe
         * @author PTBinh (04/03/2022)
         */
        renderBannerPopupBox(banner, domBannerWrap, domBanner) {
            const me = this;
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            const bannerOptions = banner.BannerOptions;
            //let widthImage;
            //if (form.FormOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly) {
            //    widthImage = form.FormOptions.callout["widthImage"] + 'px';
            //}
            //else {
            //    widthImage = form.Style["width"] ? form.Style["width"] : '600px';
            //}

            let widthImage = bannerOptions.bannerImage['widthImage'];
            let heightImage = bannerOptions.bannerImage['heightImage'];

            //Xử lý domBannerWrap
            let cssContentDomBanner = `
                                        body .lz-main-ctn{
                                            display: flex;
                                            height: 100%;
                                            width: 100%;
                                            justify-content: center;
                                        }
                                        body .lz-main-ctn *{ box-sizing: border-box; }
                                        body .lz-main-ctn .lz-inner-ctn{
                                            display: flex;
                                            max-width: 90%;
                                            overflow: auto;
                                            border-radius: 7px;
                                            flex-direction: column;
                                            align-items: center;
                                            position: absolute;
                                            width: ${widthImage};
                                            margin: 0;
                                            left: 50%;
                                            top: 50%;
                                            transform: translate(-50%, -50%);
                                        }
                        
                                        body .lz-main-ctn .lz-inner-ctn:hover{
                                            cursor: pointer;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .lz-callout-ctn{
                                            display: flex;
                                            justify-content: center;
                                            align-items: flex-start;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner{
                                            width: 100%;
                                            position: relative;
                                            background-image:url(${bannerOptions.bannerImage.urlImg}); 
                                            width: ${bannerOptions.bannerImage.widthImage}px; 
                                            height: ${bannerOptions.bannerImage.heightImage}px; 
                                            background-size: contain;
                                            background-position:center;
                                            background-repeat: no-repeat;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-free-license-warning {
                                            left: 0;
                                            bottom: 0;
                                            width: 100%;
                                            background-color: #ffffff;
                                            border-top: 1px solid #ff5637;
                                            border-bottom-left-radius: 8px;
                                            border-bottom-right-radius: 8px;
                                            padding: 4px 0;
                                            text-align: center;
                                            height: 36px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-free-license-warning a{
                                            color: #454545;
                                            text-decoration: none;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-form .aim-free-license-warning .aim-free-license-warning__logo img{
                                            height: 24px;
                                        }
                                        `;
            aimktFormCommonFunc.createStyleHtml(cssContentDomBanner, domBanner);
            aimktFormCommonFunc.createStyleHtml(`.lz-iframe-ctn {
                            height: 100% !important;
                            width: 100% !important;
                            //opacity: 0;
                            top: 0;
                            left: 0;
                            position: fixed;
                        }

                       .lz-iframe-ctn #${CLASS_NAME_BANNER}{
                            height: 100%;
                            width: 100%;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-banner__content-banner_close{
                            position: absolute;
                            top: 16px;
                            right: 16px;
                            background-color: #ffffff;
                            opacity: 50%;
                            width: 24px;
                            height: 24px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border-radius: 4px;
                        }
                        @media (max-width: 600px){
                            body .lz-main-ctn .lz-inner-ctn{
                                //left: unset;
                                //top: unset;
                                //transform: unset;
                                left: 50%;
                                top: 50%;
                                transform: translate(-50%, -50%);
                            }
                            .lz-iframe-ctn #${CLASS_NAME_BANNER}{
                                height: 100%;
                                width: unset;
                                //min-width: 300px;
                            }
                            body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner
                            {
                                width: 100%;
                            }
                        }

                        `, domBannerWrap);

            const elBanner = aimktFormCommonFunc.createElementFromHTML(
                `<div class= 'aim-banner__content-banner'>
                    <div class=aim-banner__content-banner_image'>
                    </div >
                    <div class='aim-banner__content-banner_close aimkt-close-popup'>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#707070" width="24" height="24" viewBox="0 0 24 24" class="button-close-form-popup">
                            <rect data-name="Rectangle 15970" width="24" height="24" fill="none"></rect>
                            <path data-name="Union 1" d="M5,11V7H1A1,1,0,0,1,1,5H5V1A1,1,0,0,1,7,1V5h4a1,1,0,0,1,0,2H7v4a1,1,0,0,1-2,0Z" transform="translate(12 3.515) rotate(45)"></path>
                        </svg>
                    </div>
                </div >`);
            //aimktFormCommonFunc.createStyleHtml(formOptions.renderPublished.callOut.cssForm, elCallOut);
            const elLzInnerCtn = domBanner.querySelector(".lz-inner-ctn");
            elLzInnerCtn.appendChild(elBanner);

            me.responsiveImageBanner(bannerOptions, domBanner);
        },

        /**
         * Hảm render ra DOM cho Form popup dạng: Dropdown
         * @param {any} banner Đối tượng Form
         * @param {any} domBannerWrap Dom element Dom chứa Iframe
         * @param {any} domBanner Dom element Dom Iframe
         * @author PTBinh (04/03/2022)
         */
        renderDropdownBanner(banner, domBannerWrap, domBanner) {
            const me = this;
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            const bannerOptions = banner.BannerOptions;

            //Xử lý domBannerWrap
            const cssContentDomWrap = `
                        .lz-main-ctn{
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        .lz-inner-ctn{
                            padding-bottom: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .lz-inner-ctn:hover{
                            cursor: pointer;
                        }
                        
                        .lz-iframe-ctn{
                            width: 100% !important;
                            //top: -100%;
                            top: 0;
                            left: 0;
                            position: fixed;
                        }
                       .lz-iframe-ctn #${CLASS_NAME_BANNER}{
                            width: 100%;
                        }
                        .lz-iframe-ctn #${CLASS_NAME_BANNER} .aim-banner__content-banner{
                            //margin: 4px;
                            margin-top: 16px;
                            box-shadow: 0 6px 20px #00000029;
                            position: relative;
                            background-image:url(${bannerOptions.bannerImage.urlImg}); 
                            width: ${bannerOptions.bannerImage.widthImage}px; 
                            height: ${bannerOptions.bannerImage.heightImage}px;
                            background-size: contain;
                            background-position:center;
                            background-repeat: no-repeat;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-form .aim-free-license-warning {
                            left: 0;
                            bottom: 0;
                            width: 100%;
                            background-color: #ffffff;
                            border-top: 1px solid #ff5637;
                            border-bottom-left-radius: 8px;
                            border-bottom-right-radius: 8px;
                            padding: 4px 0;
                            text-align: center;
                            height: 36px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-free-license-warning a{
                            color: #454545;
                            text-decoration: none;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-free-license-warning .aim-free-license-warning__logo img{
                            height: 24px;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-banner__content-banner_close{
                            position: absolute;
                            top: 16px;
                            right: 16px;
                            background-color: #ffffff;
                            opacity: 50%;
                            width: 24px;
                            height: 24px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border-radius: 4px;
                        }

                        @media (max-width: 600px){
                            .lz-iframe-ctn.lz-iframe-ctn.lz-iframe-ctn{
                                //top: 40px !important;
                            }
                            .lz-inner-ctn{
                                padding-bottom: 20px;
                                //padding-top: 20px;
                            }
                            .lz-iframe-ctn #${CLASS_NAME_BANNER} .aim-banner__content-banner{
                                //width: calc(100% - 32px);
                                margin-top: 16px;
                            }
                            .banner-wrapper{
                                padding-bottom: 20px;
                                padding-top: 20px;
                                margin-top: -20px;
                            }
                            .aimkt-layout-banner .aimkt-layout-banner__close{
                            }

                            .lz-iframe-ctn #${CLASS_NAME_BANNER}{
                                height: 100%;
                                width: unset;
                                //min-width: 300px;
                            }

                            body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner
                            {
                                width: 100%;
                            }
                        }
                        `;
            aimktFormCommonFunc.createStyleHtml(cssContentDomWrap, domBannerWrap);

            //Ẩn overlay
            document.querySelector(".lz-iframe-ctn .lz-iframe-ctn__overlay").classList.add("hidden");

            const elBanner = aimktFormCommonFunc.createElementFromHTML(
                `
                    <div class='aim-banner__content-banner'>
                        <div class=aim-banner__content-banner_image'>
                        </div>
                        <div class='aim-banner__content-banner_close aimkt-close-popup'>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#707070" width="24" height="24" viewBox="0 0 24 24" class="button-close-form-popup">
                                <rect data-name="Rectangle 15970" width="24" height="24" fill="none"></rect>
                                <path data-name="Union 1" d="M5,11V7H1A1,1,0,0,1,1,5H5V1A1,1,0,0,1,7,1V5h4a1,1,0,0,1,0,2H7v4a1,1,0,0,1-2,0Z" transform="translate(12 3.515) rotate(45)"></path>
                            </svg>
                        </div>
                    </div>
                `
            );
            //aimktFormCommonFunc.createStyleHtml(formOptions.renderPublished.callOut.cssForm, elCallOut);
            const elLzInnerCtn = domBanner.querySelector(".lz-inner-ctn");
            elLzInnerCtn.appendChild(elBanner);

            me.responsiveImageBanner(bannerOptions, domBanner);
        },

        /**
         * Hảm render ra DOM cho Form popup dạng: Trái phải
         * @param {any} banner Đối tượng Form
         * @param {any} domBannerWrap Dom element Dom chứa Iframe
         * @param {any} domBanner Dom element Dom Iframe
         * @author PTBinh (04/03/2022)
         */
        renderBannerSlideinBoxLeftRight(banner, domBannerWrap, domBanner, type) {
            const me = this;
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            const bannerOptions = banner.BannerOptions;

            //Xử lý domBannerWrap
            const cssContentDomWrap = `

                        .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner{
                            box-shadow: 5px 20px 40px #00000033;
                        }
                        .lz-iframe-ctn{
                            bottom: -100%;
                            //bottom: 0;
                            top: unset;
                            ${type === aimktBannerConst.BannerType.SlideinBoxLeft ? 'left: 1em;' : 'right: 1em;'}
                            border-radius: 3px;
                            position: fixed;
                            box-shadow: 0 6px 20px #00000029;
                        }
                        .lz-iframe-ctn #${CLASS_NAME_BANNER}{
                            width: unset;
                        }
                        body .lz-main-ctn  .lz-inner-ctn .aimkt-layout-banner,
                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner{
                            position: relative;
                            background-image:url(${bannerOptions.bannerImage.urlImg}); 
                            width: ${bannerOptions.bannerImage.widthImage}px; 
                            height: ${bannerOptions.bannerImage.heightImage}px;
                            background-size: contain;
                            background-position:center;
                            background-repeat: no-repeat;
                        }

                        body .lz-main-ctn .lz-inner-ctn:hover{
                            cursor: pointer;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-free-license-warning {
                            left: 0;
                            bottom: 0;
                            width: 100%;
                            background-color: #ffffff;
                            border-top: 1px solid #ff5637;
                            border-bottom-left-radius: 8px;
                            border-bottom-right-radius: 8px;
                            padding: 4px 0;
                            text-align: center;
                            height: 36px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-free-license-warning a{
                            color: #454545;
                            text-decoration: none;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-free-license-warning .aim-free-license-warning__logo img{
                            height: 24px;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner .aim-banner__content-banner_close{
                            position: absolute;
                            top: 16px;
                            right: 16px;
                            background-color: #ffffff;
                            opacity: 50%;
                            width: 24px;
                            height: 24px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border-radius: 4px;
                        }

                        @media (max-width: 600px){
                            body .lz-main-ctn  .lz-inner-ctn .aim-banner__content-banner
                            {
                                width: 100%;
                            }

                            .lz-iframe-ctn{
                                top: 0;
                            }

                            .lz-iframe-ctn #${CLASS_NAME_BANNER}{
                                bottom: 16px;
                                top: unset;
                                position: fixed;
                                ${type === aimktBannerConst.BannerType.SlideinBoxLeft ? 'left: 16px;' : 'right: 16px;'}
                            }
                        }
                    }`;

            aimktFormCommonFunc.createStyleHtml(cssContentDomWrap, domBannerWrap);

            //Ẩn overlay
            document.querySelector(".lz-iframe-ctn .lz-iframe-ctn__overlay").classList.add("hidden");

            const elBanner = aimktFormCommonFunc.createElementFromHTML(
                `
                    <div class='aim-banner__content-banner'>
                        <div class=aim-banner__content-banner_image'>
                        </div>
                        <div class='aim-banner__content-banner_close aimkt-close-popup'>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#707070" width="24" height="24" viewBox="0 0 24 24" class="button-close-form-popup">
                                <rect data-name="Rectangle 15970" width="24" height="24" fill="none"></rect>
                                <path data-name="Union 1" d="M5,11V7H1A1,1,0,0,1,1,5H5V1A1,1,0,0,1,7,1V5h4a1,1,0,0,1,0,2H7v4a1,1,0,0,1-2,0Z" transform="translate(12 3.515) rotate(45)"></path>
                            </svg>
                        </div>
                    </div>
                `
            );
            //aimktFormCommonFunc.createStyleHtml(formOptions.renderPublished.callOut.cssForm, elCallOut);
            const elLzInnerCtn = domBanner.querySelector(".lz-inner-ctn");
            elLzInnerCtn.appendChild(elBanner);

            me.responsiveImageBanner(bannerOptions, domBanner);
        },
        //#end region

        //#region Hàm sử dụng render STEP CALL OUT.
        /**
         * Hảm render ra DOM cho Form popup dạng: PopupBox
         * @param {any} form Đối tượng Form
         * @param {any} domFormWrap Dom element Dom chứa Iframe
         * @param {any} domForm Dom element Dom Iframe
         * @author dvhung1 (14/08/2021)
         */
        renderCallOutPopupBox(form, domFormWrap, domForm) {
            const me = this;
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            const formOptions = form.FormOptions;

            let widthImage;
            if (form.FormOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly) {
                widthImage = form.FormOptions.callout["widthImage"] + 'px';
            }
            else {
                widthImage = form.Style["width"] ? form.Style["width"] : '600px';
            }

            //Xử lý domFormWrap
            let cssContentDomFrom = `
                                        body .lz-main-ctn{
                                            display: flex;
                                            height: 100%;
                                            width: 100%;
                                            justify-content: center;
                                        }
                                        body .lz-main-ctn *{ box-sizing: border-box; }
                                        body .lz-main-ctn .lz-inner-ctn{
                                            display: flex;
                                            max-width: 90%;
                                            overflow: auto;
                                            border-radius: 7px;
                                            flex-direction: column;
                                            align-items: center;
                                            position: absolute;
                                            width: ${widthImage};
                                            margin: 0;
                                            left: 50%;
                                            top: 50%;
                                            transform: translate(-50%, -50%);
                                        }
                        
                                        body .lz-main-ctn .lz-inner-ctn:hover{
                                            cursor: ${formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly ? 'pointer' : 'default'}
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .lz-callout-ctn{
                                            display: flex;
                                            justify-content: center;
                                            align-items: flex-start;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form{
                                            width: 100%;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning {
                                            left: 0;
                                            bottom: 0;
                                            width: 100%;
                                            background-color: #ffffff;
                                            border-top: 1px solid #ff5637;
                                            border-bottom-left-radius: 8px;
                                            border-bottom-right-radius: 8px;
                                            padding: 4px 0;
                                            text-align: center;
                                            height: 36px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning a{
                                            color: #454545;
                                            text-decoration: none;
                                        }

                                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning .aim-free-license-warning__logo img{
                                            height: 24px;
                                        }
                                        `;
            aimktFormCommonFunc.createStyleHtml(cssContentDomFrom, domForm);
            aimktFormCommonFunc.createStyleHtml(`.lz-iframe-ctn {
                            height: 100% !important;
                            width: 100% !important;
                            opacity: 0;
                            top: 0;
                            left: 0;
                            position: fixed;
                        }

                       .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}{
                            height: 100%;
                            width: 100%;
                        }
                        @media (max-width: 600px){
                            body .lz-main-ctn .lz-inner-ctn{
                                left: unset;
                                top: unset;
                                transform: unset;
                            }
                            .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}{
                                height: 100%;
                                width: unset;
                                min-width: 300px;
                            }
                        }

                        `, domFormWrap);

            //Thêm dom call out
            if (formOptions.callout.isCallOut) {
                const elCallOut = aimktFormCommonFunc.createElementFromHTML(formOptions.renderPublished.callOut.domHtml);
                aimktFormCommonFunc.createStyleHtml(formOptions.renderPublished.callOut.cssForm, elCallOut);

                //bổ sung watermark trong trường hợp license dùng thử
                if (form["LicenseType"] == aimktFormConstant.License.Trial) {
                    //const watermark = aimktFormCommonFunc.createElementFromHTML(
                    //    `
                    //    <div class="aim-free-license-warning">
                    //        <a href="https://amis.misa.vn/amis-aimarketing" target="_blank">
                    //            <div class="aim-free-license-warning__logo">
                    //                <img src="${me.publishedUrl}/content/images/LogoAmisAimkt.png" />
                    //            </div>
                    //            <div class="aim-free-license-warning__text aim-font-body">
                    //                ${aimktFormConstant.Message.Watermark}
                    //            </div>
                    //        </a>
                    //    </div>`
                    //);
                    //elCallOut.appendChild(watermark);

                    //Loại bỏ footer
                    me.clearFooterAimkt();
                }

                const elLzInnerCtn = domForm.querySelector(".lz-inner-ctn");
                elLzInnerCtn.appendChild(elCallOut);
            }

            if (formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly && formOptions.callout["isCallOut"]) {
                //responsive lại hình ảnh
                me.responsiveImage(formOptions, domForm);
            }
        },

        /**
         * Thực hiện responsive cho hình ảnh
         * @param {any} formOptions đối tượng form option
         * @param {any} domForm đói tượng form element
         */
        responsiveImage(formOptions, domForm) {
            var me = this;
            let maxWidthDevice = window.innerWidth;
            let maxHeightDevice = window.innerHeight;
            let widthImageCurrent = formOptions.callout["widthImage"];
            let heightImageCurrent = formOptions.callout["heightImage"];
            let ratioImage = widthImageCurrent / heightImageCurrent;
            if (maxWidthDevice / maxHeightDevice >= widthImageCurrent / heightImageCurrent) {
                me.responsiveHeightImage(domForm, maxHeightDevice, widthImageCurrent, heightImageCurrent);
            }
            else {
                me.responsiveWidthImage(domForm, maxWidthDevice, widthImageCurrent, heightImageCurrent);
            }
        },

        /**
         * Thực hiện responsive cho hình ảnh
         * @param {any} bannerOptions đối tượng banner option
         * @param {any} domBanner đói tượng banner element
         * @author PTBinh (08/03/2022)
         */
        responsiveImageBanner(bannerOptions, domBanner) {
            var me = this;
            let maxWidthDevice = window.innerWidth;
            let maxHeightDevice = window.innerHeight;
            let widthImageCurrent = bannerOptions.bannerImage["widthImage"];
            let heightImageCurrent = bannerOptions.bannerImage["heightImage"];
            let ratioImage = widthImageCurrent / heightImageCurrent;
            if (maxWidthDevice / maxHeightDevice >= widthImageCurrent / heightImageCurrent) {
                me.responsiveHeightImageBanner(domBanner, maxHeightDevice, widthImageCurrent, heightImageCurrent);
            }
            else {
                me.responsiveWidthImageBanner(domBanner, maxWidthDevice, widthImageCurrent, heightImageCurrent);
            }
        },

        /**
         * Thực hiện responsive theo height
         * @param {any} domBanner đối tượng banner
         * @param {any} maxHeightDevice chiều cao tối đa màn hình
         * @param {any} widthImageCurrent chiều dài hình ảnh
         * @param {any} heightImageCurrent chiều cao hình ảnh
         * @author PTBinh (08/03/2022)
         */
        responsiveHeightImageBanner(domBanner, maxHeightDevice, widthImageCurrent, heightImageCurrent) {
            let lzInner = domBanner.querySelector(".lz-inner-ctn");
            let content = domBanner.querySelector(".aim-banner__content-banner");
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            let lzInframe = document.querySelector(`.lz-iframe-ctn #${CLASS_NAME_BANNER}`);
            this.responsiveHeightImageCommon(content, lzInner, lzInframe, widthImageCurrent, heightImageCurrent, maxHeightDevice, aimktTypePopup.Banner);

        },

        /**
         * Thực hiện responsive theo width
         * @param {any} domBanner đối tượng banner
         * @param {any} maxWidthDevice chiều dài tối đa màn hình
         * @param {any} widthImageCurrent chiều dài hình ảnh
         * @param {any} heightImageCurrent chiều cao hình ảnh
         * @author PTBinh (08/03/2022)
         */
        responsiveWidthImageBanner(domBanner, maxWidthDevice, widthImageCurrent, heightImageCurrent) {
            let lzInner = domBanner.querySelector(".lz-inner-ctn");
            let content = domBanner.querySelector(".aim-banner__content-banner");
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            let lzInframe = document.querySelector(`.lz-iframe-ctn #${CLASS_NAME_BANNER}`);
            this.responsiveWidthImageCommon(content, lzInner, lzInframe, widthImageCurrent, heightImageCurrent, maxWidthDevice, aimktTypePopup.Banner);
        },

        /**
         * Thực hiện responsive theo height
         * @param {any} domForm đối tượng form
         * @param {any} maxHeightDevice chiều cao tối đa màn hình
         * @param {any} widthImageCurrent chiều dài hình ảnh
         * @param {any} heightImageCurrent chiều cao hình ảnh
         * @author PTBinh (16/11/2021)
         */
        responsiveHeightImage(domForm, maxHeightDevice, widthImageCurrent, heightImageCurrent) {
            let lzInner = domForm.querySelector(".lz-inner-ctn");
            let content = domForm.querySelector(".aim-form-popup-call__content-form");
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            let lzInframe = document.querySelector(`.lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}`);
            this.responsiveHeightImageCommon(content, lzInner, lzInframe, widthImageCurrent, heightImageCurrent, maxHeightDevice, aimktTypePopup.FormPopup);

        },

        /**
         * Thực hiện responsive theo width
         * @param {any} domForm đối tượng form
         * @param {any} maxWidthDevice chiều dài tối đa màn hình
         * @param {any} widthImageCurrent chiều dài hình ảnh
         * @param {any} heightImageCurrent chiều cao hình ảnh
         */
        responsiveWidthImage(domForm, maxWidthDevice, widthImageCurrent, heightImageCurrent) {
            let lzInner = domForm.querySelector(".lz-inner-ctn");
            let content = domForm.querySelector(".aim-form-popup-call__content-form");
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            let lzInframe = document.querySelector(`.lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}`);
            this.responsiveWidthImageCommon(content, lzInner, lzInframe, widthImageCurrent, heightImageCurrent, maxWidthDevice, aimktTypePopup.FormPopup);
        },

        /**
         * Hàm responsive height dùng chung cho form và banner
         * @param {any} content dom content
         * @param {any} lzInner dom inner
         * @param {any} lzInframe dom lấy theo id
         * @param {any} widthImageCurrent width image hiện tại
         * @param {any} heightImageCurrent height image hiện tại
         * @param {any} maxHeightDevice max height màn hình
         * @param {any} type Kiểu popup (0: formPopup, 1: banner)
         * @author PTBinh (08/03/2022)
         */
        responsiveHeightImageCommon(content, lzInner, lzInframe, widthImageCurrent, heightImageCurrent, maxHeightDevice, type) {
            if (heightImageCurrent > maxHeightDevice - 32) {
                lzInner.style.width = (maxHeightDevice - 32) * (widthImageCurrent / heightImageCurrent) + 'px';

                content.style.height = maxHeightDevice - 32 + 'px';
                content.style.minHeight = maxHeightDevice - 32 + 'px';
                content.style.width = '100%';

                if (type == aimktTypePopup.FormPopup) {
                    lzInframe.style.minWidth = (maxHeightDevice - 32) * (widthImageCurrent / heightImageCurrent) + 'px';
                }
            }
            else {
                lzInner.style.width = widthImageCurrent + 'px';
                content.style.height = heightImageCurrent + 'px';
                content.style.minHeight = heightImageCurrent + 'px';
                content.style.width = '100%';

                if (type == aimktTypePopup.FormPopup) {
                    lzInframe.style.minWidth = widthImageCurrent + 'px';
                }
            }
        },

        /**
         * Hàm responsive width dùng chung cho form và banner
         * @param {any} content dom content
         * @param {any} lzInner dom inner
         * @param {any} lzInframe dom lấy theo id
         * @param {any} widthImageCurrent width image hiện tại
         * @param {any} heightImageCurrent height image hiện tại
         * @param {any} maxWidthDevice max width màn hình
         * @param {any} type Kiểu popup (true: formPopup, false: banner)
         * @author PTBinh (08/03/2022)
         */
        responsiveWidthImageCommon(content, lzInner, lzInframe, widthImageCurrent, heightImageCurrent, maxWidthDevice, type) {
            if (widthImageCurrent > maxWidthDevice - 32) {
                lzInner.style.width = maxWidthDevice - 32 + 'px';

                content.style.height = (maxWidthDevice - 32) / (widthImageCurrent / heightImageCurrent) + 'px';
                content.style.minHeight = (maxWidthDevice - 32) / (widthImageCurrent / heightImageCurrent) + 'px';
                content.style.width = '100%';

                if (type == aimktTypePopup.FormPopup) {
                    lzInframe.style.minWidth = (maxWidthDevice - 32) / (widthImageCurrent / heightImageCurrent) + 'px';
                }
            }
            else {
                lzInner.style.width = widthImageCurrent + 'px';
                content.style.height = heightImageCurrent + 'px';
                content.style.minHeight = heightImageCurrent + 'px';
                content.style.width = '100%';

                if (type == aimktTypePopup.FormPopup) {
                    lzInframe.style.minWidth = widthImageCurrent + 'px';
                }
            }
        },

        /**
         * Hảm render ra DOM cho Form popup dạng: PopupBox
         * @param {any} form Đối tượng Form
         * @param {any} domFormWrap Dom element Dom chứa Iframe
         * @param {any} domForm Dom element Dom Iframe
         * @param {any} elContent Dom element call
         * @author dvhung1 (14/08/2021)
         */
        renderCallOutDropdownBanner(form, domFormWrap, domForm) {
            const me = this;
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            const formOptions = form.FormOptions;

            //Xử lý domFormWrap
            const cssContentDomWrap = `
                        .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form{
                        }
                        .lz-main-ctn{
                            display: ${formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly ? 'flex' : 'unset'};
                            justify-content: center;
                            align-items: center;
                        }
                        .lz-inner-ctn{
                            padding-bottom: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .lz-inner-ctn:hover{
                            cursor: ${formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly ? 'pointer' : 'default'}
                        }
                        
                        .lz-iframe-ctn{
                            width: 100% !important;
                            top: -100%;
                            left: 0;
                            position: fixed;
                        }
                       .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}{
                            width: 100%;
                        }
                        .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP} .aim-form-popup-call__content-form{
                            margin: 4px;
                            box-shadow: 0 6px 20px #00000029;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning {
                            left: 0;
                            bottom: 0;
                            width: 100%;
                            background-color: #ffffff;
                            border-top: 1px solid #ff5637;
                            border-bottom-left-radius: 8px;
                            border-bottom-right-radius: 8px;
                            padding: 4px 0;
                            text-align: center;
                            height: 36px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning a{
                            color: #454545;
                            text-decoration: none;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning .aim-free-license-warning__logo img{
                            height: 24px;
                        }

                        @media (max-width: 600px){
                            .lz-iframe-ctn.lz-iframe-ctn.lz-iframe-ctn{
                                top: 40px !important;
                            }
                            .lz-inner-ctn{
                                padding-bottom: 20px;
                                padding-top: 20px;
                            }
                            .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP} .aim-form-popup-call__content-form{
                                //width: calc(100% - 32px);
                                margin: 0 16px;
                            }
                            .form-wrapper{
                                padding-bottom: 20px;
                                padding-top: 20px;
                                margin-top: -20px;
                            }
                            .aimkt-layout-form .aimkt-layout-form__close{
                            }

                            .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}{
                                height: 100%;
                                width: unset;
                                min-width: 300px;
                            }
                        }
                        `;
            aimktFormCommonFunc.createStyleHtml(cssContentDomWrap, domFormWrap);

            //Ẩn overlay
            document.querySelector(".lz-iframe-ctn .lz-iframe-ctn__overlay").classList.add("hidden");

            //Thêm dom call out
            if (formOptions.callout.isCallOut) {
                const elCallOut = aimktFormCommonFunc.createElementFromHTML(formOptions.renderPublished.callOut.domHtml);
                aimktFormCommonFunc.createStyleHtml(formOptions.renderPublished.callOut.cssForm, elCallOut);

                //bổ sung watermark trong trường hợp license dùng thử
                if (form["LicenseType"] == aimktFormConstant.License.Trial) {
                    //const watermark = aimktFormCommonFunc.createElementFromHTML(
                    //    `
                    //    <div class="aim-free-license-warning">
                    //        <a href="https://amis.misa.vn/amis-aimarketing" target="_blank">
                    //            <div class="aim-free-license-warning__logo">
                    //                <img src="${me.publishedUrl}/content/images/LogoAmisAimkt.png" />
                    //            </div>
                    //            <div class="aim-free-license-warning__text aim-font-body">
                    //                ${aimktFormConstant.Message.Watermark}
                    //            </div>
                    //        </a>
                    //    </div>`
                    //);
                    //elCallOut.appendChild(watermark);

                    //Loại bỏ footer
                    me.clearFooterAimkt();
                }

                const elLzInnerCtn = domForm.querySelector(".lz-inner-ctn");
                elLzInnerCtn.appendChild(elCallOut);
            }

            if (formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly && formOptions.callout["isCallOut"]) {
                //responsive lại hình ảnh
                me.responsiveImage(formOptions, domForm);
            }
        },

        /**
         * Hảm render ra DOM cho Form popup dạng: PopupBox
         * @param {any} form Đối tượng Form
         * @param {any} domFormWrap Dom element Dom chứa Iframe
         * @param {any} domForm Dom element Dom Iframe
         * @author dvhung1 (14/08/2021)
         */
        renderCallOutSlideinBoxLeftRight(form, domFormWrap, domForm) {
            const me = this;
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            const formOptions = form.FormOptions;

            //Xử lý domFormWrap
            const cssContentDomWrap = `

                        .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form{
                            box-shadow: 5px 20px 40px #00000033;
                        }
                        .lz-iframe-ctn{
                            bottom: -100%;
                            top: unset;
                            ${form.PopupFormType === aimktFormConstant.PopupFormType.SlideinBoxLeft ? 'left: 1em;' : 'right: 1em;'}
                            border-radius: 3px;
                            position: fixed;
                            box-shadow: 0 6px 20px #00000029;
                        }
                        .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}{
                            height: 100%;
                            //max-height: 500px;
                            width: unset;
                            min-width: ${formOptions.callout["urlImg"] && formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly ? formOptions.callout["widthImage"] : '300px'};
                        }
                        body .lz-main-ctn  .lz-inner-ctn .aimkt-layout-form,
                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form{

                        }

                        body .lz-main-ctn .lz-inner-ctn:hover{
                            cursor: ${formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly ? 'pointer' : 'default'}
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning {
                            left: 0;
                            bottom: 0;
                            width: 100%;
                            background-color: #ffffff;
                            border-top: 1px solid #ff5637;
                            border-bottom-left-radius: 8px;
                            border-bottom-right-radius: 8px;
                            padding: 4px 0;
                            text-align: center;
                            height: 36px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning a{
                            color: #454545;
                            text-decoration: none;
                        }

                        body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form .aim-free-license-warning .aim-free-license-warning__logo img{
                            height: 24px;
                        }

                        @media (max-width: 600px){
                            body .lz-main-ctn  .lz-inner-ctn .aim-form-popup-call__content-form
                            {
                                width: 100%;
                            }

                            .lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}{
                                height: 100%;
                                width: unset;
                                min-width: 300px;
                            }
                        }
                    }`;

            aimktFormCommonFunc.createStyleHtml(cssContentDomWrap, domFormWrap);

            //Ẩn overlay
            document.querySelector(".lz-iframe-ctn .lz-iframe-ctn__overlay").classList.add("hidden");

            //Thêm dom call out
            if (formOptions.callout.isCallOut) {
                const elCallOut = aimktFormCommonFunc.createElementFromHTML(formOptions.renderPublished.callOut.domHtml);
                aimktFormCommonFunc.createStyleHtml(formOptions.renderPublished.callOut.cssForm, elCallOut);

                //bổ sung watermark trong trường hợp license dùng thử
                if (form["LicenseType"] == aimktFormConstant.License.Trial) {
                    //const watermark = aimktFormCommonFunc.createElementFromHTML(
                    //    `
                    //    <div class="aim-free-license-warning">
                    //        <a href="https://amis.misa.vn/amis-aimarketing" target="_blank">
                    //            <div class="aim-free-license-warning__logo">
                    //                <img src="${me.publishedUrl}/content/images/LogoAmisAimkt.png" />
                    //            </div>
                    //            <div class="aim-free-license-warning__text aim-font-body">
                    //                ${aimktFormConstant.Message.Watermark}
                    //            </div>
                    //        </a>
                    //    </div>`
                    //);
                    //elCallOut.appendChild(watermark);

                    //Loại bỏ footer
                    me.clearFooterAimkt();
                }

                const elLzInnerCtn = domForm.querySelector(".lz-inner-ctn");
                elLzInnerCtn.appendChild(elCallOut);
            }

            if (formOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly && formOptions.callout["isCallOut"]) {
                //responsive lại hình ảnh
                me.responsiveImage(formOptions, domForm);
            }
        },

        /**
        * Hàm thực hiện đẩy dữ liệu option vào select
        * @param {any} target đối tượng select
        * @param {array} data danh sách data option
        * @author DVHAI (14/07/2021)
        */
        renderOptionDropdown(target, data = []) {
            if (!target) return;

            target.innerHTML = '';
            let row = `<option hidden="" value="">${aimktFormConstant.Dropdown.Message}</option>`;

            data.forEach(item => {
                row += `<option value="${item.locationName}">${item.locationName}</option>`;
            })

            target.innerHTML = row;
        },

        /**
        * Render Layout của Form
        * @param {any} form
        * @param {any} domForm
        */
        renderLayoutForm(form, domForm) {
            const me = this;
            const formObj = this.convertFormDataToObj(form);
            const formOptions = form.FormOptions;

            //Bổ sung tiêu đề + content:
            let titleForm = formOptions["layout"]["title"] ? formOptions["layout"]["title"] : formOptions["callout"]["titleForm"];
            let content = formOptions["layout"] ? formOptions["layout"]["content"] : "";

            const elLayout = aimktFormCommonFunc.createElementFromHTML(`<div class="aimkt-layout-form">
                <div class="aimkt-layout-form__close aimkt-close-popup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#707070" width="24" height="24" viewBox="0 0 24 24" class="button-close-form-popup">
                        <rect data-name="Rectangle 15970" width="24" height="24" fill="none"></rect>
                        <path data-name="Union 1" d="M5,11V7H1A1,1,0,0,1,1,5H5V1A1,1,0,0,1,7,1V5h4a1,1,0,0,1,0,2H7v4a1,1,0,0,1-2,0Z" transform="translate(12 3.515) rotate(45)"></path>
                    </svg>
                </div>
                    <div class="aimkt-layout-form_static">
                        <div class="aimkt-layout-form_title">${titleForm}</div>
                        <div class="aimkt-layout-form_content">${content}</div>
                    </div>
              
            </div>`);


            let cssLayoot = `
                .aimkt-layout-form{
                    display: flex;
                    //flex-direction: column;
                    background: transparent;
                    //box-shadow: 0 3px 6px #00000029;
                    position: relative;
                    width: 100%;
                }

                .aimkt-layout-form .aimkt-layout-form__close{
                    position: absolute;
                    top: 16px;
                    right: 16px;
                    opacity: 0.5;
                }
                .aimkt-layout-form .aimkt-layout-form__close:hover{
                    opacity: 1;
                    cursor: pointer;
                }

                .aimkt-layout-form .form-wrapper{
                    width: 100%;
                }

                .aimkt-layout-form .form-wrapper .aim-free-license-warning {
                    left: 0;
                    bottom: 0;
                    width: 100%;
                    background-color: #ffffff;
                    border-top: 1px solid #ff5637;
                    border-bottom-left-radius: 8px;
                    border-bottom-right-radius: 8px;
                    padding: 4px 0;
                    text-align: center;
                            height: 36px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                }

                .aimkt-layout-form .form-wrapper .aim-free-license-warning a{
                    color: #454545;
                    text-decoration: none;
                }

                .aimkt-layout-form .form-wrapper .aim-free-license-warning__logo img{
                    height: 24px;
                }

                .aimkt-layout-form .aimkt-layout-form_static{
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                }

                .aimkt-layout-form .aimkt-layout-form_title{
                    font-size: 24px;
                    font-weight: bold;
                    color: #000000;
                    word-break: break-word;
                }

                .aimkt-layout-form .aimkt-layout-form_content{
                    font-size: 14px;
                    color: #000000;
                }

                @media screen and (max-width: 768px) {
                    .aimkt-layout-form .aimkt-layout-form_content br {
                        display: block;
                        content: ""; /* Đảm bảo xuống dòng */
                    }
                }


                .aimkt-layout-form_form-childrens{
                    width: 100%;
                }
                .aimkt-layout-form_form-wrap-container{
                    width: 960px;
                    margin: 0 auto;
                }

                ${form.PopupFormType != aimktFormConstant.PopupFormType.PopupBox ?
                    `
                    .aimkt-layout-form form.private-form.private-form.private-form.private-form{
                        box-shadow: 0 6px 20px #00000029;
                    }
                ` : ``}

                ${form.PopupFormType == aimktFormConstant.PopupFormType.DropdownBanner ?
                    `
                .aimkt-layout-form{
                    //box-shadow: 0 6px 20px #00000029;
                }
                .aimkt-layout-form form.private-form.private-form.private-form.private-form{
                    width: 100% !important;
                    box-shadow: 0 6px 20px #00000029;
                    max-height: 50vh;
                    overflow-y: auto !important;
                }
                
                .aimkt-layout-form form.private-form.private-form.private-form.private-form::-webkit-scrollbar-thumb {
                    height: 6px;
                    width: 6px;
                    border: none;
                    background-clip: padding-box;
                    -webkit-border-radius: 4px;
                    background-color: #c0c4cc;
                    -webkit-box-shadow: inset -1px -1px 0px rgb(0 0 0 / 5%), inset 1px 1px 0px rgb(0 0 0 / 5%);
                }

                .aimkt-layout-form form.private-form.private-form.private-form.private-form::-webkit-scrollbar {
                    width: 6px;
                    height: 6px;
                    background-color: #e9ebee;
                    border-radius: 2px;
                  }

                .aimkt-layout-form .aimkt-layout-form_form-wrap-container{
                    display: flex;
                }
                .aimkt-layout-form .form-wrapper{
                    padding-bottom: 20px;
                }
                `:
                    ``
                }

                @media (max-width: 960px){
                    .aimkt-layout-form form.private-form.private-form{
                        width: 100% !important;
                    }
                    .aimkt-layout-form .aimkt-layout-form_form-wrap-container{
                        width: 100%;
                        margin: 0 auto;
                    }
                }

                @media (max-width: 767px){
                    .aimkt-layout-form form.private-form.private-form{
                        width: 100% !important;
                    }
                    .aimkt-layout-form .aimkt-layout-form_form-wrap-container{
                        width: 100%;
                        margin: 0 auto;
                    }
                    .aimkt-layout-form .aimkt-layout-form_form-wrap-container{
                        flex-direction: column;
                    }

                    .aimkt-layout-form .form-wrapper .private-form{
                        //max-height: calc(100vh - 60px) !important;
                        overflow-y: auto !important;
                    }

                    .aimkt-layout-form .form-wrapper .aim-free-license-warning{
                        height: 56px;
                    }

                    .aimkt-layout-form .form-wrapper .aim-free-license-warning .aim-watermark-form-desktop{
                        display: none;
                    }
                    
                    .aimkt-layout-form .form-wrapper .aim-free-license-warning .aim-watermark-form-mobile{
                        display: block;
                        height: 40px;
                    }
                }

                @media (min-width: 768px){
                    .aimkt-layout-form .form-wrapper .aim-free-license-warning{
                        height: 36px;
                    }
                    .aimkt-layout-form .form-wrapper .aim-free-license-warning .aim-watermark-form-desktop{
                        display: block;
                        height: 24px;
                    }
                    
                    .aimkt-layout-form .form-wrapper .aim-free-license-warning .aim-watermark-form-mobile{
                        display: none;
                    }
                }

            `;

            //Ẩn stage 2 layout: TODO-dvhung1: Kiểm tra có sang bước 2 luôn hay không
            elLayout.classList.add("hidden");

            //Thêm vào dom
            const elLzInnerCtn = domForm.querySelector(".lz-inner-ctn");
            elLzInnerCtn.appendChild(elLayout);
            aimktFormCommonFunc.createStyleHtml(cssLayoot, elLayout);

            //Thêm FormRow
            let domLayoutFormModel = this.renderDomFormByModel(formObj);
            let elForm = domLayoutFormModel.querySelector("form");

            //Bổ sung static vào form nếu là Form type khác loại hiển thị từ trên xuống
            if (form.PopupFormType !== aimktFormConstant.PopupFormType.DropdownBanner) {
                let elStatic = elLzInnerCtn.querySelector(".aimkt-layout-form_static");
                elForm.insertBefore(elStatic, elForm.firstChild);
            } else {
                let elChilds = elForm.children;
                let eldChildWrap = aimktFormCommonFunc.createElementFromHTML('<div class="aimkt-layout-form_form-childrens"></div>');
                while (elChilds.length > 0) {
                    eldChildWrap.appendChild(elChilds[0]);
                }

                let elStatic = elLzInnerCtn.querySelector(".aimkt-layout-form_static");

                let elWrapFormContainer = aimktFormCommonFunc.createElementFromHTML('<div class="aimkt-layout-form_form-wrap-container"></div>');
                elWrapFormContainer.appendChild(elStatic);
                elWrapFormContainer.appendChild(eldChildWrap);

                elForm.appendChild(elWrapFormContainer);
            }

            //Bổ sung watermark nếu license là trial
            if (form["LicenseType"] == aimktFormConstant.License.Trial) {
                let img;
                if (form.PopupFormType == aimktFormConstant.PopupFormType.DropdownBanner || form.PopupFormType == aimktFormConstant.PopupFormType.PopupBox) {
                    img = me.publishedUrl + '/content/images/watermarkHorizontal.svg';
                }
                else {
                    img = me.publishedUrl + '/content/images/Logo-Form-Watermark.svg';
                }
                const watermark = aimktFormCommonFunc.createElementFromHTML(
                    `
                        <div class="aim-free-license-warning">
                            <a href="https://amis.misa.vn/amis-aimarketing" target="_blank">
                                <div class="aim-free-license-warning__logo">
                                    <img src="${img}" class="aim-watermark-form-desktop"/>
                                    <img src="${me.publishedUrl}/content/images/Logo-Form-Watermark.svg" class="aim-watermark-form-mobile"/>
                                </div>
                            </a>
                        </div>`
                );
                domLayoutFormModel.appendChild(watermark);

                //Loại bỏ footer
                me.clearFooterAimkt();
            }

            elLayout.appendChild(domLayoutFormModel);

            let {
                formStyleStr,
                formSubmitStyleStr,
                formFieldStyleStr,
                formSubmitWrapperStyleStr,
                formLineHeight,
                formRowStyleStr,
                helpTextStyleStr,
                placeholderStyleStr,
                isLabelInInput,
                labelInInputStyleStr,
                labelStyleStr,
                dimensionInput,
                contentMultiCheck
            } = aimktFormCommonFunc.convertObjectStyleToCss(formObj);

            let styleContentForm = aimktFormCommonFunc.getStyleCssContent({
                form: formObj,
                formStyleStr,
                formSubmitWrapperStyleStr,
                formLineHeight,
                formFieldStyleStr,
                formSubmitStyleStr,
                formRowStyleStr,
                helpTextStyleStr,
                placeholderStyleStr,
                isLabelInInput,
                labelInInputStyleStr,
                labelStyleStr,
                dimensionInput,
                contentMultiCheck
            });
            aimktFormCommonFunc.createStyleHtml(styleContentForm, domForm);

            //Đổi css cho dom wrap form: TODO: Đang lỗi với loại form trượt từ trên xuống
            elLayout.style.background = elForm.style.background;
            elLayout.style.boxShadow = elForm.style.boxShadow;
            elForm.style.background = "";
            elForm.style.boxShadow = "";

            //Bổ sung script tracking ucap
            const linkScripts = [`${this.trackingUrl}/ucap/form-ucap.js?tenantId=${form.TenantID}&formid=${form.FormID}&domainUnomi=${this.trackingUrl}&lpageid=${ldpageId}`];
            aimktFormCommonFunc.createLinkScript(linkScripts, domForm);

            runViewPage(domForm, form.TenantID, this.trackingUrl);
        },

        /**
         * Thiết lập step đang hiển thị hiện tại
         * @param {Number} step Step muốn chọn (1: CallOut; 2: Layout; 3: Thanks)
         * @param {*} form đối tượng form
         * @author dvhung1 (07/09/2021)
         */
        setShowUIStep(step, form) {
            const me = this;
            const domForm = document.querySelector(".lz-iframe-ctn");
            if (domForm === null) return;

            const FORM_POPUP_STEP = aimktFormConstant.FormPopupStep;
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;

            const elCallout = domForm.querySelector(".aim-form-popup-call__content-form");
            const elLayout = domForm.querySelector(".aimkt-layout-form");
            const elOverlay = document.querySelector(".lz-iframe-ctn .lz-iframe-ctn__overlay");
            const elDomFormContent = domForm.querySelector(`#${CLASS_NAME_FORM_POPUP}`);


            switch (step) {
                case FORM_POPUP_STEP.Callout:
                    break;
                case FORM_POPUP_STEP.LayoutForm:
                    elCallout && elCallout.classList.add("hidden");
                    elLayout && elLayout.classList.remove("hidden");
                    me.removeCssCallout(domForm, form);
                    me.autoFocusFirstInput(domForm);
                    break;
                case FORM_POPUP_STEP.Thanks:
                    elOverlay && elOverlay.classList.remove("hidden");
                    elLayout.classList.add("hidden");
                    elDomFormContent.classList.add("hidden");
                    break;
                default:
            }
        },

        /**
         * Hàm thực hiện autofocus và input đầu tiên trong form
         * @param domForm element của form
         * @author PTBinh (23/11/2021)
         * */
        autoFocusFirstInput(domForm) {
            let input = domForm.getElementsByClassName('form-wrapper')[0].getElementsByClassName('form-row')[0].firstChild.getElementsByTagName('input')[0];
            if (input) {
                input.select();
            }
        },

        /**
         * Remove các css của callout bổ sung thêm
         * @param {any} domForm đối tượng form html
         * @param {any} form đối tượng form
         * @author PTBinh (16/11/2021)
         */
        removeCssCallout(domForm, form) {
            let maxWidthDevice = window.innerWidth;
            let popupFormType = form["PopupFormType"];
            let width;
            let top = 'unset';
            if (popupFormType == aimktFormConstant.PopupFormType.PopupBox) {
                if (form.FormOptions.layout["width"]) {
                    width = form.FormOptions.layout["width"];
                }
                else {
                    width = form.Style["width"] ? form.Style["width"] : '600px';
                }
                top = '60px';
            }
            else if (popupFormType == aimktFormConstant.PopupFormType.DropdownBanner) {
                width = 'unset';
                top = '0px';
            }
            else {
                if (form.FormOptions.layout["width"]) {
                    width = form.FormOptions.layout["width"];
                }
                else {
                    width = form.Style["width"] ? form.Style["width"] : '600px';
                }
            }
            let lzInner = domForm.querySelector('.lz-inner-ctn');
            let lzIframe = document.querySelector('.lz-iframe-ctn');
            let lzMain = domForm.querySelector('.lz-main-ctn');
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            let lzIframeClass = document.querySelector(`.lz-iframe-ctn #${CLASS_NAME_FORM_POPUP}`);
            lzInner.style.margin = 'unset';
            lzInner.style.left = 0;
            lzInner.style.right = 0;
            lzInner.style.top = 'unset';
            lzInner.style.transform = 'unset';
            lzInner.style.marginLeft = 'auto';
            lzInner.style.marginRight = 'auto';

            if (maxWidthDevice < 600) {
                lzInner.style.width = '100%';
                lzIframe.style.width = '90%';
            }
            else {
                lzInner.style.width = width;
            }

            lzIframe.style.top = top;

            lzMain.style.display = 'unset';
            lzMain.justifyContent = 'unset';
            lzMain.alignItems = 'unset';

            lzIframeClass.style.minWidth = 'unset';
        },

        /**
         * Đóng Form Popup
         * @param {any} form Đối tượng Form hiện tại
         * @author dvhung1 (07/09/2021)
         * */
        closeFormPopupUI(form) {
            let elFormPopup = document.querySelector(`.lz-iframe-ctn`);
            if (elFormPopup !== null) elFormPopup.parentNode.removeChild(elFormPopup);

            this.setLastTimeShowFormPopup(form);
        },

        /**
         * Đóng Banner
         * @param {any} banner Đối tượng Banner hiện tại
         * @author PTBinh (04/03/2022)
         * */
        closeBannerUI(banner) {
            let elBanner = document.querySelector(`.lz-iframe-ctn`);
            if (elBanner !== null) elBanner.parentNode.removeChild(elBanner);

            this.setLastTimeShowBanner(banner);
        },

        /**
         * Delete footer aim when page preview form aim
         * @author dvhung1 (22/10/2021)
         * */
        clearFooterAimkt() {
            var ispageAim = document.querySelector("#aim-form-preview");
            if (ispageAim == null) return;

            //Xoa footer aim
            var footer = ispageAim.querySelector("#aim-footer-design");
            if (footer) {
                if (footer.parentNode !== null) {
                    footer.parentNode.removeChild(footer);
                }
            }
        },
        //#endregion

        /**
         * Hàm thực hiện tracking utm
         * @param {any} banner thông tin của banner
         * @author PTBinh (11/03/2022)
         */
        trackingUtm(banner) {
            //tracking utm
            let formData = {};
            const PROPERTY_NAME = aimktFormConstant.PropertyName;

            //Bổ sung thông tin utm khi click banner
            let valParamUtmSource = this.getUrlParameter(PROPERTY_NAME.UtmSource);
            if (valParamUtmSource) formData[PROPERTY_NAME.UtmSource] = valParamUtmSource;

            let valParamUtmTerm = this.getUrlParameter(PROPERTY_NAME.UtmTerm);
            if (valParamUtmTerm) formData[PROPERTY_NAME.UtmTerm] = valParamUtmTerm;

            let valParamUtmMedium = this.getUrlParameter(PROPERTY_NAME.UtmMedium);
            if (valParamUtmMedium) formData[PROPERTY_NAME.UtmMedium] = valParamUtmMedium;

            let valParamUtmContent = this.getUrlParameter(PROPERTY_NAME.UtmContent);
            if (valParamUtmContent) formData[PROPERTY_NAME.UtmContent] = valParamUtmContent;

            let valParamUtmCampaign = this.getUrlParameter(PROPERTY_NAME.UtmCampaign);
            if (valParamUtmCampaign) formData[PROPERTY_NAME.UtmCampaign] = valParamUtmCampaign;

            let valParamGclid = this.getUrlParameter(PROPERTY_NAME.Gclid);
            if (valParamGclid) formData[PROPERTY_NAME.Gclid] = valParamGclid;

            let valParamFbclid = this.getUrlParameter(PROPERTY_NAME.Fbclid);
            if (valParamFbclid) formData[PROPERTY_NAME.Fbclid] = valParamFbclid;

            let valParamEmailId = this.getUrlParameter(PROPERTY_NAME.EmailId);
            if (valParamEmailId) formData[PROPERTY_NAME.EmailId] = valParamEmailId;

            formData = {
                ...formData,
                TenantID: banner.TenantID
            };

            return formData;
        },

        /**
         * Khởi tạo các sự kiện để hoạt động Banner
         * @param {any} domBanner DOM iframe chứa banner
         * @param {any} banner Đối tượng Banner
         * @author PTBinh (04/03/2022)
         */
        initEventBanner(domBanner, banner, callbackClickCloseBanner, callbackClickBanner) {
            const that = this;
            const CLASS_NAME_BANNER = aimktBannerConst.ClassName.DomBanner;
            const TIME_ANIMATION_SHOW_POPUP = aimktBannerConst.Config.TimeAnimationShowPopup;
            const bannerOptions = banner.BannerOptions;
            const elDomBannerWrap = domBanner.parentNode;
            let labelInInput;

            //Các sự kiện có trong Banner
            aimktFormCommonFunc.documentReady(() => {
                let maxWidthDevice = window.innerWidth;
                //Khởi tạo hiệu ứng hiện thị popup lên màn hình.
                switch (banner.BannerType) {
                    case aimktBannerConst.BannerType.PopupBox:
                        domBanner.parentNode.style.opacity = 1; // animated

                        domBanner.classList.add("animated");
                        domBanner.classList.add("fadeIn");
                        break;
                    case aimktBannerConst.BannerType.DropdownBanner:
                        domBanner.parentNode.style.top = 0;

                        domBanner.classList.add("animated");
                        domBanner.classList.add("fadeInDown");
                        break;
                    case aimktBannerConst.BannerType.SlideinBoxLeft:
                        if (maxWidthDevice < 600) {
                            domBanner.style.bottom = '0';
                            domBanner.classList.add("animated");
                            domBanner.classList.add("fadeInLeft");
                        }
                        else {
                            domBanner.parentNode.style.bottom = '1em';
                            domBanner.parentNode.classList.add("animated");
                            domBanner.parentNode.classList.add("fadeInLeft");
                        }

                        break;
                    case aimktBannerConst.BannerType.SlideinBoxRight:
                        if (maxWidthDevice < 600) {
                            domBanner.style.bottom = '0';
                            domBanner.classList.add("animated");
                            domBanner.classList.add("fadeInRight");
                        }
                        else {
                            domBanner.parentNode.style.bottom = '1em';
                            domBanner.parentNode.classList.add("animated");
                            domBanner.parentNode.classList.add("fadeInRight");
                        }
                        break;
                    default:
                        domBanner.parentNode.style.bottom = '1em';
                        break;
                }

                //Clear animation sau khi đã hiển thị xong
                setTimeout(() => {
                    domBanner.classList.remove("animated");
                    domBanner.parentNode.classList.remove("animated");
                }, 1000);

                ////Sự kiện đóng Popup :stage cuối
                //elDomBannerWrap.addEventListener("click", function (e) {
                //    // loop parent nodes from the target to the delegation node
                //    for (var target = e.target; target && target != this; target = target.parentNode) {
                //        if (target.matches('.close-popup')) {
                //            debugger
                //            that.closeBannerUI(banner);
                //            break;
                //        }
                //        if (target.matches('.aimkt-close-popup')) {
                //            debugger
                //            that.closeBannerUI(banner);
                //            break;
                //        }
                //    }
                //}, false);
                //domBanner.querySelector(".lz-main-ctn").addEventListener("click", (event) => {
                //    if (event.target === event.currentTarget) {
                //        that.closeBannerUI(banner);
                //    }
                //});

                //sự kiện khi click vào banner thì chuyển sang trang mới
                let elPopupBanner = domBanner.querySelector('.aim-banner__content-banner');
                elPopupBanner && elPopupBanner.addEventListener("click", (e) => {
                    let isClose = false;
                    for (var target = e.target; target && target != this; target = target.parentNode) {
                        if (!target.matches) {
                            break;
                        }
                        if (target.matches('.close-popup')) {
                            that.closeBannerUI(banner);
                            isClose = true;
                            if (callbackClickCloseBanner != null && typeof callbackClickCloseBanner == "function") {
                                callbackClickCloseBanner();
                            }
                        }
                        if (target.matches('.aimkt-close-popup')) {
                            that.closeBannerUI(banner);
                            isClose = true;
                            if (callbackClickCloseBanner != null && typeof callbackClickCloseBanner == "function") {
                                callbackClickCloseBanner();
                            }
                        }
                    }
                    if (isClose == false) {
                        window.open(banner.URLRedirect, '_blank');
                        //Ẩn hiện UI
                        if (banner.BannerOptions.option["autoClose"] == true) {
                            that.closeBannerUI(banner);
                        }

                        //tracking utm
                        let formData = {};
                        formData = this.trackingUtm(banner);

                        let lpage = this.getPageInfo();

                        //Gọi api tracking click banner
                        try {
                            fetch(this.trackingUrl + '/api/ucap/eventcollector', {
                                method: 'POST',
                                headers: {
                                    'Content-type': 'text/plain; charset=utf-8',
                                    'Access-Control-Allow-Origin': '*',
                                },
                                body: JSON.stringify({
                                    events: [
                                        {
                                            eventType: globalThis.lzpr.eventTypeBannerClick,
                                            scope: globalThis.lzpr.scopeBanner,
                                            source: {
                                                itemType: 'landingPageId',
                                                properties: lpage,
                                                itemId: lpage['lpageId'],
                                            },
                                            target: {
                                                itemType: 'bannerId',
                                                itemId: banner.BannerID,
                                                properties: {
                                                    TargetName: banner.BannerName,
                                                },
                                            },
                                            properties: {
                                                ...formData,
                                                Email: that.getUrlParameter('emailaddress') || undefined,
                                                ProfileID: that.getCookie('ucap_profile_id'),
                                                lpageId: lpage['lpageId'],
                                                pageName: lpage['pageName'],
                                                bannerID: banner.BannerID,
                                                bannerName: banner.BannerName
                                            },
                                        },
                                    ],
                                }),
                            });
                        } catch (error) {
                            console.error('aimarketing_error GET FORM - ', error);
                        }

                        if (callbackClickBanner != null && typeof callbackClickBanner == "function") {
                            callbackClickBanner();
                        }
                    }

                });

            });
        },

        /**
         * Khởi tạo các sự kiện để hoạt động FormPopup
         * @param {any} domForm DOM iframe chưa FormPopup
         * @param {any} form Đối tượng Form
         * @author dvhung1 (14/08/2021)
         */
        initEventFormPopup(domForm, form) {
            const that = this;
            const POPUP_FORM_TYPE = aimktFormConstant.PopupFormType;
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            const TIME_ANIMATION_SHOW_POPUP = aimktFormPopupConst.Config.TimeAnimationShowPopup;
            const formOptions = form.FormOptions;
            const elDomFormWrap = domForm.parentNode;
            let labelInInput;

            if (form['DefaultStyles']) {
                let defaultStyles = JSON.parse(form.DefaultStyles);
                if (defaultStyles['LabelInInput']) {
                    labelInInput = defaultStyles.LabelInInput;
                }
                else {
                    labelInInput = false;
                }
            }
            else {
                labelInInput = false;
            }

            //Các sự kiện có trong Form
            aimktFormCommonFunc.documentReady(() => {
                let maxWidthDevice = window.innerWidth;
                //Khởi tạo hiệu ứng hiện thị popup lên màn hình.
                switch (form.PopupFormType) {
                    case POPUP_FORM_TYPE.PopupBox:
                        domForm.parentNode.style.opacity = 1; // animated

                        domForm.classList.add("animated");
                        domForm.classList.add("fadeIn");
                        break;
                    case POPUP_FORM_TYPE.DropdownBanner:
                        domForm.parentNode.style.top = 0;

                        domForm.classList.add("animated");
                        domForm.classList.add("fadeInDown");
                        break;
                    case POPUP_FORM_TYPE.SlideinBoxLeft:
                        if (maxWidthDevice < 600) {
                            domForm.parentNode.style.bottom = 'unset';
                        }
                        else {
                            domForm.parentNode.style.bottom = '1em';
                        }

                        domForm.parentNode.classList.add("animated");
                        domForm.parentNode.classList.add("fadeInLeft");
                        break;
                    case POPUP_FORM_TYPE.SlideinBoxRight:
                        if (maxWidthDevice < 600) {
                            domForm.parentNode.style.bottom = 'unset';
                        }
                        else {
                            domForm.parentNode.style.bottom = '1em';
                        }

                        domForm.parentNode.classList.add("animated");
                        domForm.parentNode.classList.add("fadeInRight");
                        break;
                    default:
                        domForm.parentNode.style.bottom = '1em';
                        break;
                }

                //Clear animation sau khi đã hiển thị xong
                setTimeout(() => {
                    domForm.classList.remove("animated");
                    domForm.parentNode.classList.remove("animated");
                }, 1000);


                //Sự kiện đóng Popup :stage cuối
                elDomFormWrap.addEventListener("click", function (e) {
                    // loop parent nodes from the target to the delegation node
                    for (var target = e.target; target && target != this; target = target.parentNode) {
                        if (target.matches('.close-popup')) {
                            that.closeFormPopupUI(form);
                            break;
                        }
                        if (target.matches('.aimkt-close-popup')) {
                            that.closeFormPopupUI(form);
                            break;
                        }
                    }
                }, false);
                domForm.querySelector(".lz-main-ctn").addEventListener("click", (event) => {
                    if (event.target === event.currentTarget) {
                        that.closeFormPopupUI(form);
                    }
                });

                //Action giữa các step
                let elCalloutBtn = domForm.querySelector('.callout-btn');
                elCalloutBtn && elCalloutBtn.addEventListener("click", () => {
                    //Ẩn hiện UI
                    that.setShowUIStep(aimktFormConstant.FormPopupStep.LayoutForm, form);
                });

                // Event bấm vào popup call thì sang step 2 (chỉ dành cho callout chỉ có hình ảnh)
                if (form.FormOptions.callout["selectTemplate"] == aimktFormConstant.TemplateCallout.ImageOnly) {
                    let elPopupCall = domForm.querySelector('.aim-form-popup-call__content-form');
                    elPopupCall && elPopupCall.addEventListener("click", () => {
                        //Ẩn hiện UI
                        if (form.FormOptions.callout["actionCallout"] == aimktFormConstant.ActionCallOut.GoToSubmitForm || !form.FormOptions.callout["actionCallout"]) {
                            that.setShowUIStep(aimktFormConstant.FormPopupStep.LayoutForm, form);
                        }
                        else {
                            that.closeFormPopupUI(form);
                        }
                    });
                }

                //Thêm sự kiện
                let elForm = domForm.querySelector('.private-form');
                this.onReadyFormNomal(form, elForm, { isLabelInInput: labelInInput });
            });
        },

        /**
         * Hàm xử lý khi submit thành công.
         * @param {any} event
         * @param {any} form
         */
        handleSubmitFormNomalSussces(event, form) {
            const me = this;
            me.setLastTimeShowFormPopup(form, IsSubmitForm = true);
            const CLASS_NAME_FORM_POPUP = aimktFormPopupConst.ClassName.DomFormPopup;
            const elDomFormContent = document.querySelector(`#${CLASS_NAME_FORM_POPUP}`);

            //Tắt layout
            this.setShowUIStep(aimktFormConstant.FormPopupStep.Thanks);
            //Thêm dom cảm ơn
            let afterSubmissionRender = form.FormOptions.renderPublished.afterSubmission;

            const elAfterSubmission = aimktFormCommonFunc.createElementFromHTML(afterSubmissionRender.domHtml);

            //bổ sung watermark nếu là license trial
            if (form["LicenseType"] == aimktFormConstant.License.Trial) {
                const watermark = aimktFormCommonFunc.createElementFromHTML(
                    `
                        <div class="aim-free-license-warning">
                            <a href="https://amis.misa.vn/amis-aimarketing" target="_blank">
                                <div class="aim-free-license-warning__logo">
                                    <img src="${me.publishedUrl}/content/images/watermarkHorizontal.svg" class="aim-watermark-thank-desktop"/>
                                    <img src="${me.publishedUrl}/content/images/Logo-Form-Watermark.svg" class="aim-watermark-thank-mobile"/>
                                </div>
                            </a>
                        </div>`
                );
                elAfterSubmission.appendChild(watermark);
                let cssThankWatermark = `
                    .aim-form-popup-thanks-preview__form{
                            border-bottom-left-radius: 0px !important;
                            border-bottom-right-radius: 0px !important;
                            font-family: 'Google Sans' !important;
                    }

                    .aim-form-popup-thanks-preview__form .aim-free-license-warning {
                        left: 0;
                        width: 100%;
                        background-color: #ffffff;
                        border-top: 1px solid #ff5637;
                        border-bottom-left-radius: 8px;
                        border-bottom-right-radius: 8px;
                        padding: 4px 0;
                        text-align: center;
                        position: absolute;
                        margin-top: 40px;
                        height: 36px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .aim-form-popup-thanks-preview__form .aim-free-license-warning a{
                        color: #454545;
                        text-decoration: none;
                    }

                    .aim-form-popup-thanks-preview__form .aim-free-license-warning .aim-free-license-warning__logo img{
                        height: 24px;
                    }

                    .aim-form-popup-thanks-preview__form .aim-free-license-warning .aim-free-license-warning__text{
                        font-size: 8px;
                        padding-top: 4px;
                        line-height: inherit;
                    }

                    @media (min-width: 768px){
                        .aim-form-popup-thanks-preview__form .aim-free-license-warning{
                            height: 36px;
                        }
                        .aim-form-popup-thanks-preview__form .aim-free-license-warning .aim-free-license-warning__logo .aim-watermark-thank-desktop{
                            display: block;
                            height: 24px;
                        }
                    
                        .aim-form-popup-thanks-preview__form .aim-free-license-warning .aim-free-license-warning__logo .aim-watermark-thank-mobile{
                            display: none;
                        }
                    }

                    @media (max-width: 767px){
                        .aim-form-popup-thanks-preview__form .aim-free-license-warning{
                            height: 56px;
                        }

                        .aim-form-popup-thanks-preview__form .aim-free-license-warning .aim-free-license-warning__logo .aim-watermark-thank-desktop{
                            display: none;
                        }
                    
                        .aim-form-popup-thanks-preview__form .aim-free-license-warning .aim-free-license-warning__logo .aim-watermark-thank-mobile{
                            display: block;
                            height: 40px;
                        }
                    }
            `
                afterSubmissionRender.cssForm = afterSubmissionRender.cssForm + cssThankWatermark;

                //Loại bỏ footer
                me.clearFooterAimkt();
            }


            aimktFormCommonFunc.createStyleHtml(afterSubmissionRender.cssForm, elAfterSubmission);
            aimktFormCommonFunc.createLinkFontCssGoogle('Google Sans', elAfterSubmission);
            elDomFormContent.parentNode.appendChild(elAfterSubmission);

            document.querySelector(".lz-iframe-ctn__overlay").addEventListener("click", (event) => {
                if (event.target === event.currentTarget) {
                    me.closeFormPopupUI(form);
                }
            });

            //bổ sung tracking google/facebook
            if (form.FormOptions["option"]) {
                me.convertScriptConversionEvent(form.FormOptions.option["conversionEvent"]);
            }
        },

        /**
        * Hàm thực hiện convert script conversion event
        * @param {*} script mã script
        * @returns 
        * @author PTBinh (30/11/2021)
        */
        convertScriptConversionEvent(script) {
            let scriptString = script;
            let array = [];
            while (scriptString) {
                let first = scriptString.indexOf('<script>');
                let last = scriptString.indexOf('</script>');
                if (first != -1 && last != -1) {
                    array.push(scriptString.slice(first + 8, last));
                    scriptString = scriptString.slice(last + 9, scriptString.length);
                }
                else {
                    scriptString = '';
                }
            }
            array.forEach((item) => {
                document.body.appendChild(aimktFormCommonFunc.createScript(item));
            })
        },

        /**
         * Gửi thông báo lỗi Form khi có lỗi xảy ra
         * @param {string} message Nội dung thông báo
         * @param {object} error Đối tượng lỗi
         * @author dvhung1 (07/03/2022)
         */
        sendNotificationMessage(message, error = null) {
            try {
                const me = this;
                const msgError = error == null ? "" :
                    error.toString != undefined ? error.toString() : JSON.stringify(error);
                const bodyData = { Message: message, Action: "FormRender process", Error: msgError };
                fetch(me.publishedUrl + '/api/notifications',
                    {
                        method: 'POST',
                        headers: {
                            'Content-type': 'application/json',
                            'Access-Control-Allow-Origin': '*',
                        },
                        body: JSON.stringify(bodyData),
                    }
                );
            } catch (error) {
                console.error('aimarketing_error sendNotificationMessage : ', error);
            }
        },

        /**
         * Hàm run chạy các hàm khởi tạo Form Popup render
         * NTGIANG (11/06/2020)
         * */
        runFormPopupRender(bannerIDOtherApp = null) {
            function documentReady(fn) {
                if (document.readyState != 'loading') {
                    fn();
                } else {
                    document.addEventListener('DOMContentLoaded', fn);
                }
            }

    documentReady(async () => {
        try {
            const elDomScriptLoader = document.getElementById("ldz-popup-loader");
            if (elDomScriptLoader === null) return;

            const urlPopup = elDomScriptLoader.getAttribute("src");
            const tenantID = getParameterByName("companyid", urlPopup);
            const formID = getParameterByName("formid", urlPopup);
            const bannerID = getParameterByName("bannerid", urlPopup);

            if (tenantID === null) return;
            var publishedUrl = 'https://ladizone.misa.local';
            var trackingUrl = 'https://ladizone.misa.local';
            //Load biến env cho từng môi trường
            let env = elDomScriptLoader.getAttribute("env");

            //tenantID = "0f15496c-d991-41a8-8f45-5d9cf281ce32";
            //publishedUrl = 'https://localhost:44303';
            ////publishedUrl = 'https://ladizone.misa.local';
            switch (env) {
                case "develop":
                    publishedUrl = 'https://localhost:44303';
                    //publishedUrl = 'https://ladizone.misa.local';
                    trackingUrl = 'https://ladizone.misa.local';
                    break;
                case "testlocal":
                    publishedUrl = 'https://ladizone.misa.local';
                    trackingUrl = 'https://ladizone.misa.local';
                    break;
                case "testonline":
                    publishedUrl = 'https://test.ladizone.com';
                    trackingUrl = 'https://test.ladizone.com';
                    break;
                default:
                    publishedUrl = 'https://ladizone.com';
                    trackingUrl = 'https://ladizone.com';
                    break;
            }

            //appendSessionIdAndProfileIdToUrl();

            //read session from cookie
            var ucapSessionId = readCookie('ucap_session_id');
            if (!ucapSessionId) {
                ucapSessionId = generateGuid();
                createCookie('ucap_session_id', ucapSessionId, 1);
            }

            var contextSessionURL = trackingUrl + '/api/ucap/context.json?sessionId=';
            var eventCollectorUrl = trackingUrl + '/api/ucap/eventcollector';
            function generateGuid() {
                function s4() {
                    return Math.floor((1 + Math.random()) * 0x10000)
                        .toString(16)
                        .substring(1);
                }

                return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
            }

            function createCookie(name, value, days) {
                var expires;

                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
                    expires = '; expires=' + date.toGMTString();
                } else {
                    expires = '';
                }
                document.cookie =
                    encodeURIComponent(name) +
                    '=' +
                    encodeURIComponent(value) +
                    expires +
                    '; path=/;SameSite=Lax';
            }

            function readCookie(name) {
                var nameEQ = encodeURIComponent(name) + '=';
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0)
                        return decodeURIComponent(c.substring(nameEQ.length, c.length));
                }
                return null;
            }


            //check language
            var constantListLanguage = [
                { key: 'Abkhazian', value: 'ab' },
                { key: 'Afar', value: 'aa' },
                { key: 'Afrikaans', value: 'af' },
                { key: 'Akan', value: 'ak' },
                { key: 'Albanian', value: 'sq' },
                { key: 'Amharic', value: 'am' },
                { key: 'Arabic', value: 'ar' },
                { key: 'Aragonese', value: 'an' },
                { key: 'Armenian', value: 'hy' },
                { key: 'Assamese', value: 'as' },
                { key: 'Avaric', value: 'av' },
                { key: 'Avestan', value: 'ae' },
                { key: 'Aymara', value: 'ay' },
                { key: 'Azerbaijani', value: 'az' },
                { key: 'Bambara', value: 'bm' },
                { key: 'Bashkir', value: 'ba' },
                { key: 'Basque', value: 'eu' },
                { key: 'Belarusian', value: 'be' },
                { key: 'Bengali', value: 'bn' },
                { key: 'Bihari languages', value: 'bh' },
                { key: 'Bislama', value: 'bi' },
                { key: 'Bosnian', value: 'bs' },
                { key: 'Breton', value: 'br' },
                { key: 'Bulgarian', value: 'bg' },
                { key: 'Burmese', value: 'my' },
                { key: 'Catalan, Valencian', value: 'ca' },
                { key: 'Chamorro', value: 'ch' },
                { key: 'Chechen', value: 'ce' },
                { key: 'Chichewa, Chewa, Nyanja', value: 'ny' },
                { key: 'Chinese', value: 'zh' },
                { key: 'Chuvash', value: 'cv' },
                { key: 'Cornish', value: 'kw' },
                { key: 'Corsican', value: 'co' },
                { key: 'Cree', value: 'cr' },
                { key: 'Croatian', value: 'hr' },
                { key: 'Czech', value: 'cs' },
                { key: 'Danish', value: 'da' },
                { key: 'Divehi, Dhivehi, Maldivian', value: 'dv' },
                { key: 'Dutch, Flemish', value: 'nl' },
                { key: 'Dzongkha', value: 'dz' },
                { key: 'English', value: 'en' },
                { key: 'Esperanto', value: 'eo' },
                { key: 'Estonian', value: 'et' },
                { key: 'Ewe', value: 'ee' },
                { key: 'Faroese', value: 'fo' },
                { key: 'Fijian', value: 'fj' },
                { key: 'Finnish', value: 'fi' },
                { key: 'French', value: 'fr' },
                { key: 'Fulah', value: 'ff' },
                { key: 'Galician', value: 'gl' },
                { key: 'Georgian', value: 'ka' },
                { key: 'German', value: 'de' },
                { key: 'Greek, Modern (1453–)', value: 'el' },
                { key: 'Guarani', value: 'gn' },
                { key: 'Gujarati', value: 'gu' },
                { key: 'Haitian, Haitian Creole', value: 'ht' },
                { key: 'Hausa', value: 'ha' },
                { key: 'Hebrew', value: 'he' },
                { key: 'Herero', value: 'hz' },
                { key: 'Hindi', value: 'hi' },
                { key: 'Hiri Motu', value: 'ho' },
                { key: 'Hungarian', value: 'hu' },
                {
                    key: 'Interlingua (International Auxiliary Language Association)',
                    value: 'ia',
                },
                { key: 'Indonesian', value: 'id' },
                { key: 'Interlingue, Occidental', value: 'ie' },
                { key: 'Irish', value: 'ga' },
                { key: 'Igbo', value: 'ig' },
                { key: 'Inupiaq', value: 'ik' },
                { key: 'Ido', value: 'io' },
                { key: 'Icelandic', value: 'is' },
                { key: 'Italian', value: 'it' },
                { key: 'Inuktitut', value: 'iu' },
                { key: 'Japanese', value: 'ja' },
                { key: 'Javanese', value: 'jv' },
                { key: 'Kalaallisut, Greenlandic', value: 'kl' },
                { key: 'Kannada', value: 'kn' },
                { key: 'Kanuri', value: 'kr' },
                { key: 'Kashmiri', value: 'ks' },
                { key: 'Kazakh', value: 'kk' },
                { key: 'Central Khmer', value: 'km' },
                { key: 'Kikuyu, Gikuyu', value: 'ki' },
                { key: 'Kinyarwanda', value: 'rw' },
                { key: 'Kirghiz, Kyrgyz', value: 'ky' },
                { key: 'Komi', value: 'kv' },
                { key: 'Kongo', value: 'kg' },
                { key: 'Korean', value: 'ko' },
                { key: 'Kurdish', value: 'ku' },
                { key: 'Kuanyama, Kwanyama', value: 'kj' },
                { key: 'Latin', value: 'la' },
                { key: 'Luxembourgish, Letzeburgesch', value: 'lb' },
                { key: 'Ganda', value: 'lg' },
                { key: 'Limburgan, Limburger, Limburgish', value: 'li' },
                { key: 'Lingala', value: 'ln' },
                { key: 'Lao', value: 'lo' },
                { key: 'Lithuanian', value: 'lt' },
                { key: 'Luba-Katanga', value: 'lu' },
                { key: 'Latvian', value: 'lv' },
                { key: 'Manx', value: 'gv' },
                { key: 'Macedonian', value: 'mk' },
                { key: 'Malagasy', value: 'mg' },
                { key: 'Malay', value: 'ms' },
                { key: 'Malayalam', value: 'ml' },
                { key: 'Maltese', value: 'mt' },
                { key: 'Maori', value: 'mi' },
                { key: 'Marathi', value: 'mr' },
                { key: 'Marshallese', value: 'mh' },
                { key: 'Mongolian', value: 'mn' },
                { key: 'Nauru', value: 'na' },
                { key: 'Navajo, Navaho', value: 'nv' },
                { key: 'North Ndebele', value: 'nd' },
                { key: 'Nepali', value: 'ne' },
                { key: 'Ndonga', value: 'ng' },
                { key: 'Norwegian Bokmål', value: 'nb' },
                { key: 'Norwegian Nynorsk', value: 'nn' },
                { key: 'Norwegian', value: 'no' },
                { key: 'Sichuan Yi, Nuosu', value: 'ii' },
                { key: 'South Ndebele', value: 'nr' },
                { key: 'Occitan', value: 'oc' },
                { key: 'Ojibwa', value: 'oj' },
                {
                    key: 'Church Slavic, Old Slavonic, Church Slavonic, Old Bulgarian, Old Church Slavonic',
                    value: 'cu',
                },
                { key: 'Oromo', value: 'om' },
                { key: 'Oriya', value: 'or' },
                { key: 'Ossetian, Ossetic', value: 'os' },
                { key: 'Punjabi, Panjabi', value: 'pa' },
                { key: 'Pali', value: 'pi' },
                { key: 'Persian', value: 'fa' },
                { key: 'Polish', value: 'pl' },
                { key: 'Pashto, Pushto', value: 'ps' },
                { key: 'Portuguese', value: 'pt' },
                { key: 'Quechua', value: 'qu' },
                { key: 'Romansh', value: 'rm' },
                { key: 'Rundi', value: 'rn' },
                { key: 'Romanian, Moldavian, Moldovan', value: 'ro' },
                { key: 'Russian', value: 'ru' },
                { key: 'Sanskrit', value: 'sa' },
                { key: 'Sardinian', value: 'sc' },
                { key: 'Sindhi', value: 'sd' },
                { key: 'Northern Sami', value: 'se' },
                { key: 'Samoan', value: 'sm' },
                { key: 'Sango', value: 'sg' },
                { key: 'Serbian', value: 'sr' },
                { key: 'Gaelic, Scottish Gaelic', value: 'gd' },
                { key: 'Shona', value: 'sn' },
                { key: 'Sinhala, Sinhalese', value: 'si' },
                { key: 'Slovak', value: 'sk' },
                { key: 'Slovenian', value: 'sl' },
                { key: 'Somali', value: 'so' },
                { key: 'Southern Sotho', value: 'st' },
                { key: 'Spanish, Castilian', value: 'es' },
                { key: 'Sundanese', value: 'su' },
                { key: 'Swahili', value: 'sw' },
                { key: 'Swati', value: 'ss' },
                { key: 'Swedish', value: 'sv' },
                { key: 'Tamil', value: 'ta' },
                { key: 'Telugu', value: 'te' },
                { key: 'Tajik', value: 'tg' },
                { key: 'Thai', value: 'th' },
                { key: 'Tigrinya', value: 'ti' },
                { key: 'Tibetan', value: 'bo' },
                { key: 'Turkmen', value: 'tk' },
                { key: 'Tagalog', value: 'tl' },
                { key: 'Tswana', value: 'tn' },
                { key: 'Tonga (Tonga Islands)', value: 'to' },
                { key: 'Turkish', value: 'tr' },
                { key: 'Tsonga', value: 'ts' },
                { key: 'Tatar', value: 'tt' },
                { key: 'Twi', value: 'tw' },
                { key: 'Tahitian', value: 'ty' },
                { key: 'Uighur, Uyghur', value: 'ug' },
                { key: 'Ukrainian', value: 'uk' },
                { key: 'Urdu', value: 'ur' },
                { key: 'Uzbek', value: 'uz' },
                { key: 'Venda', value: 've' },
                { key: 'Tiếng Việt', value: 'vi' },
                { key: 'Volapük', value: 'vo' },
                { key: 'Walloon', value: 'wa' },
                { key: 'Welsh', value: 'cy' },
                { key: 'Wolof', value: 'wo' },
                { key: 'Western Frisian', value: 'fy' },
                { key: 'Xhosa', value: 'xh' },
                { key: 'Yiddish', value: 'yi' },
                { key: 'Yoruba', value: 'yo' },
                { key: 'Zhuang, Chuang', value: 'za' },
                { key: 'Zulu', value: 'zu' },
            ];

            //check mobile
            window.mobileCheck = function () {
                let check = false;
                (function (a) {
                    if (
                        /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
                            a
                        ) ||
                        /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
                            a.substr(0, 4)
                        )
                    )
                        check = true;
                })(navigator.userAgent || navigator.vendor || window.opera);
                return !check ? 'Desktop' : 'Mobile';
            };

            //check browser
            var isOpera =
                (!!window.opr && !!opr.addons) ||
                !!window.opera ||
                navigator.userAgent.indexOf(' OPR/') >= 0;
            var isFirefox = typeof InstallTrigger !== 'undefined';
            var isSafari =
                /constructor/i.test(window.HTMLElement) ||
                (function (p) {
                    return p.toString() === '[object SafariRemoteNotification]';
                })(
                    !window['safari'] ||
                    (typeof safari !== 'undefined' && window['safari'].pushNotification)
                );
            var isIE = /*@cc_on!@*/ false || !!document.documentMode;
            var isEdge = !isIE && !!window.StyleMedia;
            var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);
            var isEdgeChromium = isChrome && navigator.userAgent.indexOf('Edg') != -1;
            isEdge = isEdge || isEdgeChromium;
            if (isEdgeChromium) isChrome = false;
            var resultBrowser = [
                {
                    key: 'Opera',
                    value: isOpera,
                },
                { key: 'Firefox', value: isFirefox },
                { key: 'Safari', value: isSafari },
                { key: 'Internet Explorer', value: isIE },
                { key: 'Edge', value: isEdge },
                { key: 'Chrome', value: isChrome },
            ];

            //local variable

            var scopeLandingPage = 1;
            var eventTypeLandingPageView = 101;

            /**
             * Generate profileId cho website
             * @param {any} ucapSessionId ID của session
             * @param {any} trackingUrl trackingUrl
             * @author dvhung1 (20/09/2021)
             */
            async function trackProfile(ucapSessionId) {
                var profileInformation = {};
                let profileId = globalThis.lzpr.getUrlParameter('ucap_profile_id')
                    ? atob(decodeURIComponent(globalThis.lzpr.getUrlParameter('ucap_profile_id')))
                    : readCookie('ucap_profile_id');
                let sessionId = globalThis.lzpr.getUrlParameter('ucap_session_id')
                    ? atob(decodeURIComponent(globalThis.lzpr.getUrlParameter('ucap_session_id')))
                    : readCookie('ucap_session_id');

                if (profileId && sessionId) {
                    profileInformation['profileId'] = profileId;
                    profileInformation['sessionId'] = sessionId;
                    createCookie('ucap_profile_id', profileId, 1);
                    createCookie('ucap_session_id', sessionId, 1);
                    appendSessionIdAndProfileIdToUrl();
                    postEventViewPageToUnomi();
                } else {
                    if (!globalThis.isProcessProfile) {
                        globalThis.isProcessProfile = true;
                        let result = await fetch(contextSessionURL + ucapSessionId, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json; charset=utf-8',
                            },
                        });
                        if (!readCookie('ucap_profile_id')) {
                            let jsonValue = await result.json();
                            profileInformation['profileId'] = jsonValue['profileId'];
                            profileInformation['sessionId'] = jsonValue['sessionId'];
                            createCookie('ucap_profile_id', profileInformation['profileId'], 1);
                            postEventViewPageToUnomi();
                            if (
                                globalThis.aimFuncInQueue !== undefined &&
                                globalThis.aimFuncInQueue !== null &&
                                globalThis.aimFuncInQueue.length > 0
                            ) {
                                for (let index = 0; index < globalThis.aimFuncInQueue.length; index++) {
                                    const func = globalThis.aimFuncInQueue[index];
                                    func();
                                }
                            }
                        } else {
                            if (globalThis.aimFuncInQueue === undefined) {
                                globalThis.aimFuncInQueue = [postEventViewPageToUnomi];
                            }
                            else {
                                globalThis.aimFuncInQueue.push(postEventViewPageToUnomi);
                            }
                        }
                        appendSessionIdAndProfileIdToUrl();
                    } else {
                        if (globalThis.aimFuncInQueue === undefined) {
                            globalThis.aimFuncInQueue = [postEventViewPageToUnomi];
                        }
                        else {
                            globalThis.aimFuncInQueue.push(postEventViewPageToUnomi);
                        }
                    }
                }

                return profileInformation;
            }

            function getPageInfo() {
                let brower = resultBrowser.filter((f) => f.value)[0]
                    ? resultBrowser.filter((f) => f.value)[0]
                    : {};

                return {
                    destinationURL: window.parent.location.href,
                    pagePath: window.parent.location.pathname,
                    pageOrigin: window.parent.location.origin,
                    pageName: window.parent.document.title,
                    referringURL: window.parent.document.referrer,
                    lpageId: '',
                    device: mobileCheck(),
                    browser: brower['key'] ? brower['key'] : '',
                    language: constantListLanguage.find(
                        (item) => item.value == navigator.language.split('-')[0]
                    ).key,
                };
            }

            function postEventViewPageToUnomi() {
                //fill data to unomi
                // View Page
                var landingPageInfo = getPageInfo();
                var pageViewToUnomi = {
                    events: [
                        {
                            eventType: eventTypeLandingPageView,
                            scope: scopeLandingPage,
                            source: {
                                itemType: 'LandingPageID',
                                properties: landingPageInfo,
                                itemId: '',
                            },
                            target: {
                                itemType: 'LandingPageID',
                                properties: {
                                    ...landingPageInfo,
                                    TargetName: landingPageInfo.pageName,
                                },
                                itemId: '',
                            },
                            properties: {
                                ProfileID: readCookie('ucap_profile_id'),
                                TenantID: tenantID,
                                Email: getParameterByName('emailaddress') || undefined,
                                utm_source: getParameterByName('utm_source') || undefined,
                                utm_campaign: getParameterByName('utm_campaign') || undefined,
                                utm_content: getParameterByName('utm_content') || undefined,
                                utm_medium: getParameterByName('utm_medium') || undefined,
                                utm_term: getParameterByName('utm_term') || undefined,
                                gclid: getParameterByName('gclid') || undefined,
                                fbclid: getParameterByName('fbclid') || undefined,
                                emailid: getParameterByName('emailid') || undefined,
                                workflowid: getParameterByName('workflowid') || undefined,
                            },
                        },
                    ],
                    sessionId: readCookie('ucap_session_id'),
                };

                // API
                fetch(eventCollectorUrl, {
                    method: 'POST',
                    headers: {
                        'Content-type': 'text/plain; charset=utf-8',
                    },
                    body: JSON.stringify(pageViewToUnomi),
                });
            }

            /**
             * append sessionid và profileid vào url
             * @author pqhuy1 (12/07/2022)
             */
            function appendSessionIdAndProfileIdToUrl() {
                return;
                let allLinks = document.links;
                for (var i = 0, n = allLinks.length; i < n; i++) {
                    let link = allLinks[i].getAttribute('href');
                    if (typeof link !== 'string') continue;
                    let url;
                    // ko append sessionID và profileID vào link cùng domain
                    if (link.includes(location.host) || (!link.includes('http') && !link.includes(location.host))) {
                        continue;
                    }

                    try {
                        url = new URL(link);
                    } catch (error) {
                        link = location.origin + link;
                        url = new URL(link);
                    }
                    url.searchParams.set('ucap_session_id', btoa(encodeURIComponent(globalThis.lzpr.getCookie('ucap_session_id'))));
                    url.searchParams.set('ucap_profile_id', btoa(encodeURIComponent(globalThis.lzpr.getCookie('ucap_profile_id'))));
                    allLinks[i].setAttribute('href', url.toString());
                }
            }

            let ldPage = document.querySelector("script#ai-ldpage-viewport");
            let previewFormPage = document.querySelector("body#aim-form-preview");
            if ((ldPage == null || ldPage == undefined) && (previewFormPage == null || previewFormPage == undefined)) {
                await trackProfile(ucapSessionId, trackingUrl);
            }

            //Nếu là nhúng form popup/banner thông thường
            if (bannerIDOtherApp == null) {
                if (formID === null && bannerID === null) {
                    //Render form mang đi nhúng embed
                    callFormPopupRender(tenantID, publishedUrl, trackingUrl);
                } else {
                    if (formID != null && bannerID === null) {
                        //Render Form preview
                        callFormPopupPreview(tenantID, formID, publishedUrl, trackingUrl);
                    }
                    if (formID == null && bannerID != null) {
                        //Render Banner preview
                        callBannerPreview(tenantID, bannerID, publishedUrl, trackingUrl);
                    }
                }
            }
            //Nếu được gọi để hiển thị banner thì app bên ngoài
            else {
                callRenderBannerOtherApp(tenantID, bannerIDOtherApp, publishedUrl, trackingUrl);
            }

        } catch (ex) {
            console.error("aimarketing render form popup error.", ex);
        }
    });


    /**
     * Hàm render Form Popup mang đi nhúng Embed    
     * @param {any} tenantId ID của công ty
     * @param {any} elDomScriptLoader Dom script mag đi nhúng
     * @author dvhung1 (30/08/2021)
     * @modifier PTBinh (22/02/2023) Sửa lại thành hàm get thay cho hàm post để lưu cache
     */
    async function callFormPopupRender(tenantId, publishedUrl, trackingUrl) {

        let _tenantId = tenantId;
        try {
            _tenantId = encodeURIComponent(_tenantId);
        } catch (err) {
            console.log(err);
        }

        let timeCallApi = new Date().getTime();
        let resultEndpoint;

        // TDBA - 30/10/2024: Version này dùng trong trường hợp xóa cache API
        const _versionAPI = "20250529";

        let ldPage = document.querySelector("script#ai-ldpage-viewport");
        if (ldPage == null || ldPage == undefined) {
            resultEndpoint = `/api/v3/forms/info?tenantID=${_tenantId}&type=popup&url=${encodeURIComponent(window.location.href)}&isGetLicense=true&version=${_versionAPI}`;
        }
        else {
            resultEndpoint = `/api/v3/forms/info?tenantID=${_tenantId}&type=popup&url=${encodeURIComponent(window.location.href)}&version=${_versionAPI}`;
        }
        let result = await fetch(publishedUrl + resultEndpoint,
            {
                method: 'GET',
                headers: {
                    // 'x-tenant-id': tenantId,
                    'Content-type': 'application/json',
                    'Access-Control-Allow-Origin': '*',
                },
                //body: JSON.stringify([]),
            }
        );
        let resultValue = await result.json();
        let forms = resultValue.ListFormRender;
        let banners = resultValue.ListBannerRender;

        let timeCallApiEnd = new Date().getTime();
        //if (!forms || forms.length === 0) return;

        //if (!banners || banners.length === 0) return;

        if ((!forms || forms.length === 0) && (!banners || banners.length === 0)) {
            return;
        }

        globalThis.lzpr.timeCallApi = timeCallApiEnd - timeCallApi;

        //Tồn tại form nhưng không tồn tại banner
        if (forms && forms.length != 0 && (!banners || banners.length === 0)) {
            //render form
            globalThis.lzpr.renderExternalPopupForm(forms, publishedUrl, trackingUrl);
        }

        //Tồn tại banner nhưng không tồn tại form
        if ((!forms || forms.length === 0) && banners && banners.length != 0) {
            //render banner
            globalThis.lzpr.renderExternalBanner(banners, publishedUrl, trackingUrl);
        }

        //Tồn tại cả form và banner
        if (forms && forms.length != 0 && banners && banners.length != 0) {
            globalThis.lzpr.renderExternalMerge(forms, banners, publishedUrl, trackingUrl);
        }
    }

    /**
     * Hàm thực hiện render banner khi gọi từ app bên ngoài
     * @author PTBinh (14/09/2023)
     * */
    async function callRenderBannerOtherApp(tenantID, bannerIDOtherApp, publishedUrl, trackingUrl) {
        let resultEndpoint = `/api/v3/forms/banner_app?bannerID=${bannerIDOtherApp}`;
        let result = await fetch(publishedUrl + resultEndpoint,
            {
                method: 'GET',
                headers: {
                    'x-tenant-id': tenantID,
                    'Content-type': 'application/json',
                    'Access-Control-Allow-Origin': '*',
                },
            }
        );
        let resultValue = await result.json();
        if (resultValue && resultValue.length > 0) {
            globalThis.lzpr.renderExternalBanner(resultValue, publishedUrl, trackingUrl);
        }
    }


    /**
     * Hàm render cho trang xem trước Form
     * @param {any} tenantID ID của công ty
     * @param {any} formID ID của Form
     * @author dvhung1 (31/08/2021)
     */
    async function callFormPopupPreview(tenantID, formID, publishedUrl, trackingUrl) {
        const result = await fetch(publishedUrl + `/api/v3/forms/popup?formID=${formID}&isGetLicense=true`, {
            headers: {
                'x-tenant-id': tenantID,
                'Access-Control-Allow-Origin': '*',
            },
        });
        const form = await result.json();

        //#region Data fake
        //form.PopupFormType = 2;
        //form.IsEnableCaptcha = false;
        //#endregion

        const forms = [form];

        if (!forms || forms.length === 0) return;
        //render form
        globalThis.lzpr.renderExternalPopupForm(forms, publishedUrl, trackingUrl, isPreview = true);
    }

    /**
     * Hàm render cho trang xem trước Banner
     * @param {any} tenantID id của công ty
     * @param {any} bannerID id của banner
     * @param {any} publishedUrl url xuất bản
     * @param {any} trackingUrl url mã tracking
     * @author PTBinh (04/03/2022)
     */
    async function callBannerPreview(tenantID, bannerID, publishedUrl, trackingUrl) {
        const result = await fetch(publishedUrl + `/api/v3/banners?bannerID=${bannerID}`, {
            headers: {
                'x-tenant-id': tenantID,
                'Access-Control-Allow-Origin': '*',
            },
        });
        const banner = await result.json();

        const banners = [banner];

        if (!banners || banners.length === 0) return;
        //render banner
        globalThis.lzpr.renderExternalBanner(banners, publishedUrl, trackingUrl, isPreview = true);
    }

    /**
     * Lấy value của pram trong url
     * @param {string} name Tên pram muốn lấy
     * @param {string} url Đường dẫn url
     */
    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)', 'i'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
}
    };

    /**
     * Hàm run khi view form
     * @param {any} domForm element của form
     * @param {any} tenantId id công ty
     * @param {any} urlTracking mã tracking unomi
     * @author PTBinh (01/12/2021)
     */
    function runViewPage(domForm, tenantId, urlTracking) {
        //kiểm tra xem có phải landingPage hay không
        let isLandingPage;
        let ldPage = document.querySelector("script#ai-ldpage-viewport");
        if (ldPage == null || ldPage == undefined) {
            isLandingPage = false;
        }
        else {
            isLandingPage = true;
        }

        if (isLandingPage == false) {
            ////Lấy tenantID

            //const tenantID = tenantId;

            ////kiểm tra đã tồn tại script lpage-ucap.js ứng với tenantid chưa
            //let listScript = document.scripts;
            //let lengthListScript = listScript.length;
            //let index = 0;
            //if (listScript) {
            //    for (var i = 0; i < lengthListScript; i++) {
            //        if (listScript[i].src.includes(`ucap/lpage-ucap.js?tenantId=${tenantID}`) == true) {
            //            return;
            //        }
            //        else {
            //            index++;
            //        }
            //    }
            //}
            //if (index == listScript.length) {

            //    //append thêm script vào html
            //    let script = [`${urlTracking}/ucap/lpage-ucap.js?tenantId=${tenantID}&domainUnomi=${urlTracking}`];

            //    aimktFormCommonFunc.createLinkScript(script, domForm);
            //}
        }
    };

    window.lzpr = lzpr;
    globalThis.lzpr = lzpr;
}



var isAutoShow = document.getElementById("ldz-popup-loader").getAttribute("isautoshow");
if (isAutoShow !== "false") {
    isAutoShow = true;
    globalThis.lzpr.runFormPopupRender(null);
} else {
    isAutoShow = false;
}


