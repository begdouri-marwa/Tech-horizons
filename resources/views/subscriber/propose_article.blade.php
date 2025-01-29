@extends('layouts.app')

@section('title', 'Propose an Article')

@section('content')
    <style>
        .propose-article-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .propose-article-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-textarea {
            resize: vertical;
        }

        .form-submit {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1rem;
            cursor: pointer;
        }

        .form-submit:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="propose-article-container">
        <h1 class="propose-article-header">Propose an Article</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('subscriber.submitArticle') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="theme_id" class="form-label">Select Theme</label>
                <select name="theme_id" id="theme_id" class="form-select" required>
                    <option value="">Choose a theme</option>
                    @foreach($subscribedThemes as $theme)
                        <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" rows="5" class="form-textarea" required></textarea>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Image (Optional)</label>
                <input type="file" name="image" id="image" class="form-input" accept="image/*">
            </div>

            <button type="submit" class="form-submit">Submit Article</button>
        </form>
    </div>
@endsection
