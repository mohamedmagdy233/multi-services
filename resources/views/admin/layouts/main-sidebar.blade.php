<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{route('adminHome')}}">
            <img src="{{ getFile(isset($setting->where('key', 'logo')->first()->value) ? $setting->where('key', 'logo')->first()->value : null)}}"
                 class="header-brand-img" alt="logo">
        </a>
        <!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li><h3>{{ trns('elements') }}</h3></li>
        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'adminHome' ? 'active' : '' }}" href="{{route('adminHome')}}">
                <i class="fa fa-home side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('home') }}</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'admins.index' ? 'active' : '' }}" href="{{route('admins.index')}}">
                <i class="fa fa-users side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('admins') }}</span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'service_types.index' ? 'active' : '' }}" href="{{route('service_types.index')}}">
                <i class="fa fa-calendar-times side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('service_types') }}</span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'sub_service_types.index' ? 'active' : '' }}" href="{{route('sub_service_types.index')}}">
                <i class="fa fa-fan side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('sub_service_types') }}</span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'offers.index' ? 'active' : '' }}" href="{{route('offers.index')}}">
                <i class="fa fa-tag side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('offers') }}</span>
            </a>
        </li>


        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'users.index' ? 'active' : '' }}" href="{{route('users.index')}}">
                <i class="fa fa-user-alt side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('users') }}</span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'leaders.index' ? 'active' : '' }}" href="{{route('leaders.index')}}">
                <i class="fa fa-user-alt-slash side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('leaders') }}</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'general_offers.index' ? 'active' : '' }}" href="{{route('general_offers.index')}}">
                <i class="fa fa-comment-dollar side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('general_offers') }}</span>
            </a>
        </li>


        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'settingIndex' ? 'active' : '' }}" href="{{route('settingIndex')}}">
                <i class="fa fa-wrench side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('settings') }}</span>
            </a>
        </li>


        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'admin.logout' ? 'active' : '' }}" href="{{route('admin.logout')}}">
                <i class="fa fa-lock side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li>

    </ul>
</aside>

