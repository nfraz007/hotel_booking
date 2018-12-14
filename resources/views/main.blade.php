<!DOCTYPE html>
<html lang="en">
<head>
    @include("include.meta")
    @include("include.css")
</head>
<body>
    @include('include.header')
    <br>
    <div class="container">
        {!! $content !!}
    </div>
    @include('include.footer')
    @include('include.modal')
</body>
</html>
@include("include.js")