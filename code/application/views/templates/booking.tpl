{include file="header_grande.tpl"}
<div id="page-content-wrapper">
<link rel="stylesheet" type="text/css" href="{$base_url}/assets/css/css-booking.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.4.1/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.4.1/jquery.fancybox.min.js"></script>
{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}


    <script>

        var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0];

        jQuery(window).on('load resize', function() {
            x = w.innerWidth || e.clientWidth || g.clientWidth,
            y = w.innerHeight|| e.clientHeight|| g.clientHeight;

            $(".grande-bg-img img").css('height',y)
        });

        $banner_duration = 6;
        $grande_banner = Array(
            '/assets/images/grande/bg/b1.jpg',
            '/assets/images/grande/bg/b2.jpg',
            '/assets/images/grande/bg/b3.jpg'
        );

        $(document).ready(function() {

            $banner_total = $grande_banner.length;
            $banner_total_duration = $banner_duration*$banner_total;

            $(".cb-slideshow.runanimated li span").css("animation","imageAnimation "+$banner_total_duration+"s linear infinite 0s");
            $(".cb-slideshow.nav.runanimated li").css("animation","navAnimation "+$banner_total_duration+"s linear infinite 0s");
            $.each($grande_banner, function( index, value ) {
                $banner_idx = index+1;
                $(".cb-slideshow li:nth-child("+$banner_idx+") span").css("background-image","url("+value+")");

                $dalay = (index * $banner_duration)+'s';
                $(".cb-slideshow li:nth-child("+$banner_idx+") span").css('animation-delay',$dalay);
                $(".cb-slideshow.nav li:nth-child("+$banner_idx+")").css('animation-delay',$dalay);
            });


            $(".cb-slideshow.nav li").click(function(){

                $idx = $(this).data('rel');
                $(".cb-slideshow-nav").removeClass('runanimated');
                $(".cb-slideshow").removeClass('runanimated');
                for (i = 0; i < $banner_total; i++) {
                    $banner_idx = (($idx+i-1) % $banner_total)+1;
                    $dalay = (i * $banner_duration)+'s';
                    $(".cb-slideshow li:nth-child("+$banner_idx+") span").css('animation-delay',$dalay);
                    $(".cb-slideshow.nav li:nth-child("+$banner_idx+")").css('animation-delay',$dalay);
                }
                setTimeout(function(){
                    $(".cb-slideshow-nav").addClass('runanimated');
                    $(".cb-slideshow").addClass('runanimated');
                }, 100);

            });

            $("input[name=sign_citizen_id]").inputmask({ "mask": "9-9999-99999-99-9"});
            $("input[name=sign_phone]").inputmask({ "mask": "999-999-9999"});

            function hilight_input(elm) {
                elm.addClass('require');
                elm.focus();
            }
            function hilight_clear(elm) {
                elm.removeClass('require');
                elm.focus();
            }
            function hilight_clearall(elm) {
                $(".grande-form ul li:nth-child("+elm+") input").removeClass('require');
            }
            function check_field($elm_type, $elm_name, $field_name, $checks){

                function check_empty() {
                    console.log('check_empty');
                    $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
                    if ($txt === '') {
                        hilight_input($($elm_type+"[name='"+$elm_name+"']"));
                        popError("{$lang['booking_please_select']} ","Error");
                        return false;
                    } else { hilight_clear($($elm_type+"[name='"+$elm_name+"']"))}
                    return true;
                }
                function check_length($option) {
                    console.log('check_length');
                    $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
                    if ($txt.length < $option) {
                        hilight_input($($elm_type+"[name='"+$elm_name+"']"));
                        popError($field_name+"ต้องมีอย่างน้อย 8 ตัวอักษร","Error");
                        return false;
                    } else { hilight_clear($($elm_type+"[name='"+$elm_name+"']"))}
                    return true;
                }
                function check_email() {
                    console.log('check_email');
                    $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
                    if (!validateEmail($txt)) {
                        hilight_input( $("input[name="+$elm_name+"]"));
                        popError($field_name+"ไม่ถูกต้อง","Error");
                        return false;
                    } else { hilight_clear($($elm_type+"[name='"+$elm_name+"']"))}
                    return true;
                }
                function check_ID() {
                    console.log('check_ID');
                    $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
                    $txt = $txt.replace(/-/g, "");
                    console.log($txt);
                    if($txt.length != 13) {
                        hilight_input( $("input[name="+$elm_name+"]"));
                        popError($field_name+"ไม่ถูกต้อง","Error");
                        return false;
                    }
                    for(i=0, sum=0; i < 12; i++){
                        sum += parseFloat($txt.charAt(i))*(13-i);
                    }
                    if((11-sum%11)%10!=parseFloat($txt.charAt(12))){
                        hilight_input( $("input[name="+$elm_name+"]"));
                        popError($field_name+"ไม่ถูกต้อง","Error");
                        return false;
                    }
                    return true;
                }
                function check_unique() {
                    console.log('check_unique');
                    $txt = $($elm_type+"[name='"+$elm_name+"']").val()||'';
                    $txt = $txt.replace(/-/g, "");
                    $data = {};
                    eval("$data."+$elm_name+"=$txt")

                    if ($txt !== '') {
                        $.ajax({
                            type: "POST",
                            url: "/signup/check_unique",
                            data: $data,
                            //data: $(".grande-form input[name="+$elm_name+"]").serialize(),
                            dataType: "json",
                            async: false,
                            cache: false,
                            timeout: 30000,
                            success: function(msg){
                                if (msg.status == "success") {
                                    hilight_clear($($elm_type+"[name='"+$elm_name+"']"));
                                    $res = true;
                                } else {
                                    var errormsg = $field_name+"ที่ระบุถูกลงทะเบียนแล้ว";
                                    hilight_input($("[name="+$elm_name+"]"));
                                    popError(errormsg,"Error");
                                    $res = false;
                                }
                            }
                        });
                        return $res
                    }
                }

                $res = true;
                for ($check in $checks) {
                    //console.log($field_name);
                    //console.log($checks[$check]);

                    $method = $checks[$check];
                    $option = '';
                    if (typeof($checks[$check]) ==="object" ) {
                        $method = Object.keys($checks[$check])[0];
                        $option = Object.values($checks[$check])[0]
                    }
                    //console.log($method);

                    switch($method) {
                        case 'empty':
                            $res = check_empty();
                            break;
                        case 'length':
                            $res = check_length($option);
                            break;
                        case 'email':
                            $res = check_email();
                            break;
                        case 'check_id':
                            $res = check_ID();
                            break;
                        case 'is_unique':
                            $res = check_unique();
                            console.log("error1");
                            break;
                    }
                    console.log($res);
                    if (!$res) { break; }
                }
                console.log("$res");
                console.log($res);
                return $res;
            }

            $(".btn-grande.next").click(function(event){
                event.preventDefault();

                if ($(".grande-form").hasClass('active1')) {

                    if (!check_field("input", "sign_email","อีเมล",['empty','email','is_unique'])) { return; };
                    if (!check_field("input", "sign_password","รหัสผ่าน",['empty',{ 'length':8 }])) { return; };
                    if (!check_field("input", "sign_password_confirm","ยืนยันรหัสผ่าน",['empty', { 'length': 8 }])) { return; };

                    $pass1 = $("input[name='sign_password']").val()||'';
                    $pass2 = $("input[name='sign_password_confirm']").val()||'';

                    if ($pass1 !== $pass2) {
                        hilight_input($("input[name='sign_password_confirm']"));
                        popError("ข้อมูลรหัสผ่านไม่ตรงกัน","Error");
                        return;
                    }

                    $(".grande-form").removeClass('active1');
                    $(".grande-form").addClass('active2');

                    hilight_clearall('1');
                    return;
                }
                if ($(".grande-form").hasClass('active2')) {

                    if (!check_field("input","room_id","ห้อง",['empty'])) { return; };

                    var v_room_id = $("input[name=room_id]").val();

                    $.ajax({
                        type: "POST",
                        url: "/booking/check_room",
                        data: { room_id:v_room_id, user_id:'{$user.id}' },
                        success: function(msg){
                            $(".confirm_check_box").html(msg);
                            $(".grande-form").removeClass('active2');
                            $(".grande-form").addClass('active4');
                            $(".book-info.booking").addClass('ipadshow');
                            hilight_clearall('2');
                            return;
                        }
                    });

                }
            });
            $(".btn-grande.back").click(function(event){
                event.preventDefault();
                if ($(".grande-form").hasClass('active2')) {
                    $(".grande-form").removeClass('active2');
                    var location = "{$base_url}/project/{$project_name}/{$project_id}";
                    window.location.href = location;
                    return;
                }
                if ($(".grande-form").hasClass('active3')) {
                    $(".grande-form").removeClass('active3');
                    $(".grande-form").addClass('active2');
                    return;
                }
                if ($(".grande-form").hasClass('active4')) {
                    $(".grande-form").removeClass('active4');
                    $(".book-info.booking").removeClass('ipadshow');
                    $(".grande-form").addClass('active2');
                    $(".confirm_check_box").html('');
                    $( "#omise-checkout-iframe-app" ).remove();
                    return;
                }
            });
            $(".btn-grande.confirm").click(function(event){
                event.preventDefault();
                var c_confirm = $("input[name=confirm]:checked").val();
                $.ajax({
                    type: "POST",
                    url: "/booking/booking_confirm",
                    data: $("form.grande-form").serialize(),
                    dataType: "json",
                    success: function(msg){

                        console.log(msg);
                        console.log(msg.status);
                        console.log(msg.message);
                        console.log(msg.message.sign_email);

                        //msg = JSON.parse(msg);
                        if (msg.status == "success") {
                            popError("ลงทะเบียนสำเร็จ","Success");
                            window.location = "/";
                        } else {
                            var errormsg = msg.message;
                            var step;
                            var allerrormsg = '';
                            for (var key in errormsg) {
                                if (typeof(step) === "undefined") {
                                    step = $("[name="+key+"]").parent().parent().data("step");
                                    $(".grande-form").removeClass("active4");
                                    $(".grande-form").addClass("active"+step);
                                }
                                hilight_input($("[name='"+key+"']"));
                                allerrormsg += errormsg[key]+"<br>";
                            }
                            popError(allerrormsg,"Error");
                        }

                    }
                });
            });

            $("input[name=sign_sameas]").click(function(){
                if ($("input[name=sign_sameas]:checked").val() == 'on') {

                    $("ul.signup-page li:nth-child(3) .row:nth-child(3)").addClass("disabled");
                    $("ul.signup-page li:nth-child(3) .row:nth-child(4)").addClass("disabled");
                    $("ul.signup-page li:nth-child(3) .row:nth-child(5)").addClass("disabled");
                    $("ul.signup-page li:nth-child(3) .row:nth-child(6)").addClass("disabled");

                    $("input[name=sign_doc_address_no").val('');
                    $("input[name=sign_doc_village").val('');
                    $("input[name=sign_doc_soi").val('');
                    $("select[name=sign_doc_province").val('');
                    $("select[name=sign_doc_district").val('');
                    $("select[name=sign_doc_subdistrict").val('');
                    $("input[name=sign_doc_postcode").val('');

                } else {
                    $("ul.signup-page li:nth-child(3) .row").removeClass("disabled");
                }
            });

            /*$("input[name=floor]").change(function(){
                $(".floor_img").css("opacity", "0.1");
                $floor_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/booking/get_floor_image",
                    data: { floor_id:$floor_id },
                    success: function(msg){
                        $(".floor_img").attr("src",msg);
                        $(".floor_img").css("opacity", "1");
                    }
                });
            });*/


            $(".logo-grande").attr("src","{$logo}");

            var c_floor = $("input[name=floor]:checked").val();
            $(".div_floor_"+c_floor).show();

            $("input[name=floor]").change(function(){
                $(".element-wrapper").show();
                var c_floor = $("input[name=floor]:checked").val();
                $(".div_floor").hide();
                $(".div_floor_"+c_floor).show();
                $("input[name=roomtype][value=0]").prop('checked', true);
                $("select[name=sign_roomtype_"+c_floor+"]").val("0");
                input_roomtype();
            });

            $("select[name=sign_floor]").change(function(){
                $(".element-wrapper").show();
                var c_floor = $("select[name=sign_floor]").val();
                $('input[name=floor]').removeAttr('checked');
                $("input[name=floor][value=" + c_floor + "]").prop('checked', true);

                $(".div_floor").hide();
                $(".div_floor_"+c_floor).show();
                $("input[name=roomtype][value=0]").prop('checked', true);
                $("select[name=sign_roomtype_"+c_floor+"]").val("0");
                $("select[name=sign_roomtype_"+c_floor+"]").removeAttr('disabled');
                select_roomtype();
            });

            function show_chackout($v_roomtype){
                if($v_roomtype == 0){
                    $("button.btn-grande.g-border.next").hide();
                } else {
                    $("button.btn-grande.g-border.next").show();
                }
                $(".element-wrapper").hide();
            }

            $("input[name=roomtype]").change(function(){
                $(".element-wrapper").show();
                var v_floor = $("input[name=floor]:checked").val();
                var v_roomtype = $("input[name=roomtype]:checked").val();
                var v_project_id = $("input[name=project_id]").val();
                $.ajax({
                    type: "POST",
                    url: "/booking/get_floor_image",
                    data: { project_id:v_project_id ,floor_id:v_floor ,roomtype_id:v_roomtype },
                    success: function(msg){
                        $(".floor_img_show").html(msg);
                        show_chackout(v_roomtype);
                    }
                });
            });

            function input_roomtype(){
                $(".element-wrapper").show();
                var v_floor = $("input[name=floor]:checked").val();
                var v_roomtype = $("input[name=roomtype]:checked").val();
                var v_project_id = $("input[name=project_id]").val();
                $.ajax({
                    type: "POST",
                    url: "/booking/get_floor_image",
                    data: { project_id:v_project_id ,floor_id:v_floor ,roomtype_id:v_roomtype },
                    success: function(msg){
                        $(".floor_img_show").html(msg);
                        show_chackout(v_roomtype);
                    }
                });
            }

            $(".sign_roomtype").change(function(){
                $(".element-wrapper").show();
                var v_floor = $("#sign_floor").val();
                var v_roomtype = $(this).val();
                var v_project_id = $("input[name=project_id]").val();
                $('input[name=roomtype]').removeAttr('checked');
                $("input[name=roomtype][value=" + v_roomtype + "]").prop('checked', true);

                $.ajax({
                    type: "POST",
                    url: "/booking/get_floor_image",
                    data: { project_id:v_project_id ,floor_id:v_floor ,roomtype_id:v_roomtype },
                    success: function(msg){
                        $(".floor_img_show").html(msg);
                        show_chackout(v_roomtype);
                    }
                });
            });

            function select_roomtype(){
                $(".element-wrapper").show();
                var v_floor = $("#sign_floor").val();    
                var v_roomtype = 0;
                var v_project_id = $("input[name=project_id]").val();
                if(v_floor === ""){
                    $.ajax({
                        type: "POST",
                        url: "/booking/get_floor_image",
                        data: { project_id:v_project_id ,floor_id:v_floor ,roomtype_id:v_roomtype },
                        success: function(msg){
                            $(".floor_img_show").html(msg);
                            show_chackout(v_roomtype);
                        }
                    });
                }
            }

            input_roomtype();
            select_roomtype();

        });

    </script>




<!-- div id="grande-bg">
    <div id="grande-bg-container">
        <div class="grande-bg-img">
            <img src="{$base_url}/assets/images/grande/bg/DSC_9063@1X.jpg" />
        </div>
    </div>
</div -->

        <ul class="cb-slideshow runanimated">
            <li><span>Image 01</span></li>
            <li><span>Image 02</span></li>
            <li><span>Image 03</span></li>
        </ul>

        <ul class="cb-slideshow nav runanimated">
            <li data-rel="1"></li>
            <li data-rel="2"></li>
            <li data-rel="3"></li>
        </ul>


<!-- Page Content -->

        <div class="container booking" style="width: 100%;max-width: initial;">
            <div class="h-100 row align-items-center" style="margin: 0;">
                <div class="book-info booking" style=''>

                    <div class="text">
                        <h1 class="underline">{$lang['booking_title']}</h1>
                        <form class="grande-form active2">
                            <input type="hidden" name="lang" value="{$lang['lang']}"/>
                            <input type="hidden" name="project_id" value="{$project_id}">
                            <div class="term-box">                                
                                <div class="msg"><i class="fa fa-times"></i>{$lang['term_title']}{$lang['term_detail']}</div>
                            </div>
                            <ul class="signup-page {$lang['lang']}">
                                <li data-step="1"></li>
                                <li data-step="2">
                                    <p class="title_booking" style="margin: 0;text-align: center;">
                                        {$lang['booking_title_select']}
                                    </p>
                                    <div class="select_floor_desktop">

                                        <div class="select_floor_1">
                                            <span>{$lang['booking_select_floor']}</span>
                                            <div style="column-count: {if $get_floor_in_project|@count > 12}3{elseif $get_floor_in_project|@count > 6}2{else}1{/if};">
                                            {foreach $get_floor_in_project as $key => $floor}
                                            <label class="ch_floor">{$floor.floor_name}
                                                <input type="radio" {if $key == 0}checked="checked"{/if} name="floor" value="{$floor.floor_id}">
                                              <span class="checkmark"></span>
                                            </label>
                                            {/foreach}
                                            </div>
                                        </div>
                                        <div class="select_floor_2">
                                            <span>{$lang['booking_select_roomtype']}</span>
                                            <label class="ch_floor">ALL
                                                <input type="radio" name="roomtype" checked="checked" value="0">
                                              <span class="checkmark"></span>
                                            </label>
                                            {foreach $get_roomtype_in_project as $key => $roomtype}
                                            <div class="div_floor div_floor_{$key}" style="display:none;">
                                                {foreach $roomtype as $k => $v}
                                                <label class="ch_floor">{$v.roomtype_name}
                                                    <input type="radio" name="roomtype" value="{$v.roomtype_id}">
                                                    <span class="checkmark"></span>
                                                </label>
                                                {/foreach}
                                            </div>
                                            {/foreach}
                                        </div>
                                        <div style="clear:both"></div>
                                    </div>
                                    <div class="select_floor_mobile">
                                        <div class="row m_dropdown">
                                            <select name="sign_floor" id="sign_floor" autocomplete="off">
                                                <option value="0" disabled="disabled" selected="selected">{$lang['booking_select_floor']}</option>
                                                {foreach $get_floor_in_project as $key => $floor}
                                                <option value="{$floor.floor_id}">{$floor.floor_name}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        {foreach $get_roomtype_in_project as $key => $roomtype}
                                        <div class="div_floor div_floor_{$key}" style="display:none;">
                                            <div class="row m_dropdown">
                                            <select disabled name="sign_roomtype_{$key}" class="sign_roomtype" id="sign_roomtype" autocomplete="off">
                                                <option value="0" disabled="disabled" selected="selected">{$lang['booking_select_roomtype']}</option>
                                                {foreach $roomtype as $k => $v}
                                                    <option value="{$v.roomtype_id}">{$v.roomtype_name}</option>
                                                {/foreach}
                                            </select>
                                            </div>
                                        </div>
                                        {/foreach}
                                    </div>
                                    <div class="row">
                                        {*$get_floor_in_project|@print_r*}
                                        <div class="floor_img_show">

                                        </div>
                                    </div>

                                    <div class="row pnl-btn-signup">
                                        <button class="btn-grande g-border back">{$lang['booking_back']}</button><button class="btn-grande g-border next" style="display:none;">{$lang['booking_next']}</button>
                                    </div>
                                    <div style="clear:both"></div>

                                </li>
                                <li data-step="3"></li>
                                <li data-step="4">
                                    <p class="title_booking" style="margin: 0;text-align: center;">{$lang['booking_title_confirm']}</p>
                                    <img src="{$base_url}/assets/images/booking/banner_comfirm.jpg" width="100%" />
                                    <div class="confirm_check_box">
                                        {$user}
                                    </div>
                                    <div class="row pnl-btn-signup"><button class="btn-grande g-border back">{$lang['booking_back']}</button><button class="btn-grande comserv g-border" style="display:none;">{$lang['booking_next']}</button></div>
                                    
                                </li>
                            </ul>
                            <ul class="signup-step ">
                                <li class=""><span>{$lang['booking_status_1']}</span></li>
                                <li class=""><span>{$lang['booking_status_2']}</span></li>
                                <li class=""><span>{$lang['booking_status_3']}</span></li>
                            </ul>
                            <div style="clear:both"></div>
                        </form>
                    </div>
                    <div class="panel-btn in">

                    </div>

                </div>

            </div>
        </div>

<!-- /#page-content-wrapper -->


<style>
.term-pop {
    
}
.term-box {
    position: fixed;
    left: 0px;
    top: 0px;
    right: 0px;
    bottom: 0px;
    background-color: rgba(0,0,0,0.9);
    z-index: 1031;
    /*box-shadow: 1px 1px 6px -2px rgba(0,0,0,0.8);
    border-radius: 6px;
    border: 1px solid rgb(186, 186, 186);*/
    display: flex;
    align-items: center;
    justify-content: center;
    display: none;
}
.term-box .msg{
    font-weight: normal;
    line-height: 1;
    font-size: 1.85rem;
    overflow-y: auto;    
    width: 100%;
    margin: 0 auto;
    background-color: #FFF;
    padding: 40px 10px 0 25px; 
    height: 100%;
    position: relative;
}
.term-box .fa.fa-times {
    position:absolute;right:12px;top:8px;font-size: 200%;cursor: pointer;
}
@media screen and (min-width: 480px){    
    .term-box .msg{   
        font-size: 1.5rem;
        width: 80%;        
        padding: 45px 45px 0;
        max-width: 720px;
        border-radius: 2px;        
        height: 520px;
    }
    .term-box .fa.fa-times {
        right:8px;
        font-size: 150%;
    }
}
</style>
<script>
$(document).ready(function() {



});

</script>
{include file="footer_grande.tpl"}
