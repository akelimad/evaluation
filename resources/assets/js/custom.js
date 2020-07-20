import $ from 'jquery'

$(document).ready(function () {
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
})
