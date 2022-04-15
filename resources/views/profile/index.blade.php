@extends('layout.main')
@section('title', 'Профиль')
@section('page_title', 'Мой профиль')
@section('content')
    <div class="row">
        <div class="col-md-5">
            <h5 class="mb-4">Редактирование профиля</h5>
            <form id="editProfile" action="{{ route('profile.update') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="form-group row mb-2">
                    <div class="col-md-3">
                        <span>Фото: </span>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" name="profile_pic" type="file" placeholder="Сменить изображение профиля">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <div class="col-md-3">
                        <span>Телефон: </span>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="text" placeholder="Номер телефона" name="phone" value="{{ $user->phone }}">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <div class="col-md-3">
                        <span>Email: </span>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="email" placeholder="Email" name="email" value="{{ $user->email }}">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span id="changePasswordButton" class="text-primary" data-bs-toggle="modal" data-bs-target="#passwordModal" style="cursor: pointer">Сменить пароль</span>
                    <button type="submit" class="btn btn-primary" id="saveProfileInfoButton">Сохранить</button>
                </div>
            </form>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">{{ $user->full_name }}</h5>
                    <div class="d-flex justify-content-between mt-3">
                        <img src="{{ $user->profile_pic }}" width="128px" height="128px" style="border-radius: 50%" id="profile-pic">
                        <div class="profile-info" style="margin-left: 25px;">
                            @if($user->role === 'Ученик')
                            <span>Класс: {{ $user->classroom }}</span> <br>
                            <span>Классный руководитель: {{ $user->classroom_teacher }}</span> <br>
                            @endif
                            <span>Дата рождения: {{ $user->date_of_birth }} ({{ $user->age }} лет).</span> <br>
                            <span>Номер телефона: {{ $user->phone }}</span> <br>
                            <span>Email: {{ $user->email }}</span> <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('profile.change_password') }}" method="POST">
                @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Изменение пароля</h5>
                    <button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mb-2">
                        <div class="col-md-4">
                            <span>Старый пароль:</span>
                        </div>
                        <div class="col-md-8">
                            <input type="password" class="form-control" placeholder="Старый пароль" name="old_password">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <div class="col-md-4">
                            <span>Новый пароль:</span>
                        </div>
                        <div class="col-md-8">
                            <input type="password" class="form-control" placeholder="Новый пароль" name="new_password">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <div class="col-md-4">
                            <span>Повторите пароль:</span>
                        </div>
                        <div class="col-md-8">
                            <input type="password" class="form-control" placeholder="Повторите новый пароль" name="repeat_password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary text-light">Сохранить</button>
                </div>
            </div>
            </form>
        </div>
@endsection