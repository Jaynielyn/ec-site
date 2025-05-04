<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    // ダッシュボード画面
    public function dashboard()
    {
        $users = User::with('profile')->paginate(6);
        return view('admin.index', compact('users'));
    }

    // ユーザーを削除
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // ダッシュボードへリダイレクト
        return redirect()->route('admin.dashboard')->with('success', 'ユーザーを削除しました');
    }

    // メール送信フォームの表示
    public function sendMailForm($id)
    {
        $user = User::findOrFail($id);
        return view('admin.mail', ['user' => $user]);
    }


    public function sendMail(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // ユーザーを取得
        $user = User::findOrFail($id);

        // 件名と本文を取得
        $title = $request->input('subject');
        $content = $request->input('body'); // ここで名前を変更

        // メール送信
        Mail::send('admin.user_notification', compact('title', 'content'), function ($mail) use ($user, $title) {
            $mail->to($user->email)->subject($title);
        });

        return redirect()->route('admin.dashboard')->with('success', 'メールを送信しました！');
    }

    public function showUserComments(User $user)
    {
        // ユーザーがコメントした商品の画像を取得
        $images = $user->comments()->with('item')->get()->pluck('item')->unique('id');

        // S3のURLを取得
        foreach ($images as $image) {
            $image->img_url = Storage::disk('s3')->url($image->img_url);
        }

        return view('admin.item_comments', compact('user', 'images'));
    }

    public function showItemDetails($itemId)
    {
        // 商品を取得
        $item = Item::findOrFail($itemId);

        // 商品に関連するコメントを取得
        $comments = $item->comments;

        // コメントの画像URLをS3から取得
        foreach ($comments as $comment) {
            if (!empty($comment->img_url)) {
                // S3から画像URLを取得
                $comment->img_url = Storage::disk('s3')->url($comment->img_url);
            } else {
                // img_urlが空の場合、デフォルト画像を設定
                $comment->img_url = asset('img/noimage.png');
            }
        }

        // 'image' 変数は不要なので削除
        return view('admin.item_details', compact('item', 'comments'));
    }

    public function destroyComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // コメントを削除
        $comment->delete();

        return redirect()->back()->with('success', 'コメントを削除しました。');
    }

}