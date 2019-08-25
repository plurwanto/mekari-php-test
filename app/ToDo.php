<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ToDo extends Model {

    public $timestamps = false;
    protected $table = 'todo';
    protected $primaryKey = 'id';

    public static function getDataTodoById($id) {
        $todo_list = self::where('todo_id', '=', $id)
                ->orderBy('todo_id', 'DESC')
                ->get();
        return $todo_list;
    }

    public static function getDataTodoByIncr($id) {
        $todo_list = self::where('id', '=', $id)
                ->first();
        return $todo_list;
    }

    public static function autoNumber() {
        $todo_list = Self::selectRaw('RIGHT(todo_id,4) AS kode')
                ->orderBy('todo_id', 'DESC');

        if ($todo_list->count() <> 0) {
            $data = $todo_list->first();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;
        }

        $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
        $new_code = $kodemax;
        return $new_code;
    }

}
