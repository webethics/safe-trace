@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('global.request.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.request.fields.name') }}
                    </th>
                    <td>
                        {{ $request->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.request.fields.company') }}
                    </th>
                    <td>
                        {!! $request->company !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.request.fields.url') }}
                    </th>
                    <td>
                        ${{ $request->url }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection