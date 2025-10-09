<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="mdi mdi-dots-horizontal"></i>
                    <span class="hide-menu">Admin</span>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="hide-menu"> Users </span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="fas fa-users"></i>
                        <span class="hide-menu">Dashboard </span>
                        <span class="badge badge-pill badge-info ml-auto m-r-15">3</span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="index.html" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu"> Classic </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="index2.html" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu"> Analytical </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="index3.html" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu"> Modern </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-tune"></i>
                        <span class="hide-menu">Sidebar Type </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="sidebar-type-minisidebar.html" class="sidebar-link">
                                <i class="mdi mdi-view-quilt"></i>
                                <span class="hide-menu"> Minisidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="sidebar-type-iconsidebar.html" class="sidebar-link">
                                <i class="mdi mdi-view-parallel"></i>
                                <span class="hide-menu"> Icon Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="sidebar-type-overlaysidebar.html" class="sidebar-link">
                                <i class="mdi mdi-view-day"></i>
                                <span class="hide-menu"> Overlay Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="sidebar-type-fullsidebar.html" class="sidebar-link">
                                <i class="mdi mdi-view-array"></i>
                                <span class="hide-menu"> Full Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="sidebar-type-horizontalsidebar.html" class="sidebar-link">
                                <i class="mdi mdi-view-module"></i>
                                <span class="hide-menu"> Horizontal Sidebar </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-crop-square"></i>
                        <span class="hide-menu">Page Layouts </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="layout-inner-fixed-left-sidebar.html" class="sidebar-link">
                                <i class="mdi mdi-format-align-left"></i>
                                <span class="hide-menu"> Inner Fixed Left Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="layout-inner-fixed-right-sidebar.html" class="sidebar-link">
                                <i class="mdi mdi-format-align-right"></i>
                                <span class="hide-menu"> Inner Fixed Right Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="layout-inner-left-sidebar.html" class="sidebar-link">
                                <i class="mdi mdi-format-float-left"></i>
                                <span class="hide-menu"> Inner Left Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="layout-inner-right-sidebar.html" class="sidebar-link">
                                <i class="mdi mdi-format-float-right"></i>
                                <span class="hide-menu"> Inner Right Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="page-layout-fixed-header.html" class="sidebar-link">
                                <i class="mdi mdi-view-quilt"></i>
                                <span class="hide-menu"> Fixed Header </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="page-layout-fixed-sidebar.html" class="sidebar-link">
                                <i class="mdi mdi-view-parallel"></i>
                                <span class="hide-menu"> Fixed Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="page-layout-fixed-header-sidebar.html" class="sidebar-link">
                                <i class="mdi mdi-view-column"></i>
                                <span class="hide-menu"> Fixed Header &amp; Sidebar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="page-layout-boxed-layout.html" class="sidebar-link">
                                <i class="mdi mdi-view-carousel"></i>
                                <span class="hide-menu"> Box Layout </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-small-cap">
                    <i class="mdi mdi-dots-horizontal"></i>
                    <span class="hide-menu">Apps</span>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->