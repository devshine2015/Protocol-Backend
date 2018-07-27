<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use App\Relation;

class NoteCategoryController extends Controller
{
    private $fieldsRequired = ['name'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $urlQuery   = $request->query();
        $user       = Auth::guard('api')->user();

        if (isset($urlQuery['ids'])) {
            $ids = is_array($urlQuery['ids']) ? $urlQuery['ids']: [$urlQuery['ids']];
            $categoryQuery = \App\NoteCategory::whereIn('id', $ids);
        } else {
            $categoryQuery = \App\NoteCategory::where(function($query) {
                $query->where([ 'status' => 0, 'type' => 1,'is_active' => 1 ]);
            });

            if (isset($user)) {
                $categoryQuery = $categoryQuery->orWhere(function($query)  use($user) {
                    $query->where([ 'status' => 0, 'type' => 0,'created_by' => $user['id'],'is_active' => 1 ]);
                });
            }
        }

        $categories = $categoryQuery->get();
        return $this->apiOk($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new \App\NoteCategory;

        foreach ($this->fieldsRequired as $f) {
            $category->$f = $request->$f;
        }
        $category->id               = 15;
        $category->privacy          = 0;
        $category->created_by       = $request->user()['id'];
        $category->save();

        return $this->apiOk($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = \App\NoteCategory::findOrFail($id);
        return $this->apiOk($category);
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
        $user       = $request->user();
        $category   = \App\NoteCategory::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $note['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        foreach ($this->fieldsRequired as $f) {
            $category->$f = $request->$f;
        }

        $category->updated_by = $user['id'];
        $category->save();

        return $this->apiOk($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user       = $request->user();
        $category   = \App\NoteCategory::findOrFail($id);

        if ($user['admin'] !== 1 && $user['id'] !== $note['created_by']) {
            return $this->apiErr(222003, 'Not Authorized');
        }

        $category->status       = 1;
        $category->updated_by   = $user['id'];
        $category->save();

        return $this->apiOk(true);
    }
}
