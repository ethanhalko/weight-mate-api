@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Import</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <h3>Import</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <form method="POST"
                                          action="{{ route('import.store') }}"
                                          enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <label for="import">Upload Excel Spreadsheet For Import</label>
                                            </div>
                                            <div class="row text-left">
                                                <select class="groups" name="group" style="width: 50%">
                                                    @foreach($groups as $group)
                                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row align-items-center pt-1">
                                                <input type="file" id="import" name="file">
                                            </div>
                                            <div class="row align-items-center pt-1">
                                                <label class="form-check-label pr-4" for="overwrite">Overwrite
                                                    Group</label>
                                                <input class="form-check-input"
                                                       type="hidden"
                                                       id="overwrite"
                                                       name="overwrite"
                                                       value="0">
                                                <input type="checkbox" id="overwrite" name="overwrite" value="1">
                                            </div>
                                            <div class="row align-items-center pt-4">
                                                <button class="btn btn-primary" type="submit">Submit</button>
                                            </div>
                                        </div>
                                        {{ csrf_field() }}
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Export</div>
                    <div class="card-body">
                        <div class="row">
                            <a href="{{route('export')}}" class="btn btn-primary btn-block m-5">Export</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection