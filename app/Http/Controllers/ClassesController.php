<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassesController extends CrudController
{
    public function __construct()
    {
        $this->MODEL_NAME = 'App\Models\Classes';
        parent::__construct();
    }

    public function index(Request $request) {
        if ($request->get('class_id')) {
            $students = User::where('role', 'Ученик')->get();
            $already_exists = [];

            foreach($students as $key => $student) {
                $exists = Classes::select('user_id')->where('user_id', '!=', $students[$key]->id)->get();
                foreach($exists as $class_record) {
                    $already_exists[] = $class_record->user_id;
                }
            }
            
            unset($students);
            $already_exists = array_unique($already_exists);

            $students = User::whereNotIn('id', $already_exists)->where('role', 'Ученик')->get();

            $classroom = Classroom::where('id', $request->get('class_id'))->first();

            $this->CONFIG['modal_fields']['user_id'] = [
                'field_type'  => 'select',
                'name'        => 'user_id',
                'placeholder' => 'ФИО Ученика'
            ];
    
            $this->CONFIG['title'] = $this->CONFIG['page_title'] = "$classroom->class класс"; 
            $this->CONFIG['page_subtitle'] = 'Классный руководитель: '.User::where('id', $classroom->teacher_id)->first()->full_name; 
            $this->CONFIG['modal_fields']['classroom']['value'] = $classroom->class;

            $this->CONFIG['modal_fields']['classroom_id']['value'] = $classroom->id;
            if (count($students) === 0) {
                $this->CONFIG['modal_fields']['user_id']['options'] = [
                    'empty' => [
                        'label' => 'Учеников нет',
                        'value' => null
                    ]
                ];
            } 
                
            if (count($students) > 0){
                foreach ($students as $key => $value) {
                    $this->CONFIG['modal_fields']['user_id']['options'][$key] = [
                        'label' => $value->full_name,
                        'value' => $students[$key]->id
                    ];
                }
            }
        }
        
        $config = $this->CONFIG;
        $model = $this->MODEL_NAME;
        $model_path = 'App\Models\\';
        $paginate_count = 15;
        $config['table_body'] = $model::where('classroom_id', $request->get('class_id'))->paginate($paginate_count);

        unset($model);

        if ($request->has('search')) {
            unset($config['table_body']);
            $model = $this->MODEL_NAME;
            $model_path = 'App\Models\\';
            $record = $model::where('classroom_id', $request->get('class_id'));
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

                    $config['table_body'] = $model::whereIn($ref_key, $ref_search_result_ids)->where('classroom_id', $request->get('class_id'))->paginate($paginate_count);
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
}
