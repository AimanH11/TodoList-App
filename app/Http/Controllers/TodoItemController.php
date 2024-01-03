<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoItem;

class TodoItemController extends Controller
{
    public function index(Request $request)
    {
        $todoItems = TodoItem::all();

        if ($request->expectsJson()) {
            return response()->json($todoItems);
        }

        return view('welcome', compact('todoItems'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $todoItem = TodoItem::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'completed' => false,
            ]);
            return response()->json($todoItem, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create todo item.'], 500);
        }
    }

    public function show($title, $description)
    {
        $todoItems = TodoItem::where('title', $title)
            ->where('description', $description)
            ->get();

        return view('welcome', compact('todoItems'));
    }

    public function update(Request $request, $id)
    {
        // Find the TodoItem by ID
        $todoItem = TodoItem::find($id);

        if (!$todoItem) {
            return response()->json(['error' => 'Todo item not found'], 404);
        }

        // Update the TodoItem with the request data
        $todoItem->update([
            'completed' => true,
        ]);

        return response()->json($todoItem);
    }


    public function destroy($id)
    {
        // Find the TodoItem by ID
        $todoItem = TodoItem::findOrFail($id);

        // Delete the TodoItem
        $todoItem->delete();

        // Return a success message as JSON
        return response()->json(['message' => 'Todo item deleted successfully']);
    }
}
