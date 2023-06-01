<!DOCTYPE html>
<html lang="en">
<head>
  <title>Seekex Assignment</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js"></script>
  <link rel="stylesheet" href="custom.css">

</head>
<body>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-6">
            <h4>Bucket Form</h4>
            <div class="box">
                @if($errors->has('bucketForm'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <ul>
                            @foreach($errors->get('bucketForm') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ Session::get('success') }}
                    </div>
                @endif

               
                <form method="POST" action="{{ route('buckets_store') }}">
                    @csrf
                    <div class="form-inline mb-2">
                        <label for="bucketname" class="col-md-4">Bucket Name: </label>
                        <input type="text" class="form-control col-md-8" id="bucketname" placeholder="Enter Bucket Name" name="bucketname">
                    </div>
                    <div class="form-inline mb-2">
                        <label for="bucketvolume" class="col-md-4 text-left">Volume (in Inches):</label>
                        <input type="text" class="form-control col-md-8" id="bucketvolume" placeholder="Enter Volume (in Inches)" name="bucketvolume">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-warning font-weight-bold rounded-pill">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Ball Form</h4>
            <div class="box">
                @if($errors->has('ballForm'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <ul>
                            @foreach($errors->get('ballForm') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('ballsuccess'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ Session::get('ballsuccess') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('balls_store') }}">
                    @csrf
                    <div class="form-inline mb-2">
                        <label for="ballname" class="col-md-4">Ball Name: </label>
                        <input type="text" class="form-control col-md-8" id="ballname" placeholder="Enter Ball Name" name="ballname">
                    </div>
                    <div class="form-inline mb-2">
                        <label for="ballvolume" class="col-md-4 text-left">Volume (in Inches):</label>
                        <input type="text" class="form-control col-md-8" id="ballvolume" placeholder="Enter Volume (in Inches)" name="ballvolume">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-warning font-weight-bold rounded-pill">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <h4 class="col-md-12">Bucket Suggestion</h4><br>
        <div class="col-md-5 col_collasp_right">
            <div class="box">
                @if (Session::has('fillBucketError'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ Session::get('fillBucketError') }}
                    </div>
                @endif
                @if (Session::has('fillbucketsuccess'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ Session::get('fillbucketsuccess') }}
                    </div>
                @endif
                @if (Session::has('errorMsg'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ Session::get('errorMsg') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('fill_bucket') }}">
                    @csrf
                    @foreach($getBallList as $data)
                        <div class="form-inline mb-2">
                            <label for="pink" class="col-md-2">{{ strtoupper($data->ballname) }}: </label>
                            <input type="number" class="form-control col-md-10" id="pink" name="{{ $data->id }}">
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button type="submit" class="btn btn-warning font-weight-bold rounded-pill">PLACE BALLS IN BUCKET</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-7 col_collasp_left">
            <div class="box side_height">
                <h4>RESULT</h4>
                <p>Following are the suggested buckets:</p>
                    {!! $completeMessage !!}
            </div>
        </div>
    </div>
</div>


</body>
</html>
