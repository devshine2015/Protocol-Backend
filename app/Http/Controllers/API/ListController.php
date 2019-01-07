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
            function ($key) use($whereInFields) { return in_array($key, $whereInFields); },
            ARRAY_FILTER_USE_KEY
        );
        $builder = $this->model;
        foreach ($queryWhereIn as $key => $v) {
            $arr = is_array($v) ? $v : [$v];
            $builder = $builder->whereIn($key, $arr);
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
        $list = new \App\ListModel;
        if($listId){
            $user   = $request->user();
            $list = $this->model->findOrFail($listId);
            if ($user['admin'] !== 1 && $user['id'] !== $list['created_by']) {
                return $this->apiErr(222003, 'Not Authorized');
            }
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
    public function destroy($listId)
    {
        $user      = Request()->user();
        $list       = $this->model->findOrFail($listId);

        if ($user['admin'] !== 1 && $user['id'] !== $list['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }
        $list->delete();
        return $this->apiOk(true);
    }
}
