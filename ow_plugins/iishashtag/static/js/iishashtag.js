/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */

var iishashtagListCache = {};
var iishashtagLastItems;
function iishashtag_wait_for_at() {
    var text_input_selector = '.ow_newsfeed_status_input, .comments_fake_autoclick';
    setInterval(function() {
        if(iishashtagLastItems != $(text_input_selector).length){
            iishashtagLastItems = $(text_input_selector).length;
            $(text_input_selector).suggest('#', {
                data: function (q) {
                    if(q.length <3 ) return [];
                    if (q in iishashtagListCache)
                        return iishashtagListCache[q];
                    var ret = $.getJSON(iishashtagLoadTagsUrl + q);
                    iishashtagListCache[q] = ret;
                    return ret;
                },
                map: function (item) {
                    return {
                        value: item.tag,
                        text: '<strong>' + item.tag + '</strong> (× <small>' + item.count + '</small>)'
                    }
                },
                position: "bottom"
            });
        }
    }, 1000);
}

$(function() {
    iishashtag_wait_for_at();
});