@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('global.request.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.requests.update", [$request->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('global.request.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($request) ? $request->name : '') }}">
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.request.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                <label for="company">{{ trans('global.request.fields.company') }}</label>
                <input type="text" id="company" name="company" class="form-control " value="{{ old('company', isset($request) ? $request->company : '') }}">
                @if($errors->has('company'))
                    <em class="invalid-feedback">
                        {{ $errors->first('company') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.request.fields.company_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                <label for="url">{{ trans('global.request.fields.url') }}</label>
                <input type="url" id="url" name="url" class="form-control" value="{{ old('url', isset($request) ? $request->url : '') }}" step="0.01">
                @if($errors->has('url'))
                    <em class="invalid-feedback">
                        {{ $errors->first('url') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.request.fields.url_helper') }}
                </p>
            </div>
			
			<div class="form-group {{ $errors->has('other_info') ? 'has-error' : '' }}">
                <label for="other_info">{{ trans('global.request.fields.other_info') }}</label>
                 <textarea id="other_info" name="other_info" class="form-control ">{{ old('other_info', isset($request) ? $request->other_info : '') }}</textarea>
                @if($errors->has('other_info'))
                    <em class="invalid-feedback">
                        {{ $errors->first('other_info') }}
                    </em>
                @endif 
            </div>
			 <div class="form-group {{ $errors->has('priority') ? 'has-error' : '' }}">
                <label for="priority">{{ trans('global.request.fields.priority') }}</label>
                <input type="radio" id="priority" name="priority" class="form-control" value="normal"
				@if($request->priority='normal') checked=checked  @endif >{{ trans('global.request.fields.normal') }} <span>{{ trans('global.request.fields.5_10_wokring_days') }}</span>
                
				<input type="radio" id="priority" name="priority" class="form-control" value="urgent"
				@if($request->priority='urgent') checked=checked  @endif >{{ trans('global.request.fields.urgent') }} <span>{{ trans('global.request.fields.asap') }}</span>
				
				@if($errors->has('priority'))
                    <em class="invalid-feedback">
                        {{ $errors->first('priority') }}
                    </em>
                @endif
               
            </div>
			<?php  $data_archive = explode('|',$request->data_archive); 
			?>
			<div class="form-group {{ $errors->has('data_archive') ? 'has-error' : '' }}">
                <label for="data_archive">{{ trans('global.request.fields.data_archive') }}</label>
                <input type="checkbox"  name="data_archive[]" class="form-control" value="screenshot"
				 @if(in_array('screenshot',$data_archive)) checked=checked  @endif >{{ trans('global.request.fields.screenshot') }} {{$request->data_archive}}
                
				<input type="checkbox"  name="data_archive[]" class="form-control" value="fullhtml"
				@if(in_array('fullhtml',$data_archive)) checked=checked  @endif >{{ trans('global.request.fields.fullpagehtml') }}
				
				@if($errors->has('data_archive'))
                    <em class="invalid-feedback">
                        {{ $errors->first('data_archive') }}
                    </em>
                @endif
               
            </div>
			
			
			
			
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection