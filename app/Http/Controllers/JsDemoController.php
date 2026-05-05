<?php

namespace App\Http\Controllers;

class JsDemoController extends Controller
{
    public function table()
    {
        return view('js-demo.table');
    }

    public function datatable()
    {
        return view('js-demo.datatable');
    }

    public function select()
    {
        return view('js-demo.select');
    }
}
