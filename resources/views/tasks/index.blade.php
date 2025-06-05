@php
  use App\Models\TaskList;
  $taskLists = TaskList::all();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('frontend/css/todolist.css') }}" rel="stylesheet">
</head>
<body>
  <header>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-4">
          <a href="{{ route('calendar.layout') }}"><img src="{{ asset('frontend/images/calendar.png') }}" alt="logocld" class="iconheader calendar"></a>
          <a href="#"><img src="{{ asset('frontend/images/to-do-list.png') }}" alt="logotdl" class="iconheader todolist"></a>
        </div>
        <div class="col-sm-4"></div>
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
    <div class="row">
      <div class="col-sm-3">
        <button data-bs-toggle="modal" data-bs-target="#createTaskModal">
          <img src="{{ asset('frontend/images/plus.png') }}" alt=""> Tạo
        </button>
        <button data-bs-toggle="modal" data-bs-target="#createListModal">
          <img src="{{ asset('frontend/images/plus.png') }}" alt=""> Tạo danh sách mới
        </button>
        <button class="btn btn-light w-100 text-start b3" type="button" data-bs-toggle="collapse" data-bs-target="#taskListDropdown" aria-expanded="false" aria-controls="taskListDropdown">
          <img src="{{ asset('frontend/images/tick.png') }}" alt=""> Danh sách nhiệm vụ
        </button>
        <div class="collapse show" id="taskListDropdown">
          <div class="card card-body">
            @foreach ($taskLists as $list)
            <form method="GET" action="{{ route('tasks.index') }}" style="display: inline;">
              <input type="hidden" name="task_list_id" value="{{ $list->id }}">
              <button 
              type="submit" 
              class="btn btn-light mb-2 {{ (isset($taskListId) && $taskListId == $list->id) ? 'active' : '' }}">
              {{ $list->name }}
              </button>
            </form>
            @endforeach
            @if ($taskLists->isEmpty())
              <p class="text-muted">Chưa có danh sách nào.</p>
            @endif
          </div>
        </div>
      </div>

      <div class="col-sm-9 content-right">
        <h2>Danh sách các nhiệm vụ</h2>
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <ul class="list-group">
          @forelse($tasks as $task)
            <li class="list-group-item">
              <h5>{{ $task->title }} 
                <span class="badge bg-info">
                  {{ $task->all_day ? 'Cả ngày' : \Carbon\Carbon::parse($task->date_time)->format('d/m/Y H:i') }}
                </span>
              </h5>
              @if($task->description)
                <p>{{ $task->description }}</p>
              @endif
            </li>
          @empty
            <li class="list-group-item">Chưa có nhiệm vụ nào.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </section>

  <!-- Modal tạo danh sách -->
  <div class="modal fade" id="createListModal" tabindex="-1" aria-labelledby="createListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="createListForm" method="POST" action="{{ route('task-lists.store') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tạo danh sách mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="listName" class="form-label">Tên danh sách</label>
              <input type="text" class="form-control" id="listName" name="name" placeholder="Nhập tên danh sách..." required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Tạo</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal tạo nhiệm vụ -->
  <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tạo nhiệm vụ mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="taskTitle" class="form-label">Tiêu đề</label>
              <input type="text" name="title" class="form-control" id="taskTitle" required>
            </div>
            <div class="mb-3">
              <label for="taskDate" class="form-label">Ngày và giờ</label>
              <input type="datetime-local" name="date_time" class="form-control" id="taskDate" required>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="all_day" id="allDayCheck" value="1">
              <label class="form-check-label" for="allDayCheck">Cả ngày</label>
            </div>
            <div class="mb-3">
              <label for="taskDescription" class="form-label">Mô tả</label>
              <textarea class="form-control" name="description" id="taskDescription" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="taskList" class="form-label">Chọn danh sách nhiệm vụ</label>
              <select name="task_list_id" id="taskList" class="form-select" required>
                <option value="" disabled selected>-- Chọn danh sách --</option>
                @foreach($taskLists as $list)
                  <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Tạo</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Ẩn/hiện trường ngày giờ khi chọn "Cả ngày"
    document.addEventListener('DOMContentLoaded', function () {
      const allDayCheck = document.getElementById('allDayCheck');
      const taskDateInput = document.getElementById('taskDate');

      function toggleDateInputType() {
        taskDateInput.type = allDayCheck.checked ? 'date' : 'datetime-local';
      }

      toggleDateInputType();
      allDayCheck.addEventListener('change', toggleDateInputType);
    });
  </script>
</body>
</html>
