
@extends('layout')

@section('title', 'Авторизация')

@section('content')
    <h1>Регистрация</h1>
  
    <!-- Сообщения об успехе или ошибках -->
    <div id="messages"></div>

    <!-- Форма регистрации -->
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <div class="error" id="username-error"></div>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <div class="error" id="email-error"></div>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <div class="error" id="password-error"></div>
        </div>

        <div class="form-group">
            <label for="c_password">Confirm Password:</label>
            <input type="password" id="c_password" name="c_password" required>
            <div class="error" id="c_password-error"></div>
        </div>

        <div class="form-group">
            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" required>
            <div class="error" id="birthday-error"></div>
        </div>

        <button type="submit">Register</button>
    </form>

    @endsection