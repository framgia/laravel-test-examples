@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Create New City</span>
                            <a href="{{ route('cities.index') }}" class="action-link">
                                Back to index
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="post"
                              action="{{ ($city) ? route('cities.update', $city) : route('cities.store') }}"
                        >
                            {{ csrf_field() }}
                            @if ($city)
                                {{ method_field('PUT') }}
                            @endif

                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">City name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ old('name', data_get($city, 'name')) }}"
                                           required autofocus
                                    >
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                    <a class="btn btn-default" href="{{ url()->previous() }}">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
