<?php

namespace App\Http\Controllers;

use App\Models\Movie_Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Episode;
use App\Models\Rating;
use Carbon\Carbon;
use File;
use Validator;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Movie::with('category', 'movie_genre', 'country', 'genre')->withCount('episode')->orderBy('id', 'DESC')->get();
        $category = Category::pluck('title', 'id');
        $country = Country::pluck('title', 'id');

        $path = public_path() . '/json_files/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        \Illuminate\Support\Facades\File::put($path . 'movies.json', json_encode($list));
        // $list_episode_count = Episode::
        return view('admincp.movie.index', compact('list', 'category', 'country'));
    }
    public function update_year(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);
        $movie->year = $data['year'];
        $movie->save();
    }
    public function update_season(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);
        $movie->season = $data['season'];
        $movie->save();
    }
    public function update_topview(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);
        $movie->topview = $data['topview'];
        $movie->save();
    }
    public function filter_topview(Request $request)
    {
        $data = $request->all();
        $movie = Movie::where('topview', $data['value'])->orderBy('ngaycapnhat', 'DESC')->take(5)->get();

        $output = '';
        foreach ($movie as $key => $value) {
            $movie_star = Rating::where('movie_id', $value->id)->get();
            if ($value->count_views >  0) {
                $count_views = $value->count_views;
            } else {
                $count_views = rand(100, 99999);
            }
            if ($value->resolution  == 0) {
                $text = 'SD';
            } else if ($value->resolution == 2) {
                $text = 'HD Cam';
            } else if ($value->resolution == 3) {
                $text =  'Cam';
            } else if ($value->resolution == 4) {
                $text =  'Full HD';
            } else if ($value->resolution == 5) {
                $text =  'Trailer';
            } else {
                $text = 'HD';
            }
            $output .= '<div class="item post-37176">
                    <a href="' . url('phim/' . $value->slug) . '" title="' . $value->title . '">
                        <div class="item-link">
                            <img src="' . url('uploads/movie/' . $value->image) . '"
                                class="lazy post-thumb" alt="' . $value->title . '"
                                title="' . $value->title . '" />
                            <span class="is_trailer">' . $text . '</span>
                        </div>
                        <p class="title">' . $value->title . '</p>
                        <div class="viewsCount" style="color: #9d9d9d;">
                            ' . $count_views . ' lượt quan tâm</div>
                        <div style="float: left;">
                            <ul class="list-inline rating" title="Average Rating">';

            $averageRating = $movie_star->avg('rating');

            // Generate star ratings based on the average rating
            for ($count = 1; $count <= $averageRating; $count++) {
                // Set the class for filled or empty stars based on the average rating
                $starClass = $count <= $averageRating ? 'filled-star' : 'empty-star';
                $output .= '<li title="star_rating" class="' . $starClass . '" style="font-size:20px; color:yellow; padding: 0">&#9733;</li>';
            }
            $output .= '</ul>
                        </div>
                    </a>
                </div>';
        }
        echo  $output;
    }
    public function filter_default(Request $request)
    {
        $data = $request->all();
        $movie = Movie::where('topview', 0)->orderBy('ngaycapnhat', 'DESC')->take(5)->get();
        $output = '';

        foreach ($movie as $key => $value) {
            $movie_star = Rating::where('movie_id', $value->id)->get();
            if ($value->count_views >  0) {
                $count_views = $value->count_views;
            } else {
                $count_views = rand(100, 99999);
            }
            if ($value->resolution  == 0) {
                $text = 'SD';
            } else if ($value->resolution == 2) {
                $text = 'HD Cam';
            } else if ($value->resolution == 3) {
                $text =  'Cam';
            } else if ($value->resolution == 4) {
                $text =  'Full HD';
            } else if ($value->resolution == 5) {
                $text =  'Trailer';
            } else {
                $text = 'HD';
            }
            $output .= '<div class="item post-37176">
                    <a href="' . url('phim/' . $value->slug) . '" title="' . $value->title . '">
                        <div class="item-link">
                            <img src="' . url('uploads/movie/' . $value->image) . '"
                                class="lazy post-thumb" alt="' . $value->title . '"
                                title="' . $value->title . '" />
                            <span class="is_trailer">' . $text . '</span>
                        </div>
                        <p class="title">' . $value->title . '</p>
                        <div class="viewsCount" style="color: #9d9d9d;">
                            ' . $count_views . ' lượt quan tâm</div>
                        <div style="float: left;">
                            <ul class="list-inline rating" title="Average Rating">';

            $averageRating = $movie_star->avg('rating');

            // Generate star ratings based on the average rating
            for ($count = 1; $count <= $averageRating; $count++) {
                // Set the class for filled or empty stars based on the average rating
                $starClass = $count <= $averageRating ? 'filled-star' : 'empty-star';
                $output .= '<li title="star_rating" class="' . $starClass . '" style="font-size:20px; color:yellow; padding: 0">&#9733;</li>';
            }
            $output .= '</ul>
                        </div>
                    </a>
                </div>';
        }
        echo  $output;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::pluck('title', 'id');
        $genre = Genre::pluck('title', 'id');
        $list_genre = Genre::all();
        $country = Country::pluck('title', 'id');
        return view('admincp.movie.form', compact('category', 'genre', 'country', 'list_genre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|unique:movies|max:255',
                'slug' => 'required|max:255',
                'description' => 'required|max:255',
                'status' => 'required',
                'name_eng' => 'required|max:255',
                'film_tag' => 'required|max:255',
                'film_duration' => 'required',
                'genre' => 'required',
                'resolution' => 'required',
                'trailer' => 'required',
                'phim_hot' => 'required',
                'category_id' => 'required',
                'thuocphim' => 'required',
                'country_id' => 'required',
                'phude' => 'required',
                'sotap' => 'required',

                'audienceRating' => 'required',
                'expertRating' => 'required',

                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            ],
            [
                'title.required' => 'Tên phim phải có nhé',
                'description.required' => 'Mô tả phim phải có nhé',
                'film_duration.required' => 'Thời lượng phim phải có nhé',
                'film_tag.required' => 'Tag phim phải có nhé',
                'genre.required' => 'Vui lòng chọn thể loại',
                'image.required' => 'Hình ảnh không đúng định dạng (jpg, jpeg,gif,svg) hoặc quá 2MB'
            ]
        );
        // $data = $request->all();
        $movie = new Movie();
        $movie->title = $data['title'];
        $movie->sotap = $data['sotap'];
        // $movie->sotap = $request->input('sotap');
        $movie->slug = $data['slug'];
        $movie->film_duration = $data['film_duration'];
        $movie->film_tag = $data['film_tag'];
        $movie->trailer = $data['trailer'];
        $movie->audienceRating = $data['audienceRating'];
        $movie->expertRating = $data['expertRating'];
        $movie->name_eng = $data['name_eng'];
        $movie->phim_hot = $data['phim_hot'];
        // $movie->sotap = $request->input('phim_hot');

        $movie->description = $data['description'];
        $movie->resolution = $data['resolution'];
        $movie->status = $data['status'];
        $movie->category_id = $data['category_id'];
        $movie->country_id = $data['country_id'];
        $movie->phude = $data['phude'];
        $movie->thuocphim = $data['thuocphim'];
        $movie->count_views = rand(100, 99999);

        $movie->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
        foreach ($data['genre'] as $key => $gen) {
            $movie->genre_id = $gen[0];
        }

        $get_image = $request->file('image');

        if ($get_image) {

            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 9999) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/movie/', $new_image);
            $movie->image = $new_image;
        }
        toastr()->success('Successfully', 'Thêm thành công');

        $movie->save();
        //Thêm nhiều thể loại phim
        $movie->movie_genre()->attach($data['genre']);
        return redirect()->route('movie.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::pluck('title', 'id');
        $genre = Genre::pluck('title', 'id');
        $country = Country::pluck('title', 'id');
        $movie =  Movie::find($id);
        $list_genre = Genre::all();
        $movie_genre  =  $movie->movie_genre;
        return view('admincp.movie.form', compact('category', 'genre', 'country', 'movie', 'list_genre', 'movie_genre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate(
            [
                'title' => 'required|max:255',
                'slug' => 'required|max:255',
                'description' => 'required',
                'status' => 'required',
                'name_eng' => 'required|max:255',
                'film_tag' => 'required|max:255',
                'film_duration' => 'required',
                'genre' => 'required',
                'resolution' => 'required',
                'trailer' => 'required',
                'phim_hot' => 'required',
                'category_id' => 'required',
                'thuocphim' => 'required',
                'country_id' => 'required',
                'phude' => 'required',
                'sotap' => 'required',
                'audienceRating' => 'required',
                'expertRating' => 'required',

            ],
            [
                'title.required' => 'Tên phim phải có nhé',
                'description.required' => 'Mô tả phim phải có nhé',
                'film_duration.required' => 'Thời lượng phim phải có nhé',
                'film_tag.required' => 'Tag phim phải có nhé',
                'genre.required' => 'Vui lòng chọn thể loại',
            ]
        );
        $movie = Movie::find($id);
        $movie->title = $data['title'];
        $movie->sotap = $data['sotap'];
        $movie->slug = $data['slug'];
        $movie->film_duration = $data['film_duration'];
        $movie->film_tag = $data['film_tag'];
        $movie->name_eng = $data['name_eng'];
        $movie->trailer = $data['trailer'];
        $movie->phim_hot = $data['phim_hot'];
        $movie->description = $data['description'];
        $movie->status = $data['status'];
        $movie->resolution = $data['resolution'];
        $movie->phude = $data['phude'];
        $movie->thuocphim = $data['thuocphim'];
        $movie->category_id = $data['category_id'];
        $movie->expertRating = $data['expertRating'];
        $movie->audienceRating = $data['audienceRating'];
        // $movie->genre_id = $data['genre_id'];
        $movie->country_id = $data['country_id'];
        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
        foreach ($data['genre'] as $key => $gen) {
            $movie->genre_id = $gen[0];
        }
        $get_image = $request->file('image');

        if ($get_image) {
            if (file_exists('uploads/movie/' . $movie->image)) {
                unlink('uploads/movie/' . $movie->image);
            } else {
                $get_name_image = $get_image->getClientOriginalName();
                $name_image = current(explode('.', $get_name_image));
                $new_image = $name_image . rand(0, 9999) . '.' . $get_image->getClientOriginalExtension();
                $get_image->move('uploads/movie/', $new_image);
                $movie->image = $new_image;
            }
        }
        $movie->save();
        $movie->movie_genre()->sync($data['genre']);
        toastr()->success('Successfully', 'Sửa thành công');
        return redirect()->route('movie.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);
        if (file_exists('uploads/movie/' . $movie->image)) {
            unlink('uploads/movie/' . $movie->image);
        }
        $movie->delete();
        Movie_Genre::whereIn('movie_id', [$movie->id])->delete();
        Episode::whereIn('movie_id', [$movie->id])->delete();
        $movie->delete();
        toastr()->success('Successfully', 'Xóa thành công');

        return redirect()->back();
    }
    public function choose_category(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['movie_id']);
        $movie->category_id =  $data['cate_id'];
        $movie->save();
        toastr()->success('Successfully', 'Sửa thành công');
    }
    public function choose_country(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['movie_id']);
        $movie->country_id =  $data['country_id'];
        $movie->save();
        toastr()->success('Successfully', 'Sửa thành công');
    }
    public function choose_status(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['movie_id']);
        $movie->status =  $data['status'];
        $movie->save();
        toastr()->success('Successfully', 'Sửa thành công');
    }
    public function choose_ToM(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['movie_id']);
        $movie->thuocphim =  $data['ToM'];
        $movie->save();
        toastr()->success('Successfully', 'Sửa thành công');
    }
    public function choose_resolution(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['movie_id']);
        $movie->resolution =  $data['resolution'];
        $movie->save();
        toastr()->success('Successfully', 'Sửa thành công');
    }
    public function choose_updateImage(Request $request)
    {
        $get_image = $request->file('file');
        $movie_id = $request->movie_id;
        if ($get_image) {
            $movie = Movie::find($movie_id);
            unlink('uploads/movie/' . $movie->image);
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 9999) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/movie/', $new_image);
            $movie->image = $new_image;
            $movie->save();
            toastr()->success('Successfully', 'Sửa thành công');
        }
    }
}
