'use strict';
(function() {
  const sp = document.querySelectorAll('a.share-popup');
  sp.forEach(function(i) {
    i.addEventListener('click', function(e) {
      window.open(event.currentTarget.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');
      e.preventDefault();
      return false;
    });
  });
})();
