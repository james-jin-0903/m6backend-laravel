<?php
namespace App\Http\Controllers;

use App\AppSync\UserCompanyDynamo;
use Illuminate\Http\Request;

class UserCompanyController extends Controller {
    private $updateErrorMsg = [
        'type' => 'ERROR_USER_COMPANY',
        'msg'  => 'there was an error while updating the user company'
    ];

    private $switchErrorMsg = [
        'type' => 'ERROR_WHILE_SWITCHING_COMPANIES',
        'msg'  => 'there was an error while switching the users company'
    ];
    /**
     * creates a relationship bewteen a user and a company
     * @param Request $request
     */
    public function create(Request $request) {
        try {
            $this->validate($request, [
                'userID' => 'required',
                'companyID' => 'required'
            ]);
            
            $userCompanyDynamo = new UserCompanyDynamo();
            $client = $userCompanyDynamo->create( $request->only('userID', 'companyID'), false );
            $json = $client->sendRequest(); 
            
            return $json;
        } catch( \Exception $e ) {
            return $e;
        }
    }
    /**
     * Updates the relationship between a user and a company
     */
    public function update(Request $request) {
        try{
            // need to get the userCompany object
            // also need to send the bearer token to verify this
            $this->validate($request, [
                'userCompany' => 'required'
            ]);

            ['userCompany' => $userCompany] = $request->only('userCompany');
            unset($userCompany["user"]);
            unset($userCompany["userWhoInvited"]);
            $userCompanyDynamo = new UserCompanyDynamo();
            $client = $userCompanyDynamo->update($userCompany);

            $json = $client->sendRequest();

            if( isset( $json["errors"] ) ){
                return response()->json($this->updateErrorMsg, 422);
            }

            return $json;
        } catch(\Exception $e) {
            return response()->json($this->updateErrorMsg, 422);
        }

    }
    /**
     * switch active companies
     * 
     * @param Request $request
     */
    public function switchCompanies(Request $request) {
        try{
            $this->validate($request, [
                'currentCompany' => 'required',
                'nextCompany' => 'required'
            ]);
            
            [ 'currentCompany' => $currentCompany, 'nextCompany' => $nextCompany ] = $request->only('currentCompany', 'nextCompany');
            unset($currentCompany["user"]);
            unset($currentCompany["userWhoInvited"]);
            unset($currentCompany["company"]);

            unset($nextCompany["user"]);
            unset($nextCompany["userWhoInvited"]);
            unset($nextCompany["company"]);

            $userCompanyDynamo = new UserCompanyDynamo();
            
            $currentCompany["active"] = false;
            $client = $userCompanyDynamo->update($currentCompany);
            $res = $client->sendRequest();

            if( isset( $res["errors"] ) ){
                return response()->json($this->switchErrorMsg , 422);
            }

            $nextCompany["active"] = true;
            $client = $userCompanyDynamo->update($nextCompany);
            $res = $client->sendRequest();

            if( isset( $res["errors"] ) ){
                return response()->json($this->switchErrorMsg, 422);
            }

            return response()->json([
                'type' => 'USER_CONNECTION_UPDATED',
                'msg'  => 'user connection updatede'
            ]);
        } catch( \Exception $e ) {
            return response()->json($this->switchErrorMsg, 422);
        } 
    }


}