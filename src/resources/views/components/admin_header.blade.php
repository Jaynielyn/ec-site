<div class="admin__header">
    <div class="admin__header-container">
        <div class="admin__logo">
            <a href="/admin/dashboard"><img src="{{ asset('img/logo.svg') }}" alt="Sample Logo"></a>
        </div>
        <div class="admin__logout">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="menu__link admin__logout" type="submit">ログアウト</button>
            </form>
        </div>
    </div>
</div>