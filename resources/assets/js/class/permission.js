import $ from 'jquery'

export default class chmPermission {

  static delete(event, ids) {
    ids = (typeof event[0] !== 'undefined') ? event : ids
    window.chmModal.show({
      type: 'DELETE',
      url: '/permission/delete',
      data: {
        "ids": ids,
        "_method": 'DELETE',
        "_token": $('input[name="_token"]').val(),
      }
    }, {
      message: '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + trans("Suppression en cours..."),
      onSuccess: (response) => {
        f ('status' in response && response.status === 'alert') {
          window.chmTable.refresh('#PermissionsTableContainer')
        }
      }
    })
  }

}
