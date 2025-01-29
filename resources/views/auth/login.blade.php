@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="auth-container">
        <h1>Login</h1>
        <form method="POST" action="{{ route('loginSubmit') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Login</button>
            </div>
            <p class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Register</a>
            </p>
        </form>
    </div>
@endsection
