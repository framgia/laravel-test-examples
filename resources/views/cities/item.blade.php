@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>City: {{ $city->getKey() }}</span>
                            <a href="{{ route('cities.index') }}" class="action-link">
                                Back to index
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <h1>{{ $city->name }}</h1>
                        <p>Created at: {{ $city->created_at->format('Y-m-d H:i:s') }}</p>
                        <p>Updated at: {{ $city->updated_at->format('Y-m-d H:i:s') }}</p>

                        <hr>

                        <div>
                            <a href="{{ route('cities.edit', $city) }}" class="btn btn-default">Edit</a>
                            <form style="display:inline-block;" action="{{ route('cities.destroy', $city) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-danger-outline" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
