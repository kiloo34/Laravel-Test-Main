<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function show($id)
    {
        # code...
    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        $book = new Book(); // important
        $book->findOrFail($bookId); // important

        $validator = Validator::make($request->all(), 
        [
            'review'    => ['required', 'numeric', 'integer', 'between:1,10'],
            'comment'   => ['required'],
        ]);

        if($validator->fails()) {
            dd($validator->validate());
            return response()->json($validator->validate(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $bookReview = new BookReview();

        // bookreview section
        $bookReview->book_id = 1;
        $bookReview->user_id = 1;
        $bookReview->review = $request->input('review');
        $bookReview->comment = $request->input('comment');
        $bookReview->save();

        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        $data = BookReview::where([
            'id' => $reviewId,
            'book_id' => $bookId
        ])->first();
        // dd($data);
        if ($data) {
            $data->delete();
            return response()->json([], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
    }
}
