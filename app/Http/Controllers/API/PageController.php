<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
  public function search(Request $request)
  {
    $url = $request->input('url');

    if (is_null($url)) {
      return $this->apiErr(22010, 'url is required');
    }

    $elements = \App\Element::where([ 'status' => 0, 'url' => $url ])->get();
    $eids     = array_map(function ($v) { return $v['id']; }, $elements->toArray());

    $notes    = \App\Note::whereIn('target', $eids)->get();
    $bridges  = \App\Bridge::whereIn('from', $eids)->orWhereIn('to', $eids)->get();

    $result   = [
      'elements'  => $elements,
      'bridges'   => $bridges,
      'notes'     => $notes
    ];

    return $this->apiOk($result);
  }
}
