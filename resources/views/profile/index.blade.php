@extends('layout.main')
@section('title', 'Профиль')
@section('page_title', 'Мой профиль')
@section('content')
<style>
    #profilePage #profile-image-wrapper {
        height: auto;
        padding: 20px;
        border-radius: 15px;
    }
    
    #profilePage #profile-image-wrapper, .widget-element {
        background-color: rgba(75, 64, 170, 0.5);
    }

    #profilePage #profile-pic {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #fff;
    }

    #profilePage #profile-pic-edit {
        position: absolute;
        padding-top: 60px;
        z-index: 9999;
        font-size: 30px;
        background: rgba(0, 0, 0, 0.8);
        width: 150px;
        height: 150px;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0.7;
        backdrop-filter: blur(10px);
        transition: 0.2s ease-in-out;
    }

    .widget-element {
        height: auto;
        border-radius: 15px;
        text-align: center;
        padding: 20px;
    }

    .widget-profile-info {
        width: 23.5%;
        word-wrap: break-word;
    }

    .main-widget {
        width: 49%;
    }

    .widget-element  .main {
        font-size: 64px;
        padding-top: 20px;
    }

    
</style>
<div id="profilePage" class="mb-5">

    <div class="row">
        <div class="col-md-12 text-center" id="profile-image-wrapper">
            <i class="fa fa-edit text-light hidden" id="profile-pic-edit"></i>
            <img src="images/default.png" width="32px" id="profile-pic">
            <h4 class="text-light mt-2">{{ $user->full_name }}</h4>
        </div>
    </div>

    <div class="row mt-4" id="profile-widgets">
        <div class="col-md-12 d-flex justify-content-between">
            <div class="widget-element main-widget">
                <span class="main text-light">5A</span> <br>
                <span class="secondary text-light">Класс</span>
            </div>
            <div class="widget-element main-widget">
                <span class="main text-light">3.5</span> <br>
                <span class="secondary text-light">Средний бал за учебный год</span>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 d-flex justify-content-between widget-profile-info-wrapper">
            <div class="widget-element widget-profile-info">
                <span class="text-light">+{{ $user->phone }}</span>
            </div>
            <div class="widget-element widget-profile-info">
                <span class="text-light">{{ $user->email }}</span>
            </div>
            <div class="widget-element widget-profile-info">
                <span class="text-light">{{ $user->date_of_birth }}</span>
            </div>
            <div class="widget-element widget-profile-info">
                <span class="text-light">{{ $user->age }} лет</span>
            </div>
        </div>
    </div>
</div>
@endsection