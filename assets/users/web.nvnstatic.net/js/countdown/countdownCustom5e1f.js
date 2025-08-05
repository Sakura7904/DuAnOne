$(function(){
    if ($('.endDateTime').length) {
        var currentDate = new Date();
        var end = new Date($('.endDateTime').val().replace(/-/g, '/'));

        var currentCalculate = currentDate.getTime() / 1000;
        var endCalculate = end.getTime() / 1000;
        $('.countdown').final_countdown({
            // 'start': 1387461319,
            'end': endCalculate,
            'now': currentCalculate
        });
    }
});