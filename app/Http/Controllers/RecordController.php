<?php

namespace App\Http\Controllers;

use App\Models\Record;
class RecordController extends Controller
{
    public function index(): Response
    {
        $records = Record::all();
        return response()->json([
            'records' => $records,
        ]);


    }

}
