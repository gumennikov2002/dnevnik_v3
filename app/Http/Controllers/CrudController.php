<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{
    public const MODEL_NAME = '';
    public const CONFIG     = [];
    public const VALIDATE   = [];
    public const REFERENCES = [];
    public const TABLE_LINK = '';

    public function __construct()
    {

    }

    public function index(Request $request) {
        $config = $this->CONFIG;
        $model = $this->MODEL_NAME;
        $model_path = 'App\Models\\';
        $paginate_count = 15;
        $config['table_body'] = $model::paginate($paginate_count);

        unset($model);

        if ($request->has('search')) {
            unset($config['table_body']);
            $model = $this->MODEL_NAME;
            $model_path = 'App\Models\\';
            $record = $model::where('id', '!=', null);
            $search_word = $request->get('search');
            $searchable_rows = array_keys($config['modal_fields']);
            
            for ($i = 0; $i < count($searchable_rows); $i++) {
                if ($config['modal_fields'][$searchable_rows[$i]]['field_type'] === 'text') {
                    unset($searchable_rows[$i]);
                }
            }

            $searchable_rows = array_values($searchable_rows);

            for ($i = 0; $i < count($searchable_rows); $i++) {
                if ($i == 0) {
                    $record->where($searchable_rows[0], 'like', "%$search_word%");
                }

                if ($i > 0) {
                    $record->orWhere($searchable_rows[$i], 'like', "%$search_word%");
                }
            }

            $config['table_body'] = $record->paginate($paginate_count);

            if (!empty($this->REFERENCES)) {
                $reference_keys = array_keys($this->REFERENCES);
                $ref_search_result_ids = [];
                
                foreach($reference_keys as $ref_key) {
                    $ref_model = $model_path.$this->REFERENCES[$ref_key]['model'];
                    $ref_get = $this->REFERENCES[$ref_key]['get'];
                    $ref_search = $ref_model::select('id')->where($ref_get, 'like', "%$search_word%")->get();
                    
                    foreach($ref_search as $result) {
                        $ref_search_result_ids[] = $result->id;
                    }

                    foreach($ref_search_result_ids as $key => $value) {
                        $check = $model::where($ref_key, $value)->count();
                        
                        if ($check === 0) {
                            unset($ref_search_result_ids[$key]);
                        }
                    }

                    $config['table_body'] = $model::whereIn($ref_key, $ref_search_result_ids)->paginate($paginate_count);
                }
            }

            
            if (empty($config['table_body'])) {
                $config['table_body'] = [];
            }
        }

        if (!empty($this->REFERENCES)) {
            $this->references_run($config, $this->REFERENCES, $model_path);
        }

        if (isset($this->TABLE_LINK)) {
            $config['table_link'] = $this->TABLE_LINK;
        }
        
        return view('crud.table', $config);
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

    protected function references_run($config, $references, $model_path) {
        foreach ($config['table_body'] as $index => $record) {
            $reference_keys = array_keys($references);
            
            foreach ($reference_keys as $key) {
                $model = $model_path.$references[$key]['model'];
                $need_get = $references[$key]['get'];
                $get = $model::where('id', $config['table_body'][$index]->$key)->first();
                $config['table_body'][$index]->$key = $get->$need_get;
            }
        }
    }
}
