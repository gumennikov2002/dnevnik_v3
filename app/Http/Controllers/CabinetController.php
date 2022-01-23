<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Кабинеты',
            'page_title' => 'Кабинеты',
            'modal_title' => 'Добавить кабинет',
            'table_heads' => ['#', 'Номер кабинета'],
            'table_body'  => json_decode(Cabinet::all()),
            'modal_fields' => [
                'num' => [
                    'field_type' => 'input',
                    'type' => 'text',
                    'name' => 'num',
                    'placeholder' => 'Номер кабинета'
                ]
            ]
        ];

        return view('crud.index', $data);
    }

    function create(Request $request) {
        $validatedFields = $request->validate([
            'num' => 'required'
        ]);
 
        $cabinet = Cabinet::create($validatedFields);

        if ($cabinet) {
            return response(true);
        }
    }

    public function delete(Request $request) {
        Cabinet::destroy($request->input('id'));
    }
}
