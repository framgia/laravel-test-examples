@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-header-flex">
                            <span>Cities</span>
                            <a href="{{ route('cities.create') }}" class="action-link">
                                Create New City
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cities as $city)
                                    <tr>
                                        <td>{{ $city->getKey() }}</td>
                                        <td><a href="{{ route('cities.show', $city) }}">{{ $city->name }}</a></td>
                                        <td>{{ $city->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $city->updated_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="table-instance-actions">
                                            <a href="{{ route('cities.edit', $city) }}" class="btn btn-sm btn-default">Edit</a>
                                            <form style="display:inline-block;" action="{{ route('cities.destroy', $city) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button class="btn btn-sm btn-danger-outline" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $cities }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
