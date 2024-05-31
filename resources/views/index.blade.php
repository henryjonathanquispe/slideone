@extends('layouts.process-map')

@section('title')
  {{ __('Process Map') }}
@endsection


@section('css')
    <link rel="stylesheet" href="{{mix('/css/package.css', 'vendor/processmaker/packages/package-slideshow')}}">
@endsection
@section('content')
    <div class="page-content" id="app-package-slideshow">
        <slide-show/>
    </div>
    @section('js')

    <script>
    const breadcrumbData = [];

    </script>

    <script src="{{ mix('js/processes/modeler/initialLoad.js') }}"></script>
    
    <script src="{{mix('/js/package.js', 'vendor/processmaker/packages/package-slideshow')}}"></script>

    @endsection
@endsection


