page {
  includeCSS {
    tkmedia = EXT:tkmedia/Resources/Public/Styles/plyr.css
  }

  includeJSFooter {
    tkmedia-plyr = EXT:tkmedia/Resources/Public/JavaScript/plyr.js
    tkmedia = EXT:tkmedia/Resources/Public/JavaScript/tkmedia.js
  }
}

lib.contentElement {
  partialRootPaths {
    99 = EXT:tkmedia/Resources/Private/Partials/
  }
}

lib.math = TEXT
lib.math {
  current = 1
  prioriCalc = 1
}

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

  dataProcessing {
    10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
    10 {
      references.fieldName = assets
    }

    20 = ThomasK\Tkmedia\DataProcessing\GalleryProcessor
    20 {
      maxGalleryWidth = {$styles.content.textmedia.maxW}
      maxGalleryWidthInText = {$styles.content.textmedia.maxWInText}
      columnSpacing = {$styles.content.textmedia.columnSpacing}
      borderWidth = {$styles.content.textmedia.borderWidth}
      borderPadding = {$styles.content.textmedia.borderPadding}
    }
  }
}
