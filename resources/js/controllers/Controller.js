import tippy from 'tippy.js'

const { default: axios } = require("axios")

const urlBase     = window.location.origin
const urlPathname = window.location.pathname

axios.defaults.headers.post['X-CSRF-TOKEN'] = document.querySelector('input[name=_token]').value

routeManager()

function routeManager() {
    urlPathname !== '/auth'    ? sidebarController() : authController()
    urlPathname === '/profile' ? profileController() : null
    urlPathname.split('_')[1] === 'crud' ? crudController() : null
}

function authController() {
    const form      = document.querySelector('#auth')
    const password  = form.querySelector(`input[name='password']`)
    const button    = form.querySelector(`button[name='save']`)
    const textError = form.querySelector('.text-danger')

    let phone = form.querySelector(`input[name='phone']`)

    let checkFields = {
        'phone': 0,
        'password': 0
    }

    function disableEnableButton(action) {
        if (action === 'disable') {
            button.removeAttribute('disabled')
        } 

        if (action === 'enable') {
            button.setAttribute('disabled', true)
        }
    }
    
    phone.addEventListener('keyup', () => {
        phone.value !== '' ? checkFields.phone = 1 : checkFields.phone = 0
        checkFields.phone !== 0 && checkFields.password !== 0 ? disableEnableButton('disable') : disableEnableButton('enable')
        textError.innerHTML = ''
    })

    password.addEventListener('keyup', () => {
        password.value !== '' ? checkFields.password = 1 : checkFields.password = 0
        checkFields.phone !== 0 && checkFields.password !== 0 ? disableEnableButton('disable') : disableEnableButton('enable')
        textError.innerHTML = ''
    })


    button.addEventListener('click', () => {
        if (phone.value.indexOf('+') !== -1) {
            phone.value = phone.value.replace(/[^0-9]/g, '')
        }

        const data = {
            'phone': phone.value,
            'password': password.value
        }

        axios.post(urlPathname + '/check', data)
        .then(() => {
            window.location.href = urlBase + '/profile'
        })
        .catch(() => {
            textError.append('Неверный логин или пароль')
        })
    })
}

function sidebarController() {
    const sidebarContainer       = document.querySelector('#sidebarContainer')
    const bottomSidebarContainer = document.querySelector('#bottom-sidebar')
    const mobileMenu             = document.querySelector('#menu')
    const openMenu               = document.querySelector('#openMenu')
    const closeMenu              = document.querySelector('#closeMenu')

    openMenu.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden', 'animate__animated', 'animate__fadeOutDown')
        mobileMenu.classList.add('animate__animated', 'animate__fadeInUp')
    })

    closeMenu.addEventListener('click', () => {
        mobileMenu.classList.remove('animate__animated', 'animate__fadeInUp')
        mobileMenu.classList.add('animate__animated', 'animate__fadeOutDown')
    })


    axios.post(urlBase + '/sidebar/load')
    .then((response) => {
        const menu    = response.data.menu
        const profile = response.data.profile


        Object.keys(menu).forEach((index) => {
            sidebarContainer.innerHTML += `
            <li>
                <a href="${menu[index].url}" class="nav-link py-3 ${menu[index].class} ${urlPathname == menu[index].url ? 'active' : ''}">
                    <ion-icon class="text-light" name="${menu[index].icon}"></ion-icon>
                </a>
            </li>
            `

            
            bottomSidebarContainer.querySelector('.items').innerHTML += `
                <a href="${menu[index].url}"><ion-icon class="text-primary" name="${menu[index].icon}"></ion-icon></a>
            `

            mobileMenu.querySelector('ul').innerHTML += `
                <li><a href="${menu[index].url}">${menu[index].title}</a></li>
            `
        })

        for (let i = 0; i < Object.keys(menu).length; i++) {
            tippy(`.${Object.keys(menu)[i]}`, {content: `${Object.values(menu)[i].title}`, animation: 'fade', hideOnClick: true, theme: 'custom', theme: 'custom', placement: 'right'})
        }

        Object.keys(profile.profile_menu).forEach((index, value) => {
            const dropdownMenu = document.getElementsByClassName('sidebar-dropdown dropdown-menu')
            const menu         = profile.profile_menu

            for (let i = 0; i < dropdownMenu.length; i++) {
                dropdownMenu[i].insertAdjacentHTML('afterbegin', `<li><a class="dropdown-item" href="${menu[index].link}">${menu[index].title}</a></li>`)
            }
        })

        document.querySelector('#profile-pic').setAttribute('src', profile.profile_pic)

    })
}

function crudController() {
    modalFields = JSON.parse(modalFields)
    const saveBtn      = document.querySelector('#modalSaveBtn')
    const dataFields   = {}
    const errorElement = document.querySelector('.errors')
    const cleanBtn     = document.querySelector('#cleanModal')
    const modal        = document.querySelector('#myModal')
    const closeModal   = document.querySelector('#closeModal')
    const openModal    = document.querySelector('#openModal')
    const editModal    = document.querySelector('#editModal')
    const crudTable    = document.querySelector('#crudTable')
    const crudSearch   = document.querySelector('#crudSearch')
    const goSearch     = document.querySelector('#goSearch')
    const crudRefresh  = document.querySelector('#crudRefresh')
    const modaTitle    = document.querySelector('.modal-title')

    tippy(openModal, {content: 'Добавить запись', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy(crudRefresh, {content: 'Обновить таблицу', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy(goSearch, {content: 'Поиск', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy('.rowEdit', {content: 'Изменить', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy('.rowDelete', {content: 'Удалить', animation: 'fade', hideOnClick: true, theme: 'custom'})

    document.querySelectorAll('.page-link').forEach((elem) => {
        let page = null
        
        elem.addEventListener('click', (e) => {
            e.preventDefault();
            const active = document.querySelectorAll('.custom-paginate .active')[0];    

            if (active) {
                active.classList.remove('active')
            }

            page = e.target.innerHTML
            e.target.parentNode.classList.add('active')
            // window.history.pushState({} , '', `?page=${page}`)
            paginateUpdate(page)
        })
    })

    /* Кнопка "Очистить" | Clean button */
    cleanBtn.addEventListener('click', () => {
        cleanModal()
    })

    /* Кнопка обновить таблицу | Update table button */
    crudRefresh.addEventListener('click', () => {
        crudSearch.value = ''
        updateContent()
    })

    /* При нажатии кнопки "Добавить" очищает данные полей | Clean modal fields if button has been pressed */
    openModal.addEventListener('click', () => {
        cleanModal()
        
        if (modal.querySelector('.record-id')) {
            const recordId = modal.querySelector('.record-id')
            recordId.removeAttribute('name')
            recordId.classList.remove('record-id')
            recordId.value = null
        }

        modaTitle.innerHTML = 'Добавить запись'
    })

    /* Кнопка "Сохранить" | Save button */
    saveBtn.addEventListener('click', () => {
        let url = null

        Object.keys(modalFields).forEach((index, value) => {
            dataFields[index] = document.querySelector(`[name=${index}]`).value
            url = urlPathname + '/create'

            if (modal.querySelector(`input[name='id']`)) {
                url = urlPathname + '/update'
                dataFields['id'] = modal.querySelector(`input[name='id']`).value
            }
        })

        hideErrors()

        axios.post(url, dataFields)
        .then(() => {
            closeModal.click()
            cleanModal()
            updateContent()
        })
        .catch((error) => {
            Object.keys(error.response.data.errors).forEach((index) => {
                let errorInput = document.querySelector(`[name=${index}]`)
                errorInput.style.border = '2px solid #f8d7da'
                errorElement.classList.remove('hidden')
                errorElement.innerHTML += `<span>${error.response.data.errors[index]}</span> <br>`
            })
        })

        document.querySelector('.text-no-records').innerHTML = ''
    })

    /* Отслеживание кликов динамических объектов | Dynamic objects click tracker */
    document.addEventListener('click', (e) => {

        /* Удалить запись | Delete record */
        if (e.target && e.target.classList.contains('rowDelete')) {
            const warningModal = document.querySelector('#warningModal')
            let rowId = e.target.parentNode.parentNode.getAttribute('data-id')

            warningModal.querySelector('#deleteAccept').onclick = () => {
                axios.post(urlPathname + '/delete', {'id': rowId})

                e.target.parentNode.parentNode.classList.add('animate__animated', 'animate__fadeOut')
                setTimeout(() => {
                    e.target.parentNode.removeChild
                    e.target.parentNode.parentNode.innerHTML = ''
    
                }, 450)
            }

        } 

        /* Получить данные записи в модальном окне | Get record data in modal window */
        if (e.target && e.target.classList.contains('rowEdit')) {
            let rowId = e.target.parentNode.parentNode.getAttribute('data-id')

            axios.post(urlPathname + '/get_fields', {'id': rowId})
            .then((response) => {
                editModal.click()
                modaTitle.innerHTML = 'Изменить запись'
                const recordId = modal.querySelector(`input[name='id']`)
                if (recordId) {
                    recordId.parentNode.removeChild(recordId)
                }

                modal.querySelector('.modal-body').innerHTML += `<input type='text' name='id' value='${rowId}' class='hidden record-id'>`
                Object.keys(response.data).forEach((index) => {
                    const fields = modal.querySelectorAll('.data-field')

                    fields.forEach((e) => {
                        if (e.tagName === 'INPUT' && e.getAttribute('name') === index) {
                            e.value = response.data[index]
                        }
                        
                        if (e.tagName === 'TEXTAREA' && e.getAttribute('name') === index) {
                            e.innerHTML = response.data[index]
                        }

                        if (e.tagName === 'SELECT' && e.getAttribute('name') === index) {
                            e.value = response.data[index]
                        }
                    })
                })
            })
        }
    })

    /* Поиск по странице | Page search */
    goSearch.addEventListener('click', () => {
        search()
    })

    crudSearch.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            search()
        }
    })

    function search() {
        let ids = []

        axios.post(urlPathname + '/search', {'word': crudSearch.value})
        .then((response) => {
            Object.keys(response.data).forEach((index) => {
                if (response.data[index].length > 0) {
                    let recordIds = response.data[index]
                    Object.keys(recordIds).forEach((i) => {
                        ids.push(recordIds[i].id)
                    })
                }
            })

            updateContent(ids)
        })

        if (crudSearch.value === '' || crudSearch.value === ' ') {
            updateContent()
        }
    }


    /* Скрыть ошибки | Hide errors */
    function hideErrors() {
        errorElement.innerHTML = ''
        errorElement.classList.add('hidden')
    }

    /* Очистить поля в модальном окне | Clean modal fields */
    function cleanModal() {
        hideErrors()
        Object.keys(modalFields).forEach((index) => {
            const field = document.querySelector(`[name=${index}]`)
            field.value = ''

            if (field.tagName == 'SELECT') {
                field.options[0].selected = true
            }
        })
    }

    /* Обновить контент | Update content */
    function updateContent(search = null) {
        let urlConfig = urlPathname

        if (search) {
            urlConfig += '?ids=' + search
        }

        axios.get(urlConfig)
        .then((response) => {
            let parser = new DOMParser().parseFromString(response.data, 'text/html')
            crudTable.querySelector('tbody').innerHTML = ''
            Object.values(parser.querySelectorAll('#crudTable tbody tr')).forEach((elem) => {
                elem.classList.add('animate__animated', 'animate__fadeIn')
                crudTable.querySelector('tbody').append(elem)
            })
        })
    }

    function paginateUpdate(page = false) {
        let urlConfig = urlPathname
        if (page) {
            urlConfig += '?page=' + page
        }

        axios.get(urlConfig)
        .then((response) => {
            let parser = new DOMParser().parseFromString(response.data, 'text/html')
            crudTable.querySelector('tbody').innerHTML = ''
            Object.values(parser.querySelectorAll('#crudTable tbody tr')).forEach((elem) => {
                elem.classList.add('animate__animated', 'animate__fadeIn')
                crudTable.querySelector('tbody').append(elem)
            })
        })
    }
}

function profileController() {
    const profile               = document.querySelector('#profilePage')
    const profilePic            = profile.querySelector('#profile-pic')
    const profilePicEditElement = profile.querySelector('#profile-pic-edit')

    profilePic.addEventListener('mouseover', () => {
        profilePicEditElement.classList.remove('hidden')
    })

    profilePicEditElement.addEventListener('mouseout', () => {
        profilePicEditElement.classList.add('hidden')
    })
}