<?php

namespace App\AppSync;

use App\Graphql\coreDB\Mutations;
use App\Graphql\coreDB\Queries;
use App\GraphQL\coreDB\CustomQuery;
use App\AppSyncQuery;

class RapidDynamo {
    use Queries;
    use Mutations;
    use CustomQuery;

    /**
     * @param Array $params
     */
    public function create($params) {
        try {
            return new AppSyncQuery($this->createRapidTicket , $params, true);
        } catch(\Exception $e) {
            return $e;
        }
    }

}