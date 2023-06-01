<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

use App\Models\BallList;

class BallController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ballname' => 'required|string|unique:ball_lists,ballname',
            'ballvolume' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ],
        [
            'ballname.required' => 'The ball name field is required.',
            'ballname.unique' => 'The ball name has already been taken.',
            'ballvolume.required' => 'The ball volume field is required.',
            'ballvolume.numeric' => 'The ball volume field must be a number.',
            'ballvolume.regex' => 'The ball volume field format is invalid.'
        ]);

        $errorlist = [];
        foreach ($validator->errors()->all() as $error) {
            $errorlist[] = $error;
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['ballForm' => $errorlist]);
        }

        $bucket = new BallList();
        $bucket->ballname = $request->ballname;
        $bucket->ballvolume = $request->ballvolume;
        $bucket->save();

        return redirect()->route('home')->with('ballsuccess', 'Ball created successfully!');
    }
}
