<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    

	<title>Calendar</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{('frontend/css/app.css')}}" rel="stylesheet">
</head>
<body>
	<header>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-4">
					<a href="#"><img src="{{ ('frontend/images/calendar.png')}}" alt="logocld" class="iconheader calendar"></a>
					<a href="{{ route('tasks.index') }}"><img src="{{ ('frontend/images/to-do-list.png')}}" alt="logotdl" class="iconheader todolist"></a>
				</div>
				<div class="col-sm-4 d-flex align-items-center justify-content-center gap-2">
					<button id="btn-today" class="btn btn-outline-dark">Hôm nay</button>
					<button id="btn-prev" class="btn text-dark"><img src="{{ ('frontend/images/left-arrow.png')}}" alt=""></button>
					<button id="btn-next" class="btn text-dark"><img src="{{ ('frontend/images/right-arrow.png')}}" alt=""></button>
					<p class="month-year-display" id="monthYear"></p>
				</div>	
				<div class="col-sm-4 text-end dropdown">
                    <a href="#" class="d-inline-flex align-items-center text-decoration-none text-dark" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                        <img src="{{ asset('frontend/images/profile.png') }}" alt="Profile" class="rounded-circle ms-2 iconheader" style="width: 50px; height: 50px;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </div>

			</div>
		</div>
	</header>
	<section>
        <div>
            <div class="row">
                <div class="col-sm-3 sticky-sidebar">
                    <div class="d-flex justify-content-between align-items-center px-2">
                      <button id="prev-month" class="btn btn-sm "><img src="{{ ('frontend/images/left-arrow.png')}}" alt=""></button>
                      <h4 id="calendar-title"></h4>
                      <button id="next-month" class="btn btn-sm "><img src="{{ ('frontend/images/right-arrow.png')}}" alt=""></button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>CN</th>
                                <th>T2</th>
                                <th>T3</th>
                                <th>T4</th>
                                <th>T5</th>
                                <th>T6</th>
                                <th>T7</th>
                            </tr>
                        </thead>
                        <tbody id="calendar-body">
                        </tbody>
                    </table>
                    <div class="tao">
                        <button data-bs-toggle="modal" data-bs-target="#createTaskModal">
                            <img class="plus" src="{{('frontend/images/plus.png') }}" alt="">Tạo 
                        </button>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="calendar-grid">
                        <div class="week-header" id="week-header"></div>
                        <div class="croll">
                          <div class="time-labels" id="time-labels"></div>
                          <div class="week-body" id="week-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal tạo sự kiện -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">Tạo sự kiện mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form id="createTaskForm">
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="taskTitle" placeholder="Nhập tiêu đề..." required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="allDayCheck" onchange="toggleTimeInputs()">
                            <label class="form-check-label" for="allDayCheck">Cả ngày</label>
                        </div>
                        <div class="mb-3 row">
                            <div class="col">
                            <label for="startDate" class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="startDate" required>
                            </div>
                            <div class="col" id="endDateGroup">
                            <label for="endDate" class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control" id="endDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="taskDescription" rows="3" placeholder="Nhập ghi chú..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="submitEventForm()">Tạo</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem chi tiết sự kiện -->
    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventDetailModalLabel">Chi tiết sự kiện</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body" id="eventDetailBody">
        <!-- Nội dung chi tiết sẽ được JS render -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="deleteEventBtn" style="display:none;">Xóa</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentDate = new Date();
    
    function taolich(month, year, updateMainTitle=true) {
        const taotitle = document.getElementById('calendar-title');
        const taobody = document.getElementById('calendar-body');
        taobody.innerHTML = "";

        const today = new Date();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        taotitle.innerText = `Tháng ${month + 1}, ${year}`;
        if(updateMainTitle){
            document.getElementById('monthYear').innerText = `Tháng ${month + 1}, ${year}`;
        }

        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement("tr");
            for (let j = 0; j < 7; j++) {
                const cell = document.createElement("td");
                if (i === 0 && j < firstDay) {
                    cell.innerText = "";
                } else if (date > daysInMonth) {
                    break;
                } else {
                    cell.innerText = date;
                    if (date === today.getDate() &&
                        month === today.getMonth() &&
                        year === today.getFullYear()
                    ) {
                        cell.classList.add("today");
                    }
                    date++;
                }
                row.appendChild(cell);
            }
            taobody.appendChild(row);
            if (date > daysInMonth) break;
        }
    }

    function getWeekDates(date = new Date()) {
        const day = date.getDay();
        const start = new Date(date);
        start.setDate(date.getDate() - day);
        const week = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(start);
            d.setDate(start.getDate() + i);
            week.push({
                label: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][i],
                date: d.getDate()
            });
        }
        return week;
    }

    function renderWeekView(date = new Date()) {
        const weekHeader = document.getElementById("week-header");
        const weekBody = document.getElementById("week-body");
        const timeLabels = document.getElementById("time-labels");

        const week = getWeekDates(date);
        weekHeader.innerHTML = "";
        week.forEach(day => {
            const div = document.createElement("div");
            div.className = "day-header";
            div.innerHTML = `<div>${day.label}</div><div>${day.date}</div>`;
            weekHeader.appendChild(div);
        });

        timeLabels.innerHTML = `<div class="hour">GMT+07</div>`;
        for (let h = 1; h < 24; h++) {
            const div = document.createElement("div");
            div.className = "hour";
            div.textContent = h < 12 ? `${h} AM` : h === 12 ? "12 PM" : `${h - 12} PM`;
            timeLabels.appendChild(div);
        }

        weekBody.innerHTML = "";
        for (let h = 0; h < 24; h++) {
            const row = document.createElement("div");
            row.className = "hour-row";
            for (let d = 0; d < 7; d++) {
                const cell = document.createElement("div");
                cell.className = "day-cell";
                row.appendChild(cell);
            }
            weekBody.appendChild(row);
        }
    }

    document.getElementById("prev-month").addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        taolich(currentDate.getMonth(), currentDate.getFullYear(), false);
    });

    document.getElementById("next-month").addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        taolich(currentDate.getMonth(), currentDate.getFullYear(), false);
    });

    document.getElementById("btn-prev").addEventListener("click", () => {
        currentDate.setDate(currentDate.getDate() - 7);
        renderWeekView(currentDate);
        taolich(currentDate.getMonth(), currentDate.getFullYear());
    });

    document.getElementById("btn-next").addEventListener("click", () => {
        currentDate.setDate(currentDate.getDate() + 7);
        renderWeekView(currentDate);
        taolich(currentDate.getMonth(), currentDate.getFullYear());
    });

    document.getElementById("btn-today").addEventListener("click", () => {
        currentDate = new Date();
        taolich(currentDate.getMonth(), currentDate.getFullYear());
        renderWeekView(currentDate);
    });

    function toggleTimeInputs() {
    const allDay = document.getElementById('allDayCheck').checked;
    const startInput = document.getElementById('startDate');
    const endInput = document.getElementById('endDate');
    const endDateGroup = document.getElementById('endDateGroup');

    if (allDay) {
        startInput.type = 'date';
        endDateGroup.style.display = 'none'; // Ẩn ô kết thúc
    } else {
        startInput.type = 'datetime-local';
        endDateGroup.style.display = 'block'; // Hiện lại ô kết thúc
        endInput.type = 'datetime-local';
    }
}
    taolich(currentDate.getMonth(), currentDate.getFullYear());
    renderWeekView();
function submitEventForm() {
    const title = document.getElementById('taskTitle').value;
    const allDay = document.getElementById('allDayCheck').checked;
    const startDate = document.getElementById('startDate').value;
    let endDate = null;
    if (!allDay) {
        endDate = document.getElementById('endDate').value;
    }

    const description = document.getElementById('taskDescription').value;

    if (!title || !startDate) {
        alert('Vui lòng nhập tiêu đề và thời gian bắt đầu!');
        return;
    }

    fetch("{{ route('events.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            title: title,
            all_day: allDay,
            start_time: startDate,
            end_time: endDate,
            description: description
        })
    })
    .then(response => {
        if (response.ok) {
            // Đóng modal
            const modalEl = document.getElementById('createTaskModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            // Reset form
            document.getElementById('createTaskForm').reset();

            // Reload lại trang hoặc gọi hàm cập nhật lịch để hiển thị sự kiện mới
            location.reload();
        } else {
            alert('Lỗi khi tạo sự kiện');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi mạng hoặc máy chủ');
    });
}
// Chuyển $events từ PHP sang JS
const events = @json($events);

// Hàm hiển thị event lên lịch tuần (ví dụ: chèn vào các ô giờ tương ứng)
function renderEventsOnWeekView(events) {
    const weekStart = new Date(currentDate);
    weekStart.setHours(0,0,0,0);
    weekStart.setDate(weekStart.getDate() - weekStart.getDay()); // CN đầu tuần

    const MILLISECONDS_PER_HOUR = 60 * 60 * 1000;
    const HOUR_HEIGHT = 60; // px chiều cao 1 giờ trong lịch

    events.forEach(event => {
        let start = new Date(event.start_time);
        let end = event.end_time ? new Date(event.end_time) : new Date(event.start_time);

        if(event.all_day) {
            // Nếu sự kiện cả ngày, hiện dạng banner trên đầu cột ngày
            start.setHours(0,0,0,0);
            end.setHours(23,59,59,999);
        }

        // Nếu sự kiện nằm ngoài tuần thì bỏ qua
        if (end < weekStart || start >= new Date(weekStart.getTime() + 7*24*60*60*1000)) {
            return;
        }

        // Chia sự kiện thành từng ngày trong tuần
        let cur = new Date(Math.max(start, weekStart));
        let last = new Date(Math.min(end, new Date(weekStart.getTime() + 7*24*60*60*1000)));

        while (cur < last) {
            let dayStart = new Date(cur);
            dayStart.setHours(0,0,0,0);
            let dayEnd = new Date(dayStart);
            dayEnd.setHours(24,0,0,0);

            let segStart = cur;
            let segEnd = new Date(Math.min(dayEnd, last));

            // Nếu là ngày đầu, segStart là start; nếu là ngày cuối, segEnd là end; các ngày giữa là 0h-24h
            const dayIndex = segStart.getDay();

            const startHour = segStart.getHours() + segStart.getMinutes()/60;
            const durationHours = (segEnd - segStart) / MILLISECONDS_PER_HOUR;

            const weekBody = document.getElementById("week-body");
            if (!weekBody) return;

            const startRow = Math.floor(startHour);
            const cell = weekBody.children[startRow]?.children[dayIndex];
            if (!cell) {
                cur = dayEnd;
                continue;
            }

            const eventDiv = document.createElement("div");
            eventDiv.classList.add("event-item");
            eventDiv.textContent = event.title;

            const top = (startHour - startRow) * HOUR_HEIGHT;
            let height = durationHours * HOUR_HEIGHT;
            const maxHeight = (24 - startRow) * HOUR_HEIGHT;
            if (height > maxHeight - top) height = maxHeight - top;
            if (height < 10) height = 10;

            eventDiv.style.position = "absolute";
            eventDiv.style.top = `${top}px`;
            eventDiv.style.height = `${height}px`;
            eventDiv.style.left = "2px";
            eventDiv.style.right = "2px";
            eventDiv.style.backgroundColor = '#0d6efd';
            eventDiv.style.color = '#fff';
            eventDiv.style.borderRadius = "4px";
            eventDiv.style.padding = "2px 5px";
            eventDiv.style.fontSize = "0.75rem";
            eventDiv.style.overflow = "hidden";
            eventDiv.style.whiteSpace = "nowrap";
            eventDiv.style.textOverflow = "ellipsis";

            cell.style.position = "relative";
            cell.appendChild(eventDiv);

            eventDiv.addEventListener('click', function() {
                fetch(`/events/${event.id}`)
                    .then(res => res.json())
                    .then(data => {
                        // KHÔNG cộng thêm 7 tiếng nữa!
                        let start = new Date(data.start_time);
                        let end = data.end_time ? new Date(data.end_time) : null;

                        let html = `
                            <div><strong>Tiêu đề:</strong> ${data.title}</div>
                            <div><strong>Bắt đầu:</strong> ${start.toLocaleString('vi-VN')}</div>
                            <div><strong>Kết thúc:</strong> ${end ? end.toLocaleString('vi-VN') : ''}</div>
                            <div><strong>Cả ngày:</strong> ${data.all_day ? 'Có' : 'Không'}</div>
                            <div><strong>Ghi chú:</strong> ${data.description ?? ''}</div>
                        `;
                        document.getElementById('eventDetailBody').innerHTML = html;
                        let modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                        const deleteBtn = document.getElementById('deleteEventBtn');
                        deleteBtn.style.display = 'inline-block';
                        deleteBtn.onclick = function() {
                            if (confirm('Bạn có chắc muốn xóa sự kiện này?')) {
                                fetch(`/events/${event.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => {
                                    if (res.ok) {
                                        modal.hide();
                                        location.reload();
                                    } else {
                                        res.json().then(data => {
                                            alert(data.message || 'Xóa sự kiện thất bại!');
                                        }).catch(() => {
                                            alert('Xóa sự kiện thất bại!');
                                        });
                                    }
                                });
                            }
                        };
                        modal.show();
                    });
            });

            // Sang ngày tiếp theo
            cur = dayEnd;
        }
    });
}


// Gọi hàm sau khi render tuần
renderEventsOnWeekView(events);

</script>
</body>
</html>
