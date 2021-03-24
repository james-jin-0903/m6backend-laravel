<?php
namespace App\AppSync;

use App\GraphQL\coreDB\Mutations;
use App\AppSyncQuery;
use Ramsey\Uuid\Uuid;

class UserCompanyDynamo {
    use Mutations;
    /**
     * creates a connection between the user and a company
     * @param array $data
     * @param Boolean $connectionStatus
    */
    public function create($data, $connectionStatus) {
        $payload = [
            'id'         => Uuid::uuid4(),
            'userID'     => $data["userID"],
            'companyID'  => $data["companyID"],
            'active'     => $connectionStatus,
            'userWhoInvited' => '' // if empty than user asked to join, else user was invited
            // will need to later add in the user connection to appsync
        ];
        $payload['joinStatus'] = empty($data['joinStatus']) ? 'PENDING' : $data['joinStatus'];

        try{
            return new AppSyncQuery($this->createUserCompany, $payload);
        } catch( Exception $e ) {
            return $e;
        }
    }

    /**
     * update UserCompanyDynamo
     * this is the relation between the user and a company
     *
     * @param Array $data
     */
    public function update($data) {
        try{
            return new AppSyncQuery($this->updateUserCompany, $data);
        } catch(\Exception $e) {
            return $e;
        }
    }
}
