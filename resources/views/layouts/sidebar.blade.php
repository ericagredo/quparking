<?php
$CurrentUrl = Helper::GetCurrentUrl();

if(count($CurrentUrl) > 5){
    $CurrentUrlArray = array_slice($CurrentUrl, 0, 3);
    $controller = $CurrentUrlArray[2];
    $action = $CurrentUrlArray[1]; 
}else{
    $CurrentUrlArray = array_slice($CurrentUrl, 0, 2);
    $controller = $CurrentUrlArray[1];
    $action = $CurrentUrlArray[0];
}

?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('img/avatar.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ ucfirst(Session::get('userData')->first_name.' '.Session::get('userData')->last_name) }}</p>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Main Navigation</li>

            <?php
            $class = $controller;
            $action = $action;
            if (isset($menuArray) && !empty($menuArray)) {
                $displayMenu = 0; //print_r($menuArray); die;
                foreach ($menuArray as $val) {
                    /*echo '<pre>';
                    print_r($val);
                    echo '</pre>'; continue;*/
                    if ($val['ShowInMenu'] == 'Yes') {
                        $displayMenu = 1;

                        $MenuId = $val['MenuId'];
                        $Name = $val['Name'];
                        $icocls = $val['IconCls'];
                        $MnuController = $val['Controller'];
                        $MnuAction = $val['Action'];

                        $clasActive = '';

                        $getActionMenuQuery = "Select act.action_id,IF(mnu.parent_id > 0, mnu.parent_id, act.menu_id) as MenuId"
                                . " From action_master as act Left Join menu_master as mnu ON act.menu_id = mnu.menu_id"
                                . " Where controller='" . $class . "' And action='" . $action . "'";
                       
                        $ActionMenuResult = DB::select($getActionMenuQuery);
                  
                        
                        $ActMenuId = '';
                       
                        if (count($ActionMenuResult) > 0) {
                            $data = $ActionMenuResult[0];
                            $ActMenuId = $data->MenuId;
                        }

                        if ($class == $MnuController && $action == $MnuAction) {
                            $clasActive = 'active';
                        } else if ($MenuId == $ActMenuId) {
                            $clasActive = 'active';
                        }

                        $url = asset($MnuController . '/' . $MnuAction);
                        
                            $subcls = '';
                            $SubMenu = '';
                            if (isset($val['SubMenu']) && !empty($val['SubMenu'])) {
                                $SubMenu = $val['SubMenu'];
                                $subcls = 'treeview';
                                $k = 0;
                                foreach ($SubMenu as $subVal) {
                                    $SubMnuName = $subVal['Name'];
                                    $SubMnuController = $subVal['Controller'];
                                    $SubMnuAction = $subVal['Action'];

                                    if ($class == $SubMnuController && $action == $SubMnuAction) {
                                        $clasActive = 'active';
                                    }
                                    $k++;
                                }
                            }
                            if ($displayMenu == 1) {
            ?>
            <?php if (isset($SubMenu) && !empty($SubMenu)) { ?>
            <li class="<?php echo $subcls; ?> <?php echo $clasActive; ?>">
            <?php } else { ?>
            <li class="<?php echo $subcls; ?> <?php echo $clasActive; ?>"><a href="<?php echo $url; ?>"><i class="<?php echo $icocls; ?>"></i> <span><?php echo $Name; ?></span></a>
                <?php } ?>

                <?php
                if (isset($SubMenu) && !empty($SubMenu)) { //print_r($SubMenu); die;
                ?>

                <a href="#">
                    <i class="fa fa-users"></i> <span>Manage Admin Users</span>
                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php

                    // print_r($SubMenu);exit;
                    foreach ($SubMenu as $subVal) {
                    $SubMnuName = $subVal['Name'];
                    $SubMnuController = $subVal['Controller'];
                    $SubMnuAction = $subVal['Action'];
                    $subclassActive = '';
                    $checkActionPermissionvar = false;

                    if ($subVal['ShowInMenu'] == 'Yes') {
                    $url = asset($SubMnuController . '/' . $SubMnuAction);
                    if ($class == $SubMnuController && $action == $SubMnuAction) {
                        $subclassActive = 'active';
                    }
                    ?>
                    <li class="<?php echo $subclassActive; ?>"><a href="<?php echo $url; ?>"><?php echo $SubMnuName; ?></a></li>
                    <?php
                    }
                    }
                    ?>
                </ul>
                <?php
                }
                ?>
            </li>
            <?php
            // }
            }
                    }
                   

                        }
                    }
                    ?>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>