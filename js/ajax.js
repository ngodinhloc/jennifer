/**
 * @param actionPara object {"action":action, "controller":controller}
 * @param para string $.para({"name":value})
 * @param json true|false
 * @param loader string id of the loader
 * @param containerPara object {"container" : container_id, "act": "replace|append"]
 * @param callback function
 */
function ajaxAction(actionPara, para, json, loader, containerPara, callback) {
  var data = para + "&" + $.param(actionPara) + "&" + $.param({"json": json});
  if (loader) {
    $(loader).html(AJAX_LOADER);
  }
  $.ajax({
    url:     CONST.CONTROLLER_URL,
    type:    "POST",
    cache:   false,
    data:    data,
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