<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    private $fieldsRequired = [
        'target',
        'title',
        'tags',
        'desc',
        'privacy'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user           = Auth::guard('api')->user();
        $whereInFields  = ['target'];
        $queryWhereIn   = array_filter(
            $request->query(),
            function ($k) use($whereInFields) { return in_array($k, $whereInFields); },
            ARRAY_FILTER_USE_KEY
        );

        $builder = \App\Note::where('status', 0)->with('followUser');

        foreach ($queryWhereIn as $k => $v) {
            $arr = is_array($v) ? $v : [$v];
            $builder = $builder->whereIn($k, $arr);
        }

        $builder = $this->withPrivacyWhere($builder, $user);
        $notes = $builder->get();
        //add isfollowtag
        $notes = $this->checkFollow($notes);

        return $this->apiOk($notes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = new \App\Note;

        foreach ($this->fieldsRequired as $f) {
            $note->$f = $request->$f;
        }
        $note->created_by = $request->user()['id'];
        $note->save();

        return $this->apiOk($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = \App\Note::findOrFail($id);
        return $this->apiOk($note);
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
        $note   = \App\Note::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $note['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        foreach ($this->fieldsRequired as $f) {
            $note->$f = $request->$f;
        }

        $note->updated_by = $user['id'];
        $note->save();

        return $this->apiOk($note);
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
        $note   = \App\Note::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $note['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        $note->status       = 1;
        $note->updated_by   = $user['id'];
        $note->save();

        return $this->apiOk(true);
    }
}
