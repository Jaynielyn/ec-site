<header class="header">
    <div class="header__container">
        <div class="header__logo">
            <a href="/"><img src="{{ asset('img/logo.svg') }}" alt="Sample Logo"></a>
        </div>
        <div class="header__search-and-menu">
            <form class="header__search" action="{{ route('search.show') }}" method="GET">
                <input class="header__search-input" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>
            <!-- ハンバーガーメニュー-->
            <div id="menu-toggle" class="header__menu-toggle">
                &#9776;
            </div>
        </div>
        <nav class="header__menu">
            <ul class="header__menu-list">
                @guest
                <li><a class="menu__link" href="/login">ログイン</a></li>
                <li><a class="menu__link" href="/register">会員登録</a></li>
                <li class="sell__btn"><a class="sell__link-btn" href="/sell">出品</a></li>
                @endguest

                @auth
                <li>
                    <form action="/logout" method="post">
                        @csrf
                        <button class="menu__link" type="submit">ログアウト</button>
                    </form>
                </li>
                <li><a class="menu__link" href="/mypage">マイページ</a></li>
                <li class="sell__btn"><a class="sell__link-btn" href="/sell">出品</a></li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.header__menu-toggle');
        const menu = document.querySelector('.header__menu');

        if (menuToggle && menu) {
            menuToggle.addEventListener('click', function() {
                menu.classList.toggle('header__menu--active');
            });
        }
    });
</script>