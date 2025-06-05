<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskList;

class TaskController extends Controller
{
    /**
     * Hiển thị danh sách nhiệm vụ, theo danh sách nhiệm vụ được chọn hoặc mặc định.
     */
    public function index(Request $request)
    {
        $taskListId = $request->query('task_list_id');

        // Lấy tất cả danh sách nhiệm vụ để hiển thị dropdown
        $taskLists = TaskList::all();

        if ($taskListId) {
            // Lấy nhiệm vụ theo danh sách nhiệm vụ được chọn
            $tasks = Task::where('task_list_id', $taskListId)->latest()->get();
        } else {
            // Lấy danh sách "Nhiệm vụ của tôi" hoặc tạo mới nếu chưa có
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
            'title' => $request->input('title'),
            'date_time' => $request->input('date_time'),
            'description' => $request->input('description'),
            'all_day' => $request->has('all_day'),
            'task_list_id' => $request->input('task_list_id'),
        ]);

        return redirect()->route('tasks.index', ['task_list_id' => $request->input('task_list_id')])
            ->with('success', 'Đã tạo nhiệm vụ thành công!');
    }
}
