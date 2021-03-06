import { isNull } from 'lodash'
import tippy from 'tippy.js'

const { default: axios } = require("axios")

const urlBase      = window.location.origin
const urlPathname  = window.location.pathname

let urlCurrent  = urlBase + urlPathname + window.location.search
let urlGetParams = null
if (typeof urlCurrent.split('?')[1] !== 'undefined') {
    urlGetParams = urlCurrent.split('?')[1].split('&')
}


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
            textError.append('???????????????? ?????????? ?????? ????????????')
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

    if (window.location.search === '') {
        urlCurrent += "?page=1"
    }

    if (!isNull(urlGetParams)) {
        const getParamSearch = urlGetParams[1]?.split('=')
        if (typeof getParamSearch !== 'undefined') {
    
            crudSearch.value = decodeURI(getParamSearch[1])
        }

        paginationInit(document)
    }

    if (crudTable.querySelectorAll('.tbody tr').length === 0) {
        textNoRecords('hide')
    }

    tippy(openModal, {content: '???????????????? ????????????', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy(crudRefresh, {content: '???????????????? ??????????????', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy(goSearch, {content: '??????????', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy('.rowEdit', {content: '????????????????', animation: 'fade', hideOnClick: true, theme: 'custom'})
    tippy('.rowDelete', {content: '??????????????', animation: 'fade', hideOnClick: true, theme: 'custom'})

    /* ???????????? "????????????????" | Clean button */
    cleanBtn.addEventListener('click', () => {
        cleanModal()
    })

    /* ???????????? ???????????????? ?????????????? | Update table button */
    crudRefresh.addEventListener('click', () => {
        crudSearch.value = ''
        removeURLParameters(['page', 'search'])
        updateContent()
    })

    /* ?????? ?????????????? ???????????? "????????????????" ?????????????? ???????????? ?????????? | Clean modal fields if button has been pressed */
    openModal.addEventListener('click', () => {
        cleanModal()
        
        if (modal.querySelector('.record-id')) {
            const recordId = modal.querySelector('.record-id')
            recordId.removeAttribute('name')
            recordId.classList.remove('record-id')
            recordId.value = null
        }

        modaTitle.innerHTML = '???????????????? ????????????'
    })

    /* ???????????? "??????????????????" | Save button */
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

        textNoRecords('hide')
    })

    /* ???????????????????????? ???????????? ???????????????????????? ???????????????? | Dynamic objects click tracker */
    document.addEventListener('click', (e) => {

        /* ?????????????? ???????????? | Delete record */
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

        /* ???????????????? ???????????? ???????????? ?? ?????????????????? ???????? | Get record data in modal window */
        if (e.target && e.target.classList.contains('rowEdit')) {
            let rowId = e.target.parentNode.parentNode.getAttribute('data-id')

            axios.post(urlPathname + '/get_fields', {'id': rowId})
            .then((response) => {
                editModal.click()
                modaTitle.innerHTML = '???????????????? ????????????'
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

    /* ?????????? ???? ???????????????? | Page search */
    goSearch.addEventListener('click', () => {
        removeURLParameters(['search'])
        updateContent(crudSearch.value)
    })

    crudSearch.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            removeURLParameters(['search'])
            updateContent(crudSearch.value)
        }
    })

    /* ???????????? ???????????? | Hide errors */
    function hideErrors() {
        errorElement.innerHTML = ''
        errorElement.classList.add('hidden')
    }

    /* ???????????????? ???????? ?? ?????????????????? ???????? | Clean modal fields */
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

    /* ???????????????? ?????????????? | Update content */
    function updateContent(search = null) {
        let urlConfig = urlPathname
        let newUrl = ''

        if (search) {
            urlConfig += '?search=' + search
            const parseCurrentUrl = urlCurrent.split('&')
            const searchGetParam  = parseCurrentUrl[1]

            if (typeof searchGetParam !== 'undefined') {
                delete parseCurrentUrl[1]
                urlCurrent = '?page=1'
            }

            newUrl = urlCurrent + `&search=${search}`

            window.history.replaceState('', '', newUrl)
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
        const activeParams = window.location.href.split('?')[1].split('&')
        const searchParam  = typeof activeParams[1] !== 'undefined' ? '&' + activeParams[1] : ''

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

/* ?????????? ?????????????? | Common functions */
function removeURLParameters(removeParams) {
    const deleteRegex = new RegExp(removeParams.join('=|') + '=')
  
    const params = location.search.slice(1).split('&')
    let search = []
    for (let i = 0; i < params.length; i++) if (deleteRegex.test(params[i]) === false) search.push(params[i])
  
    window.history.replaceState({}, document.title, location.pathname + (search.length ? '?' + search.join('&') : '') + location.hash)
}