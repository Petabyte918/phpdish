'use strict';

import 'module/common.js';
import Util from '../modules/util.js';
import store from 'store';
import SocialShare from 'social-share-button.js';
import lockButton from '../modules/button-lock.js';
import Editor from '../modules/md-editor/editor.js';
import CodeMirrorEditor from '../modules/md-editor/codemirror-editor.js';
import hljs from 'highlight.js';
import AjaxTab from '../modules/ajaxtab.js';
import 'selectize';
import {FollowUserIntialization, FollowThreadIntialization} from "../modules/actions";

//话题列表页
//AjaxTab
new AjaxTab($('[data-pjax-container]'), {
    container: '#list-container',
    loader: '#loader',
    before: (container) => {
        Util.htmlPlaceholder(container);
    },
    success: (container) => {
        new FollowUserIntialization(container);
        new FollowThreadIntialization(container);
    }
});

//话题详情页
(function($){

    //分享
    new SocialShare('.social-share-container', {
        'theme': 'default'
    });
    //代码高亮
    $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
    });

    (function(){
        //话题操作
        const $topicAction =  $('[data-role="topic-action"]');
        const $removeAction = $topicAction.find('[data-action="remove"]');
        const $recommendAction = $topicAction.find('[data-action="recommend"]');
        const $topAction = $topicAction.find('[data-action="stick-top"]');
        const $voteAction = $topicAction.find('[data-action="vote"]');

        const buttonLock = lockButton($removeAction);
        $removeAction.on('click', function(){
            if (buttonLock.isDisabled()) {
                return false;
            }
            buttonLock.lock();
            Util.dialog.confirm(Translator.trans('topic.confirm_remove_the_topic')).then(() => {
                Util.request('topic.delete', window.topicId).done(() => {
                    Util.dialog.message(Translator.trans('topic.topic_have_been_remove')).flash(2, () => {
                        location.href = '/';
                    });
                }).fail((response) => {
                    Util.dialog.message(response.responseJSON.error).flash(3);
                }).always(()  => {
                    buttonLock.release();
                });
            }, () => {
                buttonLock.release();
            });
        });
        //推荐位
        const recommendButtonLock = lockButton($recommendAction);
        $recommendAction.on('click', function(){
            if (recommendButtonLock.isDisabled()) {
                return false;
            }
            recommendButtonLock.lock();
            const isRecommended = $recommendAction.data('recommend');
            let message =  Translator.trans(isRecommended ? 'topic.confirm_cancel_recommend_the_topic' : 'topic.confirm_recommend_the_topic');

            Util.dialog.confirm(message).then(() => {
                Util.request('topic.toggleRecommend', window.topicId).done((response) => {
                    let message = Translator.trans(response.is_recommended ? 'topic.topic_has_been_recommend' : 'topic.topic_has_been_cancel_recommend');
                    Util.dialog.message(message).flash(2, () => {
                        location.reload();
                    });
                }).fail((response) => {
                    Util.dialog.message(response.responseJSON.error).flash(3);
                }).always(()  => {
                    recommendButtonLock.release();
                });
            }, () => {
                recommendButtonLock.release();
            });
        });
        //置顶位
        const topButtonLock = lockButton($topAction);
        $topAction.on('click', function(){
            if (topButtonLock.isDisabled()) {
                return false;
            }
            topButtonLock.lock();
            const isTop = $topAction.data('is-top');
            let message =  Translator.trans(isTop ? 'topic.confirm_cancel_set_top_the_topic' : 'topic.confirm_set_top_the_topic');

            Util.dialog.confirm(message).then(() => {
                Util.request('topic.toggleTop', window.topicId).done((response) => {
                    let message = Translator.trans(response.is_top ? 'topic.topic_has_been_set_top' : 'topic.topic_has_been_cancel_set_top');
                    Util.dialog.message(message).flash(2, () => {
                        location.reload();
                    });
                }).fail((response) => {
                    Util.dialog.message(response.responseJSON.error).flash(3);
                }).always(()  => {
                    topButtonLock.release();
                });
            }, () => {
                topButtonLock.release();
            });
        });

        //投票
        const voteButtonLock = lockButton($voteAction);
        $voteAction.on('click', function(){
            if (voteButtonLock.isDisabled()) {
                return false;
            }
            voteButtonLock.lock();
            Util.request('topic.vote', window.topicId).done((response) => {
                const $number = $voteAction.find('.number');
                $number.html(response.vote_count);
                if (response.vote_count > 0) {
                    $number.removeClass('hidden');
                } else {
                    $number.addClass('hidden');
                }
                //已经投票的，变成可投票状态
                if (response.is_voted) {
                    $voteAction.find('.fa').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
                    $voteAction.data('voted', true);
                    //加一特效
                    const $increase = $('<div class="one-increase">+1</div>');
                    $increase.insertBefore($voteAction);
                    $increase.addClass('fadeOutUp animated');
                } else {
                    $voteAction.find('.fa').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
                    $voteAction.data('voted', false);
                }
            }).fail((response) => {
                Util.dialog.message(response.responseJSON.error).flash(3);
            }).always(()  => {
                voteButtonLock.release();
            });
        });
    })($);



    //回复窗口
    (function(){
        const $replyTopic = $('#reply-topic');
        const $addReplyForm = $('#add-reply-form');
        const $repliesPanel = $('#reply-list');
        let editor;
        //form 表单
        if ($addReplyForm.length > 0) {
            const $replyBody = $('#reply_original_body');
            const $preview = $replyTopic.find('[data-action="preview"]');
            const $previewPanel = $replyTopic.find('[data-role="preview-panel"]');
            const $submitBtn = $addReplyForm.find('[data-role="submit-form"]');
            editor = new Editor($replyBody, $preview, $previewPanel);
            const replyTopicLock = lockButton($submitBtn);
            //添加回复表单提交
            $addReplyForm.on('submit', function(){
                if(replyTopicLock.isDisabled()){
                    return false;
                }
                let body = editor.getContent();
                if(body.length === 0){
                    Util.dialog.message(Translator.trans('ui.please_fill_in_content')).flash();
                    return false;
                }
                replyTopicLock.lock();
                Util.request('topic.addReply', window.topicId, $addReplyForm).success(function(response){
                    $replyBody.val('');
                    Util.dialog.message(Translator.trans('post.reply_success')).flash(() => location.reload());
                }).complete(function(){
                    replyTopicLock.release();
                });
                return false;
            });
        }

        //Reply list
        $repliesPanel.find('[data-role="reply"]').each(function(){
            const $this = $(this);
            const replyId = $this.data('reply-id');
            const username = $this.data('username');
            //回复层主
            if (editor) {
                $this.find('[data-action="mention"]').on('click', function(){
                    editor.appendContent(`@${username} `);
                    Util.goHash('#add-reply-form');
                });
            }
            //删除回复
            const $removeAction = $this.find('[data-action="remove"]');
            const buttonLock = lockButton($removeAction);
            $removeAction.on('click', function(){
                if (buttonLock.isDisabled()) {
                    return false;
                }
                buttonLock.lock();
                Util.dialog.confirm(Translator.trans('topic.confirm_remove_the_comment')).then(() => {
                    Util.request('topicReply.delete', replyId).done(() => {
                        $this.fadeOut();
                    }).fail((response) => {
                        Util.dialog.message(response.responseJSON.error).flash(3);
                    }).always(()  => {
                        buttonLock.release();
                    });
                }, () => {
                    buttonLock.release();
                });
            });
            //点赞
            const $voteAction = $this.find('[data-action="vote"]');
            const voteLock = lockButton($voteAction);
            const $icon = $voteAction.find('.fa');
            $voteAction.on('click', function(){
                if (voteLock.isDisabled()) {
                    return false;
                }
                voteLock.lock();
                $icon.removeClass('wobble animated')
                Util.request('topicReply.vote', replyId).done((response) => {
                    const $number = $voteAction.find('.number');

                    $number.html(response.vote_count);
                    if (response.vote_count > 0) {
                        $number.removeClass('hidden');
                    } else {
                        $number.addClass('hidden');
                    }
                    //已经投票的，变成可投票状态
                    if (response.is_voted) {
                        $icon.removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
                        $voteAction.data('voted', true);
                        //加一特效
                        // const $increase = $('<div class="one-increase">+1</div>');
                        // $increase.insertBefore($voteAction);
                        // $increase.addClass('fadeOutUp animated');

                    } else {
                        $icon.removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
                        $voteAction.data('voted', false);
                    }
                    $icon.addClass('wobble animated');
                }).fail((response) => {
                    Util.dialog.message(response.responseJSON.error).flash(3);
                }).always(()  => {
                    voteLock.release();
                });
            });


        });

    })();

})($);

/**
 * 添加topic
 */
(function($){
    const editorElement = document.getElementById("topic_originalBody");
    const $preview = $('[data-action="preview"]');
    const $previewPanel = $('[data-role="preview-panel"]');
    const $topicTitle = $('#topic_title');
    const $topicBody = $(editorElement);

    if (editorElement) {
        const editor = new CodeMirrorEditor($topicBody, $preview, $previewPanel);
        //提交表单
        $('#add-topic-form').on('submit', function(){
            if ($topicTitle.val().length === 0 || editor.getContent().length === 0) {
                Util.dialog.message(Translator.trans('topic.please_fill_in_blank')).flash();
                return false;
            }
            $topicBody.val(editor.getContent());
            
            store.remove('topic_draft');
        });

        //tags input
        const $topicThreads = $('#topic_threads');
        $topicThreads.selectize({
            valueField: 'name',
            labelField: 'name',
            searchField: 'name',
            create: true,
            createOnBlur: true,
            placeholder: Translator.trans('topic.add_topic'),
            maxItems: 5,
            load: function(query, callback){
                if (!query.length) return callback();
                Util.request('thread.autocomplete', {}, {'query': query}).done(function(response){
                    callback(response.threads.slice(0, 10));
                });
            }
        });
        const selectize = $topicThreads.get(0).selectize;
        //recommend thread
        $('#add-topic').find('[data-role="recommend-threads"] a').on('click', function(){
            const value = $(this).text();
            selectize.createItem(value);
            selectize.addItem(value, false);
        });
    }
})($);
