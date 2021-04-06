<?php

namespace App\Http\Controllers;

use App\Models\GuestBook;
use Drandin\ClosureTableComments\ClosureTableService;
use Drandin\ClosureTableComments\Commentator;
use Illuminate\Http\Request;


class HomeController extends Controller
{

    public function index()
    {
        $commentator = new Commentator(new ClosureTableService());

        $comments = paginate($commentator->getTreeBranchArray(), 25);

        return view('pages.main', compact('comments'));
    }

    public function store(Request $request) {
        $validator = \Validator::make($request->all(), [
            'text' => 'max:1000|required',
            'img' => 'required|file|image|dimensions:min_width=100,min_height=100|max:100',
            'messageId' => 'nullable|numeric|exists:comments,id'
        ]);
       if ($validator->fails()) {
         return response()->json([ 'validate' => $validator->errors(), 'data' => $request->all()], 400);
       }
        if ( array_key_exists('messageId', $validator->getData())) {
            $guestBook = GuestBook::answer($validator->getData());
            return response()->json([$guestBook], 200);
        } else {
            $guestBook = GuestBook::create($validator->getData());
            return response()->json([$guestBook], 200);
        }
    }

    public function update(Request $request) {
        $validator = \Validator::make($request->all(), [
            'text' => 'max:1000|required',
            'img' => 'required|file|image|dimensions:min_width=100,min_height=100|max:100',
            'messageId' => 'nullable|numeric|exists:comments,id'
        ]);
        if ($validator->fails()) {
            return response()->json([ 'validate' => $validator->errors(), 'data' => $request->all()], 400);
        } else {
            $guestBook = GuestBook::updated($validator->getData());
            return response()->json($guestBook, 200);
        }
    }

}
