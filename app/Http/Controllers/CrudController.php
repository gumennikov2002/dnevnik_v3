<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{
    public const MODEL_NAME = '';
    public const CONFIG = '';
    public const VALIDATE = '';

    public function __construct()
    {

    }

    public function index(Request $request) {
        $data = $this->CONFIG;
        $ids = explode(',', $request->get('ids'));
        if ($request->get('ids')) {
            $data['table_body'] = json_decode($this->MODEL_NAME::whereIn('id', $ids)->get());
        }

        return view('crud.index', $data);
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

        foreach (array_keys($this->CONFIG['modal_fields']) as $field) {
            $search[$field] = $record::select('id')->where($field, 'like', '%'.$request->word.'%')->get();
        }

        if ($search) {
            return response($search);
        }
    }
}
