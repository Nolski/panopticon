@extends('layouts.layout')
@section('content')
    <breadcrumbs
            :crumbs="[{name:'{{trans('irc.job_openings')}}', link:'{{route('job-openings')}}'}]"
    ></breadcrumbs>
    <div class="" id="all-firms">
        <case-listing type="job-opening">
            @if(auth()->user()->hasPermissionTo("cases.match"))
                <template
                        slot="end-td"
                        slot-scope="{row}"
                >
                    <a class="no-underline text-green mr-2 text-xl"
                       v-tooltip="{'content':'{{trans('irc.match_job')}}','classes':['tooltip-datatable']}"
                       :href="`{{str_replace('__id__','${row.id}', route('job-openings.match','__id__')) }}`">
                        <i class="icon-Magnify_Glass_x40_2xpng_2"></i>
                    </a>
                </template>
                <template
                        slot="start-td"
                        slot-scope="{row}"
                >
                    <a class="no-underline text-green mr-2 text-xl"
                       v-tooltip="{'content':'{{trans('irc.match_job')}}','classes':['tooltip-datatable']}"
                       :href="`{{str_replace('__id__','${row.id}', route('job-openings.match','__id__')) }}`">
                        <i class="icon-Magnify_Glass_x40_2xpng_2"></i>
                    </a>
                </template>
            @endif
        </case-listing>
    </div>
@endsection

