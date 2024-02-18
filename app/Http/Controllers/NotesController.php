<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::where('user_id', Auth::user()->id)->get();

        return response()->json($notes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $note = new Note;
        $note->user_id = Auth::user()->id;
        $note->content = $request->content;
        $note->save();

        return response()->json([
            'message' => 'Nota creada'
        ], 200);
    }
}
