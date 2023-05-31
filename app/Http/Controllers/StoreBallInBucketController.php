<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\BucketList;
use App\Models\BallList;
use App\Models\StoreBallInBucket;
use App\Models\RemainingBall;

class StoreBallInBucketController extends Controller
{
    public function fill_bucket(Request $request)
    {
        $errorMsg = '';
        $request_number = rand();
        $getBucketList = BucketList::get();
        foreach($getBucketList as $bucketData){
            $bucketID = $bucketData->id;
            $getBallList = BallList::get();
            foreach($getBallList as $ballData){
                $ballID = $ballData->id;
                $singleBallVolume = $ballData->ballvolume;
                $num_of_balls = $request[$ballData->id];

                $remainCond = [
                    'request_number' => $request_number,
                    'ball_id' => $ballID
                ];
                $RemainingBallCount = RemainingBall::where($remainCond)->count();
                if($RemainingBallCount > 0){
                    $RemainingBallData = RemainingBall::where($remainCond)->first();
                    $num_of_balls = $RemainingBallData->remaining_balls;
                }
                $ballFilledSpace = $num_of_balls * $ballData->ballvolume;

                if(!empty($num_of_balls) && $num_of_balls > 0){
                    $storeBallCond = [
                        'request_number' => $request_number,
                        'ball_id' => $ballID,
                        'num_of_left_ball' => 0
                    ];
                    $StoreBallInBucketCount = StoreBallInBucket::where($storeBallCond)->count();
                    if($StoreBallInBucketCount == 0){
                        $cond = [
                            'id' => $bucketID
                        ];
                        $getBucketFreshList = BucketList::where($cond)->first();
                        $bucketEmptyVolume = $getBucketFreshList->bucket_remaining_volume;
                        /**
                         * condition one
                         * Bucket empty space is greater than ball filled space
                         */
                        if($bucketEmptyVolume > $ballFilledSpace){
                            $remainEmptySpaceOfBucket = $bucketEmptyVolume - $ballFilledSpace;
    
                            $instBallInBucket = new StoreBallInBucket;
                            $instBallInBucket->request_number = $request_number;
                            $instBallInBucket->bucket_id = $bucketID;
                            $instBallInBucket->ball_id = $ballID;
                            $instBallInBucket->total_ball = $num_of_balls;
                            $instBallInBucket->bucket_empty_space = $bucketEmptyVolume;
                            $instBallInBucket->ball_filled_space = $ballFilledSpace;
                            $instBallInBucket->remain_empty_space_in_bucket = $remainEmptySpaceOfBucket;
                            $instBallInBucket->num_of_left_ball = 0;
                            $instBallInBucket->save();
    
                            BucketList::where($cond)->update(['bucket_remaining_volume' => $remainEmptySpaceOfBucket]);
                        } else if($bucketEmptyVolume < $ballFilledSpace){
                            /**
                             * condition two
                             */
                            /**
                             * If empty bucket volume is less then single ball volume so it voilet the condition.
                             * for ex:- if we have some space in our bucket which is 2.5 inch and the ball volume is 3 inch so, 
                             * its not possible to store that ball in our bucket.
                             * Bucket empty volume must not be less than zero
                             * 
                             */
                            if($bucketEmptyVolume > $singleBallVolume){
                                /**
                                 * Checking how much number of ball get fitin into the bucket 
                                 * Number of balls = Bucket volume / Ball volume
                                 * */
                                $numberOfBallsFilled = floor($bucketEmptyVolume / $singleBallVolume);
                                $maxFilledSpace = $numberOfBallsFilled * $singleBallVolume;
                                if($bucketEmptyVolume > $maxFilledSpace){
                                    $remainEmptySpaceOfBucket = $bucketEmptyVolume - $maxFilledSpace;
                                    $num_of_left_ball = $num_of_balls - $numberOfBallsFilled;
    
                                    $instBallInBucket = new StoreBallInBucket;
                                    $instBallInBucket->request_number = $request_number;
                                    $instBallInBucket->bucket_id = $bucketID;
                                    $instBallInBucket->ball_id = $ballID;
                                    $instBallInBucket->total_ball = $num_of_balls;
                                    $instBallInBucket->bucket_empty_space = $bucketEmptyVolume;
                                    $instBallInBucket->ball_filled_space = $maxFilledSpace;
                                    $instBallInBucket->remain_empty_space_in_bucket = $remainEmptySpaceOfBucket;
                                    $instBallInBucket->num_of_left_ball = $num_of_left_ball;
                                    $instBallInBucket->save();
    
                                    BucketList::where($cond)->update(['bucket_remaining_volume' => $remainEmptySpaceOfBucket]);
                                    if($RemainingBallCount > 0){
                                        $RemainingBallDataForUpd = RemainingBall::where($remainCond)->first();
                                        $updRemainingBall = RemainingBall::find($RemainingBallDataForUpd->id);
                                    } else {
                                        $updRemainingBall = new RemainingBall;
                                    }
                                    $updRemainingBall->request_number = $request_number;
                                    $updRemainingBall->ball_id = $ballID;
                                    $updRemainingBall->remaining_balls = $num_of_left_ball;
                                    $updRemainingBall->save();
                                } else if($bucketEmptyVolume <= $maxFilledSpace) {
                                    $ball_filled_space = $bucketEmptyVolume - $singleBallVolume;
                                    $remainEmptySpaceOfBucket = $bucketEmptyVolume - $ball_filled_space;
                                    $gettingStoredNumberOfBalls = $ball_filled_space / $singleBallVolume;
                                    $num_of_left_ball = $num_of_balls - $gettingStoredNumberOfBalls;
                                
                                    $instBallInBucket = new StoreBallInBucket;
                                    $instBallInBucket->request_number = $request_number;
                                    $instBallInBucket->bucket_id = $bucketID;
                                    $instBallInBucket->ball_id = $ballID;
                                    $instBallInBucket->total_ball = $num_of_balls;
                                    $instBallInBucket->bucket_empty_space = $bucketEmptyVolume;
                                    $instBallInBucket->ball_filled_space = $ball_filled_space;
                                    $instBallInBucket->remain_empty_space_in_bucket = $remainEmptySpaceOfBucket;
                                    $instBallInBucket->num_of_left_ball = $num_of_left_ball;
                                    $instBallInBucket->save();
    
                                    BucketList::where($cond)->update(['bucket_remaining_volume' => $remainEmptySpaceOfBucket]);
    
                                    if($RemainingBallCount > 0){
                                        $RemainingBallDataForUpd = RemainingBall::where($remainCond)->first();
                                        $updRemainingBall = RemainingBall::find($RemainingBallDataForUpd->id);
                                    } else {
                                        $updRemainingBall = new RemainingBall;
                                    }
                                    $updRemainingBall->request_number = $request_number;
                                    $updRemainingBall->ball_id = $ballID;
                                    $updRemainingBall->remaining_balls = $num_of_left_ball;
                                    $updRemainingBall->save();
                                    
                                }
                            } else {
                                $errorMsg = 'Bucket riched maximum possible volume of the balls to be placed so its not allowing any placement.';
                            }
                        }
                    }
                }
            }
        }

        if(!empty($errorMsg)){
            return redirect()->route('home')->with(['errorMsg' => $errorMsg]);
        } else {
            return redirect()->route('home')->with(['fillbucketsuccess' => 'Ball fill into the bucket successfully!']);
        }

    }
}
