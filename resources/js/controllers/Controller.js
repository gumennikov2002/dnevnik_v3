const { default: axios } = require("axios");

const urlBase = window.location.origin;
const urlPathname = window.location.pathname;
axios.defaults.headers.post['X-CSRF-TOKEN'] = document.querySelector('input[name=_token]').value;

urlPathname !== '/auth' ? sidebarController() : null;

function sidebarController() {
    let sidebar = document.querySelector('#sidebar');
    let sidebarLogo = document.querySelector('#sidebarLogo');
    let sidebarContainer = document.querySelector('#sidebarContainer');

    const menu = {
        'dashboard': {
            'url': '/dashboard',
            'icon': 'area-chart',
            'roles': ['all']
        },
        'schedule': {
            'url': '#',
            'icon': 'calendar',
            'roles': ['student']
        },
        'marks': {
            'url': '#',
            'icon': 'list-alt',
            'roles': ['student']
        },
        'users': {
            'url': '/users',
            'icon': 'users',
            'roles': ['director', 'teacher']
        },
        'cabinets': {
            'url': '/cabinets',
            'icon': 'columns',
            'roles': ['director', 'teacher']
        }
    };

    Object.keys(menu).forEach((index, value) => {
        sidebarContainer.innerHTML += `
            <li>
                <a href="${menu[index].url}" class="nav-link py-3 border-bottom ${urlPathname == menu[index].url ? 'active' : ''}">
                    <i class="gradient fa fa-${menu[index].icon}"></i>
                </a>
            </li>
        `;
    });
}

crudController();
function crudController() {
    modalFields = JSON.parse(modalFields);
    let saveBtn = document.querySelector('#modalSaveBtn');
    let dataFields = {};
    let errorElement = document.querySelector('.errors');
    let cleanBtn = document.querySelector('#cleanModal');
    let rowDelete = document.querySelectorAll('.rowDelete');
    let closeModal = document.querySelector('#closeModal');
    let crudTable = document.querySelector('#crudTable');

    cleanBtn.addEventListener('click', () => {
        cleanModal();
    });

    saveBtn.addEventListener('click', () => {
        Object.keys(modalFields).forEach((index, value) => {
            dataFields[index] = document.querySelector(`[name=${index}]`).value;
        });

        hideErrors();

        axios.post(urlPathname + '/create', dataFields)
        .then(() => {
            updateContent();
            closeModal.click();
            cleanModal();
        })
        .catch((error) => {
            Object.keys(error.response.data.errors).forEach((index, value) => {
                let errorInput = document.querySelector(`[name=${index}]`);
                errorInput.style.border = '2px solid #f8d7da';
                errorElement.classList.remove('hidden');
                errorElement.innerHTML += `<span>${error.response.data.errors[index]}</span> <br>`;
            });
        });
    });

    rowDelete.forEach((elem) => {
        elem.addEventListener('click', () => {
            let rowId = elem.parentNode.parentNode.getAttribute('data-id');
            axios.post(urlPathname + '/delete', {'id': rowId});
            elem.parentNode.removeChild;
            elem.parentNode.parentNode.innerHTML = '';
        });
    });

    function hideErrors() {
        errorElement.innerHTML = '';
        errorElement.classList.add('hidden');
    }

    function cleanModal() {
        hideErrors();
        Object.keys(modalFields).forEach((index, value) => {
            document.querySelector(`[name=${index}]`).value = '';

            if (document.querySelector(`[name=${index}]`).tagName == 'SELECT') {
                document.querySelector(`[name=${index}]`).options[0].selected = true;
            }
        });
    }

    function updateContent() {
        axios.get(urlPathname)
        .then((response) => {
            let parser = new DOMParser().parseFromString(response.data, 'text/html');
            crudTable.querySelector('tbody').innerHTML = '';
            Object.values(parser.querySelectorAll('#crudTable tbody tr')).forEach((elem) => {
                crudTable.querySelector('tbody').append(elem);
            });
        })
        .catch(() => {
            console.log('error');
        });
    }
}