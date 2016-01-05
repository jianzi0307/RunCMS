$("h1,h2,h3,h4,h5,a").removeAttr("id");

gajus.contents.formatId = function(str){
  if(/^[0-9]/.test(str)){
    str = "_"+str;
  }
  return str.replace(/ /g,'_').replace(/[^a-zA-Z_0-9\u4e00-\u9fa5]/g,'_');
};

var tocContents =gajus.contents({
  contents: document.querySelector('#toc-wrapper')
});

$('#toc-wrapper ol').first().attr('id','toc');
$('#toc').addClass('nav');

tocContents.eventProxy.on('ready', function () {
  doSideBar();
});

// Sidebar ScrollSpy
$.fn.scrollStopped = function(callback) {
  $(this).scroll(function() {
    var self = this, $this = $(self);
    if ($this.data('scrollTimeout')) {
      clearTimeout($this.data('scrollTimeout'));
    }
    $this.data('scrollTimeout', setTimeout(callback, 250, self));
  });
};

var SidebarAffixShadow = $('.sidebar-affix-shadow');
var updateSidebarAffixShadowWidth = function() {
  var tocWidth = $('#left-nav').width();
  SidebarAffixShadow.removeClass('bottom').addClass('on').attr('data-width', tocWidth);
  $('style[title=css-sidebar-affix-shadow-width]').remove();
  $('head').append('<style title=css-sidebar-affix-shadow-width>.sidebar-affix-shadow:before, .sidebar-affix-shadow:after {width: ' + tocWidth + 'px;}</style>');
  $('#toc-wrapper').width(tocWidth);
}

// Sidebar affix
var doSideBar = function(){
  $('.sidebar-wrapper').affix({
    offset: {
      top: 80
    , bottom: function () {
        return (this.bottom = $('.footer').outerHeight(true))
      }
    }
  })
  .on('affix.bs.affix', function (e) {
    updateSidebarAffixShadowWidth();

    // $(window).scroll(function() {
    //   if($(window).scrollTop() + $(window).height() > $(document).height() - $('.footer').outerHeight(true)) {
    //     // If window reaches bottom
    //     SidebarAffixShadow.addClass('bottom').removeClass('on');
    //   } else {
    //     // If user scrolls back
    //     SidebarAffixShadow.removeClass('bottom');
    //   }
    // });
  })
  .on('affix-top.bs.affix', function (e) {
    // If scrolls back to top
    $('#toc-wrapper').removeAttr('style');
    SidebarAffixShadow.removeClass('bottom on');
  })
  .on('affix-bottom.bs.affix', function (e) {
    // If window reaches bottom (Affix style)
    SidebarAffixShadow.addClass('bottom').removeClass('on');
  });
}

// Add a hover class to detect if users mouse is hovering over the sidebar
$(".sidebar-affix-shadow").hover(
  function() {
    $(this).removeClass("sidebar-hover-off");
  }, function() {
    $(this).addClass("sidebar-hover-off");
  }
);

// If the cursor is off the sidebar, scrolls to parent active heading
$(window).scrollStopped(function() {
  setTimeout(function() {
    $(".sidebar-affix-shadow.on.sidebar-hover-off .sidebar-wrapper").scrollTo($("#toc > li .active").first(), 800, {offset: -20});
    // console.log("Haven't scrolled in 250ms, fired in 250ms later.");
  }, 250);
});

$(function() {
  var arr = $('#toc ul').parents('li');
  angular.forEach(arr, function(v, k) {
    var a = $(v).children('a:first-child');
    a.addClass('has-subdoc-nav');
  });

  // Add GitHub links
    /*
  var currentPath = window.location.pathname.match(/.*\/(.+).html/i)[1];
  $("#content").prepend("<div class=docs-meta>\
      <span class='icon icon-github'></span>\
      <a href='http://github.com/leancloud/docs/blob/master/md/" + currentPath + ".md'>查看文件</a>\
      |\
      <a href='http://github.com/leancloud/docs/edit/master/md/" + currentPath + ".md'>编辑文件</a>\
    </div>");*/
  $(".sidebar-wrapper #toc").append("<li class=back-to-top><a href=#top>返回顶部</a></li>");

});


