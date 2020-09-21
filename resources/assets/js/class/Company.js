import $ from 'jquery'
import trans from './../script/functions'

export default class Company {

  static delete(event, ids) {
    ids = (typeof event[0] !== 'undefined') ? event : ids
    window.chmModal.show({
      type: 'DELETE',
      url: '/companies/delete',
      data: {
        "ids": ids,
        "_method": 'DELETE',
        "_token": $('input[name="_token"]').val(),
      }
    }, {
      message: '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + trans("Suppression en cours..."),
      onSuccess: (response) => {
        if ('status' in response && response.status === 'alert') {
          window.chmTable.refresh('#CompaniesTableContainer')
        }
      }
    })
  }

  static removeLogo (params) {
    let token = $('input[name="_token"]').val()
    if (window.confirm('Êtes-vous sûr ?')) {
      $.ajax({
        url: 'companies/logo/remove',
        type: 'DELETE',
        data: {'_token': token, id: params.id},
        success: function (response) {
          if (response.status == 'success') {
            $('.logo').remove()
            window.chmAlert.createAlert(response.message, response.status)
          }
        }
      })
    }
  }

}
