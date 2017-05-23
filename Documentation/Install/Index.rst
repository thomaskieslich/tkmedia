.. include:: ../Includes.txt

Install and Setup
=================
Download from github: https://github.com/thomaskieslich/tkmedia

Composer

.. code-block:: text

   "repositories": [
      {
         "type": "git",
         "url": "https://github.com/thomaskieslich/tkmedia/"
      }
   ],
   "require": {
      "thomask/tkmedia": "dev-master as 8.7.0"
   }

Add TypoScript to root Template.

Add optional PageTS to remove unwanted fields (width, height, border, etc.)
