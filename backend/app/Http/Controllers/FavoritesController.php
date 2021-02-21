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
            return back();
        }
        return back();
    }

    public function destroy($id, Favorite $favorite)
    {
        $user = auth()->user();

        $log_id = $id;

        // $favorite_id = $favorite->where('user_id', $user->id)->where('log_id', $log_id)->get('id');
        
        $is_favorite = $favorite->isFavorite($user->id, $log_id);
        
        if ($is_favorite) {
            $favorite->where('user_id', $user->id)->where('log_id', $log_id)->delete();
            // $favorite->destroyFavorite($favorite_id);
            // $favorite->where('id', $favorite_id)->delete();
            return back();
        }
        
        return back();
    }

}
