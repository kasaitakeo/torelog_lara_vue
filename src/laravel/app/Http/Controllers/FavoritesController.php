<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoritesController extends Controller
{
    /**
     * いいね登録
     * 
     * 
     */
    public function store(Request $request, Favorite $favorite)
    {
        $user = auth()->user();

        $log_id = $request->log_id;

        // $user->idから$log_idに対していいねしているかの状態の確認
        $is_favorite = $favorite->isFavorite($user->id, $log_id);

        // いいねしていない状態ならいいね登録
        if (!$is_favorite) {
            $favorite->storeFavorite($user->id, $log_id);
            return response('', 201);
        }
        return abort(404);;
    }

    /**
     * いいね解除
     * 
     * 
     */
    public function destroy($id, Favorite $favorite)
    {
        $user = auth()->user();

        $log_id = $id;
        
        // $user->idから$log_idに対していいねしているかの状態の確認
        $is_favorite = $favorite->isFavorite($user->id, $log_id);
        
        // いいねしている状態ならいいね解除
        if ($is_favorite) {
            $favorite->where('user_id', $user->id)->where('log_id', $log_id)->delete();
            return;
        }
        
        return abort(404);;
    }

}
