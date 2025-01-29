@extends('layouts.app')

@section('title', 'Edit Theme')

@section('content')
    <style>
        .edit-theme-container {
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
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-textarea {
            resize: vertical;
        }

        .form-submit {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 1rem;
        }

        .form-submit:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="edit-theme-container">
        <h1>Edit Theme: {{ $theme->name }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('theme_manager.themes.update', $theme->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="5" required>{{ old('description', $theme->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-input" accept="image/*">
            </div>
            <button type="submit" class="form-submit">Save Changes</button>
        </form>
    </div>
@endsection
