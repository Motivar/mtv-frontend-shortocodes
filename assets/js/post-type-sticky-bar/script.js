if (document.readyState == 'complete' || document.readyState == 'loaded') {
    mtv_sticky_bar_check();
} else {
    window.addEventListener('DOMContentLoaded', mtv_sticky_bar_check);
}



/**
 * with this function we scroll to the disired element
 */

function mtv_scroll_to(element_selector, top) {
    var element = document.querySelector(element_selector);
    if (element) {
        var opos, otop;
        opos = otop = '';
        if (top != 0 && top != '') {
            opos = element.style.position;
            otop = element.style.top;
            element.style.position = 'relative';
            element.style.top = top + 'px';
        }
        element.scrollIntoView({ behavior: "smooth", inline: "nearest", block: 'start' });
        if (top != 0 && top != '') {
            element.style.top = otop;
            element.style.position = opos;
        }
        return;
    }
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
            if (y > top) {
                if (!isScrolledIntoView(element, true) && !element.classList.contains('sticked')) {
                    element.classList.add('sticked');
                }
            } else {
                element.classList.remove('sticked');
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
            var top = document['documentElement' || 'body'].scrollTop;
            var items = element.querySelectorAll('.item');
            for (var i = 0; i < items.length; i++) {
                var selector = items[i].getAttribute('data-check');
                var item = document.querySelector(selector);
                var item_top = item.offsetTop;
                if (top >= item_top && isScrolledIntoView(item, false)) {
                    items[i].classList.add('active');
                } else {
                    items[i].classList.remove('active');
                }
            }
        }

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



function scrollHrzntl(action) {
    console.log(action);
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