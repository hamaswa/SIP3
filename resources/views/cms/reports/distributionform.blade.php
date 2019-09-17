@extends('layouts.app')
@section('content-header')
    <ol class="breadcrumb">
        <li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
        <li class="active">Distribution report</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">

            <form method="post" action="" class="form-horizontal">
                <fieldset style="border:0;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group{{ $errors->has('queue') ? ' has-error' : '' }}">

                                <div class="col-md-6">

                                    @if ($errors->has('queue'))
                                        <span class="help-block">
                                        <strong>Please Select Queue</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <h2>
                                Select Queues
                            </h2>
                            <br>
                            <table style="padding:8px;" class="col-lg-12 col-md-12">
                                <tbody>
                                <tr>


                                    <td  style="width:45%">



                                            <select name="List_Queue_available" multiple="multiple" size="9"
                                                    id="myform_List_Queue_from" class="col-md-3 form-control">

                                            </select>



                                    </td>
                                    <td style="text-align:center;font-weight: bold;font-size:30px;width:10%">
                                        <a  id="queueAllRight" class="nav nav-item"> >> </a>
                                        <a  id="queueRight" class="nav nav-item"> > </a>
                                        <a  id="queueLeft" class="nav nav-item"> < </a>
                                        <a  id="queueAllLeft" class="nav nav-item"> << </a>


                                    </td>
                                    <td style="width:45%">
                                        Selected<br>


                                        <div id="queueselection">

                                            {!! Form::select('queue[]', $queue, $queue_sel, array('class'=>'form-control col-md-3',  'size'=>'9','multiple' => 'multiple','id'=>'myform_List_Queue_to')); !!}

                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group{{ $errors->has('extension') ? ' has-error' : '' }}">

                                <div class="col-md-6">

                                    @if ($errors->has('extension'))
                                        <span class="help-block">
                                        <strong>Please Select Extension</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <h2>
                                Select Agents</h2>
                            <br>
                            <table style="padding:8px;" class="col-lg-12 col-md-12">
                                <tbody>
                                <tr>
                                    <td style="width:45%">
                                        Available<br>
                                        <select name="List_Agent_available" multiple="multiple" size="9"
                                                id="myform_List_Agent_from" class="col-md-12 col-lg-12 form-control">
                                        </select>
                                    </td>
                                    <td style="text-align:center;font-weight:bold;font-size:30px;width:10%">
                                        <br/>
                                        <a  id="agentAllRight" class="nav nav-item"> >> </a>
                                        <a  id="agentRight" class="nav nav-item"> > </a>
                                        <a  id="agentLeft" class="nav nav-item"> < </a>
                                        <a  id="agentAllLeft" class="nav nav-item"> << </a>


                                    </td>
                                    <td  style="width:45%">
                                        Selected<br>
                                        {!! Form::select('agents[]', $extension, $extension_sel, array('class'=>'form-control col-md-12 col-lg-12', 'size' => "9",'multiple' => 'multiple','id'=>'myform_List_Agent_to')); !!}

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">

                            <h2>Select Date Range</h2>
                            <input type="text" name="daterange"
                                   class="form-control col-lg-3" autocomplete="on" id="daterange">


                        </div>
                        <div class="col-md-6">

                            <h2>Select Time Frame</h2>
                            <div class="h5">
                                From
                            </div>

                            <div class="form-group">
                                <div class="control">
                                    <div class="col-md-2">
                                        <select name="hour1" id="hour1" size="1" class="input-small form-control">
                                            <option value="00" selected="selected">00</option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                        </select>

                                    </div>
                                    <div class="col-md-2">
                                        <select name="minute1" id="minute1" size="1" class="input-small form-control">
                                            <option value="00" selected="selected">00</option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                            <option value="32">32</option>
                                            <option value="33">33</option>
                                            <option value="34">34</option>
                                            <option value="35">35</option>
                                            <option value="36">36</option>
                                            <option value="37">37</option>
                                            <option value="38">38</option>
                                            <option value="39">39</option>
                                            <option value="40">40</option>
                                            <option value="41">41</option>
                                            <option value="42">42</option>
                                            <option value="43">43</option>
                                            <option value="44">44</option>
                                            <option value="45">45</option>
                                            <option value="46">46</option>
                                            <option value="47">47</option>
                                            <option value="48">48</option>
                                            <option value="49">49</option>
                                            <option value="50">50</option>
                                            <option value="51">51</option>
                                            <option value="52">52</option>
                                            <option value="53">53</option>
                                            <option value="54">54</option>
                                            <option value="55">55</option>
                                            <option value="56">56</option>
                                            <option value="57">57</option>
                                            <option value="58">58</option>
                                            <option value="59">59</option>
                                        </select>
                                    </div>
                                    hours
                                </div>
                            </div>

                            <div class="h5">
                                To
                            </div>
                            <div class="form-group">
                                <div class="control">
                                    <div class="col-md-2">
                                        <select name="hour2" id="hour2" size="1" class="input-small form-control">
                                            <option value="00">00</option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23" selected="selected">23</option>
                                        </select>

                                    </div>
                                    <div class="col-md-2">
                                        <select name="minute2" id="minute2" size="1" class="input-small form-control">
                                            <option value="00">00</option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                            <option value="32">32</option>
                                            <option value="33">33</option>
                                            <option value="34">34</option>
                                            <option value="35">35</option>
                                            <option value="36">36</option>
                                            <option value="37">37</option>
                                            <option value="38">38</option>
                                            <option value="39">39</option>
                                            <option value="40">40</option>
                                            <option value="41">41</option>
                                            <option value="42">42</option>
                                            <option value="43">43</option>
                                            <option value="44">44</option>
                                            <option value="45">45</option>
                                            <option value="46">46</option>
                                            <option value="47">47</option>
                                            <option value="48">48</option>
                                            <option value="49">49</option>
                                            <option value="50">50</option>
                                            <option value="51">51</option>
                                            <option value="52">52</option>
                                            <option value="53">53</option>
                                            <option value="54">54</option>
                                            <option value="55">55</option>
                                            <option value="56">56</option>
                                            <option value="57">57</option>
                                            <option value="58">58</option>
                                            <option value="59" selected="selected">59</option>
                                        </select>
                                    </div>
                                    hours
                                </div>
                            </div>

                        </div>
                    </div>
                    <input type="submit" id="showReport" class="btn btn-primary" value="Display Report">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </fieldset>
            </form>

        </div>
    </div>
    <div style="height:100px;">&nbsp;</div>

@endsection
@push('scripts')
    <script>

        (function () {
            $('#daterange').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY',
                },
               // format: 'MM-DD-YYYY',
                ranges   : {
                    'Today'       : [moment(), moment()],
                    'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                    'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year'  : [moment().startOf('year'), moment()]
                }
            });

            function moveItems(origin, dest) {
                $(origin).find(':selected').appendTo(dest);
            }

            function moveAllItems(origin, dest) {
                console.log($(origin).val())
                $(origin).children().appendTo(dest);
                    selectedValues=[];
                    $(dest).find("option").each(function() {
                        $(this).attr('selected',true);
                        selectedValues.push($(this).val());
                    });
                    $(dest).val(selectedValues);
             }

            $('#queueRight').click(function (e) {
                moveItems('#myform_List_Queue_from','#myform_List_Queue_to');
            })
            $('#queueAllRight').click(function (e) {
                moveAllItems('#myform_List_Queue_from','#myform_List_Queue_to');
            });
            $('#queueLeft').click(function (e) {
                moveItems('#myform_List_Queue_to','#myform_List_Queue_from');
            });
            $('#queueAllLeft').click(function (e) {
               moveAllItems('#myform_List_Queue_to','#myform_List_Queue_from');
            });

            $('#myform_List_Queue_from').dblclick(function (e) {
                moveItems('#myform_List_Queue_from','#myform_List_Queue_to');
            });
            $('#myform_List_Queue_to').dblclick(function (e) {
                moveItems('#myform_List_Queue_to','#myform_List_Queue_from');
            });

            $('#agentRight').click(function (e) {
                moveItems('#myform_List_Agent_from','#myform_List_Agent_to');
            });
            $('#agentAllRight').click(function (e) {
                moveAllItems('#myform_List_Agent_from','#myform_List_Agent_to');
            });
            $('#agentLeft').click(function (e) {
                moveItems('#myform_List_Agent_to','#myform_List_Agent_from');
            });
            $('#agentAllLeft').click(function (e) {
                moveAllItems('#myform_List_Agent_to','#myform_List_Agent_from');
            });
            $('#myform_List_Agent_from').dblclick(function (e) {
                moveItems('#myform_List_Agent_from','#myform_List_Agent_to');
            });
            $('#myform_List_Agent_to').dblclick(function (e) {
                moveItems('#myform_List_Agent_to','#myform_List_Agent_from');
            });
        }(jQuery));

    </script>
@endpush
