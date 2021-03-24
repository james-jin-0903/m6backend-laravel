<?php

namespace App\AppSync;

use App\Graphql\coreDB\Mutations;
use App\AppSyncQuery;

class UnspcCodesDynamo {
    use Mutations;
    /**
    * create a unspc in dynamo
    *
    * @param  Array $params
    **/
    public function create($params) {
        try{
            return new AppSyncQuery($this->createUnspc , $params);
        } catch(\Exception $e) {
            return $e;
        }
    }
    /**
     * updates a unspc code
     * 
     * @param Array $params
     */
    public function update($params) {
        try { 
            return new AppSyncQuery($this->updateUnspc, $params);
        } catch(\Exception $e) {
            return $e;
        }
    }
    /**
     * get all unspc codes
     */
    public function all($nextToken) {
        try {
            return new AppSyncQuery($this->listUnspcs, [], true, $nextToken);
        } catch(Exception $e) {
            return $e;
        }
    }

}
