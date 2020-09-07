<form method="POST" action="" id="hunter-table-wraper">
  @if ($items->count() > 0)
    @php($callableActions = [])
    <table class="table table-hover table-sm table-striped table-nowrap table-inversed-blue" data-count="{{ $items->count() }}">
      <thead>
      <tr>
        @php($sortables = $table->getSortables())
        @if($isBulkActions && $hasBulkActions)
          <th width="10" class="checkAll">
            <div class="custom-control custom-checkbox p-0">
              <input type="checkbox" id="hunter_checkAll" class="custom-control-input hunter_checkAll">
              <label class="custom-control-label" for="hunter_checkAll">&nbsp;</label>
            </div>
          </th>
        @endif
        @foreach($table->getColumns() as $column)
          @if (isset($sortables[$column['name']]))
            @php($orderby = $sortables[$column['name']])
            <th {{ chm_table_column_attrs($column) }}>
                <span chm-table-sort="{{ $sortables[$column->name] }}">
                  @if(request()->query->get('orderby') == $orderby)<i class="fa fa-sort-amount-{{ request()->query->get('order') }}" style="font-size: 14px;"></i>&nbsp;
                  @endif
                  {{ $column['label'] }}
                </span>
            </th>
          @else
            <th {{ chm_table_column_attrs($column) }}>{{ $column['label'] }}</th>
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
              <div class="custom-control custom-checkbox p-0">
                <input type="checkbox" name="{{ tableId }}_items[]" value="{{ $item->id }}" id="check-item-{{ $item->id }}" class="custom-control-input hunter_cb">
                <label class="custom-control-label" for="check-item-{{ $item->id }}">&nbsp;</label>
              </div>
            </td>
          @endif

          @foreach($table->getColumns() as $column)
            <td {{ chm_table_column_attrs($column) }}>{{ !is_null($column['callback']) ? chm_table_exec($column['callback'], $item) : chm_table_attribute($column['name'], $item, $table) }}</td>
          @endforeach

          @if (count($table->getActions()) > 0)
            <td class="actions text-center">
              <div class="btn-group">
                <button aria-expanded="false" aria-haspopup="true" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                <ul class="dropdown-menu dropdown-menu-right">
                  @foreach($table->getActions() as $key => $action)
                    @if(chm_table_exec($action['display'], $item))
                      @if(isset($callableActions['key']) and $action['bulk_action'])
                        @php($callableActions = [])
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
  @else
    @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé"])
  @endif
</form>