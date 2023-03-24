<?php

namespace Modules\Guardian\Entities;

use App\Models\User;
use App\Models\State;
use App\Models\Branch;
use App\Models\LocalGovernment;
use Modules\Student\Entities\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guardian extends Model
{
    use HasFactory;

    public function lga()
    {
        return $this->belongsTo(LocalGovernment::class, 'local_government_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    public function students()
    {
        return $this->hasMany(Student::class, 'guardian_id');
    }

    protected static function newFactory()
    {
        return \Modules\Guardian\Database\factories\GuardianFactory::new ();
    }
}