if (document.readyState == 'complete' || document.readyState == 'loaded') {
    mtv_sticky_bar_check();
} else {
    window.addEventListener('DOMContentLoaded', mtv_sticky_bar_check);
}



/**
 * with this function we scroll to the disired element
 */

function mtv_scroll_to(element_selector, top) {
    var elements = document.querySelectorAll(element_selector);

    if (elements.length > 0) {
        var element = elements[0];
        var opos, otop;
        opos = otop = '';
        if (top != 0 && top != '') {
            opos = element.style.position;
            otop = element.style.top;
            element.style.position = 'relative';
            element.style.top = top + 'px';
        }
        window.scroll({ top: mtv_getOffsetTop(element), behavior: 'smooth' });
        if (top != 0 && top != '') {
            element.style.top = otop;
            element.style.position = opos;
        }
        return;
    }
    console.log(element_selector + ' don\'t exist');
}



/**
 * check if we have got sticky bars
 */
function mtv_sticky_bar_check() {
    var element = document.querySelector('#mtv-sticky-bar.mtv_is_sticky');
    if (element) {
        var top = element.offsetTop;
        var mtv_scroll_timer;
        var mtv_resize_check;

        function scroll(event) {
            var y = document['documentElement' || 'body'].scrollTop - top;
            var body = document.body;
            if (y > top) {
                if (!isScrolledIntoView(element, true) && !element.classList.contains('sticked')) {
                    element.classList.add('sticked');
                    body.classList.add('mtv-sticky-bar-sticked');
                }
            } else {
                element.classList.remove('sticked');
                body.classList.remove('mtv-sticky-bar-sticked');
            }
            mtv_attach_items();
        }

        window.addEventListener('scroll', function() {
            clearTimeout(mtv_scroll_timer);
            mtv_scroll_timer = setTimeout(scroll(), 100);
        });
        mtv_sticky_bar_width();
        window.onresize = function() {
            clearTimeout(mtv_resize_check);
            mtv_resize_check = setTimeout(mtv_sticky_bar_width(), 100);
        };


    }
}


function mtv_attach_items() {
    var element = document.querySelector('#mtv-sticky-bar.mtv_is_sticky');

    if (element) {
        if (element.classList.contains('sticked')) {
            var items_array = [];
            var top = document['documentElement' || 'body'].scrollTop;
            var items = element.querySelectorAll('.item');
            for (var i = 0; i < items.length; i++) {
                var selector = items[i].getAttribute('data-check');
                var offset = ~~items[i].getAttribute('data-offset');

                items[i].classList.remove('active');
                var selector_items = document.querySelectorAll(selector);
                for (var k = 0; k < selector_items.length; k++) {
                    var item = selector_items[k];
                    var item_top = ~~(item.offsetTop + offset);
                    if (top >= item_top && isScrolledIntoView(item, false)) {
                        items_array.push({ dist: ~~(top - item_top), item: items[i] });
                    }
                }
            }

            if (items_array.length > 0) {
                items_array.sort(compare_distances);
                items_array[0].item.classList.add('active');
                items_array[0].item.scrollIntoView({ inline: 'center' });

            }
        }
    }
}



function compare_distances(a, b) {
    if (a.dist === b.dist) {
        return 0;
    } else {
        return (a.dist < b.dist) ? -1 : 1;
    }
}


function isScrolledIntoView(el, allElement) {
    var rect = el.getBoundingClientRect();
    var elemTop = rect.top;
    var elemBottom = rect.bottom;

    // Only completely visible elements return true:
    var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
    // Partially visible elements return true:
    if (!allElement) {
        isVisible = elemTop < window.innerHeight && elemBottom >= 0;
    }
    return isVisible;
}



function scrollHrzntl(action, targetElement) {
    var element = document.querySelector('#mtv-sticky-bar.mtv_is_sticky');
    if (element) {
        var distance = -100;
        if (action == 'right') {
            distance = 100;
        }



        element.scrollBy({
            left: distance,
            behavior: 'smooth'
        });
    }
}

function mtv_sticky_bar_width() {
    var items_width = document.querySelector('.mtv-sticky-wrapper .nav_wrapper').offsetWidth;
    var wrapper_width = document.querySelector('#mtv-sticky-bar').offsetWidth;

    if (items_width > wrapper_width) {
        document.querySelector('#mtv-sticky-bar').classList.add('show-icons');
        return;
    }
    document.querySelector('#mtv-sticky-bar').classList.remove('show-icons');

}

const mtv_getOffsetTop = element => {
    let offsetTop = 0;
    while (element) {
        offsetTop += element.offsetTop;
        element = element.offsetParent;
    }
    return offsetTop;
}