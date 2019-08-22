<?php

namespace Tagydes;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tagydes\Presenters\UserPresenter;
use Tagydes\Services\Auth\Api\TokenFactory;
use Tagydes\Services\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Tagydes\Services\Auth\TwoFactor\Contracts\Authenticatable as TwoFactorAuthenticatableContract;
use Tagydes\Services\Logging\UserActivity\Activity;
use Tagydes\Support\Authorization\AuthorizationUserTrait;
use Tagydes\Support\CanImpersonateUsers;
use Tagydes\Support\Enum\UserStatus;
use Illuminate\Auth\Passwords\CanResetPassword;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Tagydes\Branch;

class User extends Authenticatable implements TwoFactorAuthenticatableContract, JWTSubject
{
    use TwoFactorAuthenticatable,
        CanResetPassword,
        PresentableTrait,
        AuthorizationUserTrait,
        Notifiable,
        CanImpersonateUsers;

    protected $presenter = UserPresenter::class;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $dates = ['last_login'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','email', 'password', 'username', 'first_name', 'last_name', 'phone', 'avatar',
        'address', 'country_id',  'last_login', 'confirmation_token', 'status',
        'remember_token', 'role_id', 'template','created_by','associated_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    

    public function gravatar()
    {
        $hash = hash('md5', strtolower(trim($this->attributes['email'])));

        return sprintf("https://www.gravatar.com/avatar/%s?size=150", $hash);
    }

    public function isUnconfirmed()
    {
        return $this->status == UserStatus::UNCONFIRMED;
    }

    public function isActive()
    {
        return $this->status == UserStatus::ACTIVE;
    }

    public function isBanned()
    {
        return $this->status == UserStatus::BANNED;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function getRole(){
        return $this->role_id;
    }
    public function getId(){
        return $this->id;
    }
    
    public function getAssociateId(){
        return $this->associated_id;
    }
    
    public function getCreatedById(){
        return $this->created_by;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->id;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        $token = app(TokenFactory::class)->forUser($this);

        return [
            'jti' => $token->id
        ];
    }

    public function resellers()
    {
        return $this->belongsToMany('Tagydes\Reseller', 'reseller_user');        
    }

    public function customers()
    {
        return $this->belongsToMany('Tagydes\Customer', 'customer_user');
    }


    public function parentUser(){
        return $this->hasOne('Tagydes\User','id', 'created_by');
    }

    
    public function parentUserRole(){

        $user = auth()->user();
        $data = User::where('id', $user->created_by)->get();

        if($data->first()){
            return $data->first()->role_id;
        }
        return 0;
    }


    public function getBanch($user, $branch){
        return DB::table('user_branch')->where('user_id', $user->id)->where('branch_id', $branch)->get();
    }
    
    public function getBranches(){
        $user = auth()->user();
        $created_by = -1;
        if($user->getRole() == 1 || $user->getRole() == 2 ){ // get all branch in the case of admin
            $created_by = $user->getId();
            $branches = Branch::where('created_by', $created_by)->get();
        }else if($user->getRole() == 5){ // get branches that is allocatd to them            
            $created_by = $user->getCreatedById();
            $branches = Branch::where('created_by', $created_by)->join('user_branch', 'branch_id', 'branch.id')->where('user_id', $user->getId())->get();
        }else{
            $branches = Branch::where('created_by', -1)->get();
        }
        return $branches;       
    }

    public function resellerUser($id){
        return DB::table('resellers')->where('id', $id)->get();
        //return $this->hasOne('Tagydes\Reseller','id', 'created_by');
    }
    public function customerUser($id){
        return DB::table('customers')->where('id', $id)->get();
    }

    public function role()
    {
        return $this->belongsTo('Tagydes\Role');
    }

    // public function resellers(){
    //     return $this->hasOne('Tagydes\Reseller','id', 'reseller');
    // }
    
}
