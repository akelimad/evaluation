import $ from 'jquery'

export default class Attachment {

  static getTargetParams (target, attrName) {
    var params = {}
    var value = $(target).attr(attrName)
    if (value !== undefined && value !== '') {
      try {
        params = $.parseJSON(value)
      } catch (e) {
        window.chmAlert.warning("Le format de JSON donné n'est pas correct.")
      }
    }
    return params
  }
  // TODO - allow multiple files
  static upload (target) {
    let $fileInput = $(target)
    let $form = $($fileInput).closest('form')

    // Check if file selected
    let fileData = $(target).prop('files')[0]
    if (fileData === undefined) return

    // Define variables
    let colname = $($fileInput).data('colname')
    let $colInput = $($fileInput).closest('#' + colname + 'Input')
    let $colActions = $($colInput).next('#' + colname + 'Actions')
    let $deleteBtn = $($colActions).find('button.delete')
    let params = this.getTargetParams($deleteBtn, 'target-params')

    // Add loding effect
    let $loadingBtn = $(target).closest('span.btn')

    let $btnIcon = $(target).closest('span.btn').find('i')
    $btnIcon.removeClass('fa-upload')
    $btnIcon.addClass('fa-circle-o-notch')

    // Disabled upload files
    $($form).find('.file-upload span.btn').addClass('disabled')
    $($loadingBtn).next('.stopUpload').show()
    let $submitBtn = $($form).find('button[type="submit"]').last()
    $($submitBtn).prop('disabled', true)
    $($form).find('.file-upload [type="file"]').prop('disabled', true)

    // Prepare form data
    let formData = new window.FormData()
    formData.append('file', fileData)
    formData.append('id', params.id)
    formData.append('colname', colname)
    formData.append('fname', '')
    formData.append('uploadDir', params.uploadDir)

    // Send request to server
    window.chmModal.show({
      url: 'candidat/file/upload',
      type: 'POST',
      dataType: 'text',
      cache: false,
      contentType: false,
      processData: false,
      data: formData
    }, {
      disableLoader: true,
      onSuccess: function (response) {
        // Reset loading btn to default status
        $($form).find('.file-upload span.btn').removeClass('disabled')
        $($loadingBtn).next('.stopUpload').hide()

        let $btnIcon = $(target).closest('span.btn').find('i')
        $btnIcon.removeClass('fa-circle-o-notch')
        $btnIcon.addClass('fa-upload')

        $($submitBtn).prop('disabled', false)
        $($form).find('.file-upload [type="file"]').prop('disabled', false)

        // Reset file input
        $($fileInput).val('').clone(true)
        $($fileInput).trigger('chmFileSelected', [0, null])

        // Add attribute validated to avoid validation
        $(target).attr('chm-validated', '')

        // Hide file input and show actions
        if (response.status === 'success') {
          $($colInput).hide()
          $($colActions).show()

          let fileTitle = fileData.name.replace(/\.[^/.]+$/, '')
          $($colActions).find('.file_name').val(response.data.file_name)
          $($colActions).find('.file_title').val(fileTitle)
          $($colActions).find('.file_url').attr('href', response.data.file_url)

          // Update delete button params
          params.fname = response.data.file_name
          $($deleteBtn).attr('data-fname', params.fname)
          window.chmForm.setTargetParams($deleteBtn, 'target-params', params)
        }
      },
      onError: function (response) {
        $($form).find('.file-upload span.btn').removeClass('disabled')
        $($loadingBtn).next('.stopUpload').hide()

        let $btnIcon = $(target).closest('span.btn').find('i')
        $btnIcon.removeClass('fa-circle-o-notch')
        $btnIcon.addClass('fa-upload')

        $($submitBtn).prop('disabled', false)
        $($fileInput).val('').clone(true)
        $($form).find('.file-upload [type="file"]').prop('disabled', false)
        $($fileInput).trigger('chmFileSelected', [0, null])
        window.chmModal.alert('', "Une erreur est survenue lors de l'envoi de fichier, merci de réessayer.", {width: 370})
      }
    })
  }

  static delete (params, showAlert = true) {
    if (params.colname !== undefined) {
      var $deleteBtn = $('#' + params.colname + 'Actions').find('button.delete')
    } else {
      $deleteBtn = $(params.target)
      params = this.getTargetParams($deleteBtn, 'target-params')
    }

    let btnHtml = $deleteBtn.html()
    $deleteBtn.addClass('disabled')
    $deleteBtn.html('<i class="fa fa-circle-o-notch"></i>')
    window.chmModal.destroy('[chm-modal-id="confirm"]')

    window.chmModal.show({
      type: 'POST',
      url: window.chmSite.url('candidat/file/delete'),
      data: params
    }, {
      showAlert: false,
      disableLoader: true,
      message: '<i class="fa fa-trash"></i>&nbsp;' + 'Suppression en cours...',
      onSuccess: (response) => {
        let colname = params.colname
        let $target = $('#' + colname + 'Input').find('[data-colname]')

        $deleteBtn.removeClass('disabled')
        $deleteBtn.html(btnHtml)

        this.reset($target)

        if (showAlert) {
          window['chmAlert'][response.status](response.message)
        }

        if (response.status === 'success') {
          $($deleteBtn).trigger('chmAttachmentDeleted', response)
        }
      },
      onError: function (response) {
        $deleteBtn.removeClass('disabled')
        $deleteBtn.html(btnHtml)
      }
    })
  }

  static reset (target) {
    let colname = $(target).data('colname')
    let $colInput = $(target).closest('#' + colname + 'Input')
    let $colActions = $($colInput).next('#' + colname + 'Actions')

    $($colInput).show()
    $($colActions).hide()
    $($colActions).find('.file_name').val('')
    $($colActions).find('.file_url').attr('href', 'javascript:void(0)')

    // Update delete button params
    let $deleteBtn = $($colActions).find('button.delete')
    let fileParams = this.getTargetParams($deleteBtn, 'target-params')
    fileParams.fname = ''
    $($deleteBtn).attr('data-fname', fileParams.fname)
    window.chmForm.setTargetParams($deleteBtn, 'target-params', fileParams)

    $(target).val('').clone(true)
    $(target).trigger('chmFileSelected', [0, null])
    $(target).removeAttr('chm-validated')
  }

}

$(document).ready(function () {
  // Upload files on change
  $('body').on('chmValidateSuccess', '[chm-file-upload]', function () {
    if ($(this).attr('chm-validated') === undefined) {
      $(this).attr('chm-validated', '')
      Attachment.upload(this)
    }
  })
  // Stop Upload files
  $('body').on('click', '.stopUpload', function () {
    let $form = $(this).closest('form')
    let $target = $(this).closest('.file-upload').find('[type="file"]')
    let $submitBtn = $($form).find('button[type="submit"]').last()
    let $loadingBtn = $($form).find('.file-upload span.btn')

    $($loadingBtn).removeClass('disabled')
    $($submitBtn).prop('disabled', false)

    $($target).prop('disabled', false)

    let $btnIcon = $($target).closest('span.btn').find('i')
    $btnIcon.removeClass('fa-circle-o-notch')
    $btnIcon.addClass('fa-upload')

    Attachment.reset($target)
    $(this).hide()
  })
})
