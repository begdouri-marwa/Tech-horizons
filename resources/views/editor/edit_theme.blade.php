@extends('layouts.app')

@section('title', 'Edit Theme')

@section('content')
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            font-weight: bold;
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
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .current-image {
            margin-bottom: 10px;
        }

        .current-image img {
            max-width: 100%;
            border-radius: 5px;
        }
    </style>

    <div class="container">
        <h1>Edit Theme</h1>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('editor.themes.update', $theme->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $theme->name) }}" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="5" required>{{ old('description', $theme->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="user_id" class="form-label">Theme Manager</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    @foreach($themeManagers as $manager)
                        <option value="{{ $manager->id }}" {{ $manager->id == $theme->user_id ? 'selected' : '' }}>
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Current Image</label>
                @if($theme->image)
                    <div class="current-image">
                        <img src="{{ asset('storage/' . $theme->image) }}" alt="{{ $theme->name }}">
                    </div>
                @else
                    <p>No image available</p>
                @endif
            </div>

            <div class="form-group">
                <label for="image" class="form-label">New Image</label>
                <input type="file" name="image" id="image" class="form-input">
            </div>

            <button type="submit" class="btn-submit">Save Changes</button>
        </form>
    </div>
@endsection
