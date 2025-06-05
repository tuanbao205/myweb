<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskList;

class ListController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        TaskList::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Danh sách mới đã được tạo!');
    }
}

