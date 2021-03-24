<?php
namespace App\AppSync;

use App\GraphQL\coreDB\Mutations;
use App\GraphQL\coreDB\Queries;
use App\GraphQL\coreDB\CustomQuery;
use App\AppSyncQuery;
use Exception;
use Ramsey\Uuid\Uuid;

class CompanyDynamo {
    use Mutations;
    use Queries;
    use CustomQuery;
    /**
    * create a company in dynamo
    *
    * @param array $companyData
    **/
    public function createCompany($companyData) {
        try{
            $companyData["id"] = Uuid::uuid4();
            $companyData["socialMediaLinks"] = [];
            $companyData["projectSize"] = [ 'to' => "0", 'from' => '0' ];
            $companyData["projectCapability"] = [ 'to' => "0", 'from' => '0' ];
            $companyData["locations"] = [];
            $companyData["types"] = [];
            $companyData["regions"] = [];
            $companyData["unspcs"] = [];
            $companyData["naics"] = [];

            return new AppSyncQuery($this->createCompany, $companyData);
        } catch(\Exception $e) {
            return $e;
        }
    }


    /**
     * List the companies, used temporarily for now during signup
     */
    public function list() {
        try {
            return new AppSyncQuery($this->listCompanys, []);
        } catch( Exception $e ) {
            return $e;
        }
    }
    /**
     * gets a company by its id and brings its users
     *
     * @param Array $params
     */
    public function getCompanyWithUsers($params) {
        try {
            return new AppSyncQuery($this->getCompanyWithUsers, $params, false);
        } catch( Exception $e ) {
            return $e;
        }
    }
    /**
     * updates a company's info
     *
     * @param Array $params
     */
    public function update($params) {
        try {
            return new AppSyncQuery($this->updateCompany, $params);
        } catch( Exception $e ) {
            return $e;
        }
    }
}
