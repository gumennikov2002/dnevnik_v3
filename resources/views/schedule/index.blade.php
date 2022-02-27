@extends('layout.main')
@section('title', 'Расписание')
@section('page_title', 'Расписание')
@section('content')
    <div id="schedule" class="d-flex justify-content-between flex-wrap"></div>
        <div class="text-dark" id="chooseClassroom">
            Выберите класс.
        </div>
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить запись</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="hidden" id="dayNum" value="">
                    <input type="text" class="hidden" id="classroomId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary text-light" id="saveModal">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
@endsection
