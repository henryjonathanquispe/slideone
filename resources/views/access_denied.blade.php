@section('title')
  {{ __('You Need Access') }}
@endsection


@section('css')

@endsection
@section('content')
    <div id="app-package-access-denied">
      <need-access/>
    </div>
    @section('js')
    
    <script src="{{mix('/js/packageAccessDenied.js')}}"></script>
    
    @endsection
@endsection


