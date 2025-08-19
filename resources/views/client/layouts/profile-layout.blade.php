@extends('client.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-col lg:flex-row gap-8">
        @include('client.partials.profile-sidebar')
        <div class="lg:w-3/4 space-y-8">
            @yield('profile-content')
        </div>
    </div>
</div>
@endsection
