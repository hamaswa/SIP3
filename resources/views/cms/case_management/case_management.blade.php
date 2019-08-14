
                        {{ csrf_field() }}
                        <div class="form-group col-md-6{{ $errors->has('subject') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('subject', 'Subject:') !!}
                                {!! Form::text('subject', null, ['class' => 'form-control', 'required']) !!}

                                @if ($errors->has('subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-md-6{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('name', 'Customer Name:') !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('taxi_no') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('taxi_no', 'Taxi Number:') !!}
                                {!! Form::text('taxi_no', null, ['class' => 'form-control', 'required']) !!}

                                @if ($errors->has('taxi_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('taxi_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('contact_no') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('contact_no', 'Contact Number:') !!}
                                {!! Form::text('contact_no', null, ['class' => 'form-control', 'required']) !!}

                                @if ($errors->has('contact_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('incident_location') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('incident_location', 'Incident Location:') !!}
                                {!! Form::text('incident_location', null, ['class' => 'form-control', 'required']) !!}

                                @if ($errors->has('incident_location'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('incident_location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('pickup_point_a') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('pickup_point_a', 'Pickup Point A:') !!}
                                {!! Form::text('pickup_point_a', null, ['class' => 'form-control', 'required']) !!}

                                @if ($errors->has('pickup_point_a'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_point_a') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('pickup_point_b') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('pickup_point_b', 'Pickup Point B:') !!}
                                {!! Form::text('pickup_point_b', null, ['class' => 'form-control', 'required']) !!}

                                @if ($errors->has('pickup_point_b'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pickup_point_b') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('case_type') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('case_type', 'Case Type:') !!}
                                {!! Form::select('case_type', ['option1'=>"option1",'option2'=>"option2"], null, ['class' => 'form-control']) !!}

                                @if ($errors->has('case_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('case_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('case_status') ? ' has-error' : '' }}">
                            <div class="col-md-10">
                                {!! Form::label('case_status', 'Case Status:') !!}
                                {!! Form::select('case_status', ['option1'=>"option1",'option2'=>"option2"], null, ['class' => 'form-control']) !!}

                                @if ($errors->has('case_status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('case_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group col-md-12{{ $errors->has('comments') ? ' has-error' : '' }}">
                            <div class="col-md-8 col-md-offset-2">
                                {!! Form::label('comments', 'Comments:') !!}
                                {!! Form::textarea('comments', null, ['class' => 'form-control']) !!}

                                @if ($errors->has('comments'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('comments') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



