<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoritesController extends Controller
{
    //
    public function store(Request $request, Favorite $favorite)
    {
        $user = auth()->user();
        
        $log_id = $request->log_id;

        $is_favorite = $favorite->isFavorite($user->id, $log_id);

        if (!$is_favorite) {
            $favorite->storeFavorite($user->id, $log_id);
            return;
        }
        return;
    }

    public function destroy($id, Favorite $favorite)
    {
        $user = auth()->user();

        $log_id = $id;
        
        $is_favorite = $favorite->isFavorite($user->id, $log_id);
        
        if ($is_favorite) {
            $favorite->where('user_id', $user->id)->where('log_id', $log_id)->delete();
            return;
        }
        
        return;
    }

}
