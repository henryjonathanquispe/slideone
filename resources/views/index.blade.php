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
    <!-- modeler scripts -->
    @foreach($managerModeler as $script)
      @if (isset($script['src']) && !str_contains((string) $script['src'], 'slideshow')   )
        @if (isset($script['type']) && $script['type'] === 'module')
          <script type="module" src="{{ $script['src'] }}"></script>
        @else
          <script src="{{ $script['src'] }}"></script>
        @endif
      @endif
    @endforeach

    <script src="{{ mix('js/processes/modeler/initialLoad.js') }}"></script>
    
    <script src="{{mix('/js/package.js', 'vendor/processmaker/packages/package-slideshow')}}"></script>
    
    @endsection
@endsection


