<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $book_query = Book::with('reviews');
        if ($request->authors) {
            $author = explode(',', $request->authors);
            $book_query->whereHas('authors', function ($q) use ($author) {
                $q->whereIn('id', $author);
            });
        }

        // done list
        if ($request->sortColumn) {
            if ($request->sortColumn == 'avg_review') {
                $book_query->withCount(['reviews as avg_review' => function ($q) {
                    $q->select(\DB::raw('avg(review)'));
                }]);
                if ($request->sortDirection) {
                    $book_query->orderBy($request->sortColumn, $request->sortDirection);
                } else {
                    $book_query->orderBy($request->sortColumn);
                }
            } elseif ($request->sortDirection) {
                $book_query->orderBy($request->sortColumn, $request->sortDirection);
            } else {
                $book_query->orderBy($request->sortColumn);
            }
        }

        $books = $book_query->paginate(15);
        
        return BookResource::collection($books);
    }

    public function store(PostBookRequest $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'isbn'              => ['required', 'unique:books', 'size:13'],
            'title'             => ['required'],
            'description'       => ['required'],
            'authors'           => ['required', 'array', 'exists:authors,id'],
            'authors.*'         => ['required', 'exists:authors,id'],
            'published_year'    => ['required', 'digits:4', 'integer', 'between:1900,'.(date('Y')-1)],
        ]);

        if($validator->fails()) {
            dd($validator->validate());
            return response()->json($validator->validate(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $author = Author::find(1);
        $book = new Book();

        // book section
        $book->isbn = $request->input('isbn');
        $book->title = $request->input('title');
        $book->description = $request->input('description');
        $book->published_year = 2000;
        $book->description = $request->input('description');
        $book->save();
        // authors section
        $book->authors()->attach([1]);

        return new BookResource($book);
    }


}
