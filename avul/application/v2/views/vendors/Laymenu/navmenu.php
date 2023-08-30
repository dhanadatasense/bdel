        <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
            <div class="navbar-wrapper">
                <div class="navbar-header">
                    <ul class="nav navbar-nav flex-row text-center">
                        <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="fa fa-bars font-large-1"></i></a></li>
                        <li class="nav-item">
                            <a class="navbar-brand" href="#">
                                <img class="brand-logo rounded mx-auto d-block center-block" alt="modern admin logo" src="<?php echo BASE_URL; ?>app-assets/images/logo.png">
                                <!-- <h3 class="brand-text">Udangudi Karupatti</h3> -->
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

                                        $financial_list  = avul_call(API_URL.'master/api/financial',$where_1);
                                        $financial_result = $financial_list['data'];

                                        if(!empty($financial_result))
                                        {
                                            foreach ($financial_result as $key => $value) {
                                                $financial_id   = !empty($value['financial_id'])?$value['financial_id']:'';
                                                $financial_name = !empty($value['financial_name'])?$value['financial_name']:'';

                                                $select = '';
                                                if($financial_id == $this->session->userdata('active_year'))
                                                {
                                                    $select = 'selected';
                                                }

                                                echo '<option value="'.$financial_id.'" '.$select.'>'.$financial_name.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav float-right">
                            <li class="dropdown dropdown-user nav-item">
                                <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700"><?php echo $this->session->userdata('email'); ?></span><span class="avatar avatar-online"><img src="<?php echo BASE_URL; ?>app-assets/images/logo/logo.png" alt="avatar" style="background-color: #fff;"><i></i></span></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <!-- <a class="dropdown-item" href="<?php echo BASE_URL;?>index.php/admin/settings/profile_settings"><i class="ft-user"></i> Edit Profile</a> -->
                                    <!-- <div class="dropdown-divider"></div> -->
                                    <a class="dropdown-item" href="<?php echo BASE_URL;?>index.php/welcome/logout"><i class="ft-power"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>