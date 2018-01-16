@extends('layouts.app')

@section('content')
    <div class="container">
        @include('conversations.users', ['users' => $users, 'unread' => $unread])
    </div>

@endsection

