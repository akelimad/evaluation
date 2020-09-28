import $ from 'jquery'
import trans from './../script/functions'

export default class Translation {

  static store(event, ids) {
    ids = (typeof event[0] !== 'undefined') ? event : ids
    var target = event.target
    var values = []
    var tr = $(target).closest('tr')
    $.each(ids, function(index, id) {
      values.push({
        locales: {
          fr: $('tr[data-pkv="'+id+'"]').find('textarea[data-locale="fr"]').val(),
          en: $('tr[data-pkv="'+id+'"]').find('textarea[data-locale="en"]').val(),
        },
        key: $('tr[data-pkv="'+id+'"]').find('textarea[data-locale="fr"]').data('key')
      })
    })

    window.chmModal.show({
      type: 'post',
      url: '/interface/translations/store',
      data: {
        "ids": ids,
        "values": values,
        "_method": 'POST',
        "_token": $('input[name="_token"]').val(),
      }
    }, {
      message: '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + trans("Enregistrement en cours ..."),
      onSuccess: (response) => {
        if ('status' in response && response.status === 'success') {
          swal({
            type: 'success',
            text: response.message
          })
        }
      }
    })
  }

}