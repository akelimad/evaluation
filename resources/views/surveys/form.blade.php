@extends('layouts.app')

@section('content')
  <div class="content" id="content">
    <form @submit.prevent="handleSubmit()" action="" method="post" novalidate>
      <div class="row mb-20">
        <div class="col-md-12">
          <h3><a href="{{ route('surveys-list') }}"><i class="fa fa-angle-left"></i> Retourner aux questionnaires</a></h3>
        </div>
      </div>
      <div class="row mb-30">
        <div class="col-md-8 col-md-offset-2">
          <div class="card">
            <div class="card-body">
              <div class="form-group mb-30" :class="{'has-error': errors.has('title')}">
                <label for="">Titre du questionnaire</label>
                <input type="text" name="title" v-model="title" class="form-control" v-validate="'required'" placeholder="" @keypress.enter.prevent>
                <span v-show="errors.has('title')" class="help-block">@{{ errors.first('title') }}</span>
              </div>
              <div class="form-group mb-30">
                <label for="">Description</label>
                <textarea name="" id="" class="form-control" v-model="description"></textarea>
              </div>
              <div class="form-group" :class="{'has-error': errors.has('section')}">
                <label for="">Section</label>
                <select name="section" id="" class="form-control" v-model="section" v-validate="'required'">
                  <option value=""></option>
                  @foreach($evaluations as $eval)
                    @if($eval->title =="Evaluations" || $eval->title =="Carrières")
                      <option value="{{$eval->id}}" {{ $eval->id == $survey->evaluation_id ? 'selected':''}}>{{$eval->title}}</option>
                    @endif
                  @endforeach
                </select>
                <span v-show="errors.has('section')" class="help-block">@{{ errors.first('section') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-30">
        <div class="col-md-8 col-md-offset-2">
          <div class="card mb-40" v-for="(group, grpIndex) in groups">
            <div class="card-header">
              <div class="form-group" v-if="group.edit" :class="{'has-error': errors.has('group')}">
                <input name="group" v-model="group.title" class="form-control" @blur="updateGroup(group)" @keyup.enter="updateGroup(group)" v-focus placeholder="Entrez le titre du groupe de questions" v-validate="'required'" @keypress.enter.prevent>
                <span v-show="errors.has('group')" class="help-block">@{{ errors.first('group') }}</span>
              </div>
              <h3 v-else class="mb-0 card-title w-100">
                <label @click="group.edit = true;" class="mb-0">@{{ group.title }}</label>
                <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeGroup(grpIndex)"><i class="fa fa-trash"></i></button>
                <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editGroup(group)"><i class="fa fa-pencil"></i></button>
              </h3>
            </div>
            <div class="box-body">
              <div class="panel panel-default" v-for="(question, qIndex) in group.questions">
                <div class="panel-heading">
                  <div class="form-group" v-if="question.edit" :class="{'has-error': errors.has('question')}">
                    <input name="question" v-model="question.title" class="form-control" @blur="updateQuestion(question)" @keyup.enter="updateGroup(question)" v-focus placeholder="Entrez le titre de la question" v-validate="'required'" @keypress.enter.prevent>
                    <span v-show="errors.has('question')" class="help-block">@{{ errors.first('question') }}</span>
                  </div>
                  <p v-else class="m-0">
                    <label @click="question.edit = true;" class="mb-0">@{{ question.title }} <span class="label label-default ml-20">@{{ getQuestionType(question.type) }}</span></label>
                    <span class="d-inline-block">
                      <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeQuestion(grpIndex, qIndex)"><i class="fa fa-trash"></i></button>
                      <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editQuestion(question)"><i class="fa fa-pencil"></i></button>
                    </span>
                  </p>
                  <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled">
                    <li v-for="(choice, cIndex) in group.questions[qIndex].choices" class="mb-10">
                      <div v-if="choice.edit" class="form-group">
                        <input name="choice" v-model="choice.title" class="form-control" @blur="updateChoice(grpIndex, qIndex, cIndex, choice)" @keyup.enter="updateChoice(grpIndex, qIndex, cIndex, choice)" v-focus placeholder="Ajouter une option" v-validate="'required'" @keypress.enter.prevent>
                      </div>
                      <p v-else class="m-0 text-muted">
                        <label @click="choice.edit = true;" class="mb-0">@{{ cIndex + 1 }} | @{{ choice.title }}</label>
                        <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeChoice(grpIndex, qIndex, cIndex)"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editChoice(choice)"><i class="fa fa-pencil"></i></button>
                      </p>
                    </li>
                    <a v-if="question.type == 'radio' || question.type == 'checkbox' || question.type == 'select'" href="javascript:void(0)" @click="addNewChoice(grpIndex, qIndex)"><i class="fa fa-plus"></i> Ajouter une option</a>
                  </ul>
                </div>
              </div>
              <div class="add-new-question-btn">
                <div class="dropdown pull-right">
                  <button class="btn btn-info dropdown-toggle" type="button" id="questionTypes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-plus"></i> Ajouter une question <span class="caret"></span></button>
                  <ul class="dropdown-menu" aria-labelledby="questionTypes">
                    <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'text')">Court text</a></li>
                    <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'textarea')">Long text</a></li>
                    <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'radio')">Un seul choix (radio)</a></li>
                    <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'checkbox')">Choix multiple (checkbox)</a></li>
                    <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'select')">Liste déroulante (select)</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="add-new-section-btn">
            <button type="button" class="btn btn-success" @click="addNewGroup()"><i class="fa fa-plus"></i> Ajouter un groupe de questions</button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2" v-if="groups.length > 0">
          <div class="card">
            <div class="card-body">
              <button class="btn btn-primary pull-right"><i class="fa fa-save"></i> Enregistrer</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('javascript')
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/vue-i18n/dist/vue-i18n.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vee-validate@<3.0.0/dist/vee-validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
  <script>
    Vue.use(VeeValidate);
    new Vue({
      el: '#content',
      data: {
        id: "{{ $survey->id }}",
        title: "{!! $survey->title !!}",
        description: "{!! $survey->description !!}",
        section: "{{ $survey->evaluation_id }}",
        groups: [
          @foreach($survey->groupes as $group)
          {
            id: "{{ $group->id }}",
            title: "{{ $group->name }}",
            questions: [
              @foreach($group->questions as $question)
                @if ($question->parent_id == 0)
                {
                  id: "{{ $question->id }}",
                  title: {!! json_encode($question->titre) !!},
                  type: "{{ $question->type }}",
                  choices: [
                    @foreach($question->children as $child)
                    {
                      title: "{!! $child->titre !!}",
                      edit: false
                    },
                    @endforeach
                  ],
                  edit: false
                },
                @endif
              @endforeach
            ],
            edit: false,
          },
          @endforeach
        ],
        submit: false
      },
      methods: {
        getQuestionType: function (type) {
          switch (type) {
            case 'text':
              return "Court text"
              break;
            case 'textarea':
              return "Long text"
              break;
            case 'radio':
              return "Un seul choix"
              break;
            case 'checkbox':
              return "Choix multiple"
              break;
            case 'select':
              return "Liste déroulante"
              break;
            default: return 'Text'
          }
        },
        editGroup: function (group) {
          group.edit = true
        },
        updateGroup: function (group) {
          if (group.title.trim() != '') {
            group.edit = false
          }
        },
        addNewGroup: function () {
          this.groups.push({
            id: null,
            title: "",
            edit: true,
            questions: []
          })
        },
        removeGroup: function (index) {
          if (this.groups.length > 1) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) this.groups.splice(index, 1)
          } else {
            alert("Le questionnaire doit avoir au moins un groupe, vous ne pouvez pas supprimer ce groupe !")
          }
        },
        editQuestion: function (question) {
          question.edit = true
        },
        updateQuestion: function (question) {
          if (question.title.trim() != '') {
            question.edit = false
          }
        },
        addNewQuestion: function (groupIndex, qType) {
          this.groups[groupIndex].questions.push(
              {
                id: null,
                title: "",
                type: qType,
                edit: true,
                choices: []
              }
          )
        },
        removeQuestion: function (grpIndex, qIndex) {
          if (this.groups[grpIndex].questions.length > 1) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.groups[grpIndex].questions.splice(qIndex, 1)
            }
          } else {
            alert("La section doit avoir au moins une question !")
          }
        },
        addNewChoice: function(grpIndex, qIndex, choice) {
          this.groups[grpIndex].questions[qIndex].choices.push({title: "", edit: true})
        },
        editChoice: function (choice) {
          choice.edit = true
        },
        updateChoice: function (grpIndex, qIndex, cIndex, choice) {
          if (choice.title.trim() != '') {
            choice.edit = false
          } else {
            this.groups[grpIndex].questions[qIndex].choices.splice(cIndex, 1)
          }
          this.submit = false
        },
        removeChoice: function (grpIndex, qIndex, cIndex) {
          if (this.groups[grpIndex].questions[qIndex].choices.length > 2) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.groups[grpIndex].questions[qIndex].choices.splice(cIndex, 1)
            }
          } else {
            alert("Cette question doit avoir au moins 2 options, vous ne pouvez pas supprimer !")
          }
        },
        handleSubmit: function () {
          this.$validator.validateAll().then((result) => {
            if (result) {
              axios.post("{{ route('survey.store') }}", {
                id: this.id,
                title: this.title,
                description: this.description,
                section: this.section,
                groups: this.groups,
              }).then(function (response) {
                swal({
                  title: "Succès",
                  text: "Les informations ont bien été enregistrées",
                  type: "success"
                }).then(function () {
                  window.location.href = "{{ route('surveys-list') }}"
                });
              }).catch(function (error) {
                console.log(error)
              });
            }
          })
        }
      },
      directives: {
        focus: {
          inserted (el) {
            el.focus()
          }
        }
      }
    })
  </script>
@endsection