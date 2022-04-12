@extends('layout.main')
@section('title', 'Мой класс')
@section('page_title', 'Мой класс')
@section('content')

    <div id="classmates">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Классный руководитель</div>
                    <div class="card-body d-flex">
                        <img src="{{ $classroom_teacher->profile_pic }}" width="64px">
                        <div class="d-flex flex-column" style="margin-left: 20px">
                            <span>{{ $classroom_teacher->full_name }}</span>
                            <p class="d-flex flex-column">
                                <span>Email: {{ $classroom_teacher->email }}</span>
                                <span>Телефон: {{ $classroom_teacher->phone }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Колличество</div>
                    <div class="card-body text-center">
                        <h1>{{ count($classmates) }}</h1>
                        <span>Человек</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Класс</div>
                    <div class="card-body text-center">
                        <h1>{{ $classroom->class }}</h1>
                        <span>Класс</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Одноклассники</div>
                    <div class="card-body" id="classmatesList">
                        @foreach ($classmates as $classmate)
                            <div class="card mb-3">
                                <div class="card-body d-flex">
                                    <img src="{{ $classmate->profile_pic }}" width="64px">
                                    <div class="d-flex flex-column" style="margin-left: 20px">
                                        <span>{{ $classmate->full_name }}</span>
                                        <p class="d-flex flex-column">
                                            <span>Email: {{ $classmate->email }}</span>
                                            <span>Телефон: {{ $classmate->phone }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection