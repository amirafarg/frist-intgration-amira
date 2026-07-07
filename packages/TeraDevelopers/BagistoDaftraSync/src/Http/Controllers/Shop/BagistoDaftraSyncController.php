<?php

namespace TeraDevelopers\BagistoDaftraSync\Http\Controllers\Shop;

use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;

class BagistoDaftraSyncController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('bagistodaftrasync::shop.index');
    }
}
