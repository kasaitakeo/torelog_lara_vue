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

        if(!$is_favorite) {
            $favorite->storeFavorite($user->id, $log_id);
            return back();
        }
        return back();
    }

    public function destroy(Favorite $favorite)
    {
        $user_id = $favorite->user_id;

        $log_id = $favorite->log_id;

        $favorite_id = $favorite->id;

        $is_favorite = $favorite->isFavorite($user_id, $log_id);

        if($is_favorite) {
            $favorite->destroyFavorite($favorite_id);
            return back();
        }
        
        return back();
    }

}
