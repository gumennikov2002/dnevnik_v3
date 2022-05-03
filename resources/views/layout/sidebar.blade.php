<div class="d-flex flex-column flex-shrink-0 bg-light light-shadow" id="sidebar">
    <a href="/" class="d-block p-3 text-decoration-none text-center" id="sidebarLogo">
        <img src="images/logo-w.png" width="32px" ondragstart="return false;">
    </a>
    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center pt-2" id="sidebarContainer"></ul>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="images/default.png" width="24" height="24" class="rounded-circle" id="profile-pic">
        </a>
        <ul class="sidebar-dropdown dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="/profile/logout">Выйти</a></li>
        </ul>
    </div>
</div>

<div id="menu" class="z-top hidden">
    <h2>Меню</h2>
    <ion-icon name="close" id="closeMenu" class="text-light"></ion-icon>
    <ul class="text-center mt-5">

    </ul>
</div>

<div id="bottom-sidebar" class="light-shadow">
    <div class="d-flex justify-content-between">
        <ion-icon name="grid" class="text-light" id="openMenu"></ion-icon>
        <div class="items">

        </div>
        <div class="profile-menu">
            <div class="dropdown">
                <a href="#" class="d-flex mt-1 align-items-center justify-content-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="images/default.png" alt="mdo" width="32" height="32" class="rounded-circle" id="profile-pic">
                </a>
                <ul class="sidebar-dropdown dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/profile/logout">Выйти</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>