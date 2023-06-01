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
       
        $message = $completeMessage = '';
        $StoreBallInBucketData = StoreBallInBucket::select('*')->groupBy('request_number')->orderBy('id','desc')->get();
        foreach($StoreBallInBucketData as $data){
            $message = '';
            $completeMessage .= '<div class="resultMsg"><ul>';
            $subRecordOfStore = StoreBallInBucket::where(['request_number' => $data->request_number])->groupBy('bucket_id')->get();
            foreach($subRecordOfStore as $subData){
                $countStoreDataForMsg = StoreBallInBucket::where(['request_number' => $data->request_number , 'bucket_id' => $subData->bucket_id])->count();
                $getStoreDataForMsg = StoreBallInBucket::where(['request_number' => $data->request_number , 'bucket_id' => $subData->bucket_id])->get();
                $BucketListData = BucketList::where(['id' => $subData->bucket_id])->first();
                $bucketName = $BucketListData->bucketname;
                $insideMsg = 'Place ';
                $i = 1;
                $secondLast = $countStoreDataForMsg - 1;
                foreach($getStoreDataForMsg as $msgData){
                    $BallListData = BallList::where(['id' => $msgData->ball_id])->first();
                    $ballName = $BallListData->ballname;
                    
                    $filledBall = $msgData->total_ball - $msgData->num_of_left_ball;
                    if($countStoreDataForMsg == $i){
                        $ballString = ' ball ';
                        if($filledBall > 1){
                            $ballString = ' balls ';
                        }
                        $insideMsg .= $filledBall .' '.$ballName . $ballString;
                    } else if($i == $secondLast){
                        $ballString = ' ball and ';
                        if($filledBall > 1){
                            $ballString = ' balls and ';
                        }
                        $insideMsg .= $filledBall .' '.$ballName . $ballString;
                    } else {
                        $ballString = ' ball, ';
                        if($filledBall > 1){
                            $ballString = ' balls, ';
                        }
                        $insideMsg .= $filledBall .' '.$ballName . $ballString;
                    }
                    $i++;
                }
                $message .= '<li>Bucket '.$bucketName.': <b>'.$insideMsg.' are stored in the bucket.</b></li>';
            }

            /**
             * Get the number of remain balls.
             */
            /** start */
                $typeRemainMessage = '';
                $RemainingBallCount = RemainingBall::where(['request_number' => $data->request_number])->count();
                if($RemainingBallCount > 0){
                    $RemainingBallData = RemainingBall::where(['request_number' => $data->request_number])->get();
                    $maxCount = $RemainingBallCount;
                    $secondLastCount = $maxCount - 1;
                    $i = 1;
                    foreach($RemainingBallData as $remainData){
                        $getBallListRecord = BallList::where(['id' => $remainData->ball_id])->first();
                        if($maxCount == $i){
                            $ballString = ' ball ';
                            if($remainData->remaining_balls > 1){
                                $ballString = ' balls ';
                            }
                            $typeRemainMessage .= $remainData->remaining_balls .' '.$getBallListRecord->ballname . $ballString;
                        } else if($secondLastCount == $i){
                            $ballString = ' ball and ';
                            if($remainData->remaining_balls > 1){
                                $ballString = ' balls and ';
                            }
                            $typeRemainMessage .= $remainData->remaining_balls .' '.$getBallListRecord->ballname . $ballString;
                        } else {
                            $ballString = ' ball, ';
                            if($remainData->remaining_balls > 1){
                                $ballString = ' balls, ';
                            }
                            $typeRemainMessage .= $remainData->remaining_balls .' '.$getBallListRecord->ballname . $ballString;
                        }
                        $i++;
                    }
                }
                if(!empty($typeRemainMessage)){
                    $typeRemainMessage = '<b>Remaining Balls:</b> '.$typeRemainMessage.' are not stored in the bucket.</p>';
                }
            /** end */
            $completeMessage .= $message.'</ul><p class="remainMsg">'.$typeRemainMessage.'</div>';

        }
        return view('welcome' , compact('getBallList','message','completeMessage'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bucketname' => 'required|string|unique:bucket_lists,bucketname',
            'bucketvolume' => 'required|numeric',
        ],
        [
            'bucketname.required' => 'The bucket name field is required.',
            'bucketname.unique' => 'The bucket name has already been taken.',
            'bucketvolume.required' => 'The bucket volume field is required.',
            'bucketvolume.numeric' => 'The bucket volume field must be a number.'
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
