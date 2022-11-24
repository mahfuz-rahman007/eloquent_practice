<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $movies = Movie::all()->sortByDesc(function($movie) {
            return $movie->ratings->avg('rating');
        })->take(100);

        $movies = Movie::join('categories', 'categories.id','=','movies.category_id')
                        ->join('ratings', 'ratings.movie_id' , '=', 'movies.id')
                        ->groupBy('movies.id')
                        // ->orderByDesc([ DB::raw('AVG(ratings.rating) as rating') ])
                        ->select([
                            'movies.id',
                            DB::raw('GROUP_CONCAT(distinct movies.title) as title'),
                            DB::raw('GROUP_CONCAT(distinct movies.release_year) as release_year'),
                            DB::raw('GROUP_CONCAT(distinct categories.name) as category_name'),
                            DB::raw('AVG(distinct ratings.rating) as rating'),
                            DB::raw('COUNT(distinct ratings.id) as count'),
                        ])
                        ->take(100)->get();


        return view('home', compact('movies'));
    }
}
