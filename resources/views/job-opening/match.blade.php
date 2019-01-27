@extends('layouts.layout')
@section('content')
    <div>
        <breadcrumbs
            :crumbs="[{name:'{{trans('irc.job_openings')}}', link:'{{route('job-openings')}}'},
            {name:'{{ trans('irc.job_opening_match') }}  [ {{$jobOpening->job_title}} ]', link:''}]"
        ></breadcrumbs>
        <job-opening-match
                route="{{route('api.matches',[$jobOpening->id])}}"
                :job-opening="{{ json_encode(case_resource('job-opening',$jobOpening)->toArray(request())) }}"></job-opening-match>
    </div>
@endsection

