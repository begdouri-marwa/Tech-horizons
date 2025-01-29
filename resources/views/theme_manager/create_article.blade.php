@extends('layouts.app')

@section('title', 'Post New Article')

@section('content')
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-submit {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #218838;
        }
    </style>

    <div class="form-container">
        <h1>Post New Article</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('theme_manager.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="theme_id" class="form-label">Theme</label>
                <select name="theme_id" id="theme_id" class="form-select" required>
                    @foreach($themes as $theme)
                        <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" class="form-textarea" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="target" class="form-label">Target</label>
                <select name="target" id="target" class="form-select" required>
                    <option value="subscribers">Subscribers</option>
                    <option value="public">Public</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-input" accept="image/*">
            </div>
            <button type="submit" class="btn-submit">Post Article</button>
        </form>
    </div>
@endsection
