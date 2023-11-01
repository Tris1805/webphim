@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="{{ route('movie.create') }}" class="btn btn-primary">Thêm Phim</a>
                <div class="table-responsive">
                    <table class="table " id="tablephim">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên phim</th>
                                <th scope="col">Tập phim</th>
                                <th scope="col">Số tập</th>

                                <th scope="col">Tags phim</th>
                                <th scope="col">Thời lượng</th>
                                <th scope="col">Hình ảnh</th>
                                {{-- <th scope="col">Phim hot</th> --}}
                                <th scope="col">Định dạng</th>
                                {{-- <th scope="col">Phụ đề</th> --}}
                                <!-- <th scope="col">Mô tả</th> -->
                                <th scope="col">Đường dẫn</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Thể loại</th>
                                <th scope="col">Thuộc phim</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Quốc gia</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Ngày cập nhật</th>
                                <th scope="col">Năm phim</th>
                                <th scope="col">Top views</th>
                                <th scope="col">Season</th>
                                <th scope="col">Quản lý</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $key => $cate)
                                <tr>
                                    <th scope="row">{{ $key }}</th>
                                    <td>{{ $cate->title }}</td>
                                    <td> <a href="{{ route('add-episode', [$cate->id]) }}" class="btn btn-primary btn-sm">+
                                            Tập</a>
                                        @foreach ($cate->episode as $key => $episode)
                                            <span class="badge bg-dark"><a href=""
                                                    style="color: white">{{ $episode->episode }}</a></span>
                                        @endforeach
                                    </td>
                                    <td>{{ $cate->episode_count }} / {{ $cate->sotap }}</td>
                                    <td>{{ $cate->film_tag }}</td>
                                    <td>{{ $cate->film_duration }}</td>

                                    <td>
                                        <img width="100" src="{{ asset('uploads/movie/' . $cate->image) }}">
                                        <input type="file" data-movie_id="{{ $cate->id }}"
                                            id="file-{{ $cate->id }}" class="form-control-file file-image"
                                            accept="image/*">
                                        <span id="error_image"></span>
                                    </td>
                                    {{-- <td>
                                    @if ($cate->phim_hot == 0)
                                        Không
                                    @else
                                        Có
                                    @endif
                                </td> --}}
                                    <td>
                                        <select name="" id="{{ $cate->id }}" class="choose_resolution">
                                            @if ($cate->resolution == 0)
                                                <option selected value="0">SD</option>
                                                <option value="1">HD</option>
                                                <option value="2">HD Cam</option>
                                                <option value="3">Cam</option>
                                                <option value="4">Full HD</option>
                                                <option value="5">Trailer</option>
                                            @elseif($cate->resolution == 2)
                                                <option value="0">SD</option>
                                                <option value="1">HD</option>
                                                <option selected value="2">HD Cam</option>
                                                <option value="3">Cam</option>
                                                <option value="4">Full HD</option>
                                                <option value="5">Trailer</option>
                                            @elseif($cate->resolution == 3)
                                                <option value="0">SD</option>
                                                <option value="1">HD</option>
                                                <option value="2">HD Cam</option>
                                                <option selected value="3">Cam</option>
                                                <option value="4">Full HD</option>
                                                <option value="5">Trailer</option>
                                            @elseif($cate->resolution == 4)
                                                <option value="0">SD</option>
                                                <option value="1">HD</option>
                                                <option value="2">HD Cam</option>
                                                <option value="3">Cam</option>
                                                <option selected value="4">Full HD</option>
                                                <option value="5">Trailer</option>
                                            @elseif($cate->resolution == 5)
                                                <option value="0">SD</option>
                                                <option value="1">HD</option>
                                                <option value="2">HD Cam</option>
                                                <option value="3">Cam</option>
                                                <option value="4">Full HD</option>
                                                <option selected value="5">Trailer</option>
                                            @else
                                                <option value="0">SD</option>
                                                <option selected value="1">HD</option>
                                                <option value="2">HD Cam</option>
                                                <option value="3">Cam</option>
                                                <option value="4">Full HD</option>
                                                <option value="5">Trailer</option>
                                            @endif
                                        </select>
                                    </td>
                                    {{-- <td>
                                    @if ($cate->phude == 0)
                                        Phụ đề
                                    @else
                                        Thuyết minh
                                    @endif
                                </td> --}}
                                    <!-- <td>{{ $cate->description }}</td> -->
                                    <td>{{ $cate->slug }}</td>
                                    <td>
                                        <select name="" id="{{ $cate->id }}" class="choose_status">
                                            @if ($cate->status)
                                                <option selected value="1">Hiển thị</option>
                                                <option value="0">Ẩn</option>
                                            @else
                                                <option value="1">Hiển thị</option>
                                                <option selected value="0">Ẩn</option>
                                            @endif
                                        </select>

                                    </td>
                                    {{-- <td>{{ $cate->category->title }}</td> --}}
                                    <td>
                                        @foreach ($cate->movie_genre as $gen)
                                            <span class="badge badge-dark"> {{ $gen->title }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <select name="" id="{{ $cate->id }}" class="choose_ToM">
                                            @if ($cate->thuocphim == 'phimle')
                                                <option selected value="phimle">Phim lẻ</option>
                                                <option value="phimbo">Phim bộ</option>
                                            @else
                                                <option value="phimle">Phim lẻ</option>
                                                <option selected value="phimbo">Phim bộ</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td style="min-width: 128px;">
                                        {!! Form::select('category_id', $category, isset($cate) ? $cate->category->id : '', [
                                            'class' => 'form-control choose_category',
                                            'id' => $cate->id,
                                        ]) !!}
                                    </td>
                                    <td style="min-width: 128px;">
                                        {!! Form::select('country_id', $country, isset($cate) ? $cate->country->id : '', [
                                            'class' => 'form-control choose_country',
                                            'id' => $cate->id,
                                        ]) !!}
                                        {{-- {{ $cate->country->title }} --}}
                                    </td>
                                    <td>{{ $cate->ngaytao }}</td>
                                    <td>{{ $cate->ngaycapnhat }}</td>
                                    <td>
                                        {!! Form::selectYear('year', 2000, 2023, isset($cate->year) ? $cate->year : '', [
                                            'class' => 'select-year',
                                            'id' => $cate->id,
                                            'placeholder' => '--Năm phim--',
                                        ]) !!}
                                    </td>
                                    <td>
                                        {!! Form::select(
                                            'topview',
                                            ['0' => 'Ngày', '1' => 'Tuần', '2' => 'Tháng'],
                                            isset($cate->topview) ? $cate->topview : '',
                                            [
                                                'class' => 'select-topview',
                                                'id' => $cate->id,
                                                'placeholder' => '--Views--',
                                            ],
                                        ) !!}

                                    </td>
                                    <td>
                                        @csrf

                                        <form action="" method="POST">
                                            @csrf
                                            {!! Form::selectRange('season', 0, 20, isset($cate->season) ? $cate->season : '', [
                                                'class' => 'select-season',
                                                'id' => $cate->id,
                                            ]) !!}
                                        </form>
                                    </td>
                                    <td>
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['movie.destroy', $cate->id],
                                            'onsubmit' => 'return confirm("Bạn có chắc muốn xóa?")',
                                        ]) !!}
                                        {!! Form::submit('Xóa', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                        <a href="{{ route('movie.edit', $cate->id) }}" class="btn btn-warning">Sửa</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
