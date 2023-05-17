<?php

namespace App\Repositories;

use App\Classes\Business\WorkTime;
use App\Models\Org\Organization;
use Hash;
use Auth;
use App\Models\User;
use App\Models\Category; 
use Illuminate\Http\Request;
use Mockery\Exception;
use DB;
use App\Http\Controllers\Admin\UsersController;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendToManagers;

class OrganizationRepository
{

    public static function createOrganization(Request $request, $checkAdmin = false)
    {

        try {

            $isAutoRegisterUser = (bool) $request->to_register_user; //  регистрировать ли и авторизовать ли юзера автоматически

            $user = new User();
            $user->name =  $request->name ;
            $user->password = Hash::make($request->password);
            $user->email = mb_strtolower ($request->email);
            $user->role = User::ROLE_CLIENT;
            $user->phone = $request->phone;
            $user->account_is_created = mb_strtolower ($request->email);
            $user->unique_id = UsersController::generateUniqueUserIdNumber();
            $user->save();

            // Check admin/moderator permission for set status
            if ($checkAdmin) {
                if ($request->get('org_status', 'register') != 'register') {
                    if (!Auth()->user()->can('clients.moderate')) {
                        $request->merge([
                            'org_status' => 'register'
                        ]);
                    }
                }
            }

            $organization = $request->organization;
            $agent_id = $request->agent_id ?? null;
            $agent_inn = $request->agent_inn ?? null;

            if($organization === null) { // MVP версия , без заполнения организации
                $organization = $user->company()->create();
            }
            else{
                $organization['org_status'] = Organization::ORG_STATUS_APPROVE;
                $organization['org_is_active'] = (isset($organization['org_is_active'])) ? $organization['org_is_active'] : true;
                $organization = $user->company()->create($request->organization);

                $user->is_org_created = true;
                $user->save();
            }

            $user->organization()->associate($organization);  // Этот метод обновит отношения belongsTo() и установит внешний ключ на дочерней модели, в частности owner_id = users.id
            $user->save();

            /////////////////// пропишем категорию для организации /////////////////////////
            $categories = $request->categories;

            if($categories === null){

                $categorySecondLevelId = Category::CategoriesNotRoot()->first(); //id категории , не выше первого уровня

                if($categorySecondLevelId === null){
                    $categorySecondLevelId = 2;
                }
                else{
                    $categorySecondLevelId = $categorySecondLevelId->id;
                }

                $categories=[
                    "0" => $categorySecondLevelId
                ];
            }
            else{
                
                foreach($categories as $keyCat => $catId){

                    if(Category::where('id', $catId)->first()->parent === null){
                
                        $categorySecondLevelId = Category::CategoriesNotRoot()->first(); //id категории , не выше первого уровня
                        if($categorySecondLevelId === null){
                            $categorySecondLevelId = 2;
                        }
                        else{
                            $categorySecondLevelId = $categorySecondLevelId->id;
                        }
                    
                        $categories[$keyCat] = $categorySecondLevelId;
                    }
                    else{
                        $categories[$keyCat] = (int) $categories[$keyCat];
                    }
                }
            }

            $organization->categories()->sync($categories);
            /////////////////

            // start permissions
           // if ($organization->org_type == Organization::ORG_TYPE_SELLER) { //закомментил, ибо в MVP логически 1 тип организации
                $permissions = config('b2b.permissions_site');
                $userPermissions = [];
                foreach ($permissions as $category) {
                    $slug = $category['slug'];
                    foreach ($category['permissions'] as $permission) {
                        $userPermissions[] = $slug . '.' . $permission;
                    }
                }
                $user->syncPermissions($userPermissions);
           // }
            // end permissions

            if(false === $isAutoRegisterUser ){
                $user->notify(new \App\Notifications\UserRegisterConfirmation()); // письмо юзеру, чтобы завершить регистрацию
            }
            else{
                $user->status = User::USER_STATUS_APPROVE; // зарегали юзера
                $user->register_confirm_token = null;
                $user->save();
                $user->rollApiKey(); // авторизовали юзера
            }

            // если нужнобольше каатегорий, шлем письмо менеджерам
            if($request->is_need_more_categories) {
                $messageToManagers = "Пользователь, зарегистрировавшийся на нашем сайте желает больше категорий";
                $title = '';
                $subj = "Нужно больше категорий при завершении регистрации";
                Mail::to(config('b2b.email.managers'))->send(new SendToManagers( (string) $messageToManagers, $title, $subj));  
            }

            $data = [
                'inn' => $organization->org_inn,
            ];
            // если переход по реферальной ссылке, то привяжем организацию к агенту
            if($agent_id and $agent_inn and $agent_inn === $organization->org_inn){
                $data['agent_id'] = $agent_id;
            }
            else{ // проверим, не генерилось ли рефералок на агенте на эту компанию и тогда привяжем ее к этому агенту
                $agentIdFromAgentsServer = \App\Http\Controllers\Client\Api\v1\Organization\OrganizationStatisticController::agentIdFromInn($organization->org_inn);
                if($agentIdFromAgentsServer !== null){
                    $data['agent_id'] = $agentIdFromAgentsServer;
                }
            }

            $statistic = $organization->statistic()->create($data);

            // подписка на оповещения
            if(true === (boolean) $request->notice_allowed){
                $tagsIds = \App\Models\Org\Channel::select('id')->pluck('id')->toArray();
                $organization->channels()->syncWithoutDetaching($tagsIds);
            }
          
     //       $organization->notify(new \App\Notifications\OrganizationRegisterComplete());  //закомментил, ибо в MVP не надо слать в базу создание при регистрации

      //      dispatch((new \App\Jobs\Geo\GeoCodeOrganizationAddress($organization))->onQueue('geo')); //закомментил, ибо в MVP при регистрации не заполняем гео поля ,и значит не надо проверять гео

            return $user;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function createOrganizationWorker(Organization $organization, $name, $password, $email)
    {
        $user = new User();
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->email = $email;
        $user->role = User::ROLE_CLIENT_WORKER;
        $user->organization()->associate($organization); // Этот метод бновит отношения belongsTo() и установит внешний ключ на дочерней модели, в частности owner_id = users.id
        $user->save();

        $user->notify(new \App\Notifications\UserRegisterConfirmation());

        return $user;
    }

    public static function getOrganization()
    {
        $query = Organization::select('organizations.*');

        if ($user = Auth::guard('api')->user()) {
            $query->leftJoin('user_favorites', function ($join) use ($user) {
                $join->on('user_favorites.favorite_id', '=', 'organizations.id')
                    ->on(DB::raw('user_favorites.organization_id'), DB::raw('='),DB::raw("'".$user->organization_id."'"));
            });

            $query->addSelect(['user_favorites.favorite_id as favorite']);
        }

        return $query->with('categories', 'stores', 'offices', 'city.region.country');
    }
}