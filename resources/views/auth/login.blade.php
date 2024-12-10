
@extends('layout')

@section('title', 'Авторизация')

@section('content')
    <h1>Авторизация</h1>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label for="username">Имя пользователя</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Войти</button>
    </form>
    <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
@endsection
