@extends('layouts.layout')
@section('content')
    <div>

        @can('cases.match')
        <breadcrumbs
                :crumbs="[{name:'{{trans('irc.job_openings')}}', link:'{{route('job-openings')}}'},
                 {name:'{{ trans('irc.job_opening_match') }}  [ {{$jobOpening->job_title}} ]', link:'{{route('job-openings.match',[$jobOpening->id])}}'},
                {name:'{{ trans('irc.job_opening_saved_match') }} [ {{$jobOpening->job_title}} ]', link:''}]"
        ></breadcrumbs>
        @endcan

        <job-opening-saved-matches
                :can-back="{{ auth()->user()->hasPermissionTo('cases.match') ? 'true':'false' }}"
                route="{{route('api.matches.saved',[$jobOpening->id])}}"
                matches-url="{{route('job-openings.match',[$jobOpening->id])}}"
                :job-opening="{{ json_encode(case_resource('job-opening',$jobOpening)->toArray(request())) }}"></job-opening-saved-matches>
    </div>
@endsection

