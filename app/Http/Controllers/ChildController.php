<?php
namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ParentModel;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function index()
    {
        $children = Child::paginate(10);
        return view('children.index', compact('children'));
    }

    public function create()
    {
        $parents = ParentModel::all();
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();

        return view('children.create', compact('parents', 'countries', 'states', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => 'required|email|unique:children,email',
            'birth_date'     => 'required|date',
            'country_id'     => 'required|exists:countries,id',
            'state_id'       => 'required|exists:states,id',
            'city_id'        => 'required|exists:cities,id',
            'parents'        => 'array',
            'profile_image'  => 'nullable|image',
            'birth_certificate' => 'nullable|file',
        ]);

        $data = $request->except(['parents']);

        // Handle file uploads
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('children/profile');
        }

        if ($request->hasFile('birth_certificate')) {
            $data['birth_certificate'] = $request->file('birth_certificate')->store('children/certificates');
        }

        $child = Child::create($data);

        if ($request->parents) {
            $child->parents()->sync($request->parents);
        }

        return redirect()->route('dashboard')->with('success', 'Child created successfully.');
    }

   public function edit(Child $child)
    {
        $parents = ParentModel::all();
        $countries = Country::all();
        $states = State::where('country_id', $child->country_id)->get();
        $cities = City::where('state_id', $child->state_id)->get();

        return view('children.edit', compact('child', 'parents', 'countries', 'states', 'cities'));
    }

    public function update(Request $request, Child $child)
    {
        $request->validate([
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => "required|email|unique:children,email,$child->id",
            'birth_date'     => 'required|date',
            'country_id'     => 'required|exists:countries,id',
            'state_id'       => 'required|exists:states,id',
            'city_id'        => 'required|exists:cities,id',
            'parents'        => 'array',
        ]);

        $data = $request->except(['parents']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('children/profile');
        }

        if ($request->hasFile('birth_certificate')) {
            $data['birth_certificate'] = $request->file('birth_certificate')->store('children/certificates');
        }

        $child->update($data);

        if ($request->parents) {
            $child->parents()->sync($request->parents);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Child updated successfully.');
    }
}
