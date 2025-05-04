<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;

class SearchController extends Controller
{
    public function show(Request $request)
    {
        $keyword = $request->input('keyword');
        $items = Item::query();

        if ($keyword) {
            $items = $items->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('description', 'LIKE', "%{$keyword}%")
                ->orWhere('category', 'LIKE', "%{$keyword}%")
                ->orWhere('condition', 'LIKE', "%{$keyword}%");
            })->get();
        } else {
            $items = collect();
        }

        return view('search_result', ['items' => $items]);
    }

    // おすすめアイテムを取得するメソッド
    public function recommendations()
    {
        $user = Auth::user();
        $recommendations = collect();

        if ($user) {
            // ユーザーがいいねした商品のカテゴリーを取得
            $likedCategories = Item::whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->pluck('category')->unique();

            // 類似カテゴリーの商品を取得
            if ($likedCategories->isNotEmpty()) {
                $recommendations = Item::whereIn('category', $likedCategories)
                ->withCount('likes')
                    ->orderBy('likes_count', 'desc')  // orderByDescの代わりにorderByを使用
                    ->take(5)
                    ->get();
            }
        }

        // 全体的な人気アイテムを取得
        $popularItems = Item::withCount('likes')
        ->orderBy('likes_count', 'desc')      // ここも同様に修正
        ->whereNotIn('id', $recommendations->pluck('id'))
        ->take(10)
        ->get();

        // 両方の結果をマージ
        $recommendations = $recommendations->merge($popularItems)->take(10);

        return view('index', [
            'images' => $recommendations
        ]);
    }
}
