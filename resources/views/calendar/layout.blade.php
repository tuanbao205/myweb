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
                        <div id="weekTitle" class="fw-bold fs-5 mb-2"></div> <div class="week-header" id="week-header"></div>
                        <div class="croll">
                            <div class="time-labels" id="time-labels"></div>
                            <div class="week-body" id="week-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailModalLabel">Chi tiết sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" id="eventDetailBody">
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="editEventBtn" style="display:none;">Chỉnh sửa</button>
                    <button type="button" class="btn btn-danger" id="deleteEventBtn" style="display:none;">Xóa</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentDate = new Date();
    let editingEventId = null; // Biến toàn cục lưu id sự kiện đang chỉnh sửa
    
    // Biến lưu ngày hiện tại được chọn (cho lịch tháng nhỏ)
    let selectedDate = new Date(currentDate);

    // Chuyển $events từ PHP sang JS
    // Giả định biến `events` này chứa TẤT CẢ các sự kiện mà bạn muốn hiển thị
    // trong toàn bộ lịch, không chỉ tuần đầu tiên.
    const allEvents = @json($events);

    // Hàm chọn ngày trong lịch tháng
    function selectDate(year, month, day) {
        selectedDate = new Date(year, month, day);
        currentDate = new Date(year, month, day); // Cập nhật luôn currentDate về ngày vừa chọn

        // Cập nhật lại lịch tháng, tuần và tiêu đề
        taolich(currentDate.getMonth(), currentDate.getFullYear(), false);
        renderWeekView(currentDate); // render tuần dựa trên ngày vừa chọn
        updateWeekTitle();
        renderEventsOnWeekView(allEvents); // Vẽ sự kiện theo tuần mới
    }

    // Hàm tạo lịch tháng
    function taolich(month, year, updateMainTitle = true) {
        const taobody = document.getElementById("calendar-body");
        taobody.innerHTML = ""; // Xóa nội dung cũ

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement("tr");
            for (let j = 0; j < 7; j++) {
                const cell = document.createElement("td");
                if (i === 0 && j < firstDay) {
                    cell.innerText = "";
                } else if (date > daysInMonth) {
                    cell.innerText = "";
                } else {
                    cell.innerText = date;
                    if (
                        date === today.getDate() &&
                        month === today.getMonth() &&
                        year === today.getFullYear()
                    ) {
                        cell.classList.add("today");
                    }
                    // Thêm class 'selected-day' nếu là ngày đang được chọn
                    if (
                        date === selectedDate.getDate() &&
                        month === selectedDate.getMonth() &&
                        year === selectedDate.getFullYear()
                    ) {
                        cell.classList.add("selected-day");
                    }

                    cell.style.cursor = "pointer";
                    const thisDate = date;
                    cell.onclick = () => selectDate(year, month, thisDate);
                    date++;
                }
                row.appendChild(cell);
            }
            taobody.appendChild(row);
            if (date > daysInMonth) break;
        }

        // Cập nhật tiêu đề tháng ở cả hai vị trí
        if (updateMainTitle) {
            updateMonthTitles(month, year);
        }
    }

    // Hàm cập nhật tiêu đề tháng ở cả hai vị trí
    function updateMonthTitles(month, year) {
        document.getElementById("monthYear").innerText = `Tháng ${month + 1}, ${year}`;
        document.getElementById("calendar-title").innerText = `Tháng ${month + 1}, ${year}`;
    }

    // Hàm cập nhật tiêu đề tuần
    function updateWeekTitle() {
        document.getElementById("weekTitle").innerText = ""; // Xóa tiêu đề tuần, không hiển thị gì cả
    }

    function getWeekDates(date = new Date()) {
        const day = date.getDay(); // 0 for Sunday, 1 for Monday, ..., 6 for Saturday
        const start = new Date(date);
        start.setDate(date.getDate() - day); // Set to Sunday of the current week
        start.setHours(0,0,0,0); // Reset time to beginning of the day

        const week = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(start);
            d.setDate(start.getDate() + i);
            week.push({
                label: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][i],
                date: d.getDate(),
                full: d // Thêm đối tượng Date đầy đủ
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
            // Thêm class 'selected-day-header' nếu ngày hiện tại được chọn là một trong các ngày trong tuần đang hiển thị
            if (day.full.toDateString() === selectedDate.toDateString()) {
                div.classList.add('selected-day-header');
            }
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
        // Khi chuyển tháng, selectedDate sẽ tự động điều chỉnh trong taolich dựa vào currentDate
        taolich(currentDate.getMonth(), currentDate.getFullYear(), true);
        renderWeekView(selectedDate); // Render tuần dựa trên selectedDate
        updateWeekTitle();
        renderEventsOnWeekView(allEvents); // Vẽ lại sự kiện
    });

    document.getElementById("next-month").addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        // Khi chuyển tháng, selectedDate sẽ tự động điều chỉnh trong taolich dựa vào currentDate
        taolich(currentDate.getMonth(), currentDate.getFullYear(), true);
        renderWeekView(selectedDate); // Render tuần dựa trên selectedDate
        updateWeekTitle();
        renderEventsOnWeekView(allEvents); // Vẽ lại sự kiện
    });

    document.getElementById("btn-prev").addEventListener("click", () => {
        currentDate.setDate(currentDate.getDate() - 7);
        selectedDate.setDate(selectedDate.getDate() - 7); // Cập nhật selectedDate theo tuần mới
        taolich(currentDate.getMonth(), currentDate.getFullYear(), true); // Cập nhật lịch tháng để làm nổi bật ngày đúng
        renderWeekView(currentDate); // render tuần mới
        updateWeekTitle();
        renderEventsOnWeekView(allEvents); // Vẽ lại sự kiện
    });

    document.getElementById("btn-next").addEventListener("click", () => {
        currentDate.setDate(currentDate.getDate() + 7);
        selectedDate.setDate(selectedDate.getDate() + 7); // Cập nhật selectedDate theo tuần mới
        taolich(currentDate.getMonth(), currentDate.getFullYear(), true); // Cập nhật lịch tháng để làm nổi bật ngày đúng
        renderWeekView(currentDate); // render tuần mới
        updateWeekTitle();
        renderEventsOnWeekView(allEvents); // Vẽ lại sự kiện
    });

    document.getElementById("btn-today").addEventListener("click", () => {
        currentDate = new Date();
        selectedDate = new Date(); // Cập nhật selectedDate về hôm nay
        taolich(currentDate.getMonth(), currentDate.getFullYear(), true);
        renderWeekView(currentDate);
        updateWeekTitle();
        renderEventsOnWeekView(allEvents); // Vẽ lại sự kiện
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
                // Nếu đang chỉnh sửa, gửi yêu cầu xóa sự kiện cũ, sau đó reload
                // LƯU Ý: Cách này không tối ưu vì sẽ tạo sự kiện mới rồi mới xóa cái cũ.
                // Nếu Laravel API của bạn hỗ trợ PUT/PATCH cho chỉnh sửa, bạn nên dùng nó.
                if (editingEventId) {
                    fetch(`/events/${editingEventId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        editingEventId = null;
                        location.reload(); // Tải lại trang để lấy dữ liệu event mới
                    }).catch(error => console.error('Error deleting old event:', error));
                } else {
                    location.reload(); // Tải lại trang để lấy dữ liệu event mới
                }
                
                // Đóng modal và reset form
                const modalEl = document.getElementById('createTaskModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                document.getElementById('createTaskForm').reset();
            } else {
                alert('Lỗi khi tạo sự kiện');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi mạng hoặc máy chủ');
        });
    }

    // Hàm hiển thị event lên lịch tuần (ví dụ: chèn vào các ô giờ tương ứng)
    function renderEventsOnWeekView(events) {
        const weekBody = document.getElementById("week-body");
        // Xóa tất cả các sự kiện cũ trước khi render lại
        const oldEvents = weekBody.querySelectorAll('.event-item');
        oldEvents.forEach(eventDiv => eventDiv.remove());

        const weekDates = getWeekDates(currentDate);
        const weekStart = weekDates[0].full; // Lấy Chủ Nhật đầu tuần hiện tại
        const weekEnd = weekDates[6].full; // Lấy Thứ Bảy cuối tuần hiện tại
        weekEnd.setHours(23,59,59,999); // Set đến cuối ngày Thứ Bảy

        const MILLISECONDS_PER_HOUR = 60 * 60 * 1000;
        const HOUR_HEIGHT = 60; // px chiều cao 1 giờ trong lịch

        events.forEach(event => {
            let start = new Date(event.start_time);
            let end = event.end_time ? new Date(event.end_time) : new Date(event.start_time);

            if(event.all_day) {
                // Nếu sự kiện cả ngày, mở rộng thời gian từ 0h đến 23:59:59
                start.setHours(0,0,0,0);
                end.setHours(23,59,59,999);
            }

            // Nếu sự kiện không nằm trong tuần hiện tại thì bỏ qua
            // Điều kiện này sẽ lọc các sự kiện từ `allEvents`
            if (end < weekStart || start > weekEnd) {
                return;
            }

            // Lặp qua từng ngày mà sự kiện kéo dài trong tuần hiện tại
            let currentSegmentStart = new Date(Math.max(start.getTime(), weekStart.getTime()));
            let currentSegmentEnd;

            while (currentSegmentStart <= end && currentSegmentStart <= weekEnd) {
                let dayOfSegment = new Date(currentSegmentStart);
                dayOfSegment.setHours(0,0,0,0); // Đặt về đầu ngày

                let dayEndBoundary = new Date(dayOfSegment);
                dayEndBoundary.setHours(23,59,59,999); // Cuối ngày hiện tại

                currentSegmentEnd = new Date(Math.min(end.getTime(), dayEndBoundary.getTime(), weekEnd.getTime()));

                if (currentSegmentStart > currentSegmentEnd) {
                    break; // Ngừng nếu không còn thời gian để vẽ
                }

                const dayIndex = currentSegmentStart.getDay(); // Lấy thứ trong tuần (0: CN, 1: T2, ...)
                const startHour = currentSegmentStart.getHours() + currentSegmentStart.getMinutes()/60;
                const durationHours = (currentSegmentEnd.getTime() - currentSegmentStart.getTime()) / MILLISECONDS_PER_HOUR;

                const weekBody = document.getElementById("week-body");
                const startRow = Math.floor(startHour);
                const cell = weekBody.children[startRow]?.children[dayIndex];
                
                if (!cell) {
                     currentSegmentStart = new Date(dayEndBoundary.getTime() + MILLISECONDS_PER_HOUR); // Chuyển sang ngày tiếp theo
                     continue;
                }

                const eventDiv = document.createElement("div");
                eventDiv.classList.add("event-item");

                const maxTitleLength = 18;
                let displayTitle = event.title.length > maxTitleLength
                    ? event.title.slice(0, maxTitleLength) + "..."
                    : event.title;
                eventDiv.innerHTML = `<strong title="${event.title.replace(/"/g, '&quot;')}">${displayTitle}</strong>`;

                const top = (startHour - startRow) * HOUR_HEIGHT;
                let height = durationHours * HOUR_HEIGHT;
                if (height < 10) height = 10; // Đảm bảo event có chiều cao tối thiểu

                eventDiv.style.position = "absolute";
                eventDiv.style.top = `${top}px`;
                eventDiv.style.height = `${height}px`;
                eventDiv.style.left = "2px";
                eventDiv.style.right = "2px";
                eventDiv.style.backgroundColor = '#0d6efd';
                eventDiv.style.color = '#fff';
                eventDiv.style.borderRadius = "4px";
                eventDiv.style.padding = "2px 5px";
                eventDiv.style.fontSize = "0.85rem";
                eventDiv.style.overflow = "hidden";
                eventDiv.style.whiteSpace = "nowrap";
                eventDiv.style.textOverflow = "ellipsis";
                eventDiv.style.cursor = "pointer";

                cell.style.position = "relative";
                cell.appendChild(eventDiv);

                // Thêm trình xử lý sự kiện click cho từng sự kiện
                eventDiv.addEventListener('click', function() {
                    // Fetch chi tiết sự kiện khi click
                    fetch(`/events/${event.id}`)
                        .then(res => res.json())
                        .then(data => {
                            let startDisplay = new Date(data.start_time).toLocaleString('vi-VN');
                            let endDisplay = data.end_time ? new Date(data.end_time).toLocaleString('vi-VN') : 'N/A';

                            let html = `
                                <div><strong>Tiêu đề:</strong> ${data.title}</div>
                                <div><strong>Bắt đầu:</strong> ${startDisplay}</div>
                                <div><strong>Kết thúc:</strong> ${data.all_day ? 'Cả ngày' : endDisplay}</div>
                                <div><strong>Ghi chú:</strong> ${data.description ?? ''}</div>
                            `;
                            document.getElementById('eventDetailBody').innerHTML = html;
                            let modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                            const deleteBtn = document.getElementById('deleteEventBtn');
                            const editBtn = document.getElementById('editEventBtn');
                            deleteBtn.style.display = 'inline-block';
                            editBtn.style.display = 'inline-block';

                            // Xử lý nút Xóa
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
                                            res.text().then(text => alert(text));
                                        }
                                    });
                                }
                            };

                            // Xử lý nút Chỉnh sửa
                            editBtn.onclick = function() {
                                editingEventId = data.id;
                                document.getElementById('taskTitle').value = data.title;
                                document.getElementById('allDayCheck').checked = data.all_day;
                                document.getElementById('startDate').value = data.start_time ? data.start_time.slice(0,16) : '';
                                if (data.end_time) {
                                    document.getElementById('endDate').value = data.end_time.slice(0,16);
                                } else {
                                    document.getElementById('endDate').value = '';
                                }
                                document.getElementById('taskDescription').value = data.description ?? '';
                                toggleTimeInputs();
                                modal.hide();
                                let createModal = new bootstrap.Modal(document.getElementById('createTaskModal'));
                                createModal.show();
                            };

                            modal.show();
                        });
                });
                
                // Chuẩn bị cho phân đoạn tiếp theo (ngày tiếp theo)
                currentSegmentStart = new Date(currentSegmentEnd.getTime() + 1); // Bắt đầu từ 1ms sau khi phân đoạn này kết thúc
            }
        });
    }

    // Khởi tạo ban đầu
    taolich(currentDate.getMonth(), currentDate.getFullYear(), true);
    renderWeekView(currentDate);
    updateWeekTitle();
    renderEventsOnWeekView(allEvents); // Gọi lần đầu để hiển thị sự kiện
</script>
</body>
</html>