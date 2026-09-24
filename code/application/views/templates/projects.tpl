{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}

{ci_config name="path"}
{ci_config name="base_url"}

******** {$path.projects|print_r} ************

{literal}
    <style>
        .book-info {
            margin: 8rem auto 0rem;  
        }
        .book-info .text {
            margin-bottom: 2rem;            
        }  
        @media screen and (min-width: 768px) {
            .book-info .text {
                margin-bottom: 0;
            } 
            .book-info {
                margin: 0;
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
    
        <div class="container booking" style="width: 100%;max-width: 1150px;">
            <div class="h-100 row align-items-center" style="margin: 0;">
                <div class="book-info projects" style=''>
                    <!-- a href="#" onclick="/"><span class="icon-close"></span></a -->
                    <div class="grande-form active1 text">
                        <h1 class="underline">{$lang['projects_title']}</h1>                    
                        <p class="title" style="">{$lang['projects_subtitle']}</p>
                        <div class="project" data-item="1">
                            <div class="project-images">
                                <ul style="left:0;">
                                    
                                    {foreach from=$projects item=project}
                                        <li data-id="{$project.project_id}" data-name="{$project.project_name_url}">
                                            <div class="images selectitem" style="background-image:url({$path.projects}{$project.project_image})"></div>
                                            <div class="desc">
                                                <p class="title">{$project.project_name}</p>
                                                <p class="subtitle">{$project.project_detail_short}</p>
                                                <button class="btn-grande g-border selectitem">{$lang['projects_select']}</button>
                                            </div>
                                        </li>
                                    {/foreach}                                                                        
                                </ul>                                 
                                <div class="btn-slide back icon-left-open-big">&nbsp;</div>
                                <div class="btn-slide next icon-right-open-big">&nbsp;</div>                           
                            </div>
                            <div class="thumbs-box">
                                <div class="project-thumbs">
                                    <ul>
                                        {assign var="i" value=0}
                                        {foreach from=$projects item=project}
                                            {assign var="i" value=$i+1}
                                            <li class="{if ($i==1)}active{/if}" data-item="{$i}" data-id="{$project.project_id}" data-name="{$project.project_name_url}"><div class="thumbs"></div></li>
                                        {/foreach}                                        
                                    </ul> 
                                    <style>
                                        {assign var="w_all" value=$i*100}
                                        {assign var="w_item" value=100/$i}
                                        @media screen and (min-width: 768px) {
                                            .project-images ul {
                                                width: {$w_all}%!important;
                                            }
                                            .project-images ul li{    
                                                width: {$w_item}%!important;
                                            }
                                        } 
                                    </style>
                                </div> 
                            </div>    
                            <button class="btn-grande g-border select">{$lang['projects_select']}</button>
                        </div>
                        <ul class="signup-step ">
                            <li class=""><span>{$lang['booking_status_1']}</span></li>
                            <li class=""><span>{$lang['booking_status_2']}</span></li>
                            <li class=""><span>{$lang['booking_status_3']}</span></li>
                        </ul>                        
                    </div>
                                        
                </div>
                
            </div>
        </div>
</div>    
<!-- /#page-content-wrapper -->

<style>
    .book-info.projects .title{
        margin-bottom: 0.75rem;
        margin-top: 7.5rem;
    }
    
    .project {
        position: relative;
        width: 100%;
        margin: 0 auto;
    }
    .project-images {
        width: 100%;
        margin: 0;
        overflow: hidden;
        height: auto;
    }
    .project-images ul {
        margin:0;
        padding:0;
        width: 100%;
        float: left;
        height: 100%;        
        transition: 1s all;
    }
    .project-images ul li{
        margin:0;
        padding:0;
        width: 100%;
        float:left;                
        height: auto;
    }
    .project-images .images {
        /*background-image: url(/assets/images/grande/projects/hyde.jpg);*/
        background-size: cover;
        width: 100%;
        /*height: calc(100% - 13rem);*/
        background-position: center center;
        height: 15rem;
        cursor:pointer;
    }
    .project-images .desc {
        height:auto;
        padding-top: 2rem;
    }
    .project-images .desc p.title {
        margin: 0rem;
        line-height: 1;
    }
    .project-images .desc p.subtitle {
        font-size: 1.5rem;
        margin: 0rem;
        line-height: 1;
    }
    .thumbs-box {
        display: none;
        position: absolute;
        top: calc(100% - 11rem);
        width: 100%;
        height: 2rem;
    }
    .project-thumbs {
        /*position: absolute;
        top: 0;
        width: 8rem;*/
        height: 100%;
        /*left: 50%;
        margin-left: -12rem;*/
    }    
    .project-thumbs ul {
        margin: 0 auto;
        padding: 0;        
        height: 100%;        
        width: auto;                
        display: inline-block;            
        transition: 1s all;
    }
    .project-thumbs ul li{
        margin:0;
        padding:0;       
        float:left;        
        height: 100%;
        opacity:0.5;
        width: 7rem;
        cursor: pointer;
    }
    .project-thumbs ul li.active{
        opacity:1;
    }
    .project-thumbs .thumbs {
        background-image: url(/assets/images/grande/projects/hyde.jpg);
        background-size: cover;        
        height: calc(100% - 10rem);
        background-position: center center;
        /*width: 8rem;*/
        margin: 0;
        overflow: hidden;
        height: 100%;
        margin-right: 1rem;
    }
    
    
    .btn-slide {
        display:none;
        position: absolute;
        top:0;
        height: calc(100% - 14rem);
        width: 4rem;
        cursor: pointer;
    }
    .btn-slide.back{
        left:0;
    }
    .btn-slide.next{
        right:0;
    }
    .btn-slide:before {
        position: relative;
        top: 50%;
        transform: translateY(-50%); 
    }
    .book-info.projects .btn-grande {
        display: none;
    }
    .book-info.projects .project-images .btn-grande {
        display: block;
        margin-top: 2rem;
    }

    @media screen and (min-width: 768px) {
        .project-images ul{
            position: relative;
        }
        .project-images ul li{
            height:100%;
        }                    
        .book-info.projects .title{            
            margin-top: 0rem;
        }
        .project {
            width: 58%;
        }
        .project-images {
            width: calc(100% - 8rem);
            margin: 0 4rem;
            height: 22rem;
        }
        .btn-slide {
            display:block;
        }
        .thumbs-box {
            display:block;
        }
        .project-images .images {        
            height: calc(100% - 9rem);
        }
        .project-images .desc {
            height:10rem;
            padding-top: 4rem;
        }
        .project-thumbs ul {                    
            
        }
        
        .book-info.projects .btn-grande {
            display: inline;
        }
        .book-info.projects .project-images .btn-grande {
            display: none;            
        }
    }
    
</style>

<script>
    {literal}
    $(document).ready(function() {
        
        $(".btn-slide.next").click(function(){
                                    
            $item = parseInt($(".project").data("item"));                        
            $totalItems = parseInt($(".project .project-images ul li").length);
                        
            if ($item < $totalItems) {
                
                $item = $item+1;  
                
                $(".project").data("item",$item);
                $pos = ($item-1) * -100;
                $(".project .project-images ul").css('left',$pos+'%');                 
                $(".project .project-thumbs ul li").removeClass('active');
                $(".project .project-thumbs ul li").eq($item-1).addClass('active');
            }
            
        })
        $(".btn-slide.back").click(function(){
                                    
            $item = parseInt($(".project").data("item"));                        
            $totalItems = parseInt($(".project .project-images ul li").length);
                        
            if ($item > 1) {
                
                $item = $item-1; 
                
                $(".project").data("item",$item);
                $pos = ($item-1) * -100;
                $(".project .project-images ul").css('left',$pos+'%');                 
                $(".project .project-thumbs ul li").removeClass('active');
                $(".project .project-thumbs ul li").eq($item-1).addClass('active');
            }
            
        })
        $(".project-thumbs li").click(function(){
            $item = $(this).data('item');            
            $(".project").data("item",$item);
            $pos = ($item-1) * -100;
            $(".project .project-images ul").css('left',$pos+'%');             
            $(".project .project-thumbs ul li").removeClass('active');
            $(".project .project-thumbs ul li").eq($item-1).addClass('active');
        });
        
        $(".btn-grande.select").click(function(){
            $id = $(".project-thumbs li.active").data("id");
            $name = $(".project-thumbs li.active").data("name");
            window.location = "{/literal}{$base_url}{literal}"+"/project/"+$name+"/"+$id
        });
        $(".btn-grande.selectitem").click(function(){
            $id = $(this).parent().parent().data("id");
            $name = $(this).parent().parent().data("name");
            window.location = "{/literal}{$base_url}{literal}"+"/project/"+$name+"/"+$id
        });
        $(".project-images .images.selectitem").click(function(){            
            $id = $(this).parent().data("id");
            $name = $(this).parent().data("name");
            window.location = "{/literal}{$base_url}{literal}"+"/project/"+$name+"/"+$id
        });


    });
    
    
    
    
    {/literal}
</script>
{include file="footer_grande.tpl"}
