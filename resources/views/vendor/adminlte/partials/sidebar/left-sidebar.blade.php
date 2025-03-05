<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

{{-- Sidebar brand logo --}}
<div class="brand-logo-container text-center">
    <a href="{{ url('/') }}" class="brand-link d-flex flex-column align-items-center">
        <img src="{{ asset('images/nha.png') }}" 
            alt="Logo" 
            class="brand-image"
            id="sidebar-logo"
            style="max-height: 100px; width: auto;">
        <span class="brand-text font-weight-bold mt-2 d-none d-md-block">NOTICE TRACKER</span>
    </a>
</div>


    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>

<style>
.main-sidebar {
    background: linear-gradient(180deg, #295F98 0%, #1E3A5F 100%);
    color: white;
}

.nav-sidebar .nav-item {
    position: relative;
}

.nav-sidebar .nav-link {
    color: #ffffff !important;
    font-size: 15px;
    padding: 10px 15px;
    border-radius: 6px;
    transition: background 0.3s, transform 0.2s ease-in-out;
}

.nav-sidebar .nav-link:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateX(5px);
}

.nav-sidebar .nav-item > .nav-link.active {
    background: #4A8FD4 !important;
    color: #ffffff !important;
    font-weight: bold;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(74, 143, 212, 0.5);
    position: relative;
}

.nav-sidebar .nav-item > .nav-link.active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 10%;
    width: 5px;
    height: 80%;
    background-color: white;
    border-radius: 5px;
}

.nav-sidebar .nav-item > .nav-link.active i {
    color: white !important;
}

.nav-treeview .nav-item .nav-link {
    padding-left: 30px;
    font-size: 14px;
}

.nav-treeview .nav-item .nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
}

.nav-treeview .nav-item .nav-link.active {
    background: #5BA5E0 !important;
    border-radius: 5px;
}
/* Centering logo */
.brand-logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 15px 0;
    transition: all 0.3s ease-in-out;
}

.brand-image {
    max-height: 100px;
    width: auto;
    transition: all 0.3s ease-in-out;
}

.brand-text {
    color: white;
    font-size: 20px;
    text-align: center;
    font-weight: bold;
    transition: all 0.3s ease-in-out;
}

.sidebar-collapse .brand-image {
    max-height: 50px !important;
}

.sidebar-collapse .brand-text {
    display: none !important;
}

@media (max-width: 768px) {
    .brand-image {
        max-height: 80px;
    }
}

</style>
