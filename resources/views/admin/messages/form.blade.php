<div class="card-body form-type-material">
  <div class="row">
   <div class="col-md-6">
      <div class="form-group availibility">
        {!! Form::label('Message Category','Message Category',array('class'=>'require'))!!}
        {!! Form::select('message_categories_id',\App\MessageCategory::pluck('title','id'),null,array('class'=>'form-control selectpicker','id'=>'message_category','required' => 'required','title'=>"Choose Category")) !!}
      </div>
    </div> 
    <div class="col-md-6">
      <div class="form-group availibility">
          {!! Form::label('Message Category','Message Category',array('class'=>'require'))!!}
          {!! Form::select('message_type',array('0' => 'alpha', '1' => 'achievement'),null,array('class'=>'form-control selectpicker','data-actions-box'=>"true",'id'=>'message_type','required' => 'required','title'=>"Choose Type")) !!}
        </div>
      </div>
    <div class="col-md-6">
      <div class="form-group">
            {!! Form::label('Start Date','Start Date', array('class'=>'require' ))!!}
            {!! Form::text('start_date', null,array('class'=>'form-control datepicker','placeholder' => 'Select start date','data-provide'=>"datepicker",'autoclose'=>"true",'data-date-format'=>"mm/dd/yyyy",'readonly' => 'readonly','required' => 'required','id'=>"start_date")) !!}
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
            {!! Form::label('End Date','End Date', array('class'=>'require' ))!!}
            {!! Form::text('end_date', null,array('class'=>'form-control datepicker','placeholder' => 'Select end date','data-provide'=>"datepicker",'autoclose'=>"true",'data-date-format'=>"mm/dd/yyyy",'readonly' => 'readonly','required' => 'required','id'=>"end_date")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        {!! Form::label('Message','Message')!!}
        {!! Form::textarea('message', null,array('class'=>'form-control textarea','rows'=>6,'maxlength'=>"620",'id'=>"textMessage")) !!}
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group availibility">
        {!! Form::label('Criteria Category','Criteria Category',array('class'=>'require'))!!}
        {!! Form::select('message_criteria_id',\App\MessageCriteria::pluck('criteria','id'),null,array('class'=>'form-control selectpicker','data-actions-box'=>"true",'id'=>'message_category','title'=>"Choose Criteria")) !!}
      </div>
    </div> 
    <div class="col-md-6">
      <div class="form-group">
        {!! Form::label('Criteria','Criteria',array('class'=>'require'))!!}
        {!! Form::text('criteria', null,array('class'=>'form-control','id'=>"criteria")) !!}
      </div>
    </div>
  </div>
</div>