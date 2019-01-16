<nav id="sidebar" class="px-4 py-4 relative">
    <button type="button" id="sidebarCollapse" class="mt-2 mr-3 text-white text-2xl  absolute pin-t pin-r mt-4 mr-4">
        <i class="icon-List_1_x40_2xpng_2"></i>
    </button>

    <div class="sidebar-header mb-6 text-center mt-10">
        <img src="{{ asset('img/logo_big.png') }}" width="90" height="79" alt="">
    </div>

    <div class="user-section fhd:pl-24 hd:pl-16 pl-16 text-white mb-6 relative mb-3">
        <div class="text-2xl fhd:text-3xl hd:text-xl text-bold mb-2 hide-min ">{{auth()->user()->name}}</div>
        <div class="text-grey-dark text-base hide-min">Location: Amman</div>
        <img class="rounded-full absolute pin-l pin-t mt-3 hd:mt-2 ml-1 w-12"
             src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQC8dqjys69YAcJBCAsXrwVAkdDMI6p3r_xIRRt-XDhuZyp7gNd"
             alt="">
    </div>

    <ul class="list-unstyled components list-reset">

        <li class="uppercase  text-white {{ Request::url() == url('/') ?'active' :''}} ">
            <a href="{{route('home')}}" class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                <i class="icon-Presentation_3_1 min-w-30 text-2xl mr-10 ml-3"></i>
                <span>{{__('irc.dashboard')}}</span>
            </a>
        </li>

        @if(\Auth::user()->hasPermissionTo('cases.job-seeker'))
            <li class="uppercase  text-white {{ Request::url() == url('/job-seekers') ?'active' :''}} ">
                <a href="{{route('job-seekers')}}"
                   class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                    <i class="icon-Users_2_x40_2xpng_2 min-w-30  text-2xl mr-10 ml-3"></i>
                    <span>{{__('irc.all_job_seekers')}}</span>
                </a>
            </li>
        @endif

        @if(\Auth::user()->hasPermissionTo('cases.firm'))
            <li class="uppercase  text-white {{ Request::url() == url('/firms') ?'active' :''}}">
                <a href="{{route('firms')}}" class="text-white text-sm flex items-center remove-text-minified   py-4 relative mb-3">
                    <i class="icon-Storefront_x40_2xpng_2 min-w-30 pin-l pin-t text-xl mr-10 ml-3"></i>
                    <span>{{__('irc.employers')}}</span>
                </a>
            </li>
        @endif

        @if(\Auth::user()->hasPermissionTo('cases.job-opening'))
            <li class="uppercase  text-white {{ Request::url() == url('/job-openings') ?'active' :''}}">
                <a href="{{url('job-openings')}}"
                   class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                    <i class="icon-Briefcase_x40_2xpng_2 min-w-30  pin-l pin-t text-xl mr-10 ml-3"></i>
                    <span>{{__('irc.jobs')}}</span>
                </a>
            </li>
        @endif

        <li class="uppercase  text-white {{ Request::url() == url('/users') ?'active' :''}}">
            <a href="{{route('users')}}"
               class="text-white flex items-center  text-sm remove-text-minified py-4 relative mb-3">
                <i class="icon-Briefcase_x40_2xpng_2 min-w-30  pin-l pin-t text-xl mr-10 ml-3"></i>
                <span>{{__('irc.users')}}</span>
            </a>
        </li>


        <li class="uppercase  text-white">

            <a class="text-white text-sm flex items-center remove-text-minified py-4 relative mb-3"
               href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class=" min-w-30 icon-Lock_x40_2xpng_2  pin-l pin-t text-2xl mr-10 ml-3"></i>
                <span>{{__('irc.logout')}}</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>

    </ul>
</nav>

<style>
    .hd\:mt-2 {
        margin-top: -0.5rem;
    }

</style>

@section('script')

@endsection
