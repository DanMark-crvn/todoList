<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Routing\Controller;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Todo::where('user_id', auth()->id()) // Only fetch Todos for the authenticated user
            ->latest()
            ->get()
            ->map(function ($todo) {
                return [
                    'id' => $todo->id,
                    'url' => url(Storage::url($todo->path)),
                    'title' => $todo->title,
                    'label' => $todo->label,
                ];
            });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'image' => ['required', 'file', 'image','mimes:jpeg,png,jpg'],
            'title' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('image')->store('images', 'public');

        $image = Todo::create([
            'path' => $path,
            'title'=> $request->title,
            'label' => $request->label,
            'user_id' => auth()->id(), // Associate the Todo with the authenticated user
        ]);

        return response($image, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
        // if ($todo->user_id !== auth()->id()) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
    
        // return response()->json($todo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        //
        // Ensure the authenticated user owns the Todo
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'image' => ['nullable', 'file', 'image','mimes:jpeg,png,jpg'],
            'title' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        // Check if a new image file is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($todo->path) {
                Storage::disk('public')->delete($todo->path);
            }

            // Store the new image
            $path = $request->file('image')->store('images', 'public');
            $todo->path = $path; // Update the path with the new image
        }

        // Update the other fields
        $todo->title = $request->title;
        $todo->label = $request->label;

        // Save the changes
        $todo->save();

        // return response($todo, 200);
        return response()->json([
            'id' => $todo->id,
            'url' => url(Storage::url($todo->path)), // Ensure the correct URL is returned
            'title' => $todo->title,
            'label' => $todo->label,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        //
        // Ensure the authenticated user owns the Todo
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if($todo->path) {
            Storage::disk('public')->delete($todo->path);
        }
        $todo->delete();
        
        return response(null, 204);
    }
    public function __construct()
    {
        $this->middleware('auth');
    }
}
