<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Models\FeaturedCity;
use App\Http\Models\Location;
use Illuminate\Support\Facades\Session;
use App\Repositories\MasterRepository;

class FeaturedCityController extends Controller {
    
    private $perpage;
    
    public function __construct() {
        $this->perpage = config('app.perpage');
    }

    public function index(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                        'country' => 'required',
                        'city' => 'required|unique:featured_cities,location_id',
                        'image' => 'required',
                            ]
            );
            $response = [];
            if ($validator->passes()) {
                if ($request->file('image')) {
                    $file = $request->file('image');
                    $destinationPathProfilePic = base_path() . '/public/uploads/featured_city_images/';
                    $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($destinationPathProfilePic, $file_name);
                }
                $featuredCity = new FeaturedCity;
                $featuredCity->location_id = $request->city;
                $featuredCity->image = $file_name;
                if ($featuredCity->save()) {
                    $response['error'] = 'no';
                    $request->session()->flash('status', "Featured city added successfuly.");
                    $response['redirect_url'] = route('admin.featuredCityList');
                } else {
                    $response['error'] = 'yes';
                    $response['errors'] = ['failedmessage' => 'Not able to save your info, please try again.'];
                }
            } else {
                $response['error'] = 'yes';
                $response['errors'] = $validator->getMessageBag()->toArray();
            }
            return response()->json($response);
        }
        $countries = Location::select('country', 'id')->groupBy('country')->get();
        return view('admin.featuredcity.add_featured_city', compact('countries'));
    }

    public function getCityNameFromLocation(Request $request) {
        if (!empty($request->name)) {
            $locations = Location::where(['country' => $request->name])->orderByRaw('city ASC')->get();
            if (!empty($locations)) {
                $response = '';
                foreach ($locations as $location) {
                    $response .= '<option value="' . $location->id . '">' . $location->city . '</option>';
                }
                echo $response;
            }
        }
    }

    public function featuredCityList(Request $request) {
        $featuredCities = FeaturedCity::with('location_data')->orderByRaw('created_at DESC')
                ->paginate($this->perpage);
        return view('admin.featuredcity.listing', compact('featuredCities'));
    }

    public function activeDeactiveFeaturedCity($id = null, $status, Request $request) {
        if (!empty($status)) {
            $status = '1';
            $msg = 'Featured city activated successfuly.';
            $msgType = 'status';
        } else {
            $status = '0';
            $msg = 'Featured city deactivated successfuly.';
            $msgType = 'status';
        }
        $queryStatus = FeaturedCity::where('id', base64_decode($id))->update(['is_active' => $status]);
        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function deleteFeaturedCity($id, Request $request) {
        if (!empty($id)) {
            $featuredCity = FeaturedCity::find(base64_decode($id));
            if (!empty($featuredCity)) {
                $featuredCity->delete();
                $msg = 'Featured city deleted successfuly.';
                $msgType = 'status';
            } else {
                $msg = 'Featured city not found!';
                $msgType = 'error';
            }
        } else {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->route('admin.featuredCityList');
    }

}
