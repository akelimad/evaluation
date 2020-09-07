<?php
namespace App\Http\Service;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Table {

  private $request;

  private $primaryKey;

  private $date_format = 'd/m/Y';

  private $page;

  private $perpage;

  private $perpages = [5, 10, 15, 20, 50, 100];

  private $orderby;

  private $order = 'ASC';

  private $columns = [];

  private $actions = [];

  private $sortables = [];

  private $bulk_actions = false;

  private $tableId = 'hunterTable';

  private $trans;


  public function __construct(Request $request)
  {
    $this->request = $request;
    $perpage = $this->request->query->get('perpage', 10);
    $this->page = $this->request->query->getInt('page', 1);
    $this->perpage = $this->request->query->getInt('perpage', $perpage);
    if ($orderby = $this->request->query->get('orderby', false)) {
      $this->orderby = $orderby;
    }
    if ($order = $this->request->query->get('order', false)) {
      $this->order = $order;
    }
  }

  public function render($query)
  {
    if (!empty($this->orderby) && in_array($this->orderby, $this->sortables)) {
      $query->orderBy($this->orderby, $this->order);
    }

    $items = $query->paginate($this->perpage);

    $output = view('components/table', [
      'table'    => $this,
      'items'    => $items,
      'isBulkActions' => $this->isBulkActions(),
      'hasBulkActions' => $this->hasBulkActions(),
      'tableId' => $this->getTableId()
    ])->render();

    $user = Auth::user();
    if (!$user) {
      return new Response("<div class='alert alert-warning'>". $this->trans->trans("Session expirée, veuillez vous reconnecter !") ."</div>");
    }
    return new Response($output);
  }

  public function setPrimaryKey($primaryKey)
  {
    $this->setPrimaryKey = $primaryKey;

    return $this;
  }

  public function getPrimaryKey()
  {
    if (!empty($this->setPrimaryKey)) {
      return $this->setPrimaryKey;
    }
    $column = reset($this->columns);
    if (isset($column['name'])) {
      return $column['name'];
    }
    return '';
  }

  public function addColumn($name, $label, $callback = null, $attr = null)
  {
    $this->columns[$name] = [
      'name' => $name,
      'label' => $label,
      'callback' => $callback,
      'attr' => $attr
    ];
  }

  public function addAction($name, $args = [])
  {
    if ($name == 'delete') {
      if (!isset($args['attrs']['class'])) {
        $args['attrs']['class'] = 'delete';
      }
      if (!isset($args['attrs']['onclick'])) {
        $args['attrs']['onclick'] = "chmModal.confirm(this, '', 'trans[Are you sure you want to delete this item?]'); return false;";
      }
    }

    $this->actions[$name] = array_replace_recursive([
      'name' => $name,
      'type' => $name,
      'label' => 'No name',
      'callback' => null,
      'callback_params' => [],
      'icon' => 'fa fa-circle',
      'route' => '#',
      'attrs' => [],
      'display' => true,
      'bulk_action' => true,
      'order' => count($this->actions) + 1
    ], $args);

    return $this;
  }

  public function addDivider($args = [])
  {
    $args['type'] = 'divider';
    return $this->addAction('divider_' . count($this->actions), $args);
  }

  public function setPage($page)
  {
    if (is_numeric($page)) {
      $this->page = $page;
    }
    return $this;
  }

  public function setPerpage($perpage)
  {
    if (is_numeric($perpage)) {
      $this->perpage = $perpage;
    }
    return $this;
  }

  public function getSortables()
  {
    return $this->sortables;
  }

  public function setSortables($sortables)
  {
    if (is_array($sortables)) {
      $this->sortables = $sortables;
    }
    return $this;
  }

  public function getDateFormat()
  {
    return $this->date_format;
  }

  public function setDateFormat($date_format)
  {
    $this->date_format = $date_format;

    return $this;
  }

  public function getColumns()
  {
    return $this->columns;
  }

  public function getActions()
  {
    return $this->actions;
  }

  public function getPerpages()
  {
    return $this->perpages;
  }

  /**
   * @return boolean
   */
  public function isBulkActions()
  {
    return $this->bulk_actions;
  }

  /**
   * @param boolean $bulk_actions
   */
  public function setBulkActions($bulk_actions)
  {
    $this->bulk_actions = $bulk_actions;
  }

  /**
   * @return string
   */
  public function getTableId()
  {
    return $this->tableId;
  }

  /**
   * @param string $tableId
   */
  public function setTableId($tableId)
  {
    $this->tableId = $tableId;
  }

  /**
   * Remove array of actions
   *
   * @param array names
   *
   * @return void
   */
  public function removeActions($names = [])
  {
    if( empty($names) ) return;

    foreach ($names as $key => $name) {
      unset($this->actions[$name]);
    }
  }

  /**
   * Tell if has Bulk Actions
   *
   * @return int $page
   */
  public function hasBulkActions()
  {
    foreach ($this->actions as $key => $action) {
      if($action['bulk_action']) return true;
    }
    return false;
  }


} // END Class