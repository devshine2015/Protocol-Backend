<div class="card-body form-type-material">
  <div class="row">
  <div class="clearfix"></div>
    <div class="col-md-12">
      <div class="form-group">
            {!! Form::label('active_name','Active Name', array('class'=>'require' ))!!}
            {!! Form::text('active_name', null,array('class'=>'form-control','placeholder' => 'Enter active_name','required' => 'required','id'=>"active_name")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
            {!! Form::label('passive_name','Passive Name', array('class'=>'require' ))!!}
            {!! Form::text('passive_name', null,array('class'=>'form-control','placeholder' => 'Enter Passive name','required' => 'required','id'=>"passive_name")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <?php $user = null; if (isset($relation->user)) { $user = $relation->user->name; } ?>
            {!! Form::label('Created By','Created By', array('class'=>'require' ))!!}
            {!! Form::text('created', $user,array('class'=>'form-control','readonly' => 'readonly','placeholder' => 'Created by admin','required' => 'required','id'=>"created_by")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <?php $reltionStatus = ($relation->is_active == 1)? "Active":"Not Active"; ?>
            {!! Form::label('Active Status','Active Status', array('class'=>'require' ))!!}
            {!! Form::text('is_activeStatus', $reltionStatus,array('class'=>'form-control','readonly' => 'readonly','required' => 'required','id'=>"is_active")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group messageCriteria">
        {!! Form::label('Status','Status',array('class'=>'require apporve'))!!}
        <?php $status = ''; if($relation->is_approved == 1) $status = 'checked';?>
        <input type = "checkbox" class="my-checkbox" name="status" data-toggle="toggle" id= "is_approved"  data-on="Approved" data-off = "Not Approved" {{$status}}  value = "{{$status}}"  data-onstyle="success" data-offstyle="danger" data-size="normal">
      </div>
    </div>
  </div>
</div>