import $ from 'jquery'
import trans from './../script/functions'

export default class chmPrint {

  constructor (target, container, title = '') {
    try {
      var self = this
      $(target).hide()
      var $modal = $(window.chmModal.template('chm-print'))

      $modal.find('.panel-footer').remove()
      $modal.removeClass('chm-confirm-modal')
      $modal.addClass('chm-loading-modal')
      $modal.find('.modal-title').html('<i class="fa fa-circle-o-notch"></i>&nbsp;' + trans("Traitement en cours..."))
      $modal.find('.modal-body').hide()
      $modal.find('.modal-dialog').css('width', 230)
      $modal.modal({ backdrop: 'static', keyboard: false })

      window.html2canvas(document.querySelector(container)).then(canvas => {
        $modal.find('.modal-header').hide()
        $modal.find('.modal-content').css('width', 660)
        $modal.find('.modal-body')
          .css('padding', 0)
          .append('<img src="' + canvas.toDataURL() + '">')

        setTimeout(function () {
          window.chmModal.destroy($modal)
          $(target).show()
          self.print($modal.find('.modal-body>img').attr('src'), title)
        }, 500)
      })
    } catch (e) {
      window.chmModal.destroy($modal)
      window.chmAlert.warning(e.message)
      $(target).show()
    }
  }

  print (dataUrl, title) {
    var windowContent = '<!DOCTYPE html>'
    windowContent += '<html>'
    windowContent += '<head><title>' + title + '</title></head>'
    windowContent += '<body>'
    windowContent += '<img src="' + dataUrl + '">'
    windowContent += '</body>'
    windowContent += '</html>'
    var printWin = window.open('', title)
    if (printWin !== null) {
      printWin.document.open()
      printWin.document.write(windowContent)
      printWin.document.close()
      printWin.focus()
      printWin.print()
      printWin.close()
    } else {
      window.chmModal.alert('', trans("Vous devez autoriser les fenêtres Pop-up pour imprimer cette page."), {width: 363})
    }
  }

}

// Initialise tables
$(document).ready(function () {
  $(document).on('click', '[chm-print]', function () {
    const title = $(this).attr('chm-print-title') || null
    new chmPrint($(this), $(this).attr('chm-print'), title)
  })
})
