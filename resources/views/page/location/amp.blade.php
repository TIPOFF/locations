@extends('support::amp')

@section('content')
    @include('locations::page.location.partials._identity_tag')

    {{-- Place holder content - safe to replace --}}
    <ul>
        <li>Market: {{ $page->market->title }}</li>
        <li>Page: {{ $page->name }}</li>

        <li>Location: {{ $child_page->location->title }}</li>
        <li>Child: {{ $child_page->name }}</li>
    </ul>
@endsection
