/**
 * @param actionPara object {"action":action, "controller":controller}
 * @param para object $.para({"name":value})
 * @param loader string
 * @param containerPara array {"container" : container_id, "act": "replace|append"]
 * @param callback function
 */
function ajaxAction(actionPara, para, loader, containerPara, callback) {
  para = para + "&" + $.param(actionPara);
  if (loader) {
    $(loader).html(AJAX_LOADER);
  }
  $.ajax({
    url:     CONST.CONTROLLER_URL,
    type:    "POST",
    cache:   false,
    data:    para,
    success: function (data, textStatus, jqXHR) {
      if (loader) {
        $(loader).html('');
      }
      if (callback) {
        callback(data);
        return;
      }
      if (containerPara) {
        container = containerPara.container;
        act = containerPara.act;
        if (act == "replace") {
          $(container).html(data);
        }
        if (act == "append") {
          $(container).append(data);
        }
      }
    },
    error:   function (jqXHR, textStatus, errorThrown) {
    }
  });
}