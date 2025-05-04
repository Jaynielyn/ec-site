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
        if (Storage::disk('public')->exists('profile_images/' . Auth::id() . '.jpg')) {
            $is_image = true;
        }

        $listedItems = Item::where('user_id', Auth::id())->get();
        $purchasedItems = Item::whereIn('id', Sold::where('user_id', Auth::id())->pluck('item_id'))->get();

        // プロフィールを含めてユーザーを取得
        $user = Auth::user()->load('profile');

        return view('mypage', [
            'is_image' => $is_image,
            'listedItems' => $listedItems,
            'purchasedItems' => $purchasedItems,
            'user' => $user,
        ]);
    }

    public function profile()
    {
        $user = Auth::user();

        // ユーザーのプロフィール情報が存在するか確認
        $profile = $user->profile;

        $is_image = false;
        if (Storage::disk('public')->exists('profile_images/' . Auth::id() . '.jpg')) {
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

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // 既存の画像を削除（publicディスクの画像を削除）
            $existingImage = 'profile_images/' . Auth::id() . '.jpg';
            if (Storage::disk('public')->exists($existingImage)) {
                Storage::disk('public')->delete($existingImage);
            }

            // 新しい画像をpublicディスクに保存
            $path = $request->photo->storeAs(
                'profile_images',
                Auth::id() . '.jpg',
                'public'
            );
        }

        $profile = [
            'user_name' => $request->user_name,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building' => $request->building,
            'user_id' => Auth::id()
        ];

        Profile::updateOrCreate(
            ['user_id' => Auth::id()],
            $profile
        );

        return redirect()->route('mypage')->with('success', 'プロフィールが更新されました。');
    }
}
