<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Sold;
use App\Models\User;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    public function mypage()
    {
        $is_image = false;
        if (Storage::disk('s3')->exists('profile_images/' . Auth::id() . '.jpg')) {
            $is_image = true;
        }

        // ログインユーザーの出品した商品を取得
        $listedItems = Item::where('user_id', Auth::id())->get();

        // ログインユーザーが購入した商品を取得
        $purchasedItems = Item::whereIn('id', Sold::where('user_id', Auth::id())->pluck('item_id'))->get();

        // 現在のユーザーを取得
        $user = Auth::user();

        return view('mypage', [
            'is_image' => $is_image,
            'listedItems' => $listedItems,
            'purchasedItems' => $purchasedItems,
            'user' => $user, // ユーザー情報を渡す
        ]);
    }

    public function profile()
    {
        $user = Auth::user();

        // ユーザーのプロフィール情報が存在するか確認
        $profile = $user->profile;

        $is_image = false;
        if (Storage::disk('s3')->exists('profile_images/' . Auth::id() . '.jpg')) {
            $is_image = true;
        }

        return view('profile', [
            'is_image' => $is_image,
            'user_name' => $profile ? $profile->user_name : $user->name,
            'postcode' => $profile ? $profile->postcode : '',
            'address' => $profile ? $profile->address : '',
            'building' => $profile ? $profile->building : '',
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2500',
        ]);

        // ユーザー情報の更新
        $user = Auth::user();
        $id = Auth::id();

        // プロフィール画像が選択された場合
        if ($request->hasFile('photo')) {
            // 既存の画像を削除（S3上の画像を削除）
            Storage::disk('s3')->delete('profile_images/' . Auth::id() . '.jpg');

            // 新しい画像をS3に保存（公開アクセス付き）
            $path = $request->photo->storeAs(
                'profile_images',
                Auth::id() . '.jpg',
                [
                    'disk' => 's3',
                    'visibility' => 'public', // 公開アクセスを許可
                ]
            );
        }

        $profile = [
            'user_name' => $request->user_name,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building' => $request->building,
            'user_id' => Auth::id()
        ];

        // プロフィールを更新（なければ新規作成）
        Profile::updateOrCreate(
            ['user_id' => Auth::id()],
            $profile
        );

        return redirect()->route('mypage')->with('success', 'プロフィールが更新されました。');
    }

}
