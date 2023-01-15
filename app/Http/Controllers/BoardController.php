<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();

        $board = Board::query()->create([
            'title' => $data['title'],
            'user_id' => $data['user_id']
        ]);

        return response($board->toJson());
    }

    public function read($id, Request $request)
    {
        $data = $request->all();

        $board = Board::query()->where('id', $data['id'])->first();

        return response($board->toJson());
    }

    public function readAll(Request $request)
    {
        $data = $request->all();

        $boards = Board::query()->where('user_id', $data['user_id'])->get();

        return response($boards->toJson());
    }

    public function update(Request $request)
    {
        $data = $request->all();

        Board::query()->where('user_id', $data['user_id'])->update([$data['title']]);

        return response()->json('board is updated');
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $board = Board::query()->where('id', $data['id'])->where('user_id', $data['user_id'])->delete();
//        Board::destroy($data['id']);

        return response()->json(['status' => $board]);
    }
}
