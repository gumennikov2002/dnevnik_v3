@extends('layout.main')
@section('title', 'Расписание')
@section('page_title', 'Расписание')
@section('content')
    <div id="schedule" class="d-flex justify-content-between flex-wrap">

        @if (isset($schedules))
            @for ($i = 1;  $i < 7; $i++)
                @switch($i)
                    @case(1)
                    <?php $day_of_week = 'Понедельник'; ?>
                    @break
                    @case(2)
                    <?php $day_of_week = 'Вторник'; ?>
                    @break
                    @case(3)
                    <?php $day_of_week = 'Среда'; ?>
                    @break
                    @case(4)
                    <?php $day_of_week = 'Четверг'; ?>
                    @break
                    @case(5)
                    <?php $day_of_week = 'Пятница'; ?>
                    @break
                    @case(6)
                    <?php $day_of_week = 'Суббота'; ?>
                    @break
                @endswitch

                <div class="card table-responsive animate__animated animate__fadeIn" data-day="{{ $i }}">
                    <div class="card-header d-flex justify-content-between pt-3">
                        <h5>{{ $day_of_week }}</h5>
                    </div>
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Класс</td>
                            <td>Время</td>
                            <td>Кабинет</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($schedules as $item)
                            @if ($item->day_of_week === $i)
                                <tr>
                                    <td>#</td>
                                    <td>{{ $item->classroom }}</td>
                                    <td>{{ $item->from_time }} - {{ $item->to_time }}</td>
                                    <td>{{ $item->cabinet }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endfor
        @endif
    </div>

@endsection