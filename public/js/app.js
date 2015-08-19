$(document).ready(function() {


  $('.upvote-post').click(function() {
    var slug = $(this).attr('data-slug');
    var hub = $(this).attr('data-hub');
    var _token = $(this).attr('data-csrf');
    $.post('/hub/' + hub + '/' + slug + '/upvote', {slug: slug, title: hub, "_token": _token}, function(data) {
      if (data.success) {
        $(self).closest('.upvotes').val() = parseInt($(self).closest('.upvotes').val()) + 1;
      } else if (data.error) {
        alert(data.error);
      }

    })
  })
  $('.downvote-post').click(function() {
    var self = this;
    var slug = $(this).attr('data-slug');
    var hub = $(this).attr('data-hub');
    var _token = $(this).attr('data-csrf');
    $.post('/hub/' + hub + '/' + slug + '/downvote', {slug: slug, title: hub, "_token": _token}, function(data) {
      if (data.success) {
        $(self).closest('.downvotes').val(parseInt($(self).closest('.downvotes').val()) + 1);
      } else if (data.error) {
        alert(data.error);
      }
    })
  })

  $('.sticky-post').click(function() {
    var self = this;
    var post_id = $(this).attr('data-post-id');
    var _token = $(this).attr('data-token');
    $.post('/sticky/' + post_id, { id: post_id}, function(data) {
      if (data.success) {
        $(self).css('color', '#27ae60');
      }
    })
  })
  $('.delete-post').click(function() {
    var self = this;
    var post_id = $(this).attr('data-post-id');
    $.post("/post/delete/" + post_id, { id: post_id}, function(data) {
      if (data.success) {
        location.replace('/home');
      } else if (data.error) {
        alert(data.error);
      }
    })
  })
})