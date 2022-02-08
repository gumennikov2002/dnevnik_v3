<div class="d-flex flex-column flex-shrink-0 bg-light light-shadow" id="sidebar">
    <a href="/" class="d-block p-3 text-decoration-none text-center" id="sidebarLogo">
        <img src="images/logo.png" width="50px" ondragstart="return false;">
    </a>
    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center" id="sidebarContainer"></ul>
    <div class="dropdown border-top">
        <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="images/default.png" width="24" height="24" class="rounded-circle" id="profile-pic">
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="/profile/logout">Выйти</a></li>
        </ul>
    </div>
</div>

<div id="menu" class="z-top hidden">
    <h2>Меню</h2>
    <i class="fa fa-close" id="closeMenu"></i>
    <ul class="text-center mt-5">

    </ul>
</div>

<div id="bottom-sidebar" class="bg-light light-shadow">
    <div class="d-flex justify-content-between">
        <i class="fa fa-bars gradient" id="openMenu"></i>
        <div class="items">

        </div>
        <div class="profile-menu">
            <div class="dropdown">
                <a href="#" class="d-flex mt-1 align-items-center justify-content-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="images/default.png" alt="mdo" width="32" height="32" class="rounded-circle" id="profile-pic">
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/profile/logout">Выйти</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>