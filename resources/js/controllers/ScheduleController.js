class ScheduleController {
    constructor() {
        this.init()
    }

    init() {
        const scheduleContainer = document.querySelector('#schedule')
        const daysOfWeek = 6
        let dayOfWeek = ''
        
        for (let i = 1; i < daysOfWeek + 1; i++) {

            switch(i) {
                case 1: dayOfWeek = 'Понедельник'
                    break
                case 2: dayOfWeek = 'Вторник'
                    break
                case 3: dayOfWeek = 'Среда'
                    break
                case 4: dayOfWeek = 'Четверг'
                    break
                case 5: dayOfWeek = 'Пятница'
                    break
                case 6: dayOfWeek = 'Суббота'
                    break
            }

            scheduleContainer.innerHTML += `
                <div class="card table-responsive animate__animated animate__fadeIn" data-day="${i}">
                <div class="card-header d-flex justify-content-between pt-3">
                    <h5>${dayOfWeek}</h5>
                    <ion-icon name="add-circle" class="rowEdit text-light" style="cursor: pointer; font-size:24px"></ion-icon>
                </div>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Предмет</td>
                            <td>Учитель</td>
                            <td>Время</td>
                            <td>Кабинет</td>
                            <td>Управление</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Русский язык</td>
                            <td>Светлана Аналовна</td>
                            <td>8:00 - 8:45</td>
                            <td>215 каб.</td>
                            <td>
                                <ion-icon name="create" class="rowEdit text-primary" style="cursor: pointer; font-size:24px"></ion-icon>
                                <ion-icon name="close-circle" data-bs-toggle="modal" data-bs-target="#warningModal" class="rowDelete text-danger" style="margin-left: 10px; cursor: pointer; font-size:24px"></ion-icon>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `
        }
    }
}

new ScheduleController()