
@extends('layout')

@section('title', 'Ваши токены')

@section('content')
    <h1>Список ваших токенов</h1>

    @if (Auth::user()->tokens->isEmpty())
        <p>У вас нет активных токенов</p>
    @else
        <ul>
            @foreach (Auth::user()->tokens as $token)
                <li>
                    <strong>Токен:</strong> {{ $token->id }} <br>
                    <strong>Дата создания:</strong> {{ $token->created_at }} <br>
                    <form method="POST" action="{{ route('tokens.revoke', $token->id) }}">
                        @csrf
                        <button type="submit">Отозвать токен</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
