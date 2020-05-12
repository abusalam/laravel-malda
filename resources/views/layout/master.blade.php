
<?php

        header("X-XSS-Protection 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: SAMEORIGIN");
        header("Set-Cookie: name=value; httpOnly");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        session_set_cookie_params(0, "", "", true, true);
        header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none';");
        header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');



    ?>
    <?php

    use App\Http\Controllers\LogdetailsController;
    $data_visitor_count = LogdetailsController::get_visitor_count();

        ?>

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
    <link rel="stylesheet" href="{{ asset('/lib/fontawesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

    <link rel='stylesheet' href='./front/css/design.css' media='all' />
    <link href="{{ asset('/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet">

</head>

<body>
    <div class="loader">Loading&#8230;</div>
    <div class="main-body">
        <a href="#" title="sroll" class="scrollToTop"><i class="fa fa-angle-up"></i></a>
        <header>
            <section id="topBar1" class="wrapper">
                <div class="container">
                    <div class="push-right" role="navigation" aria-label="Primary">
                        <div id="accessibility">
                            <ul id="accessibilityMenu">
                                <li><a href="#SkipContent" class="skip-to-content" title="Skip to main content"><span class="icon-skip-to-main responsive-show"></span><strong class="responsive-hide">SKIP TO MAIN CONTENT</strong></a></li>



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
                                    <?php if(session()->get('locale') == "bn") {?>
                                    <a href="{{route('change_language',['en'])}}" class="change-language" aria-label="English" title="English">English</a>
                                    <?php } else { ?>
                                    <a href="{{route('change_language',['bn'])}}" class="change-language" aria-label="English" title="English">বাংলা</a>
                                    <?php }?>

                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="push-left">
                        <div class="govBranding">
                            <ul>
                                <li><a lang="grt" href="">Government of West Bengal</a></li>
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
                            <img class="site_logo" height="100" id="logo" src="./front/images/malda_emblem_logo.png" alt="State Emblem of India">
                            <div class="logo-text">
                                <strong lang="grt" class="site_name_regional">মালদা জেলা</strong>
                                <h1 class="site_name_english">Malda District</h1>
                            </div>
                        </a>
                    </div>
                    <div class="header-right clearfix">
                        <div class="right-content clearfix">
                            <div class="float-element">
                                <a  rel="noopener noreferrer" aria-label="Digital India - External site that opens in a new window" href="http://digitalindia.gov.in/" target="_blank" title="Digital India">
                                    <img class="sw-logo" height="95" src="./front/images/tourishm_logo.png" alt="Digital India">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="header" id="header">
                {!! Form::open(['url' => '', 'name' => 'logout', 'class' =>'', 'id' => 'logout', 'method' => 'post','role'=>'','enctype'=>'multipart/form-data']) !!}
                 {!! Form::close() !!}
                <div class="container">
                    <div id="cssmenu">
                        <ul>

                            <li><a href="/">{{__('text.home')}}</a></li>

                            <li><a href="grievance">{{__('text.grievance')}}</a></li>
                            <li><a href="grievance_status">{{__('text.grievance_status')}}</a></li>
                            <li><a href="search_case">{{__('text.case_search')}}</a></li>
                            <li><a href="todays_hearing">{{__('text.todays_hearing')}}</a></li>
                            <?php if (session()->has('user_code') == false) { ?>
                            <li class="pull-right"><a id="loginn" href="login">{{__('text.login')}}</a></li>
                            <?php }else{?>
                            <li class="pull-right">
                                <a href="#">
                                    <?php echo session()->get('user_name') ?>(
                                    <?php if(session()->get('user_type')==0) {
                                        echo " Admin ";
                                    }else{echo " User ";
}  ?>)</a>
                                <ul>
                                    <li><a href="index">{{__('text.dashboard')}}</a></li>
                                    <?php if (session()->get('user_type') == 0) { ?>
                                    <li><a href="#">{{__('text.user')}}</a>
                                        <ul>
                                            <li><a href="userRegisration">{{__('text.user_create')}}</a></li>
                                            <li><a href="userList">{{__('text.user_list')}}</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">{{__('text.case')}}</a>
                                        <ul>
                                            <li><a href="case_entry">{{__('text.case_entry')}}</a></li>
                                            <li><a href="case_list">{{__('text.case_list')}}</a></li>
                                        </ul>
                                    </li>
                                    <?php } ?>
                                    <li><a href="#">{{__('text.grievance')}}</a>
                                        <ul>
                                            <li><a href="grievance_list">{{__('text.recieved')}}</a></li>
                                            <li><a href="forwarded_grievance_list">{{__('text.forwarded')}}</a></li>
                                            <li><a href="resolve_grievance_list">{{__('text.resolved')}}</a></li>
                                            <li><a href="close_grievance_list">{{__('text.closed')}}</a></li>
                                        </ul>
                                    </li>
                                    <?php if (session()->get('user_type') == 0) { ?>
                                    <li><a href="#">{{__('text.report')}}</a>
                                        <ul>
                                            <li><a href="pending_report">{{__('text.pending_grievance_report')}}</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="log_details">{{__('text.user_log_details')}}</a></li>
                                    <?php }?>
                                    <li id="logout_user"><a>{{__('text.logout')}}</a></li>
                                </ul>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div id="SkipContent" tabindex="-1"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 p-0 top-banner">
                    <img src="./front/images/innerBanner.jpg" alt="">
                </div>
                <div class="col-sm-12">
                    <div class="wrapper bodyWrapper " role="main">
                        <div class="container ">
                            <div class="row breadcrumb-outer">
                                <div class="col-sm-8">
                                    <div class="left-content pull-left">
                                        <div id="breadcam" role="navigation" aria-label="breadcrumb">
                                            <ul class="breadcrumbs">
                                                <li><a href="./" class="home"><span>{{__('text.home')}}</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img src="front/images/footer_top_bg.gif" alt="Biswa Bangla" width="100%">

        <footer id="footer2" class="footer-home pt-2">
            <div class="container-fluid">
                <div class="row text-white p-3">
                    <div class="col-sm-4 text-sm-left text-center">
                        <span class="version">Version: <strong><?php echo config('app.version'); ?></strong></span>
                    </div>
                    <div class="col-sm-4 text-sm-center text-center">
                        <span class="last_update">Last Updated: <strong><?php echo str_replace("_", " ", config('app.lastupdate'));?></strong></span>
                    </div>
                    <div class="col-sm-4 text-sm-right text-center">
                        <span class="visitor_count">Visitor Count: <strong><?php echo $data_visitor_count ?></strong></span>
                    </div>
                </div>

                <div class="row text-white p-3">
                    <div class="col-sm-2 offset-2">
                        <a href="http://www.nic.in/"><img src="./front/images/icon/nicLogo.png" alt="National Informatics Centre"></a>
                    </div>
                    <div class="col-sm-4 text-center footer-content">
                        <div>Content Owned by District Administration</div>
                        <p class="text-warning"> Developed and hosted by
                            <a rel="noopener noreferrer" href="http://www.nic.in/" class=" text-light" target="_blank">National Informatics Centre</a>
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
        <input type="hidden" id="language_en_bn" name="language_en_bn" value={{session()->get('locale')}}>
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
    <script src="{{asset('/app/js/master.js')}}"></script>
    @yield('script')

</body>

</html>

