<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskList;

class ListController extends Controller
{
    // Tạo danh sách mới.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TaskList::create([
            'name' => $request->name,
            'user_id' => auth()->id() 
        ]);

        return redirect()->back()->with('success', 'Tạo danh sách thành công!');
    }

    // Cập nhật tên danh sách.
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $taskList = TaskList::findOrFail($id);

        if ($taskList->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa danh sách này.');
        }

        $taskList->name = $request->name;
        $taskList->save();

        return redirect()->back()->with('success', 'Cập nhật danh sách thành công!');
    }

    //Xoá danh sách nhiệm vụ.
    public function destroy($id)
    {
        $taskList = TaskList::findOrFail($id);

        $taskList->delete();

        return redirect()->back()->with('success', 'Xoá danh sách thành công!');
    }

}