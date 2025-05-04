<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class ItemController extends Controller
{
    public function index()
    {
        // いいねが多い順にアイテムを取得
        $images = Item::withCount('likes')
        ->orderBy('likes_count', 'desc')
        ->get();

        // ユーザーが「いいね」したアイテム
        $likedItems = Item::whereHas('likes', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('index', [
            'images' => $images,
            'likedItems' => $likedItems,
        ]);
    }

    public function sell()
    {
        return view('sell');
    }

    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'img_url' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            'category' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
        ]);

        $user = Auth::user();

        // リクエストされた画像ファイルをS3に保存
        $img = $request->file('img_url');
        $path = $img->store('images', 's3');  // S3ディスクに保存

        // アイテムの保存
        $item = Item::create([
            'img_url' => $path,  // S3のパス
            'category' => $request->category,
            'condition' => $request->condition,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => $user->id
        ]);

        // 商品の状態を保存
        DB::table('conditions')->insert([
            'item_id' => $item->id,
            'condition' => $request->condition,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // カテゴリ情報を保存
        $category = Category::firstOrCreate(
            ['category' => $request->category],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // `category_items` テーブルに商品とカテゴリの関連付けを保存
        DB::table('category_items')->insert([
            'item_id' => $item->id,
            'category_id' => $category->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('index');
    }

    //詳細ページ
    public function detail($id)
    {
        $item = Item::find($id);
        // S3のURLを取得
        $imageUrl = Storage::disk('s3')->url($item->img_url);
    

        return view('detail', compact('item', 'imageUrl'));
    }

    public function show($id)
    {
        $item = Item::with(['likes', 'comments'])->findOrFail($id);
        return view('detail', compact('item'));
    }
}
