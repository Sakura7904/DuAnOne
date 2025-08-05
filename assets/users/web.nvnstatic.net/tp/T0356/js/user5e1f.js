$(document).ready(function(){
    Address.load('#cityId', '#districtId');

    if($("body .validate").length){
        $(".validate").validationEngine();
    }

    var pathname = window.location.pathname;
    if(pathname == '/user/signin'){
        $('#username, #password').on('keypress',function(even){
            if(even.keyCode == 13){
                signin();
            }
        });
    }
    $('#btnSignIn').click(function () {
        signin();
    });
    $('#btnSingup').click(function () {
        signup();
        // if (!$('input[name="checkConfirm"]:checked').val()){
        //     $('#checkConfirm').css({'color':'red'});
        //     alert('Bạn phải đồng ý điều khoản của chúng tôi!');
        //
        //     setTimeout(function () {
        //         $('#checkConfirm').removeAttr('style');
        //     },5000);
        // }else{
        //     var email = $('#email').val();
        //     var username = email.replace("@gmail.com",'').replace("@yahoo.com",'');
        //     $('#username').val(username);
        //     signup();
        // }
    });
    $('.close-singup').click(function () {
        window.location.href = '/';
    });
    $('.signinButon').click(function () {
        $('.section-title').removeClass('open');
        $(this).addClass('open');
        $('.signinContent').addClass('open');
        $('.signupContent').removeClass('open');
    });
    $('.signupButon').click(function(){
        $('.section-title').removeClass('open');
        $(this).addClass('open');
        $('.signupContent').addClass('open');
        $('.signinContent').removeClass('open');
    });
});
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
        return null;
    }
    else{
        return results[1] || 0;
    }
};
function signin() {
    if($("#formAcount").validationEngine('validate')){
        $.ajax({
            type: "POST",
            data: $("#formAcount").serialize(),
            cache: false,
            dataType: 'json',
            url: "/user/ajaxsignin",
            success: function(rs) {
                if(rs.code){
                    if($.urlParam('redirectUri')) {
                        var uri = decodeURIComponent($.urlParam('redirectUri'));
                        window.location.href = uri;
                        return;
                    }
                    window.location.href = '/';
                }
                else if(rs.message['username'] != undefined){
                    alert(rs.message['username']);
                }
                else if(rs.message['email'] != undefined){
                    alert(rs.message['email']);
                }
            }
        });
    }
}



function signup() {
    if($("#formAcountSignup").validationEngine('validate')){
        $.ajax({
            type: "POST",
            data: $("#formAcountSignup").serialize(),
            cache: false,
            dataType: 'json',
            url: "/user/ajaxsignup",
            beforeSend:function () {
                var html = '<div class="modal-body text-center">\
                        <div class="desc-modal"><img style="margin: 10px 0;" src="/tp/T0356/img/loading.gif"/></div>\
                   </div>';
                $('#modalShow .modal-content').html(html);
                $('#modalShow .modal-dialog').addClass('modal-xs');
                $('#modalShow .modal-backdrop').remove();
                $('#modalShow').modal('show');
            },
            success: function(rs) {
                if(rs.code == 1){
                    $("#formAcountSignup input[type='text'],#formAcountSignup input[type='date'],#formAcountSignup input[type='password'],#formAcountSignup select,#formAcountSignup textarea").val('');
                    $('#modalShow .modal-dialog').addClass('modal-md-x1');
                    var mess = '<div class="modal-body text-center">\
                        <div class="desc-modal alert alert-success">"Cảm ơn bạn đã đăng ký tài khoản tại Website chúng tôi. Vui lòng kiểm email, bạn vừa cung cấp và ấn vào link kích hoạt tài khoản!"</div>\
                            <div class="form-group clearfix text-center"><a class="btn close-singup w100 btn-green">OK</a></div>\
                        </div>';
                    $('#modalShow .modal-content').html(mess);
                    setTimeout(function () {
                        window.location.href = '/';
                    },8000);
                }
                else{
                    // alert(rs.message);
                    alert('Tài khoản đã được đăng kí, vui lòng đăng nhập bằng Email, Google hoặc Facebook');
                    // $('.modal-backdrop').click();
                    window.location.reload();
                }
            },
        });
    }
}


