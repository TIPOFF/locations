@extends('support::base')

@section('content')
<div class="flex flex-col">
    <div class="w-1/2">
        @livewire ('addresses::domestic-address-search-bar')
    </div>
    <form 
        method="POST"
        action="{{ route('demo-submit') }}" 
        class="w-1/2 my-4"
    >
        @csrf
        @livewire ('addresses::domestic-address-search-bar-fields')
        @livewire ('addresses::get-phone')
    </form>
</div>
@endsection
