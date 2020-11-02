@extends('layouts.app')
@section('title', $survey->id > 0 ? "Modifier le questionnaire" : "Ajouter un questionnaire")
@section('breadcrumb')
  <li><a href="{{ route('surveys-list') }}" class="text-blue">Questionnaires</a></li>
  <li>{{ $survey->id > 0 ? $survey->title : 'Ajouter' }}</li>
@endsection

<style>
  #nprogress .bar {
    background: orange !important;
  }

  #nprogress .peg {
    box-shadow: 0 0 10px orange, 0 0 5px orange !important;
  }

  #nprogress .spinner-icon {
    border-top-color: orange !important;
    border-left-color: orange !important;
  }
</style>

@section('content')
  <div class="content p-sm-10" id="content">
    <form @submit.prevent="handleSubmit()" action="" method="post" novalidate>
      <div class="row mb-30">
        <div class="col-md-8 col-md-offset-2">
          <div class="card">
            <div class="card-body">
              <div class="row" :class="{'has-error': errors.has('title')}">
                <div class="col-md-12">
                  <label for="" class="control-label required">{{ __("Titre du questionnaire") }}</label>
                  <input type="text" name="title" v-model="title" class="form-control" v-validate="'required'" placeholder="" @keypress.enter.prevent>
                  <span v-show="errors.has('title')" class="help-block">@{{ errors.first('title') }}</span>
                </div>
              </div>
              <div class="row mb-30">
                <div class="col-md-12">
                  <label for="" class="control-label">{{ __("Description") }}</label>
                  <textarea name="description" id="description" class="form-control" v-model="description"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6" :class="{'has-error': errors.has('model')}">
                  <label for="" class="control-label required">{{ __("Type") }} @{{ model.id }}</label>
                  <select name="model" id="model" class="form-control" v-model="model" v-validate="'required'" @change="showHideEvalSelect($event)">
                    <option value=""></option>
                    @foreach(\App\Modele::all() as $modele)
                      <option value="{{ $modele->id }}" data-ref="{{ $modele->ref }}">{{ $modele->title }}</option>
                    @endforeach
                  </select>
                  <span v-show="errors.has('model')" class="help-block">@{{ errors.first('model') }}</span>
                </div>
                <div class="col-md-6" v-if="selectedModelRef == 'ENT'">
                  <label for="" class="control-label">{{ __("Evaluations") }}</label>
                  <select name="section" id="" class="form-control" v-model="section">
                    <option value=""></option>
                    @foreach($evaluations as $eval)
                      @if(in_array($eval->title, ['Evaluation annuelle', 'Carrières']))
                        <option value="{{$eval->id}}" {{ $eval->id == $survey->evaluation_id ? 'selected':''}}>{{$eval->title}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>

              <div v-if="groups.length <= 0" class="row mb-30">
                <div class="col-md-6">
                  <label for="" class="control-label">{{ __("Entrer le nombre des thèmes pour ce questionnaire") }} <i class="fa fa-info-circle text-primary" title="{{ __("Cela représente les blocks, sections, ou groupes de questions") }}" data-toggle="tooltip"></i></label>
                  <div class="input-group" :class="{'has-error': errors.has('number')}">
                    <input type="number" name="number" min="1" max="100" v-model="number" v-validate="'required'" class="form-control" placeholder="Entrer le nombre des groupes" maxlength="3">
                    <span class="input-group-btn">
                      <button class="btn btn-success" @click="addGroups()" :disabled="validateGrpNbr" type="button">{{ __("Continuez") }}</button>
                    </span>
                    <div class="clearfix"></div>
                  </div>
                  <span v-show="errors.has('number')" class="help-block text-red">@{{ errors.first('number') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-30">
        <div v-if="groups.length > 0 && selectedModelRef == 'ENT'" class="col-md-8 col-md-offset-2">
          <p class="help-block"><i class="fa fa-info-circle text-primary"></i> {{ __("Total de la pondération des groupes doit être égal à 100") }}</p>
        </div>
        <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-primary mb-40" v-for="(group, grpIndex) in groups" :class="{highlight:group.active}">
            <div class="panel-heading" style="border-radius: 0;">
              <div v-if="group.edit" class="form-group" :class="{'has-error': errors.has('group')}">
                <div class="row mb-0">
                  <div :class="selectedModelRef == 'ENT' ? 'col-md-9':'col-md-11'">
                    <input type="text" name="title" v-model="group.title" class="form-control"  @keyup.enter="updateGroup(group)" placeholder="Entrer le titre du thème" v-validate="'required'" @keypress.enter.prevent v-focus>
                    <span v-show="errors.has('group')" class="help-block">@{{ errors.first('group') }}</span>
                  </div>
                  <div v-if="selectedModelRef == 'ENT'" class="col-md-2 pl-0 pr-0" title="Pondération (%)" >
                    <input type="number" min="1" max="{{ App\Setting::get('max_note', 5) }}" name="ponderation" v-model="group.ponderation" class="form-control" @keypress.enter.prevent @keyup.enter="updateGroup(group)" placeholder="Pondération">
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-tool btn-xs pull-right text-danger" title="Supprimer" @click="removeGroup(grpIndex, group)"><i class="fa fa-trash"></i></button>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <h3 v-else class="mb-0 card-title w-100">
                <label @click="group.edit = true;" class="control-label pull-left mb-0 font-16">{{ __("Thème") }} @{{ grpIndex + 1 }} : @{{ group.title }} <i class="fa fa-info-circle text-white ml-10" data-toggle="tooltip" title="{{ __("Total de la pondération des questions de ce thème doit être égal à 100") }}"></i></label>
                <button type="button" class="btn btn-tool btn-xs pull-right text-danger" title="Supprimer" @click="removeGroup(grpIndex, group)"><i class="fa fa-trash"></i></button>

                <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editGroup(group)"><i class="fa fa-pencil" title="Modifier"></i></button>

                <span v-if="selectedModelRef == 'ENT'" class="badge pull-right mr-10" title="Pondération (%)" >@{{ group.ponderation + ' %' }}</span>
                <div class="clearfix"></div>
              </h3>
            </div>
            <div class="panel-body">
              <div class="card card-default p-10 mb-30" v-for="(question, qIndex) in group.questions" :class="{highlight:question.active}">
                <div class="card-heading pt-5 pb-5">
                  <div v-if="question.edit" class="form-group mb-0" :class="{'has-error': errors.has('question')}">
                    <div class="row">
                      <div :class="selectedModelRef == 'ENT' ? 'col-md-9':'col-md-11'">
                        <input name="question" v-model="question.title" class="form-control" @keyup.enter="updateQuestion(question, grpIndex)" v-focus placeholder="Entrez le titre de la question" v-validate="'required'" @keypress.enter.prevent>
                        <span v-show="errors.has('question')" class="help-block">@{{ errors.first('question') }}</span>
                      </div>
                      <div v-if="selectedModelRef == 'ENT'" class="col-md-2 pl-0 pr-0" title="Pondération (%)" >
                        <input type="number" min="1" max="{{ App\Setting::get('max_note', 5) }}" name="ponderation" v-model="question.ponderation" class="form-control" @keyup.enter="updateQuestion(question, grpIndex)" placeholder="Pondération" @keypress.enter.prevent>
                      </div>
                      <div class="col-md-1">
                        <button type="button" class="btn btn-tool btn-xs pull-right text-danger" title="Supprimer cette question" @click="removeQuestion(grpIndex, qIndex, group, question)"><i class="fa fa-trash"></i></button>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                  <div v-else class="m-0">
                    <div class="row mb-0">
                      <div class="col-md-7">
                        <label @click="editQuestion(question, grpIndex)" class="pull-left control-label mb-0 mr-5">{{ __("Question") }} @{{ qIndex + 1 }} : @{{ question.title }}</label>
                      </div>
                      <div v-if="!question.edit" class="col-md-2">
                        <div class="dropdown" style="display: inline-block;">
                          <button class="btn btn-default btn-xs dropdown-toggle" type="button" :id="'aa' + grpIndex + qIndex" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">@{{ getQuestionType(question.type) }} <span class="caret"></span></button>
                          <ul class="dropdown-menu" :aria-labelledby="'aa' + grpIndex + qIndex">
                            <li>
                              <a href="javascript:void(0)" class="popoverData" @click="changeQuestionType(grpIndex, qIndex, 'text')">{{ __("Text (court)") }}</a>
                              <div class="popper-content hide">
                                <img src="{{ asset('/img/text.png') }}" class="img-responsive" alt=""/>
                              </div>
                            </li>
                            <li>
                              <a href="javascript:void(0)" class="popoverData" @click="changeQuestionType(grpIndex, qIndex, 'textarea')">{{ __("Text (long)") }}</a>
                              <div class="popper-content hide">
                                <img src="{{ asset('/img/textarea.png') }}" class="img-responsive" alt=""/>
                              </div>
                            </li>
                            <li>
                              <a href="javascript:void(0)" class="popoverData" @click="changeQuestionType(grpIndex, qIndex, 'radio')">{{ __("Un seul choix") }}</a>
                              <div class="popper-content hide">
                                <img src="{{ asset('/img/radio.png') }}" class="img-responsive" alt=""/>
                              </div>
                            </li>
                            <li>
                              <a href="javascript:void(0)" class="popoverData" @click="changeQuestionType(grpIndex, qIndex, 'checkbox')">{{ __("Choix multiple") }}</a>
                              <div class="popper-content hide">
                                <img src="{{ asset('/img/checkbox.png') }}" class="img-responsive" alt=""/>
                              </div>
                            </li>
                            <li>
                              <a href="javascript:void(0)" class="popoverData" @click="changeQuestionType(grpIndex, qIndex, 'select')">{{ __("Liste déroulante") }}</a>
                              <div class="popper-content hide">
                                <img src="{{ asset('/img/select.png') }}" class="img-responsive" alt=""/>
                              </div>
                            </li>
                            <li>
                              <a href="javascript:void(0)" class="popoverData" @click="changeQuestionType(grpIndex, qIndex, 'array')">{{ __("Tableau") }}</a>
                              <div class="popper-content hide">
                                <img src="{{ asset('/img/array.png') }}" class="img-responsive" alt=""/>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div v-if="!question.edit" class="">
                          <button type="button" class="btn btn-tool btn-xs pull-right text-danger" title="Supprimer" @click="removeQuestion(grpIndex, qIndex, group, question)"><i class="fa fa-trash"></i></button>

                          <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editQuestion(question, grpIndex)" title="Modifier"><i class="fa fa-pencil"></i></button>

                          <span v-if="selectedModelRef == 'ENT'" class="badge pull-right mr-10 text-muted" title="Pondération (%)" >@{{ question.ponderation > 0 ? question.ponderation : 0 }} %</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div v-if="question.type != 'text' && question.type !='textarea' && question.type !='array'" class="card-body">
                  <ul class="list-unstyled">
                    <li v-for="(choice, cIndex) in group.questions[qIndex].choices" class="mb-10">
                      <div v-if="choice.edit" class="form-group">
                        <input name="choice" v-model="choice.title" class="form-control" @blur="updateChoice(grpIndex, qIndex, cIndex, choice)" @keyup.enter="updateChoice(grpIndex, qIndex, cIndex, choice)" v-focus placeholder="Entrez l'option de réponse" v-validate="'required'" @keypress.enter.prevent>
                      </div>
                      <div v-else class="m-0 text-muted">
                        <label @click="choice.edit = true;" class="mb-0 d-inline-block">@{{ cIndex + 1 }} | @{{ choice.title }}</label>
                        <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeChoice(grpIndex, qIndex, cIndex)"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editChoice(choice)"><i class="fa fa-pencil"></i></button>
                        <div class="clearfix"></div>
                      </div>
                    </li>
                  </ul>
                  <a v-if="question.type == 'radio' || question.type == 'checkbox' || question.type == 'select'" href="javascript:void(0)" @click="addNewChoice(grpIndex, qIndex)"><i class="fa fa-plus"></i> {{ __("Ajouter une option de réponse") }}</a>
                </div>
                <div v-if="question.type == 'array'" class="card-body">
                  <div class="subquestions-container mb-20">
                    <p class="border-bottom" style="border-bottom: 1px dashed #e2dddd;">{{ __("Sous questions") }}</p>
                    <ul class="list-unstyled">
                      <li v-for="(subquestion, subIndex) in group.questions[qIndex].options.subquestions" class="mb-10">
                        <div v-if="subquestion.edit" class="form-group">
                          <input name="subquestion" v-model="subquestion.title" class="form-control" @blur="updateArraySubquestion(grpIndex, qIndex, subIndex, subquestion)" @keyup.enter="updateArraySubquestion(grpIndex, qIndex, subIndex, subquestion)" v-focus placeholder="Entrez la sous question" v-validate="'required'" @keypress.enter.prevent>
                        </div>
                        <div v-else class="m-0 text-muted">
                          <label @click="subquestion.edit = true;" class="mb-0 d-inline-block">@{{ subIndex + 1 }} | @{{ subquestion.title }}</label>
                          <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeArraySubquestion(grpIndex, qIndex, subIndex)"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editArraySubquestion(subquestion)"><i class="fa fa-pencil"></i></button>
                          <div class="clearfix"></div>
                        </div>
                      </li>
                    </ul>
                    <a href="javascript:void(0)" @click="addNewArraySubquestion(grpIndex, qIndex)"><i class="fa fa-plus"></i> {{ __("Ajouter une sous question") }}</a>
                  </div>
                  <div class="answers-container">
                    <p class="border-bottom" style="border-bottom: 1px dashed #e2dddd;">{{ __("Options de réponses") }}</p>
                    <ul class="list-unstyled">
                      <li v-for="(answer, aIndex) in group.questions[qIndex].options.answers" class="mb-10">
                        <div v-if="answer.edit" class="form-group">
                          <input name="answer" v-model="answer.title" class="form-control" @blur="updateArrayAnswer(grpIndex, qIndex, aIndex, answer)" @keyup.enter="updateArrayAnswer(grpIndex, qIndex, aIndex, answer)" v-focus placeholder="Entrez l'option de réponse" v-validate="'required'" @keypress.enter.prevent>
                        </div>
                        <div v-else class="m-0 text-muted">
                          <label @click="answer.edit = true;" class="mb-0 d-inline-block">@{{ aIndex + 1 }} | @{{ answer.title }}</label>
                          <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeArrayAnswer(grpIndex, qIndex, aIndex)"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editArrayAnswer(answer)"><i class="fa fa-pencil"></i></button>
                          <div class="clearfix"></div>
                        </div>
                      </li>
                    </ul>
                    <a href="javascript:void(0)" @click="addNewArrayAnswer(grpIndex, qIndex)"><i class="fa fa-plus"></i> {{ __("Ajouter une option de réponse") }}</a>
                  </div>
                </div>
              </div>
              <div class="add-new-question-btn">
                <div v-if="group.questions.length > 0" class="pull-left">
                  <div class="custom-switch-btn">
                    <label class="switch" :for="grpIndex+'_editMode'">
                      <input type="checkbox" :id="grpIndex+'_editMode'" class="hidden" v-model="group.editAllQuestion" @change="turnOnEditMode(grpIndex)">
                      <div class="slider-toggle round">
                        <span class="on">ON</span><span class="off">OFF</span>
                      </div>
                    </label>
                    <label class="mb-0 ml-5" style="display: inline-block; position: absolute; height: 27px; line-height: 27px;">{{ __("Mode édition") }}</label>
                  </div>
                </div>
                <div class="dropdown pull-right">
                  <button class="btn btn-info dropdown-toggle" type="button" id="questionTypes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-plus"></i> {{ __("Ajouter une question") }} <span class="caret"></span></button>
                  <ul class="dropdown-menu" aria-labelledby="questionTypes">
                    <li>
                      <a href="javascript:void(0)" class="popoverData" @click="addQuestion(grpIndex, 'text')">{{ __("Text (court)") }}</a>
                      <div class="popper-content hide">
                        <img src="{{ asset('/img/text.png') }}" class="img-responsive" alt=""/>
                      </div>
                    </li>
                    <li>
                      <a href="javascript:void(0)" class="popoverData" @click="addQuestion(grpIndex, 'textarea')">{{ __("Text (long)") }}</a>
                      <div class="popper-content hide">
                        <img src="{{ asset('/img/textarea.png') }}" class="img-responsive" alt=""/>
                      </div>
                    </li>
                    <li>
                      <a href="javascript:void(0)" class="popoverData" @click="addQuestion(grpIndex, 'radio')">{{ __("Un seul choix") }}</a>
                      <div class="popper-content hide">
                        <img src="{{ asset('/img/radio.png') }}" class="img-responsive" alt=""/>
                      </div>
                    </li>
                    <li>
                      <a href="javascript:void(0)" class="popoverData" @click="addQuestion(grpIndex, 'checkbox')">{{ __("Choix multiple") }}</a>
                      <div class="popper-content hide">
                        <img src="{{ asset('/img/checkbox.png') }}" class="img-responsive" alt=""/>
                      </div>
                    </li>
                    <li>
                      <a href="javascript:void(0)" class="popoverData" @click="addQuestion(grpIndex, 'select')">{{ __("Liste déroulante") }}</a>
                      <div class="popper-content hide">
                        <img src="{{ asset('/img/select.png') }}" class="img-responsive" alt=""/>
                      </div>
                    </li>
                    <li>
                      <a href="javascript:void(0)" class="popoverData" @click="addQuestion(grpIndex, 'array')">{{ __("Tableau") }}</a>
                      <div class="popper-content hide">
                        <img src="{{ asset('/img/array.png') }}" class="img-responsive" alt=""/>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <div class="add-new-section-btn text-center">
            <button v-if="groups.length > 0" type="button" class="btn btn-primary" @click="addNewGroup()"><i class="fa fa-plus"></i> {{ __("Ajouter un thème") }}</button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2" v-if="groups.length > 0">
          <div class="card">
            <div class="card-body">
              <button class="btn btn-success pull-right submit-btn" :disabled="submitted"><i class="fa fa-save"></i> {{ __("Enregistrer") }}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('javascript')
  @parent()
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
  <script src="https://unpkg.com/vue-i18n/dist/vue-i18n.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vee-validate@<3.0.0/dist/vee-validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
  <script src="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/index.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/nprogress.css" />

  <script>
    function initPopover() {
      $(".popoverData").popover({
        container: 'body',
        trigger: 'hover',
        placement: 'left',
        html: true,
        content: function () {
          return $(this).next('.popper-content').html();
        }
      });
      $('[data-toggle="tooltip"]').tooltip()
    }
    setTimeout(function () {
      initPopover()
    }, 500)
    loadProgressBar()
    Vue.use(VeeValidate);
    new Vue({
      el: '#content',
      data: {
        number: 1,
        id: "{{ $survey->id }}",
        title: "{!! $survey->title !!}",
        description: "{!! $survey->description !!}",
        model: "{!! $survey->model_id !!}",
        selectedModelRef: "{{ $survey->getModele() ? $survey->getModele()->ref : '' }}",
        section: "{{ $survey->evaluation_id }}",
        groups: [
          @foreach($survey->groupes as $group)
          {
            id: "{{ $group->id }}",
            title: "{!! $group->name !!}",
            ponderation: "{{ $group->ponderation > 0 ? $group->ponderation : 0 }}",
            questions: [
              @foreach($group->questions as $question)
                @if ($question->parent_id == 0)
                {
                  id: "{{ $question->id }}",
                  title: {!! json_encode($question->titre) !!},
                  ponderation: "{{ $question->ponderation > 0 ? $question->ponderation : 0 }}",
                  type: "{{ $question->type }}",
                  choices: [
                    @foreach($question->children as $child)
                    {
                      title: "{!! $child->titre !!}",
                      edit: false
                    },
                    @endforeach
                  ],
                  options: {
                    answers: [
                      @foreach($question->getOptions('answers') as $answer)
                      {
                        id: "{{ isset($answer['id']) ? $answer['id'] : '' }}",
                        title: "{!! isset($answer['title']) ? $answer['title'] : '' !!}",
                        edit: false
                      },
                      @endforeach
                    ],
                    subquestions: [
                      @foreach($question->getOptions('subquestions') as $subquestion)
                      {
                        id: "{{ isset($subquestion['id']) ? $subquestion['id'] : '' }}",
                        title: "{!! $subquestion['title'] !!}",
                        edit: false
                      },
                      @endforeach
                    ]
                  },
                  edit: false,
                  active: false
                },
                @endif
              @endforeach
            ],
            edit: false,
            active: false,
            editAllQuestion: false
          },
          @endforeach
        ],
        submitted: false,
      },
      methods: {
        turnOnEditMode: function (grpIndex) {
          var group = this.groups[grpIndex]
          this.groups[grpIndex].questions.forEach(function (question, qIndex) {
            question.edit = group.editAllQuestion
          })
        },
        showHideEvalSelect: function (e) {
          if (e.target.options.selectedIndex > -1) {
            const theTarget = e.target.options[e.target.options.selectedIndex].dataset;
            this.selectedModelRef = theTarget.ref
          }
        },
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
          setTimeout(function () {
            initPopover()
          }, 500)
        },
        getQuestionType: function (type) {
          switch (type) {
            case 'text':
              return "Text (court)"
              break;
            case 'textarea':
              return "Text (long)"
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
            case 'array':
              return "Tableau"
              break;
            default: return 'Text'
          }
        },
        editGroup: function (group) {
          group.edit = true
        },
        updateGroup: function (group) {
          var sumGrpsPonderations = 0
          this.groups.forEach(function (grpObject) {
            sumGrpsPonderations += parseFloat(grpObject.ponderation)
          })
          if (sumGrpsPonderations > 100) {
            swal({
              type: "warning",
              text: "La somme de la pondération des thèmes ne doit pas dépasser 100"
            })
            return;
          }
          if (group.title.trim() != '') {
            group.edit = false
            group.ponderation = group.ponderation > 0 ? group.ponderation : 0
          }
          setTimeout(function () {
            initPopover()
          }, 500)
        },
        addNewGroup: function () {
          this.groups.push({
            id: null,
            title: "",
            edit: true,
            active: false,
            questions: []
          })
          setTimeout(function () {
            initPopover()
          }, 500)
        },
        removeGroup: function (index, group) {
          this.groups[index].active = true
          if (this.groups.length > 0) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              if (this.id > 0) {
                axios.delete('surveys/'+this.id+'/groupes/'+group.id+'/delete', {
                }).then(function (response) {
                })
              }
              setTimeout(() => this.groups.splice(index, 1), 300)
            } else {
              this.groups[index].active = false
            }
          } else {
            this.groups[index].active = false
            swal({
              type: 'warning',
              text: "Le questionnaire doit avoir au moins un block, vous ne pouvez pas supprimer ce dernier block !"
            })
          }
        },
        editQuestion: function (question, grpIndex) {
          question.edit = true
          this.groups[grpIndex].editAllQuestion = true
        },
        updateQuestion: function (question, grpIndex) {
          var sumGrpQstsPonderations = 0
          this.groups[grpIndex].questions.forEach(function (qstObject) {
            sumGrpQstsPonderations += parseFloat(qstObject.ponderation)
          })
          if (sumGrpQstsPonderations > 100) {
            swal({
              type: "warning",
              text: "La somme de la pondération des questions du thème ne doit pas dépasser 100"
            })
            return;
          }
          if (question.title.trim() != '') {
            question.edit = false
            question.ponderation = question.ponderation > 0 ? question.ponderation : 0
          }
          var existQstInEditMode = false
          this.groups[grpIndex].questions.forEach(function (qstObject) {
            if (qstObject.edit == true) existQstInEditMode = true
          })
          if (!existQstInEditMode) this.groups[grpIndex].editAllQuestion = false
          setTimeout(function () {
            initPopover()
          }, 500)
        },
        addQuestion: function (groupIndex, qType) {
          var newPonderation = 100
          if (this.groups[groupIndex].questions.length > 0) {
            newPonderation = ''
            this.groups[groupIndex].questions.forEach(function (question) {
              if (question.ponderation <= 0 || question.ponderation == 100) {
                question.ponderation = ''
              }
            })
          }
          var options = {}
          if (qType == 'array') {

            options.answers = [];
            options.subquestions = [];
          }
          this.groups[groupIndex].questions.push(
              {
                id: null,
                title: "",
                ponderation: newPonderation,
                type: qType,
                edit: true,
                active: false,
                choices: [],
                options: options
              }
          )
        },
        removeQuestion: function (grpIndex, qIndex, group, question) {
          this.groups[grpIndex].questions[qIndex].active = true
          if (this.groups[grpIndex].questions.length > 0) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              if (this.id > 0) {
                axios.delete('surveys/'+this.id+'/groupes/'+group.id+'/questions/'+question.id+'/delete', {
                }).then(function (response) {

                })
              }
              setTimeout(() => {
                this.groups[grpIndex].questions.splice(qIndex, 1)
                if (this.groups[grpIndex].questions.length == 1) {
                  this.groups[grpIndex].questions[0].ponderation = 100
                }
              }, 300)
            } else {
              this.groups[grpIndex].questions[qIndex].active = false
            }
          } else {
            this.groups[grpIndex].questions[qIndex].active = false
            swal({
              type: 'warning',
              text: "Le block doit avoir au moins une question !"
            })
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
        },
        removeChoice: function (grpIndex, qIndex, cIndex) {
          if (this.groups[grpIndex].questions[qIndex].choices.length > 2) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.groups[grpIndex].questions[qIndex].choices.splice(cIndex, 1)
            }
          } else {
            swal({
              type: 'warning',
              text: "Cette question doit avoir au moins 2 options, vous ne pouvez pas supprimer !"
            })
          }
        },
        addNewArrayAnswer: function (grpIndex, qIndex) {
          this.groups[grpIndex].questions[qIndex].options.answers.push({
            'title': '',
            'edit': true
          })
        },
        editArrayAnswer: function (answer) {
          answer.edit = true
        },
        updateArrayAnswer: function (grpIndex, qIndex, aIndex, answer) {
          if (answer.title.trim() != '') {
            answer.edit = false
          } else {
            this.groups[grpIndex].questions[qIndex].options.answers.splice(aIndex, 1)
          }
        },
        removeArrayAnswer: function (grpIndex, qIndex, aIndex) {
          var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
          if (confirmation) {
            this.groups[grpIndex].questions[qIndex].options.answers.splice(aIndex, 1)
          }
        },
        addNewArraySubquestion: function (grpIndex, qIndex) {
          this.groups[grpIndex].questions[qIndex].options.subquestions.push({
            'title': '',
            'edit': true
          })
        },
        editArraySubquestion: function (subquestion) {
          subquestion.edit = true
        },
        updateArraySubquestion: function (grpIndex, qIndex, subIndex, subquestion) {
          if (subquestion.title.trim() != '') {
            subquestion.edit = false
          } else {
            this.groups[grpIndex].questions[qIndex].options.subquestions.splice(subIndex, 1)
          }
        },
        removeArraySubquestion: function (grpIndex, qIndex, subIndex) {
          var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
          if (confirmation) {
            this.groups[grpIndex].questions[qIndex].options.subquestions.splice(subIndex, 1)
          }
        },
        changeQuestionType: function (grpIndex, qIndex, newType) {
          this.groups[grpIndex].questions[qIndex].type = newType
          if (['text', 'textarea'].indexOf(newType) !== -1) {
            this.groups[grpIndex].questions[qIndex].choices = []
          }
        },
        handleSubmit: function () {
          this.$validator.validateAll().then((result) => {
            if (result) {
            $('.submit-btn').prop('disabled', true)
              axios.post("{{ route('survey.store') }}", {
                id: this.id,
                title: this.title,
                description: this.description,
                model: this.model,
                selectedModelRef: this.selectedModelRef,
                section: this.section,
                groups: this.groups,
              }).then(function (response) {
                this.submitted = false
                var success = response.data.status == 'success'
                swal({
                  title: response.data.status == 'success' ? "Enregistré" : "Erreur survenue",
                  text: response.data.message,
                  type: response.data.status,
                  allowOutsideClick: false
                }).then(function () {
                  if (success) {
                    window.location.href = "{{ route('surveys-list') }}"
                  } else {
                    $('.submit-btn').prop('disabled', false)
                  }
                });
              }).catch(function (error) {
                console.log(error)
              });
            } else {
              swal({type: 'error', text: "Veuillez remplir tous les champs obligatoires"})
            }
          })
        }
      },
      computed: {
        validateGrpNbr: function () {
          return this.number < 1 || this.number > 100 || this.title == '' || this.model == ''
        },
      },
      mounted: function() {

      },
      directives: {
        focus: {
          inserted: function (el, binding) {
            el.focus()
          }
        }
      }
    })

  </script>
@endsection