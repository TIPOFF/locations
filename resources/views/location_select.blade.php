@extends('support::layout')

@section('content')
    <h3>Select Location @if($market)for {{ $market->name }}@endif</h3>
    <ul>
    @foreach($locations as $location)
        <li>
            <a href="{{ route('location', ['market' => $location->market, 'location' => $location]) }}">{{ $location->name }}</a>
        </li>
    @endforeach
    </ul>
@endsection
