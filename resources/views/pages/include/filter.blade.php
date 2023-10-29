<style>
    .style_filter {
        background: #12171b;
        color: white;
        min-width: 80px;
        min-height: 36px;
    }
</style>
<form action="{{ route('filter') }}" method="GET">
    <div class="col-md-2">
        <div class="form-group">
            <select class="form-control style_filter" id="exampleFormControlSelect1" name="order">
                <option value="">-- Sắp xếp --</option>
                <option value="date">Ngày đăng</option>
                <option value="year_release">Năm sản xuất</option>
                <option value="name_a_z">Tên phim</option>
                <option value="watch_views">Lượt xem</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select class="form-control style_filter" id="exampleFormControlSelect1" name="genre">
                <option value="">-- Thể loại --</option>

                @foreach ($genre as $key => $gen_filter)
                    <option {{ isset($_GET['genre']) && $_GET['genre'] == $gen_filter->id ? 'selected' : '' }}
                        value="{{ $gen_filter->id }}">{{ $gen_filter->title }}</option>
                @endforeach
                {{-- <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option> --}}
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <select class="form-control  style_filter" id="exampleFormControlSelect1" name="country">
                <option value="">-- Quốc gia --</option>

                @foreach ($country as $key => $cou_filter)
                    <option {{ isset($_GET['country']) && $_GET['country'] == $cou_filter->id ? 'selected' : '' }}
                        value="{{ $cou_filter->id }}">{{ $cou_filter->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            @php
                if (isset($_GET['year'])) {
                    $year = $_GET['year'];
                } else {
                    $year = null;
                }
                
            @endphp
            {!! Form::selectYear('year', 2010, 2023, $year, [
                'class' => 'select-year form-control style_filter',
                'placeholder' => '--Năm phim--',
            ]) !!}
        </div>
    </div>
    <div class="col-md-2">
        <input type="submit" class="btn btn-sm btn-default style_filter" id="" value="Lọc phim">
    </div>
</form>
