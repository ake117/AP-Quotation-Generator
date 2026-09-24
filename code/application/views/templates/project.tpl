{include file="header_grande.tpl"}

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}

{ci_config name="path"}
{ci_config name="base_url"}

<script type="text/javascript" src="{$base_url}/assets/js/fullpage/fullpage.js"></script>
<link rel="stylesheet" type="text/css" href="{$base_url}/assets/js/fullpage/fullpage.css" />

{literal}
    <style>
        /* Don't move !! */
        .navbar {
            height: 7rem;
            background-color: transparent;
        }
        .navbar-brand {
            width: auto;
        }
        #fp-nav {display:none}
        .cb-slideshow li span::after {
            border: 0 solid;
        }
        #page-content-wrapper, .page-content-wrapper{
            margin-top: 0%;
        }
        .book-info.project-page {
            background-color: transparent;
            position: absolute;
            top: 2rem;
            text-align: center;
            width: 100%;
            margin: 0;                        
        }
        .book-info.project-page .text{
            margin-top:0;
        }
        #section1 #page-content-wrapper, #section1 .page-content-wrapper {
            /*padding: 5rem 0 5rem;*/                        
            background-color: #DBC8A0;
            height: auto;
            padding:0;                        
            width: 88%;
            max-width: 340px;  
            margin-top: 15%;
        }
        #section2 #page-content-wrapper, #section2 .page-content-wrapper {            
            height: auto;
            padding:0;
        }
        #section3 #page-content-wrapper, #section3 .page-content-wrapper {            
            height: auto;
            padding:0;
        }
        #section4 #page-content-wrapper, #section4 .page-content-wrapper {
            height: auto;
            padding:0;
            width:100%;
        }
        
        #page-content-wrapper.facility, .page-content-wrapper.facility {
             background-color:transparent;
        }
        .project-detail-image {
            width: 100%;
            height: 260px;
            overflow: hidden;
        }
        .project-detail {
            width: 100%;
            text-align: center;
            padding: 5px;
        }
        .project-detail p, .project-detail div {
            margin-bottom: 1rem;
            line-height: 1;
            margin: 0 0 5px;
        }
        .project-detail div {
            padding-top: 5px;
        }
        .btn-booking {
            position: absolute;
            bottom: -47px;
            /*left: 50%;
            margin-left: -120px;
            width: 101%;*/
            left: -1px;
            width: calc(100% + 2px);
            font-size:22px;
        }
        
        #section2, #section4, #section5 {
            background-image: url({/literal}{$base_url}{literal}/assets/images/grande/bg/marble.jpg);
            background-size: cover;
        }
        
        .facility-images {
            width: calc(100%);
            margin: 0;
            height: 30rem;
            overflow: hidden;
        }
        .facility-images ul {
            margin: 0;
            padding: 0;
            width: 100%;
            float: left;
            height: 100%;
            transition: 1s all;            
            position: relative;
        }
        .facility-images ul li {
            margin: 0;
            padding: 0;
            width: 100%;
            float: left;
            /*height: 38rem;*/
            height: 100%;
        }
        
        .project-facility-image {                        
            float: right;
            height: 80%;
            width: 100%;
            margin-top: 0;
        }
        
        .project-facility {
            width: 100%;            
            height: 20%;            
            text-align: center;
            font-size: 2rem;
            text-transform: uppercase;                        
            line-height: 1;
            font-weight: bold;  
            float: left;
            line-height: 3;
        }
        
        .roomtype-images {
            width: calc(100%);
            margin: 0;
            height: 36rem;
            overflow: hidden;
        }
        .roomtype-images ul {
            margin: 0;
            padding: 0;
            width: 100%;
            float: left;
            height: 100%;
            transition: 1s all;            
            position: relative;
        }
        .roomtype-images ul li {
            margin: 0;
            padding: 0;
            width: 100%;
            float: left;
            /*height: 38rem;*/
            height: 100%;
        }
        .roomtype-images .btn-slide {            
            height: 80%;
            top:20%
        }
        .roomtype-images .btn-slide.back {
            left:0;            
        }
        .project-roomtype-image {                        
            float: right;
            height: calc(100% - 5rem);
            width: 100%;
            margin-top: 0;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .project-roomtype {
            width: 100%;            
            height: 5rem;            
            text-align: center;
            font-size: 2rem;
            text-transform: uppercase;                        
            line-height: 1;
            font-weight: bold;  
            float: left;
            line-height: 3;
        }
        .project-roomtype p {
            display: inline;            
            line-height: 1;
            margin-bottom: 0rem;
            width: 99%;
            display: inline-block;
            padding: 0.5rem 1rem;
        }
        
        .gallery-images {
            width: calc(100%);
            margin: 0;
            height: 30rem;
            overflow: hidden;
        }
        .gallery-images ul {
            margin: 0;
            padding: 0;
            width: 100%;
            float: left;
            height: 100%;
            transition: 1s all;            
            position: relative;
        }
        .gallery-images ul li {
            margin: 0;
            padding: 0;
            width: 100%;
            float: left;
            /*height: 38rem;*/
            height: 100%;
            position:relative;
        }
        .gallery-images .btn-slide {            
            height: 80%;
            /*top:20%*/
        }
        .gallery-images .btn-slide.back {
            left:0;            
        }
        .project-gallery-image {                        
            float: right;
            height: 100%;
            width: 100%;
            margin-top: 0;
        }
        
        .project-gallery {
            width: 100%;            
            height: 15%;            
            text-align: center;
            font-size: 2rem;
            text-transform: uppercase;                        
            line-height: 1;
            font-weight: bold;  
            float: left;
            line-height: 2;
            position: absolute;
            bottom: 0;
            background-color: #FFFFFF80;
        }
        .project-gallery p {
            display: inline;            
            line-height: 1;
            margin-bottom: 0rem;
            width: 100%;
            display: inline-block;
            padding: 0.5rem 1rem;
        }
        #section5 .btn-group {
            text-align: center;
            display: block;
            margin-top: 1rem;
        }
        #section5 .btn-group .btn-grande {            
            margin-left: 1rem;
            position: unset;            
            width: 80%;
            margin: 1.25rem 0 0;
            font-size:1.75rem;
            font-size: 22px;
        }
        #section5 .btn-group .btn-booking {
            margin-top: 1rem;
        }
        .navbar-brand .logo-grande, .project-detail .logo-grande {
            height: 56px;
            width: auto;
            margin: 0 auto 5px;
            display: block;
        }
        .project-detail .logo-grande {
            display:none;
        }
        #section5 iframe {                
            margin-top: 60px;
            width: 100%;
            height: 25rem;
        }


        @media screen and (min-width: 768px) {
            #fp-nav {display:block}
            .navbar-brand .logo-grande, .project-detail .logo-grande {
                height: 86px;                
            }
            .project-detail .logo-grande {
                display:block;
            }
            .navbar-brand {
                margin: 1rem auto 0;
                position: unset;
            }
            #section5 iframe {                
                margin-top: 80px;
                width: 100%;
                height: 60%;
            }
            #page-content-wrapper, .page-content-wrapper{
                margin-top: 0px;
            }
            #section1 .page-content-wrapper {                
                margin-top: 30px;
            }
            #section3 .page-content-wrapper, #section4 .page-content-wrapper {                
                margin-top: 80px;
            }
            
            #page-content-wrapper, .page-content-wrapper {
                /*padding: 5rem 0 5rem;*/
                width: 44%;
                max-width: 800px;
                min-width: 600px; 
                text-align: center;
            }
            #section4 #page-content-wrapper, #section4 .page-content-wrapper {
                width:44%;
            }
            .book-info.project-page {
                top: 4rem;
                min-height: auto;
                height: auto;
            }
            .project-detail-image {
                width: 40%;
                float: left;
                height: auto;                
            }
            .project-detail {
                width: 60%;
                float: left;
                padding: 5% 5% 0 5%;
            }
            .btn-booking {                
                bottom: -50px;                
                margin-left: -60px;
                width: auto;                
                left: 50%;
                margin-left: -5rem;  
                width: 10rem;
            }
            .project-facility-image {
                height: 90%;            
                width: 80%;
                margin-top: 2.5rem;
            }
            .facility-images::before {
                content: '';
                border: 1px solid #B38F4F;
                position: absolute;
                left: -2rem;
                right: 10rem;
                top: 1rem;
                bottom: 2rem;
            }
            .project-facility {
                width: 24%;
                top: 50%;
                position: relative;
                transform: translateY(-50%);
                text-align: left;
                float: none;
                line-height: 1;
            }
            .project-roomtype p {
                display: inline;                
            }
            .project-gallery p {
                display: inline;                
            }
            #section5 .btn-group .btn-grande {
                width: 10rem;
                padding: 0;                   
                margin: 0;
            }
            #section5 .btn-group .btn-booking {
                /*margin-left: 2rem;*/
                float: right;                
            }
            #section5 .btn-group .btn-back {
                /*margin-left: 2rem;*/
                float: left;                
            }
            #section5 .btn-group {
                margin: 1rem auto;
                width: 22rem;                
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

<div id="fullpage">
    <div class="section" id="section0">
        <div id="page-content-wrapper">
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
        </div>
    </div>
    <div class="section" id="section1">
                
        <div id="" class="page-content-wrapper row g-border onepx" style="/*padding-top: 45%;*/">
            <div style="">
                <div class="project-detail-image" style="">
                    <img src="{$path['projects']}{$project.project_detail_image}" class="logo-grande" alt="" style="width: 100%;">
                </div>
                <div class="project-detail" style="">
                    <img src="{$path['projects']}{$project.project_logo}" class="logo-grande" alt="" >
                    <div>{$project.project_detail}</div>
                </div>
            </div>
            <button class="btn-grande g-border btn-booking">{$lang['project_booking']}</button>
        </div>


        <div class="book-info project-page" style="">
            <div class="text"> 
                <h1 class="underline">{$lang['project_detail']}</h1> 

            </div>
        </div>
        
    </div>
    <div class="section" id="section2">
        
        <div id="" class="page-content-wrapper row facility" style="">
            <div class="facility-images project-slide" data-item="1">
                <ul style="left: 0%;">
                    {assign var="i" value=0}
                    {foreach from=$facility key=key item=item} 
                        {assign var="i" value=$i+1}
                        <li>
                            <div class="project-facility-image" style="background-image:url({$path['facility']}{$item.facility_image});">                            
                            </div>
                            <div class="project-facility" style="">                   
                                <p>{$item.facility_name}</p>                    
                            </div>
                        </li>                        
                    {/foreach}
                </ul>
                <div class="btn-slide back icon-left-open-big">&nbsp;</div>
                <div class="btn-slide next icon-right-open-big">&nbsp;</div>
            </div>
            <style>
            
            {assign var="w_all" value=$i*100}
            {assign var="w_item" value=100/$i}
            {*@media screen and (min-width: 768px) {*}
                .facility-images ul {
                    width: {$w_all}%!important;
                }
                .facility-images ul li{    
                    width: {$w_item}%!important;
                }
            {*}*}
            </style>
        </div>
        
        <div class="book-info project-page" style="">
            <div class="text"> 
                <h1 class="underline">{$lang['project_facility']}</h1> 

            </div>
        </div>
    </div>
    <div class="section" id="section3">
        
        <div id="" class="page-content-wrapper row roomtype" style="">
            <div class="roomtype-images project-slide" data-item="1">
                <ul style="left: 0%;">
                    {assign var="i" value=0}
                    {foreach from=$roomtype key=key item=item} 
                        {assign var="i" value=$i+1}                        
                        <li>
                            <div class="project-roomtype" style="">                   
                                <p class="g-border onepx">
                                    {$item.roomtype_gallery_name}
                                </p>                    
                            </div>
                            <div class="project-roomtype-image" style="background-image:url({$path['roomtype']}{$item.roomtype_gallery_image});">                            
                            </div>                        
                        </li>
                    {/foreach}                    
                </ul>
                <div class="btn-slide back icon-left-open-big">&nbsp;</div>
                <div class="btn-slide next icon-right-open-big">&nbsp;</div>
            </div>
            <style>            
            {assign var="w_all" value=$i*100}
            {assign var="w_item" value=100/$i}
            {*@media screen and (min-width: 768px) {*}
                .roomtype-images ul {
                    width: {$w_all}%!important;
                }
                .roomtype-images ul li{    
                    width: {$w_item}%!important;
                }
            {*}*}
            </style>
        </div>
        <div class="book-info project-page" style="">
            <div class="text"> 
                <h1 class="underline">{$lang['project_roomtype']}</h1> 

            </div>
        </div>
    </div>
    <div class="section" id="section4">
        <div id="" class="page-content-wrapper row gallery" style="">
            <div class="gallery-images project-slide" data-item="1">
                <ul style="left: 0%;">
                    {assign var="i" value=0}
                    {foreach from=$gallery key=key item=item} 
                        {assign var="i" value=$i+1}
                        <li>                        
                            <div class="project-gallery-image" style="background-image:url({$path['gallery']}{$item.gallery_image});">                            
                            </div> 
                            <div class="project-gallery" style="">                   
                                <p>
                                    {$item.gallery_name}
                                </p>                    
                            </div>
                        </li>
                    {/foreach}                     
                </ul>
                <div class="btn-slide back icon-left-open-big">&nbsp;</div>
                <div class="btn-slide next icon-right-open-big">&nbsp;</div>
            </div>
            <style>            
            {assign var="w_all" value=$i*100}
            {assign var="w_item" value=100/$i}
            {*@media screen and (min-width: 768px) {*}
                .gallery-images ul {
                    width: {$w_all}%!important;
                }
                .gallery-images ul li{    
                    width: {$w_item}%!important;
                }
            {*}*}
            </style>
        </div>
        <div class="book-info project-page" style="">
            <div class="text"> 
                <h1 class="underline">{$lang['project_gallery']}</h1> 

            </div>
        </div>
    </div>
    <div class="section" id="section5">
        <iframe src="{$project.project_360}" style=""></iframe>
        <div class="btn-group">
            <button class="btn-grande g-border btn-booking">{$lang['project_booking']}</button>
            <button class="btn-grande g-border btn-back">{$lang['project_back']}</button>            
        </div>
        <div class="book-info project-page" style="">
            <div class="text"> 
                <h1 class="underline">{$lang['project_360']}</h1> 

            </div>
        </div>
    </div>
</div>



<!-- div id="grande-bg">
    <div id="grande-bg-container">
        <div class="grande-bg-img">
            <img src="{$base_url}/assets/images/grande/bg/DSC_9063@1X.jpg" />
        </div>
    </div>
</div -->
        {*
        
        *}

<!-- Page Content -->
    {*
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
                                        <li data-id="{$project.project_id}">
                                            <div class="images" style="background-image:url({$path.projects}{$project.project_image})"></div>
                                            <div class="desc">
                                                <p class="title">{$project.project_name}</p>
                                                <p class="subtitle">{$project.project_detail}</p>
                                                <button class="btn-grande g-border select">{$lang['projects_select']}</button>
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
                    </div>
                    <ul class="signup-step ">
                        <li class=""><span>{$lang['sign_status_1']}</span></li>
                        <li class=""><span>{$lang['sign_status_2']}</span></li>
                        <li class=""><span>{$lang['sign_status_3']}</span></li>
                    </ul>                    
                </div>
                
            </div>
        </div>
    *}
<!-- /#page-content-wrapper -->

<style>
    .book-info.projects .title{
        margin-bottom: 1rem;
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
        position: relative;
        transition: 1s all;
    }
    .project-images ul li{
        margin:0;
        padding:0;
        width: 100%;
        float:left;                
        height: 38rem;
    }
    .project-images .images {
        /*background-image: url(/assets/images/grande/projects/hyde.jpg);*/
        background-size: cover;
        width: 100%;
        height: calc(100% - 13rem);
        background-position: center center;
    }
    .project-images .desc {
        height:13rem;
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
        top: calc(100% - 12rem);
        width: 100%;
        height: 3rem;
    }
    .project-thumbs {
        /*position: absolute;
        top: 0;
        width: 8rem;*/
        height: 3rem;
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
        width: 8rem;
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
        margin-right: 1.5rem;
    }
    
    
    .btn-slide {
        /*display:none;*/
        position: absolute;
        top:0;
        height: 80%;
        width: 4rem;
        cursor: pointer;
    }
    .btn-slide.back{
        left: 0%;
    }
    .btn-slide.next{
        right:0;
    }
    .btn-slide:before {
        position: relative;
        top: 50%;
        /*transform: translateY(-50%); */
        width: 100%;
        color:#FFF;
        font-size: 2.25rem;
    }
    .book-info.projects .btn-grande {
        display: none;
    }
    .book-info.projects .project-images .btn-grande {
        display: block;
        margin-top: 2rem;
    }

    @media screen and (min-width: 768px) {
        
        .project-images ul li{
            height:100%;
        }                    
        .book-info.projects .title{            
            margin-top: 0rem;
        }
        .project {
            width: 65%;
        }
        .project-images {
            width: calc(100% - 8rem);
            margin: 0 4rem;
            height: 25rem;
        }
        .btn-slide {
            height:100%
        }
        .btn-slide.back{
            left: 20%;
        }
        .thumbs-box {
            display:block;
        }
        .project-images .images {        
            height: calc(100% - 10rem);
        }
        .project-images .desc {
            height:10rem;
            padding-top: 6rem;
        }
        .project-thumbs ul {                    
            
        }
        
        .book-info.projects .btn-grande {
            display: inline;
        }
        .book-info.projects .project-images .btn-grande {
            display: none;            
        }
        .section {
            visibility: hidden;
        }
        .section#section0{
            visibility: visible;
        }
    }
    
</style>

<script>
    {literal}
    
    var myFullpage = new fullpage('#fullpage', {
        sectionsColor: ['#000000', '#EAE3D1', '#7BAABE', '#EAE3D1', '#ccddff','#eeaa77'],
        anchors: [resource.project_home_url, resource.project_detail_url, resource.project_facility_url, resource.project_roomtype_url, resource.project_gallery_url, resource.project_360_url],
        navigation:true,
        navigationTooltips: [resource.project_home, resource.project_detail, resource.project_facility, resource.project_roomtype, resource.project_gallery, resource.project_360],
        showActiveTooltip: true,
        menu: '#menu',
        onLeave: function(index, nextIndex, direction){ 
            //console.log(index);
            //console.log(nextIndex);
            $(".section").css("visibility","visible");
            if (nextIndex.index > 0) {
                $(".navbar").css('display','none');
            } else {
                $(".navbar").css('display','block');
            }
        }
    });
    

    $(document).ready(function() {
        
        $(".btn-slide.next").click(function(){
            
            //console.log("next");
            
            $elm = $(this).parent();
                                    
            $item = parseInt($elm.data("item"));                        
            $totalItems = parseInt($elm.find("ul li").length);
            
            //console.log($item);
            //console.log($totalItems);
                        
            if ($item < $totalItems) {
                
                $item = $item+1;  
                
                $elm.data("item",$item);
                $pos = ($item-1) * -100;
                $elm.find("ul").css('left',$pos+'%');                 
                $elm.find("ul li").removeClass('active');
                $elm.find("li").eq($item-1).addClass('active');
            }
            if ($item == $totalItems) {
                $(this).hide();
            } 
            if ($item != 1) {                
                $(this).parent().find(".btn-slide.back").show();
            }
            
        })
        $(".btn-slide.back").click(function(){
                   
            //console.log("back");
            
            $elm = $(this).parent();
            
            $item = parseInt($elm.data("item"));                        
            $totalItems = parseInt($elm.find("ul li").length);
            
            //console.log($item);
            //console.log($totalItems);
                        
            if ($item > 1) {
                
                $item = $item-1; 
                
                $elm.data("item",$item);
                $pos = ($item-1) * -100;
                $elm.find("ul").css('left',$pos+'%');                 
                $elm.find("ul li").removeClass('active');
                $elm.find("ul li").eq($item-1).addClass('active');
            }
            if ($item == 1) {
                $(this).hide();
            }
            if ($item != $totalItems) {               
               $(this).parent().find(".btn-slide.next").show();
            }            
        })
        
        
        $('.project-slide').bind('touchstart',function(e){
		 // e.preventDefault();
		  var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
		  var elm = $(this).offset();
		  window.lastX = touch.pageX - elm.left; 	
		  window.currentpos = $(this).find('ul').position().left;		
		  window.minLeft = ($(this).find('ul').outerWidth(true) - $(this).outerWidth(true)) * -1;	
	});	
	
	$('.project-slide').bind('touchmove',function(e){
		 //e.preventDefault();
		  var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
		  var elm = $(this).offset();
		  var  currentX = touch.pageX - elm.left; 		 
		  var move = Math.round(currentX-window.lastX);
		  
		  //if (Math.abs(move) < 20) { return; }
			  
                if (currentX > window.lastX) {
                        console.log('right');
                        pix = ( window.currentpos + (move * 5))  ;
                        if (pix > 0) {pix = 0;}
                        if (pix < window.minLeft) {pix = window.minLeft;}
                        $(this).find('ul').css('left', pix );	
                } else {
                        console.log('left');
                        pix = ( window.currentpos +  (move * 5)) ;	
                        if (pix > 0) {pix = 0;}	
                        if (pix < window.minLeft) {pix = window.minLeft;}
                        $(this).find('ul').css('left', pix );	
                }	  
	}); 
        $('.project-slide').bind('touchend',function(e){
		
	});
        
        
        $(".btn-booking").click(function(){
            {/literal}
            window.location = "{$base_url}"+"/booking/"+"{$project.project_name_url}"+"/"+"{$project.project_id}";
            {literal}
        });
        
        $(".btn-back").click(function(){            
            window.location = "{/literal}{$base_url}{literal}"+"/projects/";
        });


    });
    
    
    
    
    {/literal}
</script>
{include file="footer_grande.tpl"}
