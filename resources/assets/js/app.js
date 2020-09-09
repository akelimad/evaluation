// Bootstrap components
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/modal')
 // require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/dropdown')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/collapse')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/alert')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/tooltip')
 require('./../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/popover')

// Main modules
import chmAlert from './components/chmAlert'
import chmChart from './components/chmChart'
import chmCookie from './components/chmCookie'
import chmDate from './components/chmDate'
import chmFilter from './components/chmFilter'
import chmForm from './components/chmForm'
import chmModal from './components/chmModal'
import chmPrint from './components/chmPrint'
import chmSearch from './components/chmSearch'
import chmSite from './components/chmSite'
import chmTable from './components/chmTable'
import chmUrl from './components/chmUrl'

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
import Attachment from './class/attachment'
import Crm from './class/crm'
import Fonction from './class/fonction'
import Department from './class/department'
import Team from './class/team'

// Standart jQuery script
import './custom'

// Store modules in window
window.chmAlert = chmAlert
window.chmChart = chmChart
window.chmCookie = chmCookie
window.chmDate = chmDate
window.chmFilter = chmFilter
window.chmForm = chmForm
window.chmModal = chmModal
window.chmPrint = chmPrint
window.chmSearch = chmSearch
window.chmSite = chmSite
window.chmTable = chmTable
window.chmUrl = chmUrl

// store my class in window
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
window.Team = Team
