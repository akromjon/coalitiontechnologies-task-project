<?php

namespace App\Http\Controllers;

use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function index(): Factory|View
    {
        return view('pages.task.index');
    }
}
