<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Events\AddNotificationEvent;

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
        $rawQuery    = $request->query();

        if (isset($rawQuery['eids'])) {
            $eids       = is_array($rawQuery['eids']) ? $rawQuery['eids']: [$rawQuery['eids']];
            $elements   = \App\Element::whereIn('id', $rawQuery['eids'])->get();
            return $this->apiOk($elements);
        }

        $queryFields = ['url'];
        $query       = array_filter(
            $rawQuery,
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
    public function elementData(Request $request){
        $data  = $request->all();
        $valid = Validator::make($data, [
            'element_id'     => 'required|exists:elements,id',
            'category_id'    => 'required|exists:categories,id',
            'tags'           => 'required',
            'sub_category'   => 'required'
        ]);
        if ($valid->fails()) {
            return $this->apiErr(22001, $valid->messages(), 422);
        }
        $checkElement = \App\Element::whereId($request->get('element_id'))->first();
        if($checkElement){
            $checkElement->update(['name'=>$request->get('name'),'desc'=>$request->get('desc'),'tags'=>$request->get('tags'),'category_id'=>$request->get('category_id'),'sub_category'=>$request->get('sub_category')]);
            if($checkElement){
                return $this->apiOk($checkElement->fresh());
            }
        }
        return $this->apiErr(22010, 'something is wrong in element update');
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
    public function deleteElement(Request $request, $id){
        $element = \App\Element::with('followElement')->whereId($id)->first();
        // print_r($element);exit;
        if($element->followElement){
            return $this->apiErr(22010, 'You cant delete this element with followers');
        }
        $element->status = 1;
        $element->updated_by   = $request->user()['id'];
        $element->save();
        return $this->apiOk(true);
    }
}
