<nav id="sidebar" class="px-4 py-4 relative">
    <div class="flex toggle-lang items-center justify-between">
        {!! switch_url(true) !!}
        <button type="button" id="sidebarCollapse" class="text-white minifiy-button ">
            <i class="icon-List_1_x40_2xpng_2 text-base"></i>
        </button>
    </div>

    <div class="sidebar-header mb-6 text-center mt-10">
        <img src="{{ asset('img/logo_big.png') }}" width="70" height="79" alt="">
    </div>

    <div class="text-white flex items-center">
        <img class="rounded-full w-10 h-10 mx-2"
             src="{{ auth()->user()->profile_picture }}"
             alt="{{auth()->user()->name}}">

        <div>
            <div class="text-bold text-sm hide-min ">{{auth()->user()->name}}</div>
            {{--<div class="text-grey-dark text-base hide-min">Location: Amman</div>--}}
        </div>
    </div>

    <ul class="list-unstyled components list-reset">

        <li class="uppercase  text-white {{ request()->routeIs('home') ?'active' :''}} ">
            <a href="{{route('home')}}" class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                <i class="icon-Presentation_3_1 min-w-30 text-2xl mr-4 ml-3"></i>
                <span class="sm:text-xs  hide-min">{{__('irc.dashboard')}}</span>
            </a>
        </li>

        @if(\Auth::user()->hasPermissionTo('cases.job-seeker'))
            <li class="uppercase  text-white {{ request()->is('*job-seekers*') ?'active' :''}} ">
                <a href="{{route('job-seekers')}}"
                   class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                    <i class="icon-Users_2_x40_2xpng_2 min-w-30  text-2xl mr-4 ml-3"></i>
                    <span class="sm:text-xs  hide-min">{{__('irc.all_job_seekers')}}</span>
                </a>
            </li>
        @endif

        @if(\Auth::user()->hasPermissionTo('cases.firm'))
            <li class="uppercase  text-white {{ request()->is('*firms*') ?'active' :''}}">
                <a href="{{route('firms')}}" class="text-white text-sm flex items-center remove-text-minified   py-4 relative mb-3">
                    <i class="icon-Storefront_x40_2xpng_2 min-w-30 pin-l pin-t text-xl mr-4 ml-3"></i>
                    <span class="sm:text-xs  hide-min">{{__('irc.employers')}}</span>
                </a>
            </li>
        @endif

        @if(\Auth::user()->hasPermissionTo('cases.job-opening'))
            <li class="uppercase  text-white {{ request()->is('*job-openings*') ?'active' :''}}">
                <a href="{{route('job-openings')}}"
                   class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                    <i class="icon-Briefcase_x40_2xpng_2 min-w-30  pin-l pin-t text-xl mr-4 ml-3"></i>
                    <span class="sm:text-xs  hide-min">{{__('irc.jobs')}}</span>
                </a>
            </li>
        @endif
        @if(\Auth::user()->hasPermissionTo('users_management'))
        <li class="uppercase  text-white {{  request()->is('*users*') ?'active' :''}}">
            <a href="{{route('users')}}"
               class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                <i class="icon-Users_1_x40_2xpng_2 min-w-30  pin-l pin-t text-xl mr-4 ml-3"></i>
                <span class="sm:text-xs  hide-min">{{__('irc.users')}}</span>
            </a>
        </li>
        @endif


        <li class="uppercase  text-white">

            <a class="text-white text-sm flex items-center remove-text-minified py-4 relative mb-3"
               href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class=" min-w-30 icon-Lock_x40_2xpng_2  pin-l pin-t text-2xl mr-4 ml-3"></i>
                <span class="sm:text-xs  hide-min">{{__('irc.logout')}}</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>

    </ul>
</nav>


@section('script')

@endsection
