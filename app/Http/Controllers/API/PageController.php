<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function search(Request $request)
    {
        $user   = Auth::guard('api')->user();
        $url    = $request->input('url');

        if (is_null($url)) {
            return $this->apiErr(22010, 'url is required');
        }

        $result = $this->pageInfo($url, $user, true);
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

    private function pageInfo($url, $user, $withCreatorInfo = false) {
        $elementData = \App\Element::where([ 'status' => 0, 'url' => $url ]);
        if(isset($user)){
            $checkElement = $elementData->with(['followElement'=>function($q)use($user){
                $q->where('user_id',$user->id);
            }])->get();
            $elements = $this->checkFollowElement($checkElement);
        }else{
            $elements = $elementData->get();
        }

        $eids     = array_map(function ($v) { return $v['id']; }, $elements->toArray());

        $notesQuery = \App\Note::where([ 'status' => 0 ])->withCount('like')->whereIn('target', $eids);
        $notesQuery     = $this->withPrivacyWhere($notesQuery, $user);
        if(isset($user)){
            $checkNotes = $notesQuery->with(['followUser'=>function($q)use($user){
                $q->where('follower_id',$user->id);
            }])->get();
            $notesData = $this->checkFollowElement($checkNotes);
        }else{
            $notesData = $notesQuery->get();
        }
        $notes          = $notesData->toArray();

        $bridgesQuery = \App\Bridge::where([ 'status' => 0 ])->withCount('like')->where(function($query) use($eids)  {
            $query->whereIn('from', $eids)->orWhereIn('to', $eids);
        });
        $bridgesQuery   = $this->withPrivacyWhere($bridgesQuery, $user);
        if(isset($user)){
            $checkBridge = $bridgesQuery->with(['followUser'=>function($q)use($user){
                $q->where('follower_id',$user->id);
            }])->get();
            $bridgeData = $this->checkFollowElement($checkBridge);
        }else{
            $bridgeData = $bridgesQuery->get();
        }
        $bridges        = $bridgeData->toArray();

        if ($withCreatorInfo) {
            $uids = array_merge(
                array_map(function($note) { return $note['created_by']; }, $notes),
                array_map(function($bridge) { return $bridge['created_by']; }, $bridges)
            );
            $users      = \App\User::whereIn('id', $uids)->get()->toArray();
            $userMap    = array_reduce($users, function($prev, $user) {
                $prev[$user['id']] = $user;
                return $prev;
            }, []);
            
            $notes = array_map(function ($note) use($userMap) {
                $note['created_by_username'] = $userMap[$note['created_by']]['name'];
                return $note;
            }, $notes);

            $bridges = array_map(function ($bridge) use($userMap) {
                $bridge['created_by_username'] = $userMap[$bridge['created_by']]['name'];
                return $bridge;
            }, $bridges);
        }

        return [
            'elements'  => $elements,
            'bridges'   => $bridges,
            'notes'     => $notes
        ];
    }
}
