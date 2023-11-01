<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Movie;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_episode = Episode::with('movie')->orderBy('episode', 'DESC')->get();
        return view('admincp.episode.index', compact('list_episode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_movie = Movie::orderBy('id', 'DESC')->pluck('title', 'id');
        return view('admincp.episode.form', compact('list_movie'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $ep_check = Episode::where("episode",  $data['episode'])->where('movie_id', $data['movie_id'])->orderBy('episode', 'DESC')->count();
        // dd($ep_check);
        if ($ep_check > 0) {
            echo '<script>alert("Tập phim đã tồn tại");</script>';
            echo '<script>window.history.back();</script>';
            // return redirect()->back();
        } else {
            $ep = new Episode();
            $ep->movie_id = $data['movie_id'];
            $ep->linkphim = $data['link'];
            $ep->episode = $data['episode'];
            $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
            $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
            toastr()->success('Successfully', 'Thêm thành công');
            $ep->save();
            return redirect()->back();
        }
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
        $list_movie = Movie::orderBy('id', 'DESC')->pluck('title', 'id');
        $episode = Episode::find($id);
        return view('admincp.episode.form', compact('episode', 'list_movie'));
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

        $data = $request->all();
        $ep = new Episode();
        $ep = Episode::find($id);
        $ep->movie_id = $data['movie_id'];
        $ep->linkphim = $data['link'];
        $ep->episode = $data['episode'];
        $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        toastr()->success('Successfully', 'Sủa thành công');

        $ep->save();
        return redirect()->to('episode');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ep = Episode::find($id)->delete();
        return redirect()->to('episode');
    }
    public function select_movie()
    {
        $id = $_GET['id'];
        $movie_by_id =  Movie::find($id);
        echo $movie_by_id->sotap;
        $output = '<option>---Chọn tập phim---</option>';
        if ($movie_by_id->thuocphim == 'phimbo') {
            for ($i = 1; $i <= $movie_by_id->sotap; $i++) {
                $output .= '<option value="' . $i . '" >' . $i . '</option>';
            }
        } else {
            $output .= '<option value="HD" >HD</option>';
            $output .= '<option value="FullHD" >FullHD</option>';
        }
        echo $output;
    }
    public function add_episode($id)
    {
        $movie = Movie::find($id);
        $list_episode = Episode::with('movie')->where("movie_id", $id)->orderBy('episode', 'DESC')->get();
        return view('admincp.episode.add_episode', compact('list_episode', 'movie'));
    }
}
