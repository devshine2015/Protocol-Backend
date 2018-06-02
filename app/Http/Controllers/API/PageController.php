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

    $result = $this->pageInfo($url);
    return $this->apiOk($result);
  }

  public function batchSearch(Request $request) {
    $urls     = $request->input('urls');

    $results  = array_reduce($urls, function ($prev, $url) {
      $prev[$url] = $this->pageInfo($url);
      return $prev;
    }, []);

    return $this->apiOk($results);
  }

  private function pageInfo($url) {
    $elements = \App\Element::where([ 'status' => 0, 'url' => $url ])->get();
    $eids     = array_map(function ($v) { return $v['id']; }, $elements->toArray());

    $notes    = \App\Note::whereIn('target', $eids)->get();
    $bridges  = \App\Bridge::whereIn('from', $eids)->orWhereIn('to', $eids)->get();

    return [
      'elements'  => $elements,
      'bridges'   => $bridges,
      'notes'     => $notes
    ];
  }
}
