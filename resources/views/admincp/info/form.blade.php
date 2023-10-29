@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Quản Lý Thông Tin Website</div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {!! Form::open(['route' => ['info.update', $info->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Tiêu đề website', []) !!}
                            {!! Form::text('title', isset($info) ? $info->title : '', [
                                'class' => 'form-control',
                                'placeholder' => '...',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('description', 'Mô tả website', []) !!}
                            {!! Form::textarea('description', isset($info) ? $info->description : '', [
                                'style' => 'resize:none',
                                'class' => 'form-control',
                                'placeholder' => '...',
                                'id' => 'description',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('copyright', 'Copyright', []) !!}
                            {!! Form::text('copyright', isset($info) ? $info->copyright : '', [
                                'class' => 'form-control',
                                'placeholder' => '...',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('image', 'Hình ảnh logo', []) !!}
                            {!! Form::file('image', ['class' => 'form-control-file']) !!}
                            @if (isset($info) && $info->logo)
                                <img width="150" src="{{ asset('uploads/logo/' . $info->logo) }}">
                            @endif
                        </div>


                        {!! Form::submit('Cập Nhật Thông Tin', ['class' => 'btn btn-success']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
                {{-- <table class="table" id="tablephim">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên danh mục</th>
                            <th scope="col">Mô tả</th>
                            <th scope="col">Đường dẫn</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Quản lý</th>
                        </tr>
                    </thead>
                    <tbody class="order_position">
                        @foreach ($list as $key => $cate)
                            <tr id="{{ $cate->id }}">
                                <th scope="row">{{ $key }}</th>
                                <td>{{ $cate->title }}</td>
                                <td>{{ $cate->description }}</td>
                                <td>{{ $cate->slug }}</td>
                                <td>
                                    @if ($cate->status)
                                        Hiển thị
                                    @else
                                        Không hiển thị
                                    @endif
                                </td>
                                <td>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['info.destroy', $cate->id],
                                        'onsubmit' => 'return confirm("Bạn có chắc muốn xóa?")',
                                    ]) !!}
                                    {!! Form::submit('Xóa', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                    <a href="{{ route('info.edit', $cate->id) }}" class="btn btn-warning">Sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> --}}
            </div>
        </div>
    </div>
@endsection
