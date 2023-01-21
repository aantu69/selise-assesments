{{-- <div @class([
    'alert alert-block',
    'alert-success' => ($message = Session::get('success')),
    'alert-danger' => ($message = Session::get('error')),
    'alert-warning' => ($message = Session::get('warning')),
    'alert-info' => ($message = Session::get('info')),
])>
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div> --}}

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

{{-- @if ($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
