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
                                            <div class="row text-left">
                                                <p>Enter group name or select existing:</p>
                                            </div>
                                            <div class="row text-left pb-2">
                                                <select class="groups" name="group" style="width: 50%" required>
                                                    @foreach($groups as $group)
                                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row align-items-center">
                                                <label for="import">Upload Excel Spreadsheet For Import</label>
                                            </div>
                                            <div class="row align-items-center pt-1">
                                                <input type="file" id="import" name="file" required>
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
                {{--<div class="card mt-4">--}}
                    {{--<div class="card-header">Edit Groups</div>--}}
                    {{--<div class="card-body">--}}
                        {{--@if (session('status'))--}}
                            {{--<div class="alert alert-success" role="alert">--}}
                                {{--{{ session('status') }}--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        {{--<table class="table">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th>Group Name:</th>--}}
                                {{--<th class="text-right">Delete?:</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--@foreach($groups as $group)--}}

                                {{--<tr>--}}
                                    {{--<td>{{$group->name}}</td>--}}
                                    {{--<td class="text-right">--}}
                                        {{--<form method="POST" action="{{route('groups.delete')}}">--}}
                                            {{--{{ csrf_field() }}--}}
                                            {{--{{ method_field('DELETE') }}--}}
                                            {{--<input type="hidden" value="{{$group->id}}">--}}
                                            {{--<button class="btn btn-link text-danger"><i class="fas fa-trash-alt"></i></button>--}}
                                        {{--</form>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}

                            {{--@endforeach--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
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
                <div class="card mt-4">
                    <div class="card-header">Download App</div>
                    <div class="card-body">
                        <div class="row">
                            <a href="itms-services://?action=download-manifest&url=https://weightmate.ca/DistributionManifest.plist"
                               class="btn btn-primary btn-block m-5">Download App</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection