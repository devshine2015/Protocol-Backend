<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\ContentReport;
use Validator;

class ContentReportController extends Controller
{
    protected $model;
    public function __construct(ContentReport $contentReport)
    {
        $this->model                        = $contentReport;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data  = $request->only(['user_id','type','type_id','report']);
        $type_id = 'required';
        if($request->type ==0){
            $type_id .='|exists:bridges,id';
        }
        else{
            $type_id .='|exists:notes,id';
        }
        $valid = Validator::make($data, [
            'type'        => 'required',
            'type_id'     => $type_id
        ]);
        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }
        $request['user_id'] = $user->id;
        // print_r($request->all());exit;
        $this->model->fill($request->only('user_id','type_id', 'type', 'report','comment'));
        $this->model->save();
        return $this->apiOk($this->model->fresh());
    }
}
