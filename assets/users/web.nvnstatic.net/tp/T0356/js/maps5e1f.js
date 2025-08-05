var storeId = $('#psStoreId').val();
$(function () {
    Address.load('#cityId', '#districtId');

    // $('.header-page').append('<h1>Hệ thống '+$('#numberAgency').val()+' cửa hàng </h1>');
    // console.log(numberText);


    $('.change-tinh').change(function () {
        $.post('/agency/agencystore',
            {
                cityId: $(this).val(),
                storeId: $('#storeId').val(),
            },
            function (rs) {
                $("#address-link").empty();
                if(rs.length){
                    var inner="";
                    for(var i = 0; i < rs.length; i++) {
                        var obj = rs[i];
                        var a = obj.content;
                        inner +=
                            "<li class='cn_tenchinhanh'>" +
                            "<a href='javascript:void(0)' data-content='"+a+"' data-lat="+obj.latitude+" data-lng='"+obj.longitude+"' data-email='"+obj.email+"' data-name='"+obj.name+"' data-img='"+obj.image+"' data-address='"+obj.address+"' data-phone='"+obj.phone+"'>" +
                            "<b style='margin-bottom: 10px;display: inline-block'>"+obj.name+"</b>" +
                            "<p style='display: block'>"+obj.address+"</p>" +
                            "<p style='display: block'>"+obj.phone+"</p>" +
                            "</a>";
                        inner +="</li>";
                    }
                    $("#address-link").append(inner);
                    $(".cn_tenchinhanh a").click(function(){
                        var kinhdo = $(this).attr("data-lat");
                        var vido = $(this).attr("data-lng");
                        var mapimg =$(this).data('img');
                        var content =$(this).data('content');
                        var show = '';
                        // console.log('a'+content);
                        $('#attr-address').attr('value',$(this).attr('attr-address'));
                        $('#attr-name').attr('value',$(this).attr('attr-name'));
                        $('#attr-email').attr('value',$(this).attr('attr-email'));
                        $('#attr-phone').attr('value',$(this).attr('attr-phone'));
                        $('#attr-img').attr('value',$(this).attr('attr-img'));
                        var diachi = $(this).attr('data-address'),
                            idMap = $('#map-canvas');
                        if(diachi !== ''){
                            data = diachi;
                        }else{
                            data = kinhdo + ',' + vido;
                        }
                        mapGeneratorWithCurrentData(data,idMap);
                    });
                }else{
                    $("#address-link").append('<li class="cn_tenchinhanh"><a href="javascript:void(0)"><span style="display: block;text-align: center; font-weight: normal">Chưa có cửa hàng nào !!!</span></a></li>');
                }
            }
        );
    });
    $(".cn_tenchinhanh a").click(function(){
        var kinhdo = $(this).attr("data-lat");
        var vido = $(this).attr("data-lng");
        var mapimg =$(this).data('img');
        var content =$(this).data('content');
        var show = '';
        var diachi = $(this).attr('data-address'),
            idMap = $('#map-canvas');
        var ten = $('#attr-name').attr('value',$(this).attr('data-name'));
        var email = $('#attr-email').attr('value',$(this).attr('data-email'));
        var hotline = $('#attr-phone').attr('value',$(this).attr('data-phone'));
        var image = $('#attr-img').attr('value',$(this).attr('data-img'));
        // console.log(diachi);

        if (diachi !== '') {
            data = diachi;
        } else {
            data = kinhdo + ',' + vido;
        }
        $(".cn_tenChiNhanh").html($(this).find(".ten-chi-nhanh").html());
        $(".cn_diachi_chitiet").html(diachi);
        $(".cn_hotline").html(hotline);
        mapGeneratorWithCurrentData(data, idMap);
    });
    $(window).on('load', function() {
        let data ,
            dataAddress =  $('#address-link > li:first-child > a').attr('data-address'),
            dataLat = $('#address-link > li:first-child > a').attr('data-lat'),
            dataLong = $('#address-link > li:first-child > a').attr('data-lng'),
            idMap = $('#map-canvas');
        console.log(dataAddress);
        if(dataAddress !== ''){
            data = dataAddress;
        }else{
            data = dataLat + ',' + dataLong;
        }
        let checkMarket = $('#marketDefault').val();
        if (checkMarket) {
            data =  checkMarket;
        }
        mapGeneratorWithCurrentData(data, idMap);
    });
});

