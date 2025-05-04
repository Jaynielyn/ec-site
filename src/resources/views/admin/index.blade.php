@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
@endsection
<x-admin_header></x-admin_header>

@section('main')
<div class="container">
    <h1>ユーザー管理</h1>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('admin.users.comments', $user->id) }}">
                        {{ $user->profile->user_name ?? 'No Name' }}
                    </a>
                </td>

                <td class="admin__form-td">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="delete__btn" type="submit">削除</button>
                    </form>
                    <a href="{{ route('admin.users.sendMailForm', $user->id) }}">
                        <button class="mail__btn" type="button">メール</button>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $users->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection