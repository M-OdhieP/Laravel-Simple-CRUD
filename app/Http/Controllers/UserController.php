<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(3);

        return view('users.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 3);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|unique:users',
    //     ]);

    //     User::create($request->all());

    //     return redirect()->route('users.index')
    //         ->with('success', 'User created successfully.');
    // }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Save the image
        $imageName = time() . '.' . $request->image->extension();


        $request->image->move(public_path('images'), $imageName);

        // Save the user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $imageName;
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Delete the old image
            $oldImagePath = public_path('images') . '/' . $user->image;
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Save the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('/images'), $imageName);
            $user->image = $imageName;
        }

        $user->update($request->only(['name', 'email']));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Delete the image file
        $imagePath = public_path('images') . '/' . $user->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $user->delete();


        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
