<?php

namespace Tagydes\Repositories\User;

use Tagydes\Repositories\Role\RoleRepository;
use Tagydes\Role;
use Tagydes\Services\Auth\Social\ManagesSocialAvatarSize;
use Tagydes\Services\Upload\UserAvatarManager;
use Tagydes\User;
use Carbon\Carbon;
use DB;
use Illuminate\Database\SQLiteConnection;
use Laravel\Socialite\Contracts\User as SocialUser;
use Tagydes\Support\Enum\ResellerStatus;

class EloquentUser implements UserRepository
{
    use ManagesSocialAvatarSize;

    /**
     * @var UserAvatarManager
     */
    private $avatarManager;
    /**
     * @var RoleRepository
     */
    private $roles;

    public function __construct(UserAvatarManager $avatarManager, RoleRepository $roles)
    {
        $this->avatarManager = $avatarManager;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findBySocialId($provider, $providerId)
    {
        return User::leftJoin('social_logins', 'users.id', '=', 'social_logins.user_id')
        ->select('users.*')
        ->where('social_logins.provider', $provider)
        ->where('social_logins.provider_id', $providerId)
        ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findBySessionId($sessionId)
    {
        return User::leftJoin('sessions', 'users.id', '=', 'sessions.user_id')
        ->select('users.*')
        ->where('sessions.id', $sessionId)
        ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function associateSocialAccountForUser($userId, $provider, SocialUser $user)
    {
        return DB::table('social_logins')->insert([
            'user_id' => $userId,
            'provider' => $provider,
            'provider_id' => $user->getId(),
            'avatar' => $this->getAvatarForProvider($provider, $user),
            'created_at' => Carbon::now()
        ]);
    }

    /**
     * {@inheritdoc}
     */

    public function paginate($perPage, $search = null, $status = null, $role_id = null, $created_by = -1 , $source = null, $type = null)
    {
//        select count(*) as aggregate from `users` where `role_id` = 4 and         
//        exists (select * from `resellers` inner join `reseller_user` on `resellers`.`id` = `reseller_user`.`reseller_id` 
//        where `users`.`id` = `reseller_user`.`user_id` and `reseller_id` = 210025)

        $query = User::query();
        
        if($role_id){
            $query->where('role_id', $role_id);
        }
        
        if ($status) {
            $query->where('status', $status);
        }

        if ($created_by != -1) {
            $query->where('created_by', $created_by);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', "like", "%{$search}%");
                $q->orWhere('email', 'like', "%{$search}%");
                $q->orWhere('first_name', 'like', "%{$search}%");
                $q->orWhere('last_name', 'like', "%{$search}%");
            });
        }

         switch ($type) {
            case 'reseller':                                
                // $uid = DB::table('reseller_user')->where('reseller_id', $source)->value('user_id');
                // $query->where('created_by', $uid);
                $query->whereHas('resellers', function ($q) use ($source) {
                    $q->where('reseller_id', $source); 
                });
                break;
            
            case 'customer':
                $query->whereHas('customers', function ($q) use ($source) {
                    $q->where('id', $source);
                });
                break;
            
            default:
                # nothing to do
                break;
        }

        // if ($reseller_id) {
        //     $query->whereHas('resellers', function ($q) use ($reseller_id) {
        //         $q->where('id', $reseller_id);
        //     });
        // }

        $result = $query->orderBy('id', 'desc')
        ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        if ($status) {
            $result->appends(['status' => $status]);
        }
        
      

        // } else {
        // $result = $query->wherehas('resellers', function ($q) use ($reseller_id) {
        //     $q->where('id', $reseller_id);
        // })->get();

            // dd($result);
        // }
        return $result;       
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        if (isset($data['country_id']) && $data['country_id'] == 0) {
            $data['country_id'] = null;
        }

        $user = $this->find($id);

        $user->update($data);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $user = $this->find($id);

        $this->avatarManager->deleteAvatarIfUploaded($user);

        return $user->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return User::count();
    }

    /**
     * {@inheritdoc}
     */
    public function newUsersCount()
    {
        return User::whereBetween('created_at', [Carbon::now()->firstOfMonth(), Carbon::now()])
        ->count();
    }

    /**
     * {@inheritdoc}
     */
    public function countByStatus($status)
    {
        return User::where('status', $status)->count();
    }

    /**
     * {@inheritdoc}
     */
    public function latest($count = 20)
    {
        return User::orderBy('created_at', 'DESC')
        ->limit($count)
        ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function countOfNewUsersPerMonth(Carbon $from, Carbon $to)
    {
        $result = User::whereBetween('created_at', [$from, $to])
        ->orderBy('created_at')
        ->get(['created_at'])
        ->groupBy(function ($user) {
            return $user->created_at->format("Y_n");
        });

        $counts = [];

        while ($from->lt($to)) {
            $key = $from->format("Y_n");

            $counts[$this->parseDate($key)] = count($result->get($key, []));

            $from->addMonth();
        }

        return $counts;
    }

    /**
     * Parse date from "Y_m" format to "{Month Name} {Year}" format.
     * @param $yearMonth
     * @return string
     */
    private function parseDate($yearMonth)
    {
        list($year, $month) = explode("_", $yearMonth);

        $month = trans("app.months.{$month}");

        return "{$month} {$year}";
    }

    /**
     * {@inheritdoc}
     */
    public function getUsersWithRole($roleName)
    {
        return Role::where('name', $roleName)
        ->first()
        ->users;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserSocialLogins($userId)
    {
        return DB::table('social_logins')
        ->where('user_id', $userId)
        ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function setRole($userId, $roleId)
    {
        return $this->find($userId)->setRole($roleId);
    }

    /**
     * {@inheritdoc}
     */
    public function findByConfirmationToken($token)
    {
        return User::where('confirmation_token', $token)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function switchRolesForUsers($fromRoleId, $toRoleId)
    {
        return User::where('role_id', $fromRoleId)
        ->update(['role_id' => $toRoleId]);
    }

    public function status($id)
    {
        $reseller = $this->find($id);

        $reseller->status = $reseller->isActive() ? ResellerStatus::INACTIVE : ResellerStatus::ACTIVE;

        $reseller->update();

        return $reseller;
    }
    
}
