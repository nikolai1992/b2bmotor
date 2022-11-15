<?php

namespace App\Http\Controllers;

use Session;

class PaginationController extends Controller
{
    //
    public function changePaginationItemsCount($count)
    {
        Session::put("pagination_items_count", $count);
        return 1;
    }
}
