<?php

namespace App\AppSync;

use App\Graphql\coreDB\Mutations;
use App\Graphql\coreDB\Queries;
use App\AppSyncQuery;

class CompanyTypesDynamo {
    use Mutations;
    use Queries;
    /**
    * create a User in dynamo
    *
    * @param  Array $params
    **/
    public function create($params) {
        try{
            return new AppSyncQuery($this->createCompanyType , $params);
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
            return new AppSyncQuery($this->updateCompanyType, $params);
        } catch(\Exception $e) {
            return $e;
        }
    }
    /**
     * get all company types
     */
    public function all($nextToken) {
        try {
            return new AppSyncQuery($this->listCompanyTypes, [], true, $nextToken);
        } catch(Exception $e) {
            return $e;
        }
    }

}
