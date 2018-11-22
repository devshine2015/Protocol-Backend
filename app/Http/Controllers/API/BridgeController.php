<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\AddPointEvent;
use App\Events\AddNotificationEvent;
use App\BridgeCross;

class BridgeController extends Controller
{
    private $fieldsRequired = [
        'from',
        'to',
        'relation',
        'tags',
        'desc',
        'privacy',
        'category',
        'sub_category'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user     = Auth::guard('api')->user();
        $rawQuery = $request->query();
        $builder  = $bridges = \App\Bridge::where('status', 0)->with(['followUser'=>function($q)use($user){
            $q->where('follower_id',$user->id);
        }]);

        if (array_key_exists('eids', $rawQuery)) {
            $builder = $builder
                        ->whereIn('from', $rawQuery['eids'])
                        ->orWhereIn('to', $rawQuery['eids']);
        }

        $builder = $this->withPrivacyWhere($builder, $user);
        $bridges = $builder->get();
        //add isfollowtag
        $bridges = $this->checkFollow($bridges);
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
        $bridge->sub_category = $request->get('sub_category');
        $bridge->save();
         // add point
        $pointData['user_id'] = $request->user()['id'];
        $pointData['type'] = 1;
        $pointData['type_id'] = $bridge->id;
        $pointData['point'] = 100;
        event(new AddPointEvent($pointData));
        return $this->apiOk($bridge);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function  bridgeCross(Request $request){
        $bridgeList = $request->all();
        $addbridgeCross = \App\BridgeCross::insert($bridgeList['data']);
        return $this->apiOk($addbridgeCross);
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
        $user   = $request->user();
        $bridge = \App\Bridge::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $bridge['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        foreach ($this->fieldsRequired as $f) {
            $bridge->$f = $request->$f;
        }
        $bridge->sub_category = $request->get('sub_category');
        $bridge->updated_by = $user['id'];
        $bridge->save();

        return $this->apiOk($bridge);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user   = $request->user();
        $bridge = \App\Bridge::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $bridge['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        $bridge->status       = 1;
        $bridge->updated_by   = $user['id'];
        $bridge->save();

        return $this->apiOk(true);
    }
}
