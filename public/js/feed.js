$(function() {
    let Feed = {
        selectedFeed: null,
        fromDate: null,
        bindEvents: function () {
            $('.js-add-feed').on('click', function (e) {
                e.preventDefault();

                let urlField = $(this).closest('form').find('input[name="url"]'),
                    data = {
                        _token: application.getCsrfToken(),
                        url: urlField.val()
                    };

                $.post('/feed/add', data, function (response) {
                    window.location.href = response.redirect;
                }).fail(function (xhr) {
                    let responseText = JSON.parse(xhr.responseText);
                    if (responseText.errors.url !== undefined) {
                        urlField.addClass('is-invalid');
                        urlField.next('.invalid-feedback').html(responseText.errors.url);
                    }
                });
            });

            $('.js-open-add-feed-form').on('click', function (e) {
                e.preventDefault();

                $('#modal-add-feed').modal();
            });

            $('.js-open-update-feed-form').on('click', function (e) {
                e.preventDefault();

                let feedId = $(this).closest('li').data('id'),
                    feedUrl = $(this).closest('li').data('url'),
                    form = $('.js-update-feed').closest('form');

                form.find('input[name="feed_id"]').val(feedId);
                form.find('input[name="url"]').val(feedUrl);

                $('#modal-edit-feed').modal();
            });

            $('.js-update-feed').on('click', function (e) {
                e.preventDefault();

                let form = $(this).closest('form'),
                    urlField = form.find('input[name="url"]'),
                    data = {
                        _token: application.getCsrfToken(),
                        id: form.find('input[name="feed_id"]').val(),
                        url: urlField.val()
                    };

                $.post('/feed/update', data, function (response) {
                    $('#modal-edit-feed').modal('hide');
                    $('#modal-updated-feed').modal();
                }).fail(function (xhr) {
                    let responseText = JSON.parse(xhr.responseText);
                    if (responseText.errors.url !== undefined) {
                        urlField.addClass('is-invalid');
                        urlField.next('.invalid-feedback').html(responseText.errors.url);
                    }
                })
            });

            $('.js-load-feed-content').on('click', function (e) {
                e.preventDefault();

                Feed.selectedFeed = $(this).closest('li').data('id');
                Feed.fromDate = null;
                Feed.clearPosts();
                Feed.loadPosts();
            });

            $('.js-remove-feed').on('click', function (e) {
                e.preventDefault();

                let feedId = $(this).closest('li').data('id'),
                    feedUrl = $(this).closest('li').data('url');

                $('#modal-confirm-remove-feed-url').text(feedUrl);
                $('#modal-confirm-remove-feed').modal()
                    .find('button.btn-primary').attr('data-feed-id', feedId);
            });

            $('#modal-confirm-remove-feed').find('button.btn-primary').on('click', function (e) {
                e.preventDefault();

                let data = {
                    _token: application.getCsrfToken(),
                    id: $(this).data('feed-id')
                };

                $.post('/feed/remove', data, function (response) {
                    window.location.href = response.redirect;
                });
            });

            $('.js-load-more-content').on('click', function (e) {
                e.preventDefault();

                Feed.loadPosts();
            });
        },
        loadPosts: function () {
            let data = {
                _token: application.getCsrfToken(),
                feed_id: Feed.selectedFeed,
                from_date: Feed.fromDate || null,
                read: '0'
            };

            $.post('/feed/get_content', data, function (response) {
                let fromDate;
                response.forEach(function (post) {
                    let postHtml = '<li data-id="' + post.id + '">';
                    postHtml += '<span class="date">' + post.created_at + '</span>';
                    postHtml += '<span class="title">' + post.title + '</span>';
                    postHtml += '<span class="description">' + post.description + '</span>';
                    postHtml += '<span class="link"><a href="' + post.permalink + '" target="_blank">Read more</a></link>';
                    postHtml += '</li>';
                    $('.feed-post-list').append(postHtml);

                    fromDate = post.created_at;
                });

                $('.feed-post-list li').not('.read').off('click').on('click', function () {
                    $(this).addClass('read').off('click');
                    let postId = $(this).data('id');
                    Feed.postMarkRead(postId);
                });

                if (fromDate !== undefined) {
                    $('.js-load-more-content').show();
                    Feed.fromDate = fromDate;
                } else {
                    $('.js-load-more-content').hide();
                }
            });
        },
        clearPosts: function () {
            $('.feed-post-list').html('');
        },
        postMarkRead: function (postId) {
            let data = {
                _token: application.getCsrfToken(),
                id: postId
            };
            $.post('/feed/mark_read', data, function (response) {
            });
        }
    };

    Feed.bindEvents();
});