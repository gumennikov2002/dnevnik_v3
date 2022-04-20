@extends('layout.main')
@section('title', 'Статистика')
@section('page_title', 'Статистика')
@section('content')
    <div class="d-flex flex-wrap" id="statistics">
        @foreach ($stat as $item)
            <div class="card">
                <div class="card-body text-center d-flex flex-column">
                    <h2>{{ $item['value'] }}</h2>
                    <span>{{ $item['title'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
@endsection