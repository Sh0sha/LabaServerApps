
@extends('layout')

@section('title', 'Выход из системы')

@section('content')
    <h1>Выход</h1>
    <p>Вы уверены, что хотите выйти?</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Выйти</button>
    </form>
@endsection
