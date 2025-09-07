{{ Form::open(['url' => route('permission.store'), 'method' => 'post']) }}
<div class="form-group">
    <label class="form-label">Permission Title</label>
    {{ Form::text('title', old('title'), ['class' => 'form-control', 'id' => 'permission-title', 'placeholder' => 'Permission Title', 'required']) }}
</div>
<button type="submit" class="btn btn-primary">Save</button>
<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
    Cancel
</button>
{{ Form::close() }}
