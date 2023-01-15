<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();

        $column = Column::query()
            ->create([
                'title' => $data['title'],
                'board_id' => $data['board_id']
            ]);

        return response($column->toJson());
    }

    public function read($id, Request $request)
    {
        $data = $request->all();

        $column = Column::query()
            ->where('id', $data['id'])
            ->where('board_id', $data['board_id'])
            ->first();

        return response($column->toJson());
    }

    public function readAll(Request $request)
    {
        $data = $request->all();

        $column = Column::query()
            ->where('board_id', $data['board_id'])
            ->with('cards')
            ->get();

        return response($column->toJson());
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $column = Column::query()
            ->where('id', $data['id'])
            ->where('board_id', $data['board_id'])
            ->update(['title' => $data['title']]);

        return response(['status' => $column]);
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $column = Column::query()
            ->where('id', $data['id'])
            ->where('board_id', $data['board_id'])
            ->delete();
//        Board::destroy($data['id']);

        return response()->json(['status' => $column]);
    }
}
