<!doctype html>
<html lang="en">
    <head>
        {ci_config name="base_url"}

<title>{$title}</title>

{if !empty($description)}
    <meta name="description" content="{$description}"/>
{/if}

{if !empty($keywords)}
    <meta name="keywords" content="{$keywords}">
{/if}

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="{$author}">
<meta property="og:title" content="{$title}">
<meta property="og:description" content="{if empty($og_description)}{$description}{else}{$og_description}{/if}">
<meta property="og:url" content="{$og_url}">
<meta property="og:image" content="{$og_image}">
<meta property="og:site_name" content="{$og_site_name}">

<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">

<!-- favicon -->
<link rel="apple-touch-icon" sizes="76x76" href="{$base_url}/assets/images/favicon/grande/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="{$base_url}/assets/images/favicon/grande/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="{$base_url}/assets/images/favicon/grande/favicon-16x16.png">
<link rel="manifest" href="{$base_url}/assets/images/{$current_menu}/favicon/site.webmanifest">
<!-- link rel="mask-icon" href="{$base_url}/assets/images/{$current_menu}/favicon/safari-pinned-tab.svg" color="#5bbad5" -->
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<!-- Bootstrap core CSS
<link href="{$base_url}/assets/css/bootstrap.min.css?v1" rel="stylesheet">
-->

<link rel="stylesheet" href="{$base_url}/assets/css/bootstrap.min.css">

<link href="{$base_url}/assets/css/plugins.css" rel="stylesheet">
<link href="{$base_url}/assets/css/style_grande.css?v=13" rel="stylesheet">
<link href="{$base_url}/assets/css/blue.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="{$base_url}/assets/css/icons.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
    current_menu = '{$current_menu}';
</script>

<script src="{$base_url}/assets/js/jquery.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>-->
<script src="{$base_url}/assets/js/bootstrap.min.js" ></script>

{*<script src="{$base_url}/assets/js/plugins.js"></script>
<script src="{$base_url}/assets/js/classie.js"></script>
<script src="{$base_url}/assets/js/jquery.themepunch.tools.min.js"></script>*}
{*<script src="{$base_url}/assets/js/scripts_grande.js?v=12"></script>*}
{*<script src="{$base_url}/assets/js/masonry.pkgd.min.js" type="text/javascript"></script>*}
{*<script src="{$base_url}/assets/js/bootstrap4-toolkit.min.js" type="text/javascript"></script>*}
{*<script src="{$base_url}/assets/js/jquery.themepunch.plugins.min.js" type="text/javascript"></script>*}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>

{*<link rel="stylesheet" type="text/css" href="{$base_url}/assets/js/slick/slick.css">
<link rel="stylesheet" type="text/css" href="{$base_url}/assets/js/slick/slick-theme.css">
<script src="{$base_url}/assets/js/slick/slick.js" type="text/javascript" charset="utf-8"></script>*}

<script src="{$base_url}/assets/js/jquery.inputmask.bundle.js" type="text/javascript" charset="utf-8"></script>
<script src="{$base_url}/resource" type="text/javascript" charset="utf-8"></script>

</head>
<body class="grande-agents">
  
<div id="fb-root"></div>

    <div id="wrapper" class="{$current_menu}">
        <div class="overlay"></div>
        
        <div class="modal_loading">
            <div class="textload">Loading</div><div id="status"><div class="spinner"></div></div>
        </div>
        <nav class="navbar fixed-top ">
            <div class="page-content-wrapper">                
                <button type="button" class="hamburger is-closed mnu-{$language}" data-toggle="offcanvas">
                    <span class="hamb-title">{$lang['menu']}</span>
                    <span class="hamb-top"></span>
                    <span class="hamb-middle"></span>
                    <span class="hamb-bottom"></span>                    
                </button>
            </div>
        </nav>
        <nav class="" id="sidebar-wrapper" role="navigation">
            <a href="#" onclick="close_menu()"><span class="icon-close"></span></a>
            
            <ul class="nav sidebar-nav">  
                {if ($menu_login == '') }
                    {if ($active_page=='history') }
                        <li class="dropdown"><a href="{$base_url}/agents/{$agent_id_code}/reservation" >Reservation</a></li>
                        <li class="dropdown"><a class="active">History</a></li> 
                        {else}
                        <li class="dropdown"><a class="active">Reservation</a></li>
                        <li class="dropdown"><a href="{$base_url}/agents/{$agent_id_code}/history">History</a></li> 
                    {/if}
                    <li class="dropdown"><a href='#' class='agentSignout'>LogOut</a></li>   
                {else} 
                    <li class="dropdown"><a href='{$base_url}/agents/{$agent_id_code}' class='agentSignin'>LogIn</a></li>   
                {/if}
            </ul>
        </nav>
        
            
        
