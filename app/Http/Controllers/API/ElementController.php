<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElementController extends Controller
{
    private $fieldsRequired = [
        'type',
        'url',
        'start_locator',
        'start_offset',
        'end_locator',
        'end_offset',
        'image',
        'text',
        'rect'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elements = \App\Element::where('status', 0)->get();
        return $this->apiOk($elements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $element = new \App\Element;

        foreach ($this->fieldsRequired as $f) {
            $element->$f = $request->$f;
        }
        
        $element->created_by = 0;
        $element->save();

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
        $element = \App\Element::findOrFail($id);
        return $this->apiOk($element);
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
        $element = \App\Element::findOrFail($id);

        foreach ($this->fieldsRequired as $f) {
            $element->$f = $request->$f;
        }

        $element->updated_by = 0;
        $element->save();

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
        $element = \App\Element::findOrFail($id);

        $element->status       = 1;
        $element->updated_by   = 0;
        $element->save();

        return $this->apiOk(true);
    }
}
