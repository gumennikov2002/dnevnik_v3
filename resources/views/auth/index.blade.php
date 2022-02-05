@extends('layout.main')
@section('content')
@section('title', 'Авторизация')

<div class="container">
    <div id="auth" class="row align-items-center" style="height:100vh">
        <div class="col-md-4">
            <input type="text" name="phone" class="form-control mb-2" placeholder="Номер телефона">
            <input type="password" name="password" class="form-control mb-2" placeholder="Пароль">
            <button class="btn btn-secondary mb-2" name="save" style="float:right" disabled>Войти</button>

            <span class="text-danger mt-1" style="float:left"></span>
        </div>
    </div>
</div>
    
@endsection