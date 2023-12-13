<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    public function index()
    {
        return view('author.settings');
    }

    public function updateProfile(Request $request) {
        $this->validate($request, [
            'email' => 'email',
            'image' => 'mimes:jpg,jpng,png',
        ]);
        $image = $request->file('image');
        $slug = str_slug($request->name);
        $user = User::findOrFail(Auth::id());
        if(isset($image))
        {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().$image->getClientOriginalExtension();
            if(!Storage::disk('public')->exists('profile'))
            {
                Storage::disk('public')->makeDirectory('profile');
            }
            if(!Storage::disk('public')->exists('profile'))
            {
                Storage::disk()->delete('/profile'.$user->image);
            }
            $profile =  Image::make($image)->resize(500,500)->save($imageName);
            Storage::disk('public')->put('profile/'.$imageName,$profile);
        } else {
            $imageName = $user->image;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $imageName;
        $user->about = $request->about;
        $user->save();
        Toastr::success('Profile Successfully Updated :)','Success');
        return redirect()->back();
    }

    public function updatePassword(Request $request) 
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'old_password' => 'required',
        ]);
        $hasedPassword = Auth::user()->password;
        if(Hash::check($request->old_password, $hasedPassword)) 
        {
            if(!Hash::check($request->password,$hasedPassword)) 
            {
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::logout(); 
                Toastr::success('Password Successfully Saved!','Success');
                return redirect()->back();
               
            }
            else {
                Toastr::error('New password canot be the same as old password');
                return redirect()->back();
            }
        } 
        else {
            return redirect()->back();
        }
    }
}
