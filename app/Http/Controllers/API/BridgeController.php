<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BridgeController extends Controller
{
    private $fieldsRequired = [
        'from',
        'to',
        'relation',
        'tags',
        'desc'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rawQuery = $request->query();
        $builder  = $bridges = \App\Bridge::where('status', 0);

        if (array_key_exists('eids', $rawQuery)) {
            $builder = $builder
                        ->whereIn('from', $rawQuery['eids'])
                        ->orWhereIn('to', $rawQuery['eids']);
        }

        $bridges = $builder->get();
        return $this->apiOk($bridges);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bridge = new \App\Bridge;

        foreach ($this->fieldsRequired as $f) {
            $bridge->$f = $request->$f;
        }
        
        $bridge->created_by = $request->user()['id'];
        $bridge->save();

        return $this->apiOk($bridge);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bridge = \App\Bridge::findOrFail($id);
        return $this->apiOk($bridge);
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
        $bridge = \App\Bridge::findOrFail($id);

        foreach ($this->fieldsRequired as $f) {
            $bridge->$f = $request->$f;
        }

        $bridge->updated_by = $request->user()['id'];
        $bridge->save();

        return $this->apiOk($bridge);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bridge = \App\Bridge::findOrFail($id);

        $bridge->status       = 1;
        $bridge->updated_by   = $request->user()['id'];
        $bridge->save();

        return $this->apiOk(true);
    }
}
