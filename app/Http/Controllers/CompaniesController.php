<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\AppSync\CompanyDynamo;
use Exception;

class CompaniesController extends Controller {
    protected $genericErrMsg = [
        'type' => 'COMPANY_ERR',
        'msg'  => 'There was an error with your operation'
    ];
    /**
     * Returns a list of companies: TEMP USE ONLY, WILL BE PURGED LATER
    */
    public function list() {
        try{
            $companyDynamo = new CompanyDynamo();
            $client = $companyDynamo->list();
            $json = $client->sendRequest();
            return $json["data"]["listCompanys"];
        } catch(Exception $e) {
            return $e;
        }
    }
    /**
     * gets a company and the users within it
     */
    public function getUsersByCompany( $id ) {
        try {
            $companyDynamo = new CompanyDynamo();
            $client = $companyDynamo->getCompanyWithUsers( [ 'id' => $id ] );
            $json = $client->sendRequest();

            return $json["data"]["getCompany"];
        } catch(Exception $e) {
            return $e;
        }
    }

    /**
     * 
     */
    public function update(Request $request) {
        $this->validate($request, [
            'company' => 'required'
        ]);
        
        try{
            $company = request('company');

            unset($company["users"]);
            unset($company["createdAt"]);    
            unset($company["updatedAt"]);
            unset($company["applications"]);

            $companyDynamo = new CompanyDynamo();
            $client = $companyDynamo->update( $company );
            $json = $client->sendRequest();

            return $json;
            if(isset( $json["errors"] )){
                return response()->json($this->genericErrMsg, 422);
            } else {
                return $json["data"]["updateCompany"];
            }
        } catch( Exception $e ) {
            return $e;
        }
    }
    
}