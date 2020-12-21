jQuery(function($) {
  $(document).on("click", ".media-select", function(e) {
    var frame;
    e.preventDefault();

    if (frame) {
      frame.open();
      return ;
    }

    frame = wp.media({
      title: mediaSelect.selectOrUploadMedia,
      multiple: false
    });

    frame.on("select", function() {
      var id = e.target.id.replace("-button", "");
      var attachment = frame.state().get("selection").first().toJSON();
      $(document.getElementById(id)).val(attachment.id);
      $(document.getElementById(id + "-preview")).attr("src", attachment.url);
    });

    frame.open();
  });
});
