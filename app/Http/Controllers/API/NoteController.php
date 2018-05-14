<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    private $fieldsRequired = [
        'target',
        'title',
        'tags',
        'desc'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = \App\Note::where('status', 0)->get();
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
        $note = \App\Note::findOrFail($id);

        foreach ($this->fieldsRequired as $f) {
            $note->$f = $request->$f;
        }

        $note->updated_by = $request->user()['id'];
        $note->save();

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
        $note = \App\Note::findOrFail($id);

        $note->status       = 1;
        $note->updated_by   = $request->user()['id'];
        $note->save();

        return $this->apiOk(true);
    }
}
