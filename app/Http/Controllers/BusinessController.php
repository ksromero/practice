<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Business;
use App\Http\Resources\BusinessResource;

class BusinessController extends Controller
{
    //If no search query is available show the business collection else show the business collection filtered by query
    public function index(Request $request)
    {
        $business = Business::orderBy('created_at', 'desc')->paginate(5);
        $businessCollection = BusinessResource::collection($business);
        if (request()->has('q')) {
            $keyword = '%'.request()->get('q').'%';
            $builder = Business::orderBy('created_at', 'desc');
            $builder = $builder->where('businessName', 'like', $keyword)->paginate(5);
            $builderCollection = BusinessResource::collection($builder);
            return $builderCollection->count() ? $builderCollection : $businessCollection;
        }
        return $businessCollection;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'businessName' => 'required',
            'businessAddress' => 'required',
            'businessType' => 'required'
        ]);
        //check if method is put
        $business = $request->isMethod('put') ? Business::findOrFail($request->business_id) : new Business;
        $business->id = $request->input('business_id');
        $business->businessName = $request->input('businessName');
        $business->businessAddress = $request->input('businessAddress');
        $business->businessType = $request->input('businessType');
        if($business->save()) 
        {
            return new BusinessResource($business);
        }
    }
    public function show($id)
    {
        $business =  Business::findOrFail($id);
        return new BusinessResource($business);
    }
    public function destroy($id)
    {
        $business  = Business::findOrFail($id);
        $business->delete();
        return new BusinessResource($business);
    }

}
