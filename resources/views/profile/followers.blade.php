@extends('layouts.app')

@section('content')
<div class="container followers-page">
    <h1>Seguidores de {{ $user->username }}</h1>
    <ul class="list-group">
        @foreach ($followers as $follower)
        <li class="list-group-item">
            <a href="{{ route('profile.view', $follower->id) }}">{{ $follower->username }}</a>
        </li>
        @endforeach
    </ul>
</div>
@endsection
