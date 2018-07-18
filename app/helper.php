<?php
 if (!function_exists('uploadFile')) {
    function uploadFile($image,$imgPath)
    {
        if ($image->isValid()) {
            $relativePath = '/public/images/'.$imgPath.'/';
            $fileName = time() . '-' . str_random(15) . '.' . $image->getClientOriginalExtension();
            $destinationPath = base_path();
            $image->move($destinationPath . $relativePath, $fileName);
            $imgName = $fileName;
            return $imgName;
        }
    }
}
 if (!function_exists('get_domain')) {
    function get_domain($url)
    {
      $domain = isset($url['host']) ? $url['host'] : $url;
      if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $domainUrl = explode('.',$regs['domain']);
        return $domainUrl[0];
      }
      return false;
    }
}
if (!function_exists('customPagination')) {
        /**
         * @param string $format
         *
         * @return bool|string
         */
        function customPagination($dataArray)
        {
            $perPage = 8;
            $currentPage = \Request::get('page', 1);
            $offSet = ($currentPage * $perPage) - $perPage;
            $new_collection = array_slice($dataArray->toArray(), $offSet, $perPage, true);
            $finalArray = [];
                foreach ($new_collection as  $value) {
                    $finalArray[] = $value;
                }
            $allData = new \Illuminate\Pagination\LengthAwarePaginator($finalArray, count($dataArray), $perPage,Illuminate\Pagination\Paginator::resolveCurrentPage(), array('path' => Illuminate\Pagination\Paginator::resolveCurrentPath()));
            return $allData;
            }
        }


