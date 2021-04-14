@extends('support::base')

@section('content')
    {{-- DO NOT REMOVE - page identity tag --}}
    <!-- M:{{ $page->market->id }} -->


    {{-- Place holder content - safe to replace --}}
    <ul>
        <li>Market: {{ $page->market->title }}</li>
        <li>Page: {{ $page->name }}</li>
    </ul>
@endsection
