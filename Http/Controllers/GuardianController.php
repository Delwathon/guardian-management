<?php

namespace Modules\Guardian\Http\Controllers;

use App\Models\User;
use App\Models\State;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Guardian\Entities\Guardian;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admission\Entities\Enrol;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $branches = Branch::get();
        if ($request->get('branch')) {
            return redirect()->route('guardian.show', $request->branch);
        }

        return view('guardian.index', compact(['branches']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::get();
        $states = State::get();
        return view('guardian.create', compact(['branches', 'states']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'avatar' => 'sometimes|nullable|image|mimes:png,jpg',
            'branch' => 'required|numeric',
            'state' => 'required|numeric',
            'mobile' => 'required|numeric',
            'lga' => 'required|numeric',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'gender' => 'required|string',
            'religion' => 'required|string',
            'mobile' => 'required|numeric',
            'email' => 'required|email',
            'city' => 'required|string',
            'address' => 'required|string',
            'password' => 'required|confirmed',
            'relationship' => 'required|string',

        ]);

        // return $request;
        $user = new User();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->middlename = $request->middlename;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->file("avatar")) {
            $fileName = str_replace(" ", "_", $request->fname . " " . $request->lname);
            $user->addMediaFromRequest('avatar')->usingFileName($fileName)->toMediaCollection("avatar");
        }
        $user->assignRole(6);
        $employee = new Guardian();
        $employee->user_id = $user->id;
        $employee->mobile = $request->mobile;
        $employee->city = $request->city;
        $employee->state_id = $request->state;
        $employee->local_government_id = $request->lga;
        $employee->address = $request->address;
        $employee->gender = $request->gender;
        $employee->religion = $request->religion;
        $employee->branch_id = $request->branch;
        $employee->relationship = $request->relationship;
        $employee->occupation = $request->occupation;

        $employee->save();


        return redirect()->route('guardian.index')->with('success', 'New Guardian added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {

        $branches = Branch::get();
        $guardian = Guardian::with(['user', 'branch', 'students', 'state', 'lga'])->where('id', $id)->first();
        $enrols = Enrol::all()->sortByDesc('id');
        // return $guardian;
        $states = State::get();
        return view('guardian.profile', compact(['guardian', 'branches', 'states', 'enrols']));

    }


    public function show($id)
    {
        $guardians = Guardian::with(['user', 'branch'])->where('branch_id', $id)->get();
        $branches = Branch::get();
        $branche = Branch::find($id);
        return view('guardian.index', compact(['branches', 'guardians', 'branche']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'avatar' => 'sometimes|nullable|image|mimes:png,jpg',
            'branch' => 'required|numeric',
            'state' => 'required|numeric',
            'mobile' => 'required|numeric',
            'lga' => 'required|numeric',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'gender' => 'required|string',
            'religion' => 'required|string',
            'email' => 'required|email',
            'city' => 'required|string',
            'address' => 'required|string',
            'password' => 'sometimes|nullable|confirmed',
            'relationship' => 'required|string',

        ]);

        $guardian = Guardian::find($id);
        $user = User::find($guardian->id);
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->middlename = $request->middlename;
        if ($request->password) {
            # code...
            $user->password = Hash::make($request->password);
        }
        $user->update();
        $guardian->user_id = $user->id;
        $guardian->mobile = $request->mobile;
        $guardian->city = $request->city;
        $guardian->state_id = $request->state;
        $guardian->local_government_id = $request->lga;
        $guardian->address = $request->address;
        $guardian->gender = $request->gender;
        $guardian->religion = $request->religion;
        $guardian->branch_id = $request->branch;
        $guardian->relationship = $request->relationship;
        $guardian->occupation = $request->occupation;
        $guardian->update();
        return redirect()->back()->with('success', 'Guardian profile updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}