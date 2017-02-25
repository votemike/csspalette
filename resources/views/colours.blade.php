@extends('layout.app')

@section('content')
    @forelse($colours as $colour)
        <div class="colour-container">
            <div class="colour" style="background-color: {{$colour->toRgba()}};"></div>
            <div class="colour-text">{{$colour->toRgba()}}</div>
            <div class="colour-text">{{$colour->toHex()}}</div>
            <div class="colour-text">{{$colour->toX11()}}</div>
        </div>
    @empty
        <div><strong>No Colours Found</strong></div>
        <div><a href="{{ url('/') }}">Back</a></div>
    @endforelse
@endsection
