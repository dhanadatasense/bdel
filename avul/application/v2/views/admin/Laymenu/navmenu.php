        <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
            <div class="navbar-wrapper">
                <div class="navbar-header">
                    <ul class="nav navbar-nav flex-row text-center">
                        <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="fa fa-bars font-large-1"></i></a></li>
                        <li class="nav-item">
                            <a class="navbar-brand" href="#">
                                <img class="brand-logo rounded mx-auto d-block center-block" alt="modern admin logo" style="margin-top: -20px;" src="https://www.datasense.in/images/datasense.png" />
                            </a>
                        </li>
                        <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
                    </ul>
                </div>
                <div class="navbar-container content">
                    <div class="collapse navbar-collapse" id="navbar-mobile">
                        <ul class="nav navbar-nav mr-auto float-left">
                            <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                            <li class="header-academic" style="margin-top: 13px;">
                                <select name="active_academic_id" id="active_academic_id" class="form-control active_academic_id js-select1-multi" data-te="1">
                                    <?php
                                    $where_1 = array(
                                        'method'    => '_listFinancial'
                                    );

                                    $financial_list  = avul_call(API_URL . 'master/api/financial', $where_1);
                                    $financial_result = $financial_list['data'];

                                    if (!empty($financial_result)) {
                                        foreach ($financial_result as $key => $value) {
                                            $financial_id   = !empty($value['financial_id']) ? $value['financial_id'] : '';
                                            $financial_name = !empty($value['financial_name']) ? $value['financial_name'] : '';

                                            $select = '';
                                            if ($financial_id == $this->session->userdata('active_year')) {
                                                $select = 'selected';
                                            }

                                            echo '<option value="' . $financial_id . '" ' . $select . '>' . $financial_name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav float-right">
                            <li class="dropdown dropdown-notification nav-item">
                                <?php

                                $overall_btn = 'hide';
                                $stock_btn   = 'hide';
                                $payment_btn = 'hide';
                                $outlet_btn  = 'hide';

                                // Low stock alert
                                $whr_1 = array(
                                    'offset'         => 0,
                                    'limit'          => 5,
                                    'search'         => '',
                                    'item_type'      => '1',
                                    'method'         => '_lowStockProduct'
                                );

                                $data_1 = avul_call(API_URL . 'catlog/api/productType', $whr_1);
                                $res_1  = !empty($data_1['data']) ? $data_1['data'] : '';

                                if ($res_1) {
                                    $overall_btn = 'show';
                                    $stock_btn   = 'show';
                                }

                                $whr_2 = array(
                                    'offset'  => 0,
                                    'limit'   => 5,
                                    'search'  => '',
                                    'method'  => '_outletPaymentList'
                                );

                                $data_2 = avul_call(API_URL . 'outlets/api/outletPayment', $whr_2);
                                $res_2  = !empty($data_2['data']) ? $data_2['data'] : '';

                                if ($res_2) {
                                    $overall_btn = 'show';
                                    $payment_btn = 'show';
                                }

                                $whr_3 = array(
                                    'offset'  => 0,
                                    'limit'   => 5,
                                    'search'  => '',
                                    'method'  => '_todayOutlets'
                                );

                                $data_3 = avul_call(API_URL . 'outlets/api/outlets', $whr_3);
                                $res_3  = !empty($data_3['data']) ? $data_3['data'] : '';

                                if ($res_3) {
                                    $overall_btn = 'show';
                                    $outlet_btn  = 'show';
                                }
                                ?>
                                <a class="nav-link nav-link-label" href="#" data-toggle="dropdown" aria-expanded="true"><i class="ficon ft-bell"></i><span class="badge badge-pill badge-danger badge-up badge-glow <?php echo $overall_btn; ?>">NEW</span></a>
                                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                    <li class="dropdown-menu-header">
                                        <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span></h6>
                                        <span class="notification-tag badge badge-danger float-right m-0" style="background-color: #fff !important;">New</span>
                                    </li>
                                    <li class="scrollable-container media-list w-100">
                                        <a href="<?php echo BASE_URL . 'index.php/admin/catlog/low_stock'; ?>">
                                            <span class="badge badge-pill badge-danger badge-up badge-glow <?php echo $stock_btn; ?>" style="top: 16px !important;">NEW</span>
                                            <div class="media">
                                                <div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan mr-0"></i></div>
                                                <div class="media-body">
                                                    <h6 class="media-heading">Low stock product</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="<?php echo BASE_URL . 'index.php/admin/outlets/balance_list'; ?>">
                                            <span class="badge badge-pill badge-danger badge-up badge-glow <?php echo $payment_btn; ?>" style="top: 66px !important;">NEW</span>
                                            <div class="media">
                                                <div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan mr-0"></i></div>
                                                <div class="media-body">
                                                    <h6 class="media-heading">Outlet pending collection list</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="<?php echo BASE_URL . 'index.php/admin/outlets/new_outlets'; ?>">
                                            <span class="badge badge-pill badge-danger badge-up badge-glow <?php echo $outlet_btn; ?>" style="top: 120px !important;">NEW</span>
                                            <div class="media">
                                                <div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan mr-0"></i></div>
                                                <div class="media-body">
                                                    <h6 class="media-heading">New outlet</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown dropdown-user nav-item">
                                <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700"><?php echo $this->session->userdata('email'); ?></span><span class="avatar avatar-online"><img src="<?php echo BASE_URL; ?>app-assets/images/logo/logobdel.jpg" alt="avatar" style="background-color: #fff;"><i></i></span></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>index.php/admin/settings/profile_settings"><i class="ft-user"></i> Edit Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>index.php/welcome/logout"><i class="ft-power"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>