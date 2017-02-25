@extends('layout.app')

@section('content')

    <div class="title">Palette</div>
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            There was an error. Please check your input or raise a bug at <a
                    href="https://github.com/votemike/palette/issues">the GitHub project page</a> with the message
            "{{ $errors->first() }}".
        </div>
    @endif
    <div class="forms-container">
        <div>From Website</div>
        <form method="get" action="/site">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-7">
                        <input type="url" class="form-control" id="site" name="site"
                               placeholder="http://votemike.co.uk/" value="{{ old('site') }}"/>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-default">Go go go</button>
                    </div>
                </div>
            </div>
        </form>
        <div>From Online CSS file</div>
        <form method="get" action="/sitefile">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-7">
                        <input type="url" class="form-control" id="sitefile" name="sitefile"
                               placeholder="http://votemike.co.uk/styles.css" value="{{ old('sitefile') }}"/>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-default">Go go go</button>
                    </div>
                </div>
            </div>
        </form>
        <div>From Uploaded CSS file</div>
        <form method="post" action="/file" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-7">
                        <input type="file" class="form-control" id="file" name="file" accept="text/css"/>
                        {{ csrf_field() }}
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-default">Go go go</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
