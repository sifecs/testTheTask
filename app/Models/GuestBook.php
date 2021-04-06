<?php

namespace App\Models;

use Drandin\ClosureTableComments\ClosureTableService;
use Drandin\ClosureTableComments\Commentator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Storage;

class GuestBook extends Model
{
    use HasFactory;

    static function create($data) {

        $commentator = new Commentator(new ClosureTableService());

        $id = $commentator->addCommentToRoot($data['text'], \Auth::id());
        $GuestBook = self::find($id)->uploadImg($data['img']);

        return $GuestBook;
    }

    static function updated($data) {
        $commentator = new Commentator(new ClosureTableService());

        $guestBook = self::find($data['messageId']);
        if ($guestBook->user_id == \Auth::id() && $commentator->getTreeBranch($data['messageId'])->count() == 1) {
            $res = $commentator->editComment($data['messageId'], $data['text']);
            $guestBook->uploadImg($data['img']);
                return $guestBook;
        }
        return false;
    }

    static function answer($data){
        $commentator = new Commentator(new ClosureTableService());

        $id = $commentator->replyToComment($data['messageId'], $data['text'], \Auth::id());

        $GuestBook = self::find($id)->uploadImg($data['img']);

        return $GuestBook;
    }

    public  function uploadImg ($image) {
        if ($image == null){ return; }

        $this->removeImg();

        $filename = Str::random(30) . '.' . $image->extension();
        $image->storeAs('uploads/images',$filename);

        $srs = 'uploads/images/'.$filename;
        $ext = $image->extension();
        resize($srs,$ext);

        $this->img = $filename;
        $this->save();

        return $this;
    }

    public function removeImg($field = 'img') {
        if ($this->$field !=null) {
            Storage::delete('/uploads/images/' . $this->$field);
        }
    }

    static function CreateFake($count = 5){
        $commentator = new Commentator(new ClosureTableService());
        $x=0;
        while ($x++< $count) {
            $id = $commentator->addCommentToRoot('Сообщение'.$x, 1);
            $GuestBook = GuestBook::find($id);
            $GuestBook->img = '8.jpg';
            $GuestBook->save();
        }
    }

    static function answerFake(){
        $commentator = new Commentator(new ClosureTableService());

        $id = $commentator->replyToComment(1, 'Ответ на сообщение 1', 1);

        $GuestBook = GuestBook::find($id);
        $GuestBook->img = '8.jpg';
        $GuestBook->save();
    }


    protected $table = 'comments';
}
