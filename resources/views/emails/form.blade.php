
<div class="content">
    <input type="hidden" name="id" value="{{ isset($email->id) ? $email->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="date" class="control-label">Emetteur <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="sender" placeholder="contact@lycom.ma" value="contact@lycom.ma" required="" value="{{ $email->sender ? $email->sender : '' }}">
    </div>
    <div class="form-group">
        <label for="subject" class="control-label">Object <span class="asterisk">*</span></label>
        <input type="text" class="form-control" name="subject" placeholder="" required="" value="{{ $email->subject ? $email->subject : ''}}">
    </div>
    <div class="form-group">
        <label for="message" class="control-label">Message <span class="asterisk">*</span></label>
        <textarea id="compose-textarea" name="message" class="form-control" rows="10" required="">{{ $email->message ? $email->message : '' }}</textarea>
    </div>
</div>

<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
