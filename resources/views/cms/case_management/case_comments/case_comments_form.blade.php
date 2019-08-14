
                        {{ csrf_field() }}
                        <input type="hidden" name="case_id" value="{{$inputs['id']}}" >


                        <div class="form-group col-md-8{{ $errors->has('comment') ? ' has-error' : '' }}">
                            <div class="col-md-8">
                                {!! Form::label('comment', 'Comments:') !!}
                                {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}

                                @if ($errors->has('comment'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-md-8{{ $errors->has('case_status') ? ' has-error' : '' }}">
                            <div class="col-md-8">
                                {!! Form::label('case_status', 'Case Status:') !!}
                                {!! Form::select('case_status', ['open'=>"Open",'close'=>"Close"], null, ['class' => 'form-control']) !!}

                                @if ($errors->has('case_status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('case_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group col-md-6">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    Add Comments
                                </button>
                            </div>
                        </div>

