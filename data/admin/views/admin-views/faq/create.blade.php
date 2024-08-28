@extends('admin-layouts.company')

@section('title', 'Article')
@section('article', 'active')


@section('content')
        <form role="form" autocomplete="off" method="post"  action="{{route('admin.article.create-article')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label>Title</label>
                <input class="form-control" placeholder="Title" name="title" value="{{ old('title') }}"/>
                <div class="text-danger">{{ $errors->post->first('title') }}</div>
            </div>

            <div class="form-group">
                <label>Questions</label>
                <textarea class="mceEditor"  name="questions">{{ old('questions') }}</textarea>

                <div class="text-danger">{{ $errors->post->first('questions') }}</div>

            </div>
            <div class="form-group">
                <label>Answers</label>
                <textarea class="mceEditor" id="text"  name="answers" >{{ old('answers') }}</textarea>
                <div class="text-danger">{{ $errors->post->first('answers') }}</div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>

@endsection
@section('script')
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: "textarea",
            height: 300,
            plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen link template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        });
    </script>
@endsection