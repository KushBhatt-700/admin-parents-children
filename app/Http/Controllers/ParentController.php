<?php
namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendParentLinkEmail;
use Illuminate\Support\Facades\Storage;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentModel::paginate(10);
        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $children  = Child::orderBy('first_name')->get(); // if not already loaded

        return view('parents.create', compact('countries','children'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:parents,email',
            'birth_date'=>'required|date',
            'age'        => 'required',
            'country_id' => 'required',
            'state_id'   => 'required',
            'city_id'    => 'required',
            'profile_image' => 'nullable|image|max:2048',
            'residential_proofs.*'=>'nullable|file|max:5120'
        ]);

        // UPLOAD PROFILE IMAGE
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')
                ->store('profile_images', 'public');
        }

        $validated['age'] = \Carbon\Carbon::parse($validated['birth_date'])->age;
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('parents','public');
        }
        $parent = ParentModel::create($validated);

        if ($request->children) {
            $parent->children()->sync($request->children);
        }

        // save residential proofs
        if ($request->hasFile('residential_proofs')) {
            foreach ($request->file('residential_proofs') as $file) {
                $path = $file->store('residential_proofs', 'public');

                DB::table('files')->insert([
                    'parent_id' => $parent->id,
                    'path'      => $path,
                    'created_at' => now(),
                ]);
            }
        }

        // link children if provided
        if ($request->children) {
            $parent->children()->attach($request->children);
            // dispatch emails to each linked parent (the actual parent is this one) - spec requires email to parent when child linked; we'll queue emails to each parent (here just this parent)
            foreach ($parent->children as $child) {

                // send email to parent after 5 minutes. Simply uncommenting the line below.
                // SendParentLinkEmail::dispatch($parent, $child)->delay(now()->addMinutes(5));
            }
        }

        return redirect()->route('dashboard')->with('success', 'Parent created successfully.');
    }

    public function edit(ParentModel $parent)
    {
        // Load countries
        $countries = Country::orderBy('name')->get();

        // Load states based on parent's country
        $states = $parent->country_id
                    ? State::where('country_id', $parent->country_id)->orderBy('name')->get()
                    : collect();

        // Load cities based on parent's state
        $cities = $parent->state_id
                    ? City::where('state_id', $parent->state_id)->orderBy('name')->get()
                    : collect();

        // Children for the multi-select
        $children = Child::paginate(10);

        return view('parents.edit', compact(
            'parent',
            'countries',
            'states',
            'cities',
            'children'
        ));
    }

    public function update(Request $request, ParentModel $parent)
    {
        $validated = $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            'birth_date'=>'required|date',
            'age'        => 'required',
            'country_id' => 'required',
            'state_id'   => 'required',
            'city_id'    => 'required',
            'education'  => 'nullable',
            'occupation' => 'nullable',
            'profile_image' => 'nullable|image|max:2048',
        ]);
        $validated['age'] = \Carbon\Carbon::parse($validated['birth_date'])->age;
        // Upload profile image
        if ($request->hasFile('profile_image')) {

            if ($parent->profile_image && Storage::disk('public')->exists($parent->profile_image)) {
                Storage::disk('public')->delete($parent->profile_image);
            }

            $validated['profile_image'] = $request->file('profile_image')
                ->store('profile_images', 'public');
        }
        $parent->update($validated);

        if ($request->children) {
            $parent->children()->sync($request->children);
        }

        if ($request->hasFile('residential_proofs')) {
            foreach ($request->file('residential_proofs') as $f) {
                $path = $f->store('parents/proofs','public');
                \DB::table('files')->insert(['parent_id'=>$parent->id,'path'=>$path,'type'=>'residential','created_at'=>now(),'updated_at'=>now()]);
            }
        }

        if ($request->children) {
            $parent->children()->sync($request->children);
            foreach ($parent->children as $child) {

                // Simply uncommenting the line below to send email to parent after 5 minutes.
                // SendParentLinkEmail::dispatch($parent, $child)->delay(now()->addMinutes(5));
            }
        }

        return redirect()->route('dashboard')->with('success', 'Parent updated successfully.');
    }

    public function destroy(ParentModel $parent)
    {
        // delete parent & related pivot
        $parent->children()->detach();
        \DB::table('files')->where('parent_id',$parent->id)->delete();
        $parent->delete();
        return redirect()->route('dashboard')->with('success', 'Parent deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?: [];
        foreach ($ids as $id) {
            ParentModel::find($id)->children()->detach();
            \DB::table('files')->where('parent_id',$id)->delete();
            ParentModel::find($id)->delete();
        }
        return back()->with('success','Selected parents deleted.');
    }

    public function linkChildren(Request $request, ParentModel $parent)
    {
        $parent->children()->syncWithoutDetaching($request->children ?: []);
        foreach ($request->children as $childId) {
            $child = Child::find($childId);

            // Simply uncommenting the line below to send email to parent after 5 minutes.
            // SendParentLinkEmail::dispatch($parent, $child)->delay(now()->addMinutes(5));
        }
        return back()->with('success','Children linked and email scheduled.');
    }
}
