<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function get($id){
        $obj = (object)[
            'id' => $id
        ];
        $params = json_encode($obj);
        $contact = DB::select('EXEC sp_select_contact ?,?',[$params,null]);
        return $contact;
    }
    public function insert(Request $request){
        $obj = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city
        ];
        $params = json_encode($obj);
        $res = DB::statement('EXEC sp_insert_contact ?,?', [$params, null]);
        if($res){
            $time = date('Y-m-d H:i:s');
            DB::connection('mysql')->insert('insert into tbllogs(id, log_message, created_at) values (?, ?, ?)', ['', 'insert success', $time]);
            return "Insert Success";
        }
    }
    public function update($id, Request $request) {
        $obj = [
            'id' => $id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city
        ];
        $params = json_encode($obj);
        $result = DB::select('DECLARE @return_value int EXEC @return_value = sp_update_contact ?,? SELECT res = @return_value', [$params, null]);
        foreach ($result as $r)
            $res = $r->res;
        if($res == 0){
            $time = date('Y-m-d H:i:s');
            $log = DB::connection('mysql')->select('CALL logs_insert(?,?)', ['update success('.$id.')', $time]);
            return "Update Successed";
        } else
            return "Update Failed";
    }
    public function delete($id) {
        $obj = (object)[
            'id' => $id
        ];
        $params = json_encode($obj);
        $res = DB::statement('EXEC sp_delete_contact ?,?',[$params,null]);
        if($res){
            $time = date('Y-m-d H:i:s');
            $log = DB::connection('mysql')->select('CALL logs_insert(?,?)', ['Delete success('.$id.')', $time]);
            return "Delete Success";
        }

    }
}
