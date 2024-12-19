<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChecklistController extends Controller
{
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $checklists = Checklist::where('user_id', $user->id)->get();
        return response()->json(['checklists' => $checklists], 200);
    }
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $checklist = Checklist::with('items')->where('user_id', $user->id)->find($id);

        if (!$checklist) {
            return response()->json(['error' => 'Checklist not found'], 404);
        }

        return response()->json(['checklist' => $checklist], 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $checklist = Checklist::create([
            'user_id' => JWTAuth::parseToken()->authenticate()->id,
            'title' => $request->title,
        ]);

        return response()->json(['checklist' => $checklist], 201);
    }
    public function delete($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $checklist = Checklist::where('user_id', $user->id)->find($id);

        if (!$checklist) {
            return response()->json(['error' => 'Checklist not found'], 404);
        }

        $checklist->delete();
        return response()->json(['message' => 'Checklist deleted successfully'], 200);
    }
}
