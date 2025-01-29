@extends('layouts.app')

@section('title', 'Chat')

@section('content')
    <h1>Chat for Article #{{ $articleId }}</h1>
    <ul>
        @foreach($messages as $message)
            <li><strong>{{ $message->user->name }}:</strong> {{ $message->message }}</li>
        @endforeach
    </ul>
    <form action="{{ route('subscriber.chat.post') }}" method="POST">
        @csrf
        <input type="hidden" name="article_id" value="{{ $articleId }}">
        <textarea name="message" required></textarea>
        <button type="submit">Send</button>
    </form>
@endsection
