@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <a href="{{ route('episode.create') }}" class="btn btn-primary" style="width: 100%">Thêm tập phim</a>
            <div class="col-md-12">

                <table class="table" id="tablephim">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên phim</th>
                            <th scope="col">Tập phim</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Link phim</th>
                            <th scope="col">Quản lý</th>
                        </tr>
                    </thead>
                    <tbody class="order_position">
                        @foreach ($list_episode as $key => $episode)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $episode->movie->title }}</td>
                                <td>{{ $episode->episode }}</td>
                                <td>
                                    <img width="100" src="{{ asset('uploads/movie/' . $episode->movie->image) }}">
                                </td>
                                <td>{{ $episode->linkphim }}</td>
                                {{-- <td>
                                    @if ($episode->status)
                                        Hiển thị
                                    @else
                                        Không hiển thị
                                    @endif
                                </td> --}}
                                <td>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['episode.destroy', $episode->id],
                                        'onsubmit' => 'return confirm("Bạn có chắc muốn xóa?")',
                                    ]) !!}
                                    {!! Form::submit('Xóa', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                    <a href="{{ route('episode.edit', $episode->id) }}" class="btn btn-warning">Sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
