import { has, isNull } from 'lodash'
import tippy from 'tippy.js'

const { default: axios } = require("axios")

const urlBase      = window.location.origin
const urlPathname  = window.location.pathname

let urlCurrent  = urlBase + urlPathname + window.location.search
let urlGetParams = null
let urlGetFilters = new Map()

if (typeof urlCurrent.split('?')[1] !== 'undefined') {
    urlGetParams = urlCurrent.split('?')[1].split('&')

    urlGetParams.forEach((get_param) => {
        let key = get_param.split('=')[0];
        let value = get_param.split('=')[1]
        urlGetFilters.set(key, value)
    })
}


axios.defaults.headers.post['X-CSRF-TOKEN'] = document.querySelector('input[name=_token]').value

routeManager()

function routeManager() {
    urlPathname !== '/auth'     ? sidebarController() : authController()
    urlPathname === '/profile'  ? profileController() : null
    urlPathname === '/schedule' ? scheduleController() : null
    urlPathname.split('_')[1] === 'crud' ? crudController() : null
}

function authController() {
    const form        = document.querySelector('#auth')
    const password    = form.querySelector(`input[name='password']`)
    const button      = form.querySelector(`button[name='save']`)
    const textError   = form.querySelector('.text-danger')
    const body        = document.querySelector('body')
    const loginWindow = document.querySelector('.window')

    let phone = form.querySelector(`input[name='phone']`)

    let checkFields = {
        'phone': 0,
        'password': 0
    }

    let season = seasonDetector()

    if (season === 'Winter') {
        body.classList.remove('spring-bg')
        body.classList.remove('autumn-bg')
        body.classList.add('winter-bg')

        loginWindow.classList.remove('spring')
        loginWindow.classList.remove('autumn')
        loginWindow.classList.add('winter')
    }

    if (season === 'Spring') {
        body.classList.add('spring-bg')
        body.classList.remove('autumn-bg')
        body.classList.remove('winter-bg')

        loginWindow.classList.add('spring')
        loginWindow.classList.remove('autumn')
        loginWindow.classList.remove('winter')
    }

    if (season === 'Autumn') {
        body.classList.remove('spring-bg')
        body.classList.add('autumn-bg')
        body.classList.remove('winter-bg')

        loginWindow.classList.remove('spring')
        loginWindow.classList.add('autumn')
        loginWindow.classList.remove('winter')
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

    function seasonDetector() {
        let now = new Date()
        let month = now.getMonth() + 1
        let season = null
        
        if (2 >= month >= 1 || month === 12) {
            season = 'Winter'
        }

        if (month >= 3 && month <= 5) {
            season = 'Spring'
        }

        if (month >= 6 && month < 12) {
            season = 'Autumn'
        }

        return season
    }
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
    const saveBtn         = document.querySelector('#modalSaveBtn')
    const dataFields      = {}
    const errorElement    = document.querySelector('.errors')
    const cleanBtn        = document.querySelector('#cleanModal')
    const modal           = document.querySelector('#myModal')
    const closeModal      = document.querySelector('#closeModal')
    const openModal       = document.querySelector('#openModal')
    const editModal       = document.querySelector('#editModal')
    const crudTable       = document.querySelector('#crudTable')
    const crudSearch      = document.querySelector('#crudSearch')
    const goSearch        = document.querySelector('#goSearch')
    const crudRefresh     = document.querySelector('#crudRefresh')
    const modaTitle       = document.querySelector('.modal-title')
    const pagination      = document.querySelector('.custom-paginate')
    const activeUrl       = window.location.href.split('?')[1]?.split('&')
    let activeParams = new Map()

    if (typeof activeUrl !== 'undefined') {
        activeUrl.forEach((get_param) => {
            let key = get_param.split('=')[0];
            let value = get_param.split('=')[1]
            activeParams.set(key, value)
        })
    }

    if (window.location.search === '') {
        urlCurrent += "?page=1"
    }

    if (!isNull(urlGetParams)) {
        if (typeof urlGetFilters.get('search') !== 'undefined') {
    
            crudSearch.value = decodeURI(urlGetFilters.get('search'))
        }

        paginationInit(document)
    }

    if (crudTable.querySelectorAll('.tbody tr').length === 0) {
        textNoRecords('hide')
    }

    tippy(openModal, {content: 'Добавить запись', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy(crudRefresh, {content: 'Обновить таблицу', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy(goSearch, {content: 'Поиск', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy('.rowEdit', {content: 'Изменить', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy('.rowDelete', {content: 'Удалить', animation: 'fade', hideOnClick: true, theme: 'custom'})

    /* Кнопка "Очистить" | Clean button */
    cleanBtn.addEventListener('click', () => {
        cleanModal()
    })

    /* Кнопка обновить таблицу | Update table button */
    crudRefresh.addEventListener('click', () => {
        crudSearch.value = ''
        removeURLParameters(['search', 'page'])
        activeParams.delete('search')
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
            if (document.querySelector(`[name=${index}]`).classList.contains('hidden') && document.querySelector(`[name=${index}]`).value == '') {
                document.querySelector(`[name=${index}]`).value = document.querySelector(`[name=${index}]`).getAttribute('value')
            }

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

        textNoRecords('hide')
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
        removeURLParameters(['search'])
        if (isEmptyString(crudSearch.value)) {
            removeURLParameters(['page'])
            activeParams.delete('search')
        }
        updateContent(crudSearch.value)
    })

    crudSearch.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            removeURLParameters(['search'])
            if (isEmptyString(crudSearch.value)) {
                removeURLParameters(['page'])
                activeParams.delete('search')
            }
            updateContent(crudSearch.value)
        }
    })

    /* Скрыть ошибки | Hide errors */
    function hideErrors() {
        errorElement.innerHTML = ''
        errorElement.classList.add('hidden')
    }

    /* Очистить поля в модальном окне | Clean modal fields */
    function cleanModal() {
        hideErrors()
        Object.keys(modalFields).forEach((index) => {
            if (modalFields[index].field_type === 'text') {
                delete modalFields[index]
            }

            const field = document.querySelector(`[name=${index}]`)

            if (!isNull(field)) {
                field.value = ''

                if (field.tagName == 'SELECT') {
                    field.options[0].selected = true
                }
            }

        })
    }

    /* Обновить контент | Update content */
    function updateContent(search = null) {
        let urlConfig = urlPathname
        let filters = ''

        if (search) {
            urlConfig += '?search=' + search

            
            if (activeParams.has('search')) {
                activeParams.delete('search')
            }
            
            if (activeParams.has('page')) {
                activeParams.delete('page')
            }

            activeParams.set('search', search)

            for (let key of activeParams) {
                filters += '&' + key[0] + '=' + key[1]
            }

            window.history.replaceState('', '', urlBase + urlPathname + '?page=1' + filters)
        }

        if (urlPathname === '/class_crud') {
            let sign = activeParams.has('search') ? '&' : '?'
            urlConfig += sign + 'class_id=' + activeParams.get('class_id')
        }

        axios.get(urlConfig)
        .then((response) => {
            let parser = new DOMParser().parseFromString(response.data, 'text/html')
            const parsedPagination = parser.querySelector('.custom-paginate nav')
            
            crudTable.querySelector('tbody').innerHTML = ''

            if (parser.querySelectorAll('#crudTable tbody tr').length > 0) {
                textNoRecords('hide')
            } else {
                textNoRecords('show')
            }

            Object.values(parser.querySelectorAll('#crudTable tbody tr')).forEach((elem) => {
                elem.classList.add('animate__animated', 'animate__fadeIn')
                crudTable.querySelector('tbody').append(elem)
            })
            pagination.innerHTML = ''

            pagination.append(parsedPagination)

            if (isNull(parsedPagination)) {
                pagination.innerHTML = ''
            }

            paginationInit(parsedPagination)
        })
    }

    function textNoRecords(action) {
        const text = document.querySelector('.text-no-records')
        if (action === 'show') {
            text.classList.remove('hidden')
        }

        if (action === 'hide') {
            text.classList.add('hidden')
        }
    }

    function paginationInit(doc) {
        const pageLinks  = doc.querySelectorAll('.page-link')

        if (pageLinks.length === 0) {
            return
        }
        const nextPage     = doc.querySelector(`[rel="next"]`)
        const prevPage     = doc.querySelector(`[rel="prev"]`)
        const activePage   = doc.querySelector('.active')
        const nextPageNum  = Number(activePage.children[0].innerHTML) + 1
        const prevPageNum  = Number(activePage.children[0].innerHTML) - 1
        
        const searchParam  = activeParams.has('search') ? '&search=' + activeParams.get('search') : ''

        pageLinks.forEach((elem) => {
            elem.href = urlBase + urlPathname + '?page=' + elem.innerHTML + searchParam
        })

        if (!isNull(nextPage)) {
            nextPage.href = urlBase + urlPathname + '?page=' + nextPageNum + searchParam
        }

        if (!isNull(prevPage)) {
            prevPage.href = urlBase + urlPathname + '?page=' + prevPageNum + searchParam
        }
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

function scheduleController() {
    const chooseClassroom = document.querySelector('#classroomsChooseDataList')

    if (urlGetFilters.has('classroom_id')) {
        const currentClass = document.querySelector(`#classroomsChooseDataListOptions [data-value="${urlGetFilters.get('classroom_id')}"]`)
        chooseClassroom.value = currentClass.value
        drawModal()
    }

    chooseClassroom.addEventListener('input', () => {
        let selectedClassroom = getDataListSelectedOption('classroomsChooseDataList', 'classroomsChooseDataListOptions')

        if (typeof selectedClassroom !== 'undefined') {
            window.history.replaceState('', '', urlBase + urlPathname + '?classroom_id=' + selectedClassroom)
        }

        softReload()
    })

    function drawModal() {
        const modalBody         = document.querySelector('.modal-body')
        const openModalButton   = document.querySelectorAll('.rowAdd')
        const closeModal        = document.querySelectorAll('[data-bs-dismiss="modal"]')
        const saveModal         = document.querySelector('#saveModal')
        const acceptDelete      = document.querySelector('#acceptDelete')

        const modalFields    = {
            'subject': {
                'searchable': true,
                'placeholder': 'Предмет',
                'name': 'subject',
                'data': null
            },
            'teacher': {
                'searchable': true,
                'placeholder': 'Учитель',
                'name': 'teacher',
                'data': null
            },
            'cabinet': {
                'searchable': true,
                'placeholder': 'Кабинет',
                'name': 'cabinet',
                'data': null
            },
            'from_time': {
                'searchable': false,
                'placeholder': 'Время (начало)',
                'name': 'from_time',
            },
            'to_time': {
                'searchable': false,
                'placeholder': 'Время (конец)',
                'name': 'to_time',
            }
        }

        Object.keys(modalFields).forEach((index) => {
            const field = modalFields[index]
            if (field.searchable) {
                modalBody.innerHTML += `
                    <input class="form-control mb-2" list="${field.name}sDataListOptions" id="${field.name}sDataList" placeholder="${field.placeholder}">
                    <datalist id="${field.name}sDataListOptions"></datalist>
                `
            }

            if (!field.searchable) {
                if (field.name === 'from_time' || field.name === 'to_time') {
                    modalBody.innerHTML += `
                        <div class="d-flex justify-content-between small-input-group mb-2"></div>
                    `
                    document.querySelector('.small-input-group').innerHTML += `
                        <input type="text" style="width: 48%;" class="form-control" id="${field.name}Field" placeholder="${field.placeholder}">
                    `
                } else {
                    modalBody.innerHTML += `
                        <input type="text" class="form-control mb-2" id="${field.name}Field" placeholder="${field.placeholder}">
                    `
                }
            }
        })

        loadModalFieldsOptions()

        let dayNum = document.querySelector('#dayNum')
        let recordId = document.querySelector('#recordId')
        
        document.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('rowAdd')) {
                dayNum.value = e.target.getAttribute('data-day')
                recordId.value = ''
            }
            if (e.target && e.target.classList.contains('rowDelete')) {
                acceptDelete.setAttribute('data-id', e.target.getAttribute('data-record-id'))
            }

            if (e.target && e.target.classList.contains('rowEdit')) {
                dayNum.value = e.target.getAttribute('data-day')
                recordId.value = e.target.getAttribute('data-record-id')
                if (recordId.value !== '') {
                    axios.post(urlBase + urlPathname + '/get_record', {'id': recordId.value})
                    .then((response) => {
                        let data = response.data
                        delete data['id']
                        delete data['day']

                        Object.keys(data).forEach((index) => {

                            let elemId = '#' + index.split('_')[0] + 'sDataList'

                            if (index === 'from_time' || index == 'to_time') {
                                elemId = '#' + index + 'Field'
                                document.querySelector(elemId).value = data[index]
                            }
 
                            let field = document.querySelector(elemId)
                            let dataList = document.querySelector(elemId + 'Options')
                            let selectedElem = null
                            if (!isNull(dataList)) {
                                selectedElem = dataList.querySelector(`option[data-value="${data[index]}"]`)
                                field.value = selectedElem.value
                            }
                        })
                    })
                }
            }
        })


        closeModal.forEach((elem) => {
            elem.addEventListener('click', () => {
                modalBody.querySelectorAll('input').forEach((input) => {
                    input.value = ''
                })
                modalBody.querySelectorAll('.errors').forEach((error) => {
                    error.parentNode.removeChild(error);
                })
            })
        })

        saveModal.addEventListener('click', () => {
            saveItemToSchedule({
                'id': recordId.value !== '' ? recordId.value : null, 
                'classroom_id': window.location.search.split('=')[1],
                'subject_id': getDataListSelectedOption('subjectsDataList', 'subjectsDataListOptions'),
                'cabinet_id': getDataListSelectedOption('cabinetsDataList', 'cabinetsDataListOptions'),
                'day_of_week': dayNum.value,
                'from_time': document.querySelector('#from_timeField').value,
                'to_time': document.querySelector('#to_timeField').value
            })
        })

        acceptDelete.addEventListener('click', () => {
            axios.post(urlBase + urlPathname + '/delete', {'id': acceptDelete.getAttribute('data-id')})
            .then(() => {
                softReload()
                document.querySelector('#closeModal').click()
            })
        })
    }

    function loadModalFieldsOptions() {
        axios.post(urlPathname + '/get_modal_fields')
        .then((result) => {
            const subjectsData = result.data.subjects
            const teachersData = result.data.teachers
            const cabinetsData = result.data.cabinets

            Object.keys(subjectsData).forEach((item) => {
                document.querySelector('#subjectsDataListOptions').innerHTML += `
                    <option data-value="${subjectsData[item].id}" value="${subjectsData[item].name}">
                `
            })

            Object.keys(teachersData).forEach((item) => {
                document.querySelector('#teachersDataListOptions').innerHTML += `
                    <option data-value="${teachersData[item].id}" value="${teachersData[item].full_name}">
                `
            })

            Object.keys(cabinetsData).forEach((item) => {
                document.querySelector('#cabinetsDataListOptions').innerHTML += `
                    <option data-value="${cabinetsData[item].id}" value="${cabinetsData[item].num}">
                `
            })
                
        })
    }

    function softReload() {
        axios.get(window.location.href)
        .then((response) => {
            const scheduleContainer = document.querySelector('#schedule')
            let parser = new DOMParser().parseFromString(response.data, 'text/html')
            scheduleContainer.innerHTML = ''
            Object.values(parser.querySelectorAll('#schedule .card')).forEach((elem) => {
                scheduleContainer.append(elem)
            })
        })
    }


    function saveItemToSchedule(data) {
        axios.post(urlPathname + '/save', data)
        .then((result) => {
            softReload()
            document.querySelector('[data-bs-dismiss="modal"]').click()
        })
        .catch((data) => {
            let errors = data.response.data.errors
            
            Object.keys(errors).forEach((error) => {
                document.querySelector('.modal-body').innerHTML += `<p class="errors text-danger">${errors[error]}</p>`
            })
        })
    }
}

/* Общие функции | Common functions */
function removeURLParameters(removeParams) {
    const deleteRegex = new RegExp(removeParams.join('=|') + '=')
  
    const params = location.search.slice(1).split('&')
    let search = []
    for (let i = 0; i < params.length; i++) if (deleteRegex.test(params[i]) === false) search.push(params[i])
  
    window.history.replaceState({}, document.title, location.pathname + (search.length ? '?' + search.join('&') : '') + location.hash)
}

function isEmptyString(str) {
    if (str.trim() == '') 
      return true;
      
    return false;
}

function getDataListSelectedOption(inputId, dataListOptions) 
{
    const shownVal = document.getElementById(inputId).value
    const result   = document.querySelector("#" + dataListOptions + " option[value='" + shownVal + "']")?.dataset.value
    return result
}