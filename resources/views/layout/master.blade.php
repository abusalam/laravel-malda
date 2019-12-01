<?php if(session()->has('user_code')==false){ ?>
<script>
    window.location = "{{route('login')}}";
</script>
<?php } else{ 

        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src *; script-src 'self' 'unsafe-inline' 'unsafe-eval';  frame-src 'self' 'unsafe-inline' 'unsafe-eval'; object-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; connect-src 'self' 'unsafe-inline' 'unsafe-eval'; font-src 'self' 'unsafe-inline' 'unsafe-eval';");
        //header("X-XSS-Protection 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: SAMEORIGIN");
        header("Set-Cookie: name=value; httpOnly");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");


       
        



            
            $now = time(); // checking the time now when home page starts  
            if(session()->has('expire')==1){
                if($now > session()->get('expire')){
                   Session::flush();
                }else{
                   session(['expire' => $now + (60 * 15)]); 
                }
            }else{
                session(['expire' => $now + (60 * 15)]);
            }
            if (session()->has('user_code') != 1) { ?>

            <script type="text/javascript">
               window.location = "./session";
           </script>
               
           <?php } ?>
        
             
<!DOCTYPE html>
<html lang="en-US">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Malda District</title>
        <meta name="description" content="Malda District" />
        <link rel="shortcut icon" type="text/css" href="./front/images/favicon.ico" />
        <link href="./front/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="./front/css/bootstrap.min.css">
        <link rel='stylesheet' href='./front/css/sliderhelper.css' media='all' />
        <link rel="stylesheet" type="text/css" href="front/css/menumaker.css">
        <link rel='stylesheet' href='./front/css/base.css' media='all' />
        <link rel='stylesheet' href='./front/css/extra.css' media='all' />
        <link rel='stylesheet' href='./front/css/flexslider.min.css' media='all' />
        <link rel='stylesheet' href='./front/css/custom-flexslider.css' media='all' />
        <link rel='stylesheet' href='./front/css/footer-logo-carousel.css' media='all' />
        <link rel="stylesheet" href="{{ asset('/css/bootstrapValidator.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/jquery-confirm.min.css') }}">
        

        <link rel="stylesheet" href="{{ asset('/lib/fontawesome-free/css/fontawesome.min.css') }}">
        

        <link rel='stylesheet' href='./front/css/design.css' media='all' />
        <link href="{{ asset('/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" integrity="sha256-bLNUHzSMEvxBhoysBE7EXYlIrmo7+n7F4oJra1IgOaM=" crossorigin="anonymous" />

    </head>
    <body>
        <div class="main-body">
            <a href="#" title="sroll" class="scrollToTop" style="display: inline;"><i class="fa fa-angle-up"></i></a>
            <header>
                <section id="topBar1" class="wrapper">
                    <div class="container">
                        <div class="push-right" role="navigation" aria-label="Primary">
                            <div id="accessibility" >
                                <ul id="accessibilityMenu">
                                    <li><a href="#SkipContent" class="skip-to-content" title="Skip to main content"><span class="icon-skip-to-main responsive-show"></span><strong class="responsive-hide">SKIP TO MAIN CONTENT</strong></a></li>
                                    <li><a href="javascript:void(0);" title="Site Search"><span class="icon-search" aria-hidden="true"></span></a>
                                        <div class="goiSearch" role="search">
                                            <form onsubmit="" action="" method="get">
                                                <label for="search" class="hide">Search</label>
                                                <input type="search" title="Enter Text" name="s" id="search" value="" />
                                                <button type="submit" title="Search"><small class="tcon">Search</small><small class="tcon">Search</small><span class="icon-search" aria-hidden="true"></span></button>
                                            </form>
                                        </div>
                                    </li>
                                    <li><a href="javascript:void(0);" title="Social Media Links" class="show-social-links">
                                            <span class="icon-social-link" aria-hidden="true">
                                                <span class="icon-facebook"></span>
                                                <span class="icon-twitter"></span>
                                                <span class="icon-youtube"></span>
                                            </span>
                                            <span class="off-css">Social Media Links</span>
                                        </a>
                                        <div class="socialIcons" role="complementary">
                                            <ul>
                                                <li class="ico-social">
                                                    <ul>
                                                        <li><a href="#" target="_blank" aria-label="Facebook | External site that opens in a new window"><img src="./front/images/icon/ico-facebook.png" title="Facebook | External site that opens in a new window" alt="Facebook | External site that opens in a new window"></a></li>
                                                        <li><a href="#" target="_blank" aria-label="Twitter | External site that opens in a new window"><img src="./front/images/icon/ico-twitter.png" title="Twitter | External site that opens in a new window" alt="Twitter | External site that opens in a new window"></a></li>
                                                        <li><a href="#" target="_blank" aria-label="Youtube | External site that opens in a new window"><img src="./front/images/icon/ico-youtube.png" title="Youtube | External site that opens in a new window" alt="Youtube | External site that opens in a new window"></a></li>
                                                    </ul>
                                                </li>

                                            </ul>
                                        </div>
                                    </li>
                                    <li><a href="" title="Sitemap"><span class="off-css">Site Map</span>
                                            <span class="icon-sitemap" aria-hidden="true"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" title="Accessibility Links" aria-label="Accessibility Links" class="mobile-show accessible-icon"><span class="off-css">Accessibility Links</span><span class="icon-accessibility" aria-hidden="true"></span></a>
                                        <div class="accessiblelinks textSizing">
                                            <ul>
                                                <li><a href="javascript:void(0);" aria-label="Font Size Increase" title="Font Size Increase"><span aria-hidden="true">A+</span><span class="off-css"> Font Size Increase</span></a></li>
                                                <li><a href="javascript:void(0);" aria-label="Normal Font" title="Normal Font"><span aria-hidden="true">A</span><span class="off-css"> Normal Font</span></a></li>
                                                <li><a href="javascript:void(0);" aria-label="Font Size Decrease" title="Font Size Decrease"><span aria-hidden="true">A-</span><span class="off-css"> Font Size Decrease</span></a></li>
                                                <li class="highContrast dark tog-con">
                                                    <a href="javascript:void(0);" aria-label="High Contrast" title="High Contrast"><span aria-hidden="true">A</span> <span class="tcon">High Contrast</span></a>
                                                </li>
                                                <li class="highContrast light">
                                                    <a href="javascript:void(0);" aria-hidden="true" aria-label="Normal Contrast" title="Normal Contrast"><span aria-hidden="true">A</span> <span class="tcon">Normal Contrast</span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="change-language" aria-label="English" title="English">English</a>
                                        <div class="socialIcons select-lang">
                                            <ul>
                                                <li class="lang-item lang-item-2 lang-item-en lang-item-first current-lang">
                                                    <a lang="en-US" hreflang="en-US" href="/">English</a></li>
                                                <li class="lang-item lang-item-1300 lang-item-bn"><a lang="bn-BD" hreflang="bn-BD" href="">বাংলা</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="push-left">
                            <div class="govBranding">
                                <ul>
                                    <li><a lang="grt" href="" >Government of West Bengal</a></li>
                                    <li><a href="#">Malda District</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="wrapper header-wrapper">
                    <div class="container header-container">
                        <div class="logo">
                            <a href="./" aria-label="Go to home" class="emblem" rel="home">
                                <img class="site_logo" height="100" id="logo" src="./front/images/malda_emblem_logo.png" alt="State Emblem of India" >
                                <div class="logo-text">
                                    <strong lang="grt" class="site_name_regional">মালদা জেলা</strong>
                                    <h1 class="site_name_english">Malda District</h1>
                                </div>
                            </a>
                        </div>
                        <div class="header-right clearfix">
                            <div class="right-content clearfix">
                                <div class="float-element">
                                    <a aria-label="Digital India - External site that opens in a new window" href="http://digitalindia.gov.in/" target= "_blank" title="Digital India">
                                        <img class="sw-logo" height="95" src="./front/images/tourishm_logo.png" alt="Digital India">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="header" id="header">
                    <div class="container">
                    <div id="cssmenu">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="{{route('grievance')}}">Grievance</a></li>
                            <li><a href="{{route('search_case')}}">Case Search</a></li>
                            <li><a href="{{route('todays_hearing')}}">Today's Hearing</a></li>
                            <?php if (session()->has('user_code') == false) { ?>
                            <li><a id="loginn" href="{{route('login')}}">Login</a></li>
                            <?php }else{?>

                            <li>
                                <a href="#">Admin</a>
                                <ul>
                                     <?php if (session()->get('user_type') == 0) { ?>
                                    <li><a href="#">User</a>
                                        <ul>
                                            <li><a href="{{route('userRegisration')}}">User Create</a></li>
                                            <li><a href="{{route('userList')}}">User List</a></li>
                                            
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">Case</a>
                                        <ul>
                                            <li><a href="{{route('case_entry')}}">Case Entry</a></li>
                                            <li><a href="{{route('case_list')}}">Case List</a></li>
                                            
                                        </ul>
                                    </li>
                                    <?php } ?> 
                                    <li><a href="#">Grievance</a>
                                        <ul>
                                        <li><a href="{{route('grievance_list')}}">Grievance List</a></li>
                                        <?php if (session()->get('user_type') == 0) { ?>
                                        <li><a href="{{route('forworded_grievance_list')}}">Forwarded Grievance List</a></li>
                                        <?php } ?>
                                        </ul>

                                    </li>
                                    <li><a href="{{route('logout')}}">Logout</a></li>
                                </ul>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                </div>
            </header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 p-0 top-banner">
                        <img src="./front/images/innerBanner.jpg"  alt="">
                    </div>
                    <div class="col-sm-12">
                        <div class="wrapper bodyWrapper " role="main">
                            <div class="container ">
                                <div class="row breadcrumb-outer">
                                    <div class="col-sm-8">
                                        <div class="left-content pull-left">
                                            <div id="breadcam" role="navigation" aria-label="breadcrumb">
                                                <ul class="breadcrumbs">
                                                    <li><a href="./" class="home"><span>Home</span></a></li>
                                                    <!--                                                    <li><a href="">Directory</a></li>
                                                                                                        <li class="current">Grievance</li>-->
                                                </ul>               
                                            </div>
                                        </div>
                                    </div><div class="col-sm-4">
                                        <div class="right-content pull-right">
                                            <div class="printShare">
                                                <ul class="">
                                                    <li>
                                                        <a href="#" id="print" title="Print" aria-label="Print Page Content">
                                                            <span class="icon-printer"></span><span class="off-css">Print</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <span class="share-text"><em class="icon-share"></em>
                                                            <span class="off-css">Share</span>                                                    
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <a href=""  target="_blank" title="Share on Facebook" aria-label="Facebook that opens in a new window">
                                                            <span class="icon-facebook"></span><span class="off-css">Facebook</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href=""  target="_blank" title="Share on Twitter" aria-label="Twitter that opens in a new window">
                                                            <span class="icon-twitter"></span><span class="off-css">Twitter</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="SkipContent" tabindex="-1"></div>
                                
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <img src="front/images/footer_top_bg.gif" alt="Biswa Bangla" width="100%">
            <div class="container-fluid footer-slider">
                <div class="row">
                    <div class="col-sm-12 p-0">
                        <div id="footerScrollbar" class="footer-slider nav-white flexslider main-banner-three">
                            <ul class="slides" aria-label="Important Sites">
                                <li>
                                    <a href="https://incredibleindia.org/" target="_blank" title="Digital India">
                                        <img src="./front/images/footer_slider/3.png" alt="Digital India"/>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.india.gov.in/" target="_blank" title="National Portal of India">
                                        <img src="./front/images/footer_slider/4.png" alt="india.gov.in"/>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.digitalindia.gov.in/" target="_blank" title="Kanyashree Prakalpa">
                                        <img src="./front/images/footer_slider/5.png" alt="digital-india"/>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.digitalindia.gov.in/" target="_blank" title="Mission Nirmal Bangla">
                                        <img src="./front/images/footer_slider/6.png" alt="digital-india"/>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.digitalindia.gov.in/" target="_blank" title="Swacch Bharat">
                                        <img src="./front/images/footer_slider/7.png" alt="digital-india"/>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <footer id="footer2" class="footer-home pt-2" style="background:black">
                <div class="container-fluid">
                    <div class="row text-white p-3">
                        <div class="col-sm-4 text-sm-left text-center">
                            <span class="version">Version: <strong>1.0.0</strong></span>
                        </div>
                        <div class="col-sm-4 text-sm-center text-center">
                            <span class="last_update">Last Updated: <strong>Sep 06, 2019</strong></span>
                        </div>
                        <div class="col-sm-4 text-sm-right text-center">
                            <span class="visitor_count">Visitor Count <strong>1000</strong></span>
                        </div>
                    </div>
                    <ul id="footer-menu" class="menu-list pt-3">
                        <li class="menu-list-item"><a href="/website-policies/">Website Policies</a></li>
                        <li class="menu-list-item"><a href="/help/">Help</a></li>
                        <li class="menu-list-item"><a href="/contact-us/">Contact Us</a></li>
                        <li class="menu-list-item"><a href="/feedback/">Feedback</a></li>
                    </ul>
                    <div class="row text-white p-3">

                        <div class="col-sm-2 offset-2">
                            <a href="http://www.nic.in/"><img src="./front/images/icon/nicLogo.png" alt="National Informatics Centre"></a>
                        </div>
                        <div class="col-sm-4 text-center footer-content">
                            <div>Content Owned by District Administration</div>
                            <p class="text-warning"> Developed and hosted by
                                <a href="http://www.nic.in/" class=" text-light" target="_blank">National Informatics Centre</a>
                            </p>
                        </div>
                        <div class="col-sm-2">
                            <a href="http://www.digitalindia.gov.in/">
                                <img src="./front/images/icon/digitalIndia.png" alt="Digital India">
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <script src="{{ asset('/lib/jquery/jquery.min.js') }}"></script>
        
        <script src="./front/js/menumaker.js"></script>
        <script src="./front/js/bootstrap.min.js" type="text/javascript"></script>
        <script src='./front/js/themescript.js'></script>
        <script src='./front/js/jquery.flexslider.js'></script>
        <script src='./front/js/jquery.flexslider-min.js'></script>
        <script src="{{ asset('/js/bootstrapValidator.min.js') }}"></script>
        <script src="{{ asset('/js/jquery-confirm.min.js') }}"></script>
        <script src="{{asset('lib/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('lib/datatables.net-dt/js/dataTables.dataTables.min.js')}}"></script>
        <script src="{{asset('lib/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js')}}"></script>
        <script src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
        <script src="{{asset('/lib/fontawesome-free/js/fontawesome.min.js')}}"></script>
        @yield('script')
    </body>
</html>

           <?php } ?>