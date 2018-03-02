// Bootstrap components
require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/modal')
// require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/dropdown')
// require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/collapse')
// require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/alert')

// Main modules
import chmSite from './class/site'
import chmUrl from './class/url'
import chmCookie from './class/cookie'
import chmModal from './class/modal'
import chmFilter from './class/filter'

import chmUser from './class/user'
import chmRole from './class/role'
import chmPermission from './class/permission'
import chmEntretien from './class/entretien'
import chmDocument from './class/document'
import chmRemuneration from './class/remuneration'
import chmComment from './class/comment'
import chmDecision from './class/decision'
import chmSkill from './class/skill'
import chmActivite from './class/activite'
import chmFormation from './class/formation'
import chmObjectif from './class/objectif'
import chmGroupe from './class/groupe'
import chmQuestion from './class/question'

// Store modules in window
window.chmSite = chmSite
window.chmUrl = chmUrl
window.chmCookie = chmCookie
window.chmModal = chmModal
window.chmFilter = chmFilter

window.chmUser = chmUser
window.chmRole = chmRole
window.chmPermission = chmPermission
window.chmEntretien = chmEntretien
window.chmDocument = chmDocument
window.chmRemuneration = chmRemuneration
window.chmComment = chmComment
window.chmDecision = chmDecision
window.chmSkill = chmSkill
window.chmActivite = chmActivite
window.chmFormation = chmFormation
window.chmObjectif = chmObjectif
window.chmGroupe = chmGroupe
window.chmQuestion = chmQuestion

// Standart jQuery script
import './custom'
