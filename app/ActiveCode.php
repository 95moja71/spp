<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expired_at'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeGenerateCode($query , $user)
    {
        if($code = $this->getAliveCodeForUser($user)) {
            $code = $code->code;
        } else {
            do {
                $code = mt_rand(100000, 999999);
            } while($this->checkCodeIsUnique($user , $code));

            // store the code
            $user->activeCode()->create([
                'code' => $code,
                'expired_at' => now()->addMinutes(10)
            ]);
        }

        return $code;
    }

    private function checkCodeIsUnique($user, int $code)
    {
        return !! $user->activeCode()->whereCode($code)->first();
    }

    private function getAliveCodeForUser($user)
    {
        return $user->activeCode()->where('expired_at' , '>' , now())->first();
    }
}
