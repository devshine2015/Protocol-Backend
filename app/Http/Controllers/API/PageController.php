<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\CheckPageUrl;

class PageController extends Controller
{
    public function search(Request $request)
    {
        $user   = Auth::guard('api')->user();
        $url    = $request->input('url');
        $checkLimit = $request->get('setLimit');
        if (is_null($url)) {
            return $this->apiErr(22010, 'url is required');
        }

        $result = $this->pageInfo($url, $user, true,$checkLimit);
        if($request->has('host_name')){
            $checkHost = \App\CheckPageUrl::where('hostname', 'like', '%' . $request->get('host_name') . '%')->select('z_index')->first();
            if ($checkHost) {
                $result['z_index'] = $checkHost->z_index;
            }
        }
        return $this->apiOk($result);
    }

    public function batchSearch(Request $request) {
        $user   = Auth::guard('api')->user();
        $urls   = $request->input('urls');

        $results  = array_reduce($urls, function($prev, $url) use($user) {
            $prev[$url] = $this->pageInfo($url, $user);
            return $prev;
        }, []);

        return $this->apiOk($results);
    }

    private function pageInfo($url, $user, $withCreatorInfo = false,$checkLimit = false) {
        $elementData = \App\Element::where([ 'status' => 0, 'url' => $url ]);
        if(isset($user)){
            $checkElement = $elementData->with(['followElement'=>function($q)use($user){
                $q->where('user_id',$user->id);
            }])->get();
            $elements = $this->checkFollowElement($checkElement);
        }else{
            $elements = $elementData->where('saveBoard',0)->get();
        }

        $eids     = array_map(function ($v) { return $v['id']; }, $elements->toArray());

        $notesQuery = \App\Note::where([ 'status' => 0 ])->withCount('like')->whereIn('target', $eids);
        $notesQuery     = $this->withPrivacyWhere($notesQuery, $user);
        $bridgesQuery = \App\Bridge::where([ 'status' => 0 ])->withCount('like')->where(function($query) use($eids)  {
            $query->whereIn('from', $eids)->orWhereIn('to', $eids);
        });
        $bridgesQuery   = $this->withPrivacyWhere($bridgesQuery, $user);
        // list data
        $listQuery = \App\ListModel::whereIn('target', $eids);
        $listQuery     = $this->withPrivacyWhere($listQuery, $user);
        $listData = $listQuery->get()->toArray();
        // complete list data
        if ($checkLimit) {
            $noteQuery = $notesQuery->take($checkLimit)->orderBy('created_at','desc')->with('relationName','category');
            $bridgesQuery = $bridgesQuery->take($checkLimit)->orderBy('created_at','desc')->with('relationName','category');
            $listData = $listData->take($checkLimit)->orderBy('created_at','desc')->with('category');
        }
        if(isset($user)){
            $checkNotes = $notesQuery->with(['followUser'=>function($q)use($user){
                $q->where('follower_id',$user->id);
            }])->with(['like'=>function($q)use($user){
                $q->where('user_id',$user->id);
            }])->get();
            $notesData = $this->checkFollowElement($checkNotes);
            $notesData = $this->checkLike($notesData);
        }else{
            $notesData = $notesQuery->get();
        }
        $notes          = $notesData->toArray();
        if(isset($user)){
            $checkBridge = $bridgesQuery->with(['followUser'=>function($q)use($user){
                $q->where('follower_id',$user->id);
            }])->with(['like'=>function($q)use($user){
                $q->where('user_id',$user->id);
            }])->get();
            $bridgeData = $this->checkFollowElement($checkBridge);
            $bridgeData = $this->checkLike($checkBridge);
        }else{
            $bridgeData = $bridgesQuery->get();
        }
        $bridges        = $bridgeData->toArray();
        if ($withCreatorInfo) {
            $uids = array_merge(
                array_map(function($note) { return $note['created_by']; }, $notes),
                array_map(function($bridge) { return $bridge['created_by']; }, $bridges),
                array_map(function($lists) { return $lists['created_by']; }, $listData)
            );
            $users      = \App\User::whereIn('id', $uids)->get()->toArray();
            $userMap    = array_reduce($users, function($prev, $user) {
                $prev[$user['id']] = $user;
                return $prev;
            }, []);
            
            $notes = array_map(function ($note) use($userMap,$checkLimit) {
                $note['created_by_username'] = $userMap[$note['created_by']]['name'];
                if($checkLimit){
                    $note['category_name'] = $note['category']['name'];
                    $note['relation_name'] = $note['relation_name']['name'];
                    $note['category'] =  $note['category']['id'];
                }
                return $note;
            }, $notes);

            $bridges = array_map(function ($bridge) use($userMap,$checkLimit) {
                $bridge['created_by_username'] = $userMap[$bridge['created_by']]['name'];
                if($checkLimit){
                    $bridge['category_name'] = $bridge['category']['name'];
                    $bridge['relation_name'] = $bridge['relation_name']['name'];
                    $bridge['category']        = $bridge['category']['id'];
                }
                return $bridge;
            }, $bridges);
            $listData = array_map(function ($lists) use($userMap,$checkLimit) {
                $lists['created_by_username'] = $userMap[$lists['created_by']]['name'];
                 if($checkLimit){
                    $lists['category_name'] = $lists['category']['name'];
                }
                return $lists;
            }, $listData);
        }

        return [
            'elements'  => $elements,
            'bridges'   => $bridges,
            'notes'     => $notes,
            'lists'     => $listData
        ];
    }
}
