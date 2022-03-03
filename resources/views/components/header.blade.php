@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-sm-6">
                    <h1 class="m-0">{{$slot}}</h1>
                </div>

                @if($actions??false)
                    <div class="col d-flex justify-content-end">
                        {{$actions}}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
