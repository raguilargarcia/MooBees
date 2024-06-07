@extends('layouts.app')

@section('content')
<div class="container followings-page">
    <h1>Usuarios que {{ $user->username }} sigue</h1>
    <ul class="list-group">
        @foreach ($followings as $following)
        <li class="list-group-item">
            <a href="{{ route('profile.view', $following->id) }}">{{ $following->username }}</a>
        </li>
        @endforeach
    </ul>
</div>
@endsection
