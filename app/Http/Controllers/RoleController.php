<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\RoleTaxonomies;
use App\UserRole;
use App\AppRole;


class RoleController extends Controller
{
  public function get()
  {
    try {
      $roles = Role::All();

      foreach ($roles as $role) {
        $role->taxonomies = RoleTaxonomies::where('role_id', $role->id)->pluck('taxonomy_id');
      }

      return response()->json($roles, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }

  }

  public function create(Request $request)
  {
    $role = new Role;

    $role->name = $request->name;
    $role->save();

    if (isset($request->taxonomies)) {
      foreach ($request->taxonomies as $taxonomy) {
        $rt = new RoleTaxonomies;
        $rt->role_id = $role->id;
        $rt->taxonomy_id = $taxonomy;
        $rt->save();
      }
    }

    return response()->json($role, 200);
  }

  public function update(Request $request)
  {
    try {
      $role = Role::findOrFail($request->id);

      $role->name = $request->name;
      $role->save();

      RoleTaxonomies::where('role_id', $role->id)->delete();

      foreach ($request->taxonomies as $taxonomy) {
        $rt = new RoleTaxonomies;
        $rt->role_id = $role->id;
        $rt->taxonomy_id = $taxonomy;
        $rt->save();
      }

      return response()->json($role, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }

  }

  public function delete(Request $request)
  {
    try {
      $role = Role::findOrFail($request->id);

      $role->delete();

      return response()->json($role, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }
  }

  public function assignRole(Request $request)
  {
    try {

      // Delete previous Roles
      UserRole::where('user_id', $request->userId)->delete();
      $roles = array();
      foreach ($request->roles as $rid) {
        $role = new UserRole;

        $role->user_id = $request->userId;
        $role->role_id = $rid;

        $role->save();
        $roles[] = $role;
      }

      return response()->json($roles, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }
  }

  public function removeRole(Request $request)
  {
    try {
      $role = UserRole::findOrFail($request->id);

      $role->delete();

      return response()->json($role, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }
  }

  public function saveAppRole(Request $request)
  {
    try {

      $role = AppRole::find($request->appRoleId);
      if (!$role) {
        $role = new AppRole;
      }

      $role->role_id = $request->role_id;
      $role->app_id = $request->app_id;
      $role->panel_id = $request->panel_id;
      $role->field_id = $request->field_id;
      $role->permission = $request->permission;

      $role->save();

      return response()->json($role, 200);
    } catch (\Exception $e) {
      return response()->json($e, 500);
    }
  }

  public function removeAppRole(Request $request)
  {
    try {
      $role = AppRole::findOrFail($request->id);

      $role->delete();

      return response()->json($role, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }
  }

  public function getUserAppRole(Request $request)
  {
    try {
      $role = UserRole::where('user_id', '=', $request->userId)->with(['role'])->get();


      return response()->json($role, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }
  }

  public function getUserTaxonomies(Request $request)
  {
    try {
      $userRoles = UserRole::where('user_id', $request->userId)->pluck('role_id');
      $userTaxonomies = RoleTaxonomies::whereIn('role_id', $userRoles)->with('taxonomy')->get();
      return response()->json($userTaxonomies, 200);
    } catch (\Exception $e) {

      return response()->json($e, 500);
    }
  }

}
