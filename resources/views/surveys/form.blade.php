@extends('layouts.app')
@section('title', $survey->id > 0 ? "Modifier le questionnaire" : "Ajouter un questionnaire")
@section('breadcrumb')
  <li><a href="{{ route('surveys-list') }}" class="text-blue">Questionnaires</a></li>
  <li>{{ $survey->id > 0 ? $survey->title : 'Ajouter' }}</li>
@endsection
@section('content')
  <div class="content" id="content">
    <form @submit.prevent="handleSubmit()" action="" method="post" novalidate>
      <div class="row mb-30">
        <div class="col-md-8 col-md-offset-2">
          <div class="card">
            <div class="card-body">
              <div class="row" :class="{'has-error': errors.has('title')}">
                <div class="col-md-12">
                  <label for="" class="control-label required">Titre du questionnaire</label>
                  <input type="text" name="title" v-model="title" class="form-control" v-validate="'required'" placeholder="" @keypress.enter.prevent>
                  <span v-show="errors.has('title')" class="help-block">@{{ errors.first('title') }}</span>
                </div>
              </div>
              <div class="row mb-30">
                <div class="col-md-12">
                  <label for="" class="control-label">Description</label>
                  <textarea name="" id="" class="form-control" v-model="description"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6" :class="{'has-error': errors.has('model')}">
                  <label for="" class="control-label required">Modèle</label>
                  <select name="model" id="" class="form-control" v-model="model" v-validate="'required'">
                    <option value=""></option>
                    <option value="Entretien annuel">Entretien annuel</option>
                    <option value="Feedback 360">Feedback 360</option>
                  </select>
                  <span v-show="errors.has('model')" class="help-block">@{{ errors.first('model') }}</span>
                </div>
                <div class="col-md-6" v-if="model == 'Entretien annuel'">
                  <label for="" class="control-label">Evaluations</label>
                  <select name="section" id="" class="form-control" v-model="section">
                    <option value=""></option>
                    @foreach($evaluations as $eval)
                      @if($eval->title != "Commentaires")
                        <option value="{{$eval->id}}" {{ $eval->id == $survey->evaluation_id ? 'selected':''}}>{{$eval->title}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="groups.length <= 0" class="row mb-30">
        <div class="col-md-4 col-md-offset-4">
          <label for="">Entrer le nombre des blocks pour ce questionnaire</label>
          <div class="input-group" :class="{'has-error': errors.has('number')}">
            <input type="number" name="number" min="1" max="100" v-model="number" v-validate="'required'" class="form-control" placeholder="Entrer le nombre des groupes">
            <span class="input-group-btn">
              <button class="btn btn-success" @click="addGroups()" type="button">Valider</button>
            </span>
            <div class="clearfix"></div>
          </div>
          <span v-show="errors.has('number')" class="help-block text-red">@{{ errors.first('number') }}</span>
        </div>
      </div>
      <div class="row mb-30">
        <div class="col-md-8 col-md-offset-2">
          <div class="card mb-40" v-for="(group, grpIndex) in groups" :class="{highlight:group.active}">
            <div class="card-header">
              <div class="form-group" v-if="group.edit" :class="{'has-error': errors.has('group')}">
                <div class="row">
                  <div class="col-md-11">
                    <input name="group" v-model="group.title" class="form-control" @blur="updateGroup(group)" @keyup.enter="updateGroup(group)" placeholder="Entrer le titre du block" v-validate="'required'" @keypress.enter.prevent v-focus>
                    <span v-show="errors.has('group')" class="help-block">@{{ errors.first('group') }}</span>
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-tool btn-xs pull-right text-danger" data-toggle="tooltip" title="Supprimer ce block" @click="removeGroup(grpIndex)"><i class="fa fa-trash"></i></button>
                  </div>
                </div>
              </div>
              <h3 v-else class="mb-0 card-title w-100">
                <label @click="group.edit = true;" class="control-label pull-left mb-0 font-16">Block @{{ grpIndex + 1 }} : @{{ group.title }}</label>
                <button type="button" class="btn btn-tool btn-xs pull-right text-danger" data-toggle="tooltip" title="Supprimer ce block" @click="removeGroup(grpIndex)"><i class="fa fa-trash"></i></button>

                <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editGroup(group)"><i class="fa fa-pencil"></i></button>
              </h3>
            </div>
            <div class="box-body">
              <div class="panel panel-default" v-for="(question, qIndex) in group.questions" :class="{highlight:question.active}">
                <div class="panel-heading pt-5 pb-5">
                  <div class="form-group mb-0" v-if="question.edit" :class="{'has-error': errors.has('question')}">
                    <div class="row">
                      <div class="col-md-11">
                        <input name="question" v-model="question.title" class="form-control" @blur="updateQuestion(question)" @keyup.enter="updateGroup(question)" v-focus placeholder="Entrez le titre de la question" v-validate="'required'" @keypress.enter.prevent>
                        <span v-show="errors.has('question')" class="help-block">@{{ errors.first('question') }}</span>
                      </div>
                      <div class="col-md-1">
                        <button type="button" class="btn btn-tool btn-xs pull-right text-danger" title="Supprimer cette question" @click="removeQuestion(grpIndex, qIndex)"><i class="fa fa-trash"></i></button>
                      </div>
                    </div>
                  </div>
                  <p v-else class="m-0">
                    <label @click="question.edit = true;" class="pull-left control-label mb-0">Question @{{ qIndex + 1 }} : @{{ question.title }} <span class="label label-default ml-20">@{{ getQuestionType(question.type) }}</span></label>
                    <span class="d-inline-block">
                      <button type="button" class="btn btn-tool btn-xs pull-right text-danger" title="Supprimer cette question" @click="removeQuestion(grpIndex, qIndex)"><i class="fa fa-trash"></i></button>
                      <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editQuestion(question)"><i class="fa fa-pencil"></i></button>
                    </span>
                  </p>
                  <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled">
                    <li v-for="(choice, cIndex) in group.questions[qIndex].choices" class="mb-10">
                      <div v-if="choice.edit" class="form-group">
                        <input name="choice" v-model="choice.title" class="form-control" @blur="updateChoice(grpIndex, qIndex, cIndex, choice)" @keyup.enter="updateChoice(grpIndex, qIndex, cIndex, choice)" v-focus placeholder="Entrez l'option de réponse" v-validate="'required'" @keypress.enter.prevent>
                      </div>
                      <p v-else class="m-0 text-muted">
                        <label @click="choice.edit = true;" class="mb-0">@{{ cIndex + 1 }} | @{{ choice.title }}</label>
                        <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeChoice(grpIndex, qIndex, cIndex)"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editChoice(choice)"><i class="fa fa-pencil"></i></button>
                      </p>
                    </li>
                    <a v-if="question.type == 'radio' || question.type == 'checkbox' || question.type == 'select'" href="javascript:void(0)" @click="addNewChoice(grpIndex, qIndex)"><i class="fa fa-plus"></i> Ajouter une option de réponse</a>
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
            <button v-if="groups.length > 0" type="button" class="btn btn-success pull-right" @click="addNewGroup()"><i class="fa fa-plus"></i> Ajouter un block</button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2" v-if="groups.length > 0">
          <div class="card">
            <div class="card-body">
              <button class="btn btn-primary pull-right" :disabled="submitted"><i class="fa fa-save"></i> Enregistrer</button>
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
        number: 1,
        id: "{{ $survey->id }}",
        title: "{!! $survey->title !!}",
        description: "{!! $survey->description !!}",
        model: "{!! $survey->model !!}",
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
                  edit: false,
                  active: false
                },
                @endif
              @endforeach
            ],
            edit: false,
            active: false
          },
          @endforeach
        ],
        submitted: false,
      },
      methods: {
        addGroups: function () {
          for (let i = 0; i < this.number; i++) {
            this.groups.push({
              id: null,
              title: "",
              edit: true,
              active: false,
              questions: []
            })
          }
        },
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
            active: false,
            questions: []
          })
        },
        removeGroup: function (index) {
          this.groups[index].active = true
          if (this.groups.length > 1) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              setTimeout(() => this.groups.splice(index, 1), 300)
            } else {
              this.groups[index].active = false
            }
          } else {
            this.groups[index].active = false
            alert("Le questionnaire doit avoir au moins un block, vous ne pouvez pas supprimer ce dernier block !")
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
                active: false,
                choices: []
              }
          )
        },
        removeQuestion: function (grpIndex, qIndex) {
          this.groups[grpIndex].questions[qIndex].active = true
          if (this.groups[grpIndex].questions.length > 1) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              setTimeout(() => this.groups[grpIndex].questions.splice(qIndex, 1), 300);
            } else {
              this.groups[grpIndex].questions[qIndex].active = false
            }
          } else {
            this.groups[grpIndex].questions[qIndex].active = false
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
              this.submitted = true
              axios.post("{{ route('survey.store') }}", {
                id: this.id,
                title: this.title,
                description: this.description,
                model: this.model,
                section: this.section,
                groups: this.groups,
              }).then(function (response) {
                if (response.status == 200) {
                  swal({
                    title: "Succès",
                    text: "Les informations ont bien été enregistrées",
                    type: "success"
                  }).then(function () {
                    window.location.href = "{{ route('surveys-list') }}"
                  });
                }
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