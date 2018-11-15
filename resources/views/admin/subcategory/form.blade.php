<div class="card-body form-type-material">
  <div class="row">
  <div class="clearfix"></div>
   <div class="col-md-12">
      <div class="form-group">
        {!! Form::label('Category','Category',array('class'=>'require'))!!}
        {!! Form::select('category_id',\App\Category::pluck('name','id'),null,array('class'=>'form-control selectpicker category','id'=>'category','required' => 'required','title'=>"Choose Category")) !!}
      </div>
    </div> 
    <div class="col-md-12">
      <div class="form-group">
            {!! Form::label('Sub-category','Sub-category', array('class'=>'require' ))!!}
            {!! Form::text('name', null,array('class'=>'form-control','placeholder' => 'Enter sub-category','required' => 'required','id'=>"sub_category")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <?php $user = null; if (isset($category->user)) { $user = $category->user->name; } ?>
            {!! Form::label('Created By','Created By', array('class'=>'require' ))!!}
            {!! Form::text('created', $user,array('class'=>'form-control','readonly' => 'readonly','required' => 'required','id'=>"created_by")) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group messageCriteria">
        {!! Form::label('Status','Status',array('class'=>'require apporve'))!!}
        <?php $status = ''; if($category->is_approved == 1) $status = 'checked';?>
        <input type = "checkbox" class="my-checkbox" name="status" data-toggle="toggle" id= "is_approved"  data-on="Approved" data-off = "Not Approved" {{$status}}  value = "{{$status}}"  data-onstyle="success" data-offstyle="danger" data-size="normal">
      </div>
    </div>
  </div>
</div>