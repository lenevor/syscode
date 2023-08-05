(function(d) {
    /** 
     * CODE FOR SELECTION OF FRAMES 
     */

    /* IE8 Incompatibility crap */
    let elements = ['section', 'header', 'nav', 'footer'];

    for (let i = 0; i < elements.length; i++) {
        d.createElement(elements[i]);
    }

    /**
     * CODE FOR CONTROL OF ELEMENTS HTML IN THE HEADER
     */

    let header = d.querySelector('header');

    window.addEventListener('scroll', (e) => {
        if (d.documentElement.scrollTop > 10) {
            if (localStorage.getItem('dark-mode') === 'true') {
                header.style.background = '#1F2937';
                header.style.borderBottom = '1px solid rgba(36, 68, 86, 0.5)';
                header.style.boxShadow = '0 0 15px 4px rgba(0, 0, 0, 0.2)';
            } else {
                header.style.background = '#FFFFFF';
                header.style.borderBottom = 'none';
                header.style.boxShadow = '0 0 15px 4px rgba(0, 0, 0, 0.2)';
            }
        } else {
            if (localStorage.getItem('dark-mode') === 'true') {
                header.style.background = 'none';
                header.style.borderBottom = '1px solid rgba(31, 41, 51, 1)';
                header.style.boxShadow = 'none';
            } else {
                header.style.background = 'none';
                header.style.borderBottom = 'none';
                header.style.boxShadow = 'none';
            }
        }
    });

    /**
     * CODE FOR SELECTED THE FRAMES
     */

    var previousFrame = null;
    var previousInfo  = null;
    var allFrames     = d.querySelectorAll('.frame');
    var allFramesCode = d.querySelectorAll('.code-source');
    let evento        = ((document.ontouchstart !== null) ? 'mouseup' : 'touchstart');

    function changeTo(el) 
    {
        if (previousInfo) previousInfo.classList.remove("active");

        previousInfo = el;

        el.classList.add("active");
    }

    function selectFrameInfo(index)
    {
        var el = allFramesCode[index];

        if (el) {
            if (el.closest('[data-frame]')) {
                return changeTo(el);
            }
        }
    }

    for (let i = 0; i < allFrames.length; i++) {
        (function(i, el) {
            var el = allFrames[i];

            el.addEventListener(evento, (e) => {
                e.preventDefault();

                allFrames[0].classList.remove("active");
                allFramesCode[0].classList.remove("active");
                
                if (previousFrame) {
                    previousFrame.classList.remove("active");                    
                }
                
                el.classList.add("active");  
                      
                previousFrame = el;
                                
                selectFrameInfo(el.attributes["data-index"].value);
            });

        })(i);
    }

})(document);