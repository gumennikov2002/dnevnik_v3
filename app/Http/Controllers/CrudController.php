<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{
    public const MODEL_NAME = '';
    public const CONFIG     = [];
    public const VALIDATE   = [];

    public function __construct()
    {

    }

    public function index(Request $request) {
        $data = $this->CONFIG;
        $get_ids = $request->get('ids');
        $ids = explode(',', $get_ids);
        $records = json_decode($this->MODEL_NAME::whereIn('id', $ids)->get());
        $all_records = json_decode($this->MODEL_NAME::all());

        if (!isset($_GET['ids'])) {
            $data['table_body'] = $all_records;
        }

        if ($get_ids !== null) {
            $data['table_body'] = $records;

            if (empty($records)) {
                $data['table_body'] = [];
            }
        }

        return view('crud.table', $data);
    }

    public function create(Request $request) {
        $validatedFields = $request->validate($this->VALIDATE);
 
        $record = $this->MODEL_NAME::create($validatedFields);

        if ($record) {
            return response($record);
        }
    }

    public function delete(Request $request) {
        $this->MODEL_NAME::destroy($request->input('id'));
    }

    public function get_fields(Request $request) {
        $record = $this->MODEL_NAME::find($request->input('id'));

        if ($record) {
            return response($record);
        }
    }

    public function update(Request $request) {
        $record = $this->MODEL_NAME::find($request->id);
        $updates = $request->all();
        $record->update($updates);

        if ($record) {
            return response($record);
        }
    }

    public function search(Request $request) {
        $record = $this->MODEL_NAME;
        $search = [];
        $fields_config = array_keys($this->CONFIG['modal_fields']);

        foreach ($fields_config as $field) {
            $search[$field] = $record::select('id')->where($field, 'like', '%'.$request->word.'%')->get();
        }

        if ($search) {
            return response($search);
        }
    }
}
