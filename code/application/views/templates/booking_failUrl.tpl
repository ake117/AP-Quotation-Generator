{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

{literal}
    <style>
        .book-info.howto{
            padding: 0 1rem;
        }
        .book-info.howto .text h1 {
            letter-spacing: 0;
        }
        .book-info .text {
            margin-bottom: 2rem;
        }
        .book-info.howto .detail-title {
            text-align: center;
            font-size: 2.75rem;
            font-weight: normal;
            display: inline-table;
            width: auto;
            margin: 0.5rem auto 0.5rem;
        }
        .book-info.howto .detail-title::before, .book-info.howto .detail-title::after {
            content:" ";
            display:block;
            position: absolute;
            top: 2.1rem;
            width: 6rem;            
            border-top: 2px solid #B59354;
        }
        .book-info.howto .detail-title::before {            
            left: -6rem;           
        }
        .book-info.howto .detail-title::after {            
            right: -6rem;            
        }
        
        .book-info.howto .detail ul{
            list-style: disc;
            font-size: 1.5rem;
            line-height: 1.5rem;
        }
        
        .book-info.howto .topic>div {            
            height:238px;
            margin-top: 0rem;
        }
        .book-info.howto .topic>div .topic-detail {
            background-color: #FFF;
            height: 188px;
            margin-top: 50px;
            padding: 60px 10% 0;
            font-size: 1.5rem;
            line-height: 21px;
            font-size: 21px;
            text-align: center;
        }
        .book-info.howto .topic>div .topic-detail p {            
            text-align: center;
            font-size: 2rem;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .book-info.howto .topic>div .topic-icon {
            width: 90px;
            height: 90px;
            background-color:#B39050; 
            position: absolute;
            top:0;
            left:50%;
            margin-left:-45px;
            border-radius: 50%;
        }
        .book-info.howto .topic>div .topic-icon::after {
            content:" ";
            display:block;
            position: absolute;
            top: 0;
            left:0;
            width: 50px;
            height:50px;            
            left: 22px;
            top: 20px;
            background-repeat: no-repeat;  
            background-image: url(/assets/images/grande/howto.png);
        }
        .book-info.howto .topic>div .topic1 .topic-icon::after{            
            background-position-x: 1px;
            background-position-y: 0px;
        }
        .book-info.howto .topic>div .topic2 .topic-icon::after{            
            background-position-x: -52px;
            background-position-y: 0px;
        }
        .book-info.howto .topic>div .topic3 .topic-icon::after{            
            background-position-x: -104px;
            background-position-y: 0px;
        }
        .book-info.howto .topic>div .topic3 .topic-detail::after{
            content:" ";
            display:block;
            position: absolute;
            width: 200px;
            height: 33px;
            left: 50%;
            bottom: 0px;
            background-repeat: no-repeat;
            background-image: url(/assets/images/grande/howto.png);
            background-position-x: 0px;
            background-position-y: -47px;
            margin-left: -100px;
        }
        .book-info.howto .topic .detail-box {
            width: 250px;
            margin: 0 auto;
        }
        .book-info.howto .topic > div {
           margin-bottom: 1.5rem;
        }
        @media screen and (min-width: 768px) {
            .a-grande {text-align: left;}
            .book-info.howto {
                padding-top: 2.75rem;
                height: auto;
                padding-bottom: 0rem;
                margin-top: 5%;
                
            }
            .book-info.howto .grande-form {
                width:80%;
            }
            
            .book-info.howto .topic > div {
                margin-bottom: 0rem;
            }
            .book-info.howto .detail-title {
                margin: 0 auto;
            }
            .book-info .text {
                margin-bottom: 0rem;
            }
        }
        @media screen and (min-width: 1200px) {
            .book-info.howto .topic .detail-box {
                width: 100%;                
            }
        }
        .book-info.howto .text h1 {
            width: 100%;
        }
        .grande-form {     min-height: unset !important;  }
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
        <div class="container booking" style="width: 100%;">
            <div class="h-100 row align-items-center" style="margin: 0;">
                <div class="book-info howto" style=''>
                    <!-- a href="#" onclick="/"><span class="icon-close"></span></a -->
                    <div class="row text">
                        <h1 class="underline">{$lang['booking_reservation_summary']}</h1>   
                        <div class="grande-form">
                            
                            <div class="row"><div class="col-12 detail-title">{$lang['booking_confirm_fail']}</div></div>
                        </div>
                    </div>
                    <div class="panel-btnx in">
                        
                    </div>
                </div>
                
            </div>
        </div>
</div>    
<!-- /#page-content-wrapper -->



<script>
    {literal}
    $(document).ready(function() {



    });
    
    
    
    
    {/literal}
</script>
{include file="footer_grande.tpl"}
