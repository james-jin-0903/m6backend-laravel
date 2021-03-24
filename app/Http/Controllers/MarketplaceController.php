<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Marketplace;
use App\AppAttachments as Attachments;
use App\M6Apps;

class MarketplaceController extends Controller {
  /**
   * Get single marketplace
   *
   * @param int $id Marketplace id
   * @return Response
   */
  public function getMarketplace($id) {
    try {
      $marketplace = Marketplace::findOrFail($id)->load('media');
      return response()->json($marketplace);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Get all marketplaces
   *
   * @return Response
   */
  public function getMarketplaces() {
    try {
      $marketplace = Marketplace::all();
      return response()->json($marketplace);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Create new marketplace
   *
   * @param Request $request
   * @return Response
   */
  public function createMarketplace(Request $request) {
    try {
      $this->validate($request, [
        'app_id' => 'required | integer | exists:m6_apps,id',
        'status' => 'required | max:255',
        'overview' => 'required'
      ]);

      $marketplace = Marketplace::create($request->all());
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }

    return response([
      'message' => 'Successful',
      'marketplace_id' => $marketplace['id'],
    ]);
  }

  /**
   * Update a marketplace
   *
   * @param Request $request
   * @param int $id Marketplace id to update
   *
   * @return Response
   */
  public function updateMarketplace(Request $request, $id) {

    try {
      $this->validate($request, [
        'app_id' => 'nullable | integer | exists:m6_apps,id',
        'status' => 'nullable | max:255',
        'overview' => 'nullable'
      ]);
  
      $marketplace = Marketplace::findOrFail($id);

      // Filter out undefined items
      $updateObject = collect($request->all())->filter(function ($key, $value) {
        return $value !== null;
      });

      $marketplace->update($updateObject->toArray());
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }

    return response()->json([
      'message' => 'Successful',
    ], 200);
  }

  /**
   * Delete marketplace
   *
   * @param int $id Marketplace id to delete
   *
   * @return Response
   */
  public function deleteMarketplace($id) {
    try {
      $marketplace = Marketplace::findOrFail($id);
      $marketplace->delete();
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }

    return response()->json([
      'message' => 'Successful',
    ], 200);
  }

  /**
   * Add media to marketplace
   *
   * @param Request $request
   * @param int $marketplaceId Marketplace id to add media on
   *
   * @return Response
   */
  public function addMarketplaceMedia(Request $request, $marketplaceId) {
    $marketplace = Marketplace::findOrFail($marketplaceId);

    try {
      if ($request->headers->get('Content-Length') < 50000000) {
        $parts = explode('/', $request->headers->get('Content-Type'));

        $globalPath = pathinfo(storage_path('app/public/app-builder/'.$parts[0].'/'.$request->headers->get('Content-Name')));
        $path = '/app-builder/'.$request->headers->get('path').$parts[0].'/';

        $data = array(
          "file_size" => $request->headers->get('Content-Length'),
          "file_extension" => $globalPath['extension'],
          "file_name_full" => $globalPath['basename'],
          "file_type" => $request->headers->get('Content-Type'),
          "file_name" => $globalPath['filename'],
          "file_path" => 'app-builder/'.$request->headers->get('path').$parts[0].'/'.$request->headers->get('Content-Name'),
          "file_url" => Storage::disk('public')->url($path.$globalPath['basename'])
        );

        Storage::disk('public')
          ->put($path.$globalPath['basename'], $request->getContent());

        $attachment = Attachments::create($data);
        $marketplace->media()->attach($attachment);

        return response()->json(["attachmentId" => $attachment['id']], 201);
      }
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Delete marketplace's media
   *
   * @param Request $request
   * @param int $marketplaceId Marketplace id to add media on
   *
   * @return Response
   */
  public function deleteMarketplaceMedia($marketplaceId, $mediaId) {
    try {
      $marketplace = Marketplace::findOrFail($marketplaceId);
      $media = $marketplace->media()->where('file_id', $mediaId)->firstOrFail();
      $media->delete();
      return response()->json(['message' => 'Successful'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
