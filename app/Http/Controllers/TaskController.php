<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskList;

class TaskController extends Controller
{
    /**
     * Hiển thị danh sách nhiệm vụ.
     */
    public function index(Request $request)
    {
        $taskListId = $request->query('task_list_id');
        $taskLists = TaskList::all();

        if ($taskListId) {
            $tasks = Task::where('task_list_id', $taskListId)->latest()->get();
        } else {
            $defaultList = TaskList::firstOrCreate(['name' => 'Nhiệm vụ của tôi']);
            $tasks = Task::where('task_list_id', $defaultList->id)->latest()->get();
            $taskListId = $defaultList->id;
        }

        return view('tasks.index', compact('tasks', 'taskLists', 'taskListId'));
    }

    /**
     * Lưu nhiệm vụ mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date_time' => 'required|date',
            'description' => 'nullable|string',
            'all_day' => 'nullable|boolean',
            'task_list_id' => 'required|exists:task_lists,id',
        ]);

        Task::create([
            'title' => $request->title,
            'date_time' => $request->date_time,
            'description' => $request->description,
            'all_day' => $request->has('all_day'),
            'task_list_id' => $request->task_list_id,
        ]);

        return redirect()->route('tasks.index', ['task_list_id' => $request->task_list_id])
                         ->with('success', 'Đã tạo nhiệm vụ thành công!');
    }

    /**
     * Cập nhật nhiệm vụ.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date_time' => 'required|date',
            'description' => 'nullable|string',
            'all_day' => 'nullable|boolean',
            'task_list_id' => 'required|exists:task_lists,id',
        ]);

        $task->update([
            'title' => $request->title,
            'date_time' => $request->date_time,
            'description' => $request->description,
            'all_day' => $request->has('all_day'),
            'task_list_id' => $request->task_list_id,
        ]);

        return redirect()->route('tasks.index', ['task_list_id' => $request->task_list_id])
                         ->with('success', 'Đã cập nhật nhiệm vụ!');
    }

    /**
     * Xóa nhiệm vụ.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Đã xóa nhiệm vụ!');
    }
    public function edit(Task $task)
{
    $taskLists = TaskList::all();
    return view('tasks.index', [ // Dùng index làm view chính
        'editTask' => $task,     // Truyền task cần sửa
        'taskLists' => $taskLists,
        'tasks' => Task::where('task_list_id', $task->task_list_id)->latest()->get(),
        'taskListId' => $task->task_list_id,
    ]);
}


}
