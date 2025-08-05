var storeId = $('#psStoreId').val();
$(document).ready(function() {
    if ($(window).width() < 768) {
        $(".ortherNews .listnews").addClass('owl-carousel');
        if ($(".ortherNews .listnews .news-info").length) {
            $(".ortherNews .listnews").owlCarousel({
                items: 6,
                nav: false,
                dots: true,
                autoplay: false,
                loop: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    320: {
                        items: 1,
                    },
                    480: {
                        items: 1,
                    },
                    768: {
                        items: 1,
                    },
                    992: {
                        items: 6
                    }
                },
                navText: ["<i class='fal fa-angle-left'></i>", "<i class='fal fa-angle-right'></i>"]
            });
        }

        if ($('.productSuggest .productItem').length > 6){
            $('#flip').addClass('open');
        }

        $("#flip").on('click',function () {
            var $this = $(this), $description = $('.productSuggest');
            $this.addClass('active');
            $description.addClass('fill');
            $description.slideDown();
        });
    }else {
        $(".ortherNews .listnews").removeClass('owl-carousel');
    }
});