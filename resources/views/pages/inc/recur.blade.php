@foreach($comments as  $comment)
        <li>
            <div>
                <div class="main-container">
                    <div class="img"> <img src="uploads/images/{{$comment['data']['img'] ?? 0}}" width="150px"></div>
                    <div class="content" contentEditable="false"> {{$comment['data']['content']}} </div>
                </div>
                @if(Auth::check())
                <div>
                    <div class="errors"></div>
                    @if($comment['data']['user_id'] == Auth::id() && count($comment['descendant']) == 0)
                    <input type="button" class="edit" data-messageId="{{$comment['data']['id']}}"  value="Редактировать">
                    @endif
                    <input type="button" class="ToAnswer" value="Ответить" data-messageId="{{$comment['data']['id']}}">
                </div>
                @endif

            </div>
            @if(count($comment['descendant']) != 0)
            <ul>
             @include('pages.inc.recur', ['comments' => $comment['descendant']])
            </ul>
            @endif
        </li>
@endforeach
