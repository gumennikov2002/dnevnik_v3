@extends('layout.main')
@section('content')
@section('title', 'Авторизация')

<div id="authPage">
    <div class="container">
        <div id="auth" class="row align-items-center pt-5" style="height:100vh">
            <div class="col-lg-4 col-md-2 col-sm-1"></div>
            <div class="col-lg-4 col-md-8 col-sm-10 window">
                <img src="images/snow.png" class="snow" ondragstart="return false;">
                <div class="text-center mb-5 mt-5">
                    <img class="logo-auth mb-2" src="images/logo.png">
                    <h4 class="text-light">Авторизация</h4>
                </div>
                <input type="text" name="phone" class="form-control form-control-custom mb-4" placeholder="Номер телефона">
                <input type="password" name="password" class="form-control form-control-custom mb-4" placeholder="Пароль">
                <button class="btn btn-secondary mb-2" name="save" style="float:right" disabled>Войти</button>
    
                <span class="text-danger mt-2" style="float:left"></span>
            </div>
            <div class="col-lg-4 col-md-2 col-sm-1"></div>
        </div>
    </div>
</div>
    
@endsection