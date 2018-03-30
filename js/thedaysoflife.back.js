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
  jennifer.ajaxAction({"action": "ajaxUpdateInfo", "controller": "ControllerBack"}, data, false, "#ajax-loader",
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
  jennifer.ajaxAction({
    "action":     "ajaxPostToFacebook",
    "controller": "ControllerFacebook"
  }, data, false, loader, false, callback);
}

function processPostToFacebook(data) {
  $("#fb-post-" + data.id).html(data.data);
  if (data.status == "OK") {
    $("#fb-type-" + data.id).addClass("fb-posted");
  }
}
/**
 * Print list
 * @param page
 */
function ajaxPrintDay(page) {
  data = $.param({page: page});
  jennifer.ajaxAction({"action": "ajaxPrintDay", "controller": "ControllerBack"}, data, false, "#loader",
    {"container": "#print-list", "act": "replace"}, false);
}

/**
 * Remove unused photos
 */
function ajaxRemoveUnusedPhoto() {
  jennifer.ajaxAction({"action": "ajaxRemoveUnusedPhoto", "controller": "ControllerBack"},
    false, false, "#remove-photo-result", {"container": "#remove-photo-result", "act": "replace"}, false);
}

/**
 * Check database
 */
function ajaxCheckDatabase() {
  act = $('input[name=checkdb]:checked').val();
  jennifer.ajaxAction({"action": "ajaxCheckDatabase", "controller": "ControllerBack"},
    $.param({"act": act}), false, "#check-database-result", {
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
  jennifer.ajaxAction({
    "action":     "ajaxUpdateADay",
    "controller": "ControllerBack"
  }, data, false, "#ajax-loader", false, callback);
}

/**
 * @param data
 */
function processUpdateADay(data) {
  if (data.status = "success") {
    link = CONST.LIST_URL + data.id + "/" + data.day + data.month + data.year + "-" + data.slug + CONST.LIST_EXT;
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
  jennifer.ajaxAction({"action": "ajaxRemoveADay", "controller": "ControllerBack"},
    $.param({"id": id}), loader, false, callback);
}
/**
 * @param data
 */
function processRemoveADay(data) {
  if (data.status = "success") {
    $('#row-' + data.id).remove();
  }
}