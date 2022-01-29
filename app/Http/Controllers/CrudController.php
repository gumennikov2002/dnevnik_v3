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

    public function index() {
        $data = $this->CONFIG;
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
}
