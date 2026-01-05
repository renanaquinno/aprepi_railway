@extends('layouts.app')
@section('content')
    <div class="bg-white shadow-md rounded-lg p-8">
        {{ $slot }}
    </div>
@endsection
