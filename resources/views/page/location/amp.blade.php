@extends('support::amp')

@section('content')
    {{-- DO NOT REMOVE - page identity tag --}}
    <!-- M:{{ $page->market->id }} L:{{ $child_page->location->id }} -->

    {{-- Place holder content - safe to replace --}}
    <ul>
        <li>Market: {{ $page->market->title }}</li>
        <li>Page: {{ $page->name }}</li>

        <li>Location: {{ $child_page->location->title }}</li>
        <li>Child: {{ $child_page->name }}</li>
    </ul>
@endsection
