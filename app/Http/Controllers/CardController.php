<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();

        $card = Card::query()
            ->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'column_id' => $data['column_id']
            ]);

        return response($card->toJson());
    }

    public function read($id, Request $request)
    {
        $data = $request->all();

        $card = Card::query()
            ->where('id', $data['id'])
            ->where('column_id', $data['column_id'])
            ->first();

        return response($card->toJson());
    }

    public function readAll(Request $request)
    {
        $data = $request->all();

        $card = Card::query()
            ->where('column_id', $data['column_id'])
            ->get();

        return response($card->toJson());
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $card = Card::query()
            ->where('id', $data['id'])
            ->where('column_id', $data['column_id'])
            ->update(['title' => $data['title'], 'description' => $data['description'], 'is_done' => $data['is_done']]);

        return response(['status' => $card]);
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $card = Card::query()
            ->where('id', $data['id'])
            ->where('column_id', $data['column_id'])
            ->delete();
//        Board::destroy($data['id']);

        return response()->json(['status' => $card]);
    }
}
