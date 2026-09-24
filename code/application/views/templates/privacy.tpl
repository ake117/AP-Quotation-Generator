{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

{literal}
    <style>    
        .book-info.term h2 {
            font-size: 2.25rem;
            line-height: 1;
            margin-bottom: 0.25rem;
            font-weight: bold;
            letter-spacing: unset;
        }
        .book-info.term .grande-form {
            min-height: 16rem !important;
            font-weight: normal;
        } 
        .indent {padding-left: 1.25rem}
        @media screen and (min-width: 768px) {
            .book-info.term h2 {
                /*font-size: 1.75rem;*/
            }
            .book-info.term{
                height: auto;
                margin-top: 5%;
                padding: 3.5rem 0.5rem 2rem 3rem;
            }
            .book-info.term .text{
                font-size: 1.5rem;
                /*font-weight: normal;*/
                width:95%;
            }            
            .book-info.term .grande-form{                
                overflow: scroll;
                overflow-y: auto;
                overflow-x: hidden;
                max-height: 18em;
                line-height: 1.5rem;
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
                <div class="book-info term" style=''>
                    <!-- a href="#" onclick="/"><span class="icon-close"></span></a -->
                    <div id="booking" class="text">
                        {*<h1 class="underline">{$lang['term_title']}</h1>*}
                        <div class="grande-form" style="font-weight: normal;"> 
                            {$lang['privacy']}               
                        </div>
                        
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
