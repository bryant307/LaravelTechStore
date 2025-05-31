<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class OptionController
{
    public function index(Request $request)
    {

        return view('admin.options.index');
    }
}
