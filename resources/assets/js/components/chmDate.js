import $ from 'jquery'
import trans from './../script/functions'

export default class chmDate {

  static datepicker (target, params = {}) {
    params = $.extend({}, {
      closeText: trans("Fermer"),
      prevText: trans("Précédent"),
      nextText: trans("Suivant"),
      currentText: trans("Aujourd'hui"),
      monthNames: [
        trans("Janvier"),
        trans("Février"),
        trans("Mars"),
        trans("Avril"),
        trans("Mai"),
        trans("Juin"),
        trans("Juillet"),
        trans("Août"),
        trans("Septembre"),
        trans("Octobre"),
        trans("Novembre"),
        trans("Décembre")
      ],
      monthNamesShort: [
        trans("Janv."),
        trans("Févr."),
        trans("Mars"),
        trans("Avril"),
        trans("Mai"),
        trans("Juin"),
        trans("Juil."),
        trans("Août"),
        trans("Sept."),
        trans("Oct."),
        trans("Nov."),
        trans("Déc.")
      ],
      dayNames: [
        trans("Dimanche"),
        trans("Lundi"),
        trans("Mardi"),
        trans("Mercredi"),
        trans("Jeudi"),
        trans("Vendredi"),
        trans("Samedi")
      ],
      dayNamesShort: [
        trans("Dim."),
        trans("Lun."),
        trans("Mar."),
        trans("Mer."),
        trans("Jeu."),
        trans("Ven."),
        trans("Sam.")
      ],
      dayNamesMin: [
        trans("D"),
        trans("L"),
        trans("M"),
        trans("M"),
        trans("J"),
        trans("V"),
        trans("S")
      ],
      weekHeader: trans("Sem."),
      dateFormat: 'dd/mm/yy',
      defaultDate: "+1w",
      maxDate: '-1day',
      minDate: "-50Y",
      yearRange: "-100:+0",
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 1
    }, params)

    $(target).datepicker(params)
  }

  static getTargetParams (target, attrName) {
    var params = {}
    var value = $(target).attr(attrName)
    if (value !== undefined && value !== '') {
      try {
        params = $.parseJSON(value)
      } catch (e) {
        window.chmAlert.warning(trans("Le format de JSON donné n'est pas correct."))
      }
    }
    return params
  }

}

// Initialise datepicker
$(document).ready(function () {
  // Set date picker for all inputs with chmDate class
  $(document).on('focus', '[chm-date]', function () {
    let target = $(this)
    chmDate.datepicker(target, chmDate.getTargetParams(target, 'chm-date'))
  })

  // Force prevent default after selecting a day
  $('body').on('click', '.ui-state-default', function (event) {
    event.preventDefault()
    $('[chm-date]').datepicker("hide")
  })
})
