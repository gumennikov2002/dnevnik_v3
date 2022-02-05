const { default: axios } = require("axios");

const urlBase = window.location.origin;
const urlPathname = window.location.pathname;
axios.defaults.headers.post['X-CSRF-TOKEN'] = document.querySelector('input[name=_token]').value;

routeManager();

function routeManager() {
    urlPathname !== '/auth' ? sidebarController() : authController();
    urlPathname.split('_')[1] === 'crud' ? crudController() : null;
}

function authController() {
    const form = document.querySelector('#auth');
    const password = form.querySelector(`input[name='password']`);
    const button = form.querySelector(`button[name='save']`);
    const textError = form.querySelector('.text-danger');
    let phone = form.querySelector(`input[name='phone']`);

    let checkFields = {
        'phone': 0,
        'password': 0
    };

    function disableEnableButton(action) {
        if (action === 'disable') {
            button.removeAttribute('disabled');
            button.classList.remove('btn-secondary');
            button.classList.add('btn-primary');
        } if (action === 'enable') {
            button.setAttribute('disabled', true);
            button.classList.remove('btn-primary');
            button.classList.add('btn-secondary');
        }
    }
    
    phone.addEventListener('keyup', () => {
        phone.value !== '' ? checkFields.phone = 1 : checkFields.phone = 0;
        checkFields.phone !== 0 && checkFields.password !== 0 ? disableEnableButton('disable') : disableEnableButton('enable');
        textError.innerHTML = '';
    });

    password.addEventListener('keyup', () => {
        password.value !== '' ? checkFields.password = 1 : checkFields.password = 0;
        checkFields.phone !== 0 && checkFields.password !== 0 ? disableEnableButton('disable') : disableEnableButton('enable');
        textError.innerHTML = '';
    });


    button.addEventListener('click', () => {
        if (phone.value.indexOf('+') !== -1) {
            phone.value = phone.value.replace(/[^0-9]/g, '');
        }

        const data = {
            'phone': phone.value,
            'password': password.value
        }

        axios.post(urlPathname + '/check', data)
        .then(() => {
            window.location.href = urlBase + '/profile';
        })
        .catch(() => {
            textError.append('Неверный логин или пароль');
        });
    });
}

function sidebarController() {
    const sidebarContainer = document.querySelector('#sidebarContainer');

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

    Object.keys(menu).forEach((index) => {
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
    const saveBtn = document.querySelector('#modalSaveBtn');
    const dataFields = {};
    const errorElement = document.querySelector('.errors');
    const cleanBtn = document.querySelector('#cleanModal');
    const modal = document.querySelector('#myModal');
    const closeModal = document.querySelector('#closeModal');
    const openModal = document.querySelector('#openModal');
    const crudTable = document.querySelector('#crudTable');
    const crudSearch = document.querySelector('#crudSearch');

    /* Кнопка "Очистить" | Clean button */
    cleanBtn.addEventListener('click', () => {
        cleanModal();
    });

    /* Кнопка "Сохранить" | Save button */
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
        .then(() => {
            closeModal.click();
            cleanModal();
            updateContent();
        })
        .catch((error) => {
            Object.keys(error.response.data.errors).forEach((index) => {
                let errorInput = document.querySelector(`[name=${index}]`);
                errorInput.style.border = '2px solid #f8d7da';
                errorElement.classList.remove('hidden');
                errorElement.innerHTML += `<span>${error.response.data.errors[index]}</span> <br>`;
            });
        });
    });

    /* Отслеживание кликов динамических объектов | Dynamic objects click tracker */
    document.addEventListener('click', (e) => {
        /* Удалить запись | Delete record */
        if (e.target && e.target.classList.contains('rowDelete')) {
            let rowId = e.target.parentNode.parentNode.getAttribute('data-id');
            axios.post(urlPathname + '/delete', {'id': rowId});
            e.target.parentNode.removeChild;
            e.target.parentNode.parentNode.innerHTML = '';
        }

        /* Получить данные записи в модальном окне | Get record data in modal window */
        if (e.target && e.target.classList.contains('rowEdit')) {
            let rowId = e.target.parentNode.parentNode.getAttribute('data-id');
            axios.post(urlPathname + '/get_fields', {'id': rowId})
            .then((response) => {
                openModal.click();
                const recordId = modal.querySelector(`input[name='id']`)
                if (recordId) {
                    recordId.parentNode.removeChild(recordId);
                }

                modal.querySelector('.modal-body').innerHTML += `<input type='text' name='id' value='${rowId}' class='hidden record-id'>`;
                Object.keys(response.data).forEach((index) => {
                    const fields = modal.querySelectorAll('.data-field');
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

    /* Поиск по странице | Page search */
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

    /* Скрыть ошибки | Hide errors */
    function hideErrors() {
        errorElement.innerHTML = '';
        errorElement.classList.add('hidden');
    }

    /* Очистить поля в модальном окне | Clean modal fields */
    function cleanModal() {
        hideErrors();
        Object.keys(modalFields).forEach((index) => {
            const field = document.querySelector(`[name=${index}]`);
            field.value = '';

            if (field.tagName == 'SELECT') {
                field.options[0].selected = true;
            }
        });
    }

    /* Обновить контент | Update content */
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
        });
    }
}