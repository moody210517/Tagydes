<!DOCTYPE html>
<!--[if IE 8]> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>@yield('page-title') - {{ settings('app_name') }}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>        
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/animate/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />

    <!-- <link href="/assets/css/{{ auth()->user()->template }}/style.min.css" rel="stylesheet" />
    <link href="/assets/css/{{ auth()->user()->template }}/style-responsive.min.css" rel="stylesheet" />
    <link href="/assets/css/{{ auth()->user()->template }}/theme/default.css" rel="stylesheet" id="theme" /> -->
    
    <link href="/tagydes/assets/css/{{ auth()->user()->template }}/style.min.css" rel="stylesheet" />
    <link href="/tagydes/assets/css/{{ auth()->user()->template }}/style-responsive.min.css" rel="stylesheet" />
    <link href="/tagydes/assets/css/{{ auth()->user()->template }}/theme/default.css" rel="stylesheet" id="theme" />
    
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="{{ asset('assets/plugins/jquery-jvectormap/jquery-jvectormap.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL STYLE ================== -->
    
    @yield('styles')

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>
    <!-- ================== END BASE JS ================== -->
    @yield('header-scripts')
</head>
<body>
    <!-- begin page-cover -->
    <div class="page-cover"></div>
    <!-- end page-cover -->
    
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade show"><span class="spinner"></span></div>
    <!-- end #page-loader -->
    
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">

        @include('partials.navbar')
        
        @include('partials.sidebar')
        
        <!-- begin #content -->
        <div id="content" class="content">
            <!-- begin breadcrumb -->
            @yield('breadcrumbs')
            
            <!-- end breadcrumb -->
            @yield('content')
        </div>
        <!-- end #content -->
        
        <!-- begin theme-panel -->
        <div class="theme-panel theme-panel-lg">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <h5 class="m-t-0">Color Theme</h5>
                <ul class="theme-list clearfix">
                    <li><a href="javascript:;" class="bg-red" data-theme="red" data-theme-file="{{ asset('assets/css/transparent/theme/red.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Red">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-pink" data-theme="pink" data-theme-file="{{ asset('assets/css/transparent/theme/pink.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Pink">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-orange" data-theme="orange" data-theme-file="{{ asset('assets/css/transparent/theme/orange.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Orange">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-yellow" data-theme="yellow" data-theme-file="{{ asset('assets/css/transparent/theme/yellow.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Yellow">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-lime" data-theme="lime" data-theme-file="{{ asset('assets/css/transparent/theme/lime.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Lime">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-green" data-theme="green" data-theme-file="{{ asset('assets/css/transparent/theme/green.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Green">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-teal" data-theme="teal" data-theme-file="{{ asset('assets/css/transparent/theme/teal.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Teal">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-aqua" data-theme="aqua" data-theme-file="{{ asset('assets/css/transparent/theme/aqua.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Aqua">&nbsp;</a></li>
                    <li class="active"><a href="javascript:;" class="bg-blue" data-theme="default" data-theme-file="{{ asset('assets/css/transparent/theme/default.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Default">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-purple" data-theme="purple" data-theme-file="{{ asset('assets/css/transparent/theme/purple.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Purple">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-indigo" data-theme="indigo" data-theme-file="{{ asset('assets/css/transparent/theme/indigo.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Indigo">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-black" data-theme="black" data-theme-file="{{ asset('assets/css/transparent/theme/black.css') }}" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Black">&nbsp;</a></li>
                </ul>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Header</div>
                    <div class="col-md-6">
                        <select name="header-fixed" class="form-control form-control-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Sidebar Styling</div>
                    <div class="col-md-6">
                        <select name="sidebar-styling" class="form-control form-control-sm">
                            <option value="1">default</option>
                            <option value="2">grid</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Sidebar</div>
                    <div class="col-md-6">
                        <select name="sidebar-fixed" class="form-control form-control-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Sidebar Gradient</div>
                    <div class="col-md-6">
                        <select name="content-gradient" class="form-control form-control-sm">
                            <option value="1">disabled</option>
                            <option value="2">enabled</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Direction</div>
                    <div class="col-md-6">
                        <select name="direction" class="form-control form-control-sm">
                            <option value="1">LTR</option>
                            <option value="2">RTL</option>
                        </select>
                    </div>
                </div>
                <div class="divider"></div>
                <h5>THEME VERSION</h5>
                <div class="theme-version">
                    <a href="/template_html/index_v2.html">
                        <span style="background-image: url(asset('assets/img/theme/default.jpg'));"></span>
                    </a>
                    <a href="/template_transparent/index_v2.html" class="active">
                        <span style="background-image: url(asset('assets/img/theme/transparent.jpg'));"></span>
                    </a>
                </div>
                <div class="theme-version">
                    <a href="/template_apple/index_v2.html">
                        <span style="background-image: url(asset('assets/img/theme/apple.jpg'));"></span>
                    </a>
                    <a href="/template_material/index_v2.html">
                        <span style="background-image: url(asset('assets/img/theme/material.jpg'));"></span>
                    </a>
                </div>
                <div class="theme-version">
                    <a href="/template_facebook/index_v2.html">
                        <span style="background-image: url(asset('assets/img/theme/facebook.jpg'));"></span>
                    </a>
                </div>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <a href="javascript:;" class="btn btn-inverse btn-block btn-rounded" data-click="reset-local-storage"><b>Reset Local Storage</b></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end theme-panel -->

        
        <!-- begin scroll to top btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <!-- end scroll to top btn -->
    </div>
    <!-- end page container -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('assets/plugins/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!--[if lt IE 9]>
    <script src="{{ asset('assets/crossbrowserjs/html5shiv.js') }}"></script>
    <script src="{{ asset('assets/crossbrowserjs/respond.min.js') }}"></script>
    <script src="{{ asset('assets/crossbrowserjs/excanvas.min.js') }}"></script>
<![endif]-->
<script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/plugins/js-cookie/js.cookie.js') }}"></script>
<script src="{{ asset('assets/js/theme/transparent.min.js') }}"></script>
<script src="{{ asset('assets/js/apps.min.js') }}"></script>
<script src="{{ url('assets/js/as/app.js') }}"></script>
<script src="{{ asset('assets/plugins/gritter/js/jquery.gritter.js') }}"></script>

<!-- ================== END BASE JS ================== -->

@yield('scripts')
<script>
    $(document).ready(function() {
        COLOR_BLACK_TRANSPARENT_2 = 'rgba(255,255,255,0.15)';
        COLOR_WHITE = '#333';
        App.init();
        handleMessagesGritterNotification();
    });

    $("#div-message-alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#div-message-alert").slideUp(500);
    });
</script>
</body>
</html>
