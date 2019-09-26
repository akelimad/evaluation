import $ from 'jquery'

$(document).ready(function () {
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

