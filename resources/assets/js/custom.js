import $ from 'jquery'

$(window).on('load', function() {
  jQuery('.spinner-wp').remove()
});

$(document).ready(function () {
  // Trigger bulk actions
  $('body').on('click', '#bulk-wrap [type="submit"]', function (event) {
    var $tableContainer = $(this).closest('[chm-table]')
    var selectedBulk = $($tableContainer).find('#bulk-wrap select').val()
    var bulkCallable = $($tableContainer).find('#bulk-wrap select option:selected').data('callback')
    var callbackParams = $($tableContainer).find('#bulk-wrap select option:selected').data('params') || null
    var countChecked = $($tableContainer).find('.hunter_cb:checked').length
    if (countChecked === 0) {
      window.chmAlert.error('Vous devez choisir au moins une ligne.')
      event.preventDefault()
      return
    } else if (selectedBulk === '') {
      event.preventDefault()
      window.chmAlert.error('Merci de choisir une action.')
      return
    } else if (bulkCallable !== undefined) {
      event.preventDefault()
      var checked = []
      $($tableContainer).find('.hunter_cb:checked').each(function (k, v) {
        checked[k] = $(this).val()
      })
      var parts = bulkCallable.split('.')
      if (parts.length === 2) {
        if (callbackParams !== null) {
          window[parts[0]][parts[1]](event, checked, callbackParams)
        } else {
          window[parts[0]][parts[1]](event, checked)
        }
      } else {
        if (callbackParams !== null) {
          window[parts[0]](event, checked, callbackParams)
        } else {
          window[parts[0]](event, checked)
        }
      }
    }
  })

  $('body').on('change', '.hunter_cb', function () {
    let $table = $(this).closest('table')
    let checked = $($table).find('.hunter_cb').length === $($table).find('.hunter_cb:checked').length
    $($table).find('.hunter_checkAll').prop('checked', checked)
  })

  // Check all
  $('body').on('change', '.hunter_checkAll', function () {
    let $table = $(this).closest('table')
    let checked = $(this).is(':checked')
    $($table).find('.hunter_checkAll').not($(this)).prop('checked', checked)
    $($table).find(".hunter_cb:not(:disabled)").prop("checked", checked)
  })

  // Delete table chechAll & row checkbox if there's no bulk action
  $('body').on('chmTableSuccess', function () {
    if ($('#bulk-wrap select').length == 0) {
      $('.checkAll').remove()
      $('.hunter_cb_td').remove()
    }
  })

  // Modal close event
  $('body').on('hidden.bs.modal', '.chm-modal', function () {
    if ($(this).attr('chm-modal-action') === 'reload') {
      window.location.reload()
    } else {
      $(this).data('bs.modal', null)
      $(this).remove()
    }
  })

  // Prevent default on click event
  $('a[onclick]').on('click', function (e) {
    return e.preventDefault()
  })

  // Initialise filter form
  window.chmFilter.init()

  $('body').on('click', '.select2 option[value="all"]', function () {
    var $select = $(this).closest('select')
    $($select).find('option').not($(this)).prop('selected', $(this).is(':checked'))
    $($select).trigger('change')
  })

  // Add new Line with table
  $('body').on('click', '.addLine', function (event) {
    event.preventDefault()

    $(this).addClass('target')
    let $copy = $(this).closest('tr').clone()

    $(this).removeClass('target')
    $(this).toggleClass('addLine deleteLine')
    $(this).toggleClass('btn-success btn-danger')
    $(this).find('i').toggleClass('fa-plus fa-minus')

    $copy.find('input').not("input[type='hidden']").val('')

    $(this).closest('tbody').append($copy)

    $($copy).find('[chm-duplicate].target').removeClass('target').trigger('chmLineAdded')
  })

  // Delete added Line
  $('body').on('click', '.deleteLine', function () {
    let $table = $(this).closest('tr')
    $(this).closest('tr').remove()
    $table.trigger('chmLineDeleted')
  })

  $('body').on('chmFormSuccess', function (event, response) {
    if (response.status == 'reload') {
      window.location.reload()
    }
  })

  $('[data-toggle="tooltip"]').tooltip()

  var baseUrl =  $("base").attr("href")

  var max_note = parseInt($('#max_note').text())

  // to show the choosen filename in input like: avatar.png
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });
  $(document).ready( function() {
    $(':file').on('fileselect', function(event, numFiles, label) {
      var input = $(this).parents('.input-group').find(':text'),
          log = numFiles > 1 ? numFiles + ' files selected' : label;
      if( input.length ) {
        input.val(log);
      }
    });
  });

  $(document).on('show','.accordion', function (e) {
    //$('.accordion-heading i').toggleClass(' ');
    $(e.target).prev('.accordion-heading').addClass('accordion-opened');
  });

  $(document).on('hide','.accordion', function (e) {
    $(this).find('.accordion-heading').not($(e.target)).removeClass('accordion-opened');
    //$('.accordion-heading i').toggleClass('fa-chevron-right fa-chevron-down');
  });

})

