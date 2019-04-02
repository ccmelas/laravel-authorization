<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {

        if (Gate::allows('create-thread')) {
            $thread = auth()->user()
                            ->threads()
                            ->create($request->all());

            return response()->json(['thread' => $thread], 200);
        }

        return response()->json(['message' => 'Unauthorized Action'], 403);
    }
}
