$("#update-info").on("click", function () {
  ajaxUpdateInfo();
});

$(".fb-post-button").live("click", function () {
  id = $(this).data("id");
  type = $("#fb-type-" + id).val();
  ajaxPostToFacebook(id, type);
});

$(".page-nav").live("click", function () {
  page = $(this).data("page");
  ajaxPrintDay(page);
});

$("#remove-photo").live("click", function () {
  ajaxRemoveUnusedPhoto();
});

$("#check-database").live("click", function () {
  ajaxCheckDatabase();
});

$("#update-day").live("click", function () {
  if (checkInput()) {
    ajaxUpdateADay();
  }
});

$("a.remove-day").live("click", function () {
  id = $(this).data("day-id");
  bootbox.confirm('Are you sure you want to delete this day?', function (response) {
    if (response) {
      ajaxRemoveADay(id);
    }
  });
});

/**
 * Update info: about, privacy
 */
function ajaxUpdateInfo() {
  CKEDITOR.instances['content'].updateElement();
  data = $("#div-edit-info").find("select[name], textarea[name], input[name]").serialize();
  ajaxAction({"action": "ajaxUpdateInfo", "controller": "ControllerAdmin"}, data, "#ajax-loader",
             {"container": "#ajax-loader", "act": "replace"}, false);
}

/**
 * Post day to facebook
 * @param id
 * @param type
 */
function ajaxPostToFacebook(id, type) {
  loader = "#fb-post-" + id;
  data = $.param({"id": id, "type": type});
  callback = processPostToFacebook;
  ajaxAction({"action": "ajaxPostToFacebook", "controller": "ControllerFacebook"}, data, loader, false, callback);
}

function processPostToFacebook(data) {
  getData = $.parseJSON(data);
  $("#fb-post-" + getData.id).html(getData.data);
  if (getData.status == "OK") {
    $("#fb-type-" + getData.id).addClass("fb-posted");
  }
}
/**
 * Print list
 * @param page
 */
function ajaxPrintDay(page) {
  data = $.param({page: page});
  ajaxAction({"action": "ajaxPrintDay", "controller": "ControllerAdmin"}, data, "#loader",
             {"container": "#print-list", "act": "replace"}, false);
}

/**
 * Remove unused photos
 */
function ajaxRemoveUnusedPhoto() {
  ajaxAction({"action": "ajaxRemoveUnusedPhoto", "controller": "ControllerAdmin"},
             false, "#remove-photo-result", {"container": "#remove-photo-result", "act": "replace"}, false);
}

/**
 * Check database
 */
function ajaxCheckDatabase() {
  act = $('input[name=checkdb]:checked').val();
  ajaxAction({"action": "ajaxCheckDatabase", "controller": "ControllerAdmin"},
             $.param({"act": act}), "#check-database-result", {
               "container": "#check-database-result",
               "act":       "replace"
             }, false);
}

/**
 * Update day
 */
function ajaxUpdateADay() {
  CKEDITOR.instances['content'].updateElement();
  content = $("#edit-day").find("select[name], textarea[name], input[name]").serialize();
  photos = getIDs();
  data = content + "&" + $.param({"photos": photos})
  callback = processUpdateADay;
  ajaxAction({"action": "ajaxUpdateADay", "controller": "ControllerAdmin"}, data, "#ajax-loader", false, callback);
}

/**
 * @param data
 */
function processUpdateADay(data) {
  getData = $.parseJSON(data);
  if (getData.status = "success") {
    link = CONST.LIST_URL + getData.id + "/" + getData.day + getData.month + getData.year + "-" + getData.slug + CONST.LIST_EXT;
    $("#ajax-loader").html('Update successed. Clik <a target="_blank" href="' + link + '">here to view</a>');
    //window.location = link;
  }
}

/**
 * @param id
 */
function ajaxRemoveADay(id) {
  loader = "#remove-day-" + id;
  callback = processRemoveADay;
  ajaxAction({"action": "ajaxRemoveADay", "controller": "ControllerAdmin"},
             $.param({"id": id}), loader, false, callback);
}
/**
 * @param data
 */
function processRemoveADay(data) {
  getData = $.parseJSON(data);
  if (getData.status = "success") {
    $('#row-' + getData.id).remove();
  }
}