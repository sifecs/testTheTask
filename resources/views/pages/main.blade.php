@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">  Список </div>

                    <div class="card-body">
                        @include('errorMessage')
                        <ul class="treeline mb-4">
                            Гостивая книга
                            @include('pages.inc.recur', ['comments' => $comments])
                        </ul>

                        {{$comments->links('vendor.pagination.bootstrap-4')}}
                        @if(Auth::check())
                        <div class="m-5">
                            <div class="errors"></div>
                            <form class="was-validated" id="store" method="post" enctype="multipart/form-data" action="{{route('store')}}">
                                @csrf
                                <div class="custom-file">
                                    <input type="file" name="img" class="custom-file-input" id="validatedCustomFile" required>
                                    <label class="custom-file-label" for="validatedCustomFile">Выберите картинку</label>
                                    <div class="invalid-feedback">
                                        картинка должна быть не более 500 х 500, но и не менее 100 х 100 пикселей,. Размер картинки не должен превышать 100 Кб.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Текст</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="text" required minlength="10" maxlength="1000" rows="3"></textarea>
                                </div>

                                <input type="submit">
                            </form>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
