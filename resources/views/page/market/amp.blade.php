@extends('support::amp')

@section('content')
    @include('locations::page.market.partials._identity_tag')
    @include('locations::page.market.partials.announcements')

    {{-- Place holder content - safe to replace --}}
    <ul>
        <li>Market: {{ $page->market->title }}</li>
        <li>Page: {{ $page->name }}</li>
    </ul>
@endsection
