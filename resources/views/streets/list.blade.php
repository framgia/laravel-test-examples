@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-header-flex">
                            <span>Streets</span>
                            <a href="{{ route('streets.create') }}" class="action-link">
                                Create New Street
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>City ID</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($streets as $street)
                                    <tr>
                                        <td>{{ $street->getKey() }}</td>
                                        <td><a href="{{ route('streets.show', $street) }}">{{ $street->name }}</a></td>
                                        <td>{{ $street->city_id }}</td>
                                        <td>{{ $street->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $street->updated_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="table-instance-actions">
                                            <a href="{{ route('streets.edit', $street) }}" class="btn btn-sm btn-default">Edit</a>
                                            <form style="display:inline-block;" action="{{ route('streets.destroy', $street) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button class="btn btn-sm btn-danger-outline" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $streets }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
