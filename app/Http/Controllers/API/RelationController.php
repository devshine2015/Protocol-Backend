<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use App\Relation;

class RelationController extends Controller
{
    private $fieldsRequired = ['active_name', 'passive_name'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $urlQuery   = $request->query();
        $user       = Auth::guard('api')->user();

        if (isset($urlQuery['ids'])) {
            $ids = is_array($urlQuery['ids']) ? $urlQuery['ids']: [$urlQuery['ids']];
            $relationsQuery = \App\Relation::whereIn('id', $ids);
        } else {
            $relationsQuery = \App\Relation::where(function($query) {
                $query->where([ 'status' => 0, 'type' => 1, 'is_approved' => 1 ]);
            });

            if (isset($user)) {
                $relationsQuery = $relationsQuery->orWhere(function($query)  use($user) {
                    $query->where([ 'status' => 0, 'type' => 0, 'created_by' => $user['id'],'is_approved' => 1 ]);
                });
            }
        }
         if(isset($urlQuery['locale']) && $urlQuery['locale'] == 'zh'){
            $relations = $relationsQuery->get(['relations.chinese_active_name AS active_name','relations.chinese_passive_name AS passive_name','id', 'is_active','created_at','updated_at','created_by','status','type','inactive','chinese_active_name','chinese_passive_name']);
        }else{

            $relations = $relationsQuery->get();
        }
        return $this->apiOk($relations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $relation = new \App\Relation;

        foreach ($this->fieldsRequired as $f) {
            $relation->$f = $request->$f;
        }
        
        $relation->type         = 0;
        $relation->created_by   = $request->user()['id'];
        $relation->save();

        return $this->apiOk($relation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relation = \App\Relation::findOrFail($id);
        return $this->apiOk($relation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user       = $request->user();
        $relation   = \App\Relation::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $note['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        foreach ($this->fieldsRequired as $f) {
            $relation->$f = $request->$f;
        }

        $relation->updated_by = $user['id'];
        $relation->save();

        return $this->apiOk($relation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user       = $request->user();
        $relation   = \App\Relation::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $note['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        $relation->status       = 1;
        $relation->updated_by   = $user['id'];
        $relation->save();

        return $this->apiOk(true);
    }
}
