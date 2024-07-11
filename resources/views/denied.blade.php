@extends('layouts.process-map')

@section('title')
  {{ __('You Need Access') }}
@endsection

@section('content')
    <div id="app-package-access-denied" class="h-full">
      <need-access/>
    </div>

    @section('js')

    <script src="{{mix('/js/packageAccessDenied.js', 'vendor/processmaker/packages/package-slideshow')}}"></script>
    @endsection
@endsection


