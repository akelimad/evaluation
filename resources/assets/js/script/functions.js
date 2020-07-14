import $ from 'jquery'

export default function trans (msgid) {
  if ('etaTrans' in window && msgid in window.etaTrans) {
    return window.etaTrans[msgid]
  } else {
    return msgid
  }
}

window.showNumberOfSelectedItems = (target) => {
  var $ul = $(target).next('span').find('ul')
  $ul.hide()
  window.setTimeout(function () {
    var count = $ul.find('li').length - 1
    var label = (count === 1) ? 'élément sélectionné' : 'éléments sélectionnés'
    switch (count) {
      case 0:
        $ul.empty().hide()
        break
      case 1:
        $ul.empty().html('<li class="count-selected">' + count + ' ' + label + '</li>')
        $ul.show()
        break
      default:
        $ul.html('<li class="count-selected">' + count + ' ' + label + '</li>')
        $ul.show()
        break
    }
  }, 500)
}

