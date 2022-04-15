@extends('layout.main')
@section('title', $title)
@section('page_title', $page_title)
@if(isset($page_subtitle))
@section('page_subtitle', $page_subtitle)
@endif
@section('content')

<div class="d-flex justify-content-between" id="crudTemplate">
    <div>
        @if($role === 'Админ' || $role === 'Директор' || $role === 'Зам. директора')
            <ion-icon name="add-circle" class="text-primary" data-bs-toggle="modal" data-bs-target="#myModal" id="openModal"></ion-icon>
            <ion-icon name="refresh-circle" class="text-primary" id="crudRefresh"></ion-icon>
        @endif
    </div>
    <div class="hidden" data-bs-toggle="modal" data-bs-target="#myModal" id="editModal"></div>
    <div>
        <ion-icon name="search-circle" class="text-primary" id="goSearch"></ion-icon>
        <input type="text" class="form-control form-control-custom" placeholder="Поиск" style="width: 200px" id="crudSearch" aria-hidden="true">
    </div>
</div>

<div class="col-md-12 table-responsive mt-3">
    <table class="table text-center table-hover animate__animated animate__fadeIn" id="crudTable">
        <thead class="thead-custom">
            <tr>
                @foreach($table_heads as $head_title)
                <td>{{ $head_title }}</td>
                @endforeach
                @if(isset($table_link))
                <td>Ссылка</td>
                @endif
                @if($role === 'Админ' || $role === 'Директор' || $role === 'Зам. директора')
                        <td>Действия</td>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($table_body as $record)
            <tr data-id={{ $record->id }}>
                @foreach(json_decode($record) as $row)
                <td>{{ $row }}</td>
                @endforeach
                @if(isset($table_link))
                <td><a href="{{ $table_link.$record->id }}">Перейти</a></td>
                @endif
                @if($role === 'Админ' || $role === 'Директор' || $role === 'Зам. директора')
                <td>
                    <ion-icon name="create" class="rowEdit text-primary {{ isset($hide_actions) && $hide_actions['edit'] ? 'hidden' : '' }}" style="cursor: pointer; font-size:24px"></ion-icon>
                    <ion-icon name="close-circle" data-bs-toggle="modal" data-bs-target="#warningModal" class="rowDelete text-danger {{ isset($hide_actions) && $hide_actions['delete'] ? 'hidden' : '' }}" style="margin-left: 10px; cursor: pointer; font-size:24px"></ion-icon>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center text-no-records animate__animated animate__zoomIn">Нет записей.</div>

    <div class="custom-paginate">
        {{ $table_body->links() }}
    </div>
</div>

@if($role === 'Админ' || $role === 'Директор' || $role === 'Зам. директора')
<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal"></button>
            </div>
            <div class="modal-body">
                @foreach($modal_fields as $field)
                @if($field['field_type'] == 'text')
                <div class="mb-2">
                    <b>{{ $field['text'] }}:</b> {{ $field['value'] }}
                </div>
                @endif

                @if($field['field_type'] == 'input')
                <input type='{{ $field['type'] }}'  value='{{ isset($field['value']) ? $field['value'] : null }}' name='{{ $field['name'] }}' class="form-control mb-2 data-field {{ isset($field['hidden']) && $field['hidden'] ? 'hidden' : null }}" placeholder='{{ $field['placeholder'] }}'>
                @endif

                @if($field['field_type'] == 'textarea')
                <textarea placeholder='{{ $field['placeholder'] }}' name='{{ $field['name'] }}' cols="30" rows="10" class="data-field form-control mb-2 {{ isset($field['hidden']) && $field['hidden'] ? 'hidden' : null }}"></textarea>
                @endif

                @if($field['field_type'] == 'select')
                @if (isset($field['options']['empty']))
                <select class="form-select mb-2 data-field" name='{{ $field['name'] }}' disabled>
                    @else
                    <select class="form-select mb-2 data-field" name='{{ $field['name'] }}'>
                        @endif
                        @foreach($field['options'] as $option)
                        <option value='{{ $option['value'] }}'>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                    @endif
                    @endforeach

                    <div class="alert alert-danger errors hidden"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cleanModal">Очистить</button>
                <button type="button" class="btn btn-primary text-light" id="modalSaveBtn">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="warningModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Предупреждение</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <p>Вы точно хотите удалить запись?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteAccept">Удалить</button>
            </div>
        </div>
    </div>
</div>
@endif
<script>
    let modalFields = '<?= json_encode($modal_fields); ?>';
</script>
@endsection