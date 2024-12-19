<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ItemController extends Controller
{
    public function create(Request $request, $checklistId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = JWTAuth::parseToken()->authenticate();

        $checklist = Checklist::where('user_id', $user->id)->find($checklistId);

        if (!$checklist) {
            return response()->json(['error' => 'Checklist not found'], 404);
        }


        $item = Item::create([
            'checklist_id' => $checklistId,
            'content' => $request->content,
            'status' => false,
        ]);

        return response()->json(['item' => $item], 201);
    }

    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json(['item' => $item], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'string|max:255',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        if ($request->has('content')) {
            $item->content = $request->content;
        }

        if ($request->has('status')) {
            $item->status = $request->status;
        }

        $item->save();

        return response()->json(['item' => $item], 200);
    }

    public function updateStatus($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }


        $item->status = !$item->status;  
        $item->save();

        return response()->json(['item' => $item], 200);
    }

    public function delete($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $item->delete();
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }
}
