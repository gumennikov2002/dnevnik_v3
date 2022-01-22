const urlBase = window.location.origin;
const urlPathname = window.location.pathname;

urlPathname !== '/auth' ? sidebarController() : null;

function sidebarController() {
    let sidebar = document.querySelector('#sidebar');
    let sidebarLogo = document.querySelector('#sidebarLogo');
    let sidebarContainer = document.querySelector('#sidebarContainer');

    const menu = {
        'dashboard': {
            'url': '/dashboard',
            'icon': 'area-chart',
            'roles': 'all'
        },
        'schedule': {
            'url': '#',
            'icon': 'calendar',
            'roles': 'student'
        },
        'marks': {
            'url': '#',
            'icon': 'list-alt',
            'roles': 'student'
        }
    };

    Object.keys(menu).forEach((index, value) => {
        sidebarContainer.innerHTML += `
            <li>
                <a href="${menu[index].url}" class="nav-link py-3 border-bottom ${urlPathname == menu[index].url ? 'active' : ''}">
                    <i class="fa fa-${menu[index].icon}"></i>
                </a>
            </li>
        `;
    });
}