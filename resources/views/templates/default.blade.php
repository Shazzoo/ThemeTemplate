{{--
Template Name: Default
Template Key: default
Description: Default with header + CMS blocks + footer
Regions: header,content,footer
--}}

@extends($layout)

@section('content')
    @include('partials.cms-blocks')
@endsection
