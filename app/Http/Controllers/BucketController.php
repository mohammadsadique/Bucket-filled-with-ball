<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\BucketList;
use App\Models\BallList;
use App\Models\StoreBallInBucket;
use App\Models\RemainingBall;

class BucketController extends Controller
{
    public function home()
    {
        $getBallList = BallList::get();
       
        $message = $subMessage = '';
        $StoreBallInBucketData = StoreBallInBucket::select('*')->groupBy('request_number')->orderBy('id','desc')->get();
        foreach($StoreBallInBucketData as $data){
            $message = '';
            $subMessage .= '<div class="resultMsg"><ul>';
            $subRecordOfStore = StoreBallInBucket::where(['request_number' => $data->request_number])->groupBy('bucket_id')->get();
            foreach($subRecordOfStore as $subData){
                $countStoreDataForMsg = StoreBallInBucket::where(['request_number' => $data->request_number , 'bucket_id' => $subData->bucket_id])->count();
                $getStoreDataForMsg = StoreBallInBucket::where(['request_number' => $data->request_number , 'bucket_id' => $subData->bucket_id])->get();
                $BucketListData = BucketList::where(['id' => $subData->bucket_id])->first();
                $bucketName = $BucketListData->bucketname;
                $insideMsg = 'Place ';
                $remainInsideMsg = 'Remaining ';
                $i = 1;
                $secondLast = $countStoreDataForMsg - 1;
                foreach($getStoreDataForMsg as $msgData){
                    $BallListData = BallList::where(['id' => $msgData->ball_id])->first();
                    $ballName = $BallListData->ballname;
                    
                    $filledBall = $msgData->total_ball - $msgData->num_of_left_ball;
                    if($countStoreDataForMsg == 1){
                        $insideMsg .= $filledBall .' '.$ballName .' balls.' ;
                    } else if($i == $secondLast){
                        $insideMsg .= $filledBall .' '.$ballName .' balls and ' ;
                    } else {
                        $insideMsg .= $filledBall .' '.$ballName .' balls, ' ;
                    }
                    $i++;
                }
                $message .= '<li>Bucket '.$bucketName.': <b>'.$insideMsg.'</b></li>';
                
            }
            $subMessage .= $message.'</ul></div>';
        }
        return view('welcome' , compact('getBallList','message','subMessage'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bucketname' => 'required|string',
            'bucketvolume' => 'required|numeric',
        ],
        [
            'bucketname.required' => 'The bucket name field is required.',
            'bucketvolume.required' => 'The bucket volume field is required.'
        ]);
        $errorlist = [];
        foreach ($validator->errors()->all() as $error) {
            $errorlist[] = $error;
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['bucketForm' => $errorlist]);
        }

        $bucket = new BucketList();
        $bucket->bucketname = $request->bucketname;
        $bucket->bucketvolume = $request->bucketvolume;
        $bucket->bucket_remaining_volume = $request->bucketvolume;
        $bucket->save();

        return redirect()->route('home')->with('success', 'Bucket created successfully!');
    }
}
