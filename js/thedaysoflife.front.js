$(function () {
  // scroll to top
  $(window).scroll(function () {
    if ($(this).scrollTop() != 0) {
      $('.back-to-top').fadeIn();
    }
    else {
      $('.back-to-top').fadeOut();
    }
  });

  $('.back-to-top').click(function () {
    $('body,html').animate({scrollTop: 0}, 800);
  });

  // slide toggle
  $('.navbar-header .navbar-toggle').click(function () {
    $(".nav-content").toggle("slide");
  });

  // sticky header
  var stickyNavTop = $('.header').offset().top;
  var stickyNav = function () {
    var scrollTop = $(window).scrollTop();
    if (scrollTop > stickyNavTop) {
      $('.header').addClass('sticky');
    } else {
      $('.header').removeClass('sticky');
    }
  };
  stickyNav();
  $(window).scroll(function () {
    stickyNav();
  });
});

$("#top-search-button").live("click", function () {
  search = $("#top-search-text").val();
  if (search != '') {
    link = CONST.SITE_URL + '/search/' + encodeURI(search);
    window.location = link;
  }
});

$("#top-search-text").live("keypress", function (e) {
  if (e.which == 13) {
    search = $("#top-search-text").val();
    if (search != '') {
      link = CONST.SITE_URL + '/search/' + encodeURI(search);
      window.location = link;
    }
  }
});

$("#main-search-button").live("click", function () {
  search = $("#main-search-text").val();
  if (search != '') {
    link = CONST.SITE_URL + '/search/' + encodeURI(search);
    window.location = link;
  }

});

$("#main-search-text").live("keypress", function (e) {
  if (e.which == 13) {
    search = $("#main-search-text").val();
    if (search != '') {
      link = CONST.SITE_URL + '/search/' + encodeURI(search);
      window.location = link;
    }
  }
});

$("#make-day").live("click", function () {
  if (checkInputDate()) {
    if (checkValidDate()) {
      if (checkInput()) {
        ajaxMakeADay();
      }
    }
  }
});

$("a.reply-display").live("click", function () {
  $(".reply-form").remove();
  rep_id = $(this).data("rep-id");
  com_id = $(this).data("com-id");
  text = createReplyForm(rep_id, com_id);
  content_id = "#content-" + com_id;
  comment_id = "#comment-" + com_id;
  $(text).insertAfter(comment_id);
  $(content_id).focus();
  $('.comment-text').autosize();
});

$(".reply-focus").live("click", function () {
  $("#content").focus();
});
$("#submit").live("click", function () {
  if (checkCommentInput()) {
    ajaxMakeAComment();
  }
});

$("button.reply-submit").live("click", function () {
  rep_id = $(this).data("rep-id");
  com_id = $(this).data("com-id");
  if (checkReplyInput(com_id)) {
    ajaxMakeAReply(rep_id, com_id);
  }
});

$("a.like-day").live("click", function () {
  id = $(this).data("id");
  like = $(this).data("like");
  html = '<i class="icon"></i>' + numberWithCommas(parseInt(like) + 1);
  $(this).parent().addClass('liked').prop('title', 'Liked').html(html);
  ajaxLikeADay(id);
});

$("a.like-com").live("click", function () {
  id = $(this).data("id");
  like = $(this).data("like");
  html = '<i class="icon"></i>' + numberWithCommas(parseInt(like) + 1);
  $(this).parent().addClass('liked').prop('title', 'Liked').html(html);
  ajaxLikeAComment(id);
});

$("a.dislike-com").live("click", function () {
  id = $(this).data("id");
  dislike = $(this).data("dislike");
  html = '<i class="icon"></i>' + numberWithCommas(parseInt(dislike) + 1);
  $(this).parent().addClass('disliked').prop('title', 'Disliked').html(html);
  ajaxDislikeAComment(id);
});

$("#show-more").live("click", function () {
  action = $(this).attr("class");
  if (action == 'show-more') {
    from = $('#slide-show>li.item').length;
    order = $(this).attr("order-tag");
    ajaxShowDay(from, order);
  }
});

$("#show-calendar").live("click", function () {
  action = $(this).attr("class");
  if (action == 'show-more') {
    from = $('div.calendar-div').length;
    ajaxShowCalendar(from);
  }
});

$("#show-picture").live("click", function () {
  action = $(this).attr("class");
  if (action == 'show-more') {
    from = $('#picture>li').length;
    ajaxShowPicture(from);
  }
});

$("#search-more").live("click", function () {
  action = $(this).attr("class");
  if (action == 'show-more') {
    search = $('#main-search-text').val();
    from = $('#slide-show>li.item').length;
    ajaxSearchMore(search, from);
  }
});

/**
 * Add new day
 */
function ajaxMakeADay() {
  content = $("#div-day-content").find("select[name], textarea[name], input[name]").serialize();
  info = $("#div-author-info").find("select[name], textarea[name], input[name]").serialize();
  photos = getIDs();
  data = content + "&" + info + "&" + $.param({"photos": photos});
  callback = processMakeADay;
  jennifer.ajaxAction({"action": "ajaxMakeADay", "controller": "ControllerFront"}, data, false, "#ajax-loader", false, callback);
}

/**
 * Add new comment
 */
function ajaxMakeAComment() {
  day_id = $("#day-id").val();
  content = $("#div-comment-content").find("select[name], textarea[name], input[name]").serialize();
  data = content + '&' + $.param({"day_id": day_id});
  callback = processMakeAComment;
  jennifer.ajaxAction({
               "action":     "ajaxMakeAComment",
               "controller": "ControllerFront"
             }, data, false, "#ajax-loader", false, callback);
}

/**
 * Add new reply
 * @param rep_id
 * @param com_id
 */
function ajaxMakeAReply(rep_id, com_id) {
  day_id = $("#day-id").val();
  form_id = "#reply-form-" + com_id;
  name_id = "#name-" + com_id;
  rep_name = $(name_id).html();
  content = $(form_id).find("select[name], textarea[name], input[name]").serialize();
  data = content + "&" + $.param({"day_id": day_id, "com_id": com_id, "rep_id": rep_id, "rep_name": rep_name});
  loader = "#ajax-loader-" + com_id;
  callback = processMakeAReply;
  jennifer.ajaxAction({"action": "ajaxMakeAReply", "controller": "ControllerFront"}, data, true, loader, false, callback);
}

/**
 * Like day
 * @param id
 */
function ajaxLikeADay(id) {
  jennifer.ajaxAction({
               "action":     "ajaxLikeADay",
               "controller": "ControllerFront"
             }, $.param({"id": id}), false, false, false, false);
}

/**
 * Like comment
 * @param id
 */
function ajaxLikeAComment(id) {
  jennifer.ajaxAction({
               "action":     "ajaxLikeAComment",
               "controller": "ControllerFront"
             }, $.param({"id": id}), false, false, false, false);
}

/**
 * Dislike day
 * @param id
 */
function ajaxDislikeAComment(id) {
  jennifer.ajaxAction({
               "action":     "ajaxDislikeAComment",
               "controller": "ControllerFront"
             }, $.param({"id": id}), false, false, false, false);
}

/**
 * show more days
 * @param from
 * @param order
 */
function ajaxShowDay(from, order) {
  callback = processDays;
  jennifer.ajaxAction({"action": "ajaxShowDay", "controller": "ControllerFront"}, $.param({"from": from, "order": order}), false,
             "#show-more", false, callback);
}

/**
 * show calendar
 * @param from
 */
function ajaxShowCalendar(from) {
  callback = processCalendar;
  jennifer.ajaxAction({
                        "action":     "ajaxShowCalendar",
                        "controller": "ControllerFront"
                      }, $.param({"from": from}), false, "#show-calendar", false, callback);
}

/**
 * show picture
 * @param from
 */
function ajaxShowPicture(from) {
  callback = processPicture;
  jennifer.ajaxAction({
               "action":     "ajaxShowPicture",
               "controller": "ControllerFront"
             }, $.param({"from": from}), false, "#show-picture", false, callback);
}

/**
 * show more search result
 * @param search
 * @param from
 */
function ajaxSearchMore(search, from) {
  callback = processSearchMore;
  jennifer.ajaxAction({"action": "ajaxSearchMore", "controller": "ControllerFront"},
             $.param({"from": from, "search": search}), false, "#search-more", false, callback);
}

/**
 * process returned data when add day
 * @param data
 */
function processMakeADay(data) {
  if (data.status = "success") {
    link = CONST.LIST_URL + data.id + "/" + data.day + data.month +
           data.year + '-' + data.slug + CONST.LIST_EXT;
    window.location = link;
  }
}

/**
 * process returned data when comment
 * @param data
 */
function processMakeAComment(data) {
  result = data.result;
  if (result == true) {
    rep_id = data.day_id;
    content = data.content;
    $("#content").val('');
    $("#username").val('');
    $("#email").val('');
    $("#location").val('');
    updateCount(rep_id);
    $(content).hide().appendTo("#comment-container").fadeIn(CONST.COM_FADE);
  }
}

/**
 * process returned data when reply
 * @param data
 */
function processMakeAReply(data) {
  result = data.result;
  if (result == true) {
    com_id = data.com_id;
    content = data.content;
    comment_id = "#comment-" + com_id;
    $(".reply-form").remove();
    $(content).hide().insertAfter(comment_id).fadeIn(CONST.COM_FADE);
  }
}

/**
 * process returned data when show more day
 * @param data
 */
function processDays(data) {
  loader = "#show-more";
  html = $(data).hide();
  count = html.filter('li.item').length;
  if (count > 0) {
    $("#slide-show").append(html);
    wookmarkHandle();
    html.filter('li.item').fadeIn(CONST.LIST_FADE);
    from = $('#slide-show>li.item').length;
    if (count >= CONST.NUM_PER_PAGE) {
      $(loader).attr('data', from).html('+ Load More Days').fadeIn(CONST.LOADER_FADE);
    }
    else {
      $(loader).attr('data', from).attr('action', 'no-more').addClass('no-more').html('No more to show')
               .fadeIn(CONST.LOADER_FADE);
    }
  }
  else {
    $(loader).attr('action', 'no-more').addClass('no-more').html('No more to show').fadeIn(CONST.LOADER_FADE);
  }
}

/**
 * process returned data when show more calendar
 * @param data
 */
function processCalendar(data) {
  loader = "#show-calendar";
  html = $(data).hide();
  count = html.filter('div.calendar-div').length;
  if (count > 0) {
    $("#calendar-container").append(html);
    html.filter('div.calendar-div').fadeIn(CONST.LIST_FADE);
    from = $('div.calendar-div').length;
    if (count >= CONST.NUM_CALENDAR) {
      $(loader).attr('data', from).html('+ Load More Days').fadeIn(CONST.LOADER_FADE);
    }
    else {
      $(loader).attr('data', from).attr('action', 'no-more').addClass('no-more').html('No more to show')
               .fadeIn(CONST.LOADER_FADE);
    }
  }
  else {
    $(loader).attr('action', 'no-more').addClass('no-more').html('No more to show').fadeIn(CONST.LOADER_FADE);
  }
}

/**
 * process returned data when show more pictures
 * @param data
 */
function processPicture(data) {
  loader = "#show-picture";
  html = $(data).hide();
  count = html.filter('li').length;
  if (count > 0) {
    $("#picture").append(html);
    html.filter('li').fadeIn(CONST.LIST_FADE);
    from = $('#picture>li').length;
    if (count >= CONST.NUM_PICTURE) {
      $(loader).attr('data', from).html('+ Load More Photos').fadeIn(CONST.LOADER_FADE);
    }
    else {
      $(loader).attr('data', from).attr('action', 'no-more').addClass('no-more').html('No more to show')
               .fadeIn(CONST.LOADER_FADE);
    }
  }
  else {
    $(loader).attr('action', 'no-more').addClass('no-more').html('No more to show').fadeIn(CONST.LOADER_FADE);
  }
}

/**
 * process returned data when show more search result
 * @param data
 */
function processSearchMore(data) {
  loader = "#search-more";
  html = $(data).hide();
  count = html.filter('li.item').length;
  if (count > 0) {
    $("#slide-show").append(html);
    wookmarkHandle();
    html.filter('li.item').fadeIn(CONST.LIST_FADE);
    from = $('#slide-show>li.item').length;
    if (count >= CONST.NUM_PER_PAGE) {
      $(loader).attr('data', from).html('+ Load More Days').fadeIn(CONST.LOADER_FADE);
    }
    else {
      $(loader).attr('data', from).attr('action', 'no-more').addClass('no-more').html('No more to show')
               .fadeIn(CONST.LOADER_FADE);
    }
  }
  else {
    $(loader).attr('action', 'no-more').addClass('no-more').html('No more to show').fadeIn(CONST.LOADER_FADE);
  }
}

/**
 * Reload page
 */
function reloadPage() {
  window.location.reload();
}

/**
 * Scroll to element
 * @param id
 */
function scrollTo(id) {
  $('html,body').animate({scrollTop: $(id).offset().top}, 'slow');
}

/**
 * Get XmlHttpObject
 * @returns {*}
 * @constructor
 */
function GetXmlHttpObject() {
  var xmlHttp = null;
  try {
    // Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch (e) {
    // Internet Explorer
    try {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
  return xmlHttp;
}

/**
 * @param c_name
 * @param value
 * @param expiredays
 */
function setCookie(c_name, value, expiredays) {
  path = '/';
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + expiredays);
  document.cookie = c_name + "=" + escape(value) +
                    ( ( path ) ? ";path=" + path : "" ) +
                    ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
}

/**
 * @param c_name
 * @returns {string}
 */
function getCookie(c_name) {
  if (document.cookie.length > 0) {
    c_start = document.cookie.indexOf(c_name + "=");
    if (c_start != -1) {
      c_start = c_start + c_name.length + 1;
      c_end = document.cookie.indexOf(";", c_start);
      if (c_end == -1) {
        c_end = document.cookie.length;
      }
      return unescape(document.cookie.substring(c_start, c_end));
    }
  }
  return "";
}

/**
 * @param email
 * @returns {boolean}
 */
function validateEmail(email) {
  var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
  return pattern.test(email);
}

/**
 * Get ids of photos
 * @returns {string|*}
 */
function getIDs() {
  var sortedIDs = $("#sortable").sortable("toArray");
  return sortedIDs.toString();
}

/**
 * @param x
 * @returns {string}
 */
function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 *
 * @param frame
 * @returns {*}
 */
function getDoc(frame) {
  var doc = null;
  try {
    if (frame.contentWindow) {
      doc = frame.contentWindow.document;
    }
  }
  catch (err) {
  }

  if (doc) {
    return doc;
  }

  try {
    doc = frame.contentDocument ? frame.contentDocument : frame.document;
  }
  catch (err) {
    doc = frame.document;
  }
  return doc;
}

/**
 * Hanlde wookmark after page load
 */
function wookmarkHandle() {
  $('#slide-show').imagesLoaded(function () {
    var options = {
      align:         'left',
      itemWidth:     300,
      autoResize:    true,
      container:     $('#slide-show'),
      offset:        20,
      outerOffset:   0,
      flexibleWidth: '50%'
    };
    var handler = $('#slide-show li');
    var $window = $(window);
    $window.resize(function () {
      var windowWidth = $window.width(),
        newOptions = {flexibleWidth: '50%'};
      if (windowWidth < 1024) {
        newOptions.flexibleWidth = '100%';
      }
      handler.wookmark(newOptions);
    });
    handler.wookmark(options);
  });
}

/**
 *
 * @returns {boolean}
 */
function checkInput() {
  if ($("#title").val().length < 2) {
    $("#ajax-loader").html("Title or What happened on that day ?");
    $("#title").focus();
    return false;
  }
  else if ($("#content").val().length < 10) {
    $("#ajax-loader").html("Share your memory of that day.");
    $("#content").focus();
    return false;
  }
  else if ($("#username").val().length < 1) {
    $("#ajax-loader").html("Please enter your name.");
    $("#username").focus();
    return false;
  }
  else if ($("#email").val() == "" || validateEmail($("#email").val()) == false) {
    $("#ajax-loader").html("Please enter a valid email.");
    $("#email").focus();
    return false;
  }
  else {
    $("#ajax-loader").html("");
    return true;
  }
}

/**
 *
 * @returns {boolean}
 */
function checkInputDate() {
  var d = parseInt($("#day").val(), 10);
  var m = parseInt($("#month").val(), 10);
  var y = parseInt($("#year").val(), 10);
  if (m == 0) {
    $("#ajax-loader").html("Please select Month.").show("slow");
    $("#month").focus();
    return false;
  }
  else if (y == 0) {
    $("#ajax-loader").html("Please select Year.").show("slow");
    $("#year").focus();
    return false;
  }
  else {
    $("#check").html("");
    return true;
  }
}

/**
 *
 * @returns {boolean}
 */
function checkValidDate() {
  var d = parseInt($("#day").val(), 10);
  var m = parseInt($("#month").val(), 10);
  var y = parseInt($("#year").val(), 10);
  if (d == 0) {
    $("#check").html("");
    return true;
  }
  else if (d > 0 && m > 0 && y > 0) {
    var date = new Date(y, m - 1, d);
    if (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d) {
      $("#check").html("");
      return true;
    }
    else {
      $("#ajax-loader").html("Day does not exists.").show("slow");
      return false;
    }
  }
}

/**
 *
 * @returns {boolean}
 */
function checkCommentInput() {
  if ($("#content").val().length < 1) {
    $("#content").focus();
    return false;
  }
  if ($("#username").val().length < 1) {
    $("#username").focus();
    return false;
  }
  else if ($("#email").val() == "" || validateEmail($("#email").val()) == false) {
    $("#email").focus();
    return false;
  }
  else {
    return true;
  }
}

/**
 *
 * @param rep_id
 * @param com_id
 * @returns {string|*}
 */
function createReplyForm(rep_id, com_id) {
  form_id = "reply-form-" + com_id;
  content_id = "content_" + com_id;
  username_id = "username_" + com_id;
  email_id = "email_" + com_id;
  loader_id = "ajax-loader-" + com_id;

  form = '<div class="reply-form form-contact form-group" id="' + form_id + '">'
         + '<div class="media-body">'
         + '<div class="form-contact">'
         + '<div class="form-group col-md-12">'
         + '<textarea class="form-control comment-text" id="' + content_id + '" name="content"></textarea>'
         + '</div>'
         + '</div>'
         + '<div class="form-group col-md-4">'
         + '<div class="icon">'
         + '<label class="full-name sr-only">Full Name</label>'
         + '</div>'
         + '<input type="text" class="full-name form-control" id="' + username_id + '" name="username" placeholder="Your full name" >'
         + '</div>'
         + '<div class="form-group col-md-4 col-sm-4">'
         + '<div class="icon">'
         + '<label class="email sr-only">Email address</label>'
         + '</div>'
         + '<input type="email" class="email form-control" id="' + email_id + '" name="email" placeholder="Your email address*">'
         + '</div>'
         + '<div class="form-group col-sm-4 registering">'
         + '<button type="button" class="btn btn-primary reply-submit" data-com-id="' + com_id + '" data-rep-id="' + rep_id + '">Submit</button>'
         + '<span id="' + loader_id + '"></span>'
         + '</div>'
         + '</div>'
         + '</div>';
  return form;
}

/**
 *
 * @param com_id
 * @returns {boolean}
 */
function checkReplyInput(com_id) {
  content_id = "#content_" + com_id;
  username_id = "#username_" + com_id;
  email_id = "#email_" + com_id;
  if ($(content_id).val().length < 1) {
    $(content_id).focus();
    return false;
  }
  if ($(username_id).val().length < 1) {
    $(username_id).focus();
    return false;
  }
  else if ($(email_id).val() == "" || validateEmail($(email_id).val()) == false) {
    $(email_id).focus();
    return false;
  }
  else {
    return true;
  }
}

/**
 *
 * @param id
 */
function updateCount(id) {
  com_id = "#count-" + id;
  html = $(com_id).html().replace('<i class="icon"></i>', '').trim();
  count = parseInt(html) + 1;
  $(com_id).html('<i class="icon"></i>' + count);
}