<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Relation;

class RelationController extends Controller
{
    private $fieldsRequired = ['active_name', 'passive_name'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $relations = \App\Relation::where('status', 0)->get();
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
        
        $relation->created_by = $request->user()['id'];
        $relation->save();

        return $this->apiOk(true);
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
        $relation = \App\Relation::findOrFail($id);

        foreach ($this->fieldsRequired as $f) {
            $relation->$f = $request->$f;
        }

        $relation->updated_by = $request->user()['id'];
        $relation->save();

        return $this->apiOk(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $relation = \App\Relation::findOrFail($id);

        $relation->status       = 1;
        $relation->updated_by   = $request->user()['id'];
        $relation->save();

        return $this->apiOk(true);
    }
}
