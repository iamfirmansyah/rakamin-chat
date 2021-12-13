<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaginatorFormatter
{
    public static function format(LengthAwarePaginator $paginator)
    {
        $paginatorData = $paginator->toArray();
        $object = (object) [];

        $object->records = $paginatorData['data'];
        $object->current_page = $paginatorData['current_page'];
        $object->first_page = $paginatorData['from'];
        $object->last_page = $paginatorData['last_page'];
        $object->per_page = $paginatorData['per_page'];
        $object->total = $paginatorData['total'];

        return $object;
    }

    public static function formatFromArray($paginatorData)
    {
        $object = (object) [];

        $object->records = $paginatorData['data'];
        $object->current_page = $paginatorData['current_page'];
        $object->first_page = $paginatorData['from'];
        $object->last_page = $paginatorData['last_page'];
        $object->per_page = $paginatorData['per_page'];
        $object->total = $paginatorData['total'];

        return $object;
    }

    public static function formatCollection(Collection $collection, int $page, int $limit)
    {
        $data = $collection->chunk($limit);
        $paginatorData = $data[$page - 1]->toArray();
        $object = (object) [];

        $object->total = $collection->count();
        $object->records = array_values($paginatorData);
        $object->current_page = $page;
        $object->last_page = ceil($object->total / $limit);

        return $object;
    }
}
