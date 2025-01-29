@extends('layouts.app')

@section('title', 'Edit Article')

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

        .form-textarea {
            resize: vertical;
        }

        .btn-submit {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="form-container">
        <h1>Edit Article</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('theme_manager.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $article->title) }}" required>
            </div>
            <div class="form-group">
                <label for="theme_id" class="form-label">Theme</label>
                <select name="theme_id" id="theme_id" class="form-select" required>
                    @foreach($themes as $theme)
                        <option value="{{ $theme->id }}" {{ $theme->id == $article->theme_id ? 'selected' : '' }}>
                            {{ $theme->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" class="form-textarea" rows="5" required>{{ old('content', $article->content) }}</textarea>
            </div>
            <div class="form-group">
                <label for="target" class="form-label">Target</label>
                <select name="target" id="target" class="form-select" required>
                    <option value="subscribers" {{ $article->target === 'subscribers' ? 'selected' : '' }}>Subscribers</option>
                    <option value="public" {{ $article->target === 'public' ? 'selected' : '' }}>Public</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-input" accept="image/*">
                @if($article->image)
                    <p>Current Image:</p>
                    <img src="{{ asset('storage/' . $article->image) }}" alt="Article Image" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 5px;">
                @endif
            </div>
            <button type="submit" class="btn-submit">Save Changes</button>
        </form>
    </div>
@endsection