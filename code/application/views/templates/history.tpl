{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

{literal}
    <style>
        .book-info{
            margin: 11rem auto 0rem;    
        }
        .book-info.signin .form-title {
            width: 10rem; 
        }
        .book-info.signin .grande-form {
            min-height: 16rem !important;
        }
        .book-info .text {
            margin-bottom:1rem;
        }
        
        @media screen and (min-width: 768px) {
            .history-link {
                padding:0;
            }
            .book-info.history{
                height: auto;
                margin-top: 8%;
                padding: 3rem 2rem 2rem;
            }
            .book-info.history .text{
                font-size: 1.5rem;
                /*font-weight: normal;*/
                width:90%;
            }
            .book-info.history #booking-detail.text {
                width: 45%;
                min-width: 15em;
            }
            .book-info.history .grande-form{
                min-height: 24rem !important;
                overflow: scroll;
                overflow-y: auto;
                overflow-x: hidden;
                max-height: 40rem;
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
                <div class="book-info history" style=''>
                    <!-- a href="#" onclick="/"><span class="icon-close"></span></a -->
                    <div id="booking" class="text">
                        <h1 class="underline">{$lang['history_title']}</h1>                                            
                        <div class="grande-form"> 
                            <script>$history=Array();</script>
                            {foreach from=$history key=key item=item}                                 
                                <div class="row">
                                    <div class="col-xs-12 col-sm-7 col-md-7">{$lang['history_subtitle']} {$item.project_name}</div>
                                    <div class="col-xs-12 col-sm-3 col-md-3">{$lang['history_date']} {$item.booking_date|date_format}</div>
                                    <div class="col-xs-12 col-sm-2 col-md-2 history-link" style=""><a data-id="{$key}" class="gold" href="#">{$lang['history_click']}</a></div>
                                </div>
                                <script>$history["{$key}"]={$item|json_encode}</script>
                            {/foreach}                                                       
                        </div>
                        <div class="row" style="margin:0;align-items: center;justify-content: center;">                                
                            <button class="btn-grande g-border btn-home">{$lang['history_button']}</button>                                
                        </div> 
                    </div>
                    <div id="booking-detail" class="text" style="display:none;">
                        <h1 class="underline">{$lang['history_title']}</h1>  
                        <a href="#" onclick="closeDetail(event)"><span class="icon-close"></span></a>
                        <div class="grande-form">
                            <p> </p>
                            <div class="row">
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6">{$lang['history_unit']}</div>
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6" id="unit"></div>                                
                            </div>
                            <div class="row">
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6">{$lang['history_floor']}</div>
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6" id="floor"></div>                                
                            </div>
                            <div class="row">
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6">{$lang['history_roomtype']}</div>
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6" id="roomtype"></div>                                
                            </div>
                            <div class="row">
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6">{$lang['history_roomsize']}</div>
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6" id="roomsize"> {$lang['history_sqm']}</div>                                
                            </div>
                            <div class="row">
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6">{$lang['history_roomprice']}</div>
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6" id="roomprice"> {$lang['history_baht']}</div>                                
                            </div>
                            <div class="row">
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6">{$lang['history_bookingprice']}</div>
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6" id="bookingprice"> {$lang['history_baht']}</div>                                
                            </div>
                        </div>
                        <div class="row" style="margin:0;align-items:center;justify-content:center;">                                
                            <button class="btn-grande g-border btn-home">{$lang['history_button']}</button>                                
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
                                              
    $(".btn-home").click(function (event) {
        event.preventDefault();
        window.location = "{$base_url}";
    });
    
    function closeDetail(event){
        event.preventDefault(); 
        $("#booking-detail").hide();
        $("#booking").show();        
    }
    
    $('.grande-form a').click(function (event) {
        event.preventDefault();
        $("#booking").hide();
        $("#booking-detail").show();
        
        $id = $(this).data('id');
        
        $("#booking-detail p").html("{$lang['history_subtitle']} "+$history[$id].project_name );
        $("#unit").html($history[$id].room_name);
        $("#floor").html($history[$id].floor_name);
        $("#roomtype").html($history[$id].roomtype_name);
        $("#roomsize").html($history[$id].room_size+" {$lang['history_sqm']}");
        $("#roomprice").html($history[$id].room_price+" {$lang['history_baht']}");
        $("#bookingprice").html($history[$id].booking_price+" {$lang['history_baht']}");                        
    });
    
    
    
</script>
{include file="footer_grande.tpl"}
