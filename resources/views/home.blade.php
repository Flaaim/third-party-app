@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    {{$error}}
                @endforeach
            @endif
            <div class="card">
                <div class="card-header">
                    Timeline
                </div>
                <div class="card-body">
                    @if(Auth::user()->token)
                        @if(count($tweets))
                            @foreach($tweets as $tweet)
                            <div class="d-flex p-2">
                            <div class="flex-shrink-0">
                            <img src="https://eu.ui-avatars.com/api/?size=64" class="img-responsive" alt="avatar">
                                </div>
                            <div class="flex-grow-1 ms-3">
                                    {{$tweet->user->name}}
                                <p>
                                    {{ $tweet->body }}
                                </p>
                            </div>
                            </div>

                            @endforeach
                        @endif
                    @else
                        Error. Please <a href="{{url('/auth/twitter')}}">authorize</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
