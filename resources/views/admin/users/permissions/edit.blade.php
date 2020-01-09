@extends('layouts.admin')
@section('title', 'Редактирование доступа')
@push('css')
    <link href="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.css" rel="stylesheet" />
@endpush
@section('content')
<div class="panel panel-inverse">
    <div class="panel-body">

        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="/admin">Админ. панель</a></li>
            <li class="breadcrumb-item active"><a href="/admin">Редактирование доступа</a></li>
        </ol>

        <h1 class="page-title">Редактирование доступа</h1>

        <form action="{{ route('permissions.update', $value) }}" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="name">
                    Имя доступа <span class="text-theme">*</span><br>
                    <small>Текст длиною не более 255 символов</small>
                </label>
                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                id="name" name="name" type="text"
                value="@if(old('name')){{ old('name') }}@else{{$value->name ?? ''}}@endif" autofocus>
                @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            
            <div class="form-group">
                <label for="code">
                    Код доступа <span class="text-theme">*</span><br>
                    <small>Текст длиною не более 255 символов</small>
                </label>
                <input class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"
                id="code" name="code" type="text"
                value="@if(old('code')){{ old('code') }}@else{{$value->code ?? ''}}@endif"
                autofocus>
                @if ($errors->has('code'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('code') }}</strong>
                </span>
                @endif
            </div>
            
            {{ method_field('put') }}

            {{ csrf_field() }}

            <button type="submit" class="btn btn-lime"><i class="fas fa-plus-circle"></i> Добавить</button>
            
        </form>
        
        @if (session('status'))
        <div class="alert alert-success"> {{ session('status') }} </div>
        @endif

        @if (count($errors))
        <div class="alert alert-danger"> 
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>
</div>
@endsection
@push('scripts')
<script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
    $('#detailed_desc').wysihtml5({
        toolbar: {
            "font-styles": true, // Font styling, e.g. h1, h2, etc.
            "emphasis": true, // Italics, bold, etc.
            "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
            "html": true, // Button which allows you to edit the generated HTML.
            "link": true, // Button to insert a link.
            "image": false, // Button to insert an image.
            "color": false, // Button to change color of font
            "blockquote": true, // Blockquote
            "fa": true, // Use Awesome Icon
        }
    });
</script>
<script type="text/javascript">
function bs_input_file_picture_announce() {
    $(".input-file-picture-announce").before(
        function() {
            if ( ! $(this).prev().hasClass('input-ghost') ) {
                var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                element.attr("name",$(this).attr("name"));
                element.change(function(){
                    element.next(element).find('input').val((element.val()).split('\\').pop());
                });
                $(this).find("button.btn-choose-picture-announce").click(function(){
                    element.click();
                });
                $(this).find("button.btn-reset").click(function(){
                    element.val(null);
                    $(this).parents(".input-file-picture-announce").find('input').val('');
                });
                $(this).find('input').css("cursor","pointer");
                $(this).find('input').mousedown(function() {
                    $(this).parents('.input-file-picture-announce').prev().click();
                    return false;
                });
                return element;
            }
        }
    );
}
$(function() {
        bs_input_file_picture_announce();
});
</script>
@endpush