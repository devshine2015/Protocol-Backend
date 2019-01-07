<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\ListModel;

class ListController extends Controller
{
    private $fieldsRequired = [
        'element_id',
        'title',
        'tag',
        'desc',
        'privacy'
    ];
    protected $model;
    public function __construct(ListModel $list)
    {
        $this->model = $list;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user           = Auth::guard('api')->user();
        $whereInFields  = ['eleemnt_id'];
        $queryWhereIn   = array_filter(
            $request->query(),
            function ($k) use($whereInFields) { return in_array($k, $whereInFields); },
            ARRAY_FILTER_USE_KEY
        );
        $builder = $this->model;
        foreach ($queryWhereIn as $k => $v) {
            $arr = is_array($v) ? $v : [$v];
            $builder = $builder->whereIn($k, $arr);
        }

        $builder = $this->withPrivacyWhere($builder, $user);
        $lists = $builder->get();
        //add isfollowtag

        return $this->apiOk($lists);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$listId = null)
    {
        if($listId){
            $user   = $request->user();
            $list = $this->model->findOrFail($listId);
            if ($user['admin'] !== 1 && $user['id'] !== $list['created_by']) {
                return $this->apiErr(222003, 'Not Authorized');
            }
        }else{
            $list = new \App\ListModel;
        }

        foreach ($this->fieldsRequired as $f) {
            $list->$f = $request->$f;
        }
        $list->created_by = $request->user()['id'];
        $list->category_id = $request->get('category_id');
        $list->sub_category_id = $request->get('sub_category_id');
        $list->save();

        return $this->apiOk($list);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $listId)
    {
        return $this->store($request,$listId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user      = Request()->user();
        $list       = $this->model->findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $note['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }
        $list->delete();
        return $this->apiOk(true);
    }
}
