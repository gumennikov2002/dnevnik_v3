<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{
    public const MODEL_NAME = '';
    public const CONFIG     = [];
    public const VALIDATE   = [];
    public const REFERENCES = [];

    public function __construct()
    {

    }

    public function index(Request $request) {
        $data = $this->CONFIG;
        $get_ids = $request->get('ids');
        $ids = explode(',', $get_ids);
        $model_name = $this->MODEL_NAME;
        $records = json_decode($model_name::whereIn('id', $ids)->get());
        $all_records = json_decode($model_name::all());
        $model_path = 'App\Models\\';

        $data['table_body'] = $model_name::paginate(15);
        
        if (!empty($this->REFERENCES)) {
            foreach ($all_records as $index => $record) {
                $references = $this->REFERENCES;
                $reference_keys = array_keys($references);
                
                foreach ($reference_keys as $key) {
                    $model = $model_path.$references[$key]['model'];
                    $need_get = $references[$key]['get'];
                    $get = $model::where('id', $all_records[$index]->$key)->first();
                    $all_records[$index]->$key = $get->$need_get;
                }
            }
        }

        if ($request->has('ids') && !empty($this->REFERENCES)) {
            foreach ($records as $index => $record) {
                $references = $this->REFERENCES;
                $reference_keys = array_keys($references);
                
                foreach ($reference_keys as $key) {
                    $model = $model_path.$references[$key]['model'];
                    $need_get = $references[$key]['get'];
                    $get = $model::where('id', $records[$index]->$key)->first();
                    $records[$index]->$key = $get->$need_get;
                }
            }
        }

        if ($get_ids !== null) {
            $data['table_body'] = $model_name::whereIn('id', $ids)->paginate(15);

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
            if (!empty($this->REFERENCES)) {
                $references = $this->REFERENCES;
                $reference_keys = array_keys($references);
                
                foreach($reference_keys as $reference_key) {
                    foreach($references as $reference) {
                        $table = strtolower(explode('\\', $this->MODEL_NAME)[2]).'s';
                        $reference_table = strtolower($reference['model']).'s';
                        $search[$field] = $record::join($reference_table, $table.'.'.$reference_key, $reference_table.'.id')
                                            ->where($field, 'like', '%'.$request->word.'%')
                                            ->orWhere($reference['get'], 'like', '%'.$request->word.'%')
                                            ->get([$table.'.*', $reference_table.'.'.$reference['get']]);
                    }
                }
            }
        }

        if ($search) {
            return response($search);
        }
    }
}
