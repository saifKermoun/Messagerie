@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('conversations.users', ['users' => $users, 'unread' => $unread])
            <div class="col-sm-9 col-md-9">
                <div class="card">
                    <div class="card-header">Conversation avec <strong>{{$user->name}}</strong></div>
                    <div class="card-body conversations">
                        @if($messages->hasMorePages())
                            <div class="text-center">
                                <a href="{{$messages->nextPageUrl()}}" class="btn btn-light">
                                    Voir les messages précédents
                                </a>
                            </div>
                        @endif
                        @foreach(array_reverse($messages->items()) as $msg)
                            <div class="row">
                                <div class="col-md-10 {{$msg->from->id !== $user->id ? 'col-md-offset-3 text-right' : ''}}">
                                    <p>
                                        <strong>{{$msg->from->id !== $user->id ? 'Moi' : $msg->from->name}}</strong><br>
                                        {!!  nl2br(e($msg->content)) !!}
                                    </p>
                                </div>
                                <hr>
                            </div>
                        @endforeach
                            @if($messages->previousPageUrl())
                                <div class="text-center">
                                    <a href="{{$messages->previousPageUrl()}}" class="btn btn-light">
                                        Voir les dernier messages
                                    </a>
                                </div>
                            @endif
                        <form action="" method="post">
                            {{ csrf_field()  }}
                            <div class="form-group">
                                <textarea name="content" placeholder="Votre message ici" class="form-control {{ $errors->has('content') ? 'is-invalid' :  '' }}"></textarea>
                                @if($errors->has('content'))
                                    <div class="invalid-feedback">
                                        {{ implode(',', $errors->get('content')) }}
                                    </div>
                                @endif
                            </div>
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

