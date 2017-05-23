
.. include:: ../Includes.txt

TypoScript
----------
You can override css and js in your site template. Defaults are:

.. code-block:: typoscript

   page {
     includeCSS {
       tkmedia = EXT:tkmedia/Resources/Public/Styles/plyr.css
     }

     includeJSFooter {
       tkmedia-plyr = EXT:tkmedia/Resources/Public/JavaScript/plyr.js
       tkmedia = EXT:tkmedia/Resources/Public/JavaScript/tkmedia.js
     }
   }

Override Partial Path:

.. code-block:: typoscript

   lib.contentElement {
     partialRootPaths {
       110 = EXT:tkmedia/Resources/Private/Partials/
     }
   }

Default Image Settings use zurb foundation.

For Bootstrap you can change columnPrefix to col-md-

.. code-block:: typoscript

   tt_content.textmedia {
     settings {
       columnPrefix {
         medium = medium-
       }

       srcset {
         small = 480
         medium = 960
         lightbox = 1920
       }

       sizes {
         medium = 40em
       }
     }
   }
