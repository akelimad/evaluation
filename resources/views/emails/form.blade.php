
<div class="content">
    <input type="hidden" name="id" value="{{ isset($email) ? $email->id : null }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="form-group">
            <div class="col-md-6">
                <label for="date" class="control-label">Emetteur <span class="asterisk">*</span></label>
                <input type="text" class="form-control" name="sender" placeholder="contact@lycom.ma" required="" value="{{ isset($email) ? $email->sender : '' }}">
            </div>
            <div class="col-md-6">
                <label for="name" class="control-label">Nom <span class="asterisk">*</span></label>
                <input type="text" class="form-control" name="name" placeholder="Lycom" required="" value="{{ isset($email) ? $email->name : '' }}">
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="subject" class="control-label">Object <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="subject" placeholder="Object" required="" value="{{ isset($email) ? $email->subject : ''}}">
    </div>
    <div class="form-group">
        <p><label class="control-label">Les variables disponibles sont : </label></p>
        <label>@{{user_name}}</label>  -
        <label>@{{date_limit}}</label>  -
        <label>@{{email}}</label>  -
        <label>@{{password}}</label>  
    </div>
    <div class="form-group">
        <label for="message" class="control-label">Message <span class="asterisk">*</span></label>
        <textarea id="compose-textarea" name="message" class="form-control" rows="10" required="" placeholder="Votre message">{{ isset($email) ? $email->message : '' }}</textarea>
    </div>
</div>

<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5({
        toolbar: {
            "image": false,
            "html": true,
        },
    });
  });
</script>
