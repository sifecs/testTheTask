<?php
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;

function paginate($items, $perPage = 5, $page = null, $options = []){
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}

function resize($srs, $ext) {
    $image = Image::make($srs);
    $image  ->heighten(500)
        ->resizeCanvas($image->width() > 500 ? 500 : $image->width(), $image->height() > 500 ? 500 : $image->width(), 'center', false)
        ->encode($ext, 100)->save();
    return $image;
}


?>
