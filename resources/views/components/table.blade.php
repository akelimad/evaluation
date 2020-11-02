<style>
  #table-overlay {
    position: absolute;
    width: 100%;
    top: 0;
    bottom: 0;
    left: 0;
    background-color: rgb(173 173 173 / 50%);
    text-align: center;
    z-index: 999999999;
  }
  #table-overlay i {
    position: absolute;
    top: 50%;
    left: 50%;
  }
</style>
<div class="table-container">
  <div id="table-overlay" style="display: none"><i class="fa fa-refresh fa-3x fa-spin"></i></div>
  <form method="POST" action="">
    @if ($items->count() > 0)
      <div class="">
        <div class="table-responsive">
          @php($callableActions = [])
          <table class="table table-hover table-sm table-striped table-inversed-blue" data-count="{{ $items->count() }}">
            <thead>
            <tr>
              @php($sortables = $table->getSortables())
              @if($isBulkActions && $hasBulkActions)
                <th width="10" class="checkAll">
                  <div class="custom-control custom-checkbox p-0">
                    <input type="checkbox" id="hunter_checkAll" class="custom-control-input hunter_checkAll m-auto">
                    <label class="custom-control-label d-none" for="hunter_checkAll">&nbsp;</label>
                  </div>
                </th>
              @endif
              @foreach($table->getColumns() as $column)
                @if (isset($sortables[$column['name']]))
                  @php($orderby = $sortables[$column['name']])
                  <th {!! chm_table_column_attrs($column) !!}>
                <span chm-table-sort="{{ $sortables[$column->name] }}">
                  @if(request()->query->get('orderby') == $orderby)<i class="fa fa-sort-amount-{{ request()->query->get('order') }}" style="font-size: 14px;"></i>&nbsp;
                  @endif
                  {{ __($column['label']) }}
                </span>
                  </th>
                @else
                  <th {!! chm_table_column_attrs($column) !!}>{{ __($column['label']) }}</th>
                @endif
              @endforeach
              @if (count($table->getActions()) > 0)
                <th class="actions text-center" style="width: 51px">{{ "Actions" }}</th>
              @endif
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
              <tr data-pkv="{{ $item->id }}">
                @if($isBulkActions and $hasBulkActions)
                  <td class="hunter_cb_td">
                    <div class="custom-control custom-checkbox p-0" style="height: 16px;">
                      <input type="checkbox" name="{{ $tableId }}_items[]" value="{{ $item->id }}" id="check-item-{{ $item->id }}" class="custom-control-input hunter_cb m-0">
                      <label class="custom-control-label d-inline-block m-0" for="check-item-{{ $item->id }}">&nbsp;</label>
                    </div>
                  </td>
                @endif

                @foreach($table->getColumns() as $column)
                  <td {!! chm_table_column_attrs($column) !!}>{!! !is_null($column['callback']) ? chm_table_exec($column['callback'], $item) : chm_table_attribute($column['name'], $item, $table) !!}</td>
                @endforeach

                @if (count($table->getActions()) > 0)
                  <td class="actions text-center">
                    <div class="btn-group">
                      <button aria-expanded="false" aria-haspopup="true" class="btn btn-info btn-sm py-1 dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        @foreach($table->getActions() as $key => $action)
                          @if (chm_table_exec($action['display'], $item))
                            @if($action['bulk_action'])
                              @php($callableActions = array_merge($callableActions, [$action['name'] => $action]))
                            @endif
                            @if($action['type'] == 'divider')
                              <li class="dropdown-divider"></li>
                            @else
                              <li>
                                <a href="{{ chm_table_action_url($action, $item) }}"
                                    {!! chm_table_action_attrs($action, $item, $table) !!}
                                ><i class="{{ chm_table_exec($action['icon'], $item) }}"></i>&nbsp;{{ chm_table_exec($action['label'], $item) }}</a>
                              </li>
                            @endif
                          @endif
                        @endforeach
                      </ul>
                    </div>
                  </td>
                @endif
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row mb-0">
        <div class="col-md-7 pt-10 mb-sm-20" id="bulk-wrap">
          @if($isBulkActions && $hasBulkActions && count($callableActions) > 0)
            <select name="" id="table-bulk-action-select" class="d-inline-block">
              <option value="">{{ "Actions groupées" }}</option>
              @foreach($callableActions as $action)
                <option value="{{ $action['name'] }}" data-callback="{{ $action['callback'] }}" data-params="{{ json_encode($action['callback_params']) }}">{{ $action['label'] }}</option>
              @endforeach
            </select>
            <button type="submit" class="btn btn-info btn-xs d-inline-block" style="padding: 0px 8px;border: 0px; margin: 0 5px 4px; border-radius: 0; min-height: 24px;">{{ "Appliquer" }}</button>
          @endif
          <select class="chmTable_perpage d-inline-block">
            @foreach($table->getPerpages() as $perpage)
              <option value="{{ $perpage }}" {{ $perpage == $items->perPage() ? 'selected' : '' }}>{{ $perpage }}</option>
            @endforeach
          </select>

          @php($curpage = $items->currentPage())
          @php($perpage = $items->perPage())
          @php($from = ($perpage * $curpage) - ($perpage - 1))
          @php($total = $items->total())
          @php($to = $perpage * $curpage)
          @php($to = ($to < $total) ? $to : $total)

          {{ __("Affichage de l'élément :from à :to sur :total éléments", ['from' => $from, 'to' => $to, 'total' => $total]) }}
        </div>
        <div class="col-md-5">
          <div class="navigation pull-md-right pull-sm-right">
            {{ $items->links() }}
          </div>
        </div>
      </div>
    @else
      @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé"])
    @endif
  </form>
</div>
