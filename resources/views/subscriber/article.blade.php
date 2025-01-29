@extends('layouts.app')

@section('title', $content->title)

@section('content')
    <style>
        .article-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .article-image {
            width: 100%;
            height: 300px;
            background-color: #f0f0f0;
            color: #888;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-title {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .article-content {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .article-date {
            font-size: 0.8rem;
            color: #999;
            margin-top: 10px;
        }

        .chat-container {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .chat-header {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .messages {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        .message-bubble {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 70%;
            position: relative;
        }

        .message-bubble.right {
            background-color: #007bff;
            color: white;
            margin-left: auto;
            text-align: right;
        }

        .message-bubble.left {
            background-color: #f1f1f1;
            color: #333;
            text-align: left;
        }

        .message-bubble .username {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .message-bubble .delete-btn {
            position: absolute;
            top: 0px;
            right: 5px;
            background: none;
            border: none;
            color: red;
            cursor: pointer;
        }

        .message-bubble.right .delete-btn {
            left: 5px;
            right: auto;
        }

        .chat-input {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .chat-input textarea {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
        }

        .chat-input button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-input button:hover {
            background-color: #0056b3;
        }

        .login-prompt {
            margin-top: 20px;
            color: #555;
        }
    </style>

    <div class="article-container">
        <div class="article-image">
            @if($content->image)
                <img src="{{ asset('storage/' . $content->image) }}" alt="{{ $content->title }}">
            @else
                <span>No Image</span>
            @endif
        </div>

        <h1 class="article-title">{{ $content->title }}</h1>
        <p class="article-content">{{ $content->content }}</p>
        <p class="article-date">Published: {{ $content->created_at->format('M d, Y') }}</p>

        <div class="chat-container">
            <h2 class="chat-header">Chat</h2>

            <div class="messages">
                @if(count($content->chats))
                    @foreach($content->chats->sortBy('created_at') as $chat)
                        <div class="message-bubble {{ Auth::check() && Auth::id() === $chat->user_id ? 'right' : 'left' }}">
                            <span class="username">{{ $chat->user->name }}</span>
                            <p>{{ $chat->message }}</p>
                            @if(Auth::check())
                                @if(Auth::id() === $chat->user_id)
                                    <form action="{{ route('subscriber.chat.delete', $chat->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="delete-btn">x</button>
                                    </form>
                                @elseif(Auth::user()->role === 'theme_manager' && Auth::id() === $content->theme->user_id)
                                    <form action="{{ route('theme_manager.chat.delete', $chat->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="delete-btn">x</button>
                                    </form>
                                @endif
                            @endif

                        </div>
                    @endforeach
                @else
                    <p>No messages</p>
                @endif
            </div>

            @if(Auth::check())
                @if($isAuthorized && Auth::user()->role === "subscriber")
                    <form action="{{ route('subscriber.chat.store', $content->id) }}" method="POST" class="chat-input">
                        @csrf
                        <textarea name="message" rows="3" placeholder="Write your message..." required></textarea>
                        <button type="submit">Send</button>
                    </form>
                @endif
            @else
                <p class="login-prompt">
                    To join the conversation, please <a href="{{ route('login') }}">log in</a> or <a href="{{ route('register') }}">register</a>.
                </p>
            @endif
        </div>
    </div>
@endsection
