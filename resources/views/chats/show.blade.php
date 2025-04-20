@extends('layouts.app')

@section('content')
    <h1>{{ $chat->name }}</h1>

    <div class="messages">
        @foreach ($chat->messages as $message)
            <div class="message">
                <p>{{ $message->text }}</p>
                @if($message->user_id === auth()->id())
                    <a href="{{ route('messages.reply', $message->id) }}">Odpovedať</a>
                @endif
            </div>
        @endforeach
    </div>

    <form action="{{ route('messages.store', $chat->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <textarea name="text" placeholder="Napíšte správu..."></textarea>
        <input type="file" name="attachment">
        <button type="submit">Poslať</button>
    </form>
@endsection
