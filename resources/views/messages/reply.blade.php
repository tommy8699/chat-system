@extends('layouts.app')

@section('content')
    <h1>Odpovedať na správu</h1>
    <form action="{{ route('messages.reply.store', $message->id) }}" method="POST">
        @csrf
        <textarea name="text" placeholder="Napíšte odpoveď..."></textarea>
        <button type="submit">Odpovedať</button>
    </form>
@endsection
