<div class="card-body form-type-material">
  <div class="row">
   <div class="col-md-12">
      <div class="form-group">
           <?php $language = null; if (isset($message)) { $language = ($message->language_type ==1)?$language = 1 : $language = 2; }?>
          {!! Form::label('Select Language','Select Language',array('class'=>'require'))!!}
          {!! Form::select('language_type',array('1' => 'English', '2' => 'Chinese'),$language,array('class'=>'form-control selectpicker languageType','data-actions-box'=>"true",'id'=>'language_type','title'=>"Choose Language")) !!}
      </div>
  </div>
  <div class="clearfix"></div>
   <div class="col-md-6">
      <div class="form-group">
        {!! Form::label('Message Category','Message Category',array('class'=>'require'))!!}
        {!! Form::select('message_categories_id',\App\MessageCategory::pluck('title','id'),null,array('class'=>'form-control selectpicker messageCategory','id'=>'message_category','required' => 'required','title'=>"Choose Category")) !!}
      </div>
    </div> 
    <div class="col-md-6">
      <div class="form-group messageType">
          {!! Form::label('Message Type','Message Type',array('class'=>'require'))!!}
          {!! Form::select('message_type',array('1' => 'Alert', '2' => 'Achievement'),null,array('class'=>'form-control selectpicker messageType','data-actions-box'=>"true",'id'=>'message_type','title'=>"Choose Type")) !!}
        </div>
      </div>
    <div class="col-md-6">
      <div class="form-group">
            {!! Form::label('Start Date','Start Date', array('class'=>'require' ))!!}
            {!! Form::text('start_date', null,array('class'=>'form-control datepicker','placeholder' => 'Select start date','data-provide'=>"datepicker",'data-date-format'=>"mm/dd/yyyy",'readonly' => 'readonly','required' => 'required','id'=>"start_date")) !!}
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
            {!! Form::label('End Date','End Date', array('class'=>'require' ))!!}
            {!! Form::text('end_date', null,array('class'=>'form-control datepicker','placeholder' => 'Select end date','data-provide'=>"datepicker",'data-date-format'=>"mm/dd/yyyy",'readonly' => 'readonly','required' => 'required','id'=>"end_date")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
      <?php $messageData = null; if (isset($message)) { $messageData = ($message->type ==1)?$messageData = $message->message : $messageData = $message->chinese_message;}?>
        {!! Form::label('Message','Message')!!}
        {!! Form::textarea('message', $messageData,array('class'=>'form-control textarea summernote','rows'=>6,'maxlength'=>"620",'id'=>"textMessage",'required' => 'required')) !!}
      </div>
    </div>
    <div class="col-md-6 ">
      <div class="form-group criteriaCategory">
        {!! Form::label('Criteria Category','Criteria Category',array('class'=>'require'))!!}
        {!! Form::select('message_criteria_id',\App\MessageCriteria::pluck('criteria','id'),null,array('class'=>'form-control selectpicker criteriaCategory','data-actions-box'=>"true",'id'=>'message_criteria','title'=>"Choose Criteria")) !!}
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group messageCriteria">
        {!! Form::label('Criteria','Criteria',array('class'=>'require'))!!}
        {!! Form::text('criteria', null,array('class'=>'form-control','id'=>"criteria")) !!}
      </div>
    </div>
  </div>
</div>