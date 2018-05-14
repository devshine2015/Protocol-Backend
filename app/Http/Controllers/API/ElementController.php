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
    public function index(Request $request)
    {
        $queryFields = ['url'];
        $query       = array_filter(
            $request->query(),
            function ($k) use($queryFields) { return in_array($k, $queryFields); },
            ARRAY_FILTER_USE_KEY
        );

        $elements = \App\Element::where('status', 0)->where($query)->get();
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

        $fileObj = $request->file('image');

        if (is_null($fileObj)) {
            return $this->apiErr(22100, 'image should be an image file', 422);
        }

        $filePath       = $fileObj->store('public/elements');
        $element->image = $filePath;
        
        $element->created_by = $request->user()['id'];
        $element->save();

        return $this->apiOk($element);
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

        $element->updated_by = $request->user()['id'];
        $element->save();

        return $this->apiOk($element);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $element = \App\Element::findOrFail($id);

        $element->status       = 1;
        $element->updated_by   = $request->user()['id'];
        $element->save();

        return $this->apiOk(true);
    }
}
