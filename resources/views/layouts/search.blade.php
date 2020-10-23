@php($is_visible = isset($_COOKIE['chm_filter']) && $_COOKIE['chm_filter'] == 1)

<div class="search-container mb-10" chm-search chm-search-table="#{{ $tableId }}">
  <div class="box-header mb-0 p-0">
    <h4 class="help-block showFormBtn m-0 chm-title {{ $is_visible ? "uncollapsed":"collapsed" }}" data-toggle="collapse" data-target="#{{ $tableId }}Collapse" onclick="return chmFilter.collapse(this)"><span class="fa fa-search"></span> Rechercher <button class="btn btn-info btn-sm pull-right pt-0 pb-0"><i class="fa fa-chevron-down"></i></button>
    </h4>
  </div>
  <div class="box-body filter-box p-0 chm-search-form collapse {{ $is_visible ? "in":"" }}" id="{{ $tableId }}Collapse">
    <form class="mb-0 pl0 bg-white p-15">
      <div class="row">
        @yield('fields')
      </div>
      <div class="row form_buttons mb-0">
        <div class="col-md-12">
          <a href="{{ $resetUrl }}" class="btn btn-danger btn-sm mr-5"><i class="fa fa-refresh"></i> {{ "Réinitialiser" }}</a>
          <button class="btn btn-primary btn-sm pull-right" type="submit"><i class="fa fa-search"></i> {{ "Rechercher" }}</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $('.showFormBtn').on('click', function () {
    $('.filter-box').slideToggle()
  })
</script>