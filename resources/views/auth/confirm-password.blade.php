
@extends('layout')

@section('title', 'Подтверждение пароля')

@section('content')
    <h1>Подтвердите свой пароль</h1>
    <p>Для выполнения этой операции введите свой текущий пароль.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div>
            <label for="password">Текущий пароль</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Подтвердить</button>
    </form>
@endsection
