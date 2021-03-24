<?php

namespace App\AppSync;

use App\GraphQL\coreDB\Mutations;
use App\GraphQL\coreDB\Queries;
use App\GraphQL\coreDB\CustomQuery;
use App\AppSyncQuery;
use App\CognitoJWT;

class UserDynamo {
    use Queries;
    use Mutations;
    use CustomQuery;

    /**
    * create a User in dynamo
    *
    * @param  Array $userData
    **/
    public function create($userData) {
        try{
            $userDynamo = [
                "id"         => $userData["dynamoID"],
                "drupalId"   => '',
                "firstName"  => $userData["name"],
                "lastName"   => $userData["lastName"],
                "cognitoId"  => $userData["email"],
                "email"      => $userData["email"],
                "phone"      => '',
                "lastVisit"  => '',
                "profilePic" => '',
                "location"  => [
                    "lat"     => 0,
                    "lng"     => 0,
                    "address" => ''
                ],
            ];

            return new AppSyncQuery($this->createUser , $userDynamo);
        } catch(\Exception $e) {
            return $e;
        }
    }
    /**
     * updates the dynamo user
     *
     * @param Array $params
     */
    public function update($params) {
        try {
            return new AppSyncQuery($this->updateUser, $params, true);
        } catch(\Exception $e) {
            return $e;
        }
    }

    /**
     * Gets user by id token from Dynamo
     * @param String $IdToken
     */
    public function getUserByIdToken($IdToken) {
        try {
            $json = (array) CognitoJWT::verifyToken($IdToken);
            return new AppSyncQuery($this->getUserWithCompanies, [ 'id' => $json["custom:dynamoID"] ], false);
        } catch(\Exception $e) {
            return $e;
        }
    }

    /**
     * Gets user by id token from Dynamo
     * @param String $IdToken
     */
    public function getUserById($id) {
        try {
            return new AppSyncQuery($this->getUser, [ 'id' => $id ], false);
        } catch(\Exception $e) {
            return $e;
        }
    }

    /**
     * Get user by Email from Dynamo
     * @param $email
     * @return AppSyncQuery|\Exception
     */
    public function getUserByEmail($email){
        try{
            $query = new AppSyncQuery($this->findUserByEmail, [ 'email' => $email ], false);
            return $query->sendRequest();
        }catch(\Exception $e){
            return $e;
        }
    }

}
