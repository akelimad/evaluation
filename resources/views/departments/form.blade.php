<div class="content departmentsForm commentsForm">
  <input type="hidden" name="id" value="{{ $department->id }}">
  {{ csrf_field() }}
  <div id="addLine-wrap">
    <table class="table mb-10" id="departmentsTable" data-count="0">
      <tbody>
        <tr>
          <td>
            <label class="control-label">Département : <span class="badge"> </span></label>
          </td>
          <td>
            <input type="text" name="departments[]" class="form-control" value="{{ isset($department->title)
             ? $department->title : '' }}" required>
          </td>
          @if (!isset($department->id))
          <td>
            <label class="control-label">&nbsp;</label>
            <button type="button" class="btn btn-success addLine pull-right dept-duplicate-btn" chm-duplicate><i class="fa fa-plus"></i></button>
          </td>
          @endif
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
  $(document).ready(function () {

  })
</script>