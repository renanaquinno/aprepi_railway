<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'likeable_id' => 'required|integer',
            'likeable_type' => 'required|string',
        ]);

        $user = Auth::user();

        $likeableType = $request->input('likeable_type');
        $likeableId = $request->input('likeable_id');

        // Normalizar o namespace
        if (!str_starts_with($likeableType, 'App\Models\\')) {
            $likeableType = 'App\Models\\' . $likeableType;
        }

        // Verifica se o like já existe
        $like = Like::where('user_id', $user->id)
            ->where('likeable_id', $likeableId)
            ->where('likeable_type', $likeableType)
            ->first();

        if ($like) {
            // Remove like
            $like->delete();
            $liked = false;
        } else {
            // Cria like
            Like::create([
                'user_id' => $user->id,
                'likeable_id' => $likeableId,
                'likeable_type' => $likeableType,
            ]);
            $liked = true;
        }

        return response()->json(['liked' => $liked]);
    }
}
