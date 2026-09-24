{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

{literal}
    <style>

    </style>
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
        $grande_banner = Array({/literal}
            //'{$base_url}/assets/images/grande/bg/b1.jpg',
            '{$base_url}/assets/images/grande/bg/b2.jpg',
            //'{$base_url}/assets/images/grande/bg/b3.jpg'
        {literal});

        $(document).ready(function() {

            $banner_total = $grande_banner.length;
            $banner_total_duration = $banner_duration*$banner_total;

            
            //$(".cb-slideshow.runanimated li span").css("animation","imageAnimation "+$banner_total_duration+"s linear infinite 0s");
            //$(".cb-slideshow.nav.runanimated li").css("animation","navAnimation "+$banner_total_duration+"s linear infinite 0s");
            $.each($grande_banner, function( index, value ) {
                $banner_idx = index+1;
                $(".cb-slideshow li:nth-child("+$banner_idx+") span").css("background-image","url("+value+")");
                $(".cb-slideshow li:nth-child("+$banner_idx+") span").css('opacity','1');
                //$dalay = (index * $banner_duration)+'s';
                //$(".cb-slideshow li:nth-child("+$banner_idx+") span").css('animation-delay',$dalay);
                //$(".cb-slideshow.nav li:nth-child("+$banner_idx+")").css('animation-delay',$dalay);
            });
            /*

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
            */

            if ('{/literal}{$lang['lang']}{literal}'=='th') {
                $("input[name=sign_citizen_id]").inputmask({"mask": "9-9999-99999-99-9"});
                $("input[name=sign_phone]").inputmask({"mask": "999-999-9999"});
            }



            $(".btn-grande.next").click(function(event){
                event.preventDefault();

                if ($(".grande-form").hasClass('active1')) {

                    if (!check_field("input","sign_email",resource.sign_email,['empty','email','is_unique'])) {return;};
                    if (!check_field("input","sign_password",resource.sign_password,['empty',{'length':8}])) {return;};
                    if (!check_field("input","sign_password_confirm",resource.sign_password_confirm,['empty',{'length':8}])) {return;};

                    $pass1 = $("input[name='sign_password']").val()||'';
                    $pass2 = $("input[name='sign_password_confirm']").val()||'';

                    if ($pass1 !== $pass2) {
                        hilight_input($("input[name='sign_password_confirm']"));
                        popError(String.format(resource.validate_differs,resource.sign_password,resource.sign_password_confirm),"Error");
                        return;
                    }

                    $(".grande-form").removeClass('active1');
                    $(".grande-form").addClass('active2');

                    hilight_clearall('1');
                    return;
                }
                if ($(".grande-form").hasClass('active2')) {

                    if (!check_field("input","sign_name",resource.sign_name,['empty'])) {return;};
                    if (!check_field("input","sign_surname",resource.sign_surname,['empty'])) {return;};

                    if ('{/literal}{$lang['lang']}{literal}'=='en') {
                        if (!check_field("input","sign_passport_id",resource.sign_passport_id,['empty','passport_id','is_unique'])) {return;};
                        if (!check_field("input","sign_phone",resource.sign_phone,['empty'])) {return;};
                    } else {
                        if (!check_field("input","sign_citizen_id",resource.sign_citizen_id,['empty','check_id','is_unique'])) {return;};
                        if (!check_field("input","sign_phone",resource.sign_phone,['empty'])) {return;};
                        if (!check_field("input","sign_address_no",resource.sign_address_no,['empty'])) {return;};
                        if (!check_field("select","sign_province",resource.sign_province,['empty'])) {return;};
                        if (!check_field("select","sign_district",resource.sign_district,['empty'])){return;};
                        if (!check_field("select","sign_subdistrict",resource.sign_subdistrict,['empty'])){return;};
                        if (!check_field("input","sign_postcode",resource.sign_postcode,['empty'])){return;};
                    }


                    $(".grande-form").removeClass('active2');
                    $(".grande-form").addClass('active3')

                    hilight_clearall('2');
                    return;
                }
                if ($(".grande-form").hasClass('active3')) {

                    $name = $("select[name=sign_prefix]").val()+" "+$("input[name=sign_name]").val()+" "+$("input[name=sign_surname]").val();
                    $citizen_id = $("input[name=sign_citizen_id]").val();
                    $passport_id = $("input[name=sign_passport_id]").val();

                    $phone = $("input[name=sign_phone]").val();
                    $email = $("input[name=sign_email]").val();

                    if ('{/literal}{$lang['lang']}{literal}'=='en') {

                        if (!check_field("input","sign_country",resource.sign_country,['empty'])) {return;};
                        if (!check_field("input","sign_postcode",resource.sign_postcode,['empty'])){return;};
                        if (!check_field("input","sign_address1",resource.sign_address,['empty'])) {return;};
                        if ($("input[name=sign_sameas]:checked").val() != 'on') {
                            if (!check_field("input","sign_doc_country",resource.sign_country,['empty'])) {return;};
                            if (!check_field("input","sign_doc_postcode",resource.sign_postcode,['empty'])){return;};
                            if (!check_field("input","sign_doc_address1",resource.sign_address,['empty'])) {return;};
                        }

                        $addresss = $("input[name=sign_address1]").val();
                        $addresss += ($("select[name=sign_address2]").text()==='')?'':" "+$("input[name=sign_address2]").val();
                        $addresss += $("input[name=sign_country]").val();
                        $addresss += $("input[name=sign_postcode]").val();

                        if ($("input[name=sign_sameas]:checked").val() == 'on') {
                            $addresss_doc = "ที่อยู่ตามบัตรประชาชน";
                        } else {
                            $addresss_doc = $("input[name=sign_doc_address1]").val();
                            $addresss_doc += ($("select[name=sign_address2]").text()==='')?'':" "+$("input[name=sign_doc_address2]").val();
                            $addresss_doc += $("input[name=sign_doc_country]").val();
                            $addresss_doc += $("input[name=sign_doc_postcode]").val();
                        }


                        $("label[name=name]").text($name);
                        $("label[name=passport_id]").text($passport_id);
                        $("label[name=phone]").text($phone);
                        $("label[name=email]").text($email);
                        $("label[name=addresss]").text($addresss);
                        $("label[name=addresss_doc]").text($addresss_doc);

                        $(".grande-form").removeClass('active3');
                        $(".grande-form").addClass('active4')

                        hilight_clearall('3');
                        return;

                    } else {
                        if ($("input[name=sign_sameas]:checked").val() != 'on') {
                            if (!check_field("input","sign_doc_address_no",resource.sign_address_no,['empty'])) {return;};
                            if (!check_field("select","sign_doc_province",resource.sign_province,['empty'])) {return;};
                            if (!check_field("select","sign_doc_district",resource.sign_district,['empty'])){return;};
                            if (!check_field("select","sign_doc_subdistrict",resource.sign_subdistrict,['empty'])){return;};
                            if (!check_field("input","sign_doc_postcode",resource.sign_postcode,['empty'])){return;};
                        }

                        $addresss = $("input[name=sign_address_no]").val()
                        $addresss += ($("input[name=sign_village]").val()==='')?'':" "+$("input[name=sign_village]").val();
                        $addresss += ($("input[name=sign_soi]").val()==='')?'':" "+$("input[name=sign_soi]").val();
                        $addresss += ($("select[name=sign_subdistrict] option:selected").text()==='')?'':" "+$("select[name=sign_subdistrict] option:selected").text();
                        $addresss += ($("select[name=sign_district] option:selected").text()==='')?'':" "+$("select[name=sign_district] option:selected").text();
                        $addresss += ($("select[name=sign_province] option:selected").text()==='')?'':" "+$("select[name=sign_province] option:selected").text();
                        $addresss += ($("input[name=sign_postcode]").val()==='')?'':" "+$("input[name=sign_postcode]").val();

                        if ($("input[name=sign_sameas]:checked").val() == 'on') {
                            $addresss_doc = "ที่อยู่ตามบัตรประชาชน";
                        } else {
                            $addresss_doc = $("input[name=sign_doc_address_no]").val()
                            $addresss_doc += ($("input[name=sign_doc_village]").val()==='undefined')?'':" "+$("input[name=sign_doc_village]").val();
                            $addresss_doc += ($("input[name=sign_doc_soi]").val()==='')?'undefined':" "+$("input[name=sign_doc_soi]").val();
                            $addresss_doc += ($("select[name=sign_doc_subdistricte] option:selected").text()==='undefined')?'':" "+$("select[name=sign_doc_subdistricte] option:selected").text();
                            $addresss_doc += ($("select[name=sign_doc_district] option:selected").text()==='')?'undefined':" "+$("select[name=sign_doc_district] option:selected").text();
                            $addresss_doc += ($("select[name=sign_doc_province] option:selected").text()==='')?'undefined':" "+$("select[name=sign_doc_province] option:selected").text();
                            $addresss_doc += ($("input[name=sign_doc_postcode]").val()==='')?'undefined':" "+$("input[name=sign_doc_postcode]").val();
                        }

                        $("label[name=name]").text($name);
                        $("label[name=citizen_id]").text($citizen_id);
                        $("label[name=phone]").text($phone);
                        $("label[name=email]").text($email);
                        $("label[name=addresss]").text($addresss);
                        $("label[name=addresss_doc]").text($addresss_doc);

                        $(".grande-form").removeClass('active3');
                        $(".grande-form").addClass('active4')

                        hilight_clearall('3');
                        return;
                    }
                }
            });
            $(".btn-grande.back").click(function(event){
                event.preventDefault();
                if ($(".grande-form").hasClass('active2')) {
                    $(".grande-form").removeClass('active2');
                    $(".grande-form").addClass('active1');
                    return;
                }
                if ($(".grande-form").hasClass('active3')) {
                    $(".grande-form").removeClass('active3');
                    $(".grande-form").addClass('active2')
                    return;
                }
                if ($(".grande-form").hasClass('active4')) {
                    $(".grande-form").removeClass('active4');
                    $(".grande-form").addClass('active3')
                    return;
                }
            });
            $(".btn-grande.confirm").click(function(event){
                event.preventDefault();
                                                                              
                $.ajax({
                    type: "POST",
                    url: "{/literal}{$base_url}{literal}/signup/signup",
                    data: $("form.grande-form").serialize()+"&mode=add",
                    dataType: "json",
                    success: function(msg){
                        
                        //msg = JSON.parse(msg);
                        if (msg.status == "success") {
                            //popError(resource.sign_success,"Success");
                            //window.location = "{/literal}{$base_url}{literal}";
                            $email = $("input[name=sign_email]").val();               
                            $body = String.format(resource.sign_verify_body,$email,$email)                               
                            $(".book-info.signup .text h1").html(resource.sign_verify_title);
                            $(".book-info.signup .text h1").addClass("finish");
                            $(".finish .body").html($body);                                
                            $(".grande-form").removeClass('active4');
                            $(".grande-form").addClass('active5');
                            $(".signup-step ").css("display","none");
                            
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

                    if ($(".signup-page").hasClass('en')) {
                        $("ul.signup-page li:nth-child(3) .row:nth-child(6)").addClass("disabled");
                        $("ul.signup-page li:nth-child(3) .row:nth-child(7)").addClass("disabled");
                        $("ul.signup-page li:nth-child(3) .row:nth-child(8)").addClass("disabled");

                        $("input[name=sign_doc_address1").val('');
                        $("input[name=sign_doc_address2").val('');
                        $("input[name=sign_doc_country").val('');
                        $("input[name=sign_doc_postcode").val('');
                    } else {
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
                    }



                } else {
                    $("ul.signup-page li:nth-child(3) .row").removeClass("disabled");
                }
            });



            $("select[name=sign_province]").change(function(){
                $province_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/signup/get_amphur",
                    data: {province_id:$province_id},
                    success: function(msg){
                        $("select[name=sign_district]").html(msg);
                        $("input[name=sign_postcode]").val('');
                    }
                });
            });
            $("select[name=sign_district]").change(function(){
                $amphur_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/signup/get_tumbon",
                    data: {amphur_id:$amphur_id},
                    success: function(msg){
                        $("select[name=sign_subdistrict]").html(msg);
                        $("input[name=sign_postcode]").val('');
                    }
                });
            });
            $("select[name=sign_subdistrict]").change(function(){
                $tumbon_code = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/signup/get_postcode",
                    data: {tumbon_code:$tumbon_code},
                    success: function(msg){
                        $("input[name=sign_postcode]").val(msg);
                    }
                });
            });

            $("select[name=sign_doc_province]").change(function(){
                $province_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/signup/get_amphur",
                    data: {province_id:$province_id},
                    success: function(msg){
                        $("select[name=sign_doc_district]").html(msg);
                        $("input[name=sign_doc_postcode]").val('');
                    }
                });
            });
            $("select[name=sign_doc_district]").change(function(){
                $amphur_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/signup/get_tumbon",
                    data: {amphur_id:$amphur_id},
                    success: function(msg){
                        $("select[name=sign_doc_subdistrict]").html(msg);
                        $("input[name=sign_doc_postcode]").val('');
                    }
                });
            });
            $("select[name=sign_doc_subdistrict]").change(function(){
                $tumbon_code = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/signup/get_postcode",
                    data: {tumbon_code:$tumbon_code},
                    success: function(msg){
                        $("input[name=sign_doc_postcode]").val(msg);
                    }
                });
            });
            $(".btn-grande.home").click(function(event){
                event.preventDefault();
                window.location = "{/literal}{$base_url}{literal}";
            });

        });

    </script>
{/literal}



<!-- div id="grande-bg">
    <div id="grande-bg-container">
        <div class="grande-bg-img">
            <img src="{$base_url}/assets/images/grande/bg/DSC_9063@1X.jpg" />
        </div>
    </div>
</div -->

        <ul class="cb-slideshow runanimated">
            <li><span>Image 01</span></li>
            {*<li><span>Image 02</span></li>
            <li><span>Image 03</span></li>*}
        </ul>
        {*
        <ul class="cb-slideshow nav runanimated">
            <li data-rel="1"></li>
            <li data-rel="2"></li>
            <li data-rel="3"></li>
        </ul>
        *}


<!-- Page Content -->

        <div class="container booking" style="width: 100%;max-width: 1150px;">
            <div class="h-100 row align-items-center" >
                <div class="book-info signup" style=''>

                    <div class="text">
                        <h1 class="underline">{$lang['sign_title']}</h1>
                        <form class="grande-form active1">
                            <input type="hidden" name="lang" value="{$lang['lang']}"/>
                            <ul class="signup-page {$lang['lang']}">
                                <li data-step="1">
                                    <p class="title" >{$lang['sign_subtitle1']} <br class='show-mobile'><span class="form-info gray">{$lang['sign_subtitle11']}</span></p>
                                    <div class="row"><div class="form-title require">{$lang['sign_email']}</div><input name="sign_email" type="text" autocomplete="off"/></div>
                                    <div class="row"><div class="form-title require">{$lang['sign_password']}</div><input name="sign_password" type="password" autocomplete="off"/></div>
                                    <div class="row"><div class="form-title show-desktop">&nbsp;</div><span class="form-notify">{$lang['sign_password_valid']}</span></div>
                                    <div class="row"><div class="form-title require">{$lang['sign_password_confirm']}</div><input name="sign_password_confirm" type="password" autocomplete="off"/></div>
                                    <div class="row"><div class="form-title">&nbsp;</div><button class="btn-grande g-border next" style="margin-top:1rem;">{$lang['sign_next']}</button></div>
                                </li>
                                <li data-step="2">
                                    <p class="title">{$lang['sign_subtitle2']} <br class='show-mobile'><span class="form-info gray">{$lang['sign_subtitle21']}</span></p>

                                    {if ($lang['lang']=='en') }
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_prefix']}</div>
                                            
                                            {html_options name=sign_prefix options=$lang['signin_prefix']}
                                                                                        
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_name']}</div><input name="sign_name" type="text" autocomplete="off" />
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_surname']}</div><input name="sign_surname" type="text" autocomplete="off" />
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_passport_id']}</div>
                                            <input name="sign_passport_id" type="text" autocomplete="off" />
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_phone']}</div>
                                            <input name="sign_phone" type="text" autocomplete="off" />
                                        </div>
                                    {else}
                                        <div class="row">
                                            <div class="form-title auto require">{$lang['sign_prefix']}</div><select name="sign_prefix" ><option>นาย</option>><option>นางสาว</option>><option>นาง</option></select>
                                            <div class="form-title auto require">{$lang['sign_name']}</div><input name="sign_name" type="text" autocomplete="off" />
                                            <div class="form-title auto require">{$lang['sign_surname']}</div><input name="sign_surname" type="text" autocomplete="off" />
                                        </div>
                                        <div class="row">
                                            <div class="form-title auto require">{$lang['sign_citizen_id']}</div>
                                            <input name="sign_citizen_id" type="text" autocomplete="off" />
                                            <div class="form-title auto require">{$lang['sign_phone']}</div><input name="sign_phone" type="text" autocomplete="off" />
                                        </div>
                                        <p class="title addr">{$lang['sign_subtitle22']} <br class='show-mobile'><span class="form-info gray">{$lang['sign_subtitle23']}</span></p>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_address_no']}</div><input name="sign_address_no" type="text" />
                                            <div class="form-title">{$lang['sign_village']}</div><input name="sign_village" type="text" />
                                        </div>
                                        <div class="row">
                                            <div class="form-title">{$lang['sign_soi']}</div><input name="sign_soi" type="text" />
                                            <div class="form-title require">{$lang['sign_province']}</div>
                                            <select name="sign_province" autocomplete="off" >
                                                <option value='' disabled="disabled" selected='selected'>{$lang['sign_province_info']}</option>
                                                {html_options options=$province}
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_district']}</div>
                                            <select name="sign_district" autocomplete="off" >
                                                {html_options options=$district }
                                            </select>
                                            <div class="form-title require">{$lang['sign_subdistrict']}</div>
                                            <select name="sign_subdistrict" autocomplete="off" >
                                                {html_options options=$subdistrict }
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_postcode']}</div><input name="sign_postcode" type="text" autocomplete="off" />
                                        </div>
                                    {/if}
                                    <div class="row pnl-btn-signup"><button class="btn-grande g-border back">{$lang['sign_back']}</button><button class="btn-grande g-border next">{$lang['sign_next']}</button></div>
                                </li>
                                <li data-step="3">

                                    {if ($lang['lang']=='en') }
                                        <p class="title" style="text-align: left;">{$lang['sign_subtitle22']}<br class='show-mobile'><span class="form-info gray">{$lang['sign_subtitle23']}</span></p>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_country']}</div><input name="sign_country" type="text"/>
                                            <div class="form-title require">{$lang['sign_postcode']}</div><input name="sign_postcode" type="text"/>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_address1']}</div><input name="sign_address1" type="text" class="full"/>
                                        </div>
                                        <div class="row">
                                            <div class="form-title">{$lang['sign_address2']}</div><input name="sign_address2" type="text" class="full"/>
                                        </div>

                                        <div class="row" style="margin-top: 1rem;">
                                            <div class="form-title"><p class="title">{$lang['sign_subtitle3']}</p></div>
                                            <label for="sign_sameas" class="form-title auto" style="padding-left: 0;color:#000;">&nbsp;<input id="sign_sameas" name="sign_sameas" type="checkbox" class="form-title auto" autocomplete="off" style="margin-bottom: 0;">{$lang['sign_sameas']}</label>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_doc_country']}</div><input name="sign_doc_country" type="text"/>
                                            <div class="form-title require">{$lang['sign_postcode']}</div><input name="sign_doc_postcode" type="text"/>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_doc_address1']}</div><input name="sign_doc_address1" type="text" class="full"/>
                                        </div>
                                        <div class="row">
                                            <div class="form-title">{$lang['sign_doc_address2']}</div><input name="sign_doc_address2" type="text" class="full"/>
                                        </div>
                                    {else}
                                        <p class="title">{$lang['sign_subtitle3']}<br class='show-mobile'><span class="form-info gray">{$lang['sign_subtitle30']}</span></p>
                                        <div class="row">
                                            <div class="form-title auto">{$lang['sign_subtitle31']}</div><br class='show-mobile'/>
                                            <label for="sign_sameas" class="form-title auto">&nbsp;<input id="sign_sameas" name="sign_sameas" type="checkbox" class="form-title auto" autocomplete="off">{$lang['sign_sameas']}</label>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_address_no']}</div><input name="sign_doc_address_no" type="text"/>
                                            <div class="form-title">{$lang['sign_village']}</div><input name="sign_doc_village" type="text"/>
                                        </div>
                                        <div class="row">
                                            <div class="form-title">{$lang['sign_soi']}</div><input name="sign_doc_soi" type="text"/>
                                            <div class="form-title require">{$lang['sign_province']}</div>
                                            <select name="sign_doc_province" autocomplete="off" >
                                                <option value='' disabled="disabled" selected='selected'>{$lang['sign_province_info']}</option>
                                                {html_options options=$province}
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_district']}</div>
                                            <select name="sign_doc_district" autocomplete="off" >
                                                {html_options options=$district }
                                            </select>
                                            <div class="form-title require">{$lang['sign_subdistrict']}</div>
                                            <select name="sign_doc_subdistrict" autocomplete="off" >
                                                {html_options options=$subdistrict }
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="form-title require">{$lang['sign_postcode']}</div><input name="sign_doc_postcode" type="text"/>
                                        </div>

                                    {/if}

                                    <div class="row pnl-btn-signup"><button class="btn-grande g-border back">{$lang['sign_back']}</button><button class="btn-grande g-border next">{$lang['sign_next']}</button></div>
                                </li>
                                <li data-step="4">
                                    <p class="title">{$lang['sign_subtitle4']}</p>
                                    <div class="rowx"><div class="form-title">{$lang['sign_name_surname']}</div><label name="name"/></label></div>
                                    {if ($lang['lang']=='en') }
                                        <div class="rowx"><div class="form-title">{$lang['sign_passport_id']}</div><label name="passport_id" /></label></div>
                                    {else}
                                        <div class="rowx"><div class="form-title">{$lang['sign_citizen_id']}</div><label name="citizen_id" /></label></div>
                                    {/if}
                                    <div class="rowx"><div class="form-title">{$lang['sign_phone']}</div><label name="phone" /></label></div>
                                    <div class="rowx"><div class="form-title">{$lang['sign_email']}</div><label name="email" ></label></div>
                                    <div class="rowx"><div class="form-title">{$lang['sign_address']}</div><label name="addresss" /></label></div>
                                    <div class="rowx"><div class="form-title">{$lang['sign_address_doc']}</div><label name="addresss_doc" /></label></div>
                                    <div class="rowx pnl-btn-signup"><button class="btn-grande g-border back">{$lang['sign_back']}</button><button class="btn-grande g-border confirm">{$lang['sign_confirm']}</button></div>
                                </li>
                                <li class="finish" data-step="5">
                                    <p class="title">&nbsp;</p>
                                    <div class="rowx body" style="line-height: 2rem;text-align: center;">&nbsp;</div>                                    
                                    <div class="rowx pnl-btn-signup" style="text-align: center;"><button class="btn-grande g-border home">{$lang['sign_verify_button']}</button></div>
                                </li>
                            </ul>
                            <ul class="signup-step ">
                                <li class=""><span>{$lang['sign_status_1']}</span></li>
                                <li class=""><span>{$lang['sign_status_2']}</span></li>
                                <li class=""><span>{$lang['sign_status_3']}</span></li>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-btnx in">

                    </div>

                </div>

            </div>
        </div>
</div>
<!-- /#page-content-wrapper -->



<script>
$(document).ready(function() {
   

});

</script>
{include file="footer_grande.tpl"}
