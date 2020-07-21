// Bootstrap components
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/modal')
 // require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/dropdown')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/collapse')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/alert')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/tooltip')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/popover')

// Main modules
import chmSite from './class/site'
import chmUrl from './class/url'
import chmCookie from './class/cookie'
import chmModal from './class/modal'
import chmFilter from './class/filter'
import chmAlert from './class/alert'

import chmUser from './class/user'
import chmRole from './class/role'
import chmPermission from './class/permission'
import chmEntretien from './class/entretien'
import chmSalary from './class/salary'
import chmComment from './class/comment'
import chmCarreer from './class/carreer'
import chmSkill from './class/skill'
import chmFormation from './class/formation'
import chmObjectif from './class/objectif'
import chmGroupe from './class/groupe'
import chmQuestion from './class/question'
import chmSurvey from './class/survey'
import chmEntretienObjectif from './class/entretienObjectif'
import chmEmail from './class/email'
import chmEmailAction from './class/emailAction'
import Setting from './class/setting'
import Attachment from './class/Attachment'
import Crm from './class/crm'
import Fonction from './class/Fonction'
import Department from './class/Department'
import chmForm from './class/chmForm'
import Team from './class/Team'

// Standart jQuery script
import './custom'

// Store modules in window
window.chmSite = chmSite
window.chmUrl = chmUrl
window.chmCookie = chmCookie
window.chmModal = chmModal
window.chmFilter = chmFilter
window.chmAlert = chmAlert

window.chmUser = chmUser
window.chmRole = chmRole
window.chmPermission = chmPermission
window.chmEntretien = chmEntretien
window.chmSalary = chmSalary
window.chmComment = chmComment
window.chmCarreer = chmCarreer
window.chmSkill = chmSkill
window.chmFormation = chmFormation
window.chmObjectif = chmObjectif
window.chmGroupe = chmGroupe
window.chmQuestion = chmQuestion
window.chmSurvey = chmSurvey
window.chmEntretienObjectif = chmEntretienObjectif
window.chmEmail = chmEmail
window.chmEmailAction = chmEmailAction
window.Setting = Setting
window.Attachment = Attachment
window.Crm = Crm
window.Fonction = Fonction
window.Department = Department
window.chmForm = chmForm
window.Team = Team
