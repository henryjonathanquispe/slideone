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
      window.ProcessMaker.PMBlockList = @json($pmBlockList);
      window.ProcessMaker.modeler = {
        process: @json($process),
        xml: {!! json_encode($bpmnA) !!},
        xmlB: {!! json_encode($bpmnB) !!},
        published_version: '{!! $published_version !!}',
      }
    </script>
    <!-- screen scripts -->
    @foreach($manager->getScripts() as $script)
        <script src="{{$script}}"></script>
    @endforeach
    
    @foreach($managerModeler->getScripts() as $script)
      @if (!str_contains($script, 'slideshow'))
      <script src="{{ $script }}"></script>
      @endif
    @endforeach

    <script src="{{ mix('js/processes/modeler/initialLoad.js') }}"></script>
    
    <script src="{{mix('/js/package.js', 'vendor/processmaker/packages/package-slideshow')}}"></script>
    
    @endsection
@endsection


