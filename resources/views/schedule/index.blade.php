@extends('layout.main')
@section('title', 'Расписание')
@section('page_title', 'Расписание')
@section('content')

@if ($user_role !== 'Ученик')
    <input class="form-control mb-4" list="classroomsChooseDataListOptions" id="classroomsChooseDataList" placeholder="Выберите класс">
    <datalist id="classroomsChooseDataListOptions">
        @foreach($classrooms as $classroom)
        <option data-value="{{ $classroom->id }}" value="{{ $classroom->class }}">
        @endforeach
    </datalist>
@endif

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
                    @if ($user_role !== 'Ученик')
                        <ion-icon data-bs-toggle="modal" data-day="{{ $i }}" data-bs-target="#addModal" name="add-circle" class="rowAdd text-light" style="cursor: pointer; font-size:24px"></ion-icon>
                    @endif
                </div>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Предмет</td>
                            <td>Учитель</td>
                            <td>Время</td>
                            <td>Кабинет</td>
                            @if ($user_role !== 'Ученик')
                                <td>Управление</td>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $item)
                            @if ($item->day_of_week === $i)
                                <tr>
                                    <td>#</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ $item->teacher }}</td>
                                    <td>{{ $item->from_time }}</td>
                                    <td>{{ $item->cabinet }}</td>
                                    @if ($user_role !== 'Ученик')
                                        <td>
                                            <ion-icon data-day="{{ $item->day_of_week }}" data-record-id="{{ $item->id }}" name="create" data-bs-toggle="modal" data-bs-target="#addModal" class="rowEdit text-primary" style="cursor: pointer; font-size:24px"></ion-icon>
                                            <ion-icon data-record-id="{{ $item->id }}" name="close-circle" data-bs-toggle="modal" data-bs-target="#warningModal" class="rowDelete text-danger" style="margin-left: 10px; cursor: pointer; font-size:24px"></ion-icon>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endfor
    @endif
</div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить запись</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body add-modal-body">
                    <input type="text" class="hidden" id="dayNum" value="">
                    <input type="text" class="hidden" id="recordId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary text-light" id="saveModal">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="warningModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить запись</h5>
                    <button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <span>Вы уверены, что хотите удалить запись?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-danger text-light" id="acceptDelete">Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection