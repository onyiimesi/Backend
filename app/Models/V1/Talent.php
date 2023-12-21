<?php

namespace App\Models\V1;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\V1\ResetPasswordNotification;
use OwenIt\Auditing\Contracts\Auditable;


class Talent extends Authenticatable implements Auditable
{
    use HasFactory, SoftDeletes, HasApiTokens, Notifiable;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'skill_title',
        'overview',
        'location',
        'employment_type',
        'top_skills',
        'highest_education',
        'rate',
        'compensation',
        'portfolio_title',
        'portfolio_description',
        'image',
        'social_media_link',
        'password',
        'otp',
        'otp_expires_at',
        'verify_status',
        'status',
        'type',
        'availability',
        'linkedin',
        'instagram',
        'twitter',
        'behance',
        'facebook',
        'application_link',
        'currency',
        'phone_number',
        'country_code'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->uuid = (string) Str::uuid();
        });

        static::deleting(function ($model) {
            $model->topskills()->delete();
            $model->talentimage()->delete();
            $model->educations()->delete();
            $model->employments()->delete();
            $model->certificates()->delete();
            $model->portfolios()->delete();
            $model->talentbillingaddress()->delete();
            $model->talentlanguage()->delete();
        });

    }

    public function topskills()
    {
        return $this->hasMany(TopSkill::class);
    }

    public function talentimage()
    {
        return $this->hasMany(TalentImages::class);
    }

    public function bankAccount()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function educations()
    {
        return $this->hasMany(TalentEducation::class, 'talent_id');
    }

    public function employments()
    {
        return $this->hasMany(TalentEmployment::class, 'talent_id');
    }

    public function certificates()
    {
        return $this->hasMany(TalentCertificate::class, 'talent_id');
    }

    public function portfolios()
    {
        return $this->hasMany(TalentPortfolio::class, 'talent_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function talentbillingaddress()
    {
        return $this->hasOne(TalentBillingAddress::class, 'talent_id');
    }

    public function talentlanguage()
    {
        return $this->hasMany(TalentLanguage::class, 'talent_id');
    }

    public function talentidentity()
    {
        return $this->hasOne(TalentIdentity::class, 'talent_id');
    }

    public function talentwallet()
    {
        return $this->hasOne(TalentWallet::class, 'talent_id');
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = 'https://mango-glacier-097715310.3.azurestaticapps.net/reset-password?token='.$token;

        $this->notify(new ResetPasswordNotification($url));
    }
}
