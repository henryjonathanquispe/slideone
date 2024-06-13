@extends('layouts.process-map')

@section('title')
  {{ __('Process Map') }}
@endsection


@section('css')

@endsection
@section('content')
    <div id="app-package-show">
        <slide-show/>
    </div>
    @section('js')
    <script>
      window.ProcessMaker.modeler = {
        process: @json($process),
        xml: @json($process->bpmn),
      }
    </script>

    <script src="{{ mix('js/processes/modeler/initialLoad.js') }}"></script>
    
    <script src="{{mix('/js/package.js', 'vendor/processmaker/packages/package-slideshow')}}"></script>

    
    @endsection
@endsection


