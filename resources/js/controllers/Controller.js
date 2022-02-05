const { default: axios } = require("axios");

const urlBase = window.location.origin;
const urlPathname = window.location.pathname;
axios.defaults.headers.post['X-CSRF-TOKEN'] = document.querySelector('input[name=_token]').value;

routeManager();

function routeManager() {
    urlPathname !== '/auth' ? sidebarController() : null;
    urlPathname.split('_')[1] === 'crud' ? crudController() : null;
}


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
            'url': '/users_crud',
            'icon': 'users',
            'roles': ['director', 'teacher']
        },
        'cabinets': {
            'url': '/cabinets_crud',
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

function crudController() {
    modalFields = JSON.parse(modalFields);
    let saveBtn = document.querySelector('#modalSaveBtn');
    let dataFields = {};
    let errorElement = document.querySelector('.errors');
    let cleanBtn = document.querySelector('#cleanModal');
    let modal = document.querySelector('#myModal');
    let closeModal = document.querySelector('#closeModal');
    let openModal = document.querySelector('#openModal');
    let crudTable = document.querySelector('#crudTable');
    let crudSearch = document.querySelector('#crudSearch');

    //Кнопка "Очистить"
    cleanBtn.addEventListener('click', () => {
        cleanModal();
    });

    //Кнопка "Сохранить"
    saveBtn.addEventListener('click', () => {
        let url = null;
        Object.keys(modalFields).forEach((index, value) => {
            dataFields[index] = document.querySelector(`[name=${index}]`).value;
            url = urlPathname + '/create';
            if (modal.querySelector(`input[name='id']`)) {
                url = urlPathname + '/update';
                dataFields['id'] = modal.querySelector(`input[name='id']`).value;
            }
        });

        hideErrors();

        axios.post(url, dataFields)
        .then((response) => {
            closeModal.click();
            cleanModal();
            updateContent();
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

    //Отслеживание кликов динамических объектов
    document.addEventListener('click', (e) => {
        //Удалить запись
        if (e.target && e.target.classList.contains('rowDelete')) {
            let rowId = e.target.parentNode.parentNode.getAttribute('data-id');
            axios.post(urlPathname + '/delete', {'id': rowId});
            e.target.parentNode.removeChild;
            e.target.parentNode.parentNode.innerHTML = '';
        }

        //Получить данные записи в модалке
        if (e.target && e.target.classList.contains('rowEdit')) {
            let rowId = e.target.parentNode.parentNode.getAttribute('data-id');
            axios.post(urlPathname + '/get_fields', {'id': rowId})
            .then((response) => {
                openModal.click();
                let recordId = modal.querySelector(`input[name='id']`)
                if (recordId) {
                    recordId.parentNode.removeChild(recordId);
                }

                modal.querySelector('.modal-body').innerHTML += `<input type='text' name='id' value='${rowId}' class='hidden record-id'>`;
                Object.keys(response.data).forEach((index) => {
                    let fields = modal.querySelectorAll('.data-field');
                    fields.forEach((e) => {
                        if (e.tagName === 'INPUT' && e.getAttribute('name') === index) {
                            e.value = response.data[index];
                        }
                        
                        if (e.tagName === 'TEXTAREA' && e.getAttribute('name') === index) {
                            e.innerHTML = response.data[index];
                        }

                        if (e.tagName === 'SELECT' && e.getAttribute('name') === index) {
                            e.value = response.data[index];
                        }
                    });
                });
            });
        }
    });

    //Поиск по странице
    crudSearch.addEventListener('keyup', () => {
        let ids = [];
        setTimeout(() => {}, 1500);
        axios.post(urlPathname + '/search', {'word': crudSearch.value})
        .then((response) => {
            Object.keys(response.data).forEach((index) => {
                if (response.data[index].length > 0) {
                    let recordIds = response.data[index];
                    Object.keys(recordIds).forEach((i) => {
                        ids.push(recordIds[i].id);
                    });
                }
            });

            updateContent(ids);
        });

        if (crudSearch.value === '' || crudSearch.value === ' ') {
            updateContent();
        }

    });

    //Скрыть ошибки
    function hideErrors() {
        errorElement.innerHTML = '';
        errorElement.classList.add('hidden');
    }

    //Очистить поля в модалке
    function cleanModal() {
        hideErrors();
        Object.keys(modalFields).forEach((index, value) => {
            document.querySelector(`[name=${index}]`).value = '';

            if (document.querySelector(`[name=${index}]`).tagName == 'SELECT') {
                document.querySelector(`[name=${index}]`).options[0].selected = true;
            }
        });
    }

    //Обновить контент
    function updateContent(search = null) {
        let urlConfig = urlPathname;
        if (search !== null) {
            urlConfig += '?ids=' + search;
        }
        axios.get(urlConfig)
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