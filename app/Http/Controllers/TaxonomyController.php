<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\TaxonomyVocabularies;
use App\TaxonomyTerms;

class TaxonomyController extends Controller
{
  /**
   * Get list of vocabularies
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getVocabularies()
  {
    try {
      $vocabularies = TaxonomyVocabularies::all();
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($vocabularies, 200);
  }

  /**
   * Get list of terms
   *
   * @return \Illuminate\Http\Response
   */
  public function getTerms($vocabularyId)
  {
    try {
      $terms = TaxonomyTerms::where('vocabulary_id', $vocabularyId)->get();
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($terms, 200);
  }

  /**
   * Get all of terms
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getAllTerms()
  {
    try {
      $vocabulary = TaxonomyVocabularies::all()->pluck('id');
      $terms = TaxonomyTerms::whereIn('vocabulary_id', $vocabulary)->get();
      return response()->json($terms, 200);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }

  }

  /**
   * Get taxonomy term from term id
   *
   * @return \Illuminate\Http\Response
   */
  public function getTerm($termId)
  {
    try {
      $term = TaxonomyTerms::where('id', $termId)->get();
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json($term, 200);
  }

  /**
   * Create Vocabulary
   *
   * @return \Illuminate\Http\Response
   */
  public function createVocabulary(Request $request)
  {
    try {
      $this->validate($request, [
        'title' => 'required',
        'weight' => 'required | integer',
        'description' => 'required'
      ]);
      $newVocabulary['title'] = $request->title;
      $newVocabulary['description'] = $request->description;
      $newVocabulary['weight'] = $request->weight;
      TaxonomyVocabularies::create($newVocabulary);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json(['result' => true], 200);
  }

  /**
   * Create Term
   *
   * @return \Illuminate\Http\Response
   */
  public function createTerm(Request $request)
  {
    try {
      $this->validate($request, [
        'title' => 'required',
        'weight' => 'required | integer',
        'vocabulary_id' => 'required | integer',
        'description' => 'required'
      ]);
      $newTerm['title'] = $request->title;
      $newTerm['description'] = $request->description;
      $newTerm['weight'] = $request->weight;
      $newTerm['vocabulary_id'] = $request->vocabulary_id;
      $newTerm['parent_id'] = $request->parent_id;
      $newTerm['code'] = $request->code;

      TaxonomyTerms::create($newTerm);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json(['result' => true], 200);
  }

  /**
   * Remove Vocabulary
   *
   * @return \Illuminate\Http\Response
   */
  public function removeVocabulary($id)
  {
    try {
      $value = TaxonomyVocabularies::findOrFail($id);
      $value->delete();
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json(['result' => true], 200);
  }

  /**
   * Remove Term
   *
   * @return \Illuminate\Http\Response
   */
  public function removeTerm($id)
  {
    try {
      $term = TaxonomyTerms::findOrFail($id);
      $term->delete();
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json(['result' => true], 200);
  }

  /**
   * Update Vocabulary
   *
   * @return \Illuminate\Http\Response
   */
  public function updateVocabulary(Request $request, $id)
  {
    try {
      $this->validate($request, [
        'title' => 'required',
        'weight' => 'required | integer',
        'description' => 'required'
      ]);
      TaxonomyVocabularies::where('id', '=', $id)->update([
        'title' => $request->title,
        'description' => $request->description,
        'weight' => $request->weight
      ]);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json(['result' => true], 200);
  }

  /**
   * Update Term
   *
   * @return \Illuminate\Http\Response
   */
  public function updateTerm(Request $request, $id)
  {
    try {
      $this->validate($request, [
        'title' => 'required',
        'weight' => 'required | integer',
        'vocabulary_id' => 'required | integer',
        'description' => 'required'
      ]);
      TaxonomyTerms::where('id', '=', $id)->update([
        'vocabulary_id' => $request->vocabulary_id,
        'title' => $request->title,
        'description' => $request->description,
        'weight' => $request->weight,
        'code' => $request->code
      ]);
    } catch (QueryException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json(['result' => true], 200);
  }

}
