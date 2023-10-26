<?php
  
namespace App\Models;
  
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Exception;
  
class User extends Authenticatable  
// implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'is_blocked',
        'phone', 
        'email_sf', 
        'title',
        'dept', 
        'department',
        'section',
        'section_code',
        'divid',
        'division', 
        'company_name',
        'companyid',
        'worklocation_code',
        'worklocation_name',
        'worklocation_lat_long',
        'photo',
        'grade'
    ];
  
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
  
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public static function getUserSF($nrp)
    {
        try{
            // $response = Http::get('http://webportal.patria.co.id/apisunfish/allempMagang.php');
             $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );

            $response = file_get_contents("https://webportal.patria.co.id/apisunfish/allempMagang.php", false, stream_context_create($arrContextOptions));
            if($nrp==999){
                $arr = json_decode($response,true);
                return $arr['emp'];
            }else{
                $arr = json_decode($response,true);
                $datas=array();
                foreach ($arr['emp'] as $key ) {
                    if($key['nrp']==$nrp){
                        array_push($datas, $key);
                    }
                }
                return isset($datas[0]) ? $datas[0] : $datas;
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }
}