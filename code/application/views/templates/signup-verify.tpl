{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

{literal}
    <style>
        .book-info.signin .grande-form {
            margin-bottom: 2rem;
        }
        @media screen and (min-width: 768px) {
            .book-info.signin .grande-form {
                width: 50rem;
                text-align: center;
            }        
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
                    <div id="success_changepass" class="text" >
                        <h1 class="underline">{$lang['sign_success_title']}</h1>                                            
                        <form class="grande-form">
                            <p style="margin-bottom: 2rem;text-align:center;">{$success_content}</p>
                                          
                            <div class="rowx" style="margin-top: 2rem;">                                
                                <button class="btn-grande g-border btn-signin" style="margin: 0 auto;display: block;">{$lang['signin_button']}</button>                                
                            </div>                            
                        </form>
                    </div>
                    {else}
                    <div id="success_changepass" class="text" style="">
                        <h1 class="underline">{$lang['sign_success_title_invalid']}</h1>                                            
                        <form class="grande-form">
                            <p style="margin-bottom: 2rem;text-align:center;">{$lang['sign_verify_invalid']}</p> 
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
                
        $(".btn-signin").click(function (event) {
            event.preventDefault();
            window.location = "{/literal}{$base_url}{literal}/signin/{/literal}{$member.email|base64_encode|urlencode}{literal}";
        });
        $(".btn-home").click(function (event) {
            event.preventDefault();
            window.location = "{/literal}{$base_url}{literal}";
        });

    });
    
    {/literal}
</script>
{include file="footer_grande.tpl"}
