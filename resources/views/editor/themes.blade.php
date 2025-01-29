@extends('layouts.app')

@section('title', 'Manage Themes')

@section('content')
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            color: #333;
        }

        .btn-create {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-create:hover {
            background-color: #218838;
        }

        .theme-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .theme-card img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        .theme-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>

    <div class="container">
        <div class="header">
            <h1>Manage Themes</h1>
            <a href="{{ route('editor.themes.create') }}" class="btn-create">Create Theme</a>
        </div>

        @foreach($themes as $theme)
            <div class="theme-card">
                <h2>{{ $theme->name }}</h2>
                @if($theme->image)
                    <img src="{{ asset('storage/' . $theme->image) }}" alt="{{ $theme->name }}">
                @else
                    <p>No image available</p>
                @endif
                <p>{{ $theme->description }}</p>
                <p>Managed by: {{ $theme->user->name }}</p>

                <div class="theme-actions">
                    <a href="{{ route('editor.themes.edit', $theme->id) }}" class="btn-edit">Edit</a>
                    <form action="{{ route('editor.themes.delete', $theme->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
