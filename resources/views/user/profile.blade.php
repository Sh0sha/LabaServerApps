
@extends('layout')

@section('title', 'Профиль пользователя')

@section('content')
    <h1>Профиль пользователя</h1>
    <p>Имя: {{ Auth::user()->username }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
    <p>Дата рождения: {{ Auth::user()->birthday }}</p>
    <a href="{{ route('logout') }}">Выход</a>
@endsection
