<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassesController extends CrudController
{
    public function __construct()
    {
        parent::__construct();
        $this->MODEL_NAME = 'App\Models\Classes';
        $this->CONFIG = [
            'title'        => '',
            'page_title'   => '',
            'table_heads'  => ['#', 'Класс', 'ФИО Ученика', 'Классный руководитель'],
            'table_body'   => [],
            'modal_fields' => [
                'classroom_teacher' => [
                    'field_type'  => 'text',
                    'text'  => 'Классный руководитель',
                    'value' => ''
                ],
                'classroom' => [
                    'field_type' => 'text',
                    'text'  => 'Класс',
                    'value' => ''
                ],
                'teacher_id' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'teacher_id',
                    'placeholder' => 'Классный руководитель',
                    'value'       => '',
                    'hidden'      => true
                ],
                'classroom_id' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'classroom_id',
                    'placeholder' => 'Класс',
                    'value'       => '',
                    'hidden'      => true
                ]
            ]
        ];

        $this->VALIDATE = [
            'user_id'      => 'required',
            'teacher_id'   => 'required',
            'classroom_id' => 'required',
        ];

        $this->REFERENCES = [
            'classroom_id' => [
                'model' => 'Classroom',
                'get'   => 'class'
            ],
            'teacher_id' => [
                'model' => 'User',
                'get'   => 'full_name'
            ],
            'user_id'    => [
                'model' => 'User',
                'get'   => 'full_name'
            ]
        ];
    }

    public function index(Request $request) {

        if ($request->get('class_id')) {
            $students = User::where('role', 'Ученик')->get();
            $classroom = Classroom::where('id', $request->get('class_id'))->first();
            $classroom_teacher = User::where('id', $classroom->teacher_id)->first();

            $this->CONFIG['modal_fields']['user_id'] = [
                'field_type'  => 'select',
                'name'        => 'user_id',
                'placeholder' => 'ФИО Ученика'
            ];
    
            $this->CONFIG['title'] = $this->CONFIG['page_title'] = "$classroom->class класс"; 
            $this->CONFIG['modal_fields']['classroom_teacher']['value'] = $classroom_teacher->full_name;
            $this->CONFIG['modal_fields']['classroom']['value'] = $classroom->class;
            
            $this->CONFIG['modal_fields']['teacher_id']['value'] = $classroom_teacher->id;
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
                        'value' => $value->id
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
            $record = $model::where('classroom_id', $request->get('class_id')); //null
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
