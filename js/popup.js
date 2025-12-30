'use strict';

(() => {
  const links = document.querySelectorAll('a.share-popup');
  for (const link of links) {
    link.addEventListener('click', (event) => {
      window.open(event.currentTarget.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');
      event.preventDefault();
      return false;
    });
  }

  if ('share' in globalThis.navigator) {
    const shares = document.querySelectorAll('.share-menu');
    for (const share of shares) {
      const url = share?.dataset?.url;
      const title = share?.dataset?.title;
      share.hidden = false;
      share.addEventListener('click', async (event) => {
        event.preventDefault();
        try {
          await navigator.share({
            url,
            title,
          });
        } catch (err) {
          console.error(err);
        }
        return false;
      });
    }
  }
})();
