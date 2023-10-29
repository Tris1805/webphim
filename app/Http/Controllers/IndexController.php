<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Movie_Genre;
use App\Models\Rating;



use DB;

class IndexController extends Controller
{
    public function filter()
    {
        $order = $_GET['order'];
        $genre_get = $_GET['genre'];
        $country_get = $_GET['country'];
        $year_get = $_GET['year'];

        // Check if all filtering parameters are empty
        if (empty($genre_get) && empty($country_get) && empty($year_get) && empty($order)) {
            return redirect()->back();
        }

        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        // Start with the base query
        $movie = Movie::withCount('episode');

        // Apply filters
        if ($genre_get) {
            $movie = $movie->where('genre_id', $genre_get);
        }

        if ($country_get) {
            $movie = $movie->where('country_id', $country_get);
        }

        if ($year_get) {
            $movie = $movie->where('year', $year_get);
        }

        if ($order) {
            $movie = $movie->orderBy('title', 'ASC');
        }

        // Paginate the results
        $movie = $movie->orderBy('ngaycapnhat', 'DESC')->paginate(40);

        return view('pages.filter', compact('category', 'genre', 'country', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }


    public function timkiem()
    {
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
            $genre = Genre::orderBy('id', 'DESC')->get();
            $country = Country::orderBy('id', 'DESC')->get();
            $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
            $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

            $movie = Movie::withCount('episode')->where('title', 'LIKE', '%' . $search . '%')->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->paginate(8);
            return view('pages.search', compact('category', 'genre', 'country', 'search', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
        } else {
            return redirect()->to('/');
        }
    }

    public function home()
    {
        $phimhot = Movie::withCount('episode')->where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $category_home = Category::with('movie')->with(['movie' => function ($q) {
            $q->withCount('episode')->where('status', 1);
        }])->orderBy('id', 'DESC')->where('status', 1)->get();

        $movie_star = [];

        foreach ($phimhot_sidebar as $movie) {
            // Retrieve star ratings for the current movie
            $ratings = Rating::where('movie_id', $movie->id)->get();

            // Push the ratings for the current movie into the $movie_star array
            $movie_star[$movie->id] = $ratings;
        }
        return view('pages.home', compact(
            'category',
            'genre',
            'country',
            'category_home',
            'phimhot',
            'phimhot_sidebar',
            'phimhot_trailer',
            'movie_star',
        ));
    }
    public function category($slug)
    {
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $cate_slug = Category::where('slug', $slug)->first();
        $movie = Movie::withCount('episode')->where('category_id', $cate_slug->id)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->paginate(8);
        return view('pages.category', compact('category', 'genre', 'country', 'cate_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function year($year)
    {
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $year = $year;
        $movie = Movie::withCount('episode')->where('year', $year)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->paginate(8);
        return view('pages.year', compact('category', 'genre', 'country', 'year', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function tag($tag)
    {
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $country = Country::orderBy('id', 'DESC')->get();
        $tag = $tag;
        $movie = Movie::withCount('episode')->where('film_tag', 'LIKE', '%' . $tag . '%')->orderBy('ngaycapnhat', 'DESC')->paginate(10);
        return view('pages.tag', compact('category', 'genre', 'country', 'tag', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function genre($slug)
    {
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::withCount('episode')->where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $genre_slug = Genre::where('slug', $slug)->first();
        //Render by genres
        $movie_genre = Movie_Genre::where('genre_id', $genre_slug->id)->get();
        $many_genre = [];
        foreach ($movie_genre as $key => $value) {
            $many_genre[] = $value->movie_id;
        }
        $movie = Movie::withCount('episode')->whereIn('id', $many_genre)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->paginate(8);
        return view('pages.genre', compact('category', 'genre', 'country', 'genre_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function country($slug)
    {
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $country = Country::orderBy('id', 'DESC')->get();
        $country_slug = Country::where('slug', $slug)->first();
        $movie = Movie::withCount('episode')->where('country_id', $country_slug->id)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->paginate(8);
        return view('pages.country', compact('category', 'genre', 'country', 'country_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function movie($slug)
    {
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $movie = Movie::with('category', 'genre', 'country', 'movie_genre')->where('slug', $slug)->where('status', 1)->first();
        $first_ep = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'ASC')->take(1)->first();
        $episode = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', "DESC")->take(3)->get();
        $episode_current_list = Episode::with('movie')->where('movie_id', $movie->id)->get();
        $episode_current_list  = $episode_current_list->count();
        $rating = Rating::where('movie_id', $movie->id)->avg('rating');
        $rating = round($rating);
        $get_rating =  Rating::get();
        $count_total =  Rating::where('movie_id', $movie->id)->count();
        $related = Movie::with('category', 'genre', 'country')->where('category_id', $movie->category->id)->orderBy(\Illuminate\Support\Facades\DB::raw('RAND()'))->whereNotIn('slug', [$slug])->get();
        $count_views  = $movie->count_views;
        $count_views  += 1;
        $movie->count_views  = $count_views;
        $movie->save();
        return view('pages.movie', compact('category', 'genre', 'country', 'movie', 'related', 'phimhot_sidebar', 'phimhot_trailer', 'episode', 'first_ep', 'episode_current_list', 'rating', 'count_total', 'get_rating'));
    }
    public function add_rating(Request  $request)
    {
        $data = $request->all();
        $ip_address = $request->ip();
        $rating_count = Rating::where('movie_id', $data['movie_id'])->where('ip_address', $ip_address)->count();
        if ($rating_count > 0) {
            echo 'exist';
        } else {
            $rating = new Rating();
            $rating->movie_id = $data['movie_id'];
            $rating->rating = $data['index'];
            $rating->ip_address = $ip_address;
            $rating->save();
            echo 'done';
        }
    }
    public function watch($slug, $tap)
    {
        if (isset($tap)) {
            $tapphim = $tap;
        } else {
            $tapphim = 1;
        }
        $tapphim = substr($tap, 4, 20);
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $movie = Movie::with('category', 'genre', 'country', 'movie_genre', 'episode')->where('slug', $slug)->where('status', 1)->first();
        $ep = Episode::where('movie_id', $movie->id)->where('episode', $tapphim)->first();
        $related = Movie::with('category', 'genre', 'country')->where('category_id', $movie->category->id)->orderBy(\Illuminate\Support\Facades\DB::raw('RAND()'))->whereNotIn('slug', [$slug])->get();

        return view('pages.watch', compact('category', 'genre', 'country', 'movie', 'phimhot_sidebar', 'phimhot_trailer', 'ep', 'tapphim', 'related'));
    }
    public function episode()
    {
        return view('pages.episode');
    }
}
