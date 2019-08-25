<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ToDo;
use Session;
use DB;
use DateTime;

// develop by purwanto on 25 august 19

class ToDoController extends Controller {

    public function index(Request $request) {
        return view('todo_view');
    }

    public function save(Request $request) {
        DB::beginTransaction();
        try {
            if (empty($request->todo_id)) {
                $code_new = ToDo::autoNumber();
            } else {
                $code_new = $request->todo_id;
            }

            $todo_list = new ToDo();
            $todo_list->todo_id = $code_new;
            $todo_list->todo_name = $request->todo_name;
            $todo_list->update_at = Date('Y-m-d H:i:s');
            $todo_list->save();
            DB::commit();
            $data = array(
                'message' => 'success',
                'response_code' => 200,
            );
            $data['td_id'] = $todo_list->todo_id;
        } catch (Exception $ex) {
            DB::rollBack();
            $data = array(
                'error' => 1,
                'message' => 'Failed to insert request, Please try again',
                'response_code' => 500,
            );
        }
        return response()->json($data, 200);
    }

    public function getTodoById(Request $request) {
        $id = $request->todo_id;
        $todo_list = ToDo::getDataTodoById($id);
        if (!empty($todo_list)) {
            $row = array();
            foreach ($todo_list as $value) {
                $row[] = array(
                    'id' => $value->id,
                    'todo_id' => $value->todo_id,
                    'todo_name' => $value->todo_name,
                );
            }
            $data = array(
                'message' => 'success',
                'response_code' => 200,
            );
            $data['data'] = $row;
        } else {
            $data = array(
                'message' => 'data empty',
                'response_code' => 200,
            );
        }
        return response()->json($data, 200);
    }

    public function destroy(Request $request) {
        $id = $request->id;
        $todo_list = ToDo::getDataTodoByIncr($id);

        if (!empty($todo_list)) {
            DB::beginTransaction();
            try {
                if ($todo_list->delete()) {
                    DB::commit();
                    $data = array(
                        'message' => 'success',
                        'response_code' => 200,
                    );
                }
            } catch (Exception $ex) {
                DB::rollBack();
                $data = array(
                    'error' => 1,
                    'message' => 'Failed to delete request, Please try again',
                    'response_code' => 500,
                );
            }
        }
        return response()->json($data, 200);
    }

}
