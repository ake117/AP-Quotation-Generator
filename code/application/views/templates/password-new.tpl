{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

{literal}
    <style>
        .book-info.signin .form-title {
            width: 10rem; 
        }
        .book-info.signin .grande-form {
            min-height: 16rem !important;
        }
        input[disabled=disabled] {
            border: transparent;
            background-color: transparent;
        }

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
            '{$base_url}/assets/images/grande/bg/b1.jpg',
            '{$base_url}/assets/images/grande/bg/b2.jpg',
            '{$base_url}/assets/images/grande/bg/b3.jpg'
        {literal});
        
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
            <li><span>Image 02</span></li>
            <li><span>Image 03</span></li>            
        </ul>

        <ul class="cb-slideshow nav runanimated">
            <li data-rel="1"></li>
            <li data-rel="2"></li>
            <li data-rel="3"></li>            
        </ul>
        

<!-- Page Content -->
    
        <div class="container booking" style="width: 100%;max-width: 800px;">
            <div class="h-100 row align-items-center" style="margin: 0;">
                <div class="book-info signin" style=''>
                    <!-- a href="#" onclick="/"><span class="icon-close"></span></a -->
                    {if ($member|count > 0)} 
                    <div id="changepass" class="text">
                        <h1 class="underline">{$lang['newpass_title']}</h1>                                            
                        <form class="grande-form">
                            <p style="margin-bottom: 1rem;text-align:center;">{$lang['newpass_detail']}</p>
                            <input type="hidden" name="sign_member_id" value="{$member.member_id}"/>
                            <div class="rowx"><div class="form-title">{$lang['chpass_email']}</div><input type="text" name="email" value="{$member.email}" disabled="disabled"/></div>
                            <div class="rowx"><div class="form-title">{$lang['chpass_newpassword']}</div><input type="password" name="sign_password"/></div>
                            <div class="rowx"><div class="form-title">{$lang['chpass_confirm']}</div><input type="password" name="sign_password_confirm"/></div>
                            <div class="rowx">
                                <div class="form-title">&nbsp;</div>
                                <button class="btn-grande g-border btn-save">{$lang['chpass_button']}</button>                                
                            </div>                            
                        </form>
                    </div>
                    <div id="success_changepass" class="text" style="display:none;">
                        <h1 class="underline">{$lang['newpass_title']}</h1>                                            
                        <form class="grande-form">
                            <p style="margin-bottom: 2rem;text-align:center;">{$lang['newpass_success']}</p>
                            <div class="rowx"><div class="form-title" style="text-align: right;">{$lang['chpass_email']}</div><input type="text" name="email" value="{$member.email}" disabled="disabled" style="border: 0 solid;background-color: transparent;"/></div>                            
                            <div class="rowx" style="margin-top: 2rem;">                                
                                <button class="btn-grande g-border btn-signin" style="margin: 0 auto;display: block;">{$lang['signin_button']}</button>                                
                            </div>                            
                        </form>
                    </div>
                    {else}
                    <div id="success_changepass" class="text" style="">
                        <h1 class="underline">{$lang['newpass_title']}</h1>                                            
                        <form class="grande-form">
                            <p style="margin-bottom: 2rem;text-align:center;">{$lang['newpass_detail_invalid']}</p> 
                            <div class="rowx">&nbsp</div>    
                            <div class="rowx" style="margin-top: 2rem;">                                
                                <button class="btn-grande g-border btn-home" style="margin: 0 auto;display: block;">{$lang['chpass_home']}</button>                                
                            </div>                            
                        </form>
                    </div>
                    {/if}   
                </div>
                
            </div>
        </div>
</div>    
<!-- /#page-content-wrapper -->



<script>
    {literal}
    $(document).ready(function() {
        
        $(".btn-save").click(function (event) {
            event.preventDefault();
            save();        
        });
        $(".btn-signin").click(function (event) {
            event.preventDefault();
            window.location = "{/literal}{$base_url}{literal}/signin";
        });
        $(".btn-home").click(function (event) {
            event.preventDefault();
            window.location = "{/literal}{$base_url}{literal}";
        });

    });
    
    function save() {
        
        if (!check_field("input","sign_password",resource.sign_password,['empty',{'length':8}])) {return;}
        if (!check_field("input","sign_password_confirm",resource.sign_password_confirm,['empty',{'length':8}])) {return;};
        
        $pass1 = $("input[name='sign_password']").val()||'';
        $pass2 = $("input[name='sign_password_confirm']").val()||'';

        if ($pass1 !== $pass2) {
            hilight_input($("input[name='sign_password_confirm']"));
            popError(String.format(resource.validate_differs,resource.sign_password,resource.sign_password_confirm),"Error");
            return;
        }
        
        $.ajax({
            type: "POST",
            url: "{/literal}{$base_url}{literal}/password/submitchangereset",
            data: $(".grande-form").serialize(),
            dataType: "json",
            success: function(msg){
                if (msg.status === 'success') {
                    //popError("Change password success.","Success");
                    $("#changepass").css('display','none');
                    $("#success_changepass").css('display','block');
                } else {
                    popError(msg.message,"Error");
                }
            }
        }); 
    }
                                   
    
        
    
    
    {/literal}
</script>
{include file="footer_grande.tpl"}
