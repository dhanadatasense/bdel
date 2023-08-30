<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <title>Login Page - Bdel Admin Panel</title>
    <link id="favicon" rel="shortcut icon" href="<?php echo BASE_URL; ?>app-assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/vendors/css/forms/icheck/custom.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/css/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app-assets/css/pages/login-register.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/css/style.css">

    <link href="<?php echo BASE_URL; ?>app-assets/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>app-assets/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons -->
    <link href="<?php echo BASE_URL; ?>app-assets/fonts/meteocons/style.min.css" rel="stylesheet" type="text/css">
    <!-- <link href="<?php echo BASE_URL; ?>app-assets/fonts/line-awesome/css/line-awesome.min.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <link href="<?php echo BASE_URL; ?>app-assets/fonts/feather/style.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo BASE_URL; ?>app-assets/fonts/simple-line-icons/style.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .field-icon {
            float: right;
            margin-top: -40px;
            position: relative;
            z-index: 2;
        }
    </style>
</head>

<body class="vertical-layout vertical-menu 1-column   blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <input type="hidden" class="geturl" value="<?php echo BASE_URL; ?>">
                <section class="row flexbox-container">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-flex align-items-center justify-content-center">
                        <!-- <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2"><span>Login with Bdel</span>
                        </h6> -->
                        <div class="card border-grey border-lighten-3 m-0 col-md-6 col-lg-6 col-xs-12">
                            <div class="card-header border-0">
                                <div class="card-title text-center">
                                    <div class="p-2">
                                        <img src="<?php echo BASE_URL; ?>app-assets/images/logob.png" alt="branding logo" style="height:74px;">
                                    </div>
                                </div>
                                <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2"><span>Log in to your account </span>
                                </h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="log-form" action="" method="post">
                                        <div class="form-group">
                                            <fieldset class="form-group position-relative has-icon-left mb-0">
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Mail Id" value="<?php echo $log_name; ?>">
                                                <div class="form-control-position">
                                                    <!-- <i class="icon-user"></i> -->
                                                    <i class="las la-user-alt"></i>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="form-group">
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" value="<?php echo $log_pwrd; ?>">
                                                <div class="form-control-position">
                                                    <!-- <i class="icon-key"></i> -->
                                                    <i class="las la-key"></i>
                                                </div>
                                                <div class="form-control-position field-icon">
                                                    <i class="toggle-password la la-eye-slash" toggle="#password"></i>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <button type="button" class="btn btn-primary col-4 mb-4" id="butf2">
                                            <span class="first_btn show"><i class="las la-sign-in-alt"></i> Login</span>

                                            <span class="span_btn hide"><i class="la la-spinner spinner"></i> Loading....</span>
                                        </button>
                                        <div class="form-group position-relative has-icon-left">
                                            <div class="col-sm-6 col-12 text-center text-sm-left pr-0">
                                                <div class="form-checkbox">
                                                    <input type="checkbox" class="form-check-input me-1 check_remember" id="customControlInline" value="<?php echo ($log_rmbr == 2) ? 'true' : 'false'; ?>" <?php echo ($log_rmbr == 2) ? 'checked' : ''; ?>>
                                                    <label class="form-label check_remember" for="customControlInline">Remember me</label>
                                                    <input type="hidden" name="remember_val" class="remember_val" value="<?php echo $log_rmbr; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-6 p-0">
                        <!-- <div class="card-header border-0"> -->
                        <!-- <div class="card-title text-center"> -->
                        <div>

                            <img src="<?php echo BASE_URL; ?>/app-assets/images/init-login.png" alt="branding logo"><br>
                        </div>
                        <!-- </div> -->
                        <!-- </div> -->
                    </div>
                    <!-- <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">

                        </div>
                    </div> -->
                </section>
            </div>
        </div>
    </div>
    <script src="<?php echo BASE_URL; ?>app-assets/vendors/js/vendors.min.js"></script>
    <script src="<?php echo BASE_URL; ?>app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
    <script src="<?php echo BASE_URL; ?>app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <script src="<?php echo BASE_URL; ?>app-assets/js/core/app-menu.min.js"></script>
    <script src="<?php echo BASE_URL; ?>app-assets/js/core/app.min.js"></script>
    <script src="<?php echo BASE_URL; ?>app-assets/js/scripts/forms/form-login-register.min.js"></script>

    <script src="<?php echo BASE_URL; ?>app-assets/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>app-assets/toastr/js/toastr.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>app-assets/toastr/js/toastr-init.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $(".toggle-password").click(function() {
                $(this).toggleClass("la-eye la-eye-slash");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

            var baseurl = $('.geturl').val();

            var _success = function(title, text) {
                toastr.success(text, title, {
                    timeOut: 500000000,
                    closeButton: !0,
                    debug: !1,
                    newestOnTop: !0,
                    progressBar: !0,
                    positionClass: "toast-bottom-right",
                    preventDuplicates: !0,
                    onclick: null,
                    showDuration: "300",
                    hideDuration: "1000",
                    extendedTimeOut: "1000",
                    showEasing: "swing",
                    hideEasing: "linear",
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    tapToDismiss: !1
                })
            }

            var _error = function(title, text) {
                toastr.error(text, title, {
                    positionClass: "toast-bottom-right",
                    timeOut: 5e3,
                    closeButton: !0,
                    debug: !1,
                    newestOnTop: !0,
                    progressBar: !0,
                    preventDuplicates: !0,
                    onclick: null,
                    showDuration: "300",
                    hideDuration: "1000",
                    extendedTimeOut: "1000",
                    showEasing: "swing",
                    hideEasing: "linear",
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    tapToDismiss: !1
                })
            }

            var _LO = function() {
                $("#butf2").attr("disabled", "disabled").button('refresh');
                $('.first_btn').removeClass('show').addClass('hide');
                $('.span_btn').removeClass('hide').addClass('show');
            }

            var _LH = function() {
                $("#butf2").removeAttr("disabled").button('refresh');
                $('.first_btn').removeClass('hide').addClass('show');
                $('.span_btn').removeClass('show').addClass('hide');
            }

            $(document).on('change', '#customControlInline', function() {
                if ($('#customControlInline').is(":checked") == true) {
                    $('.remember_val').empty('').val(2);
                } else {
                    $('.remember_val').empty('').val(1);
                }
            });

            $('#butf2').on('click', function() {
                _LO();

                var datastring = 'method=insert&' + $("#log-form").serialize();
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'index.php/welcome/admin_login',
                    dataType: 'JSON',
                    data: datastring,

                }).done(function(response) {
                    _LH();

                    if (response['status'] == true) {
                        var title = "";
                        var text = response['message'];
                        _success(title, text);

                        window.location = response['url'];
                    } else {
                        var title = "";
                        var text = response['message'];
                        _error(title, text);
                    }
                });
            });
        });
    </script>
</body>

</html>