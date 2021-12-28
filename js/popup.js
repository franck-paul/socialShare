'use strict';

(() => {
  const sp = document.querySelectorAll('a.share-popup');
  sp.forEach((i) => {
    i.addEventListener('click', (e) => {
      window.open(event.currentTarget.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');
      e.preventDefault();
      return false;
    });
  });
})();
