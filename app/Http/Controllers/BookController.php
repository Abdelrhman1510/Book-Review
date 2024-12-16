<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');


        $booksQuery = Book::when($title, fn ($query, $title) => $query->title($title));


        switch ($filter) {
            case 'popular_last_month':
                $booksQuery->popularLastMonth()->limit(8);
                break;
            case 'popular_last_6months':
                $booksQuery->popularLast6Months()->limit(8);
                break;
            case 'highest_rated_last_month':
                $booksQuery->highestRatedLastMonth()->limit(8);
                break;
            case 'highest_rated_last_6months':
                $booksQuery->highestRatedLast6Months()->limit(8);
                break;
            default:
                $booksQuery->latest()->withAvg('reviews', 'rating')->withCount('reviews');
                break;
        }


        if ($filter === '' || $filter === null) {
            $books = $booksQuery->paginate(8);
        } else {
            $cacheKey = 'books:' . $filter . ':' . $title;
            $books = cache()->remember($cacheKey . $filter, now()->addMinutes(10), fn () => $booksQuery->get());
        }

        return view('books.index', [
            'books' => $books,
            'isPaginated' => $filter === '' || $filter === null,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

     public function show(Book $book): View
     {
         // Manually calculate and set the average rating to debug
         $book->loadCount('reviews')
              ->setAttribute('reviews_avg_rating', $book->reviews()->avg('rating') ?? 0.0);

         // Cache the paginated reviews separately
         $reviews = cache()->remember("book:{$book->id}:reviews", 3600, fn() =>
             $book->reviews()->latest()->paginate(5)
         );

         return view('books.show', [
             'book' => $book,
             'reviews' => $reviews,
         ]);
     }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
