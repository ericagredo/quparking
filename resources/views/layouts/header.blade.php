<header class="main-header">
    <!-- Logo -->
    <a href="/admin/dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Q</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>QU</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning noti_count">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <span class="noti_count"></span> notifications</li>
              <li>
                <!-- Inner Menu: contains the notifications -->
                <ul class="menu noti_manu">
                  <!-- start notification -->
                  <!-- end notification -->
                </ul>
              </li>
              <!--li class="footer"><a href="#">View all</a></li-->
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{ asset('img/avatar.png') }}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ ucfirst(Session::get('userData')->first_name.' '.Session::get('userData')->last_name) }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{ asset('img/avatar.png') }}" class="img-circle" alt="User Image">
                <p>
                 {{ ucfirst(Session::get('userData')->first_name.' '.Session::get('userData')->last_name) }}                 
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ url('/admin/viewProfile') }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ url('/admin/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
         </ul>
      </div>
    </nav>
  </header>