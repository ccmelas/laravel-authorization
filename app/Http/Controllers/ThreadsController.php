<?php

namespace App\Http\Controllers;

use App\Models\Thread;
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

    public function update(Request $request, Thread $thread)
    {
        if ($request->user()->can('update', $thread)) {
            $thread->update($request->all());

            return response()->json(['thread' => $thread->fresh()], 200);
        }

        return response()->json(['message' => 'Unauthorized Action'], 403);

    }
}
