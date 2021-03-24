<?php

namespace App\AppSync;

use App\Graphql\coreDB\Mutations;
use App\Graphql\coreDB\Queries;
use App\AppSyncQuery;
use Exception;

class RegionDynamo {
    use Mutations;
    use Queries;
    /**
    * create a region
    *
    * @param  Array $params
    **/
    public function create($params) {
        try{
            return new AppSyncQuery($this->createRegion , $params);
        } catch(Exception $e) {
            return $e;
        }
    }
    /**
     * updates the region
     * 
     * @param Array $params
     */
    public function update($params) {
        try { 
            return new AppSyncQuery($this->updateRegion, $params);
        } catch(Exception $e) {
            return $e;
        }
    }
    /**
     * gets all regions
     */
    public function all() {
        try {
            return new AppSyncQuery($this->listCompanyRegions, []);
        } catch(Exception $e) {
            return $e;
        }
    }

}
