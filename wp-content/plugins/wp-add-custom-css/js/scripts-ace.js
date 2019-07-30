(function ($) {
  'use strict';

  $(document).ready(function(){
    var targetId = "single_custom_css";
    if ( $('#main_custom_css').length > 0 )
      targetId = "main_custom_css";

    var editor = ace.edit(targetId + "_ace");
    var textarea = $('#' + targetId);

    if ( $('.wpacc_editor_dark').length > 0 )
      editor.setTheme("ace/theme/tomorrow_night");

    editor.session.setMode("ace/mode/css");
    editor.setOptions({
      fontSize: 14,
      tabSize: 2
    });

    editor.getSession().setValue(textarea.val());
    editor.getSession().on('change', function(){
      textarea.val(editor.getSession().getValue());
    });
  });

})(jQuery);
