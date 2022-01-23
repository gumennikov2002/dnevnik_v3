@extends('layout.main')
@section('title', $title)
@section('page_title', $page_title)
@section('content')

<div class="d-flex justify-content-between">
    <div class="btn btn-info text-light" data-bs-toggle="modal" data-bs-target="#myModel">Добавить</div>
    <input type="text" class="form-control" placeholder="Поиск" style="width: 200px">
</div>

<div class="col-md-12 table-responsive mt-2">
    <table class="table text-center table-hover" id="crudTable">
        <thead class="thead-inverse">
            <tr>
                @foreach($table_heads as $head_title)
                    <td>{{ $head_title }}</td>
                @endforeach
                <td>Действия</td>
            </tr>
        </thead>
        <tbody>
            @foreach($table_body as $record)
                <tr data-id={{ $record->id }}>
                    @foreach($record as $row)
                        <td>{{ $row }}</td>
                    @endforeach
                    <td>
                        <i class="fa fa-edit gradient" style="cursor: pointer;"></i>
                        <i class="fa fa-close rowDelete text-danger" style="margin-left: 10px; cursor: pointer;"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if(empty($table_body))
        <div class="text-center">Нет записей.</div>
    @endif
</div>

<div class="modal fade" id="myModel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $modal_title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal"></button>
            </div>
            <div class="modal-body">
                @foreach($modal_fields as $field)
                    @if($field['field_type'] == 'input')
                        <input type='{{ $field['type'] }}' name='{{ $field['name'] }}' class="form-control mb-2" placeholder='{{ $field['placeholder'] }}'>
                    @endif
                    
                    @if($field['field_type'] == 'textarea')
                        <textarea placeholder='{{ $field['placeholder'] }}' name='{{ $field['name'] }}' cols="30" rows="10" class="form-control mb-2"></textarea>
                    @endif

                    @if($field['field_type'] == 'select')
                        <select class="form-select mb-2" name='{{ $field['name'] }}'>
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
                <button type="button" class="btn btn-info text-light" id="modalSaveBtn">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<script>
    let modalFields = '<?= json_encode($modal_fields); ?>';
</script>
@endsection