<?php

namespace App\AppSync;

use App\Graphql\coreDB\Mutations;
use App\Graphql\coreDB\Queries;
use App\AppSyncQuery;

class NaicsCodesDynamo {
    use Mutations;
    use Queries;
    /**
    * create a User in dynamo
    *
    * @param  Array $params
    **/
    public function create($params) {
        try{
            return new AppSyncQuery($this->createNaic , $params );
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
            return new AppSyncQuery($this->updateNaic, $params);
        } catch(\Exception $e) {
            return $e;
        }
    }
    /**
     * gets all naics does, paginated with $nextToken
     */
    public function all($nextToken) {
        try {
            return new AppSyncQuery($this->listNaics, [], true, $nextToken);
        } catch(Exception $e) {
            return $e;
        }
    }

}
